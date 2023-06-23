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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('hotel_id');
            $table->string('reference')->unique();
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->decimal('total_amount')->default(0);
            $table->decimal('vat')->default(0);
            $table->decimal('sub_total_amount')->default(0);
            $table->enum('status', [0, 1, 2])->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};