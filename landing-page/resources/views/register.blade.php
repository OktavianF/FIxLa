@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<section class="min-h-screen flex items-center justify-center py-20 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/50 to-indigo-50"></div>
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-500 rounded-full mix-blend-multiply filter blur-[150px] opacity-10 -translate-y-1/2 -translate-x-1/2"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-multiply filter blur-[120px] opacity-10 translate-y-1/2 translate-x-1/2"></div>

    <div class="relative z-10 w-full max-w-[450px] mx-auto">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo-dark.png') }}" alt="FixLA" class="h-12 mx-auto mb-4"
                    onerror="this.src='https://via.placeholder.com/120x120/3b82f6/ffffff?text=FixLA'">
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Akun Baru</h1>
            <p class="text-slate-500 mt-2">Bergabung menjadi warga pelapor aktif</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
            <div id="register-error" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-600 font-medium"></div>

            <form id="register-form" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-user"></i></span>
                        <input type="text" id="reg-name" required
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Masukkan nama lengkap">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="reg-email" required
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="contoh@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock"></i></span>
                        <input type="password" id="reg-password" required minlength="8"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Minimal 8 karakter">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock"></i></span>
                        <input type="password" id="reg-password-confirm" required minlength="8"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit" id="register-btn"
                    class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-300 text-sm tracking-wide uppercase">
                    <span id="reg-btn-text">Daftar Sekarang</span>
                    <span id="reg-btn-loader" class="hidden">
                        <i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...
                    </span>
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ url('/login') }}" class="text-blue-600 font-bold hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('register-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('register-btn');
    const btnText = document.getElementById('reg-btn-text');
    const btnLoader = document.getElementById('reg-btn-loader');
    const errorEl = document.getElementById('register-error');

    const password = document.getElementById('reg-password').value;
    const confirm = document.getElementById('reg-password-confirm').value;

    if (password !== confirm) {
        errorEl.textContent = 'Password dan konfirmasi tidak sama';
        errorEl.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btnText.classList.add('hidden');
    btnLoader.classList.remove('hidden');
    errorEl.classList.add('hidden');

    try {
        const res = await fetch(`${API_BASE}/register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                name: document.getElementById('reg-name').value,
                email: document.getElementById('reg-email').value,
                password: password,
                password_confirmation: confirm,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            const errors = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
            throw new Error(errors || 'Registrasi gagal');
        }

        Auth.setAuth(data.data.token, data.data.user);
        window.location.href = '/';
    } catch (err) {
        errorEl.textContent = err.message;
        errorEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
    }
});
</script>
@endpush
