<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\InstituteWhiteLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstituteWhiteLabelController extends Controller
{
    /**
     * Current institute's white-label purchase status + submitted branding.
     */
    public function show(Request $request)
    {
        $institute = $request->user();
        $record = $institute->whiteLabel;

        return response()->json([
            'status' => 'success',
            'data' => [
                'purchased' => (bool) $record,
                'record' => $record,
                'addon' => $this->addonConfig(),
            ],
        ]);
    }

    /**
     * Create a Razorpay order for the White Label add-on (one-time purchase).
     */
    public function createOrder(Request $request)
    {
        $institute = $request->user();

        $addOn = AddOn::whiteLabel();

        if (!$addOn || !$addOn->enabled) {
            return response()->json([
                'status' => 'error',
                'message' => 'White Label add-on is not currently available.',
            ], 422);
        }

        $existing = $institute->whiteLabel;
        if ($existing && $existing->status === InstituteWhiteLabel::STATUS_ACTIVE) {
            return response()->json([
                'status' => 'error',
                'message' => 'White Label is already active for your institute.',
            ], 422);
        }

        $price = $addOn->price;

        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
            $api->setAppDetails('Tuoora', '1.0.0');

            $invoice = $api->invoice->create([
                'type' => 'invoice',
                'description' => 'Mobile App White Label add-on',
                'customer' => [
                    'name' => $institute->institute_name,
                    'email' => $institute->email ?? 'test@example.com',
                    'contact' => $institute->phone ?? '9999999999',
                ],
                'line_items' => [
                    [
                        'name' => $addOn->name,
                        'amount' => $price * 100,
                        'currency' => 'INR',
                        'quantity' => 1,
                    ],
                ],
                'sms_notify' => 0,
                'email_notify' => 0,
                'currency' => 'INR',
                'notes' => [
                    'institute_id' => (string) $institute->id,
                    'purpose' => 'white_label_addon',
                ],
            ]);

            $orderId = $invoice['order_id'];

            $record = InstituteWhiteLabel::updateOrCreate(
                ['institute_id' => $institute->id],
                [
                    'status' => InstituteWhiteLabel::STATUS_PENDING,
                    'amount' => $price,
                    'razorpay_order_id' => $orderId,
                ]
            );

            return response()->json([
                'status' => 'success',
                'data' => [
                    'order_id' => $orderId,
                    'amount' => $price * 100,
                    'currency' => 'INR',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('White Label Razorpay Order Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
            ], 500);
        }
    }

    /**
     * Verify Razorpay payment and activate the add-on.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $institute = $request->user();
        $record = $institute->whiteLabel;

        if (!$record || $record->razorpay_order_id !== $request->razorpay_order_id) {
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

        $record->update([
            'status' => InstituteWhiteLabel::STATUS_ACTIVE,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
            'purchased_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'White Label add-on activated successfully.',
            'data' => $record->fresh(),
        ]);
    }

    /**
     * Institute submits their desired app name / logo / colors, once active.
     * Reviewed by ops (admin_confirmed_at) before the build is submitted to
     * the app stores — this endpoint does not trigger any build/deploy step.
     */
    public function updateBranding(Request $request)
    {
        $institute = $request->user();
        $record = $institute->whiteLabel;

        if (!$record || $record->status !== InstituteWhiteLabel::STATUS_ACTIVE) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase the White Label add-on before submitting branding.',
            ], 422);
        }

        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'app_logo' => 'nullable|image|max:5120',
            'primary_color' => 'nullable|regex:/^#?[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|regex:/^#?[0-9A-Fa-f]{6}$/',
        ]);

        $data = [
            'app_name' => $validated['app_name'],
            'primary_color' => $this->normalizeHex($validated['primary_color'] ?? null),
            'secondary_color' => $this->normalizeHex($validated['secondary_color'] ?? null),
            // Re-opens ops review whenever the institute changes their branding.
            'admin_confirmed_at' => null,
        ];

        if ($request->hasFile('app_logo')) {
            if ($record->app_logo && Storage::disk('public')->exists($record->app_logo)) {
                Storage::disk('public')->delete($record->app_logo);
            }
            $data['app_logo'] = $request->file('app_logo')->store('white_labels/logos', 'public');
        }

        $record->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Branding submitted. Our team will confirm before publishing your app.',
            'data' => $record->fresh(),
        ]);
    }

    private function normalizeHex(?string $color): ?string
    {
        if (!$color) return null;
        return '#' . ltrim($color, '#');
    }

    private function addonConfig(): array
    {
        return AddOn::whiteLabel()?->toApiArray() ?? [
            'id' => AddOn::SLUG_WHITE_LABEL,
            'title' => 'Mobile App White Label',
            'description' => '',
            'price' => 5000,
            'formatted_price' => '₹5,000',
            'billing_type' => 'One Time',
            'enabled' => false,
            'features' => [],
        ];
    }
}
