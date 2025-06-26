<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$nama = isset($_SESSION['user']['nama']) ? $_SESSION['user']['nama'] : 'Produktif!';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Productivity App</title>
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
        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: var(--card-bg);
            box-shadow: var(--shadow);
            padding: 16px 32px;
        }
        .logout-btn {
            background: #e63946;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #b71c1c;
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
    <div class="header">
        <form id="logoutForm" method="post" action="/logout" style="margin:0;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- Hero 1: Welcome -->
    <div class="hero">
        <div class="hero-emoji">🚀</div>
        <h1 class="hero-title">Selamat Datang, <?= htmlspecialchars($nama) ?></h1>
        <div class="hero-desc">
            Tingkatkan produktivitasmu dengan mengelola tugas harian secara mudah dan efisien.<br>
            Mulai dengan membuat daftar tugas, tandai yang sudah selesai, dan capai targetmu setiap hari!
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
        <a class="hero-btn" href="<?= dirname($_SERVER['SCRIPT_NAME']) !== '/' ? dirname($_SERVER['SCRIPT_NAME']) . '/todolist' : '/todolist' ?>">
            Mulai ToDoList &nbsp;📝
        </a>
    </div>

    <!-- Hero 3: Word to PDF Converter -->
    <div class="hero">
        <div class="hero-emoji">📄➡️📑</div>
        <h2 class="hero-title">Word to PDF Converter</h2>
        <div class="hero-desc">
            Ubah dokumen Word (.docx) menjadi PDF dengan cepat dan mudah.<br>
            Praktis untuk kebutuhan tugas, laporan, dan lainnya!
        </div>
        <a class="hero-btn" href="<?= dirname($_SERVER['SCRIPT_NAME']) !== '/' ? dirname($_SERVER['SCRIPT_NAME']) . '/wordtopdf' : '/wordtopdf' ?>">
            Buka Converter &nbsp;🔄
        </a>
    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> Productivity App &middot; Dibuat dengan <span style="color:#e63946;">&#10084;</span>
    </div>

    <script>
    // AJAX logout, redirect ke login
    document.getElementById('logoutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const res = await fetch('/logout', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else {
            window.location.href = '/login';
        }
    });
    </script>
</body>
</html>