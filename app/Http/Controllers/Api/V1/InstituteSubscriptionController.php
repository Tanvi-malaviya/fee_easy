<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Plan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class InstituteSubscriptionController extends Controller
{
    public function show(Request $request)
    {
        $subscription = $request->user()->currentSubscription();

        if (! $subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'No subscription found.',
            ], 404);
        }

        $subscription->load('payments');

        return response()->json([
            'status' => 'success',
            'data' => $subscription,
        ]);
    }

    public function renew(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string|unique:subscription_renewals,transaction_id',
            'screenshot'     => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'message'        => 'nullable|string|max:500',
        ]);

        $institute = $request->user();

        // Prevent duplicate pending submissions
        $existing = \App\Models\SubscriptionRenewal::where('institute_id', $institute->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already have a pending renewal request under review.',
            ], 400);
        }

        $path = null;
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('payment_proofs', 'public');
        }

        $renewal = \App\Models\SubscriptionRenewal::create([
            'institute_id' => $institute->id,
            'transaction_id' => $request->transaction_id,
            'screenshot' => $path,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your subscription renewal request has been submitted successfully. We will review and activate it shortly!',
            'data' => $renewal
        ]);
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $institute = $request->user();
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
            $api->setAppDetails('Tuoora', '1.0.0');

            $invoice = $api->invoice->create([
                'type'            => 'invoice',
                'description'     => 'Subscription for ' . $plan->name,
                'customer'        => [
                    'name'    => $institute->institute_name,
                    'email'   => $institute->email ?? 'test@example.com',
                    'contact' => $institute->phone ?? '9999999999'
                ],
                'line_items'      => [
                    [
                        'name'     => $plan->name,
                        'amount'   => $plan->price * 100, // in paise
                        'currency' => 'INR',
                        'quantity' => 1
                    ]
                ],
                'sms_notify'      => 0,
                'email_notify'    => 0,
                'currency'        => 'INR',
                'notes'           => [
                    'plan_id' => $plan->id,
                    'institute_id' => $institute->id,
                    'app_name' => 'Tuoora',
                    'app_id' => 'com.app.tuoora'
                ]
            ]);

            $orderId = $invoice['order_id'];

            // Save order ID for replay protection
            $institute->update(['razorpay_order_id' => $orderId]);

            return response()->json([
                'status' => 'success',
                'razorpay_order_id' => $orderId,
                'amount' => $plan->price,
                'plan_name' => $plan->name,
                'institute_name' => $institute->institute_name,
                'email' => $institute->email,
                'phone' => $institute->phone,
               'razorpay_key' => config('services.razorpay.key_id')
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay Order Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to initiate payment. Please try again.'
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
            'plan_id' => 'required|exists:plans,id',
            'razorpay_order_id' => 'required_without:razorpay_invoice_id',
            'razorpay_invoice_id' => 'nullable',
        ]);

        $api = new \Razorpay\Api\Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        try {
            $invoiceId = $request->razorpay_invoice_id;

            if (empty($invoiceId)) {
                // If invoice_id is not passed, fetch payment details from Razorpay to check if it's an invoice payment
                $payment = $api->payment->fetch($request->razorpay_payment_id);
                $invoiceId = $payment['invoice_id'] ?? null;
            }

            if (!empty($invoiceId)) {
                // Securely fetch invoice details from Razorpay API to get correct receipt/status and linked order_id
                $invoice = $api->invoice->fetch($invoiceId);
                $invoiceOrderId = $invoice['order_id'];
                
                $attributes = [
                    'razorpay_payment_link_id' => $invoiceId,
                    'razorpay_payment_link_reference_id' => $invoice['receipt'] ?? '',
                    'razorpay_payment_link_status' => $invoice['status'] ?? 'paid',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];

                $orderIdToCheck = $request->razorpay_order_id ?? $invoiceOrderId;
            } else {
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];
                $orderIdToCheck = $request->razorpay_order_id;
            }

            $api->utility->verifyPaymentSignature($attributes);

            // Payment verified, now create/update subscription
            $institute = $request->user();
            $plan = \App\Models\Plan::findOrFail($request->plan_id);

            // Replay protection check
            if ($institute->razorpay_order_id !== $orderIdToCheck) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment verification failed: Order ID mismatch'
                ], 400);
            }

            $activeSub = $institute->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>', Carbon::now())
                ->latest('end_date')
                ->first();

            $startDate = $activeSub ? $activeSub->end_date : Carbon::now();
            $endDate = $startDate->copy()->addDays($plan->duration_days);

            $subscription = Subscription::create([
                'institute_id' => $institute->id,
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'plan_id' => $plan->id,
                'razorpay_order_id' => $orderIdToCheck,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'platform' => 'web',
            ]);

            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_gateway' => 'razorpay',
                'payment_source' => 'web',
                'transaction_id' => $request->razorpay_payment_id,
                'paid_at' => Carbon::now(),
            ]);

            // Clear the razorpay_order_id to prevent replay
            $institute->update(['razorpay_order_id' => null]);

            // Send email invoice/receipt to user
            try {
                if ($institute->email) {
                    \Illuminate\Support\Facades\Mail::to($institute->email)->send(new \App\Mail\SubscriptionStatusMail(
                        $institute->institute_name,
                        $plan->name,
                        $endDate->toDateString(),
                        $plan->price,
                        'online_paid'
                    ));
                }
            } catch (\Exception $mailEx) {
                \Illuminate\Support\Facades\Log::error('Razorpay verify payment mail error: ' . $mailEx->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payment verified and subscription activated successfully.',
                'data' => $subscription
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay Verify Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Create Razorpay Order for Android.
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $institute = $request->user();
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
            $api->setAppDetails('Tuoora', '1.0.0');

            $invoice = $api->invoice->create([
                'type'            => 'invoice',
                'description'     => 'Subscription for ' . $plan->name,
                'customer'        => [
                    'name'    => $institute->institute_name,
                    'email'   => $institute->email ?? 'test@example.com',
                    'contact' => $institute->phone ?? '9999999999'
                ],
                'line_items'      => [
                    [
                        'name'     => $plan->name,
                        'amount'   => $plan->price * 100, // in paise
                        'currency' => 'INR',
                        'quantity' => 1
                    ]
                ],
                'sms_notify'      => 0,
                'email_notify'    => 0,
                'currency'        => 'INR',
                'notes'           => [
                    'plan_id'      => (string)$plan->id,
                    'institute_id' => (string)$institute->id,
                    'app_name'     => 'Tuoora',
                    'app_id'       => 'com.app.tuoora'
                ]
            ]);

            $orderId = $invoice['order_id'];

            // Save order ID for replay protection
            $institute->update(['razorpay_order_id' => $orderId]);

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $orderId,
                    'amount' => $plan->price * 100, // in paise
                    'currency' => 'INR',
                    'plan_name' => $plan->name,
                    'plan_id' => (int)$plan->id
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay Android Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order'
            ]);
        }
    }

    /**
     * Verify Razorpay Payment for Android.
     */
    public function verifyPaymentAndroid(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $institute = $request->user();
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        // 1. Verify signature
        $secret = config('services.razorpay.key_secret');
        $data = $request->razorpay_order_id . '|' . $request->razorpay_payment_id;
        $generatedSignature = hash_hmac('sha256', $data, $secret);

        if ($generatedSignature !== $request->razorpay_signature) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);
        }

        // 2. Replay protection check
        if ($institute->razorpay_order_id !== $request->razorpay_order_id) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);
        }

        // 3. Activate subscription
        $activeSub = $institute->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', Carbon::now())
            ->latest('end_date')
            ->first();

        $startDate = $activeSub ? $activeSub->end_date : Carbon::now();
        $endDate = $startDate->copy()->addDays($plan->duration_days);

        $subscription = Subscription::create([
            'institute_id' => $institute->id,
            'plan_name' => $plan->name,
            'amount' => $plan->price,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'plan_id' => $plan->id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
            'platform' => 'android',
        ]);

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'amount' => $plan->price,
            'payment_gateway' => 'razorpay',
            'payment_source' => 'android',
            'transaction_id' => $request->razorpay_payment_id,
            'paid_at' => Carbon::now(),
        ]);

        // Clear the razorpay_order_id against the institute to prevent replays
        $institute->update(['razorpay_order_id' => null]);

        // Send email invoice/receipt to user
        try {
            if ($institute->email) {
                \Illuminate\Support\Facades\Mail::to($institute->email)->send(new \App\Mail\SubscriptionStatusMail(
                    $institute->institute_name,
                    $plan->name,
                    $endDate->toDateString(),
                    $plan->price,
                    'online_paid'
                ));
            }
        } catch (\Exception $mailEx) {
            \Illuminate\Support\Facades\Log::error('Razorpay Android verify payment mail error: ' . $mailEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription activated successfully'
        ]);
    }

        /**
    * Verify In-App Purchase receipt for iOS and Android.
    */
    public function verifyIap(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'transaction_id' => 'required|string',
            'receipt_data' => 'required|string',
            'platform' => 'required|in:ios,android',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $platform = $request->platform;
        $receiptData = $request->receipt_data;
        $transactionId = $request->transaction_id;

        if ($platform === 'ios') {
            $appleSecret = config('services.google.apple_shared_secret');
            $endpoint = config('app.env') === 'production'
                ? 'https://buy.itunes.apple.com/verifyReceipt'
                : 'https://sandbox.itunes.apple.com/verifyReceipt';

            $appleResponse = Http::post($endpoint, [
                'receipt-data' => $receiptData,
                'password' => $appleSecret,
                'exclude-old-transactions' => true,
            ]);

            if ($appleResponse->failed()) {
                Log::error('Apple verification failed', ['response' => $appleResponse->body()]);
                return response()->json(['message' => 'Invalid/tampered receipt'], 422);
            }

            $data = $appleResponse->json();
            if (($data['status'] ?? null) !== 0) {
                return response()->json(['message' => 'Invalid/tampered receipt'], 422);
            }

            $inApp = collect($data['receipt']['in_app'] ?? []);
            $matched = $inApp->firstWhere('transaction_id', $transactionId);
            if (! $matched || ($matched['product_id'] ?? '') !== 'com.tuoora.plan' . $plan->id) {
                return response()->json(['message' => 'Invalid receipt details'], 422);
            }

            // Idempotency check
            $existing = Subscription::where('apple_transaction_id', $transactionId)->first();
            if ($existing) {
                return response()->json([
                    'message' => 'Already activated (duplicate)',
                    'subscription' => $this->formatSubscription($existing),
                ], 200);
            }

            // Activate subscription
            $start = Carbon::now();
            $end = $start->copy()->addDays($plan->duration_days);
            $subscription = Subscription::create([
                'institute_id' => $request->user()->id,
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => $start,
                'end_date' => $end,
                'status' => Subscription::STATUS_ACTIVE,
                'apple_transaction_id' => $transactionId,
            ]);
        } else {
            // Android flow
            $packageName = config('services.google.package_name');
            $productId = 'com.tuoora.plan' . $plan->id;
            $url = "https://androidpublisher.googleapis.com/androidpublisher/v3/applications/{$packageName}/purchases/products/{$productId}/tokens/{$receiptData}";
            $token = $this->getGoogleAccessToken();
            $googleResponse = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
            ])->get($url);

            if ($googleResponse->failed()) {
                Log::error('Google verification failed', ['response' => $googleResponse->body()]);
                return response()->json(['message' => 'Invalid/tampered receipt'], 422);
            }

            $data = $googleResponse->json();
            if (($data['purchaseState'] ?? null) != 0) {
                return response()->json(['message' => 'Invalid receipt details'], 422);
            }
            if (($data['productId'] ?? '') !== $productId || ($data['orderId'] ?? '') !== $transactionId) {
                return response()->json(['message' => 'Invalid receipt details'], 422);
            }

            // Idempotency check
            $existing = Subscription::where('google_order_id', $transactionId)->first();
            if ($existing) {
                return response()->json([
                    'message' => 'Already activated (duplicate)',
                    'subscription' => $this->formatSubscription($existing),
                ], 200);
            }

            // Acknowledge purchase
            $ackUrl = "https://androidpublisher.googleapis.com/androidpublisher/v3/applications/{$packageName}/purchases/products/{$productId}/tokens/{$receiptData}:acknowledge";
            Http::withHeaders([
                'Authorization' => "Bearer {$token}",
            ])->post($ackUrl, []);

            // Activate subscription
            $start = Carbon::now();
            $end = $start->copy()->addDays($plan->duration_days);
            $subscription = Subscription::create([
                'institute_id' => $request->user()->id,
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => $start,
                'end_date' => $end,
                'status' => Subscription::STATUS_ACTIVE,
                'google_order_id' => $transactionId,
            ]);
        }

        return response()->json([
            'message' => 'Subscription activated successfully',
            'subscription' => $this->formatSubscription($subscription),
        ], 200);
    }

    /**
     * Helper to format subscription response.
     */
    protected function formatSubscription(Subscription $sub)
    {
        return [
            'plan_name' => $sub->plan_name,
            'status' => $sub->status,
            'expires_at' => $sub->end_date,
            'students_enrolled' => \App\Models\Student::where('institute_id', $sub->institute_id)->count(),
            'student_limit' => $sub->plan_name ? \App\Models\Plan::where('name', $sub->plan_name)->value('student_limit') : null,
        ];
    }

    /**
     * Obtain Google Service Account access token.
     */
    protected function getGoogleAccessToken()
    {
        $serviceAccountPath = config('services.google.service_account_json');
        $resolvedPath = str_starts_with($serviceAccountPath, '/') || str_contains($serviceAccountPath, ':\\') 
            ? $serviceAccountPath 
            : base_path($serviceAccountPath);
        $json = json_decode(file_get_contents($resolvedPath), true);
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $now = time();
        $jwtPayload = base64_encode(json_encode([
            'iss' => $json['client_email'],
            'scope' => 'https://www.googleapis.com/auth/androidpublisher',
            'aud' => $json['token_uri'],
            'exp' => $now + 3600,
            'iat' => $now,
        ]));
        $signature = '';
        openssl_sign("{$jwtHeader}.{$jwtPayload}", $signature, $json['private_key'], OPENSSL_ALGO_SHA256);
        $jwt = "{$jwtHeader}.{$jwtPayload}." . base64_encode($signature);
        $tokenResponse = Http::asForm()->post($json['token_uri'], [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);
        return $tokenResponse->json()['access_token'] ?? '';
    


        }
    public function history(Request $request)
    {
        $subscriptions = $request->user()->subscriptions()
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $subscriptions->items(),
            'meta' => [
                'current_page' => $subscriptions->currentPage(),
                'last_page' => $subscriptions->lastPage(),
                'total' => $subscriptions->total(),
            ]
        ]);
    }

    public function allData(Request $request)
    {
        $institute = $request->user();

        // 1. Current Subscription
        $subscription = $institute->activeSubscription();

        // Institute-level effective status (active / expire_soon / expired /
        // cancelled / pending / rejected) with pending days for "expire soon".
        $subscriptionStatus = $institute->subscriptionStatus();

        $enrolledCount = \App\Models\Student::where('institute_id', $institute->id)->count();

        // 2. Plans
        $plans = \App\Models\Plan::where('status', 1)
            ->where('price', '>', 0)
            ->where('name', 'not like', '%free%')
            ->get()
            ->map(function($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'duration_days' => $plan->duration_days,
                    'status' => $plan->status,
                    'created_at' => $plan->created_at,
                    'updated_at' => $plan->updated_at,
                ];
            });

        // 3. History
        $history = $institute->subscriptions()
            ->latest()
            ->take(10)
            ->get();
            
        // 4. Payment Settings for Offline Renewal
        $paymentSettings = [
            'bank_holder_name' => \App\Models\SystemSetting::get('bank_holder_name', 'Tuoora Education'),
            'bank_name'        => \App\Models\SystemSetting::get('bank_name', 'HDFC Bank'),
            'bank_account'     => \App\Models\SystemSetting::get('bank_account_number', '—'),
            'bank_ifsc'        => \App\Models\SystemSetting::get('bank_ifsc', '—'),
            'upi_id'           => \App\Models\SystemSetting::get('payment_upi_id', '—'),
            'qr_path'          => \App\Models\SystemSetting::get('payment_qr_path', 'payment_qr_code.png'),
            'qr_url'           => \App\Models\SystemSetting::getQrUrl(),
        ];

        $currency = \App\Models\SystemSetting::get('currency_symbol', '₹');
        $whiteLabelPrice = (float) \App\Models\SystemSetting::get('mobile_app_whitelabel_price', 5000);
        $whiteLabelTitle = \App\Models\SystemSetting::get('mobile_app_whitelabel_title', 'Mobile App White Label');
        $whiteLabelBillingType = \App\Models\SystemSetting::get('mobile_app_whitelabel_billing_type', 'One Time');
        $whiteLabelEnabled = (bool) \App\Models\SystemSetting::get('mobile_app_whitelabel_enabled', true);
        $whiteLabelDesc = \App\Models\SystemSetting::get('mobile_app_whitelabel_description', 'Custom branded Android & iOS Mobile Application with your institute logo, colors, and name published on Google Play Store & Apple App Store.');

        $whiteLabelAddon = [
            'id' => 'mobile_app_whitelabel',
            'name' => $whiteLabelTitle,
            'title' => $whiteLabelTitle,
            'price' => $whiteLabelPrice,
            'billing_type' => $whiteLabelBillingType,
            'type' => 'one_time',
            'currency' => $currency,
            'formatted_price' => $currency . number_format($whiteLabelPrice),
            'description' => $whiteLabelDesc,
            'features' => [
                'Institute Name & Logo on Google Play Store & Apple App Store',
                'Direct Store Download Links & Shareable Marketing QR',
                'Push Notifications with Institute Branding',
                'Continuous App Store Maintenance & Support'
            ],
            'is_active' => $whiteLabelEnabled,
        ];

        $currentSub = $institute->currentSubscription();

        return response()->json([
            'status' => 'success',
            'data' => [
                'subscription' => $subscription ? [
                    'plan_name' => $subscription->plan_name,
                    'price' => $subscription->amount,
                    'status' => $subscriptionStatus['status'],
                    'status_label' => $subscriptionStatus['label'],
                    'pending_days' => $subscriptionStatus['days_left'],
                    'expires_at' => $subscription->end_date,
                    'students_enrolled' => $enrolledCount,
                ] : [
                    'plan_name' => $subscriptionStatus['plan_name'] ?? 'No Active Plan',
                    'price' => $currentSub ? $currentSub->amount : 0,
                    'status' => $subscriptionStatus['status'],
                    'status_label' => $subscriptionStatus['label'],
                    'pending_days' => $subscriptionStatus['days_left'],
                    'expires_at' => $subscriptionStatus['end_date'],
                    'students_enrolled' => $enrolledCount,
                ],
                'plans' => $plans,
                'addons' => [
                    $whiteLabelAddon
                ],
                'white_label_addon' => $whiteLabelAddon,
                'history' => $history,
                'payment_settings' => $paymentSettings
            ]
        ]);
    }

    public function handleWebhook(Request $request)
    {
        $signature = $request->header('X-Razorpay-Signature');
        $webhookSecret = config('services.razorpay.webhook_secret');

        if (!$signature) {
            Log::error('Razorpay Webhook Error: Signature header missing');
            return response()->json([
                'success' => false,
                'message' => 'Signature missing'
            ], 400);
        }

        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
            $api->utility->verifyWebhookSignature($request->getContent(), $signature, $webhookSecret);
        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            Log::error('Razorpay Webhook Signature Verification Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Signature verification failed'
            ], 400);
        }

        $payload = json_decode($request->getContent(), true);
        $event = $payload['event'] ?? null;

        if ($event !== 'payment.captured') {
            return response()->json([
                'success' => true,
                'message' => 'Event ignored'
            ]);
        }

        $paymentEntity = $payload['payload']['payment']['entity'] ?? null;
        if (!$paymentEntity) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid webhook payload structure'
            ], 400);
        }

        $orderId = $paymentEntity['order_id'] ?? null;
        $paymentId = $paymentEntity['id'] ?? null;

        if (!$orderId || !$paymentId) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID or Payment ID missing in payload'
            ], 400);
        }

        // Idempotency: Check if the subscription for this order is already active/created
        $existing = Subscription::where('razorpay_order_id', $orderId)->first();
        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Subscription already active (idempotent)'
            ]);
        }

        try {
            // Fetch order details via SDK to read notes safely
            $order = $api->order->fetch($orderId);
            $planId = $order->notes->plan_id ?? null;
            $instituteId = $order->notes->institute_id ?? null;

            if (!$planId || !$instituteId) {
                Log::error("Razorpay Webhook: plan_id or institute_id missing in order {$orderId} notes.");
                return response()->json([
                    'success' => false,
                    'message' => 'Missing plan_id or institute_id in order notes'
                ], 400);
            }

            $institute = \App\Models\Institute::find($instituteId);
            $plan = \App\Models\Plan::find($planId);

            if (!$institute || !$plan) {
                Log::error("Razorpay Webhook: Institute ({$instituteId}) or Plan ({$planId}) not found.");
                return response()->json([
                    'success' => false,
                    'message' => 'Institute or Plan not found'
                ], 404);
            }

            // Calculate subscription dates
            $activeSub = $institute->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>', Carbon::now())
                ->latest('end_date')
                ->first();

            $startDate = $activeSub ? $activeSub->end_date : Carbon::now();
            $endDate = $startDate->copy()->addDays($plan->duration_days);

            $subscription = Subscription::create([
                'institute_id' => $institute->id,
                'plan_name' => $plan->name,
                'amount' => $plan->price,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'plan_id' => $plan->id,
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
                'platform' => 'webhook',
            ]);

            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_gateway' => 'razorpay',
                'payment_source' => 'webhook',
                'transaction_id' => $paymentId,
                'paid_at' => Carbon::now(),
            ]);

            // Clear the razorpay_order_id to prevent replay
            $institute->update(['razorpay_order_id' => null]);

            // Send email invoice/receipt to user
            try {
                if ($institute->email) {
                    \Illuminate\Support\Facades\Mail::to($institute->email)->send(new \App\Mail\SubscriptionStatusMail(
                        $institute->institute_name,
                        $plan->name,
                        $endDate->toDateString(),
                        $plan->price,
                        'online_paid'
                    ));
                }
            } catch (\Exception $mailEx) {
                Log::error('Razorpay webhook payment mail error: ' . $mailEx->getMessage());
            }

            Log::info("Razorpay Webhook: Subscription successfully activated for Institute {$instituteId}, Plan {$planId}.");

            return response()->json([
                'success' => true,
                'message' => 'Subscription activated successfully via webhook',
                'data' => [
                    'subscription_id' => $subscription->id,
                    'plan_name' => $plan->name
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Razorpay Webhook Activation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process webhook activation: ' . $e->getMessage()
            ], 500);
        }
    }
}
