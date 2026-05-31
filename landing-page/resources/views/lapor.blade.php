@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
<section class="min-h-screen py-24 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500 rounded-full mix-blend-multiply filter blur-[180px] opacity-5"></div>

    <div class="relative z-10 w-full max-w-[700px] mx-auto">
        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs font-bold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                <i class="fas fa-plus-circle"></i> BUAT LAPORAN BARU
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Laporkan Kerusakan Jalan</h1>
            <p class="text-slate-500 mt-2">Isi form berikut untuk mengirimkan laporan ke Dinas PU Lamongan</p>
        </div>

        {{-- Auth Check --}}
        <div id="auth-gate" class="hidden text-center py-16">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock text-3xl text-blue-600"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Login Diperlukan</h2>
            <p class="text-slate-500 mb-6">Anda harus masuk terlebih dahulu untuk membuat laporan</p>
            <a href="{{ url('/login') }}" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all">Masuk Sekarang</a>
        </div>

        {{-- Form --}}
        <form id="report-form" class="space-y-6 hidden">
            {{-- Error / Success --}}
            <div id="form-alert" class="hidden p-4 rounded-2xl text-sm font-medium"></div>

            {{-- 1. Foto --}}
            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/40 border border-slate-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-camera text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Foto Bukti <span class="text-red-500">*</span></h3>
                </div>

                <div id="photo-preview" class="flex flex-wrap gap-3 mb-4"></div>

                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-blue-300 rounded-2xl bg-blue-50/50 hover:bg-blue-50 cursor-pointer transition-all group">
                    <i class="fas fa-cloud-upload-alt text-2xl text-blue-400 group-hover:text-blue-600 transition-all mb-2"></i>
                    <span class="text-sm font-semibold text-blue-500">Klik atau drag foto ke sini (max 5)</span>
                    <input type="file" id="photo-input" accept="image/*" multiple class="hidden">
                </label>
            </div>

            {{-- 2. Lokasi --}}
            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/40 border border-slate-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Lokasi Jalan</h3>
                </div>

                <div id="gps-status" class="mb-4 p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-sm font-medium text-amber-700 flex items-center gap-2">
                    <i class="fas fa-circle-notch fa-spin"></i> Mendeteksi lokasi GPS...
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="report-address" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                            placeholder="Jl. Contoh No. 123">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                        <input type="text" id="report-district" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                            placeholder="Nama Kecamatan">
                    </div>
                </div>
                <input type="hidden" id="report-lat">
                <input type="hidden" id="report-lng">
            </div>

            {{-- 3. Dimensi --}}
            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/40 border border-slate-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-ruler-combined text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Dimensi Kerusakan</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Panjang (m) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" id="report-length" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                            placeholder="0.0">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Lebar (m) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" id="report-width" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                            placeholder="0.0">
                    </div>
                </div>
            </div>

            {{-- 4. Tingkat Kerusakan --}}
            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/40 border border-slate-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Tingkat Kerusakan</h3>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <label class="damage-option cursor-pointer">
                        <input type="radio" name="damage_level" value="ringan" class="hidden peer">
                        <div class="p-4 rounded-2xl border-2 border-slate-200 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300">
                            <i class="fas fa-info-circle text-2xl text-green-500 mb-2"></i>
                            <p class="text-sm font-bold text-slate-800">Ringan</p>
                        </div>
                    </label>
                    <label class="damage-option cursor-pointer">
                        <input type="radio" name="damage_level" value="sedang" class="hidden peer" checked>
                        <div class="p-4 rounded-2xl border-2 border-slate-200 text-center transition-all peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:border-yellow-300">
                            <i class="fas fa-exclamation-circle text-2xl text-yellow-500 mb-2"></i>
                            <p class="text-sm font-bold text-slate-800">Sedang</p>
                        </div>
                    </label>
                    <label class="damage-option cursor-pointer">
                        <input type="radio" name="damage_level" value="berat" class="hidden peer">
                        <div class="p-4 rounded-2xl border-2 border-slate-200 text-center transition-all peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-red-300">
                            <i class="fas fa-skull-crossbones text-2xl text-red-500 mb-2"></i>
                            <p class="text-sm font-bold text-slate-800">Berat</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 5. Deskripsi --}}
            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/40 border border-slate-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pen-fancy text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Deskripsi Detail</h3>
                </div>
                <textarea id="report-desc" rows="4"
                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none"
                    placeholder="Jelaskan kondisi kerusakan jalan secara detail..."></textarea>
            </div>

            {{-- Submit --}}
            <button type="submit" id="submit-btn"
                class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-1 transition-all duration-300 text-base tracking-wide">
                <span id="submit-text"><i class="fas fa-paper-plane mr-2"></i> Kirim Laporan</span>
                <span id="submit-loader" class="hidden"><i class="fas fa-circle-notch fa-spin mr-2"></i> Mengirim...</span>
            </button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const authGate = document.getElementById('auth-gate');
    const form = document.getElementById('report-form');

    if (!Auth.isLoggedIn()) {
        authGate.classList.remove('hidden');
        return;
    }
    form.classList.remove('hidden');

    // ─── Geolocation ─────────────────────
    const gpsStatus = document.getElementById('gps-status');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                document.getElementById('report-lat').value = pos.coords.latitude;
                document.getElementById('report-lng').value = pos.coords.longitude;
                gpsStatus.innerHTML = `<i class="fas fa-check-circle text-green-600"></i> GPS: ${pos.coords.latitude.toFixed(5)}, ${pos.coords.longitude.toFixed(5)}`;
                gpsStatus.className = 'mb-4 p-3.5 bg-green-50 border border-green-200 rounded-xl text-sm font-medium text-green-700 flex items-center gap-2';
            },
            () => {
                gpsStatus.innerHTML = '<i class="fas fa-times-circle text-red-600"></i> Gagal mendeteksi GPS. Silakan isi alamat manual.';
                gpsStatus.className = 'mb-4 p-3.5 bg-red-50 border border-red-200 rounded-xl text-sm font-medium text-red-700 flex items-center gap-2';
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    // ─── Photo Upload Preview ────────────
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');
    let selectedFiles = [];

    photoInput.addEventListener('change', (e) => {
        const newFiles = Array.from(e.target.files);
        selectedFiles = [...selectedFiles, ...newFiles].slice(0, 5);
        renderPreviews();
    });

    function renderPreviews() {
        photoPreview.innerHTML = '';
        selectedFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-xl border-2 border-slate-200">
                    <button type="button" data-index="${i}" class="remove-photo absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                photoPreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    photoPreview.addEventListener('click', (e) => {
        const btn = e.target.closest('.remove-photo');
        if (btn) {
            selectedFiles.splice(parseInt(btn.dataset.index), 1);
            renderPreviews();
        }
    });

    // ─── Submit ──────────────────────────
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitLoader = document.getElementById('submit-loader');
        const alertEl = document.getElementById('form-alert');

        if (selectedFiles.length === 0) {
            alertEl.textContent = 'Tambahkan minimal 1 foto';
            alertEl.className = 'p-4 rounded-2xl text-sm font-medium bg-red-50 border border-red-200 text-red-600';
            alertEl.classList.remove('hidden');
            return;
        }

        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoader.classList.remove('hidden');
        alertEl.classList.add('hidden');

        try {
            const formData = new FormData();
            formData.append('latitude', document.getElementById('report-lat').value || '-7.12');
            formData.append('longitude', document.getElementById('report-lng').value || '112.42');
            formData.append('address', document.getElementById('report-address').value);
            formData.append('district', document.getElementById('report-district').value);
            formData.append('damage_level', document.querySelector('input[name="damage_level"]:checked').value);
            formData.append('road_length', document.getElementById('report-length').value);
            formData.append('road_width', document.getElementById('report-width').value);
            formData.append('description', document.getElementById('report-desc').value);
            formData.append('is_ai_classified', '0');

            selectedFiles.forEach(f => formData.append('photos[]', f));

            const res = await Auth.authFetch('/reports', { method: 'POST', body: formData });
            const data = await res.json();

            if (!res.ok) throw new Error(data.message || 'Gagal mengirim laporan');

            alertEl.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Laporan berhasil dikirim! Mengalihkan...';
            alertEl.className = 'p-4 rounded-2xl text-sm font-medium bg-green-50 border border-green-200 text-green-700';
            alertEl.classList.remove('hidden');
            setTimeout(() => window.location.href = '/laporan-saya', 1500);
        } catch (err) {
            alertEl.textContent = err.message;
            alertEl.className = 'p-4 rounded-2xl text-sm font-medium bg-red-50 border border-red-200 text-red-600';
            alertEl.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoader.classList.add('hidden');
        }
    });
});
</script>
@endpush
