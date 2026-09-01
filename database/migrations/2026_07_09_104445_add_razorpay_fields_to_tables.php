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
        Schema::table('institutes', function (Blueprint $table) {
            if (!Schema::hasColumn('institutes', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('status');
            }
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'plan_id')) {
                $table->unsignedBigInteger('plan_id')->nullable()->after('institute_id');
            }
            if (!Schema::hasColumn('subscriptions', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('google_order_id');
            }
            if (!Schema::hasColumn('subscriptions', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            }
            if (!Schema::hasColumn('subscriptions', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            }
            if (!Schema::hasColumn('subscriptions', 'platform')) {
                $table->string('platform')->nullable()->after('razorpay_signature');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['razorpay_order_id']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'plan_id',
                'razorpay_order_id',
                'razorpay_payment_id',
                'razorpay_signature',
                'platform',
            ]);
        });
    }
};
