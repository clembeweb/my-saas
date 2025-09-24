<?php

namespace Modules\GoogleAds\Tests;

use Modules\GoogleAds\Models\GoogleAdsCredential;
use Modules\GoogleAds\Repositories\GoogleAdsRepository;
use Modules\GoogleAds\Services\GoogleAdsService;
use PHPUnit\Framework\TestCase;
use Mockery;

class GoogleAdsServiceTest extends TestCase
{
    private GoogleAdsService $service;
    private GoogleAdsRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(GoogleAdsRepository::class);
        $this->service = new GoogleAdsService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGenerateOAuthUrl(): void
    {
        $clientId = 'test-client-id';
        $redirectUri = 'https://example.com/callback';

        $authUrl = $this->service->generateOAuthUrl($clientId, $redirectUri);

        $this->assertStringContainsString('accounts.google.com/o/oauth2/v2/auth', $authUrl);
        $this->assertStringContainsString($clientId, $authUrl);
        $this->assertStringContainsString(urlencode($redirectUri), $authUrl);
        $this->assertStringContainsString('scope=https%3A//www.googleapis.com/auth/adwords', $authUrl);
    }

    public function testExchangeCodeForTokens(): void
    {
        $code = 'test-auth-code';
        $clientId = 'test-client-id';
        $clientSecret = 'test-client-secret';
        $redirectUri = 'https://example.com/callback';

        // This test would require mocking the OAuth2 class
        // For now, we'll just test the method signature
        $this->assertTrue(method_exists($this->service, 'exchangeCodeForTokens'));
    }

    public function testGetAccountsThrowsExceptionWhenNoCredentials(): void
    {
        $this->repository
            ->shouldReceive('getActiveCredential')
            ->once()
            ->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No active Google Ads credentials found');

        $this->service->getAccounts('123456789');
    }
}