<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\Activity;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings board.
     */
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update global settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'payment_qr_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        // Handle QR Code Image Upload
        if ($request->hasFile('payment_qr_image')) {
            $file = $request->file('payment_qr_image');
            if ($file->isValid()) {
                try {
                    $filename = 'qr_' . time() . '.' . $file->getClientOriginalExtension();
                    $targetDir = public_path('images');

                    if (!file_exists($targetDir)) {
                        @mkdir($targetDir, 0755, true);
                    }

                    if (is_writable($targetDir)) {
                        $file->move($targetDir, $filename);
                        $savedPath = $filename;
                    } else {
                        // Fallback: Save to public storage disk (storage/app/public/images)
                        $path = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('images', $file, $filename);
                        $savedPath = 'storage/' . $path;
                    }

                    SystemSetting::updateOrCreate(
                        ['key' => 'payment_qr_path'],
                        ['value' => $savedPath, 'group' => 'general']
                    );
                } catch (\Exception $e) {
                    \Log::error('Settings QR Upload Error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Failed to save QR code image. Please check directory permissions.');
                }
            } else {
                return redirect()->back()->with('error', 'The uploaded QR code image is not valid.');
            }
        }

        Activity::log("Global system settings updated");

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    /**
     * Display the Razorpay credentials settings page.
     */
    public function razorpayIndex()
    {
        $settings = SystemSetting::whereIn('key', [
            'razorpay_key_id',
            'razorpay_key_secret',
            'razorpay_webhook_secret'
        ])->pluck('value', 'key');

        return view('settings.razorpay', compact('settings'));
    }

    /**
     * Update Razorpay credentials.
     */
    public function razorpayUpdate(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.razorpay_key_id' => 'nullable|string',
            'settings.razorpay_key_secret' => 'nullable|string',
            'settings.razorpay_webhook_secret' => 'nullable|string',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '', 'group' => 'razorpay']
            );
        }

        Activity::log("Razorpay credentials settings updated");

        return redirect()->back()->with('success', 'Razorpay credentials updated successfully.');
    }

    /**
     * Display the Mail / SMTP settings page.
     */
    public function mailIndex()
    {
        $settings = SystemSetting::whereIn('key', [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name'
        ])->pluck('value', 'key');

        return view('settings.mail', compact('settings'));
    }

    /**
     * Update Mail / SMTP configuration settings.
     */
    public function mailUpdate(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.mail_mailer' => 'required|string|in:smtp,sendmail,log',
            'settings.mail_host' => 'nullable|string',
            'settings.mail_port' => 'nullable|numeric',
            'settings.mail_username' => 'nullable|string',
            'settings.mail_password' => 'nullable|string',
            'settings.mail_encryption' => 'nullable|string|in:ssl,tls,null',
            'settings.mail_from_address' => 'nullable|email',
            'settings.mail_from_name' => 'nullable|string',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '', 'group' => 'mail']
            );
        }

        Activity::log("SMTP / Mail settings updated");

        return redirect()->back()->with('success', 'Mail / SMTP settings updated successfully.');
    }

    /**
     * Send a test email to verify SMTP configuration.
     */
    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $toEmail = $request->input('test_email');
            $appName = SystemSetting::get('mail_from_name', config('app.name', 'FeeEasy'));

            \Illuminate\Support\Facades\Mail::raw(
                "Hello!\n\nThis is a test email sent from {$appName} Admin Panel to verify that your SMTP / Mail configuration is functioning properly.\n\nSent at: " . now()->toDateTimeString(),
                function ($message) use ($toEmail, $appName) {
                    $message->to($toEmail)
                        ->subject("SMTP Test Email - {$appName}");
                }
            );

            Activity::log("Sent test email to {$toEmail}");

            return redirect()->back()->with('success', "Test email sent successfully to {$toEmail}!");
        } catch (\Throwable $e) {
            \Log::error('SMTP Test Mail Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
