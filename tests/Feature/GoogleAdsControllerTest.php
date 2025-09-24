<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Modules\GoogleAds\Models\GoogleAdsCredential;

class GoogleAdsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_config_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/google/config', [
            'developer_token' => 'test-token',
            'client_id' => 'test-client-id',
            'client_secret' => 'test-client-secret',
            'login_customer_id' => '123456789',
        ]);

        $response->assertStatus(401);
    }

    public function test_save_config_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/google/config', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'developer_token',
                'client_id',
                'client_secret',
                'login_customer_id',
            ]);
    }

    public function test_get_auth_url_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/google/auth/url');

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'client_id',
                'redirect_uri',
            ]);
    }

    public function test_get_campaigns_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/google/campaigns');

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'customer_id',
                'start_date',
                'end_date',
            ]);
    }

    public function test_get_campaigns_validates_date_format(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/google/campaigns?' . http_build_query([
                'customer_id' => '123456789',
                'start_date' => 'invalid-date',
                'end_date' => 'invalid-date',
            ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'start_date',
                'end_date',
            ]);
    }
}