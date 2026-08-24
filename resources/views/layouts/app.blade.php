<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Online Support System')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

     <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
   <style>
     .glass-input {
            background: rgba(245, 245, 245, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #f8fafc;
            transition: all 0.2s ease;
        }

        .glass-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            background: rgba(245, 245, 245, 0.4);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            color: black !important;
        }

        /* Also target textarea if you use it */
        textarea {
            color: black !important;
        }
   </style>
</head>

<body class="bg-white relative flex flex-col justify-between overflow-x-hidden">

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-40 border-b border-slate-800 bg-slate-900/95 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo / Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-extrabold text-base shadow-md">
                            S
                        </div>
                        <div>
                            <span class="text-base sm:text-lg font-bold text-white tracking-tight">SupportPro</span>
                            <span class="text-[10px] sm:text-xs block text-slate-400 font-medium">After-Sales Ticket Hub</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Action Links -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-sm text-slate-300 hover:text-white transition-colors px-3 py-2 rounded-lg hover:bg-slate-800/60">
                        Customer Portal
                    </a>
                    
                    @auth
                        <a href="{{ route('agent.dashboard') }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 bg-blue-500/10 border border-blue-500/20 px-3.5 py-1.5 rounded-lg hover:bg-blue-500/20 transition-all flex items-center space-x-2">
                            <span>Agent Dashboard</span>
                        </a>
                        <form method="POST" action="{{ route('agent.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-slate-400 hover:text-red-400 px-3 py-2 rounded-lg hover:bg-slate-800/60 transition-colors">
                                Logout ({{ Auth::user()->name }})
                            </button>
                        </form>
                    @else
                        <a href="{{ route('agent.login') }}" class="text-sm text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg border border-slate-700 transition-all flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Agent Login</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex md:hidden items-center">
                    <button type="button" onclick="toggleMobileMenu()" class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 focus:outline-none" aria-label="Toggle Mobile Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon-open" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800 bg-slate-900 px-4 pt-3 pb-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800">
                Customer Portal
            </a>
            @auth
                <a href="{{ route('agent.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-blue-400 hover:bg-slate-800">
                    Agent Dashboard
                </a>
                <form method="POST" action="{{ route('agent.logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-400 hover:bg-slate-800">
                        Logout ({{ Auth::user()->name }})
                    </button>
                </form>
            @else
                <a href="{{ route('agent.login') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-blue-400 hover:bg-slate-800">
                    Agent Login
                </a>
            @endauth
        </div>
    </nav>


    <!-- Global Alert / Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-5 z-50 flex flex-col space-y-3 max-w-md w-full px-4 pointer-events-none"></div>

    <!-- Main Content Area -->
    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

   

    <!-- Global Toast Script -->
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const isSuccess = type === 'success';
            const bgClass = isSuccess ? 'bg-slate-900/95 border-emerald-500/50 text-emerald-300' : 'bg-slate-900/95 border-rose-500/50 text-rose-300';
            const iconSvg = isSuccess 
                ? '<svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                : '<svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
            
            toast.className = `pointer-events-auto flex items-center space-x-3 p-4 rounded-xl border backdrop-blur-md shadow-2xl transition-all duration-300 transform translate-x-full ${bgClass}`;
            toast.innerHTML = `
                ${iconSvg}
                <div class="text-sm font-medium leading-snug">${message}</div>
            `;
            
            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 50);

            // Animate out & remove
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');
            if (menu && iconOpen && iconClose) {
                menu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            }
        }
    </script>

    @stack('scripts')

</body>

</html>