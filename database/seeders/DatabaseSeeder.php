<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Category;
use App\Models\Joke;
use App\Models\JokeReaction;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Rank;
use App\Models\User;
use App\Models\UserXpTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        // 1. Ranks
        $ranksData = [
            ['level' => 1, 'name' => 'Anak Ecek-Ecek', 'min_xp' => 0, 'badge_icon' => '🥚', 'color' => '#78716C', 'description' => 'Level 1: Baru nongkrong, datang cuma pinjam Wi-Fi gratisan.'],
            ['level' => 2, 'name' => 'Penikmat Receh', 'min_xp' => 100, 'badge_icon' => '🥜', 'color' => '#A16207', 'description' => 'Level 2: Suka minta korek dan ngikut ketawa padahal gak paham.'],
            ['level' => 3, 'name' => 'Tukang Garing', 'min_xp' => 300, 'badge_icon' => '🌵', 'color' => '#CA8A04', 'description' => 'Level 3: Hadir untuk meramaikan suasana walau jokes-nya garing.'],
            ['level' => 4, 'name' => 'Spesialis Celetukan', 'min_xp' => 650, 'badge_icon' => '⚡', 'color' => '#16A34A', 'description' => 'Level 4: Celetukan spontan makin tajam menusuk tulang rusuk.'],
            ['level' => 5, 'name' => 'Suhu Warkop', 'min_xp' => 1100, 'badge_icon' => '🫖', 'color' => '#0D9488', 'description' => 'Level 5: Mulai punya jam terbang tinggi dan ditraktir gorengan.'],
            ['level' => 6, 'name' => 'Raja Gombal', 'min_xp' => 1750, 'badge_icon' => '🔥', 'color' => '#2563EB', 'description' => 'Level 6: Gombalan maut bikin seisi warkop salah tingkah.'],
            ['level' => 7, 'name' => 'Maestro Humor', 'min_xp' => 2600, 'badge_icon' => '🎩', 'color' => '#9333EA', 'description' => 'Level 7: Punya insting humor tingkat tinggi dan timing pas.'],
            ['level' => 8, 'name' => 'Legenda Tongkrongan', 'min_xp' => 3800, 'badge_icon' => '👑', 'color' => '#C2410C', 'description' => 'Level 8: Mulai disegani dari meja depan sampai dapur warkop.'],
            ['level' => 9, 'name' => 'Bapak Segala Receh', 'min_xp' => 5500, 'badge_icon' => '☕', 'color' => '#DC2626', 'description' => 'Level 9: Rekam jejak humor kuat, cerita lucunya melegenda.'],
            ['level' => 10, 'name' => 'Bapak Kelas Badak', 'min_xp' => 8000, 'badge_icon' => '🏆', 'color' => '#1C1917', 'description' => 'Level 10: Kasta puncak Bahumin, sekali ngomong seisi warkop roboh!'],
        ];

        foreach ($ranksData as $r) {
            Rank::updateOrCreate(['level' => $r['level']], $r);
        }

        // 2. Achievements
        $achievementsData = [
            [
                'code' => 'first_game',
                'name' => 'Nongkrong Perdana',
                'description' => 'Selesaikan 1 sesi kuis pertamamu di Warung.',
                'icon' => '☕',
                'requirement_type' => 'quiz_completed',
                'requirement_value' => 1,
                'xp_bonus' => 25,
            ],
            [
                'code' => 'quiz_5',
                'name' => 'Pelanggan Setia',
                'description' => 'Selesaikan 5 sesi kuis di MEMBAHUR.',
                'icon' => '🎖️',
                'requirement_type' => 'quiz_completed',
                'requirement_value' => 5,
                'xp_bonus' => 50,
            ],
            [
                'code' => 'perfect_score',
                'name' => 'Otak Encer',
                'description' => 'Dapatkan skor sempurna 100% dalam 1 sesi.',
                'icon' => '💯',
                'requirement_type' => 'perfect_score',
                'requirement_value' => 1,
                'xp_bonus' => 50,
            ],
            [
                'code' => 'xp_500',
                'name' => 'Kolektor Receh',
                'description' => 'Kumpulkan total 500 XP di akunmu.',
                'icon' => '💰',
                'requirement_type' => 'xp_milestone',
                'requirement_value' => 500,
                'xp_bonus' => 75,
            ],
            [
                'code' => 'first_joke',
                'name' => 'Kreator Tongkrongan',
                'description' => 'Bagikan jokes pertamamu ke komunitas.',
                'icon' => '✍️',
                'requirement_type' => 'jokes_posted',
                'requirement_value' => 1,
                'xp_bonus' => 30,
            ],
        ];

        foreach ($achievementsData as $ach) {
            Achievement::updateOrCreate(['code' => $ach['code']], $ach);
        }

        // 3. Categories
        $categoriesData = [
            [
                'name' => 'Permainan Kata',
                'slug' => 'permainan-kata',
                'description' => 'Pelesetan kata, singkatan maksa, dan homofon bikin geleng kepala.',
                'icon' => '🔤',
                'color' => '#C2410C',
            ],
            [
                'name' => 'Makanan & Minuman',
                'slug' => 'makanan-minuman',
                'description' => 'Kuis seputar menu warteg, gorengan, es teh, dan kuliner receh.',
                'icon' => '🍜',
                'color' => '#D97706',
            ],
            [
                'name' => 'Dunia Hewan',
                'slug' => 'dunia-hewan',
                'description' => 'Fauna absurd yang kelakuannya mirip tetangga sebelah.',
                'icon' => '🐒',
                'color' => '#16A34A',
            ],
            [
                'name' => 'Kantor & Kerjaan',
                'slug' => 'kantor-kerjaan',
                'description' => 'Hiburan pelepas penat buat budak korporat dan pekerja keras.',
                'icon' => '💼',
                'color' => '#78716C',
            ],
            [
                'name' => 'Logika Receh',
                'slug' => 'logika-receh',
                'description' => 'Teka-teki yang butuh mikir miring, jangan pakai rumus fisika!',
                'icon' => '🧠',
                'color' => '#C2410C',
            ],
            [
                'name' => 'Tebakan Absurd',
                'slug' => 'tebakan-absurd',
                'description' => 'Tebak-tebakan tanpa logika yang bikin pengen lempar bantal.',
                'icon' => '🤪',
                'color' => '#EA580C',
            ],
        ];

        $cats = [];
        foreach ($categoriesData as $c) {
            $cats[$c['slug']] = Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // 4. Questions & Options
        $questionsData = [
            // Permainan Kata
            [
                'category_slug' => 'permainan-kata',
                'question_text' => 'Pintu apa yang didorong sama 10 orang kekar sekalipun nggak bakal kebuka?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Makanya dibaca dulu tulisannya, bukan malah pamer otot!',
                'options' => [
                    ['text' => 'Pintu lift rusak', 'is_correct' => false],
                    ['text' => 'Pintu tol otomatis', 'is_correct' => false],
                    ['text' => 'Pintu yang ada tulisan "TARIK"', 'is_correct' => true],
                    ['text' => 'Pintu gerbang istana', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'permainan-kata',
                'question_text' => 'Ban apa yang paling berat dan bikin sesak napas di dunia?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Soalnya menanggung beban harapan jutaan rakyat jelata!',
                'options' => [
                    ['text' => 'Ban tronton 18 roda', 'is_correct' => false],
                    ['text' => 'Ban-tuan pemerintah', 'is_correct' => true],
                    ['text' => 'Ban pesawat jet', 'is_correct' => false],
                    ['text' => 'Ban-teng liar', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'permainan-kata',
                'question_text' => 'Hewan apa yang paling banyak saudara dan selalu santun?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Katak-beradik! Akur terus gak pernah rebutan warisan.',
                'options' => [
                    ['text' => 'Semut rangrang', 'is_correct' => false],
                    ['text' => 'Katak', 'is_correct' => true],
                    ['text' => 'Ayam sekampung', 'is_correct' => false],
                    ['text' => 'Kucing anggora', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'permainan-kata',
                'question_text' => 'Tentara apa yang paling kecil sedunia?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Ten-tara (tentara) nyamuk! Gak kelihatan tapi bikin gatal sekeluarga.',
                'options' => [
                    ['text' => 'Tentara liliput', 'is_correct' => false],
                    ['text' => 'Tentara semut', 'is_correct' => false],
                    ['text' => 'Tentara nyamuk', 'is_correct' => true],
                    ['text' => 'Tentara mainan', 'is_correct' => false],
                ],
            ],

            // Makanan & Minuman
            [
                'category_slug' => 'makanan-minuman',
                'question_text' => 'Jus apa yang paling bikin orang sedih dan galau semalaman?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Udah nemenin tiap hari, eh taunya cuma dianggap just a friend...',
                'options' => [
                    ['text' => 'Jus pare pahit', 'is_correct' => false],
                    ['text' => 'Just a friend', 'is_correct' => true],
                    ['text' => 'Jus jambu kelat', 'is_correct' => false],
                    ['text' => 'Jus basi', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'makanan-minuman',
                'question_text' => 'Nasi goreng apa yang paling ditakuti sama penjahat jalanan?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Intel yang lagi nyamar sambil bawa walkie-talkie di balik gerobak!',
                'options' => [
                    ['text' => 'Nasi goreng gila', 'is_correct' => false],
                    ['text' => 'Nasi goreng setan level 10', 'is_correct' => false],
                    ['text' => 'Nasi goreng buatan polisi', 'is_correct' => true],
                    ['text' => 'Nasi goreng gosong', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'makanan-minuman',
                'question_text' => 'Kue apa yang paling pendiam dan gak mau diajak ngobrol?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Kue pie... diem bae kayak patung!',
                'options' => [
                    ['text' => 'Kue cucur', 'is_correct' => false],
                    ['text' => 'Kue pie', 'is_correct' => true],
                    ['text' => 'Kue pukis', 'is_correct' => false],
                    ['text' => 'Kue lemper', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'makanan-minuman',
                'question_text' => 'Buah apa yang paling berani dan punya nyali preman?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Duku... duku anak STM!',
                'options' => [
                    ['text' => 'Buah naga', 'is_correct' => false],
                    ['text' => 'Duku', 'is_correct' => true],
                    ['text' => 'Durian montong', 'is_correct' => false],
                    ['text' => 'Kelapa tua', 'is_correct' => false],
                ],
            ],

            // Dunia Hewan
            [
                'category_slug' => 'dunia-hewan',
                'question_text' => 'Ikan apa yang matanya banyak banget sampai ratusan?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Coba hitung sendiri mata ikan teri sekilo, pusing gak tuh!',
                'options' => [
                    ['text' => 'Ikan teri sekilo', 'is_correct' => true],
                    ['text' => 'Ikan paus raksasa', 'is_correct' => false],
                    ['text' => 'Ikan mas koki', 'is_correct' => false],
                    ['text' => 'Ikan monster laut', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'dunia-hewan',
                'question_text' => 'Kuda apa yang paling bikin capek dan encok?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Kuda-kudaan pas push-up atau bela diri setengah jam!',
                'options' => [
                    ['text' => 'Kuda lumping', 'is_correct' => false],
                    ['text' => 'Kuda pacu', 'is_correct' => false],
                    ['text' => 'Kuda-kuda pas push up', 'is_correct' => true],
                    ['text' => 'Kuda nil', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'dunia-hewan',
                'question_text' => 'Bebek apa yang kalau jalan selalu muter ke kiri terus?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Bebek dikunci stang biar gak digondol maling warkop!',
                'options' => [
                    ['text' => 'Bebek pincang', 'is_correct' => false],
                    ['text' => 'Bebek balap', 'is_correct' => false],
                    ['text' => 'Bebek dikunci stang', 'is_correct' => true],
                    ['text' => 'Bebek peking', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'dunia-hewan',
                'question_text' => 'Gajah apa yang belalainya pendek banget?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Gajah pilek, belalainya lagi disusut karena boros tisu!',
                'options' => [
                    ['text' => 'Gajah mini', 'is_correct' => false],
                    ['text' => 'Gajah pilek', 'is_correct' => true],
                    ['text' => 'Gajah mainan', 'is_correct' => false],
                    ['text' => 'Gajah sumatera', 'is_correct' => false],
                ],
            ],

            // Kantor & Kerjaan
            [
                'category_slug' => 'kantor-kerjaan',
                'question_text' => 'Pekerjaan apa yang kerjanya cuma ketawa doang tapi digaji?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Pelawak profesional! Tapi kalau garing ya dilempar gorengan dingin.',
                'options' => [
                    ['text' => 'Bos santai', 'is_correct' => false],
                    ['text' => 'Pelawak', 'is_correct' => true],
                    ['text' => 'Kasir ramah', 'is_correct' => false],
                    ['text' => 'HRD kantor', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'kantor-kerjaan',
                'question_text' => 'Kenapa keyboard komputer harus ada tombol "Space"?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Karena kalau terlalu dekat tapi tanpa status itu sakit rasanya!',
                'options' => [
                    ['text' => 'Biar lega layarnya', 'is_correct' => false],
                    ['text' => 'Buat ngasih jarak', 'is_correct' => true],
                    ['text' => 'Biar jempol ada kerjaan', 'is_correct' => false],
                    ['text' => 'Standar pabrik dari sononya', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'kantor-kerjaan',
                'question_text' => 'Tukang apa yang kalau dipanggil namanya malah lari terbirit-birit?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Kalau dipanggil "Woi copet!" ya auto ngibrit dong!',
                'options' => [
                    ['text' => 'Tukang bakso', 'is_correct' => false],
                    ['text' => 'Tukang copet', 'is_correct' => true],
                    ['text' => 'Tukang gali sumur', 'is_correct' => false],
                    ['text' => 'Tukang parkir gaib', 'is_correct' => false],
                ],
            ],

            // Logika Receh
            [
                'category_slug' => 'logika-receh',
                'question_text' => 'Ada orang jalan di tengah hujan deras tanpa payung atau topi, tapi rambutnya sama sekali nggak basah. Kok bisa?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Gimana mau basah kalau kepalanya botak licin!',
                'options' => [
                    ['text' => 'Pakai minyak rambut tebal', 'is_correct' => false],
                    ['text' => 'Orangnya botak', 'is_correct' => true],
                    ['text' => 'Lari secepat kilat', 'is_correct' => false],
                    ['text' => 'Neduh di bawah pohon', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'logika-receh',
                'question_text' => 'Benda apa yang kalau dipotong ujungnya malah jadi makin tinggi?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Celana panjang! Dipotong bawahnya jadi celana pendek, dipotong lagi makin naik!',
                'options' => [
                    ['text' => 'Pohon bambu', 'is_correct' => false],
                    ['text' => 'Celana panjang', 'is_correct' => true],
                    ['text' => 'Tiang bendera', 'is_correct' => false],
                    ['text' => 'Tali tambang', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'logika-receh',
                'question_text' => 'Gajah naik becak keliling kota, yang kelihatan apanya?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Kelihatan bohongnya lah! Mana muat gajah di becak abang-abang.',
                'options' => [
                    ['text' => 'Belalainya yang panjang', 'is_correct' => false],
                    ['text' => 'Kupingnya yang lebar', 'is_correct' => false],
                    ['text' => 'Kelihatan bohongnya', 'is_correct' => true],
                    ['text' => 'Ban becaknya meletus', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'logika-receh',
                'question_text' => 'Kalau ada mobil merah belok kiri dan motor hijau belok kanan, yang jalan duluan siapa?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Ya rodanya lah yang jalan duluan, masa bodinya melayang!',
                'options' => [
                    ['text' => 'Mobil merah', 'is_correct' => false],
                    ['text' => 'Motor hijau', 'is_correct' => false],
                    ['text' => 'Rodanya', 'is_correct' => true],
                    ['text' => 'Yang buru-buru', 'is_correct' => false],
                ],
            ],

            // Tebakan Absurd
            [
                'category_slug' => 'tebakan-absurd',
                'question_text' => 'Kenapa pohon kelapa di depan pekarangan rumah harus ditebang?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Karena kalau dicabut berat! Hulk aja mikir dua kali.',
                'options' => [
                    ['text' => 'Takut kena sambar petir', 'is_correct' => false],
                    ['text' => 'Karena kalau dicabut berat', 'is_correct' => true],
                    ['text' => 'Mau dipanen kelapanya', 'is_correct' => false],
                    ['text' => 'Biar gak nutupin jalan', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'tebakan-absurd',
                'question_text' => 'Kenapa di baju Superman bagian depan ada huruf "S"?',
                'type' => 'multiple_choice',
                'difficulty' => 'mudah',
                'punchline' => 'Kalau huruf "M" itu ukuran bajunya, kalau "XL" kegedean!',
                'options' => [
                    ['text' => 'Singkatan Superman', 'is_correct' => false],
                    ['text' => 'Karena kalau "M" itu ukuran baju', 'is_correct' => true],
                    ['text' => 'Simbol harapan planet Krypton', 'is_correct' => false],
                    ['text' => 'Biar keren aja', 'is_correct' => false],
                ],
            ],
            [
                'category_slug' => 'tebakan-absurd',
                'question_text' => 'Kutu apa yang paling bikin kagum dan disayang seisi rumah?',
                'type' => 'multiple_choice',
                'difficulty' => 'sedang',
                'punchline' => 'Kutu-ruti semua kemauanmu! Idaman banget bapak yang satu ini.',
                'options' => [
                    ['text' => 'Kutu buku', 'is_correct' => false],
                    ['text' => 'Kutu rambut wangi', 'is_correct' => false],
                    ['text' => 'Kutu-ruti semua kemauanmu', 'is_correct' => true],
                    ['text' => 'Kutu kutub utara', 'is_correct' => false],
                ],
            ],
            // Short Answer Question Example
            [
                'category_slug' => 'logika-receh',
                'question_text' => 'Hewan apa yang bersaudara dan selalu hidup berdampingan di sawah?',
                'type' => 'short_answer',
                'difficulty' => 'sedang',
                'punchline' => 'Katak! Katak-beradik!',
                'accepted_answers' => ['katak', 'katak beradik', 'kodok'],
                'options' => [],
            ],
        ];

        foreach ($questionsData as $qData) {
            $cat = $cats[$qData['category_slug']] ?? null;
            if (! $cat) {
                continue;
            }

            $question = Question::create([
                'category_id' => $cat->id,
                'question_text' => $qData['question_text'],
                'type' => $qData['type'],
                'difficulty' => $qData['difficulty'],
                'punchline' => $qData['punchline'],
                'accepted_answers' => $qData['accepted_answers'] ?? null,
                'status' => 'published',
            ]);

            if ($qData['type'] === 'multiple_choice' && ! empty($qData['options'])) {
                foreach ($qData['options'] as $idx => $opt) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $opt['text'],
                        'is_correct' => $opt['is_correct'],
                        'order' => $idx + 1,
                    ]);
                }
            }
        }

        // 5. Seed Demo Users
        $rankLevel1 = Rank::where('level', 1)->first();
        $rankLevel5 = Rank::where('level', 5)->first();
        $rankLevel3 = Rank::where('level', 3)->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@membahur.test'],
            [
                'name' => 'Pak Bambang (Suhu)',
                'username' => 'suhu_bambang',
                'password' => Hash::make('password'),
                'bio' => 'Langganan warkop sejak zaman tape kaset. Jangan ajak debat kalau belum ngopi.',
                'total_xp' => 1250,
                'rank_id' => Rank::where('level', 5)->value('id') ?? 1,
                'streak_days' => 7,
                'is_admin' => true,
            ]
        );

        $demoUser = User::updateOrCreate(
            ['email' => 'budi@membahur.test'],
            [
                'name' => 'Budi Santoso',
                'username' => 'budi_receh',
                'password' => Hash::make('password'),
                'bio' => 'Penikmat kopi tubruk dan jokes garing garis keras.',
                'total_xp' => 350,
                'rank_id' => Rank::where('level', 3)->value('id') ?? 1,
                'streak_days' => 3,
                'is_admin' => false,
            ]
        );

        $newUser = User::updateOrCreate(
            ['email' => 'user@membahur.test'],
            [
                'name' => 'Anak Baru',
                'username' => 'anak_baru',
                'password' => Hash::make('password'),
                'bio' => 'Baru gabung di warung, siap naik kasta humor!',
                'total_xp' => 45,
                'rank_id' => Rank::where('level', 1)->value('id') ?? 1,
                'streak_days' => 1,
                'is_admin' => false,
            ]
        );

        // Initial XP transactions for demo ledger
        UserXpTransaction::updateOrCreate(
            ['idempotency_key' => 'seed-admin-xp-initial'],
            [
                'user_id' => $admin->id,
                'amount' => 750,
                'reason' => 'Bonus Pemilik Warung & Suhu Warkop',
                'reference_type' => 'system',
                'reference_id' => '1',
            ]
        );

        UserXpTransaction::updateOrCreate(
            ['idempotency_key' => 'seed-budi-xp-initial'],
            [
                'user_id' => $demoUser->id,
                'amount' => 320,
                'reason' => 'Menyelesaikan 4 sesi kuis dan 1 perfect score',
                'reference_type' => 'system',
                'reference_id' => '1',
            ]
        );

        // Achievements for demo
        $firstGameAch = Achievement::where('code', 'first_game')->first();
        if ($firstGameAch) {
            $admin->achievements()->syncWithoutDetaching([$firstGameAch->id => ['unlocked_at' => now()]]);
            $demoUser->achievements()->syncWithoutDetaching([$firstGameAch->id => ['unlocked_at' => now()]]);
        }

        // 6. Seed Community Jokes
        $jokesData = [
            [
                'user_id' => $admin->id,
                'category_id' => $cats['kantor-kerjaan']->id,
                'title' => 'Cuti Idaman Bapak-bapak',
                'setup' => 'Kemarin saya minta izin cuti ke bos kantor buat refreshing...',
                'punchline' => 'Bos nanya: "Mau refreshing ke mana pak?" Saya jawab: "Ke alam mimpi, biar gak mikirin revisi terus!"',
                'views_count' => 124,
            ],
            [
                'user_id' => $demoUser->id,
                'category_id' => $cats['makanan-minuman']->id,
                'title' => 'Gorengan Misterius',
                'setup' => 'Tahu gak kenapa bakwan jagung kalau digigit bunyi kress?',
                'punchline' => 'Karena kalau bunyinya "Assalamualaikum", itu bakwan lagi bertamu ke rumah calon mertua!',
                'views_count' => 88,
            ],
            [
                'user_id' => $admin->id,
                'category_id' => $cats['permainan-kata']->id,
                'title' => 'Kopi Paling Romantis',
                'setup' => 'Kopi apa yang paling bikin berbunga-bunga?',
                'punchline' => 'Kopi-kirkan kamu setiap detik tanpa henti...',
                'views_count' => 210,
            ],
        ];

        foreach ($jokesData as $jData) {
            $joke = Joke::create($jData);
            // Add reactions
            JokeReaction::create([
                'joke_id' => $joke->id,
                'user_id' => $demoUser->id,
                'reaction_type' => 'ngakak',
            ]);
            JokeReaction::create([
                'joke_id' => $joke->id,
                'user_id' => $newUser->id,
                'reaction_type' => 'bapak',
            ]);
        }
    }
}
