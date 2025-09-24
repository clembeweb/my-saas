<?php

namespace Modules\GoogleSearchConsole\Services;

use Google\Client;
use Google\Service\SearchConsole;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;
use Modules\GoogleSearchConsole\Models\GoogleSearchConsoleProperty;
use Modules\GoogleSearchConsole\DTOs\SearchConsoleDataDTO;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleSearchConsoleService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.gsc_client_id'));
        $this->client->setClientSecret(config('services.google.gsc_client_secret'));
        $this->client->setRedirectUri(config('services.google.gsc_redirect_uri'));
        $this->client->addScope(SearchConsole::WEBMASTERS_READONLY);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback(string $code): array
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                throw new \Exception('OAuth error: ' . $token['error_description'] ?? $token['error']);
            }

            return $token;
        } catch (\Exception $e) {
            Log::error('GSC OAuth callback error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getProperties(GoogleSearchConsoleProperty $property): Collection
    {
        try {
            $this->setAuthToken($property);

            $cacheKey = "gsc_properties_{$property->user_id}";

            return Cache::remember($cacheKey, 300, function () {
                $service = new SearchConsole($this->client);
                $sitesList = $service->sites->listSites();

                return collect($sitesList->getSiteEntry())->map(function ($site) {
                    return [
                        'site_url' => $site->getSiteUrl(),
                        'permission_level' => $site->getPermissionLevel()
                    ];
                });
            });
        } catch (\Exception $e) {
            Log::error('GSC get properties error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getSearchAnalytics(
        GoogleSearchConsoleProperty $property,
        string $startDate,
        string $endDate,
        array $dimensions = ['date'],
        array $dimensionFilterGroups = []
    ): Collection {
        try {
            $this->setAuthToken($property);

            $cacheKey = "gsc_analytics_{$property->id}_{$startDate}_{$endDate}_" . md5(json_encode($dimensions) . json_encode($dimensionFilterGroups));

            return Cache::remember($cacheKey, 1800, function () use ($property, $startDate, $endDate, $dimensions, $dimensionFilterGroups) {
                $service = new SearchConsole($this->client);

                $request = new SearchAnalyticsQueryRequest();
                $request->setStartDate($startDate);
                $request->setEndDate($endDate);
                $request->setDimensions($dimensions);
                $request->setDimensionFilterGroups($dimensionFilterGroups);
                $request->setRowLimit(25000);
                $request->setStartRow(0);

                $response = $service->searchanalytics->query($property->site_url, $request);

                return collect($response->getRows())->map(function ($row) {
                    return new SearchConsoleDataDTO([
                        'date' => $row->getKeys()[0] ?? null,
                        'clicks' => $row->getClicks(),
                        'impressions' => $row->getImpressions(),
                        'ctr' => $row->getCtr(),
                        'position' => $row->getPosition()
                    ]);
                });
            });
        } catch (\Exception $e) {
            Log::error('GSC get search analytics error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getDailyTimeSeries(
        GoogleSearchConsoleProperty $property,
        string $startDate,
        string $endDate
    ): Collection {
        return $this->getSearchAnalytics($property, $startDate, $endDate, ['date']);
    }

    public function refreshTokenIfNeeded(GoogleSearchConsoleProperty $property): void
    {
        if ($property->token_expires_at && $property->token_expires_at->isPast()) {
            $this->client->setRefreshToken($property->decrypted_refresh_token);
            $newToken = $this->client->fetchAccessTokenWithRefreshToken();

            if (isset($newToken['access_token'])) {
                $property->update([
                    'access_token' => $newToken['access_token'],
                    'token_expires_at' => now()->addSeconds($newToken['expires_in'] ?? 3600)
                ]);
            }
        }
    }

    protected function setAuthToken(GoogleSearchConsoleProperty $property): void
    {
        $this->refreshTokenIfNeeded($property);
        $this->client->setAccessToken($property->decrypted_access_token);
    }

    public function exportToCsv(Collection $data, string $filename = null): string
    {
        $filename = $filename ?: 'gsc_export_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/exports/' . $filename);

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $handle = fopen($filepath, 'w');

        // Header
        fputcsv($handle, ['Date', 'Clicks', 'Impressions', 'CTR', 'Position']);

        // Data
        foreach ($data as $row) {
            fputcsv($handle, [
                $row->date,
                $row->clicks,
                $row->impressions,
                round($row->ctr * 100, 2) . '%',
                round($row->position, 2)
            ]);
        }

        fclose($handle);

        return $filepath;
    }

    public function validateDateRange(string $startDate, string $endDate): bool
    {
        try {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            // GSC has data delay of ~3 days
            $maxDate = now()->subDays(3);
            $minDate = now()->subDays(450); // ~16 months limit

            return $start >= $minDate &&
                   $end <= $maxDate &&
                   $start <= $end &&
                   $start->diffInDays($end) <= 365; // Max 1 year range
        } catch (\Exception $e) {
            return false;
        }
    }
}