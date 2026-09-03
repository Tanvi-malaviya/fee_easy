<?php

use App\Models\SystemSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            // Display label only (e.g. "One Time", "Yearly") — no recurring
            // billing automation exists anywhere in this app, this doesn't add any.
            $table->string('billing_type')->default('One Time');
            // flag: purchase grants a yes/no entitlement.
            // quota: purchase sets a named numeric limit (quota_key/quota_value).
            // custom: needs bespoke activation code (e.g. White Label's branding
            // review flow) — the catalog only manages its pricing/listing.
            $table->enum('kind', ['flag', 'quota', 'custom'])->default('flag');
            $table->string('quota_key')->nullable();
            $table->decimal('quota_value', 10, 2)->nullable();
            $table->json('features')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Carry over the currently-configured White Label pricing so nothing
        // resets — this becomes the one and only source for it going forward.
        $exists = \DB::table('add_ons')->where('slug', 'mobile_app_whitelabel')->exists();
        if (!$exists) {
            \DB::table('add_ons')->insert([
                'slug' => 'mobile_app_whitelabel',
                'name' => SystemSetting::get('mobile_app_whitelabel_title', 'Mobile App White Label'),
                'description' => SystemSetting::get(
                    'mobile_app_whitelabel_description',
                    'Custom branded Android & iOS Mobile Application with your institute logo, colors, and name published on Google Play Store & Apple App Store.'
                ),
                'price' => (float) SystemSetting::get('mobile_app_whitelabel_price', 5000),
                'billing_type' => SystemSetting::get('mobile_app_whitelabel_billing_type', 'One Time'),
                'kind' => 'custom',
                'features' => json_encode([
                    'Institute Name & Logo on Google Play Store & Apple App Store',
                    'Direct Store Download Links & Shareable Marketing QR',
                    'Push Notifications with Institute Branding',
                    'Continuous App Store Maintenance & Support',
                ]),
                'enabled' => (bool) SystemSetting::get('mobile_app_whitelabel_enabled', true),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_ons');
    }
};
