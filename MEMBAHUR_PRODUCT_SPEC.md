# MEMBAHUR --- Product Specification

**Nama:** MEMBAHUR\
**Kepanjangan:** Menjadi Bapak Humoris\
**Tagline:** Tempat bercanda, adu receh, dan naik kasta humor.\
**Versi dokumen:** 1.0\
**Status:** Product direction / MVP planning

## 1. Visi Produk

MEMBAHUR adalah aplikasi komunitas hiburan berbasis permainan
tebak-tebakan dan kuis humor. Pengguna tidak harus menjadi bapak-bapak.
Siapa pun yang suka humor receh, permainan kata, jokes, dan tantangan
ringan dapat bergabung.

Produk harus terasa seperti nongkrong di warung kopi: santai, akrab,
sedikit retro, penuh obrolan, dan tidak mengintimidasi pengguna baru.
Fokus MVP adalah membuat pengguna mudah bermain, tertawa, mendapat
progres, lalu kembali untuk mencoba tantangan berikutnya. Berbagi jokes
tersedia sebagai fitur komunitas pendukung.

**Prinsip utama:** - Gameplay lebih penting daripada feed sosial. -
Humor harus inklusif; identitas "bapak-bapak" adalah tema komedi, bukan
syarat keanggotaan. - Semua pengguna boleh membuat dan membagikan
jokes. - Ranking mengapresiasi pemain dan kreator dengan metrik
terpisah. - Sistem harus adil, mudah dipahami, dan tahan terhadap spam
atau manipulasi poin.

## 2. Target Pengguna

-   Pengguna umum yang menyukai tebak-tebakan, permainan kata, humor
    receh, dan kuis ringan.
-   Teman atau komunitas yang ingin bermain bersama.
-   Kreator jokes amatir yang ingin membagikan humor.
-   Pengguna kasual yang ingin bermain singkat tanpa harus membangun
    profil publik.

Tidak perlu membatasi aplikasi hanya untuk laki-laki, orang tua, atau
pengguna yang sudah menjadi bapak. Untuk MVP, gunakan kebijakan usia
minimum yang sesuai dengan ketentuan platform dan hukum yang berlaku;
jangan memasarkan aplikasi sebagai produk khusus anak-anak.

## 3. Positioning dan Identitas

**Positioning:** social quiz playground bertema warung kopi retro.

**Nuansa merek:** - Hangat, santai, akrab, jenaka. - Retro warung kopi
Indonesia: papan menu kapur, gelas kopi, meja kayu, poster lawas, ubin
jadul, radio, dan tipografi display. - Modern dalam keterbacaan dan
interaksi; jangan membuat UI terlihat kuno atau sulit digunakan. -
Hindari visual yang terlalu kekanak-kanakan atau menjadikan "bapak"
sebagai satu-satunya karakter.

**Contoh microcopy:** - "Gas, tes seberapa receh otakmu." - "Satu
pertanyaan lagi, masa nyerah?" - "Waduh, jawabanmu bikin meja sebelah
nengok." - "Jokes baru nongkrong." - "Naik kasta humor!"

## 4. Ruang Lingkup MVP

### P0 --- Wajib untuk MVP

1.  Registrasi, login, logout, dan reset password.
2.  Profil pengguna: username, avatar, bio opsional, level, XP,
    statistik.
3.  Beranda yang mengarahkan pengguna ke permainan.
4.  Kategori kuis/tebak-tebakan.
5.  Mode permainan solo dengan pertanyaan pilihan ganda.
6.  Mode jawaban singkat untuk pertanyaan yang mendukungnya.
7.  Hasil permainan: skor, jawaban benar/salah, pembahasan/punchline,
    XP.
8.  Riwayat permainan.
9.  XP, level, gelar, dan badge dasar.
10. Leaderboard mingguan dan sepanjang masa.
11. Buat dan bagikan jokes/tebak-tebakan.
12. Feed jokes sederhana dengan detail, reaksi, komentar, dan simpan.
13. Pelaporan konten dan moderasi dasar.
14. Panel admin untuk mengelola kategori, pertanyaan, jawaban, pengguna,
    dan laporan.

### P1 --- Setelah MVP tervalidasi

-   Daily challenge dan streak.
-   Tantangan berbatas waktu.
-   Duel async (dua pengguna menjawab set pertanyaan yang sama dalam
    periode tertentu).
-   Grup/tongkrongan.
-   Follow kreator.
-   Push notification.
-   Badge musiman dan event komunitas.

### P2 --- Eksplorasi

-   Duel real-time melalui WebSocket.
-   Tebak punchline dengan voting komunitas.
-   Voice quiz.
-   Rekomendasi konten personal.
-   AI untuk membantu moderasi atau memberi ide jokes, dengan label yang
    transparan.

Jangan memasukkan fitur P1/P2 ke MVP kecuali ada keputusan produk baru.

## 5. Struktur Navigasi

Navigasi utama yang disarankan:

1.  **Warung** --- Beranda, tantangan pilihan, kategori, aktivitas
    terbaru.
2.  **Main** --- Daftar mode permainan dan kategori kuis.
3.  **Jokes** --- Feed jokes komunitas.
4.  **Peringkat** --- Leaderboard pemain dan kreator.
5.  **Profil** --- Statistik, koleksi badge, riwayat, pengaturan.

Tombol "Buat" dapat menjadi aksi utama yang mudah ditemukan dari tab
Jokes atau tombol floating, tanpa mengganggu jalur utama bermain.

## 6. Gameplay

### 6.1 Tebak-tebakan Pilihan Ganda

-   Pengguna memilih kategori atau menekan "Main Sekarang".
-   Sistem membuat sesi dan memilih sejumlah pertanyaan.
-   Satu pertanyaan ditampilkan per layar.
-   Pengguna memilih satu jawaban.
-   Server memvalidasi jawaban dan menyimpan hasil.
-   Setelah menjawab, tampilkan benar/salah, jawaban yang benar, dan
    punchline/penjelasan.
-   Tampilkan progres sesi dan ringkasan hasil.

Jumlah pertanyaan awal yang disarankan: 10 per sesi. Sediakan sesi cepat
5 pertanyaan sebagai alternatif.

### 6.2 Kuis Jawaban Singkat

-   Pengguna mengetik jawaban.
-   Jawaban diperiksa menggunakan normalisasi teks dan daftar variasi
    jawaban yang diterima.
-   Jangan mengandalkan pencocokan AI sebagai satu-satunya penentu
    benar/salah pada MVP.
-   Jika jawaban kreatif tidak cocok dengan kunci, sediakan opsi
    "Laporkan jawaban/kunci kurang tepat" atau mekanisme review admin.
-   Untuk jokes dengan banyak kemungkinan punchline, gunakan mode voting
    komunitas terpisah, bukan penilaian otomatis yang seolah mutlak.

### 6.3 Daily Challenge (P1)

-   Tantangan harian berisi 5 pertanyaan.
-   Bonus penyelesaian dan bonus perfect score.
-   Streak tidak boleh menghukum pengguna secara berlebihan; tampilkan
    sebagai motivasi, bukan kewajiban.

### 6.4 Berbagi Jokes

Pengguna dapat membuat: - Jokes teks pendek. - Tebak-tebakan dengan
pertanyaan dan jawaban. - Kuis pilihan ganda (bila diaktifkan oleh
admin/aturan konten).

Form konten: - Judul opsional. - Isi/pertanyaan. - Jawaban atau
punchline (bisa disembunyikan di feed). - Kategori. - Jenis konten. -
Visibilitas dan status moderasi.

Konten buatan pengguna tidak otomatis masuk bank pertanyaan resmi.
Pisahkan konten komunitas dari konten kurasi permainan agar kualitas dan
keadilan kuis terjaga.

## 7. Sistem XP, Level, Gelar, dan Badge

Pisahkan empat konsep:

-   **XP:** progres akun dari aktivitas yang valid.
-   **Level:** tingkatan progres berdasarkan total XP.
-   **Gelar/rank:** nama jenaka yang ditampilkan di profil.
-   **Badge:** pencapaian spesifik yang dapat dikoleksi.

Jangan menyebut semua jenjang dengan kata "Bapak". Gunakan nama pangkat
yang beragam, singkat, dan terasa seperti kasta humor.

### Usulan jenjang awal

    Level Gelar                 Deskripsi
  ------- --------------------- -------------------------------
        1 Anak Baru Nongkrong   Baru masuk warung humor
        2 Penikmat Receh        Mulai paham aliran jokes
        3 Tukang Ketawa         Hadir untuk menikmati lawakan
        4 Ahli Garing           Jokes kering bukan halangan
        5 Suhu Warkop           Mulai punya jam terbang
        6 Raja Celetukan        Jawaban spontan makin tajam
        7 Maestro Receh         Punya insting humor
        8 Jawara Tongkrongan    Mulai dikenal komunitas
        9 Legenda Warkop        Rekam jejak humor kuat
       10 Dewa Garing           Garing pun jadi seni
       11 Badak Humor           Mental baja, receh berkelas
       12 Kelas Badak           Kasta puncak MEMBAHUR

Nama dan urutan adalah usulan yang dapat diuji melalui playtest. Gelar
tidak harus mencerminkan gender atau status keluarga.

### Aturan XP awal (dapat dikonfigurasi admin)

  Aktivitas                                       XP awal
  -------------------------------------- ----------------
  Jawaban benar                                       +10
  Menyelesaikan sesi                                   +5
  Perfect score sesi                            +25 bonus
  Daily challenge selesai                       +20 bonus
  Jokes disetujui moderasi                             +5
  Jokes mendapat apresiasi berkualitas     Bonus terbatas

Ketentuan anti-abuse: - XP hanya diberikan server-side. - Satu
jawaban/sesi tidak boleh diklaim lebih dari sekali. - Batasi XP dari
aktivitas berulang dan reaksi. - Jangan memberi XP hanya karena spam
posting atau saling memberi reaksi. - Simpan setiap perubahan XP sebagai
ledger/transaksi yang dapat diaudit. - Gunakan idempotency key untuk
penyelesaian sesi dan klaim reward.

### Statistik pemain

-   Total sesi dimainkan.
-   Total pertanyaan dijawab.
-   Jawaban benar.
-   Akurasi.
-   Total XP.
-   Level dan gelar.
-   Jumlah jokes yang dibagikan.
-   Jumlah jokes yang disimpan/apresiasi.
-   Rekor skor dan streak (jika fitur streak aktif).

Leaderboard pemain dan leaderboard kreator harus dipisah agar kontribusi
menulis jokes tidak tercampur dengan kemampuan menjawab kuis.

## 8. Kategori Konten

Kategori awal: - Permainan kata. - Makanan & minuman. - Hewan. -
Sekolah. - Kantor & pekerjaan. - Keluarga & kehidupan sehari-hari. -
Logika receh. - Tebak-tebakan absurd. - Campur aduk.

Kategori dapat dikelola admin dan tidak perlu dikunci permanen di
aplikasi.

## 9. Reaksi dan Interaksi

Reaksi khas: - Ngakak 😂 - Garing 🌵 - Bapak Banget 🧔 - Jenius 🤯

Pengguna dapat: - Memberi satu reaksi per konten (dapat diubah). -
Berkomentar. - Menyimpan konten. - Membagikan tautan. - Melaporkan
konten.

Reaksi adalah ekspresi sosial, bukan bukti objektif bahwa konten
berkualitas. Jangan menjadikan reaksi tunggal sebagai penentu otomatis
gelar atau ranking.

## 10. Leaderboard

Jenis leaderboard: - Pemain mingguan berdasarkan XP kompetitif yang
diperoleh pada periode tersebut. - Pemain sepanjang masa berdasarkan
total XP valid. - Kreator berdasarkan metrik kontribusi yang terdefinisi
dan dimoderasi.

Aturan: - Periode menggunakan zona waktu yang jelas; default
Asia/Jakarta. - Tampilkan posisi pengguna sendiri meskipun tidak masuk
halaman teratas. - Jangan menghapus total XP ketika periode mingguan
berganti. - Hindari leaderboard yang mendorong spam atau farming. -
Simpan snapshot/periode ranking bila diperlukan untuk histori.

## 11. Struktur Data Awal

Gunakan tabel relasional dan foreign key. Nama tabel berikut adalah
rancangan awal, bukan migrasi final.

### Identitas dan progres

-   `users`: akun dan autentikasi.
-   `user_profiles`: username publik, avatar, bio.
-   `ranks`: definisi level dan gelar.
-   `user_xp_transactions`: ledger XP (delta, reason, reference,
    idempotency key).
-   `achievements`: definisi badge.
-   `user_achievements`: badge yang dimiliki pengguna.

### Kuis

-   `categories`: kategori.
-   `questions`: pertanyaan, tipe, status, tingkat kesulitan, sumber.
-   `question_options`: opsi pilihan ganda.
-   `question_answers`: kunci dan variasi jawaban yang diterima; akses
    dibatasi.
-   `quiz_sessions`: sesi pengguna, status, skor, waktu mulai/selesai.
-   `quiz_session_questions`: urutan pertanyaan dalam sesi.
-   `user_answers`: jawaban pengguna, hasil validasi, waktu menjawab.

### Konten komunitas

-   `jokes`: konten jokes/tebak-tebakan buatan pengguna.
-   `joke_reactions`: reaksi pengguna terhadap jokes.
-   `joke_comments`: komentar.
-   `joke_bookmarks`: konten tersimpan.
-   `content_reports`: laporan konten/pengguna.

### Ranking dan operasional

-   `leaderboard_periods`: definisi periode.
-   `leaderboard_entries`: snapshot ranking bila diperlukan.
-   `daily_challenges`: konfigurasi tantangan harian (P1).
-   `notifications`: notifikasi dalam aplikasi (bila dibutuhkan).
-   `admin_audit_logs`: jejak tindakan admin.

### Catatan desain database

-   Gunakan UUID/ULID atau bigint konsisten; pilih satu strategi.
-   Tambahkan `created_at` dan `updated_at` sesuai kebutuhan.
-   Tambahkan unique constraint untuk satu reaksi per user per jokes.
-   Tambahkan unique constraint untuk idempotency key transaksi XP.
-   Jangan pernah mengirim kolom kunci jawaban ke payload soal sebelum
    pengguna menjawab.
-   Simpan jawaban mentah dan hasil normalisasi secara terpisah bila
    diperlukan.
-   Gunakan soft delete untuk konten yang perlu dipulihkan/moderasi.
-   Indeks kolom foreign key, status, kategori, created_at, dan kolom
    pencarian leaderboard.

## 12. Arsitektur Laravel

Stack backend: - Laravel sebagai aplikasi utama dan REST API. -
PostgreSQL sebagai database utama (MySQL juga dapat dipilih bila menjadi
standar deployment tim). - Redis untuk cache, rate limiting, queue, dan
leaderboard sementara. - Laravel Queue untuk pekerjaan asynchronous. -
Laravel Scheduler untuk tugas terjadwal. - Laravel Reverb hanya ketika
kebutuhan real-time benar-benar muncul; bukan syarat MVP. - Object
storage kompatibel S3 untuk avatar/media jika diperlukan.

Prinsip implementasi: - Business logic berada di service/action layer,
bukan menumpuk di controller. - Form Request untuk validasi. - API
Resources untuk konsistensi response. - Policies untuk otorisasi. -
Database transaction untuk penyelesaian sesi dan pemberian XP. -
Events/listeners/jobs untuk proses asynchronous. - Rate limiting untuk
login, submit jawaban, komentar, reaksi, dan posting. - Feature tests
untuk alur kritis. - Gunakan API versioning, misalnya `/api/v1`.

Contoh modul: - Auth - Profile - Quiz - Question Bank - Game Session -
XP & Rank - Leaderboard - Jokes Community - Reactions & Comments -
Moderation - Admin

## 13. API Awal

Contoh endpoint REST: - `POST /api/v1/auth/register` -
`POST /api/v1/auth/login` - `GET /api/v1/me` - `GET /api/v1/home` -
`GET /api/v1/categories` - `POST /api/v1/quiz-sessions` -
`GET /api/v1/quiz-sessions/{id}` -
`POST /api/v1/quiz-sessions/{id}/answers` -
`POST /api/v1/quiz-sessions/{id}/complete` -
`GET /api/v1/quiz-sessions/{id}/result` - `GET /api/v1/leaderboards` -
`GET /api/v1/jokes` - `POST /api/v1/jokes` - `GET /api/v1/jokes/{id}` -
`POST /api/v1/jokes/{id}/reactions` -
`POST /api/v1/jokes/{id}/comments` -
`POST /api/v1/jokes/{id}/bookmark` - `POST /api/v1/reports`

Endpoint final harus disesuaikan dengan kontrak request/response,
pagination, dan kebijakan autentikasi.

## 14. UX dan Screen List

1.  Splash / brand intro.
2.  Onboarding singkat.
3.  Login / registrasi.
4.  Beranda Warung.
5.  Pilih kategori.
6.  Pilih mode permainan.
7.  Layar pertanyaan.
8.  Feedback jawaban.
9.  Hasil sesi.
10. Riwayat permainan.
11. Feed jokes.
12. Detail jokes dan komentar.
13. Buat jokes.
14. Leaderboard.
15. Profil pengguna.
16. Koleksi badge.
17. Pengaturan.
18. Admin dashboard (web).

### Arahan visual

-   Palet: hijau botol, krem kertas, cokelat kopi, merah bata/oranye
    sebagai aksen.
-   Tekstur: kertas menu, papan tulis, kayu, stempel, tiket warung;
    gunakan secukupnya.
-   Komponen: kartu pertanyaan seperti kartu menu, label kategori
    seperti stiker, tombol besar dan jelas.
-   Tipografi: display retro untuk heading; sans-serif modern untuk body
    dan kontrol.
-   Ilustrasi: gelas kopi, teko, radio, meja warung, lampu gantung,
    papan menu, karakter beragam.
-   Pastikan kontras, ukuran teks, target sentuh, dan aksesibilitas
    tetap baik.
-   Hindari menjadikan tekstur sebagai latar padat di belakang teks
    panjang.

## 15. Moderasi, Privasi, dan Keamanan

-   Sediakan report, block, dan mekanisme moderasi.
-   Larang pelecehan, doxxing, ujaran kebencian, ancaman, spam, dan
    konten ilegal.
-   Jangan menganggap "humor" sebagai alasan untuk membiarkan serangan
    terhadap kelompok atau individu.
-   Batasi data profil publik.
-   Jangan menampilkan email, nomor telepon, atau data sensitif di
    profil.
-   Terapkan rate limit dan validasi input.
-   Escape/sanitasi output untuk mencegah XSS.
-   Batasi ukuran dan tipe file upload.
-   Simpan audit log tindakan admin.
-   Sediakan proses penghapusan akun dan data sesuai kebijakan privasi.

## 16. Metrik Keberhasilan MVP

Pantau: - Activation: persentase pengguna baru yang menyelesaikan sesi
pertama. - D1/D7 retention. - Rata-rata sesi per pengguna aktif. -
Completion rate sesi. - Rasio pertanyaan yang dilaporkan bermasalah. -
Jumlah jokes yang dibuat dan lolos moderasi. - Persentase pengguna yang
bermain sekaligus membuat konten. - Rasio laporan terhadap konten. -
Distribusi XP untuk mendeteksi farming.

Jangan mengoptimalkan waktu layar semata. Tujuan produk adalah
pengalaman hiburan yang positif dan komunitas yang sehat.

## 17. Kriteria MVP Siap Diuji

-   Pengguna dapat daftar dan login.
-   Pengguna dapat memainkan kuis dari bank pertanyaan.
-   Jawaban divalidasi server-side.
-   Hasil dan riwayat sesi tersimpan benar.
-   XP diberikan satu kali dan dapat diaudit.
-   Level/gelar dihitung konsisten.
-   Leaderboard menampilkan data yang benar.
-   Pengguna dapat membuat, melihat, bereaksi, dan melaporkan jokes.
-   Admin dapat mengelola pertanyaan dan laporan.
-   Alur kritis memiliki automated tests.
-   UI nyaman dipakai di perangkat mobile.
