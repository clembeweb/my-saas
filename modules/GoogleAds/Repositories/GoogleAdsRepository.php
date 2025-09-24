<?php

namespace Modules\GoogleAds\Repositories;

use Modules\GoogleAds\Models\GoogleAdsAccount;
use Modules\GoogleAds\Models\GoogleAdsCredential;

class GoogleAdsRepository
{
    public function getActiveCredential(): ?GoogleAdsCredential
    {
        return GoogleAdsCredential::where('is_active', true)->first();
    }

    public function saveCredential(array $data): GoogleAdsCredential
    {
        // Deactivate existing credentials
        GoogleAdsCredential::where('is_active', true)->update(['is_active' => false]);

        // Add tenant_id to data (hardcoded for testing)
        $data['tenant_id'] = 'demo-tenant';

        return GoogleAdsCredential::create(array_merge($data, ['is_active' => true]));
    }

    public function updateRefreshToken(int $credentialId, string $refreshToken): bool
    {
        return GoogleAdsCredential::where('id', $credentialId)
            ->update(['refresh_token' => $refreshToken]);
    }

    public function saveAccount(array $data): GoogleAdsAccount
    {
        return GoogleAdsAccount::updateOrCreate(
            ['customer_id' => $data['customer_id']],
            array_merge($data, ['last_sync_at' => now()])
        );
    }

    public function getAccounts(): \Illuminate\Database\Eloquent\Collection
    {
        return GoogleAdsAccount::where('is_active', true)
            ->orderBy('descriptive_name')
            ->get();
    }

    public function getManagerAccounts(): \Illuminate\Database\Eloquent\Collection
    {
        return GoogleAdsAccount::where('is_manager_account', true)
            ->where('is_active', true)
            ->with('subAccounts')
            ->get();
    }

    public function getAccountById(string $customerId): ?GoogleAdsAccount
    {
        return GoogleAdsAccount::where('customer_id', $customerId)->first();
    }

    public function saveUserCredential(int $userId, array $data): GoogleAdsCredential
    {
        // Deactivate existing credentials for this user
        GoogleAdsCredential::where('user_id', $userId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Add user_id to data
        $data['user_id'] = $userId;
        $data['tenant_id'] = 'main'; // Single tenant mode

        return GoogleAdsCredential::create(array_merge($data, ['is_active' => true]));
    }

    public function getUserCredential(int $userId): ?GoogleAdsCredential
    {
        return GoogleAdsCredential::where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }
}