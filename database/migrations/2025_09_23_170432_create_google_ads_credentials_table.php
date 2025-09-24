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
        Schema::create('google_ads_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->text('developer_token');
            $table->string('client_id');
            $table->text('client_secret');
            $table->text('refresh_token')->nullable();
            $table->string('login_customer_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_ads_credentials');
    }
};
