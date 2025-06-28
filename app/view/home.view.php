<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home | DymsTools</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #3a86ff;
            --primary-dark: #4361ee;
            --bg: #f8fafc;
            --card-bg: #fff;
            --text-main: #22223b;
            --text-muted: #4a4e69;
            --radius: 18px;
            --shadow: 0 4px 24px rgba(34,34,59,0.07);
            --font-main: 'Montserrat', Arial, sans-serif;
        }
        @import url('https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap');
        body {
            background: var(--bg);
            color: var(--text-main);
            font-family: var(--font-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .hero {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 48px 32px 32px 32px;
            text-align: center;
            max-width: 480px;
            margin: 48px auto 32px auto;
        }
        .hero-emoji {
            font-size: 4rem;
            margin-bottom: 12px;
        }
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 10px 0;
        }
        .hero-desc {
            color: var(--text-muted);
            font-size: 1.15rem;
            margin-bottom: 28px;
        }
        .hero-btn {
            display: inline-block;
            padding: 12px 32px;
            background: var(--primary);
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(58,134,255,0.07);
        }
        .hero-btn:hover {
            background: var(--primary-dark);
        }
        .footer {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 48px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <!-- Hero 1: Welcome -->
    <div class="hero">
        <div class="hero-emoji">🚀</div>
        <h1 class="hero-title">Selamat Datang di DymsTools!</h1>
        <div class="hero-desc">
            Tingkatkan aktivitasmu dengan berbagai alat sederhana dan efisien.<br>
            Mulai dari daftar tugas, kalkulator, timer, QR code, hingga konversi satuan!
        </div>
    </div>

    <!-- Hero 2: ToDoList -->
    <div class="hero">
        <div class="hero-emoji">📝</div>
        <h2 class="hero-title">ToDoList</h2>
        <div class="hero-desc">
            Buat, kelola, dan tandai tugas harianmu.<br>
            Selesaikan targetmu setiap hari dengan mudah!
        </div>
        <a class="hero-btn" href="<?= base_url('todolist') ?>">
            Mulai ToDoList &nbsp;📝
        </a>
    </div>

    <!-- Hero 3: Kalkulator -->
    <div class="hero">
        <div class="hero-emoji">🧮</div>
        <h2 class="hero-title">Kalkulator</h2>
        <div class="hero-desc">
            Hitung dengan cepat dan mudah.<br>
            Cocok untuk perhitungan sehari-hari!
        </div>
        <a class="hero-btn" href="<?= base_url('calculator') ?>">
            Buka Kalkulator &nbsp;🧮
        </a>
    </div>

    <!-- Hero 4 : Timer -->
    <div class="hero">
        <div class="hero-emoji">⏰</div>
        <h2 class="hero-title">Timer Fokus</h2>
        <div class="hero-desc">
            Atur waktu fokusmu, mulai dan raih hasil maksimal!<br>
            Cocok untuk teknik Pomodoro atau sesi belajar/kerja.
        </div>
        <a class="hero-btn" href="<?= base_url('timer') ?>">
            Buka Timer &nbsp;⏰
        </a>
    </div>

    <!-- Hero 5: QR Generator -->
    <div class="hero">
        <div class="hero-emoji">🔳</div>
        <h2 class="hero-title">QR Generator</h2>
        <div class="hero-desc">
            Buat QR code dari teks atau link.<br>
            Praktis dan cepat!
        </div>
        <a class="hero-btn" href="<?= base_url('qr') ?>">
            Buka QR Generator &nbsp;🔳
        </a>
    </div>
    <!-- Hero 6 : Unit Converter -->
    <div class="hero">
        <div class="hero-emoji">📏</div>
        <h2 class="hero-title">Unit Converter</h2>
        <div class="hero-desc">
            Ubah satuan panjang, berat, suhu, dan lainnya.<br>
            Mudah dan cepat untuk kebutuhan sehari-hari!
        </div>
        <a class="hero-btn" href="<?= base_url('unit') ?>">
            Buka Unit Converter &nbsp;📏
        </a>
    </div>

    <!-- Hero 7: Note Publik -->
    <div class="hero">
        <div class="hero-emoji">🗒️</div>
        <h2 class="hero-title">Note Publik</h2>
        <div class="hero-desc">
            Tempelkan catatan, ide, atau pesan singkat yang bisa dilihat dan dihapus siapa saja.<br>
            Cocok untuk brainstorming, pesan publik, atau sekadar iseng!
        </div>
        <a class="hero-btn" href="<?= base_url('note') ?>">
            Buka Note Publik &nbsp;🗒️
        </a>
    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> DymsTools &middot; Dibuat dengan <span style="color:#e63946;">&#10084;</span>
    </div>
</body>
</html>