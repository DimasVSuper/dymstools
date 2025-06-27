<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = !empty($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found | DymsProductivity</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 48px 32px;
            text-align: center;
            max-width: 400px;
        }
        .emoji {
            font-size: 4rem;
            margin-bottom: 16px;
        }
        h1 {
            font-size: 3rem;
            margin: 0 0 12px 0;
            font-weight: 700;
            color: var(--primary);
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 24px;
            color: var(--text-muted);
        }
        a {
            display: inline-block;
            padding: 10px 28px;
            background: var(--primary);
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }
        a:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">🚧😕</div>
        <h1>404</h1>
        <p>
            Oops! Halaman <b><?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></b> tidak ditemukan.<br>
            Mungkin sudah dipindahkan atau dihapus.
        </p>
        <a href="<?= $isLoggedIn ? base_url('home') : base_url('login') ?>">
             Kembali ke tempat semula
        </a>
    </div>
</body>