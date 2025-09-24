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
        Schema::create('google_ads_sync_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('account_id');
            $table->string('account_name')->nullable();
            $table->string('currency_code', 3)->default('EUR');
            $table->string('time_zone')->nullable();
            $table->json('campaigns_data')->nullable();
            $table->json('keywords_data')->nullable();
            $table->json('ads_data')->nullable();
            $table->string('sync_token', 64)->unique();
            $table->timestamp('last_sync_at')->nullable();
            $table->string('sync_status')->default('pending'); // pending, syncing, completed, error
            $table->text('sync_error')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'account_id']);
            $table->index('sync_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_ads_sync_data');
    }
};
