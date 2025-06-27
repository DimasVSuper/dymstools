<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Unit Converter | DymsProductivity</title>
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
        .unit-container {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 6px 32px rgba(67,97,238,0.10);
            padding: 36px 28px 28px 28px;
            text-align: center;
            width: 340px;
        }
        .unit-emoji {
            font-size: 2.5rem;
            margin-bottom: 8px;
            user-select: none;
        }
        .unit-title {
            font-size: 2rem;
            font-weight: 700;
            color: #3a86ff;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .unit-desc {
            color: #4a4e69;
            font-size: 1.08rem;
            margin-bottom: 18px;
        }
        .unit-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 18px;
        }
        .unit-form input, .unit-form select {
            padding: 9px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            font-size: 1rem;
        }
        .unit-form button {
            padding: 9px 18px;
            border-radius: 6px;
            border: none;
            background: #3a86ff;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .unit-form button:active {
            background: #4361ee;
        }
        .unit-result {
            margin-top: 16px;
            min-height: 32px;
            font-size: 1.15rem;
            color: #219150;
            font-weight: 600;
        }
        .unit-home {
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
        .unit-home:active {
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
    <div class="unit-container">
        <div class="unit-emoji">📏</div>
        <div class="unit-title">Unit Converter</div>
        <div class="unit-desc">Konversi satuan panjang, berat, dan suhu.<br>Praktis dan Cepat 😀</div>
        <form class="unit-form" id="unitForm" autocomplete="off">
            <input type="number" id="inputValue" placeholder="Masukkan nilai..." step="any" required>
            <select id="inputUnit">
                <optgroup label="Panjang">
                    <option value="m">Meter (m)</option>
                    <option value="cm">Centimeter (cm)</option>
                    <option value="km">Kilometer (km)</option>
                    <option value="inch">Inci (inch)</option>
                    <option value="ft">Kaki (ft)</option>
                </optgroup>
                <optgroup label="Berat">
                    <option value="kg">Kilogram (kg)</option>
                    <option value="g">Gram (g)</option>
                    <option value="mg">Miligram (mg)</option>
                    <option value="lb">Pound (lb)</option>
                </optgroup>
                <optgroup label="Suhu">
                    <option value="c">Celsius (°C)</option>
                    <option value="f">Fahrenheit (°F)</option>
                    <option value="k">Kelvin (K)</option>
                </optgroup>
            </select>
            <span style="font-size:1.3em;">→</span>
            <select id="outputUnit">
                <optgroup label="Panjang">
                    <option value="m">Meter (m)</option>
                    <option value="cm">Centimeter (cm)</option>
                    <option value="km">Kilometer (km)</option>
                    <option value="inch">Inci (inch)</option>
                    <option value="ft">Kaki (ft)</option>
                </optgroup>
                <optgroup label="Berat">
                    <option value="kg">Kilogram (kg)</option>
                    <option value="g">Gram (g)</option>
                    <option value="mg">Miligram (mg)</option>
                    <option value="lb">Pound (lb)</option>
                </optgroup>
                <optgroup label="Suhu">
                    <option value="c">Celsius (°C)</option>
                    <option value="f">Fahrenheit (°F)</option>
                    <option value="k">Kelvin (K)</option>
                </optgroup>
            </select>
            <button type="submit">Konversi</button>
        </form>
        <div class="unit-result" id="unitResult"></div>
        <a href="<?= base_url('home') ?>" class="unit-home">← Balik ke Home</a>
    </div>
    <script>
    function convertUnit(value, from, to) {
        // Panjang (meter sebagai basis)
        const length = {
            m: 1,
            cm: 0.01,
            km: 1000,
            inch: 0.0254,
            ft: 0.3048
        };
        // Berat (kg sebagai basis)
        const weight = {
            kg: 1,
            g: 0.001,
            mg: 0.000001,
            lb: 0.453592
        };
        // Suhu
        function convertTemp(val, from, to) {
            if (from === to) return val;
            // Celsius ke ...
            if (from === 'c') {
                if (to === 'f') return val * 9/5 + 32;
                if (to === 'k') return val + 273.15;
            }
            // Fahrenheit ke ...
            if (from === 'f') {
                if (to === 'c') return (val - 32) * 5/9;
                if (to === 'k') return (val - 32) * 5/9 + 273.15;
            }
            // Kelvin ke ...
            if (from === 'k') {
                if (to === 'c') return val - 273.15;
                if (to === 'f') return (val - 273.15) * 9/5 + 32;
            }
            return null;
        }

        // Cek jenis satuan
        if (from in length && to in length) {
            // Panjang
            return value * length[from] / length[to];
        }
        if (from in weight && to in weight) {
            // Berat
            return value * weight[from] / weight[to];
        }
        if ((from === 'c' || from === 'f' || from === 'k') &&
            (to === 'c' || to === 'f' || to === 'k')) {
            // Suhu
            return convertTemp(value, from, to);
        }
        return null;
    }

    document.getElementById('unitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const val = parseFloat(document.getElementById('inputValue').value);
        const from = document.getElementById('inputUnit').value;
        const to = document.getElementById('outputUnit').value;
        const resultDiv = document.getElementById('unitResult');
        if (from === to) {
            resultDiv.textContent = 'Satuan asal dan tujuan sama.';
            return;
        }
        const result = convertUnit(val, from, to);
        if (result === null || isNaN(result)) {
            resultDiv.textContent = 'Konversi tidak valid.';
        } else {
            let display = result;
            if (from === 'c' || from === 'f' || from === 'k') {
                display = result.toFixed(2);
            } else {
                display = result.toLocaleString('id-ID', { maximumFractionDigits: 6 });
            }
            resultDiv.textContent = `${val} ${from.toUpperCase()} = ${display} ${to.toUpperCase()}`;
        }
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