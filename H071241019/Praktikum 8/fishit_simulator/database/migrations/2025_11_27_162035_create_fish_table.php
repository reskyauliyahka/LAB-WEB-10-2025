<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFishTable extends Migration
{
    public function up(): void
    {
        Schema::create('fish', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rarity');
            $table->float('base_weight_min');
            $table->float('base_weight_max');
            $table->integer('sell_price_per_kg');
            $table->float('catch_probability');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fish');
    }
}
