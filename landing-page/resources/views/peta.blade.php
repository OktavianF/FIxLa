@extends('layouts.app')

@section('title', 'Peta Heatmap Kerusakan')

@section('content')
<section class="min-h-screen pt-20 pb-8 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50"></div>

    <div class="relative z-10 max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-100/80 text-blue-700 text-xs font-bold tracking-widest uppercase mb-3">
                <i class="fas fa-map-marked-alt"></i> Peta Kerusakan Jalan
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Peta <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Heatmap</span></h1>
            <p class="text-slate-500 text-sm mt-1">Visualisasi lokasi kerusakan jalan di Lamongan</p>
        </div>

        {{-- Map Container --}}
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            {{-- Map Stats Bar --}}
            <div class="flex flex-wrap items-center justify-between px-5 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-100 gap-3">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-xs font-semibold text-slate-600">Ringan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <span class="text-xs font-semibold text-slate-600">Sedang</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span class="text-xs font-semibold text-slate-600">Berat</span>
                    </div>
                </div>
                <div id="map-report-count" class="text-xs font-bold text-slate-500">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Memuat data...
                </div>
            </div>

            {{-- Map --}}
            <div id="heatmap" style="height: 65vh; min-height: 400px;"></div>
        </div>

        {{-- Info Card --}}
        <div class="mt-6 bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Tentang Peta Heatmap</h3>
                    <p class="text-slate-500 text-xs mt-1 leading-relaxed">Peta ini menampilkan titik-titik kerusakan jalan yang telah dilaporkan oleh warga Lamongan. Warna merah menunjukkan area dengan kerusakan berat, kuning untuk sedang, dan hijau untuk ringan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    // Initialize map centered on Lamongan
    const map = L.map('heatmap').setView([-7.115, 112.417], 13);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // Fetch reports from API
    try {
        const res = await fetch('http://localhost:8000/api/v1/reports/map');
        const json = await res.json();
        const reports = json.data || json || [];

        // Build heatmap data
        const heatData = reports.map(r => {
            const lat = parseFloat(r.latitude) || 0;
            const lng = parseFloat(r.longitude) || 0;
            const damage = (r.damage_level || '').toLowerCase().trim();
            let weight = 0.5;
            if (damage === 'sedang') weight = 0.8;
            if (damage === 'berat') weight = 1.0;
            return [lat, lng, weight];
        }).filter(d => d[0] !== 0 && d[1] !== 0);

        // Add heatmap layer
        if (heatData.length > 0) {
            L.heatLayer(heatData, {
                radius: 35,
                blur: 25,
                maxZoom: 17,
                max: 1.0,
                gradient: { 0.2: '#22c55e', 0.5: '#eab308', 0.8: '#ef4444', 1.0: '#991b1b' }
            }).addTo(map);
        }

        // Add markers
        reports.forEach(r => {
            const lat = parseFloat(r.latitude);
            const lng = parseFloat(r.longitude);
            if (!lat || !lng) return;

            const damage = (r.damage_level || '').toLowerCase().trim();
            let color = '#22c55e';
            if (damage === 'sedang') color = '#eab308';
            if (damage === 'berat') color = '#ef4444';

            const marker = L.circleMarker([lat, lng], {
                radius: 6, fillColor: color, color: '#fff', weight: 2, opacity: 1, fillOpacity: 0.9
            }).addTo(map);

            const photoUrl = r.photo ? `http://localhost:8000/storage/${r.photo}` : '';
            marker.bindPopup(`
                <div style="min-width:200px">
                    ${photoUrl ? `<img src="${photoUrl}" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-bottom:8px" onerror="this.style.display='none'">` : ''}
                    <div style="font-weight:700;font-size:14px;margin-bottom:4px">${r.description || 'Kerusakan Jalan'}</div>
                    <div style="font-size:12px;color:#64748b">${r.address || 'Lokasi tidak diketahui'}</div>
                    <div style="margin-top:6px;display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;color:#fff;background:${color}">
                        ${r.damage_level || 'N/A'}
                    </div>
                </div>
            `);
        });

        document.getElementById('map-report-count').innerHTML = `<i class="fas fa-map-pin mr-1 text-blue-600"></i> <strong>${reports.length}</strong> laporan ditemukan`;

    } catch (err) {
        console.error('Gagal memuat data peta:', err);
        document.getElementById('map-report-count').innerHTML = '<span class="text-red-500"><i class="fas fa-exclamation-triangle mr-1"></i> Gagal memuat data</span>';
    }
});
</script>
@endpush
@endsection
