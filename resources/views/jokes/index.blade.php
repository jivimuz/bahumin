@extends('layouts.app')

@section('title', 'Jokes')

@section('content')
<header class="page-heading heading-with-action">
    <div>
        <h1>Etalase jokes</h1>
        <p>Baca pertanyaannya dulu. Kalau sudah menyerah, baru buka punchline.</p>
    </div>
    @auth
        <a href="{{ route('jokes.create') }}" class="warkop-btn-primary">Tulis jokes</a>
    @else
        <a href="{{ route('login') }}" class="warkop-btn-primary">Masuk untuk menulis</a>
    @endauth
</header>

<div class="filter-bar">
    <nav class="filter-links" aria-label="Filter kategori jokes">
        <button type="button" class="cat-btn active" data-category="">Semua</button>
        @foreach ($categories as $category)
            <button type="button" class="cat-btn" data-category="{{ $category->slug }}">{{ $category->name }}</button>
        @endforeach
    </nav>
    <nav class="filter-links" aria-label="Urutkan jokes">
        <button type="button" class="sort-btn active" data-sort="latest">Terbaru</button>
        <button type="button" class="sort-btn" data-sort="popular">Terpopuler</button>
    </nav>
</div>

<div id="jokes-container" class="joke-grid" aria-live="polite">
    <article class="warkop-card joke-card">
        <span class="joke-number">MEMUAT...</span>
        <h2 class="joke-setup">Sedang menyeduh jokes dari warkop...</h2>
    </article>
</div>

<div class="form-actions" style="margin-top: 28px; justify-content: center" id="jokes-pagination">
    <button type="button" class="warkop-btn-secondary" id="prev-page" disabled>Sebelumnya</button>
    <span id="page-indicator" style="display:inline-flex; align-items:center; font-family:'Fira Code',monospace; padding:0 12px">Halaman 1</span>
    <button type="button" class="warkop-btn-secondary" id="next-page" disabled>Berikutnya</button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let currentCategory = '';
    let currentSort = 'latest';
    let currentPage = 1;
    let lastPage = 1;

    const container = document.getElementById('jokes-container');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    const indicator = document.getElementById('page-indicator');

    async function loadJokes(page = 1) {
        container.style.opacity = '0.5';
        const params = new URLSearchParams({ page, sort: currentSort });
        if (currentCategory) params.set('category', currentCategory);

        try {
            const res = await fetch(`{{ route('api.jokes') }}?${params.toString()}`);
            const json = await res.json();
            
            currentPage = json.current_page;
            lastPage = json.last_page;
            indicator.textContent = `Halaman ${currentPage} dari ${lastPage}`;
            prevBtn.disabled = !json.prev_page_url;
            nextBtn.disabled = !json.next_page_url;

            if (json.data.length === 0) {
                container.innerHTML = `<div class="empty-state" style="grid-column: 1/-1"><p>Belum ada jokes pada pilihan ini.</p></div>`;
                return;
            }

            container.innerHTML = json.data.map(joke => `
                <article class="warkop-card joke-card">
                    <span class="joke-number">TEBAKAN #${joke.id}</span>
                    <h2 class="joke-setup">${escapeHtml(joke.setup)}</h2>
                    <details>
                        <summary>Buka punchline</summary>
                        <p class="punchline">${escapeHtml(joke.punchline)}</p>
                    </details>
                    <footer class="joke-meta">
                        <span>Oleh <a href="/user/${joke.user.username}">${escapeHtml(joke.user.name)}</a></span>
                        <a class="text-link" href="/jokes/${joke.id}">Bahas jokes</a>
                    </footer>
                </article>
            `).join('');
        } catch (err) {
            container.innerHTML = `<div class="empty-state" style="grid-column: 1/-1"><p>Gagal memuat jokes dari server.</p></div>`;
        } finally {
            container.style.opacity = '1';
        }
    }

    function escapeHtml(str) {
        return (str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentCategory = btn.dataset.category;
            loadJokes(1);
        });
    });

    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentSort = btn.dataset.sort;
            loadJokes(1);
        });
    });

    prevBtn.addEventListener('click', () => { if (currentPage > 1) loadJokes(currentPage - 1); });
    nextBtn.addEventListener('click', () => { if (currentPage < lastPage) loadJokes(currentPage + 1); });

    loadJokes(1);
});
</script>
@endpush
@endsection
