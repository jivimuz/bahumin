# Bahumin

Bahumin (Bapak Humor Indonesia) adalah aplikasi Laravel untuk membaca jokes, membuat tebakan, berkomentar, bermain trivia, dan mengumpulkan XP.

## Menjalankan aplikasi

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Halaman utama

- `/`: landing page dengan tiga jokes terbaru dan ringkasan peringkat.
- `/jokes`: etalase jokes dengan filter kategori dan urutan.
- `/quiz`: pilihan trivia untuk user yang sudah masuk.
- `/leaderboard`: peringkat sepanjang waktu dan tujuh hari terakhir.
- `/sitemap.xml`: sitemap untuk halaman publik dan jokes yang tayang.

## Konsol admin

Konsol hanya tersedia melalui URL langsung `/admin` untuk user dengan `is_admin=true`. Login admin menggunakan portal terpisah di `/admin/login`. Tautan admin tidak ditampilkan di navigasi publik. Admin dapat mengelola user, jokes, kategori, serta membuat user dan jokes dummy.

Akun admin dari seeder lokal:

- Email: `admin@membahur.test`
- Password: `password`

Ganti kredensial tersebut sebelum memakai database seed pada lingkungan bersama.

## Pemeriksaan

```bash
php artisan test
npm run build
vendor/bin/pint --test
```
