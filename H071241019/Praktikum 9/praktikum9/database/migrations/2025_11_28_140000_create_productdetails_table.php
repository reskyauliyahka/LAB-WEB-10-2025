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
    Schema::create('productdetails', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id')->unique();
        $table->text('description')->nullable();
        $table->decimal('weight', 8, 2)->default(0);
        $table->string('size', 255)->nullable();
        $table->timestamps();

        // COMMENT DULU foreign key sampai products table ada
        // $table->foreign('product_id')
        //       ->references('id')
        //       ->on('products')
        //       ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productdetails');
    }
};