<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Timer | DymsProductivity</title>
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
        .timer-container {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 6px 32px rgba(67,97,238,0.10);
            padding: 38px 32px 32px 32px;
            text-align: center;
            width: 340px;
            position: relative;
        }
        .timer-emoji {
            font-size: 3.2rem;
            margin-bottom: 8px;
            user-select: none;
        }
        .timer-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: #3a86ff;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .timer-desc {
            color: #4a4e69;
            font-size: 1.08rem;
            margin-bottom: 22px;
        }
        .timer-display {
            font-size: 3.2rem;
            font-weight: 700;
            color: #22223b;
            letter-spacing: 2px;
            margin-bottom: 18px;
            background: #f4f7fa;
            border-radius: 12px;
            padding: 16px 0;
            box-shadow: 0 2px 8px rgba(67,97,238,0.07);
            transition: background 0.2s;
            user-select: none;
        }
        .timer-controls {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-bottom: 10px;
        }
        .timer-btn {
            padding: 12px 22px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            background: #3a86ff;
            color: #fff;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 2px 8px rgba(67,97,238,0.07);
        }
        .timer-btn:active {
            background: #4361ee;
            transform: scale(0.97);
        }
        .timer-btn.reset {
            background: #22223b;
        }
        .timer-btn.reset:active {
            background: #4a4e69;
        }
        .timer-set {
            margin-bottom: 18px;
        }
        .timer-set input {
            width: 60px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            font-size: 1.1rem;
            text-align: center;
            margin-right: 6px;
        }
        .timer-set label {
            font-size: 1rem;
            color: #4361ee;
            font-weight: 600;
            margin-right: 8px;
        }
        .timer-progress {
            width: 100%;
            height: 8px;
            background: #e0e7ff;
            border-radius: 6px;
            margin-bottom: 18px;
            overflow: hidden;
        }
        .timer-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #3a86ff 0%, #4361ee 100%);
            width: 0%;
            transition: width 0.3s;
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
    <div class="timer-container">
        <div class="timer-emoji" id="timerEmoji">⏰</div>
        <div class="timer-title">Timer Fokus</div>
        <div class="timer-desc">Atur waktu fokusmu, mulai dan raih produktivitas maksimal!<br>
            <span style="font-size:1.2em;">💪🕒</span>
        </div>
        <form class="timer-set" id="timerSetForm" autocomplete="off">
            <label for="minutes">Menit:</label>
            <input type="number" id="minutes" min="1" max="120" value="25">
            <button type="submit" class="timer-btn" style="padding:8px 18px;font-size:1rem;">Set</button>
        </form>
        <div class="timer-progress">
            <div class="timer-progress-bar" id="progressBar"></div>
        </div>
        <div class="timer-display" id="timerDisplay">25:00</div>
        <div class="timer-controls">
            <button class="timer-btn" id="startBtn">Mulai</button>
            <button class="timer-btn reset" id="resetBtn" disabled>Reset</button>
            <a href="<?= base_url('home') ?>" class="timer-btn">
                ← Balik ke Home
            </a>
        </div>
    </div>
    <script>
let totalSeconds = 25 * 60;
let remainingSeconds = totalSeconds;
let timer = null;
let isRunning = false;
const progressBar = document.getElementById('progressBar');
const timerDisplay = document.getElementById('timerDisplay');
const startPauseBtn = document.getElementById('startBtn');
const resetBtn = document.getElementById('resetBtn');
const timerSetForm = document.getElementById('timerSetForm');
const timerEmoji = document.getElementById('timerEmoji');

function updateDisplay() {
    let min = Math.floor(remainingSeconds / 60);
    let sec = remainingSeconds % 60;
    timerDisplay.textContent = `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
    let percent = 100 - (remainingSeconds / totalSeconds) * 100;
    progressBar.style.width = percent + '%';
}

function setEmoji(state) {
    if (state === 'run') timerEmoji.textContent = "🔥";
    else if (state === 'pause') timerEmoji.textContent = "😎";
    else if (state === 'done') timerEmoji.textContent = "🏆";
    else timerEmoji.textContent = "⏰";
}

function startTimer() {
    if (timer) return;
    isRunning = true;
    setEmoji('run');
    startPauseBtn.textContent = "Pause";
    resetBtn.disabled = false;
    timer = setInterval(() => {
        if (remainingSeconds > 0) {
            remainingSeconds--;
            updateDisplay();
        } else {
            clearInterval(timer);
            timer = null;
            isRunning = false;
            setEmoji('done');
            startPauseBtn.textContent = "Mulai";
            resetBtn.disabled = false;
        }
    }, 1000);
}

function pauseTimer() {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
    isRunning = false;
    setEmoji('pause');
    startPauseBtn.textContent = "Mulai";
}

function toggleStartPause() {
    if (!isRunning) {
        startTimer();
    } else {
        pauseTimer();
    }
}

function resetTimer() {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
    remainingSeconds = totalSeconds;
    updateDisplay();
    setEmoji();
    isRunning = false;
    startPauseBtn.textContent = "Mulai";
    resetBtn.disabled = true;
}

timerSetForm.addEventListener('submit', function(e) {
    e.preventDefault();
    let min = parseInt(document.getElementById('minutes').value) || 1;
    if (min < 1) min = 1;
    if (min > 120) min = 120;
    totalSeconds = min * 60;
    remainingSeconds = totalSeconds;
    resetTimer();
    updateDisplay();
});

startPauseBtn.textContent = "Mulai";
startPauseBtn.addEventListener('click', toggleStartPause);
resetBtn.addEventListener('click', resetTimer);

// Init
updateDisplay();
setEmoji();

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