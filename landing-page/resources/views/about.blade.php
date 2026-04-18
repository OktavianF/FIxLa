@extends('layouts.app')

@section('title', 'Tentang - FixLA')

@section('content')
<!-- Header -->
<section class="relative py-20 bg-gradient-to-br from-blue-950 via-blue-900/90 to-purple-900/70 text-white overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-10 left-10 w-64 h-64 border border-blue-500 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 border border-purple-500 rounded-full"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl md:text-6xl font-black mb-6 leading-[1.1] tracking-tight">
            Tentang <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-blue-200 to-purple-300">FixLA</span>
        </h1>
        <p class="text-lg text-blue-100/70 max-w-2xl mx-auto leading-relaxed">Mewujudkan jalan aman dan nyaman untuk masyarakat Lamongan melalui teknologi dan transparansi.</p>
    </div>
</section>

<!-- Apa Itu FixLA -->
<section class="py-24 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-5xl font-black text-slate-900 mb-8 leading-[1.1] tracking-tight">Apa itu <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-800">FixLA</span>?</h2>
        <p class="text-gray-700 text-lg leading-relaxed mb-8">
            FixLA adalah aplikasi mobile untuk melaporkan jalan rusak di Lamongan dengan <strong class="text-blue-600 font-bold">foto, GPS, dan deskripsi</strong>. Dengan fitur unggulan yaitu <strong class="text-blue-600 font-bold">peta interaktif, live street view, dan estimasi biaya perbaikan</strong>. Pantau status perbaikan secara transparan.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <span class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-blue-500/20">
                <i class="fas fa-check-circle mr-2"></i>Mudah
            </span>
            <span class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-blue-700/40">
                <i class="fas fa-check-circle mr-2"></i>Akurat
            </span>
            <span class="bg-gradient-to-r from-blue-800 to-blue-700 text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-blue-700/40">
                <i class="fas fa-check-circle mr-2"></i>Transparan
            </span>
        </div>
    </div>
</section>

<!-- Visi & Misi -->
<section class="relative py-24 bg-gradient-to-br from-blue-950 via-blue-900/90 to-purple-900/70 overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-10 left-10 w-64 h-64 border border-blue-500 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 border border-purple-500 rounded-full"></div>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="group relative p-8 bg-gradient-to-br from-blue-950 to-blue-800 rounded-[2.5rem] hover:shadow-xl transition-all duration-500 shadow-lg overflow-hidden border border-blue-700/30">
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                <div class="relative z-10 text-center">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/20 text-white text-2xl">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Visi</h3>
                    <p class="text-blue-100/70">Menjadi aplikasi pelaporan infrastruktur terdepan di Lamongan untuk mewujudkan jalan yang aman dan nyaman.</p>
                </div>
            </div>
            <div class="group relative p-8 bg-gradient-to-br from-blue-800 to-blue-900 rounded-[2.5rem] hover:shadow-xl transition-all duration-500 shadow-lg overflow-hidden border border-blue-600/30">
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                <div class="relative z-10">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/20 text-white text-2xl">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">Misi</h3>
                    <div class="space-y-3 text-left">
                        <p class="text-blue-100/70 flex items-start"><i class="fas fa-check-circle text-blue-300 mr-3 mt-1 flex-shrink-0"></i> <span>Mudahkan laporan jalan rusak</span></p>
                        <p class="text-blue-100/70 flex items-start"><i class="fas fa-check-circle text-blue-300 mr-3 mt-1 flex-shrink-0"></i> <span>Tingkatkan transparansi perbaikan</span></p>
                        <p class="text-blue-100/70 flex items-start"><i class="fas fa-check-circle text-blue-300 mr-3 mt-1 flex-shrink-0"></i> <span>Percepat respons pemerintah</span></p>
                        <p class="text-blue-100/70 flex items-start"><i class="fas fa-check-circle text-blue-300 mr-3 mt-1 flex-shrink-0"></i> <span>Sediakan data akurat</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tujuan -->
<section class="py-24 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-black text-slate-900 mb-8 leading-[1.1] tracking-tight">Tujuan <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-800">Kami</span></h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="group relative p-8 bg-blue-900 rounded-[2.5rem] hover:bg-blue-800 transition-all duration-500 shadow-xl overflow-hidden text-center">
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/20 text-white text-2xl">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="font-bold text-white mb-3 text-lg">Efektifkan Komunikasi</h3>
                    <p class="text-blue-100/60 text-sm">Masyarakat & pemerintah daerah</p>
                </div>
            </div>
            <div class="group relative p-8 bg-blue-800 rounded-[2.5rem] hover:bg-blue-700 transition-all duration-500 shadow-xl overflow-hidden text-center">
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/20 text-white text-2xl">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3 class="font-bold text-white mb-3 text-lg">Percepat Perbaikan</h3>
                    <p class="text-blue-100/60 text-sm">Lebih cepat & tepat sasaran</p>
                </div>
            </div>
            <div class="group relative p-8 bg-blue-700 rounded-[2.5rem] hover:bg-blue-600 transition-all duration-500 shadow-xl overflow-hidden text-center">
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/20 text-white text-2xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="font-bold text-white mb-3 text-lg">Tingkatkan Transparansi</h3>
                    <p class="text-blue-100/60 text-sm">Informasi status perbaikan</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection