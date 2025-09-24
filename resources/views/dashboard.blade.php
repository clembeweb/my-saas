<x-app-layout>
    <style>
        body {
            margin: 0;
            background: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .sidebar {
            width: 260px;
            background: #4c5a89;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            color: white;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .sidebar-nav li {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-nav .icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            opacity: 0.7;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .top-bar {
            background: white;
            border-bottom: 1px solid #e1e5e9;
            padding: 15px 30px;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .content-area {
            padding: 30px;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            border-radius: 8px;
            padding: 24px;
            border: 1px solid #e1e5e9;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .metric-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .metric-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .metric-title {
            font-size: 14px;
            color: #5a6c7d;
            font-weight: 500;
        }

        .metric-value {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .metric-change {
            font-size: 12px;
            font-weight: 600;
        }

        .metric-change.positive {
            color: #22c55e;
        }

        .metric-change.negative {
            color: #ef4444;
        }

        .tools-section {
            background: white;
            border-radius: 8px;
            border: 1px solid #e1e5e9;
            overflow: hidden;
        }

        .tools-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e1e5e9;
            background: #f8f9fa;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1px;
            background: #e1e5e9;
        }

        .tool-item {
            background: white;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .tool-item:hover {
            background: #f8f9fa;
        }

        .tool-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .tool-icon {
            width: 32px;
            height: 32px;
            margin-right: 12px;
        }

        .tool-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        .tool-description {
            color: #5a6c7d;
            font-size: 14px;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .tool-btn {
            background: #4c5a89;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .tool-btn:hover {
            background: #3d4a73;
            transform: translateY(-1px);
        }

        .tool-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #5a6c7d;
            margin-bottom: 24px;
        }
    </style>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2 class="text-lg font-bold">SaaS Platform</h2>
            <p class="text-sm opacity-70">Marketing Suite</p>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}" class="active">
                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('google-ads.dashboard') }}">
                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google Ads
                </a>
            </li>
            <li>
                <a href="#" class="opacity-50">
                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook Ads
                </a>
            </li>
            <li>
                <a href="#" class="opacity-50">
                    <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    Analytics
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Benvenuto, {{ Auth::user()->name }}! Gestisci i tuoi strumenti marketing da un'unica piattaforma.</p>
            </div>
        </div>

        <div class="content-area">
            <!-- Metrics Overview -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            </svg>
                        </div>
                        <div class="metric-title">Campagne Attive</div>
                    </div>
                    <div class="metric-value">12</div>
                    <div class="metric-change positive">+8% vs mese scorso</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-icon" style="background: #f0fdf4; color: #16a34a;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 18c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                            </svg>
                        </div>
                        <div class="metric-title">Conversioni</div>
                    </div>
                    <div class="metric-value">1,247</div>
                    <div class="metric-change positive">+15.2% vs mese scorso</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-icon" style="background: #fef3c7; color: #d97706;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                            </svg>
                        </div>
                        <div class="metric-title">Spesa Pubblicitaria</div>
                    </div>
                    <div class="metric-value">€8,420</div>
                    <div class="metric-change negative">-3.1% vs mese scorso</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-icon" style="background: #f3e8ff; color: #9333ea;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                            </svg>
                        </div>
                        <div class="metric-title">ROAS</div>
                    </div>
                    <div class="metric-value">4.2x</div>
                    <div class="metric-change positive">+12.5% vs mese scorso</div>
                </div>
            </div>

            <!-- Tools Section -->
            <div class="tools-section">
                <div class="tools-header">
                    <h2 class="text-lg font-semibold text-gray-800">Strumenti Marketing</h2>
                    <p class="text-sm text-gray-600 mt-1">Accedi ai tuoi strumenti per la gestione delle campagne</p>
                </div>

                <div class="tools-grid">
                    <div class="tool-item">
                        <div class="tool-header">
                            <svg class="tool-icon" fill="#4285f4" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <div class="tool-title">Google Ads</div>
                        </div>
                        <div class="tool-description">
                            Gestisci le tue campagne Google Ads, monitora le performance e ottimizza il budget pubblicitario con strumenti avanzati.
                        </div>
                        <a href="{{ route('google-ads.dashboard') }}" class="tool-btn">
                            Accedi al Tool
                        </a>
                    </div>

                    <div class="tool-item">
                        <div class="tool-header">
                            <svg class="tool-icon" fill="#1877f2" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <div class="tool-title">Facebook Ads</div>
                        </div>
                        <div class="tool-description">
                            Crea e gestisci campagne pubblicitarie su Facebook e Instagram con targeting avanzato e analytics dettagliati.
                        </div>
                        <button disabled class="tool-btn" style="background: #cbd5e0; cursor: not-allowed;">
                            Prossimamente
                        </button>
                    </div>

                    <div class="tool-item">
                        <div class="tool-header">
                            <svg class="tool-icon" fill="#ea4335" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                            </svg>
                            <div class="tool-title">Analytics Pro</div>
                        </div>
                        <div class="tool-description">
                            Analizza il traffico web, monitora le conversioni e ottimizza le performance del tuo sito con report avanzati.
                        </div>
                        <button disabled class="tool-btn" style="background: #cbd5e0; cursor: not-allowed;">
                            Prossimamente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>