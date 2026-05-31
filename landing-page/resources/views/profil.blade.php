@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<section class="min-h-screen flex items-center justify-center py-20 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50"></div>

    <div class="relative z-10 w-full max-w-lg">
        {{-- Profile Card --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
            {{-- Header Gradient --}}
            <div class="h-28 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 relative">
                <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 80 80%22><circle cx=%2240%22 cy=%2240%22 r=%222%22 fill=%22white%22/></svg>'); background-size: 20px 20px;"></div>
            </div>

            {{-- Avatar --}}
            <div class="flex justify-center -mt-14">
                <div class="relative group cursor-pointer" id="avatar-wrapper">
                    <div id="avatar-container" class="w-28 h-28 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center overflow-hidden">
                        <span id="profile-initial" class="text-white text-4xl font-black">U</span>
                        <img id="profile-avatar" class="absolute inset-0 w-full h-full object-cover hidden" alt="Avatar">
                    </div>
                    <label for="avatar-input" class="absolute bottom-0 right-0 w-9 h-9 rounded-full bg-blue-600 border-2 border-white flex items-center justify-center cursor-pointer hover:bg-blue-700 transition-all shadow-lg">
                        <i class="fas fa-camera text-white text-xs"></i>
                    </label>
                    <input type="file" id="avatar-input" accept="image/*" class="hidden">
                </div>
            </div>

            {{-- Email (readonly) --}}
            <div class="text-center mt-3 mb-6">
                <p id="profile-email" class="text-sm text-slate-500"></p>
            </div>

            {{-- Form --}}
            <div class="px-8 pb-8">
                <form id="profile-form" class="space-y-5">
                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5 tracking-wide uppercase">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="profile-name" placeholder="Masukkan nama lengkap" required
                                class="w-full pl-11 pr-4 py-3 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5 tracking-wide uppercase">No. Telepon</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="tel" id="profile-phone" placeholder="Contoh: 08123456789"
                                class="w-full pl-11 pr-4 py-3 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="profile-submit"
                        class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>

                {{-- Notification --}}
                <div id="profile-notification" class="hidden mt-4 p-3 rounded-xl text-sm font-semibold text-center"></div>
            </div>
        </div>

        {{-- Back Link --}}
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Redirect if not logged in
    if (!Auth.isLoggedIn()) {
        window.location.href = '/login';
        return;
    }

    const user = Auth.getUser();
    const nameInput = document.getElementById('profile-name');
    const phoneInput = document.getElementById('profile-phone');
    const emailEl = document.getElementById('profile-email');
    const initialEl = document.getElementById('profile-initial');
    const avatarImg = document.getElementById('profile-avatar');
    const avatarInput = document.getElementById('avatar-input');
    const form = document.getElementById('profile-form');
    const submitBtn = document.getElementById('profile-submit');
    const notification = document.getElementById('profile-notification');

    let selectedFile = null;

    // Populate form
    if (user) {
        nameInput.value = user.name || '';
        phoneInput.value = user.phone || '';
        emailEl.textContent = user.email || '';
        initialEl.textContent = (user.name?.[0] || 'U').toUpperCase();

        if (user.avatar) {
            const avatarUrl = `http://localhost:8000/storage/${user.avatar}`;
            avatarImg.src = avatarUrl;
            avatarImg.classList.remove('hidden');
            initialEl.classList.add('hidden');
        }
    }

    // Avatar preview
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        selectedFile = file;

        const reader = new FileReader();
        reader.onload = function(ev) {
            avatarImg.src = ev.target.result;
            avatarImg.classList.remove('hidden');
            initialEl.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });

    // Show notification
    function showNotif(msg, type) {
        notification.textContent = msg;
        notification.className = `mt-4 p-3 rounded-xl text-sm font-semibold text-center ${type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'}`;
        notification.classList.remove('hidden');
        setTimeout(() => notification.classList.add('hidden'), 4000);
    }

    // Submit
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const name = nameInput.value.trim();
        if (!name) { showNotif('Nama tidak boleh kosong!', 'error'); return; }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        try {
            const formData = new FormData();
            formData.append('name', name);
            formData.append('phone', phoneInput.value.trim());
            if (selectedFile) {
                formData.append('avatar', selectedFile);
            }

            const res = await Auth.authFetch('/profile', {
                method: 'POST',
                body: formData
            });

            const json = await res.json();

            if (res.ok) {
                const updatedUser = json.data || json;
                Auth.setAuth(Auth.getToken(), updatedUser);
                Auth.updateNavbar();
                showNotif('Profil berhasil diperbarui!', 'success');
                selectedFile = null;
            } else {
                const errMsg = json.message || Object.values(json.errors || {}).flat().join(', ') || 'Gagal memperbarui profil';
                showNotif(errMsg, 'error');
            }
        } catch (err) {
            console.error(err);
            showNotif('Terjadi kesalahan jaringan.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
        }
    });
});
</script>
@endpush
@endsection
