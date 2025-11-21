<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nama tabel pivot 'product_warehouse'
        Schema::create('product_warehouse', function (Blueprint $table) {
            
            // FK ke products
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade'); // 
                  
            // FK ke warehouses
            $table->foreignId('warehouse_id')
                  ->constrained('warehouses')
                  ->onDelete('cascade'); // 
            
            // Kolom tambahan di pivot table 
            $table->integer('quantity')->default(0); 
            
            // Primary key gabungan, sama seperti di modul 
            $table->primary(['product_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_warehouse');
    }
};