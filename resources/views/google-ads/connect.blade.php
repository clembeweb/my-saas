<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connetti Google Ads - SaaS Platform</title>
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
        .connect-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 48px;
            text-align: center;
            color: white;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }
        .connect-btn {
            background: white;
            color: #374151;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-decoration: none;
        }
        .connect-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }
        .feature-card:hover {
            border-color: #667eea;
            transform: translateY(-4px);
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
        .benefit-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .benefit-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
                <div class="mb-8">
                    <h1 class="text-3xl font-bold gradient-text mb-2">Google Ads Integration</h1>
                    <p class="text-gray-600">Connetti il tuo account Google Ads per iniziare a gestire le campagne</p>
                </div>

                <!-- Main Connect Card -->
                <div class="connect-card mb-12">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-3xl flex items-center justify-center mx-auto mb-8">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </div>
                    <h2 class="text-4xl font-bold mb-6">Connetti Google Ads</h2>
                    <p class="text-xl opacity-90 mb-10 max-w-2xl mx-auto leading-relaxed">
                        Accedi alle tue campagne pubblicitarie e sfrutta il potere dell'automazione intelligente per ottimizzare le performance
                    </p>
                    <a href="{{ route('google-ads.connect') }}" class="connect-btn">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        </svg>
                        Continua con Google
                    </a>
                </div>

                <!-- Process Steps -->
                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    <div class="feature-card">
                        <div class="step-number">1</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Autenticazione Sicura</h3>
                        <p class="text-gray-600">Connessione protetta tramite OAuth 2.0 con protocolli di sicurezza enterprise</p>
                    </div>
                    <div class="feature-card">
                        <div class="step-number">2</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Sincronizzazione</h3>
                        <p class="text-gray-600">Importazione automatica di tutti gli account, campagne e dati storici</p>
                    </div>
                    <div class="feature-card">
                        <div class="step-number">3</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Gestione Avanzata</h3>
                        <p class="text-gray-600">Dashboard professionale con strumenti di ottimizzazione intelligente</p>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="bg-gray-50 rounded-3xl p-12">
                    <h3 class="text-3xl font-bold text-center gradient-text mb-12">Perché scegliere la nostra piattaforma?</h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="benefit-card">
                            <div class="flex items-start space-x-4">
                                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-3">Sicurezza Enterprise</h4>
                                    <p class="text-gray-600">Crittografia end-to-end, conformità GDPR e protezione avanzata dei dati sensibili</p>
                                </div>
                            </div>
                        </div>

                        <div class="benefit-card">
                            <div class="flex items-start space-x-4">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-3">Performance Ottimizzate</h4>
                                    <p class="text-gray-600">Algoritmi di machine learning per massimizzare il ROI e ridurre i costi</p>
                                </div>
                            </div>
                        </div>

                        <div class="benefit-card">
                            <div class="flex items-start space-x-4">
                                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-3">Analytics Avanzati</h4>
                                    <p class="text-gray-600">Report dettagliati, insights predittivi e dashboard personalizzabili</p>
                                </div>
                            </div>
                        </div>

                        <div class="benefit-card">
                            <div class="flex items-start space-x-4">
                                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-3">Supporto Dedicato</h4>
                                    <p class="text-gray-600">Team di esperti certificati Google Ads disponibile 24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Link -->
                <div class="text-center mt-12">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Torna alla Dashboard Principale
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>