const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[character]));

const number = (value) => Number(value || 0).toLocaleString('id-ID');
const path = (value) => encodeURIComponent(String(value));
const token = () => document.querySelector('meta[name="csrf-token"]')?.content || '';
const methodInputs = (method) => `<input type="hidden" name="_token" value="${token()}"><input type="hidden" name="_method" value="${method}">`;

function renderJoke(joke) {
    return `<article class="warkop-card joke-card">
        <span class="joke-number">TEBAKAN #${Number(joke.id)}</span>
        <h2 class="joke-setup">${escapeHtml(joke.setup)}</h2>
        <details><summary>Buka punchline</summary><p class="punchline">${escapeHtml(joke.punchline)}</p></details>
        <footer class="joke-meta">
            <span>Oleh <a href="/user/${path(joke.user.username)}">${escapeHtml(joke.user.name)}</a></span>
            <a class="text-link" href="/jokes/${Number(joke.id)}">Bahas jokes</a>
        </footer>
    </article>`;
}

function renderLeader(leader) {
    const weeklyNote = leader.weekly_xp != null ? `<small style="opacity:.7"> · +${Number(leader.weekly_xp)} XP minggu ini</small>` : '';
    return `<li class="leaderboard-row">
        <span class="rank-number">${Number(leader.rank)}</span>
        <div>
            <a class="leader-name text-link" href="${escapeHtml(leader.url)}">${escapeHtml(leader.name)}</a>
            <small>${escapeHtml(leader.rank_name)}${weeklyNote}</small>
        </div>
        <span class="xp-number">${escapeHtml(leader.rank_name)}</span>
    </li>`;
}

function renderHistory(session) {
    const completed = session.status === 'completed';
    const action = completed ? `/quiz/${Number(session.id)}/result` : `/quiz/${Number(session.id)}`;
    return `<article class="warkop-card heading-with-action">
        <div><h2>${escapeHtml(session.category?.name || 'Meja campuran')}</h2><p>${escapeHtml(new Date(session.created_at).toLocaleString('id-ID'))} · ${completed ? 'Selesai' : 'Sedang berjalan'}</p></div>
        <div><strong>${Number(session.correct_answers)}/${Number(session.total_questions)} benar</strong><br><a class="text-link" href="${action}">${completed ? 'Lihat hasil' : 'Lanjutkan'}</a></div>
    </article>`;
}

function renderAdminUser(user, currentUserId) {
    const deleteForm = Number(user.id) === Number(currentUserId) ? '' : `<form method="POST" action="/admin/users/${Number(user.id)}" onsubmit="return confirm('Hapus user ini beserta datanya?')">
        ${methodInputs('DELETE')}<button class="warkop-btn-secondary" type="submit">Hapus user</button></form>`;
    return `<article class="warkop-card">
        <div class="heading-with-action"><div><h2>${escapeHtml(user.username)}</h2><p>${escapeHtml(user.email)} · ${number(user.jokes_count)} jokes · ${number(user.total_xp)} XP</p></div>
        <span class="warkop-badge">${user.is_admin ? 'Admin' : escapeHtml(user.rank?.name || 'User')}</span></div>
        <div class="form-actions" style="margin-top:18px;align-items:end">
            <form method="POST" action="/admin/users/${Number(user.id)}" class="form-actions" style="flex:3">${methodInputs('PUT')}
                <div><label class="field-label" for="user-name-${Number(user.id)}">Nama</label><input class="warkop-input" id="user-name-${Number(user.id)}" name="name" value="${escapeHtml(user.name)}" required></div>
                <label style="min-height:46px;display:flex;align-items:center;gap:8px"><input type="checkbox" name="is_admin" value="1" ${user.is_admin ? 'checked' : ''}> Admin</label>
                <button class="warkop-btn-primary" type="submit">Simpan</button>
            </form>${deleteForm}
        </div>
    </article>`;
}

function renderAdminJoke(joke, categories) {
    const options = [`<option value="">Tanpa kategori</option>`, ...categories.map((category) => `<option value="${Number(category.id)}" ${joke.category_id === category.id ? 'selected' : ''}>${escapeHtml(category.name)}</option>`)].join('');
    return `<details class="warkop-card">
        <summary><strong>${escapeHtml(joke.setup)}</strong></summary>
        <p class="field-help">${escapeHtml(joke.user.name)} (${escapeHtml(joke.user.username)}) · ${escapeHtml(joke.status)}</p>
        <form method="POST" action="/admin/jokes/${Number(joke.id)}" class="form-stack" style="margin-top:18px">${methodInputs('PUT')}
            <div><label class="field-label" for="title-${Number(joke.id)}">Judul</label><input class="warkop-input" id="title-${Number(joke.id)}" name="title" value="${escapeHtml(joke.title)}"></div>
            <div><label class="field-label" for="category-${Number(joke.id)}">Kategori</label><select class="warkop-input" id="category-${Number(joke.id)}" name="category_id">${options}</select></div>
            <div><label class="field-label" for="setup-${Number(joke.id)}">Pertanyaan</label><textarea class="warkop-input" id="setup-${Number(joke.id)}" name="setup" required>${escapeHtml(joke.setup)}</textarea></div>
            <div><label class="field-label" for="punchline-${Number(joke.id)}">Punchline</label><textarea class="warkop-input" id="punchline-${Number(joke.id)}" name="punchline" required>${escapeHtml(joke.punchline)}</textarea></div>
            <div><label class="field-label" for="status-${Number(joke.id)}">Status</label><select class="warkop-input" id="status-${Number(joke.id)}" name="status"><option value="published" ${joke.status === 'published' ? 'selected' : ''}>Published</option><option value="draft" ${joke.status === 'draft' ? 'selected' : ''}>Draft</option></select></div>
            <button class="warkop-btn-primary" type="submit">Simpan perubahan</button>
        </form>
        <form method="POST" action="/admin/jokes/${Number(joke.id)}" style="margin-top:12px" onsubmit="return confirm('Hapus jokes ini?')">${methodInputs('DELETE')}<button class="warkop-btn-secondary" type="submit">Hapus jokes</button></form>
    </details>`;
}

function renderCategory(category) {
    return `<form class="warkop-card form-actions" method="POST" action="/admin/categories/${Number(category.id)}" style="align-items:end">${methodInputs('PUT')}
        <div><label class="field-label" for="name-${Number(category.id)}">${number(category.jokes_count)} jokes · ${number(category.questions_count)} soal</label><input class="warkop-input" id="name-${Number(category.id)}" name="name" value="${escapeHtml(category.name)}" required></div>
        <div><label class="field-label" for="description-${Number(category.id)}">Deskripsi</label><input class="warkop-input" id="description-${Number(category.id)}" name="description" value="${escapeHtml(category.description)}"></div>
        <label style="min-height:46px;display:flex;align-items:center;gap:8px"><input type="checkbox" name="is_active" value="1" ${category.is_active ? 'checked' : ''}> Aktif</label>
        <button class="warkop-btn-primary" type="submit">Simpan</button>
    </form>`;
}

function emptyMessage(type) {
    return {
        jokes: 'Belum ada jokes pada pilihan ini. Coba kategori atau urutan lain.',
        leaderboard: 'Belum ada pemain pada periode ini. Mainkan trivia untuk mengumpulkan XP.',
        history: 'Kamu belum memulai trivia. Pilih meja untuk membuat catatan pertama.',
        'admin-users': 'Belum ada user untuk dikelola.',
        'admin-jokes': 'Belum ada jokes untuk dikelola.',
        'admin-categories': 'Belum ada kategori. Tambahkan kategori dari formulir di atas.',
    }[type] || 'Belum ada data.';
}

export function initAjaxLists() {
    document.querySelectorAll('[data-ajax-list]').forEach((root) => {
        const type = root.dataset.type;
        const filters = new URLSearchParams(root.dataset.filters || '');
        let currentPage = 1;
        let lastPage = 1;
        let controller;
        const status = document.createElement('div');
        status.className = 'empty-state';
        status.setAttribute('role', 'status');
        status.style.gridColumn = '1 / -1';
        const navigation = document.createElement('nav');
        navigation.className = 'ajax-pagination';
        navigation.setAttribute('aria-label', 'Pagination');
        const previous = document.createElement('button');
        previous.type = 'button';
        previous.className = 'warkop-btn-secondary';
        previous.textContent = 'Sebelumnya';
        const indicator = document.createElement('span');
        const next = document.createElement('button');
        next.type = 'button';
        next.className = 'warkop-btn-secondary';
        next.textContent = 'Berikutnya';
        navigation.append(previous, indicator, next);
        root.after(navigation);

        async function load(page = 1) {
            controller?.abort();
            controller = new AbortController();
            root.setAttribute('aria-busy', 'true');
            root.replaceChildren(status);
            status.textContent = 'Memuat data, mohon tunggu...';
            navigation.hidden = true;
            const parameters = new URLSearchParams(filters);
            parameters.set('page', page);
            try {
                const response = await fetch(`${root.dataset.endpoint}?${parameters}`, {
                    headers: { Accept: 'application/json' },
                    signal: controller.signal,
                });
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const payload = await response.json();
                if (type === 'leaderboard') {
                    const summary = document.querySelector('[data-current-rank]');
                    if (summary) {
                        summary.hidden = !payload.current_user;
                        if (payload.current_user) {
                            summary.querySelector('[data-rank-number]').textContent = payload.current_user.rank;
                            summary.querySelector('[data-rank-xp]').textContent = number(payload.current_user.xp);
                        }
                    }
                }
                currentPage = payload.current_page;
                lastPage = payload.last_page;
                if (!payload.data.length) {
                    root.replaceChildren(status);
                    status.textContent = emptyMessage(type);
                    return;
                }
                const categories = JSON.parse(root.dataset.categories || '[]');
                const renderers = {
                    jokes: (item) => renderJoke(item),
                    leaderboard: (item) => renderLeader(item),
                    history: (item) => renderHistory(item),
                    'admin-users': (item) => renderAdminUser(item, root.dataset.currentUser),
                    'admin-jokes': (item) => renderAdminJoke(item, categories),
                    'admin-categories': (item) => renderCategory(item),
                };
                root.innerHTML = payload.data.map(renderers[type]).join('');
                indicator.textContent = `Halaman ${currentPage} dari ${lastPage}`;
                previous.disabled = !payload.prev_page_url;
                next.disabled = !payload.next_page_url;
                navigation.hidden = lastPage <= 1;
            } catch (error) {
                if (error.name === 'AbortError') return;
                root.replaceChildren(status);
                status.textContent = 'Data gagal dimuat. Periksa koneksi lalu coba lagi. ';
                const retry = document.createElement('button');
                retry.type = 'button';
                retry.className = 'text-link';
                retry.textContent = 'Coba lagi';
                retry.addEventListener('click', () => load(page));
                status.append(retry);
            } finally {
                root.setAttribute('aria-busy', 'false');
            }
        }

        previous.addEventListener('click', () => { if (currentPage > 1) load(currentPage - 1); });
        next.addEventListener('click', () => { if (currentPage < lastPage) load(currentPage + 1); });
        document.querySelectorAll(`[data-filter-list="${root.id}"]`).forEach((button) => {
            button.addEventListener('click', () => {
                document.querySelectorAll(`[data-filter-list="${root.id}"][data-filter-key="${button.dataset.filterKey}"]`).forEach((peer) => {
                    peer.classList.toggle('active', peer === button);
                    peer.setAttribute('aria-pressed', peer === button ? 'true' : 'false');
                });
                filters.set(button.dataset.filterKey, button.dataset.filterValue);
                load(1);
            });
        });
        load(1);
    });
}
