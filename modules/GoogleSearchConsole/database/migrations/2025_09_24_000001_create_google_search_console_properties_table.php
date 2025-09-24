<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('google_search_console_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('site_url');
            $table->enum('property_type', ['URL_PREFIX', 'DOMAIN'])->default('URL_PREFIX');
            $table->string('permission_level')->default('siteOwner');
            $table->string('verification_method')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'site_url']);
            $table->index('user_id');
            $table->index('is_verified');
        });
    }

    public function down()
    {
        Schema::dropIfExists('google_search_console_properties');
    }
};