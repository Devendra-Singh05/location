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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            // $table->decimal('latitude', 10, 6);
            // $table->decimal('longitude', 10, 6);
            // $table->string('vendor_name');
            $table->decimal('latitude', 10, 7);   // 10 digits with 7 decimals for latitude
            $table->decimal('longitude', 10, 7);  // 10 digits with 7 decimals for longitude
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
