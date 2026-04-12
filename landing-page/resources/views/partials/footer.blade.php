<footer class="bg-gray-700 text-white pt-12 pb-6 mt-auto relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Company Info -->
            <div>
                <div class="flex items-center space-x-3 mb-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto" onerror="this.src='https://via.placeholder.com/32x32/3b82f6/ffffff?text=RC'">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Aplikasi pelaporan jalan rusak untuk masyarakat Lamongan. Cepat, akurat, dan transparan.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="font-semibold text-lg mb-4 text-white">Tautan Cepat</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-[#60a5fa] transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Beranda</a></li>
                    <li><a href="{{ url('/fitur') }}" class="hover:text-[#60a5fa] transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Fitur</a></li>
                    <li><a href="{{ url('/about') }}" class="hover:text-[#60a5fa] transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Tentang</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-[#60a5fa] transition flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i>Kontak</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="font-semibold text-lg mb-4 text-white">Kontak</h3>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li class="flex items-start">
                        <i class="fas fa-phone-alt mt-1 mr-3 text-[#60a5fa]"></i>
                        <span>(0322) 123456</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-envelope mt-1 mr-3 text-[#60a5fa]"></i>
                        <span>info@fixla.id</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-[#60a5fa]"></i>
                        <span>Jl. Raya Lamongan No. 123, Lamongan, Jawa Timur</span>
                    </li>
                </ul>
            </div>
            
            <!-- Social Media -->
            <div>
                <h3 class="font-semibold text-lg mb-4 text-white">Ikuti Kami</h3>
                <div class="flex space-x-3">
                    <a href="#" class="bg-gray-800 hover:bg-[#3b82f6] w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="bg-gray-800 hover:bg-[#3b82f6] w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="bg-gray-800 hover:bg-[#3b82f6] w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="bg-gray-800 hover:bg-[#3b82f6] w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-800 pt-6 text-center text-gray-400 text-sm">
            <p>&copy; 2026 FixLA. All rights reserved. | Dibuat untuk masyarakat Lamongan</p>
        </div>
    </div>
</footer>