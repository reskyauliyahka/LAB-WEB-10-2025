<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            
            // Relasi 1:1 ke Products
            $table->foreignId('product_id')
                  ->unique() // Constraint 'UNIQUE' untuk 1:1 
                  ->constrained('products')
                  ->onDelete('cascade'); // 'onDelete cascade' 
                  
            $table->text('description')->nullable(); // 
            $table->decimal('weight', 8, 2); // 
            $table->string('size')->nullable(); // 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};