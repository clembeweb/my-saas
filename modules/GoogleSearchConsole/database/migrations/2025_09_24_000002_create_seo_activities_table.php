<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seo_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('google_search_console_properties')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('area');
            $table->date('data_inizio');
            $table->date('data_fine')->nullable();
            $table->enum('stato', ['Da fare', 'In corso', 'Completato', 'Sospeso'])->default('Da fare');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['property_id', 'data_inizio']);
            $table->index(['user_id', 'area']);
            $table->index('area');
            $table->index('stato');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seo_activities');
    }
};