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
        Schema::create('fishes', function (Blueprint $table) {
            // "id" BIGINT UNSIGNED, PRIMARY KEY, AUTO_INCREMENT [cite: 10]
            $table->id(); 
            
            // "name" VARCHAR(100), NOT NULL [cite: 10]
            $table->string('name', 100); 
            
            // "rarity" ENUM, NOT NULL [cite: 10]
            $table->enum('rarity', [
                'Common', 
                'Uncommon', 
                'Rare', 
                'Epic', 
                'Legendary', 
                'Mythic', 
                'Secret'
            ]); 
            
            // "base_weight_min" DECIMAL(8,2), NOT NULL [cite: 10]
            $table->decimal('base_weight_min', 8, 2); 
            
            // "base_weight_max" DECIMAL(8,2), NOT NULL [cite: 10]
            $table->decimal('base_weight_max', 8, 2); 
            
            // "sell_price_per_kg" INTEGER, NOT NULL [cite: 10]
            $table->integer('sell_price_per_kg'); 
            
            // "catch_probability" DECIMAL(5,2), NOT NULL [cite: 11]
            $table->decimal('catch_probability', 5, 2); 
            
            // "description" TEXT, NULLABLE [cite: 11]
            $table->text('description')->nullable(); 
            
            // "created_at" dan "updated_at" TIMESTAMP [cite: 11]
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fishes');
    }
};