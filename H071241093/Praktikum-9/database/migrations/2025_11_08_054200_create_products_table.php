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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // ID unik produk
            $table->string('name'); // Nama produk
            $table->decimal('price', 15, 2); // Harga produk (15 digit, 2 desimal)
            $table->unsignedBigInteger('category_id')->nullable(); // Kategori produk (opsional)
            $table->timestamps();

            // Relasi: Kategori dihapus, category_id jadi NULL 
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};