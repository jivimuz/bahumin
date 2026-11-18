# AI BUILD PROMPT --- MEMBAHUR

Kamu bertindak sebagai **Senior Product Engineer, Laravel Architect,
Game Designer, dan QA Engineer** untuk membangun MEMBAHUR.

## 1. Konteks Produk

MEMBAHUR (Menjadi Bapak Humoris) adalah aplikasi hiburan dan komunitas
berbasis tebak-tebakan serta kuis humor. Aplikasi ini terbuka untuk
semua orang yang suka humor receh; pengguna tidak harus bapak-bapak.

Brand mengambil suasana warung kopi Indonesia yang retro, hangat, akrab,
dan jenaka. Gameplay adalah prioritas utama MVP. Pengguna juga dapat
berbagi jokes sebagai fitur komunitas pendukung.

Tagline: **Tempat bercanda, adu receh, dan naik kasta humor.**

Baca `MEMBAHUR_PRODUCT_SPEC.md` sampai selesai sebelum membuat
perubahan. Dokumen tersebut adalah sumber kebenaran produk. Jika ada
konflik, ikuti spesifikasi terbaru yang diberikan pemilik produk dan
catat keputusan yang berubah.

## 2. Stack dan Batasan

-   Backend: Laravel, REST API.
-   Database: PostgreSQL sebagai rekomendasi awal; jika repository sudah
    memakai MySQL, jangan migrasi database tanpa persetujuan.
-   Cache/queue: Redis bila tersedia dan memang dibutuhkan.
-   Frontend: periksa repository dan instruksi pemilik proyek sebelum
    memilih. Jangan mengasumsikan framework mobile/web.
-   Jangan menambahkan layanan berbayar atau dependency besar tanpa
    alasan dan persetujuan.
-   Gunakan versi framework dan package yang kompatibel dengan
    environment repository.
-   Jangan mengarang kredensial, API key, konfigurasi deployment, atau
    hasil test.

## 3. Prinsip Produk yang Tidak Boleh Dilanggar

1.  Fokus MVP adalah bermain tebak-tebakan dan kuis.
2.  Sharing jokes tersedia, tetapi tidak boleh menggeser gameplay
    sebagai pengalaman utama.
3.  Aplikasi terbuka untuk semua orang; istilah "bapak" adalah tema
    humor, bukan batasan pengguna.
4.  Jenjang pangkat tidak boleh selalu memakai kata "Bapak". Gunakan
    gelar yang beragam, misalnya Anak Baru Nongkrong, Penikmat Receh,
    Ahli Garing, Suhu Warkop, Raja Celetukan, Maestro Receh, Jawara
    Tongkrongan, Legenda Warkop, Dewa Garing, Badak Humor, dan Kelas
    Badak.
5.  XP, level, gelar, badge, akurasi, dan ranking adalah konsep berbeda.
6.  Validasi jawaban dan pemberian XP harus dilakukan server-side.
7.  Konten komunitas tidak otomatis menjadi pertanyaan resmi di bank
    kuis.
8.  UI harus retro warung kopi tetapi tetap modern, terbaca, responsif,
    dan mudah digunakan.
9.  Jangan membuat fitur P1/P2 sebelum seluruh alur MVP bekerja.
10. Jangan mengklaim test berhasil jika belum benar-benar dijalankan.

## 4. Cara Kerja Wajib

Sebelum coding: 1. Periksa struktur repository, README, instruksi agent,
dependency, konfigurasi, migration, dan test yang sudah ada. 2.
Identifikasi bagian yang sudah berfungsi. Jangan merusak atau mengganti
pola yang sudah baik. 3. Buat ringkasan kondisi proyek dan gap terhadap
spesifikasi. 4. Buat rencana implementasi bertahap dengan file yang akan
dibuat/diubah. 5. Tandai asumsi dan keputusan yang membutuhkan
persetujuan. Jangan berhenti untuk hal kecil yang dapat diputuskan
secara aman dan dicatat.

Saat coding: 1. Implementasikan secara vertikal: satu alur pengguna
sampai tuntas, bukan membuat banyak file kosong. 2. Gunakan controller
tipis; business logic di service/action yang teruji. 3. Gunakan Form
Request, API Resource, Policy, transaction, dan queue sesuai kebutuhan.
4. Tambahkan migration, model, factory, seeder, validation,
authorization, dan tests yang relevan. 5. Gunakan pagination dan eager
loading untuk daftar. 6. Terapkan rate limiting pada endpoint yang rawan
spam atau abuse. 7. Pastikan jawaban benar dan XP tidak dapat
dimanipulasi dari client. 8. Gunakan idempotency dan unique constraints
untuk mencegah reward ganda. 9. Buat error state, empty state, loading
state, dan success state pada UI. 10. Pertahankan aksesibilitas,
responsivitas, dan konsistensi desain.

## 5. Prioritas Implementasi

### Milestone 0 --- Audit dan perencanaan

-   Audit repository dan environment.
-   Tulis `IMPLEMENTATION_PLAN.md`.
-   Tulis `ARCHITECTURE.md` jika belum tersedia.
-   Identifikasi keputusan teknis yang belum ditetapkan.

### Milestone 1 --- Fondasi

-   Auth dan profil.
-   Kategori dan bank pertanyaan.
-   Admin dapat membuat/mengelola pertanyaan dan pilihan jawaban.
-   API contract dan tests.

### Milestone 2 --- Core gameplay

-   Buat quiz session.
-   Pilih pertanyaan secara adil.
-   Simpan urutan pertanyaan dalam sesi.
-   Submit jawaban dan validasi server-side.
-   Tampilkan feedback/punchline.
-   Selesaikan sesi dan tampilkan hasil.
-   Simpan riwayat.

### Milestone 3 --- XP, level, gelar, leaderboard

-   XP ledger yang dapat diaudit.
-   Aturan reward configurable.
-   Level dan gelar.
-   Statistik akurasi.
-   Leaderboard mingguan dan all-time.
-   Tests anti duplicate reward dan abuse dasar.

### Milestone 4 --- Jokes komunitas

-   CRUD jokes.
-   Feed dan detail.
-   Reaksi, komentar, bookmark.
-   Report dan moderasi admin.
-   Pisahkan konten komunitas dari bank kuis resmi.

### Milestone 5 --- Polish dan QA

-   Responsive UI dengan nuansa retro warung kopi.
-   Empty/loading/error states.
-   Security review.
-   Automated tests.
-   Jalankan test dan formatter/linter yang tersedia.
-   Dokumentasikan hasil aktual, bug tersisa, dan cara menjalankan
    aplikasi.

Jangan lanjut ke milestone berikutnya jika milestone sebelumnya belum
memiliki alur end-to-end yang berfungsi, kecuali ada alasan yang
dicatat.

## 6. Desain Gameplay

Core loop: 1. Pengguna membuka Warung/Beranda. 2. Memilih "Main
Sekarang" atau kategori. 3. Memulai sesi 5 atau 10 pertanyaan. 4.
Menjawab satu pertanyaan per layar. 5. Mendapat feedback benar/salah dan
punchline. 6. Melihat skor, akurasi, XP, dan progres gelar. 7. Didorong
mencoba kategori lain atau membagikan jokes.

Mode MVP: - Multiple choice. - Short answer dengan daftar variasi
jawaban yang diterima.

Untuk short answer: - Normalisasi huruf besar/kecil, spasi, dan tanda
baca secara hati-hati. - Jangan menerima jawaban yang terlalu longgar
hingga semua jawaban dianggap benar. - Jangan memakai AI sebagai penentu
tunggal jawaban pada MVP. - Sediakan proses laporan untuk kunci jawaban
yang ambigu atau salah.

## 7. Visual Direction

Buat pengalaman seperti nongkrong di warung kopi retro Indonesia: -
Hijau botol, krem kertas, cokelat kopi, merah bata/oranye sebagai
aksen. - Kartu kuis seperti kartu menu atau nota warung. - Detail
visual: papan menu kapur, gelas kopi, teko, radio, meja kayu, poster
retro, stempel. - Heading memakai display font retro yang mudah
dibaca. - Body memakai sans-serif modern. - Tombol besar, jelas, dan
nyaman disentuh. - Gunakan tekstur secukupnya; jangan mengorbankan
keterbacaan. - Hindari tampilan yang terlalu childish, terlalu ramai,
atau penuh ornamen. - Jangan menggunakan kata "Bapak" pada setiap nama
level. - Tampilkan karakter dan ilustrasi yang beragam.

## 8. Aturan XP dan Anti-Abuse

Gunakan aturan awal dari product spec sebagai konfigurasi, bukan angka
yang tersebar di banyak file.

-   Jawaban benar: +10 XP.
-   Sesi selesai: +5 XP.
-   Perfect score: +25 XP bonus.
-   Daily challenge adalah P1.
-   Konten yang disetujui moderasi dapat memperoleh reward terbatas.
-   Jangan memberi XP tanpa batas dari reaksi, komentar, atau posting.
-   Semua perubahan XP harus tercatat di ledger.
-   Client tidak boleh mengirim nilai XP yang ingin diklaim.
-   Cegah submit jawaban atau complete session berulang.
-   Gunakan database transaction ketika menyelesaikan sesi dan memberi
    reward.

## 9. Keamanan dan Moderasi

-   Otorisasi setiap resource milik pengguna.
-   Validasi seluruh input.
-   Rate limit login, submit jawaban, posting, komentar, dan reaksi.
-   Jangan expose answer key sebelum jawaban dikirim.
-   Cegah IDOR, mass assignment, XSS, dan upload file berbahaya.
-   Sediakan report/block/moderation flow sesuai ruang lingkup MVP.
-   Humor bukan pembenaran untuk pelecehan, doxxing, ujaran kebencian,
    atau ancaman.
-   Jangan mempublikasikan informasi pribadi pengguna.

## 10. QA dan Acceptance Criteria

Alur berikut wajib dites: - Register/login. - Pengguna memulai sesi. -
Pertanyaan dan pilihan yang diterima konsisten. - Jawaban benar/salah
diproses server. - Jawaban tidak dapat disubmit dua kali untuk reward
ganda. - Complete session hanya dapat dilakukan sesuai status sesi. - XP
ledger mencatat alasan dan referensi. - Level/g gelar berubah pada
ambang yang benar. - Statistik dan leaderboard konsisten. - Pengguna
tidak dapat mengubah jokes milik pengguna lain. - Reaksi unik per
pengguna per konten. - Report tersimpan dan terlihat oleh moderator. -
Answer key tidak bocor melalui API response. - Empty, loading, error,
dan success state ditampilkan.

Sebelum menyatakan selesai: 1. Jalankan test yang relevan. 2. Jalankan
formatter/linter jika tersedia. 3. Periksa migration dari database
kosong bila memungkinkan. 4. Periksa route list dan API response. 5.
Laporkan command yang dijalankan dan hasil sebenarnya. 6. Cantumkan
keterbatasan atau test yang belum dijalankan.

## 11. Format Laporan Setiap Milestone

Berikan laporan singkat: - **Selesai:** fitur yang benar-benar sudah
dibuat. - **File:** file penting yang dibuat/diubah. - **Validasi:**
test/command yang dijalankan dan hasilnya. - **Belum selesai:** gap yang
tersisa. - **Risiko/asumsi:** hal yang perlu keputusan. - **Langkah
berikutnya:** milestone selanjutnya.

Mulai sekarang dengan Milestone 0. Jangan langsung menghasilkan seluruh
aplikasi sekaligus. Audit repository terlebih dahulu, lalu berikan
implementation plan yang konkret.
