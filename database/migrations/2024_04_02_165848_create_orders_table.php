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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->text('address')->nullable();
            $table->text('customerId')->nullable();
            $table->text('txn_id')->nullable();
            $table->bigInteger('bill')->nullable();
            $table->string('status')->nullable()->comment('pending,completed,failed');
            $table->string('payment_type')->nullable()->comment('ex:stripe,payu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
