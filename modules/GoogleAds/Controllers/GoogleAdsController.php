<?php

namespace Modules\GoogleAds\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\GoogleAds\Services\GoogleAdsService;
use Modules\GoogleAds\Repositories\GoogleAdsRepository;

class GoogleAdsController extends Controller
{
    public function __construct(
        private GoogleAdsService $googleAdsService,
        private GoogleAdsRepository $repository
    ) {}

    public function saveConfig(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'developer_token' => 'required|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'login_customer_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $credential = $this->repository->saveCredential([
                'developer_token' => $request->developer_token,
                'client_id' => $request->client_id,
                'client_secret' => $request->client_secret,
                'login_customer_id' => $request->login_customer_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Google Ads configuration saved successfully',
                'credential_id' => $credential->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAuthUrl(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|string',
            'redirect_uri' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $authUrl = $this->googleAdsService->generateOAuthUrl(
                $request->client_id,
                $request->redirect_uri
            );

            return response()->json([
                'success' => true,
                'auth_url' => $authUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate auth URL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handleCallback(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'redirect_uri' => 'required|url',
            'credential_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tokens = $this->googleAdsService->exchangeCodeForTokens(
                $request->code,
                $request->client_id,
                $request->client_secret,
                $request->redirect_uri
            );

            $this->repository->updateRefreshToken(
                $request->credential_id,
                $tokens['refresh_token']
            );

            return response()->json([
                'success' => true,
                'message' => 'OAuth authorization completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OAuth callback failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAccounts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $credential = $this->repository->getActiveCredential();
            if (!$credential) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active Google Ads credentials found'
                ], 404);
            }

            $customerId = $request->customer_id ?? $credential->login_customer_id;
            $accounts = $this->googleAdsService->getAccounts($customerId);

            return response()->json([
                'success' => true,
                'accounts' => $accounts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch accounts: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCampaigns(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string',
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'status' => 'nullable|string|in:ENABLED,PAUSED,REMOVED',
            'channel' => 'nullable|string|in:SEARCH,DISPLAY,SHOPPING,VIDEO,MULTI_CHANNEL',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $campaigns = $this->googleAdsService->getCampaigns(
                $request->customer_id,
                $request->start_date,
                $request->end_date,
                $request->status,
                $request->channel
            );

            // Simple pagination
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 20);
            $offset = ($page - 1) * $perPage;

            $paginatedCampaigns = array_slice($campaigns, $offset, $perPage);
            $total = count($campaigns);

            return response()->json([
                'success' => true,
                'campaigns' => array_map(fn($campaign) => $campaign->toArray(), $paginatedCampaigns),
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch campaigns: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportCsv(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string',
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'status' => 'nullable|string',
            'channel' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $campaigns = $this->googleAdsService->getCampaigns(
                $request->customer_id,
                $request->start_date,
                $request->end_date,
                $request->status,
                $request->channel
            );

            $filename = "campaigns_{$request->customer_id}_{$request->start_date}_{$request->end_date}.csv";

            return response()->streamDownload(function () use ($campaigns) {
                $handle = fopen('php://output', 'w');

                // CSV Header
                fputcsv($handle, [
                    'Customer ID', 'Campaign ID', 'Campaign Name', 'Status', 'Channel',
                    'Impressions', 'Clicks', 'Cost', 'Conversions', 'CTR', 'Avg CPC'
                ]);

                // CSV Data
                foreach ($campaigns as $campaign) {
                    fputcsv($handle, [
                        $campaign->customerId,
                        $campaign->campaignId,
                        $campaign->name,
                        $campaign->status,
                        $campaign->channel,
                        $campaign->impressions,
                        $campaign->clicks,
                        $campaign->cost,
                        $campaign->conversions,
                        $campaign->ctr,
                        $campaign->averageCpc,
                    ]);
                }

                fclose($handle);
            }, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export campaigns: ' . $e->getMessage()
            ], 500);
        }
    }
}