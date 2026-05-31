<nav class="z-[9999] transition-all duration-500" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 transition-all duration-500 relative" id="nav-content">

            {{-- 1. Logo --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="group">
                    <img src="{{ asset('images/logo-dark.png') }}" alt="FixLA Logo"
                        class="h-10 w-auto transition-transform duration-500 group-hover:scale-110"
                        onerror="this.src='https://via.placeholder.com/120x120/3b82f6/ffffff?text=FixLA'">
                </a>
            </div>

            {{-- 2. Desktop Links --}}
            <div class="hidden md:flex items-center space-x-10">
                @php
                    $navLinks = [
                        ['url' => '/', 'label' => 'BERANDA'],
                        ['url' => '/fitur', 'label' => 'FITUR'],
                        ['url' => '/about', 'label' => 'TENTANG'],
                        ['url' => '/contact', 'label' => 'KONTAK'],
                    ];
                @endphp

                @foreach($navLinks as $link)
                    <a href="{{ url($link['url']) }}" class="relative text-[12px] font-bold tracking-[0.15em] transition-all duration-300 group {{ request()->is($link['url'] == '/' ? '/' : ltrim($link['url'], '/')) ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        {{ $link['label'] }}
                        <span class="absolute -bottom-1 left-0 w-0 h-[2px] bg-blue-600 transition-all duration-300 group-hover:w-full {{ request()->is($link['url'] == '/' ? '/' : ltrim($link['url'], '/')) ? 'w-full' : '' }}"></span>
                    </a>
                @endforeach
            </div>

            {{-- 3. Right Side Buttons --}}
            <div class="flex items-center gap-3">
                {{-- Login Button --}}
                <a href="{{ url('/login') }}" id="nav-login-btn" class="px-5 py-2 border-2 border-blue-600 text-blue-600 font-bold text-[11px] tracking-[0.12em] rounded-full hover:bg-blue-600 hover:text-white hover:-translate-y-0.5 transition-all duration-300 hidden lg:block uppercase">
                    Masuk
                </a>

                {{-- Download APK --}}
                <a href="https://github.com/OktavianF/FIxLa/releases/download/1.0.0/FixLA.apk" class="px-5 py-2 bg-blue-600 text-white font-bold text-[11px] tracking-[0.12em] rounded-full shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all duration-300 hidden lg:block uppercase">
                    Download APK
                </a>

                {{-- User Menu --}}
                <div class="hidden md:block">
                    <div id="nav-user-menu" class="hidden relative">
                        <button onclick="document.getElementById('user-dropdown').classList.toggle('hidden')" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-all duration-300">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center">
                                <span id="nav-user-initial" class="text-white text-xs font-bold">U</span>
                            </div>
                            <span id="nav-user-name" class="text-sm font-bold text-slate-800 max-w-[120px] truncate hidden lg:block">User</span>
                            <i class="fas fa-chevron-down text-[8px] text-slate-400"></i>
                        </button>
                        <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[99999] py-2">
                            <a href="{{ url('/lapor') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-slate-700">
                                <i class="fas fa-plus-circle text-blue-600 w-5 text-center"></i> Buat Laporan
                            </a>
                            <a href="{{ url('/laporan-saya') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-slate-700">
                                <i class="fas fa-list-alt text-blue-600 w-5 text-center"></i> Laporan Saya
                            </a>
                            <a href="{{ url('/peta') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-slate-700">
                                <i class="fas fa-map-marked-alt text-blue-600 w-5 text-center"></i> Peta Heatmap
                            </a>
                            <a href="{{ url('/profil') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-slate-700">
                                <i class="fas fa-user-circle text-blue-600 w-5 text-center"></i> Profil Saya
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <button onclick="Auth.logout()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-all text-sm font-semibold text-red-500">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button class="text-gray-800 mobile-menu-button ml-2 p-2 transition-all">
                        <i class="fas fa-bars-staggered text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu Dropdown --}}
            <div class="hidden mobile-menu absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[99999] py-2 origin-top-right md:hidden">
                @foreach($navLinks as $link)
                    <a href="{{ url($link['url']) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold {{ request()->is($link['url'] == '/' ? '/' : ltrim($link['url'], '/')) ? 'text-blue-600' : 'text-slate-700' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                
                <div class="border-t border-slate-100 my-1"></div>
                
                {{-- Unauthenticated Mobile Links --}}
                <div id="nav-login-btn-mobile" class="block">
                    <a href="{{ url('/login') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-blue-600">
                        <i class="fas fa-sign-in-alt w-5 text-center"></i> Masuk
                    </a>
                </div>
                
                {{-- Authenticated Mobile Links --}}
                <div id="nav-user-menu-mobile" class="hidden">
                    <a href="{{ url('/lapor') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-blue-600">
                        <i class="fas fa-plus-circle w-5 text-center"></i> Buat Laporan
                    </a>
                    <a href="{{ url('/laporan-saya') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-slate-700">
                        <i class="fas fa-list-alt w-5 text-center"></i> Laporan Saya
                    </a>
                    <a href="{{ url('/peta') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-slate-700">
                        <i class="fas fa-map-marked-alt w-5 text-center"></i> Peta Heatmap
                    </a>
                    <a href="{{ url('/profil') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-sm font-semibold text-slate-700">
                        <i class="fas fa-user-circle w-5 text-center"></i> Profil Saya
                    </a>
                    <button onclick="Auth.logout()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-all text-sm font-semibold text-red-500">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                    </button>
                </div>
            </div>

        </div>
    </div>
</nav>

<style>
    /* Default State: Putih Transparan Mewah */
    #navbar {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.4s ease-in-out;
    }

    /* Fixed State */
    #navbar.fixed {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
    }

    /* Scrolled State: Lebih Solid & Lebih Slim */
    #navbar.scrolled {
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid rgba(0, 0, 0, 0.02);
    }

    #navbar.scrolled #nav-content {
        height: 56px;
    }

    body {
        padding-top: 0 !important;
    }

    body.navbar-fixed {
        padding-top: 64px !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.getElementById('navbar');
        const mobileMenuBtn = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');

        window.addEventListener('scroll', function () {
            if (window.scrollY > 30) {
                navbar.classList.add('fixed', 'scrolled');
                document.body.classList.add('navbar-fixed');
            } else {
                navbar.classList.remove('fixed', 'scrolled');
                document.body.classList.remove('navbar-fixed');
            }
        });

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent closing immediately
                const isHidden = mobileMenu.classList.toggle('hidden');
                mobileMenuBtn.innerHTML = isHidden
                    ? '<i class="fas fa-bars-staggered text-xl"></i>'
                    : '<i class="fas fa-times text-xl"></i>';
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('user-dropdown');
            const userMenu = document.getElementById('nav-user-menu');
            
            if (dropdown && userMenu && !userMenu.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
            
            if (mobileMenu && mobileMenuBtn && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars-staggered text-xl"></i>';
            }
        });
    });
</script>