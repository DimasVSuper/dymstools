<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Generator | DymsProductivity</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap');
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-container {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 6px 32px rgba(67,97,238,0.10);
            padding: 36px 28px 28px 28px;
            text-align: center;
            width: 340px;
        }
        .qr-emoji {
            font-size: 2.5rem;
            margin-bottom: 8px;
            user-select: none;
        }
        .qr-title {
            font-size: 2rem;
            font-weight: 700;
            color: #3a86ff;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .qr-desc {
            color: #4a4e69;
            font-size: 1.08rem;
            margin-bottom: 18px;
        }
        .qr-form {
            display: flex;
            gap: 6px;
            margin-bottom: 18px;
        }
        .qr-form input[type="text"] {
            flex: 1;
            padding: 9px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            font-size: 1rem;
        }
        .qr-form button {
            padding: 9px 18px;
            border-radius: 6px;
            border: none;
            background: #3a86ff;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .qr-form button:active {
            background: #4361ee;
        }
        .qr-result {
            margin-top: 12px;
            min-height: 140px;
        }
        .qr-img {
            margin: 0 auto;
            display: block;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(67,97,238,0.07);
            background: #f4f7fa;
            padding: 10px;
        }
        .qr-download {
            margin-top: 10px;
            display: inline-block;
            background: #219150;
            color: #fff;
            padding: 7px 18px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .qr-download:active {
            background: #157347;
        }
        .qr-home {
            margin-top: 18px;
            display: inline-block;
            background: #4361ee;
            color: #fff;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .qr-home:active {
            background: #22223b;
        }
        /* Popup tamu */
        .popup-fitur {
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
    </style>
</head>
<body>
    <div class="qr-container">
        <div class="qr-emoji">🔳</div>
        <div class="qr-title">QR Generator</div>
        <div class="qr-desc">Buat QR code dari teks, link, atau apapun.<br>Praktis, cepat, dan maskulin! 💪</div>
        <form class="qr-form" id="qrForm" autocomplete="off">
            <input type="text" id="qrText" placeholder="Masukkan teks/link..." maxlength="200" required>
            <button type="submit">Buat QR</button>
        </form>
        <div class="qr-result" id="qrResult"></div>
        <a href="<?= base_url('home') ?>" class="qr-home">← Balik ke Home</a>
    </div>
    <script>
    const qrForm = document.getElementById('qrForm');
    const qrText = document.getElementById('qrText');
    const qrResult = document.getElementById('qrResult');

    qrForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const text = qrText.value.trim();
        if (!text) return;
        const url = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(text)}`;
        qrResult.innerHTML = `
            <img src="${url}" alt="QR Code" class="qr-img" id="qrImg">
            <br>
            <a href="${url}" download="qrcode.png" class="qr-download">Download QR</a>
        `;
    });

    // Popup tamu jika guest (sessionStorage)
    <?php if (empty($_SESSION['user'])): ?>
    if (sessionStorage.getItem('is_guest') === '1') {
        document.body.insertAdjacentHTML('beforeend', `
        <div class="popup-fitur">
            <div class="popup-content">
                <div class="popup-emoji">🔒</div>
                <div class="popup-title">Fitur Terkunci</div>
                <div class="popup-desc">
                    Anda harus <b>login</b> atau <b>daftar akun</b> untuk menggunakan fitur ini.<br>
                    <a href="<?= base_url('login') ?>" id="popupLoginBtn">Login</a> atau 
                    <a href="<?= base_url('register') ?>" id="popupRegisterBtn">Daftar</a>
                </div>
            </div>
        </div>
        `);
        // Tambahkan event klik agar popup bisa langsung redirect
        document.getElementById('popupLoginBtn').addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
        document.getElementById('popupRegisterBtn').addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    }
    <?php endif; ?>
    </script>
</body>
</html>