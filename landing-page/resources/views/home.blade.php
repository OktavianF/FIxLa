@extends('layouts.app')

@section('title', 'FixLA - Laporan Jalan Rusak')

@section('content')
    <section class="relative min-h-screen lg:min-h-[95vh] text-white overflow-hidden flex items-center pt-20 lg:pt-0">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/la.png') }}" alt="Lamongan" class="w-full h-full object-cover scale-105 animate-slow-zoom">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-950 via-blue-900/90 to-purple-900/70"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <div
            class="absolute top-1/4 -left-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen filter blur-[120px] opacity-20 animate-blob">
        </div>
        <div
            class="absolute bottom-1/4 -right-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-screen filter blur-[120px] opacity-20 animate-blob animation-delay-2000">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-0">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <div class="text-center lg:text-left space-y-6 lg:space-y-8 animate-fadeInUp">
                    <div
                        class="inline-flex items-center space-x-2 bg-white/5 backdrop-blur-xl border border-white/10 px-4 py-2 rounded-full shadow-2xl">
                        <span class="flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        <span
                            class="text-[10px] lg:text-xs font-black tracking-[0.2em] uppercase text-blue-100/80">Digitalizing
                            Lamongan</span>
                    </div>

                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-black leading-[1.1] tracking-tight">
                        Masa Depan <br>
                        <span
                            class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-blue-200 to-purple-300">Infrastruktur</span>
                        <br>
                        Lamongan.
                    </h1>

                    <p class="text-sm md:text-lg text-blue-100/70 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Platform pelaporan jalan rusak tercanggih. Gabungkan kekuatan warga dan transparansi data untuk
                        Lamongan yang lebih mulus.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                        <a href="{{ asset('apk/fixla.apk') }}"
                            class="group relative px-10 py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold rounded-2xl shadow-[0_20px_40px_rgba(37,99,235,0.3)] hover:shadow-blue-500/50 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-cloud-download-alt mr-3"></i>
                            Dapatkan APK
                        </a>
                        <a href="#features"
                            class="px-10 py-4 bg-white/5 backdrop-blur-md border border-white/10 text-white font-bold rounded-2xl hover:bg-white/10 transition-all">
                            Eksplor Fitur
                        </a>
                    </div>
                </div>

                <div class="flex justify-center relative animate-float mt-8 lg:mt-0">
                    <div class="relative group">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-[4rem] blur-[30px] opacity-20">
                        </div>

                        <div
                            class="relative w-[280px] sm:w-[320px] lg:w-[340px] bg-[#050505] rounded-[3.5rem] p-3.5 sm:p-4 shadow-2xl border-[6px] border-white/5">
                            <div class="absolute top-4 left-1/2 -translate-x-1/2 w-24 h-6 bg-black rounded-2xl z-30"></div>

                            <div class="bg-[#f8fafc] rounded-[2.8rem] overflow-hidden aspect-[9/19.5] relative">
                                <div class="bg-gradient-to-br from-blue-800 to-indigo-900 p-6 pt-12 text-white">
                                    <div class="flex justify-between items-center mb-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                                                <i class="fas fa-user-alt text-xs"></i>
                                            </div>
                                            <div class="leading-tight">
                                                <p class="text-[8px] uppercase tracking-tighter opacity-60">Citizen ID</p>
                                                <p class="text-[11px] font-black">Warga Lamongan</p>
                                            </div>
                                        </div>
                                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                                            <i class="fas fa-bell text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="bg-white/10 rounded-xl p-3 border border-white/5 backdrop-blur-sm">
                                        <p class="text-[9px] font-bold mb-1 opacity-80">Total Laporan Anda</p>
                                        <p class="text-lg font-black tracking-tight">24 <span
                                                class="text-[10px] font-normal opacity-60 italic">Laporan
                                                Terverifikasi</span></p>
                                    </div>
                                </div>

                                <div class="p-4 space-y-4">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-blue-600 p-4 rounded-2xl shadow-lg shadow-blue-200">
                                            <i class="fas fa-plus-circle text-white text-lg mb-2"></i>
                                            <p class="text-[10px] font-bold text-white">Lapor Baru</p>
                                        </div>
                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                                            <i class="fas fa-map-marked-alt text-blue-600 text-lg mb-2"></i>
                                            <p class="text-[10px] font-bold text-slate-800">Peta Jalan</p>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
                                        <div class="flex justify-between items-center mb-3">
                                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-tighter">
                                                On-Going Fix</p>
                                            <span class="text-[8px] font-bold text-blue-600 animate-pulse">LIVE</span>
                                        </div>
                                        <div class="flex gap-3">
                                            <div
                                                class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-blue-600">
                                                <i class="fas fa-hard-hat text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-[9px] font-bold text-slate-800 mb-1">Deket - Ruas A2</p>
                                                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="w-[85%] h-full bg-blue-600"></div>
                                                </div>
                                                <p class="text-[8px] text-slate-400 mt-1">Estimasi: 2 Hari lagi</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100">
                                        <div class="h-20 bg-blue-50 rounded-xl relative overflow-hidden grayscale-[0.5]">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="relative">
                                                    <div class="w-6 h-6 bg-blue-600/20 rounded-full animate-ping"></div>
                                                    <div
                                                        class="absolute inset-1.5 w-3 h-3 bg-blue-600 rounded-full border-2 border-white">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="absolute bottom-4 left-4 right-4 bg-white/80 backdrop-blur-xl border border-white/20 rounded-2xl p-3 flex justify-around shadow-xl">
                                    <i class="fas fa-home text-blue-600 text-xs"></i>
                                    <i class="fas fa-search text-slate-300 text-xs"></i>
                                    <i class="fas fa-chart-pie text-slate-300 text-xs"></i>
                                    <i class="fas fa-user text-slate-300 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-4xl md:text-5xl font-black leading-[1.1] tracking-tight text-slate-900 mb-6">
                    Fitur <span
                        class="bg-clip-text text-transparent bg-gradient-to-r from-blue-900 to-purple-700">FixLA</span>
                </h2>
                <p class="text-slate-500 text-lg">Teknologi mutakhir untuk transparansi infrastruktur Lamongan.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $features = [
                        ['icon' => 'camera', 'title' => 'AI Scanner', 'desc' => 'Identifikasi kerusakan otomatis lewat kamera.'],
                        ['icon' => 'satellite', 'title' => 'Precise GPS', 'desc' => 'Koordinat akurat menggunakan teknologi GPS.'],
                        ['icon' => 'stream', 'title' => 'Timeline Live', 'desc' => 'Update pengerjaan perbaikan setiap hari.'],
                    ];
                @endphp

                @foreach($features as $f)
                    <div
                        class="group relative p-8 bg-blue-900 rounded-[2.5rem] hover:bg-blue-800 transition-all duration-500 shadow-xl overflow-hidden">
                        <div
                            class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-blue-500/10 rounded-full group-hover:scale-150 transition-all duration-700">
                        </div>

                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/20 text-white text-xl">
                                <i class="fas fa-{{ $f['icon'] }}"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-4">{{ $f['title'] }}</h3>
                            <p class="text-blue-100/60 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-12">
                <a href="{{ url('/fitur') }}"
                    class="px-10 py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 transition-all duration-300 hover:-translate-y-1">
                    <i class="fas fa-arrow-right mr-2"></i>Lihat Semua Fitur
                </a>
            </div>
        </div>
    </section>

    <section id="how-it-works"
        class="py-24 bg-gradient-to-br from-blue-950 via-blue-900/90 to-purple-900/70 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-10 left-10 w-64 h-64 border border-blue-500 rounded-full"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 border border-purple-500 rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-black text-white tracking-tighter">
                    ALUR <span class="text-blue-500">EKSEKUSI</span> LAPORAN
                </h2>
                <div class="w-24 h-2 bg-blue-600 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <div class="group relative">
                    <div
                        class="absolute -top-10 -left-4 text-9xl font-black text-white/[0.03] group-hover:text-blue-500/10 transition-all duration-700">
                        01</div>

                    <div
                        class="relative p-8 bg-white/[0.02] border border-white/10 rounded-[2.5rem] backdrop-blur-3xl hover:border-blue-500/50 transition-all duration-500 shadow-2xl overflow-hidden">
                        <div
                            class="absolute -bottom-10 -right-10 w-32 h-32 bg-blue-600/20 blur-[50px] opacity-0 group-hover:opacity-100 transition-all duration-700">
                        </div>

                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-400 rounded-2xl flex items-center justify-center mb-8 shadow-[0_10px_30px_rgba(37,99,235,0.3)] rotate-3 group-hover:rotate-0 transition-transform">
                            <i class="fas fa-camera-retro text-2xl text-white"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">Capture & Tag</h3>
                        <p class="text-blue-100/50 text-sm leading-relaxed">
                            Ambil foto kerusakan secara detail. Biarkan AI kami yang bekerja mengunci lokasi GPS Anda secara
                            otomatis.
                        </p>

                        <div class="mt-6 flex items-center text-blue-400 font-bold text-xs tracking-widest uppercase">
                            <span>Scan Area</span>
                            <div class="ml-2 w-8 h-px bg-blue-400"></div>
                        </div>
                    </div>
                    <div
                        class="hidden md:block absolute top-1/2 -right-6 w-12 h-px bg-gradient-to-r from-blue-500 to-transparent z-0">
                    </div>
                </div>

                <div class="group relative md:mt-12">
                    <div
                        class="absolute -top-10 -left-4 text-9xl font-black text-white/[0.03] group-hover:text-purple-500/10 transition-all duration-700">
                        02</div>

                    <div
                        class="relative p-8 bg-white/[0.02] border border-white/10 rounded-[2.5rem] backdrop-blur-3xl hover:border-purple-500/50 transition-all duration-500 shadow-2xl overflow-hidden">
                        <div
                            class="absolute -bottom-10 -right-10 w-32 h-32 bg-purple-600/20 blur-[50px] opacity-0 group-hover:opacity-100 transition-all duration-700">
                        </div>

                        <div
                            class="w-16 h-16 bg-gradient-to-br from-purple-600 to-purple-400 rounded-2xl flex items-center justify-center mb-8 shadow-[0_10px_30px_rgba(147,51,234,0.3)] -rotate-3 group-hover:rotate-0 transition-transform">
                            <i class="fas fa-paper-plane text-2xl text-white"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">Instant Transmit</h3>
                        <p class="text-blue-100/50 text-sm leading-relaxed">
                            Kirim laporan Anda. Data akan langsung terenkripsi dan mendarat di dashboard teknisi Dinas PU
                            Lamongan.
                        </p>

                        <div class="mt-6 flex items-center text-purple-400 font-bold text-xs tracking-widest uppercase">
                            <span>Sending Data</span>
                            <div class="ml-2 w-8 h-px bg-purple-400"></div>
                        </div>
                    </div>
                    <div
                        class="hidden md:block absolute top-1/2 -right-6 w-12 h-px bg-gradient-to-r from-purple-500 to-transparent z-0">
                    </div>
                </div>

                <div class="group relative">
                    <div
                        class="absolute -top-10 -left-4 text-9xl font-black text-white/[0.03] group-hover:text-green-500/10 transition-all duration-700">
                        03</div>

                    <div
                        class="relative p-8 bg-white/[0.02] border border-white/10 rounded-[2.5rem] backdrop-blur-3xl hover:border-green-500/50 transition-all duration-500 shadow-2xl overflow-hidden">
                        <div
                            class="absolute -bottom-10 -right-10 w-32 h-32 bg-green-600/20 blur-[50px] opacity-0 group-hover:opacity-100 transition-all duration-700">
                        </div>

                        <div
                            class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-400 rounded-2xl flex items-center justify-center mb-8 shadow-[0_10px_30px_rgba(34,197,94,0.3)] rotate-6 group-hover:rotate-0 transition-transform">
                            <i class="fas fa-check-double text-2xl text-white"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">Execution & Done</h3>
                        <p class="text-blue-100/50 text-sm leading-relaxed">
                            Pantau status pengerjaan secara live. Jalan diperbaiki, Lamongan kembali mulus dan aman
                            digunakan.
                        </p>

                        <div class="mt-6 flex items-center text-green-400 font-bold text-xs tracking-widest uppercase">
                            <span>Road Fixed</span>
                            <div class="ml-2 w-8 h-px bg-green-400"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes slow-zoom {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.1);
            }
        }

        .animate-slow-zoom {
            animation: slow-zoom 20s infinite alternate ease-in-out;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection