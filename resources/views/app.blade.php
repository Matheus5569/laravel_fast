<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }

            /* ===== LOADING STYLES ===== */
            .inertia-loading {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.7); /* MAIS TRANSPARENTE */
                backdrop-filter: blur(2px); /* Leve desfoque */
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity 0.3s ease;
            }

            .dark .inertia-loading {
                background-color: rgba(0, 0, 0, 0.7); /* MAIS TRANSPARENTE no dark mode */
            }

            /* Loading para navegação entre telas */
            .navigation-loading {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.7); /* MAIS TRANSPARENTE */
                backdrop-filter: blur(2px); /* Leve desfoque */
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            .dark .navigation-loading {
                background: rgba(0, 0, 0, 0.7); /* MAIS TRANSPARENTE no dark mode */
            }

            .navigation-loading.active {
                display: flex;
            }

            /* ===== LOADER BONITINHO ===== */
            .inertia-loading .loader,
            .navigation-loading .loader {
                width: 60px;
                aspect-ratio: 1;
                border-radius: 50%;
                border: 8px solid #0000;
                border-right-color: #ffa600;
                position: relative;
                animation: l24 1s infinite linear;
            }
            
            .inertia-loading .loader:before,
            .inertia-loading .loader:after,
            .navigation-loading .loader:before,
            .navigation-loading .loader:after {
                content: "";
                position: absolute;
                inset: -8px;
                border-radius: 50%;
                border: inherit;
                animation: inherit;
                animation-duration: 2s;
            }
            
            .inertia-loading .loader:after,
            .navigation-loading .loader:after {
                animation-duration: 4s;
            }

            /* Versão para dark mode */
            .dark .inertia-loading .loader,
            .dark .navigation-loading .loader {
                border-right-color: #ffb347;
            }
            
            @keyframes l24 {
                100% { transform: rotate(1turn); }
            }

            .loading-text {
                margin-top: 1.5rem;
                font-size: 1.2rem;
                color: #4a5568;
                font-weight: 500;
                animation: pulse 2s infinite;
            }

            .dark .loading-text {
                color: #cbd5e0;
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        {{-- Loading inicial da aplicação --}}
        <div class="inertia-loading" id="initial-loading">
            <div class="text-center">
                <div class="loader"></div>
                <div class="loading-text"><center>Carregando...</center></div>
            </div>
        </div>

        @inertia

        {{-- Loading para navegação entre telas --}}
        <div class="navigation-loading" id="navigationLoading">
            <div class="text-center">
                <div class="loader"></div>
                <p class="loading-text"><center>Carregando...</center></p>
            </div>
        </div>

        <script>
            // Esconde o loading inicial quando a página carregar
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    const loading = document.getElementById('initial-loading');
                    if (loading) {
                        loading.style.opacity = '0';
                        setTimeout(() => {
                            loading.style.display = 'none';
                        }, 300);
                    }
                }, 500);
            });

            // Controle do loading de navegação
            document.addEventListener('inertia:start', () => {
                document.getElementById('navigationLoading').classList.add('active');
            });

            document.addEventListener('inertia:finish', () => {
                setTimeout(() => {
                    document.getElementById('navigationLoading').classList.remove('active');
                }, 300);
            });
        </script>
    </body>
</html>