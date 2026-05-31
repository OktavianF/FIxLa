@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<section class="min-h-screen flex items-center justify-center py-20 px-4 relative overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50/50 to-indigo-50"></div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-500 rounded-full mix-blend-multiply filter blur-[150px] opacity-10 -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-500 rounded-full mix-blend-multiply filter blur-[120px] opacity-10 translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative z-10 w-full max-w-[450px] mx-auto">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo-dark.png') }}" alt="FixLA" class="h-12 mx-auto mb-4"
                    onerror="this.src='https://via.placeholder.com/120x120/3b82f6/ffffff?text=FixLA'">
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Selamat Datang</h1>
            <p class="text-slate-500 mt-2">Masuk untuk melaporkan kerusakan jalan</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
            {{-- Error message --}}
            <div id="login-error" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-600 font-medium"></div>

            <form id="login-form" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="login-email" required
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="contoh@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock"></i></span>
                        <input type="password" id="login-password" required
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" id="login-btn"
                    class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-300 text-sm tracking-wide uppercase">
                    <span id="login-btn-text">Masuk</span>
                    <span id="login-btn-loader" class="hidden">
                        <i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...
                    </span>
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ url('/register') }}" class="text-blue-600 font-bold hover:underline">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('login-btn');
    const btnText = document.getElementById('login-btn-text');
    const btnLoader = document.getElementById('login-btn-loader');
    const errorEl = document.getElementById('login-error');

    btn.disabled = true;
    btnText.classList.add('hidden');
    btnLoader.classList.remove('hidden');
    errorEl.classList.add('hidden');

    try {
        const res = await fetch(`${API_BASE}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                email: document.getElementById('login-email').value,
                password: document.getElementById('login-password').value,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || data.errors?.email?.[0] || 'Login gagal');
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
