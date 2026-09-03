<?php

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
        Schema::create('institute_white_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->unique()->constrained()->onDelete('cascade');

            // Purchase / order tracking (mirrors the Subscription Razorpay flow)
            $table->string('status')->default('pending'); // pending, active, cancelled
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->timestamp('purchased_at')->nullable();

            // Branding submitted by the institute once active
            $table->string('app_name')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('primary_color', 7)->nullable();
            $table->string('secondary_color', 7)->nullable();
            $table->string('android_package_id')->nullable();
            $table->string('ios_bundle_id')->nullable();

            // Ops review before the build is submitted to the app stores
            $table->timestamp('admin_confirmed_at')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_white_labels');
    }
};
