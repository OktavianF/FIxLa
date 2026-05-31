/**
 * FixLA Auth Helper
 * Mengelola autentikasi user di Landing Page via localStorage + Backend API
 */
const API_BASE = 'http://localhost:8000/api/v1';

const Auth = {
    getToken()   { return localStorage.getItem('fixla_token'); },
    getUser()    { try { return JSON.parse(localStorage.getItem('fixla_user')); } catch { return null; } },
    isLoggedIn() { return !!this.getToken(); },

    setAuth(token, user) {
        localStorage.setItem('fixla_token', token);
        localStorage.setItem('fixla_user', JSON.stringify(user));
    },

    clearAuth() {
        localStorage.removeItem('fixla_token');
        localStorage.removeItem('fixla_user');
    },

    /** Wrapper fetch() dengan Authorization header otomatis */
    async authFetch(endpoint, options = {}) {
        const url = endpoint.startsWith('http') ? endpoint : `${API_BASE}${endpoint}`;
        const headers = { 'Accept': 'application/json', ...(options.headers || {}) };
        const token = this.getToken();
        if (token) headers['Authorization'] = `Bearer ${token}`;

        // Jangan set Content-Type jika FormData (browser auto set boundary)
        if (!(options.body instanceof FormData) && !headers['Content-Type']) {
            headers['Content-Type'] = 'application/json';
        }

        const res = await fetch(url, { ...options, headers });
        if (res.status === 401) { this.clearAuth(); window.location.href = '/login'; }
        return res;
    },

    /** Update navbar UI berdasarkan status login */
    updateNavbar() {
        const loginBtn     = document.getElementById('nav-login-btn');
        const loginBtnMob  = document.getElementById('nav-login-btn-mobile');
        const userMenu     = document.getElementById('nav-user-menu');
        const userMenuMob  = document.getElementById('nav-user-menu-mobile');
        const userName     = document.getElementById('nav-user-name');
        const userInitial  = document.getElementById('nav-user-initial');

        if (this.isLoggedIn()) {
            const user = this.getUser();
            if (loginBtn)    loginBtn.style.display = 'none';
            if (loginBtnMob) loginBtnMob.style.display = 'none';
            if (userMenu)    userMenu.classList.remove('hidden');
            if (userMenuMob) userMenuMob.classList.remove('hidden');
            if (userName)    userName.textContent = user?.name || 'User';
            if (userInitial) userInitial.textContent = (user?.name?.[0] || 'U').toUpperCase();
        } else {
            if (loginBtn)    loginBtn.style.display = '';
            if (loginBtnMob) loginBtnMob.style.display = '';
            if (userMenu)    userMenu.classList.add('hidden');
            if (userMenuMob) userMenuMob.classList.add('hidden');
        }
    },

    logout() {
        this.authFetch('/logout', { method: 'POST' }).catch(() => {});
        this.clearAuth();
        window.location.href = '/';
    }
};

document.addEventListener('DOMContentLoaded', () => Auth.updateNavbar());
