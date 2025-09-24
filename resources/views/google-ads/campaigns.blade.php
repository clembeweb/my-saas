<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Manager - SaaS Platform</title>
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
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .campaign-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .campaign-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-enabled {
            background: #dcfce7;
            color: #166534;
        }
        .status-paused {
            background: #fef3c7;
            color: #92400e;
        }
        .channel-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .channel-search {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .channel-display {
            background: #f3e8ff;
            color: #7c3aed;
        }
        .channel-video {
            background: #fecaca;
            color: #dc2626;
        }
        .channel-shopping {
            background: #d1fae5;
            color: #059669;
        }
        .channel-performance-max {
            background: #fed7d7;
            color: #e53e3e;
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold gradient-text">Campaign Manager</h1>
                            <p class="text-gray-600">Gestione completa delle campagne Google Ads</p>
                        </div>
                    </div>
                    <a href="/google-ads" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        ← Torna al Dashboard
                    </a>
                </div>

                <!-- Demo Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-blue-900">Modalità Demo</h3>
                            <p class="text-blue-800 text-sm mt-1">
                                Stai visualizzando dati dimostrativi. Per accedere ai dati reali delle campagne, è necessario un developer token Google Ads approvato per account di produzione.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Performance Summary -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                    @php
                        $totalImpressions = collect($campaigns)->sum('impressions');
                        $totalClicks = collect($campaigns)->sum('clicks');
                        $totalCost = collect($campaigns)->sum('cost_micros') / 1000000;
                        $totalConversions = collect($campaigns)->sum('conversions');
                        $avgCtr = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;
                    @endphp

                    <div class="metric-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalImpressions) }}</h3>
                        <p class="text-sm text-gray-600">Impressioni Totali</p>
                    </div>

                    <div class="metric-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalClicks) }}</h3>
                        <p class="text-sm text-gray-600">Clic Totali</p>
                    </div>

                    <div class="metric-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">€{{ number_format($totalCost, 2) }}</h3>
                        <p class="text-sm text-gray-600">Spesa Totale</p>
                    </div>

                    <div class="metric-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalConversions) }}</h3>
                        <p class="text-sm text-gray-600">Conversioni</p>
                    </div>

                    <div class="metric-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($avgCtr, 2) }}%</h3>
                        <p class="text-sm text-gray-600">CTR Medio</p>
                    </div>
                </div>

                <!-- Campaigns List -->
                <div class="space-y-6">
                    @foreach($campaigns as $campaign)
                    <div class="campaign-card">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $campaign->campaign_name }}</h3>
                                    <span class="status-badge status-{{ strtolower($campaign->campaign_status) }}">
                                        {{ $campaign->campaign_status === 'ENABLED' ? 'Attiva' : 'In Pausa' }}
                                    </span>
                                    <span class="channel-badge channel-{{ str_replace('_', '-', strtolower($campaign->advertising_channel_type)) }}">
                                        {{ str_replace('_', ' ', $campaign->advertising_channel_type) }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm">ID Campagna: {{ $campaign->campaign_id }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($campaign->impressions) }}</div>
                                <div class="text-sm text-gray-600">Impressioni</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($campaign->clicks) }}</div>
                                <div class="text-sm text-gray-600">Clic</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">€{{ number_format($campaign->cost_micros / 1000000, 2) }}</div>
                                <div class="text-sm text-gray-600">Spesa</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ number_format($campaign->conversions) }}</div>
                                <div class="text-sm text-gray-600">Conversioni</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ number_format($campaign->ctr, 2) }}%</div>
                                <div class="text-sm text-gray-600">CTR</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">€{{ number_format($campaign->average_cpc / 1000000, 3) }}</div>
                                <div class="text-sm text-gray-600">CPC Medio</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                Periodo: {{ $campaign->start_date }} - {{ $campaign->end_date }}
                            </div>
                            <div class="flex space-x-3">
                                <button class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                    Modifica
                                </button>
                                <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                    Report
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>