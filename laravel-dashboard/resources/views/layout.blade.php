<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Data Studio - Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: radial-gradient(circle at top, #1f2937 0, #020617 55%, #000 100%); color: #e5e7eb; min-height: 100vh; }
        .glass { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(148, 163, 184, 0.2); }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col">
        <nav class="glass fixed inset-x-0 top-0 z-50 border-b border-blue-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-3">
                        <button type="button" id="sidebarMobileToggle" class="md:hidden inline-flex items-center justify-center h-9 w-9 rounded-lg border border-slate-700/60 text-slate-200 hover:bg-slate-800/60 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-950" aria-controls="appSidebar" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 shadow-lg shadow-blue-500/20"></div>
                        <div>
                            <div class="text-sm font-bold tracking-wider uppercase">Excel Data Studio</div>
                            <div class="text-[10px] text-gray-400">Interactive Analytics Workspace</div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center gap-3">
                        @if(Session::has('logged_in'))
                            <a href="{{ route('profile.show') }}" class="text-xs text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                                {{ Session::get('username') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition-colors">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Login to Magindah</a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
        <div class="h-16"></div>

        <div class="flex flex-1">
            <div id="sidebarBackdrop" class="fixed inset-x-0 bottom-0 top-16 z-40 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity md:hidden"></div>

            <aside id="appSidebar" class="glass fixed top-16 bottom-0 left-0 z-50 w-72 -translate-x-full transition-transform duration-300 ease-out md:translate-x-0 md:sticky md:top-16 md:self-start md:h-[calc(100vh-4rem)] md:flex md:flex-col md:w-72 md:transition-[width] md:duration-300 md:ease-out border-r border-slate-800/60" data-collapsed="false">
                <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800/60">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 14l4-4 3 3 6-6"/>
                            </svg>
                        </div>
                        <div class="min-w-0 sidebar-label">
                            <div class="text-xs font-semibold tracking-wider uppercase text-slate-200">Main Menu</div>
                            <div class="text-[10px] text-slate-400 truncate">Navigation</div>
                        </div>
                    </div>
                    <button type="button" id="sidebarCollapseToggle" class="hidden md:inline-flex items-center justify-center h-9 w-9 rounded-lg border border-slate-700/60 text-slate-200 hover:bg-slate-800/60 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-950" aria-controls="appSidebar" aria-expanded="true">
                        <svg id="sidebarCollapseIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                </div>

                <nav class="p-3 flex-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ Request::is('/') ? 'bg-blue-500/15 text-blue-300 ring-1 ring-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                        <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Request::is('/') ? 'text-blue-300' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V9h6v12"/>
                            </svg>
                        </span>
                        <span class="sidebar-label">Dashboard</span>
                    </a>

                    @if(Session::has('logged_in'))
                        <div class="mt-4 px-3">
                            <div class="text-[10px] font-semibold tracking-wider uppercase text-slate-500 sidebar-label">Data Master</div>
                        </div>

                        <a href="{{ route('magindah.show') }}" class="mt-2 group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ Request::is('magindah') ? 'bg-blue-500/15 text-blue-300 ring-1 ring-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Request::is('magindah') ? 'text-blue-300' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/>
                                </svg>
                            </span>
                            <span class="sidebar-label">Magindah</span>
                        </a>

                        <a href="{{ route('approvals') }}" class="mt-2 group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ Request::is('approvals') ? 'bg-blue-500/15 text-blue-300 ring-1 ring-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Request::is('approvals') ? 'text-blue-300' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                </svg>
                            </span>
                            <span class="sidebar-label">Approvals</span>
                        </a>

                        <a href="{{ route('guide') }}" class="mt-2 group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ Request::is('guide') ? 'bg-blue-500/15 text-blue-300 ring-1 ring-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Request::is('guide') ? 'text-blue-300' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </span>
                            <span class="sidebar-label">Guide</span>
                        </a>

                        @if(Session::get('username') === 'admin')
                            <a href="{{ route('admin.users.create') }}" class="mt-2 group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ Request::is('admin/users/create') ? 'bg-blue-500/15 text-blue-300 ring-1 ring-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                                <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Request::is('admin/users/create') ? 'text-blue-300' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19a4 4 0 10-8 0"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 11h-6"/>
                                    </svg>
                                </span>
                                <span class="sidebar-label">Buat User</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="mt-2 group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ Request::is('login') ? 'bg-blue-500/15 text-blue-300 ring-1 ring-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Request::is('login') ? 'text-blue-300' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                                </svg>
                            </span>
                            <span class="sidebar-label">Login</span>
                        </a>
                    @endif
                </nav>

                <div class="p-3 border-t border-slate-800/60">
                    @if(Session::has('logged_in'))
                        <a href="{{ route('profile.show') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ Request::is('profile') ? 'bg-blue-500/15 text-blue-300 ring-1 ring-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                            <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ Request::is('profile') ? 'text-blue-300' : 'text-slate-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                                </svg>
                            </span>
                            <span class="sidebar-label">Profile</span>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors text-red-300 hover:bg-red-500/10 hover:text-red-200">
                                <span class="h-10 w-10 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-300 group-hover:text-red-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9"/>
                                    </svg>
                                </span>
                                <span class="sidebar-label">Keluar</span>
                            </button>
                        </form>
                    @endif
                </div>
            </aside>

            <div class="flex-1 min-w-0 flex flex-col">
                <main class="flex-1 p-4 md:p-8 md:transition-[margin] md:duration-300">
                    <div class="max-w-7xl mx-auto">
                        @yield('content')
                    </div>
                </main>

                <footer class="p-6 text-xs text-gray-500 border-t border-gray-800/50">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div>© 2026 Magindah — by Janiator Jr.</div>
                        <div>powered by <span class="text-red-500 font-semibold">Telkomsel</span></div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mobileToggle = document.getElementById('sidebarMobileToggle');
            const collapseToggle = document.getElementById('sidebarCollapseToggle');
            const collapseIcon = document.getElementById('sidebarCollapseIcon');
            if (!sidebar || !backdrop || !mobileToggle || !collapseToggle || !collapseIcon) return;

            const storageKey = 'excelDataStudio.sidebarCollapsed';
            const mq = window.matchMedia('(min-width: 768px)');

            function applyCollapsed(isCollapsed) {
                sidebar.dataset.collapsed = isCollapsed ? 'true' : 'false';
                if (isCollapsed) {
                    sidebar.classList.remove('md:w-72');
                    sidebar.classList.add('md:w-20');
                    sidebar.querySelectorAll('.sidebar-label').forEach(el => el.classList.add('hidden'));
                    collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>';
                    collapseToggle.setAttribute('aria-expanded', 'false');
                } else {
                    sidebar.classList.remove('md:w-20');
                    sidebar.classList.add('md:w-72');
                    sidebar.querySelectorAll('.sidebar-label').forEach(el => el.classList.remove('hidden'));
                    collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>';
                    collapseToggle.setAttribute('aria-expanded', 'true');
                }
            }

            function openMobile() {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100');
                mobileToggle.setAttribute('aria-expanded', 'true');
                document.documentElement.classList.add('overflow-hidden');
            }

            function closeMobile() {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100');
                mobileToggle.setAttribute('aria-expanded', 'false');
                document.documentElement.classList.remove('overflow-hidden');
            }

            function toggleMobile() {
                if (mq.matches) return;
                if (sidebar.classList.contains('-translate-x-full')) openMobile();
                else closeMobile();
            }

            const stored = localStorage.getItem(storageKey);
            if (stored === 'true') applyCollapsed(true);

            collapseToggle.addEventListener('click', function() {
                if (!mq.matches) return;
                const isCollapsed = sidebar.dataset.collapsed === 'true';
                const next = !isCollapsed;
                applyCollapsed(next);
                localStorage.setItem(storageKey, next ? 'true' : 'false');
            });

            mobileToggle.addEventListener('click', toggleMobile);
            backdrop.addEventListener('click', closeMobile);
            sidebar.addEventListener('click', function(e) {
                const a = e.target.closest('a');
                if (!a) return;
                if (!mq.matches) closeMobile();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Escape') return;
                if (!mq.matches) closeMobile();
            });
            mq.addEventListener('change', function() {
                closeMobile();
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
