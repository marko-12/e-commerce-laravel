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
            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->integer('postal_code');
            $table->boolean('delivered')->default(false);
            $table->dateTime('delivered_at')->nullable();
            $table->boolean('paid')->default(false);
            $table->dateTime('paid_at')->nullable();
            $table->boolean('pay_before_shipping')->default(false);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
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
