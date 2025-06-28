<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Generator | DymsTools</title>
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
        `;
    });
    </script>
</body>
</html>