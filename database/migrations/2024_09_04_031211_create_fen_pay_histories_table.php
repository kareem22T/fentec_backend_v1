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
        Schema::create('fen_pay_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id');
            $table->integer('admin_id');
            $table->float('ammount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fen_pay_histories');
    }
};
