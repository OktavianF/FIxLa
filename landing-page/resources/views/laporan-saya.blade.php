@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')
<section class="min-h-screen py-24 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50"></div>

    <div class="relative z-10 w-full max-w-[900px] mx-auto">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Laporan Saya</h1>
                <p class="text-slate-500 mt-1">Pantau status laporan yang telah Anda kirim</p>
            </div>
            <a href="{{ url('/lapor') }}"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-300 text-sm">
                <i class="fas fa-plus mr-2"></i>Buat Laporan Baru
            </a>
        </div>

        {{-- Auth Gate --}}
        <div id="auth-gate-reports" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock text-3xl text-blue-600"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Login Diperlukan</h2>
            <p class="text-slate-500 mb-6">Anda harus masuk terlebih dahulu</p>
            <a href="{{ url('/login') }}" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all">Masuk Sekarang</a>
        </div>

        {{-- Loading --}}
        <div id="reports-loading" class="hidden text-center py-16">
            <i class="fas fa-circle-notch fa-spin text-4xl text-blue-500 mb-4"></i>
            <p class="text-slate-500 font-medium">Memuat laporan...</p>
        </div>

        {{-- Empty State --}}
        <div id="reports-empty" class="hidden text-center py-16">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-inbox text-4xl text-slate-300"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Laporan</h2>
            <p class="text-slate-500 mb-6">Anda belum membuat laporan apapun</p>
            <a href="{{ url('/lapor') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all">Buat Laporan Pertama</a>
        </div>

        {{-- Reports List --}}
        <div id="reports-list" class="hidden space-y-4"></div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    if (!Auth.isLoggedIn()) {
        document.getElementById('auth-gate-reports').classList.remove('hidden');
        return;
    }

    const loading = document.getElementById('reports-loading');
    const empty = document.getElementById('reports-empty');
    const list = document.getElementById('reports-list');

    loading.classList.remove('hidden');

    try {
        const res = await Auth.authFetch('/my-reports');
        const data = await res.json();
        const reports = data?.data?.data || [];

        loading.classList.add('hidden');

        if (reports.length === 0) {
            empty.classList.remove('hidden');
            return;
        }

        list.classList.remove('hidden');

        const statusMap = {
            'submitted': { label: 'Dikirim', color: 'blue', icon: 'paper-plane' },
            'verified': { label: 'Terverifikasi', color: 'indigo', icon: 'check-circle' },
            'scheduled': { label: 'Dijadwalkan', color: 'purple', icon: 'calendar-alt' },
            'under_repair': { label: 'Dalam Perbaikan', color: 'amber', icon: 'hard-hat' },
            'completed': { label: 'Selesai', color: 'green', icon: 'check-double' },
            'rejected': { label: 'Ditolak', color: 'red', icon: 'times-circle' },
        };

        const damageColorMap = {
            'ringan': 'green',
            'sedang': 'yellow',
            'berat': 'red',
        };

        const API_ORIGIN = API_BASE.replace('/api/v1', '');

        reports.forEach(r => {
            const st = statusMap[r.status] || statusMap['submitted'];
            const dc = damageColorMap[r.damage_level] || 'gray';
            const photoUrl = r.photos?.length > 0
                ? `${API_ORIGIN}/api/v1/images/${r.photos[0].photo_path}`
                : 'https://via.placeholder.com/200x150/e2e8f0/94a3b8?text=No+Photo';
            const date = new Date(r.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

            list.innerHTML += `
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/40 border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-48 h-40 md:h-auto overflow-hidden">
                            <img src="${photoUrl}" alt="Foto" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="flex-1 p-5 md:p-6">
                            <div class="flex items-center gap-2 flex-wrap mb-3">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-${st.color}-100 text-${st.color}-700 text-xs font-bold rounded-full">
                                    <i class="fas fa-${st.icon}"></i> ${st.label}
                                </span>
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-${dc}-100 text-${dc}-700 text-xs font-bold rounded-full capitalize">
                                    ${r.damage_level}
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1 line-clamp-1">${r.address || 'Alamat tidak tersedia'}</h3>
                            <p class="text-xs text-slate-500 mb-2"><i class="fas fa-map-pin mr-1"></i>${r.district || '-'} &bull; ${date}</p>
                            <p class="text-sm text-slate-600 line-clamp-2">${r.description || 'Tidak ada deskripsi'}</p>
                            ${r.priority_score ? `<div class="mt-3 text-xs font-bold text-slate-400">Priority Score: ${r.priority_score}</div>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

    } catch (err) {
        loading.classList.add('hidden');
        list.innerHTML = `<div class="text-center py-10 text-red-500 font-medium">Gagal memuat laporan: ${err.message}</div>`;
        list.classList.remove('hidden');
    }
});
</script>
@endpush
