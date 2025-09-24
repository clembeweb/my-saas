<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gsc_user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->integer('font_size')->default(14);
            $table->json('series_colors')->nullable();
            $table->json('area_colors')->nullable();
            $table->enum('preset', ['default', 'brand', 'high-contrast', 'stampa'])->default('default');
            $table->json('settings_json')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gsc_user_preferences');
    }
};