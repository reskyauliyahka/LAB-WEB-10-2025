<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sesuai spek PDF 
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id(); // ID unik gudang
            $table->string('name'); // Nama gudang
            $table->text('location')->nullable(); // Lokasi gudang (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};