@extends('layouts.app')

@section('title', 'Fitur - FixLA')

@section('content')
<!-- Header -->
<section class="relative py-20 bg-gradient-to-br from-blue-950 via-blue-900/90 to-purple-900/70 text-white overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-10 left-10 w-64 h-64 border border-blue-500 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 border border-purple-500 rounded-full"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl md:text-6xl font-black mb-6 leading-[1.1] tracking-tight">
            Semua <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-blue-200 to-purple-300">Fitur</span> FixLA
        </h1>
        <p class="text-lg text-blue-100/70 max-w-2xl mx-auto leading-relaxed">Teknologi mutakhir untuk transparansi infrastruktur Lamongan. Jelajahi semua kemampuan aplikasi kami.</p>
    </div>
</section>

<!-- Fitur List - 6 Card -->
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-slate-900 mb-4">Fitur <span class="text-blue-600">Lengkap Kami</span></h2>
        </div>
        @php
            $features = [
                ['icon' => 'camera', 'title' => 'AI Scanner', 'desc' => 'Identifikasi kerusakan otomatis lewat kamera dengan teknologi AI terdepan.'],
                ['icon' => 'satellite', 'title' => 'Precise GPS', 'desc' => 'Koordinat akurat menggunakan teknologi GPS.'],
                ['icon' => 'stream', 'title' => 'Timeline Live', 'desc' => 'Update pengerjaan perbaikan setiap hari dengan notifikasi real-time.'],
                ['icon' => 'layer-group', 'title' => 'Data Terpadu', 'desc' => 'Sinkronisasi langsung ke Dinas PU Lamongan untuk efisiensi maksimal.'],
                ['icon' => 'fingerprint', 'title' => 'Secure Account', 'desc' => 'Enkripsi data pribadi pelapor secara aman dengan standar internasional.'],
                ['icon' => 'tachometer-alt', 'title' => 'Estimasi Cepat', 'desc' => 'Hitung perkiraan waktu perbaikan seketika dengan algoritma canggih.'],
            ];
        @endphp
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($features as $f)
            <div class="group relative p-8 bg-blue-900 rounded-[2.5rem] hover:bg-blue-800 transition-all duration-500 shadow-xl overflow-hidden">
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-all duration-700"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/20 text-white text-xl">
                        <i class="fas fa-{{ $f['icon'] }}"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">{{ $f['title'] }}</h3>
                    <p class="text-blue-100/60 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection