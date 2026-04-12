<nav class="bg-white shadow-lg sticky top-0 z-50 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="FixLA Logo" class="h-10 w-auto" onerror="this.src='https://via.placeholder.com/40x40/3b82f6/ffffff?text=RC'">
                <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">FixLA Lamongan</span>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-[#3b82f6] transition duration-300 font-medium {{ request()->is('/') ? 'text-[#3b82f6] border-b-2 border-[#3b82f6]' : '' }}">Beranda</a>
                <a href="{{ url('/fitur') }}" class="text-gray-700 hover:text-[#3b82f6] transition duration-300 font-medium {{ request()->is('fitur') ? 'text-[#3b82f6] border-b-2 border-[#3b82f6]' : '' }}">Fitur</a>
                <a href="{{ url('/about') }}" class="text-gray-700 hover:text-[#3b82f6] transition duration-300 font-medium {{ request()->is('about') ? 'text-[#3b82f6] border-b-2 border-[#3b82f6]' : '' }}">Tentang</a>
                <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-[#3b82f6] transition duration-300 font-medium {{ request()->is('contact') ? 'text-[#3b82f6] border-b-2 border-[#3b82f6]' : '' }}">Kontak</a>
                
                <!-- Menu Admin - Hanya muncul jika sudah login -->
                @if(session('admin_login'))
                    <div class="relative group">
                        <a href="#" class="text-gray-700 hover:text-[#3b82f6] transition duration-300 font-medium flex items-center gap-1">
                            <i class="fas fa-user-shield"></i> Admin
                            <i class="fas fa-chevron-down text-xs"></i>
                        </a>
                        <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg mt-2 w-48 z-50 border">
                            <a href="{{ url('/admin') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-[#3b82f6] rounded-t-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                            <a href="{{ url('/admin/reports') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-[#3b82f6]">
                                <i class="fas fa-file-alt mr-2"></i> Kelola Laporan
                            </a>
                            <a href="{{ url('/admin/repairs') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-[#3b82f6]">
                                <i class="fas fa-tools mr-2"></i> Kelola Perbaikan
                            </a>
                            <hr class="my-1">
                            <a href="{{ url('/logout') }}" class="block px-4 py-2 text-red-600 hover:bg-red-50 rounded-b-lg">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Tombol Admin / Login -->
            <div class="flex space-x-3">
                @if(session('admin_login'))
                    <!-- Jika sudah login, tampilkan dropdown di tombol -->
                    <div class="relative group">
                        <button class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white px-5 py-2 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                            <i class="fas fa-user-circle"></i> {{ session('admin_nama') }}
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 hidden group-hover:block bg-white shadow-lg rounded-lg mt-2 w-48 z-50 border">
                            <a href="{{ url('/admin') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-t-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                            <a href="{{ url('/admin/reports') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-file-alt mr-2"></i> Kelola Laporan
                            </a>
                            <a href="{{ url('/admin/repairs') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-tools mr-2"></i> Kelola Perbaikan
                            </a>
                            <hr class="my-1">
                            <a href="{{ url('/logout') }}" class="block px-4 py-2 text-red-600 hover:bg-red-50 rounded-b-lg">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Jika belum login, tombol langsung ke halaman login -->
                    <a href="{{ url('/login') }}" class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white px-5 py-2 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Login Admin
                    </a>
                @endif
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700 mobile-menu-button focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div class="hidden mobile-menu md:hidden bg-white border-t shadow-lg absolute left-0 right-0 z-50">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ url('/') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300 {{ request()->is('/') ? 'text-[#3b82f6] bg-blue-50' : '' }}">Beranda</a>
            <a href="{{ url('/fitur') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300 {{ request()->is('fitur') ? 'text-[#3b82f6] bg-blue-50' : '' }}">Fitur</a>
            <a href="{{ url('/about') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300 {{ request()->is('about') ? 'text-[#3b82f6] bg-blue-50' : '' }}">Tentang</a>
            <a href="{{ url('/contact') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300 {{ request()->is('contact') ? 'text-[#3b82f6] bg-blue-50' : '' }}">Kontak</a>
            
            <!-- Mobile Menu - Admin Section -->
            @if(session('admin_login'))
                <div class="border-t pt-2 mt-2">
                    <div class="text-gray-500 text-xs px-3 py-1">MENU ADMIN</div>
                    <a href="{{ url('/admin') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ url('/admin/reports') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-file-alt mr-2"></i> Kelola Laporan
                    </a>
                    <a href="{{ url('/admin/repairs') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-tools mr-2"></i> Kelola Perbaikan
                    </a>
                    <a href="{{ url('/logout') }}" class="block py-2 px-3 text-red-600 hover:bg-red-50 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            @else
                <a href="{{ url('/login') }}" class="block py-2 px-3 text-gray-700 hover:text-[#3b82f6] hover:bg-blue-50 rounded-lg transition duration-300">
                    <i class="fas fa-lock mr-2"></i> Login Admin
                </a>
            @endif
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMenu.classList.toggle('hidden');
            });
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        }
        
        // Navbar scroll effect - hide on scroll down, show on scroll up
        let lastScroll = 0;
        const navbar = document.getElementById('navbar');
        let ticking = false;
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    const currentScroll = window.pageYOffset;
                    
                    if (currentScroll > lastScroll && currentScroll > 100) {
                        // Scroll down - hide navbar
                        navbar.style.transform = 'translateY(-100%)';
                    } else {
                        // Scroll up - show navbar
                        navbar.style.transform = 'translateY(0)';
                    }
                    
                    lastScroll = currentScroll;
                    ticking = false;
                });
                ticking = true;
            }
        });
    });
</script>