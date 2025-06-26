<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi | DimProductivity</title>
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
        .register-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px 32px 32px 32px;
            text-align: center;
            width: 100%;
            max-width: 370px;
        }
        .register-emoji {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }
        .register-desc {
            color: var(--text-muted);
            font-size: 1.05rem;
            margin-bottom: 22px;
        }
        .register-form input[type="text"],
        .register-form input[type="password"] {
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
        .register-form input[type="text"]:focus,
        .register-form input[type="password"]:focus {
            border: 1.5px solid var(--primary);
        }
        .register-form button {
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
        .register-form button:hover {
            background: var(--primary-dark);
        }
        .register-footer {
            margin-top: 18px;
            font-size: 0.98rem;
            color: var(--text-muted);
        }
        .register-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .register-footer a:hover {
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
        .success-message {
            color: #219150;
            background: #e6fff2;
            border-radius: 6px;
            padding: 8px 0;
            margin-bottom: 14px;
            font-size: 0.98rem;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-emoji">📝</div>
        <div class="register-title">Registrasi</div>
        <div class="register-desc">Buat akun DimProductivity untuk mulai mengelola tugas harianmu.</div>
        <?php if (!empty($error)) : ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form class="register-form" method="post" action="">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirm" placeholder="Konfirmasi Password" required>
            <button type="submit">Daftar</button>
        </form>
        <div class="register-footer">
            Sudah punya akun? <a href="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/login' ?>">Login</a>
        </div>
    </div>
    <script>
    document.querySelector('.register-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        // Hapus pesan error/sukses lama
        const oldError = document.querySelector('.error-message');
        if (oldError) oldError.remove();
        const oldSuccess = document.querySelector('.success-message');
        if (oldSuccess) oldSuccess.remove();

        // Kirim AJAX ke backend
        const res = await fetch(form.action || '', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await res.json();

        if (data.success) {
            // Redirect langsung ke login
            window.location.href = data.redirect || '<?= dirname($_SERVER['SCRIPT_NAME']) !== '/' ? dirname($_SERVER['SCRIPT_NAME']) . '/login' : '/login' ?>';
        } else {
            const errDiv = document.createElement('div');
            errDiv.className = 'error-message';
            errDiv.textContent = data.error || 'Registrasi gagal!';
            form.parentNode.insertBefore(errDiv, form);
        }
    });
    </script>
</body>