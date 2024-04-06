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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('razorpay_order_id')->nullable()->after('payment_type');
            $table->string('currency',20)->nullable()->after('razorpay_order_id');
            $table->text('checkout_order_id')->nullable()->after('currency');
            $table->longText('razorpay_signature')->nullable()->after('checkout_order_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('razorpay_order_id');
            $table->dropColumn('currency');
            $table->dropColumn('checkout_order_id');
            $table->dropColumn('razorpay_signature');

        });
    }
};
