<?php
// 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sesuai 'varchar(255) NOT NULL'
            $table->text('location')->nullable(); // Sesuai 'text NULLABLE'
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};