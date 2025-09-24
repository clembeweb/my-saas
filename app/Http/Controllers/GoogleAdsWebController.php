<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Modules\GoogleAds\Models\GoogleAdsCredential;
use Modules\GoogleAds\Services\GoogleAdsService;
use Modules\GoogleAds\Repositories\GoogleAdsRepository;
use App\Models\GoogleAdsSyncData;
use App\Services\GoogleAdsScriptGenerator;

class GoogleAdsWebController extends Controller
{
    protected $googleAdsService;
    protected $repository;

    public function __construct(GoogleAdsService $googleAdsService, GoogleAdsRepository $repository)
    {
        $this->googleAdsService = $googleAdsService;
        $this->repository = $repository;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Check if user has Google Ads credentials
        $credential = GoogleAdsCredential::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$credential) {
            return view('google-ads.connect', ['user' => $user]);
        }

        // User has credentials, show dashboard
        return view('google-ads.dashboard', [
            'user' => $user,
            'credential' => $credential,
            'hasConnection' => true
        ]);
    }

    public function connect()
    {
        $user = Auth::user();

        // Store user ID in session for the callback
        session(['google_ads_user_id' => $user->id]);

        // Generate OAuth URL using system credentials
        $authUrl = $this->googleAdsService->generateOAuthUrl(
            config('services.google.client_id'),
            route('google-ads.callback')
        );

        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        $user = Auth::user();
        $code = $request->get('code');

        // Verify user from session
        $sessionUserId = session('google_ads_user_id');
        if (!$sessionUserId || $sessionUserId != $user->id) {
            return redirect()->route('google-ads.dashboard')
                ->with('error', 'Errore di sicurezza durante l\'autenticazione.');
        }

        // Clear session
        session()->forget('google_ads_user_id');

        try {
            // Exchange code for tokens
            $tokens = $this->googleAdsService->exchangeCodeForTokens(
                $code,
                config('services.google.client_id'),
                config('services.google.client_secret'),
                route('google-ads.callback')
            );

            // Save user credentials
            // Use the manager account login_customer_id to access sub-accounts
            $credential = $this->repository->saveUserCredential($user->id, [
                'developer_token' => config('services.google.developer_token'),
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'refresh_token' => $tokens['refresh_token'],
                'access_token' => $tokens['access_token'],
                'login_customer_id' => config('services.google.login_customer_id') // Manager account ID
            ]);

            return redirect()->route('google-ads.dashboard')
                ->with('success', 'Connessione a Google Ads completata con successo!');

        } catch (\Exception $e) {
            return redirect()->route('google-ads.dashboard')
                ->with('error', 'Errore durante la connessione: ' . $e->getMessage());
        }
    }

    public function campaigns(Request $request)
    {
        $user = Auth::user();

        $credential = GoogleAdsCredential::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$credential) {
            return redirect()->route('google-ads.dashboard')
                ->with('error', 'Devi prima connetterti a Google Ads.');
        }

        try {
            // Get available sub-accounts first
            $accounts = $this->googleAdsService->getAccounts();

            if (empty($accounts)) {
                return redirect()->route('google-ads.dashboard')
                    ->with('error', 'Nessun sub-account trovato. Assicurati di avere sub-account attivi nel tuo manager account.');
            }

            // Use the first non-manager account or the requested customer_id
            $customerId = $request->get('customer_id');
            if (!$customerId) {
                // Find first non-manager account
                foreach ($accounts as $account) {
                    if (!$account['is_manager_account']) {
                        $customerId = $account['customer_id'];
                        break;
                    }
                }

                if (!$customerId) {
                    $customerId = $accounts[0]['customer_id']; // Fallback to first account
                }
            }

            $campaigns = $this->googleAdsService->getCampaigns(
                $customerId,
                $request->get('start_date', now()->subDays(30)->format('Y-m-d')),
                $request->get('end_date', now()->format('Y-m-d')),
                $request->get('status'),
                $request->get('channel')
            );

            return view('google-ads.campaigns', [
                'campaigns' => $campaigns,
                'credential' => $credential,
                'accounts' => $accounts,
                'current_customer_id' => $customerId
            ]);

        } catch (\Exception $e) {
            return redirect()->route('google-ads.dashboard')
                ->with('error', 'Errore nel recupero delle campagne: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $user = Auth::user();

        GoogleAdsCredential::where('user_id', $user->id)
            ->update(['is_active' => false]);

        return redirect()->route('google-ads.dashboard')
            ->with('success', 'Disconnessione da Google Ads completata.');
    }

    public function scriptManager()
    {
        $user = Auth::user();

        // Get or create sync data for user
        $syncData = GoogleAdsSyncData::where('user_id', $user->id)->first();

        if (!$syncData) {
            // Create new sync data with token
            $syncData = GoogleAdsSyncData::create([
                'user_id' => $user->id,
                'account_id' => '',
                'sync_status' => 'pending',
                'sync_token' => bin2hex(random_bytes(32))
            ]);
        }

        // Generate sync token if not exists
        if (empty($syncData->sync_token)) {
            $syncData->generateSyncToken();
        }

        return view('google-ads.script-manager', [
            'syncData' => $syncData,
            'user' => $user
        ]);
    }

    public function generateScript(Request $request)
    {
        $user = Auth::user();
        $scriptType = $request->get('type', 'campaigns');

        $syncData = GoogleAdsSyncData::where('user_id', $user->id)->first();

        if (!$syncData) {
            return redirect()->route('google-ads.script-manager')
                ->with('error', 'Dati di sincronizzazione non trovati.');
        }

        $generator = new GoogleAdsScriptGenerator();
        $apiEndpoint = config('app.url') . '/api/v1/sync/campaigns';

        if ($scriptType === 'campaigns') {
            $script = $generator->generateCampaignSyncScript($syncData->sync_token, $apiEndpoint);
        } else {
            $script = $generator->generateKeywordSyncScript($syncData->sync_token, $apiEndpoint);
        }

        return response($script)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="google-ads-sync-' . $scriptType . '.js"');
    }

    public function syncedCampaigns()
    {
        $user = Auth::user();

        $syncData = GoogleAdsSyncData::where('user_id', $user->id)->first();

        if (!$syncData || empty($syncData->getCampaigns())) {
            return redirect()->route('google-ads.script-manager')
                ->with('error', 'Nessun dato sincronizzato trovato. Configura e esegui prima lo script.');
        }

        // Convert synced data to campaign DTOs format
        $campaigns = collect($syncData->getCampaigns())->map(function ($campaign) {
            return (object) [
                'campaign_id' => $campaign['id'],
                'campaign_name' => $campaign['name'],
                'campaign_status' => $campaign['status'],
                'advertising_channel_type' => $campaign['type'],
                'impressions' => $campaign['impressions'],
                'clicks' => $campaign['clicks'],
                'cost_micros' => $campaign['cost'] * 1000000, // Convert to micros
                'conversions' => $campaign['conversions'],
                'ctr' => $campaign['ctr'],
                'average_cpc' => $campaign['avg_cpc'] * 1000000, // Convert to micros
                'start_date' => now()->subDays(30)->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ];
        });

        return view('google-ads.campaigns', [
            'campaigns' => $campaigns,
            'syncData' => $syncData
        ]);
    }
}