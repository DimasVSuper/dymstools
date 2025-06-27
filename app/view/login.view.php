<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | DimProductivity</title>
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
        .login-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px 32px 32px 32px;
            text-align: center;
            width: 100%;
            max-width: 350px;
        }
        .login-emoji {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }
        .login-desc {
            color: var(--text-muted);
            font-size: 1.05rem;
            margin-bottom: 22px;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            background: #f4f7fa;
            color: var(--text-main);
            outline: none;
            transition: border 0.2s;
        }
        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus {
            border: 1.5px solid var(--primary);
        }
        .login-form button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .login-form button:hover {
            background: var(--primary-dark);
        }
        .login-footer {
            margin-top: 18px;
            font-size: 0.98rem;
            color: var(--text-muted);
        }
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #e63946;
            background: #ffe5e9;
            border-radius: 6px;
            padding: 8px 0;
            margin-bottom: 14px;
            font-size: 0.98rem;
        }
        .popup-larang, .popup-fitur {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(34,34,59,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .popup-content {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(34,34,59,0.12);
            padding: 32px 24px;
            text-align: center;
            max-width: 320px;
            animation: popupIn .25s;
        }
        .popup-emoji {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .popup-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #e63946;
            margin-bottom: 10px;
        }
        .popup-desc {
            color: #22223b;
            font-size: 1.05rem;
        }
        .popup-desc a {
            color: #3a86ff;
            text-decoration: none;
            font-weight: 600;
        }
        @keyframes popupIn {
            from { transform: scale(0.8); opacity: 0;}
            to { transform: scale(1); opacity: 1;}
        }
        .btn-tamu {
            margin-top: 10px;
            width: 100%;
            padding: 12px;
            background: #b0b0b0;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-tamu:hover {
            background: #888;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-emoji">🔐</div>
        <div class="login-title">Login</div>
        <div class="login-desc">Masuk ke Productivity App untuk mengelola tugas harianmu.</div>
        <?php if (!empty($error)) : ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form class="login-form" method="post" action="<?= base_url('login') ?>">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>
        <button class="btn-tamu" id="btnTamu" type="button">Masuk sebagai Tamu</button>
        <div class="login-footer">
            Belum punya akun? <a href="<?= base_url('register') ?>">Daftar</a>
        </div>
    </div>
    <?php if (!empty($_SESSION['auth_error'])): ?>
        <div class="popup-larang">
            <div class="popup-content">
                <div class="popup-emoji">🚫</div>
                <div class="popup-title">Akses Dilarang</div>
                <div class="popup-desc">
                    Login dahulu untuk mengakses halaman ini.<br>
                    Belum punya akun? <a href="<?= base_url('register') ?>">Daftar di sini</a>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['auth_error']); endif; ?>
    <script>
    // AJAX login
    document.querySelector('.login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        // Hapus error lama
        const oldError = document.querySelector('.error-message');
        if (oldError) oldError.remove();

        try {
            const res = await fetch(form.action || '', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Cek apakah response JSON valid
            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (err) {
                throw new Error('Server tidak membalas JSON. Cek backend!');
            }

            if (data.success) {
                window.location.href = data.redirect || '<?= base_url('home') ?>';
            } else {
                const errDiv = document.createElement('div');
                errDiv.className = 'error-message';
                errDiv.textContent = data.error || 'Login gagal!';
                form.parentNode.insertBefore(errDiv, form);
            }
        } catch (err) {
            const errDiv = document.createElement('div');
            errDiv.className = 'error-message';
            errDiv.textContent = err.message || 'Terjadi kesalahan koneksi/server!';
            form.parentNode.insertBefore(errDiv, form);
        }
    });

    // Tombol tamu
    document.getElementById('btnTamu').addEventListener('click', function() {
        // Simpan status tamu di sessionStorage
        sessionStorage.setItem('is_guest', '1');
        // Hapus session user jika ada (agar benar-benar tamu)
        <?php
        // Pastikan session user dihapus jika sebelumnya login
        unset($_SESSION['user']);
        ?>
        window.location.href = '<?= base_url('home') ?>';
    });

    // Tidak perlu popup tamu di halaman login.
    // Popup hanya ditampilkan di halaman fitur jika tamu.
    </script>
</body>
</html>