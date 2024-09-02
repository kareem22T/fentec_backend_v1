<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scooters', function (Blueprint $table) {
            $table->string('iot_id')->nullable()->unique();
        });
        DB::table('scooters')->update(['iot_id' => DB::raw('id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scooters', function (Blueprint $table) {
            //
        });
    }
};
