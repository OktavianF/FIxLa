<nav class="z-[9999] transition-all duration-500" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 transition-all duration-500" id="nav-content">

            <div class="flex items-center">
                <a href="{{ url('/') }}" class="group">
                    <img src="{{ asset('images/logo-dark.png') }}" alt="FixLA Logo"
                        class="h-10 w-auto transition-transform duration-500 group-hover:scale-110"
                        onerror="this.src='https://via.placeholder.com/120x120/3b82f6/ffffff?text=FixLA'">
                </a>
            </div>

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
                            <a href="{{ url($link['url']) }}" class="relative text-[12px] font-bold tracking-[0.15em] transition-all duration-300 group
                                   {{ request()->is($link['url'] == '/' ? '/' : ltrim($link['url'], '/'))
                    ? 'text-blue-600'
                    : 'text-gray-600 hover:text-blue-600' }}">
                                {{ $link['label'] }}
                                <span
                                    class="absolute -bottom-1 left-0 w-0 h-[2px] bg-blue-600 transition-all duration-300 group-hover:w-full {{ request()->is($link['url'] == '/' ? '/' : ltrim($link['url'], '/')) ? 'w-full' : '' }}"></span>
                            </a>
                @endforeach
            </div>

            <div class="flex items-center">
                <a href="{{ asset('apk/fixla.apk') }}"
                    class="px-5 py-2 bg-blue-600 text-white font-bold text-[11px] tracking-[0.12em] rounded-full shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all duration-300 hidden lg:block uppercase">
                    Download APK
                </a>

                <button class="md:hidden text-gray-800 mobile-menu-button ml-4 p-2 transition-all">
                    <i class="fas fa-bars-staggered text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="hidden mobile-menu md:hidden fixed inset-0 z-[-1] bg-white/95 backdrop-blur-xl p-10 pt-32">
        <div class="flex flex-col space-y-8 text-center uppercase tracking-widest">
            @foreach($navLinks as $link)
                <a href="{{ url($link['url']) }}"
                    class="text-xl font-bold {{ request()->is($link['url'] == '/' ? '/' : ltrim($link['url'], '/')) ? 'text-blue-600' : 'text-gray-400' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
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
        /* Mengecil sedikit saat scroll */
    }

    /* Menghapus padding-top default agar hero section bisa naik ke belakang navbar */
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
            mobileMenuBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden', !isHidden);
                mobileMenuBtn.innerHTML = isHidden
                    ? '<i class="fas fa-bars-staggered text-xl"></i>'
                    : '<i class="fas fa-times text-xl"></i>';
            });
        }
    });
</script>