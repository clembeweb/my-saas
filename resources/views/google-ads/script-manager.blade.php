<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Script Manager - SaaS Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            width: 280px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 4px 16px;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .step-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .step-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.15);
        }
        .step-number {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
            margin: 0 auto 16px;
        }
        .code-block {
            background: #1a202c;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 14px;
            overflow-x: auto;
            border: 1px solid #2d3748;
        }
        .status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-completed {
            background: #dcfce7;
            color: #166534;
        }
        .status-error {
            background: #fecaca;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar fixed">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 3L4 14h7v7l9-11h-7V3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">SaaS Platform</h1>
                        <p class="text-sm text-white text-opacity-70">Marketing Tools</p>
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="/dashboard" class="sidebar-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="/google-ads" class="sidebar-link active">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        </svg>
                        Google Ads
                    </a>
                    <a href="#" class="sidebar-link opacity-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analytics
                        <span class="ml-auto text-xs bg-white bg-opacity-20 px-2 py-1 rounded">Soon</span>
                    </a>
                </nav>
            </div>

            <div class="absolute bottom-6 left-6 right-6">
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">{{ auth()->user()->name[0] }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-white text-opacity-70 text-xs">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full text-left text-white text-opacity-70 hover:text-white text-xs">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-80">
            <div class="p-8">
                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold gradient-text">Script Manager</h1>
                            <p class="text-gray-600">Configura Google Ads Script per sincronizzazione automatica</p>
                        </div>
                    </div>
                    <a href="/google-ads" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Torna al Dashboard
                    </a>
                </div>

                <!-- Status Card -->
                <div class="bg-white rounded-2xl p-6 mb-8 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Stato Sincronizzazione</h3>
                            <div class="status-indicator status-{{ $syncData->sync_status }}">
                                @if($syncData->sync_status === 'pending')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    In Attesa
                                @elseif($syncData->sync_status === 'completed')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Completata
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Errore
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            @if($syncData->last_sync_at)
                                <p class="text-sm text-gray-500">Ultima sincronizzazione</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $syncData->last_sync_at->format('d/m/Y H:i') }}</p>
                            @else
                                <p class="text-sm text-gray-500">Nessuna sincronizzazione</p>
                            @endif
                        </div>
                    </div>

                    @if($syncData->sync_status === 'completed')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ count($syncData->getCampaigns()) }}</div>
                                <div class="text-sm text-gray-600">Campagne</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $syncData->account_name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-600">Account</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ $syncData->currency_code ?? 'EUR' }}</div>
                                <div class="text-sm text-gray-600">Valuta</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('google-ads.synced-campaigns') }}" class="block w-full text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                Visualizza Campagne Sincronizzate
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Instructions -->
                <div class="grid md:grid-cols-3 gap-8 mb-8">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Scarica Script</h3>
                        <p class="text-gray-600 mb-6">Genera e scarica lo script personalizzato per il tuo account</p>
                        <a href="{{ route('google-ads.generate-script', ['type' => 'campaigns']) }}"
                           class="block w-full text-center bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all">
                            Scarica Script Campagne
                        </a>
                    </div>

                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Installa in Google Ads</h3>
                        <p class="text-gray-600 mb-6">Vai su Google Ads → Tools → Scripts e incolla il codice</p>
                        <a href="https://ads.google.com/aw/bulk/scripts" target="_blank"
                           class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                            Apri Google Ads Scripts ↗
                        </a>
                    </div>

                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Esegui e Programma</h3>
                        <p class="text-gray-600 mb-6">Esegui lo script e programmalo per sincronizzazioni automatiche</p>
                        <button class="w-full bg-green-100 text-green-700 py-3 rounded-lg" disabled>
                            Script Pronto
                        </button>
                    </div>
                </div>

                <!-- Advanced Info -->
                <div class="bg-gray-50 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Informazioni Tecniche</h3>

                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Token di Sincronizzazione</h4>
                            <div class="code-block">
                                <code>{{ $syncData->sync_token }}</code>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Questo token identifica univocamente il tuo account per le sincronizzazioni</p>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Endpoint API</h4>
                            <div class="code-block">
                                <code>{{ config('app.url') }}/api/v1/sync/campaigns</code>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Endpoint a cui lo script invierà i dati delle campagne</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Cosa viene sincronizzato</h4>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">📊 Dati Campagne</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Nome e ID campagna</li>
                                    <li>• Stato (Attiva/In Pausa)</li>
                                    <li>• Tipo campagna</li>
                                    <li>• Metriche di performance</li>
                                </ul>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">📈 Metriche</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Impressioni e Clic</li>
                                    <li>• Costo e Conversioni</li>
                                    <li>• CTR e CPC medio</li>
                                    <li>• Dati ultimi 30 giorni</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>