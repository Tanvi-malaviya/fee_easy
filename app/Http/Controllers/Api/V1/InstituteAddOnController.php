<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\InstituteAddOnPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstituteAddOnController extends Controller
{
    /**
     * List enabled add-ons + this institute's purchase status for
     * flag/quota kind ones. Custom-kind add-ons (e.g. White Label) are
     * listed but without an accurate purchased/active flag here — their
     * real status lives in their own dedicated table/endpoint
     * (InstituteWhiteLabelController), which the app deep-links to instead.
     */
    public function index(Request $request)
    {
        $institute = $request->user();

        $addOns = AddOn::where('enabled', true)->get();

        $purchases = InstituteAddOnPurchase::where('institute_id', $institute->id)
            ->whereIn('add_on_id', $addOns->pluck('id'))
            ->get()
            ->keyBy('add_on_id');

        $data = $addOns->map(function (AddOn $addOn) use ($purchases) {
            $purchase = $purchases->get($addOn->id);
            $array = $addOn->toApiArray();
            $array['kind'] = $addOn->kind;
            $array['purchased'] = $addOn->kind !== AddOn::KIND_CUSTOM && $purchase?->is_active === true;
            return $array;
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Create a Razorpay order for a flag/quota kind add-on.
     */
    public function createOrder(Request $request, AddOn $addOn)
    {
        $institute = $request->user();

        if ($addOn->kind === AddOn::KIND_CUSTOM) {
            return response()->json([
                'status' => 'error',
                'message' => 'This add-on has its own dedicated purchase flow.',
            ], 422);
        }

        if (!$addOn->enabled) {
            return response()->json([
                'status' => 'error',
                'message' => 'This add-on is not currently available.',
            ], 422);
        }

        $existing = InstituteAddOnPurchase::where('institute_id', $institute->id)
            ->where('add_on_id', $addOn->id)
            ->first();

        if ($existing && $existing->status === InstituteAddOnPurchase::STATUS_ACTIVE) {
            return response()->json([
                'status' => 'error',
                'message' => 'This add-on is already active for your institute.',
            ], 422);
        }

        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
            $api->setAppDetails('Tuoora', '1.0.0');

            $invoice = $api->invoice->create([
                'type' => 'invoice',
                'description' => $addOn->name,
                'customer' => [
                    'name' => $institute->institute_name,
                    'email' => $institute->email ?? 'test@example.com',
                    'contact' => $institute->phone ?? '9999999999',
                ],
                'line_items' => [
                    [
                        'name' => $addOn->name,
                        'amount' => $addOn->price * 100,
                        'currency' => 'INR',
                        'quantity' => 1,
                    ],
                ],
                'sms_notify' => 0,
                'email_notify' => 0,
                'currency' => 'INR',
                'notes' => [
                    'institute_id' => (string) $institute->id,
                    'add_on_id' => (string) $addOn->id,
                    'purpose' => 'add_on_purchase',
                ],
            ]);

            $orderId = $invoice['order_id'];

            InstituteAddOnPurchase::updateOrCreate(
                ['institute_id' => $institute->id, 'add_on_id' => $addOn->id],
                [
                    'status' => InstituteAddOnPurchase::STATUS_PENDING,
                    'amount' => $addOn->price,
                    'razorpay_order_id' => $orderId,
                ]
            );

            return response()->json([
                'status' => 'success',
                'data' => [
                    'order_id' => $orderId,
                    'amount' => $addOn->price * 100,
                    'currency' => 'INR',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('AddOn Razorpay Order Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
            ], 500);
        }
    }

    /**
     * Verify Razorpay payment and activate the add-on entitlement.
     */
    public function verifyPayment(Request $request, AddOn $addOn)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $institute = $request->user();
        $purchase = InstituteAddOnPurchase::where('institute_id', $institute->id)
            ->where('add_on_id', $addOn->id)
            ->first();

        if (!$purchase || $purchase->razorpay_order_id !== $request->razorpay_order_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed',
            ], 400);
        }

        $secret = config('services.razorpay.key_secret');
        $data = $request->razorpay_order_id . '|' . $request->razorpay_payment_id;
        $generatedSignature = hash_hmac('sha256', $data, $secret);

        if ($generatedSignature !== $request->razorpay_signature) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed',
            ], 400);
        }

        $purchase->update([
            'status' => InstituteAddOnPurchase::STATUS_ACTIVE,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
            'purchased_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "{$addOn->name} activated successfully.",
            'data' => $purchase->fresh(),
        ]);
    }
}
