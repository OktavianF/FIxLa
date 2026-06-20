@extends('layouts.app')

@section('title', 'Berita Terkini Lamongan - FixLA')

@section('content')
<div class="pt-24 pb-16 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Berita Terkini Lamongan</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Dapatkan update informasi terbaru seputar Lamongan dan infrastruktur dari berbagai sumber terpercaya.</p>
        </div>

        <div id="news-loading" class="flex justify-center items-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600"></div>
        </div>

        <div id="news-empty" class="hidden text-center py-20">
            <i class="fas fa-newspaper text-6xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-bold text-slate-700">Belum ada berita</h3>
            <p class="text-slate-500 mt-2">Coba lagi nanti untuk mendapatkan update terbaru.</p>
        </div>

        <div id="news-grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- News cards will be inserted here via JS -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const loading = document.getElementById('news-loading');
    const empty = document.getElementById('news-empty');
    const grid = document.getElementById('news-grid');

    try {
        const response = await fetch('http://fixla-alb-486742336.ap-southeast-1.elb.amazonaws.com/api/v1/news');
        const result = await response.json();
        
        loading.classList.add('hidden');

        if (result.success && result.data && result.data.length > 0) {
            grid.classList.remove('hidden');
            result.data.forEach(news => {
                const date = new Date(news.publishedAt).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric'
                });
                
                const card = document.createElement('a');
                card.href = news.url;
                card.target = '_blank';
                card.className = 'bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 group flex flex-col';
                
                card.innerHTML = `
                    <div class="relative h-48 overflow-hidden bg-slate-200">
                        ${news.image 
                            ? `<img src="${news.image}" alt="${news.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='https://via.placeholder.com/400x250/e2e8f0/94a3b8?text=FixLA+News'">` 
                            : `<div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fas fa-image text-4xl"></i></div>`
                        }
                        <div class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            ${news.source.name}
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <p class="text-sm text-slate-500 mb-2 flex items-center gap-2">
                            <i class="far fa-calendar-alt"></i> ${date}
                        </p>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">${news.title}</h3>
                        <p class="text-slate-600 text-sm line-clamp-3 mb-4 flex-grow">${news.description}</p>
                        <div class="flex items-center text-blue-600 font-semibold text-sm mt-auto">
                            Baca selengkapnya <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
        } else {
            empty.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Failed to load news:', error);
        loading.classList.add('hidden');
        empty.classList.remove('hidden');
    }
});
</script>
@endsection
