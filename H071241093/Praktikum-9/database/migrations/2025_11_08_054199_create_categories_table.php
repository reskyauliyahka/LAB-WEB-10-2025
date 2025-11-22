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
        // Sesuai spek PDF 
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // ID unik kategori
            $table->string('name'); // Nama kategori
            $table->text('description')->nullable(); // Deskripsi kategori (opsional)
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};