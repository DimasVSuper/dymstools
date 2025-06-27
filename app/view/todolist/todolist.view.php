<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ToDoList | DymsProductivity</title>
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
        .todo-container {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 6px 32px rgba(67,97,238,0.10);
            padding: 36px 28px 28px 28px;
            text-align: center;
            width: 370px;
        }
        .todo-emoji {
            font-size: 2.5rem;
            margin-bottom: 8px;
            user-select: none;
        }
        .todo-title {
            font-size: 2rem;
            font-weight: 700;
            color: #3a86ff;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .todo-desc {
            color: #4a4e69;
            font-size: 1.08rem;
            margin-bottom: 18px;
        }
        .todo-form {
            display: flex;
            gap: 6px;
            margin-bottom: 18px;
        }
        .todo-form input[type="text"] {
            flex: 1;
            padding: 9px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            font-size: 1rem;
        }
        .todo-form button {
            padding: 9px 18px;
            border-radius: 6px;
            border: none;
            background: #3a86ff;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .todo-form button:active {
            background: #4361ee;
        }
        .todo-list {
            text-align: left;
            min-height: 40px;
            margin-bottom: 10px;
        }
        .todo-item {
            background: #e0e7ff;
            color: #22223b;
            border-radius: 6px;
            padding: 8px 10px;
            margin-bottom: 8px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: space-between;
        }
        .todo-item.done {
            text-decoration: line-through;
            color: #b0b0b0;
            background: #f4f7fa;
        }
        .todo-actions button {
            background: none;
            border: none;
            color: #e63946;
            font-size: 1.2em;
            cursor: pointer;
            margin-left: 8px;
            transition: color 0.2s;
        }
        .todo-actions button:active {
            color: #b71c1c;
        }
        .todo-home {
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
        .todo-home:active {
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
    <div class="todo-container">
        <div class="todo-emoji">📝</div>
        <div class="todo-title">ToDoList</div>
        <div class="todo-desc">Catat tugas, target, atau ide harianmu.<br>Tambah, centang, hapus dengan mudah!</div>
        <form class="todo-form" id="todoForm" autocomplete="off">
            <input type="text" id="todoInput" placeholder="Tambah tugas baru..." maxlength="60" required>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?= \App\Middleware\Middleware::csrfToken() ?>">
            <button type="submit">Tambah</button>
        </form>
        <div class="todo-list" id="todoList"></div>
        <a href="<?= base_url('home') ?>" class="todo-home">← Balik ke Home</a>
    </div>
    <script>
    // Ambil CSRF token dari input hidden
    function getCsrf() {
        return document.getElementById('csrf_token').value;
    }

    // AJAX ToDoList (session backend)
    function fetchTodos() {
        fetch('<?= base_url('todolist/ajax') ?>')
            .then(res => res.json())
            .then(renderTodos);
    }
    function renderTodos(todos) {
        const list = document.getElementById('todoList');
        if (!todos || todos.length === 0) {
            list.innerHTML = '<div style="color:#b0b0b0;font-style:italic;">Belum ada tugas.</div>';
            return;
        }
        list.innerHTML = '';
        todos.forEach(todo => {
            const div = document.createElement('div');
            div.className = 'todo-item' + (todo.done ? ' done' : '');
            div.innerHTML = `
                <span style="flex:1;cursor:pointer;" onclick="toggleDone(${todo.id})">${todo.text}</span>
                <span class="todo-actions">
                    <button title="Hapus" onclick="deleteTodo(${todo.id});event.stopPropagation();">🗑️</button>
                </span>
            `;
            list.appendChild(div);
        });
    }
    function addTodo(text) {
        fetch('<?= base_url('todolist/add') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'text=' + encodeURIComponent(text) + '&csrf_token=' + encodeURIComponent(getCsrf())
        })
        .then(res => res.json())
        .then(() => fetchTodos());
    }
    function deleteTodo(id) {
        fetch('<?= base_url('todolist/delete') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id) + '&csrf_token=' + encodeURIComponent(getCsrf())
        })
        .then(res => res.json())
        .then(() => fetchTodos());
    }
    function toggleDone(id) {
        fetch('<?= base_url('todolist/toggle') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id) + '&csrf_token=' + encodeURIComponent(getCsrf())
        })
        .then(res => res.json())
        .then(() => fetchTodos());
    }
    document.getElementById('todoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('todoInput');
        const text = input.value.trim();
        if (!text) return;
        addTodo(text);
        input.value = '';
    });
    // Expose for onclick
    window.deleteTodo = deleteTodo;
    window.toggleDone = toggleDone;
    // Init
    fetchTodos();

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