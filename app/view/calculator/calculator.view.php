<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Calculator | DymsProductivity</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: white;
            font-family: 'Montserrat', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .calc-container {
            background: #23262f;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(34,34,59,0.18);
            padding: 32px 24px;
            width: 320px;
        }
        .calc-display {
            width: 100%;
            font-size: 2.3rem;
            padding: 18px 12px 18px 12px;
            border-radius: 10px;
            border: 1.5px solid #353945;
            margin-bottom: 22px;
            text-align: right;
            background: #181a20;
            color: #fff;
            letter-spacing: 1px;
            box-sizing: border-box;
            outline: none;
        }
        .calc-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .calc-btn {
            padding: 18px 0;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            background: #353945;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .calc-btn.operator {
            background: #3a86ff;
        }
        .calc-btn.equal {
            background: #219150;
        }
        .calc-btn.clear {
            background: #e63946;
        }
        .calc-btn:active {
            background: #1d3557;
        }
        .calc-home {
            display:block;
            text-align:center;
            margin:22px auto 0 auto;
            width:100%;
            background:#3a86ff;
            color:#fff;
            border-radius:8px;
            padding:10px 0;
            font-weight:600;
            text-decoration:none;
            transition: background 0.2s;
        }
        .calc-home:active {
            background: #4361ee;
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
    <div class="calc-container">
        <input type="text" class="calc-display" id="display" value="0" readonly>
        <div class="calc-buttons">
            <button class="calc-btn" data-value="7">7</button>
            <button class="calc-btn" data-value="8">8</button>
            <button class="calc-btn" data-value="9">9</button>
            <button class="calc-btn operator" data-value="/">÷</button>
            <button class="calc-btn" data-value="4">4</button>
            <button class="calc-btn" data-value="5">5</button>
            <button class="calc-btn" data-value="6">6</button>
            <button class="calc-btn operator" data-value="*">×</button>
            <button class="calc-btn" data-value="1">1</button>
            <button class="calc-btn" data-value="2">2</button>
            <button class="calc-btn" data-value="3">3</button>
            <button class="calc-btn operator" data-value="-">−</button>
            <button class="calc-btn" data-value="0">0</button>
            <button class="calc-btn" data-value=".">.</button>
            <button class="calc-btn clear" data-action="clear">C</button>
            <button class="calc-btn operator" data-value="+">+</button>
            <button class="calc-btn equal" style="grid-column: span 4;" data-action="equal">=</button>
        </div>
        <a href="<?= base_url('home') ?>" class="calc-home">
            Balik ke Home
        </a>
    </div>
    <script>
    const display = document.getElementById('display');
    let current = '';
    let resetNext = false;

    document.querySelectorAll('.calc-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            const action = this.getAttribute('data-action');

            if (action === 'clear') {
                current = '';
                display.value = '0';
                return;
            }
            if (action === 'equal') {
                try {
                    // Evaluate safely
                    let result = eval(current.replace(/[^0-9+\-*/.]/g, ''));
                    if (result === undefined || isNaN(result)) result = 0;
                    display.value = result;
                    current = result.toString();
                    resetNext = true;
                } catch {
                    display.value = 'Error';
                    current = '';
                    resetNext = true;
                }
                return;
            }
            if (resetNext) {
                current = '';
                resetNext = false;
            }
            if (value) {
                current += value;
                display.value = current;
            }
        });
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