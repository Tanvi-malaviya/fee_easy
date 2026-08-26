<?php

namespace App\Services;

use App\Models\Institute;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Illuminate\Mail\Mailer as LaravelMailer;
use Symfony\Component\Mailer\Transport;

class InstituteMailService
{
    /**
     * Send email using Institute's custom SMTP if configured, otherwise fallback to system default SMTP.
     *
     * @param Institute|null $institute
     * @param mixed $to (Email string or array of emails)
     * @param \Illuminate\Mail\Mailable $mailable
     * @return mixed
     */
    public static function send(?Institute $institute, $to, $mailable)
    {
        if ($institute && $institute->hasCustomSmtp()) {
            try {
                $mailer = static::createInstituteMailer($institute);
                return $mailer->to($to)->send($mailable);
            } catch (\Throwable $e) {
                Log::warning("Institute [ID: {$institute->id} - {$institute->name}] custom SMTP failed: " . $e->getMessage() . ". Falling back to default system SMTP.");
                // Automatic fallback to system default SMTP
                return Mail::to($to)->send($mailable);
            }
        }

        // Default system SMTP
        return Mail::to($to)->send($mailable);
    }

    /**
     * Build a dedicated Laravel Mailer instance configured for the Institute's custom SMTP.
     */
    public static function createInstituteMailer(Institute $institute): LaravelMailer
    {
        $host = trim($institute->mail_host);
        $port = (int) ($institute->mail_port ?: 587);
        $encryption = strtolower(trim($institute->mail_encryption ?: 'tls'));
        $username = trim($institute->mail_username);
        $password = $institute->mail_password;

        $isTls = ($encryption === 'ssl' || $port === 465);
        $transport = new EsmtpTransport($host, $port, $isTls);
        
        if (!empty($username)) {
            $transport->setUsername($username);
            $transport->setPassword($password);
        }

        $fromAddress = $institute->mail_from_address ?: ($institute->email ?: config('mail.from.address'));
        $fromName = $institute->mail_from_name ?: ($institute->institute_name ?: ($institute->name ?: config('mail.from.name')));

        $laravelMailer = new LaravelMailer(
            "institute_{$institute->id}",
            app('view'),
            $transport,
            app('events')
        );

        $laravelMailer->alwaysFrom($fromAddress, $fromName);

        return $laravelMailer;
    }

    /**
     * Test SMTP connectivity and send a verification email.
     */
    public static function testConnection(Institute $institute, string $testRecipient): array
    {
        if (empty($institute->mail_host) || empty($institute->mail_username) || empty($institute->mail_password)) {
            return [
                'status' => 'error',
                'message' => 'Please fill in Host, Username, and Password to test SMTP.'
            ];
        }

        try {
            $mailer = static::createInstituteMailer($institute);
            
            $instituteName = $institute->institute_name ?: ($institute->name ?: 'Institute');
            
            $mailer->raw(
                "Hello,\n\nThis is a confirmation that your custom SMTP settings for '{$instituteName}' on Tuoora are working perfectly.\n\nTime: " . date('Y-m-d H:i:s'),
                function ($message) use ($testRecipient, $instituteName) {
                    $message->to($testRecipient)
                            ->subject("SMTP Test Successful - {$instituteName}");
                }
            );

            return [
                'status' => 'success',
                'message' => "Test email successfully sent to {$testRecipient} via your custom SMTP!"
            ];
        } catch (\Throwable $e) {
            Log::error("SMTP Test Failed for Institute [{$institute->id}]: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'SMTP Connection failed: ' . $e->getMessage()
            ];
        }
    }
}
