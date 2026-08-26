<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SystemSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaultMailSettings = [
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '465',
            'mail_username' => 'tanvi.sathwarainfotech@gmail.com',
            'mail_password' => 'snrchdpaijehqkrt',
            'mail_encryption' => 'ssl',
            'mail_from_address' => 'tanvi.sathwarainfotech@gmail.com',
            'mail_from_name' => config('app.name', 'FeeEasy'),
        ];

        foreach ($defaultMailSettings as $key => $value) {
            SystemSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'mail']
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SystemSetting::where('group', 'mail')->delete();
    }
};
