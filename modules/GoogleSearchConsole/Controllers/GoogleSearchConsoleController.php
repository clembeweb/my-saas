<?php

namespace Modules\GoogleSearchConsole\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\GoogleSearchConsole\Services\GoogleSearchConsoleService;
use Modules\GoogleSearchConsole\Models\GoogleSearchConsoleProperty;
use Modules\GoogleSearchConsole\Models\SeoActivity;
use Modules\GoogleSearchConsole\Models\UserPreference;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoogleSearchConsoleController extends Controller
{
    public function __construct(
        protected GoogleSearchConsoleService $gscService
    ) {}

    public function getAuthUrl(): JsonResponse
    {
        try {
            $authUrl = $this->gscService->getAuthUrl();

            return response()->json([
                'success' => true,
                'auth_url' => $authUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore generazione URL OAuth: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handleCallback(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'site_url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parametri non validi',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $token = $this->gscService->handleCallback($request->code);

            $property = GoogleSearchConsoleProperty::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'site_url' => $request->site_url
                ],
                [
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'] ?? null,
                    'token_expires_at' => now()->addSeconds($token['expires_in'] ?? 3600),
                    'is_verified' => true
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Proprietà Google Search Console connessa con successo',
                'property' => $property->only(['id', 'site_url', 'is_verified'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore connessione GSC: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProperties(): JsonResponse
    {
        try {
            $properties = GoogleSearchConsoleProperty::where('user_id', Auth::id())
                ->where('is_verified', true)
                ->select(['id', 'site_url', 'permission_level', 'is_verified', 'created_at'])
                ->get();

            return response()->json([
                'success' => true,
                'properties' => $properties
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore recupero proprietà: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSearchAnalytics(Request $request, int $propertyId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'areas' => 'sometimes|array',
            'areas.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parametri non validi',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Validate date range
            if (!$this->gscService->validateDateRange($request->start_date, $request->end_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intervallo date non valido (max 365 giorni, min 3 giorni fa)'
                ], 422);
            }

            // Get GSC data
            $gscData = $this->gscService->getDailyTimeSeries(
                $property,
                $request->start_date,
                $request->end_date
            );

            // Get activities
            $activitiesQuery = SeoActivity::where('property_id', $propertyId)
                ->byDateRange($request->start_date, $request->end_date);

            if ($request->has('areas') && !empty($request->areas)) {
                $activitiesQuery->byAreas($request->areas);
            }

            $activities = $activitiesQuery->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'gsc_data' => $gscData->map(fn($item) => $item->toArray()),
                    'activities' => $activities,
                    'date_range' => [
                        'start' => $request->start_date,
                        'end' => $request->end_date
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore recupero dati: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportCsv(Request $request, int $propertyId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $data = $this->gscService->getDailyTimeSeries(
                $property,
                $request->start_date,
                $request->end_date
            );

            $filePath = $this->gscService->exportToCsv($data);
            $fileName = basename($filePath);

            return response()->json([
                'success' => true,
                'download_url' => route('gsc.download', ['file' => $fileName]),
                'filename' => $fileName
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore esportazione: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserPreferences(): JsonResponse
    {
        try {
            $preferences = UserPreference::where('user_id', Auth::id())->first();

            if (!$preferences) {
                $preferences = UserPreference::create([
                    'user_id' => Auth::id(),
                    ...UserPreference::getDefaultPreferences()
                ]);
            }

            return response()->json([
                'success' => true,
                'preferences' => $preferences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore recupero preferenze: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUserPreferences(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'sometimes|in:light,dark',
            'font_size' => 'sometimes|integer|min:10|max:20',
            'series_colors' => 'sometimes|array',
            'area_colors' => 'sometimes|array',
            'preset' => 'sometimes|in:default,brand,high-contrast,stampa',
            'settings_json' => 'sometimes|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $preferences = UserPreference::updateOrCreate(
                ['user_id' => Auth::id()],
                $request->only(['theme', 'font_size', 'series_colors', 'area_colors', 'preset', 'settings_json'])
            );

            return response()->json([
                'success' => true,
                'message' => 'Preferenze aggiornate con successo',
                'preferences' => $preferences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore aggiornamento preferenze: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAreas(int $propertyId): JsonResponse
    {
        try {
            $areas = SeoActivity::where('property_id', $propertyId)
                ->distinct()
                ->pluck('area')
                ->filter()
                ->values();

            return response()->json([
                'success' => true,
                'areas' => $areas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore recupero aree: ' . $e->getMessage()
            ], 500);
        }
    }
}