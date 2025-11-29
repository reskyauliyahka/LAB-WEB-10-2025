<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            // HARUS foreignId supaya tipe sesuai
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');

            $table->integer('quantity');
            $table->string('type')->comment('in|out|transfer');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
