<?php

namespace Modules\GoogleSearchConsole\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\GoogleSearchConsole\Models\SeoActivity;
use Modules\GoogleSearchConsole\Models\GoogleSearchConsoleProperty;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeoActivityController extends Controller
{
    public function index(Request $request, int $propertyId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'search' => 'sometimes|string|max:255',
            'area' => 'sometimes|string|max:255',
            'stato' => 'sometimes|in:Da fare,In corso,Completato,Sospeso',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|in:data_inizio,title,area,stato,created_at',
            'sort_order' => 'sometimes|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify property belongs to user
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $query = SeoActivity::where('property_id', $propertyId);

            // Apply filters
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('note', 'like', "%{$search}%");
                });
            }

            if ($request->has('area') && $request->area) {
                $query->where('area', $request->area);
            }

            if ($request->has('stato') && $request->stato) {
                $query->where('stato', $request->stato);
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $query->byDateRange($request->start_date, $request->end_date);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'data_inizio');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginate
            $perPage = $request->get('per_page', 15);
            $activities = $query->with(['creator:id,name', 'updater:id,name'])
                                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'activities' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore recupero attività: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, int $propertyId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'data_inizio' => 'required|date',
            'data_fine' => 'sometimes|nullable|date|after_or_equal:data_inizio',
            'stato' => 'sometimes|in:Da fare,In corso,Completato,Sospeso',
            'note' => 'sometimes|nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify property belongs to user
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $activity = SeoActivity::create([
                'property_id' => $propertyId,
                'user_id' => Auth::id(),
                'title' => $request->title,
                'area' => $request->area,
                'data_inizio' => $request->data_inizio,
                'data_fine' => $request->data_fine,
                'stato' => $request->get('stato', 'Da fare'),
                'note' => $request->note,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            $activity->load(['creator:id,name', 'updater:id,name']);

            return response()->json([
                'success' => true,
                'message' => 'Attività creata con successo',
                'activity' => $activity
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore creazione attività: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(int $propertyId, int $activityId): JsonResponse
    {
        try {
            // Verify property belongs to user
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $activity = SeoActivity::where('id', $activityId)
                ->where('property_id', $propertyId)
                ->with(['creator:id,name', 'updater:id,name'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'activity' => $activity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Attività non trovata'
            ], 404);
        }
    }

    public function update(Request $request, int $propertyId, int $activityId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'area' => 'sometimes|required|string|max:255',
            'data_inizio' => 'sometimes|required|date',
            'data_fine' => 'sometimes|nullable|date|after_or_equal:data_inizio',
            'stato' => 'sometimes|in:Da fare,In corso,Completato,Sospeso',
            'note' => 'sometimes|nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify property belongs to user
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $activity = SeoActivity::where('id', $activityId)
                ->where('property_id', $propertyId)
                ->firstOrFail();

            $activity->update([
                ...$request->only(['title', 'area', 'data_inizio', 'data_fine', 'stato', 'note']),
                'updated_by' => Auth::id()
            ]);

            $activity->load(['creator:id,name', 'updater:id,name']);

            return response()->json([
                'success' => true,
                'message' => 'Attività aggiornata con successo',
                'activity' => $activity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore aggiornamento attività: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $propertyId, int $activityId): JsonResponse
    {
        try {
            // Verify property belongs to user
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $activity = SeoActivity::where('id', $activityId)
                ->where('property_id', $propertyId)
                ->firstOrFail();

            $activity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attività eliminata con successo'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore eliminazione attività: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkImport(Request $request, int $propertyId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify property belongs to user
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data);

            // Expected columns: Date, Area, Attività, Periodo, Stato, Note
            $expectedColumns = ['date', 'area', 'title', 'periodo', 'stato', 'note'];
            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                try {
                    if (count($row) < 3) continue; // Skip incomplete rows

                    $activity = [
                        'property_id' => $propertyId,
                        'user_id' => Auth::id(),
                        'title' => $row[2] ?? '', // Attività
                        'area' => $row[1] ?? '', // Area
                        'data_inizio' => $row[0] ?? '', // Date
                        'data_fine' => null,
                        'stato' => $row[4] ?? 'Da fare', // Stato
                        'note' => $row[5] ?? '', // Note
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];

                    // Parse date
                    try {
                        $activity['data_inizio'] = \Carbon\Carbon::parse($activity['data_inizio'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $errors[] = "Riga " . ($index + 2) . ": Data non valida";
                        continue;
                    }

                    // Validate stato
                    if (!in_array($activity['stato'], ['Da fare', 'In corso', 'Completato', 'Sospeso'])) {
                        $activity['stato'] = 'Da fare';
                    }

                    SeoActivity::create($activity);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Riga " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import completato: {$imported} attività importate",
                'imported_count' => $imported,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Errore import: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkExport(Request $request, int $propertyId): JsonResponse
    {
        try {
            // Verify property belongs to user
            $property = GoogleSearchConsoleProperty::where('id', $propertyId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $activities = SeoActivity::where('property_id', $propertyId)
                ->orderBy('data_inizio', 'desc')
                ->get();

            $filename = 'seo_activities_export_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/exports/' . $filename);

            if (!is_dir(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }

            $handle = fopen($filepath, 'w');

            // Header
            fputcsv($handle, ['Date', 'Area', 'Attività', 'Data Fine', 'Stato', 'Note']);

            // Data
            foreach ($activities as $activity) {
                fputcsv($handle, [
                    $activity->data_inizio->format('Y-m-d'),
                    $activity->area,
                    $activity->title,
                    $activity->data_fine ? $activity->data_fine->format('Y-m-d') : '',
                    $activity->stato,
                    $activity->note
                ]);
            }

            fclose($handle);

            return response()->json([
                'success' => true,
                'download_url' => route('gsc.download', ['file' => $filename]),
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore esportazione: ' . $e->getMessage()
            ], 500);
        }
    }
}