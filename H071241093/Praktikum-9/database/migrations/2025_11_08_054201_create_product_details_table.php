<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sesuai spek PDF 
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique(); // Relasi 1:1
            $table->text('description')->nullable(); // Deskripsi lengkap
            $table->decimal('weight', 8, 2); // Berat produk (contoh: 1.50 kg)
            $table->string('size')->nullable(); // Ukuran (contoh: "15 inch")
            $table->timestamps();

            // Relasi: Produk dihapus, detail ikut terhapus 
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};