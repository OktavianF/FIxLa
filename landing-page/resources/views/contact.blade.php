@extends('layouts.app')

@section('title', 'Kontak - FixLA')

@section('content')
<!-- Header -->
<section class="bg-gradient-to-r from-blue-600 to-blue-400 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fadeInUp">Hubungi Kami</h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto animate-fadeInUp delay-100">Ada pertanyaan atau saran? Tim kami siap membantu Anda</p>
    </div>
</section>

<!-- Contact Content -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-lg p-8 animate-fadeInUp">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>
                <form id="contactForm">
                    <div class="mb-5">
                        <label class="block text-gray-700 mb-2 font-medium">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" id="name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition" placeholder="Masukkan nama Anda" required>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-gray-700 mb-2 font-medium">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                            <input type="email" id="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition" placeholder="Masukkan email Anda" required>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-gray-700 mb-2 font-medium">No. Telepon</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-3 top-3 text-gray-400"></i>
                            <input type="tel" id="phone" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition" placeholder="Masukkan nomor telepon">
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-gray-700 mb-2 font-medium">Subjek</label>
                        <div class="relative">
                            <i class="fas fa-tag absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" id="subject" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition" placeholder="Subjek pesan" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2 font-medium">Pesan</label>
                        <div class="relative">
                            <i class="fas fa-comment absolute left-3 top-3 text-gray-400"></i>
                            <textarea id="message" rows="5" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition" placeholder="Tulis pesan Anda" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="animate-fadeInUp delay-200">
                <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Informasi Kontak</h2>
                    <div class="space-y-5">
                        <div class="flex items-start group">
                            <div class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] w-12 h-12 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Alamat</h3>
                                <p class="text-gray-600">Jl. Raya Lamongan No. 123, Lamongan, Jawa Timur</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] w-12 h-12 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-phone text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Telepon</h3>
                                <p class="text-gray-600">(0322) 123456</p>
                                <p class="text-gray-600">+62 812 3456 7890</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] w-12 h-12 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                                <p class="text-gray-600">info@fixla.id</p>
                                <p class="text-gray-600">support@fixla.id</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Ikuti Kami</h2>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white w-12 h-12 rounded-full flex items-center justify-center hover:shadow-lg transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white w-12 h-12 rounded-full flex items-center justify-center hover:shadow-lg transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white w-12 h-12 rounded-full flex items-center justify-center hover:shadow-lg transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white w-12 h-12 rounded-full flex items-center justify-center hover:shadow-lg transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                        <a href="#" class="bg-gradient-to-r from-[#3b82f6] to-[#60a5fa] text-white w-12 h-12 rounded-full flex items-center justify-center hover:shadow-lg transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
        this.reset();
    });
</script>
@endsection