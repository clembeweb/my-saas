<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoogleAdsSyncData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GoogleAdsSyncController extends Controller
{
    public function receiveCampaignData(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sync_token' => 'required|string|size:64',
                'account_id' => 'required|string',
                'account_name' => 'nullable|string',
                'currency_code' => 'nullable|string|size:3',
                'time_zone' => 'nullable|string',
                'campaigns' => 'required|array',
                'campaigns.*.id' => 'required|string',
                'campaigns.*.name' => 'required|string',
                'campaigns.*.status' => 'required|string',
                'campaigns.*.type' => 'required|string',
                'campaigns.*.impressions' => 'required|numeric|min:0',
                'campaigns.*.clicks' => 'required|numeric|min:0',
                'campaigns.*.cost' => 'required|numeric|min:0',
                'campaigns.*.conversions' => 'required|numeric|min:0',
                'campaigns.*.ctr' => 'required|numeric|min:0',
                'campaigns.*.avg_cpc' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $syncData = GoogleAdsSyncData::where('sync_token', $request->sync_token)->first();

            if (!$syncData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sync token'
                ], 404);
            }

            // Update sync data
            $syncData->update([
                'account_id' => $request->account_id,
                'account_name' => $request->account_name,
                'currency_code' => $request->currency_code ?? 'EUR',
                'time_zone' => $request->time_zone,
                'campaigns_data' => $request->campaigns,
                'last_sync_at' => now(),
                'sync_status' => 'completed',
                'sync_error' => null
            ]);

            Log::info('Google Ads data synchronized', [
                'user_id' => $syncData->user_id,
                'account_id' => $request->account_id,
                'campaigns_count' => count($request->campaigns)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Campaign data synchronized successfully',
                'data' => [
                    'campaigns_count' => count($request->campaigns),
                    'last_sync_at' => $syncData->last_sync_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Google Ads sync error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($syncData)) {
                $syncData->update([
                    'sync_status' => 'error',
                    'sync_error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function getSyncStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sync_token' => 'required|string|size:64'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid sync token'
            ], 422);
        }

        $syncData = GoogleAdsSyncData::where('sync_token', $request->sync_token)->first();

        if (!$syncData) {
            return response()->json([
                'success' => false,
                'message' => 'Sync token not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $syncData->sync_status,
                'last_sync_at' => $syncData->last_sync_at?->toISOString(),
                'campaigns_count' => count($syncData->getCampaigns()),
                'error' => $syncData->sync_error
            ]
        ]);
    }
}
