
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ToDoList | DymsTools</title>
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
        .todo-actions .edit-btn {
            color: #3a86ff;
            margin-left: 0;
            margin-right: 8px;
        }
        .todo-actions .edit-btn:active {
            color: #4361ee;
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
        .edit-form {
            display: flex;
            gap: 6px;
            margin-bottom: 8px;
        }
        .edit-form input[type="text"] {
            flex: 1;
            padding: 7px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            font-size: 1rem;
        }
        .edit-form button {
            padding: 7px 14px;
            border-radius: 6px;
            border: none;
            background: #219150;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .edit-form button:active {
            background: #157347;
        }
        .edit-form .cancel-btn {
            background: #e63946;
        }
        .edit-form .cancel-btn:active {
            background: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="todo-container">
        <div class="todo-emoji">📝</div>
        <div class="todo-title">ToDoList</div>
        <div class="todo-desc">Catat tugas, target, atau ide harianmu.<br>Tambah, edit, centang, hapus dengan mudah!</div>
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

    // State untuk edit
    let editingId = null;

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
            if (editingId === todo.id) {
                // Edit mode
                const editDiv = document.createElement('div');
                editDiv.className = 'edit-form';
                editDiv.innerHTML = `
                    <input type="text" id="editInput" value="${escapeHtml(todo.text)}" maxlength="60" required>
                    <button onclick="submitEdit(${todo.id})">Simpan</button>
                    <button class="cancel-btn" onclick="cancelEdit()">Batal</button>
                `;
                list.appendChild(editDiv);
            } else {
                // Normal mode
                const div = document.createElement('div');
                div.className = 'todo-item' + (todo.done ? ' done' : '');
                div.innerHTML = `
                    <span style="flex:1;cursor:pointer;" onclick="toggleDone(${todo.id})">${escapeHtml(todo.text)}</span>
                    <span class="todo-actions">
                        <button class="edit-btn" title="Edit" onclick="startEdit(${todo.id}, event)">✏️</button>
                        <button title="Hapus" onclick="deleteTodo(${todo.id});event.stopPropagation();">🗑️</button>
                    </span>
                `;
                list.appendChild(div);
            }
        });
    }

    function addTodo(text) {
        fetch('<?= base_url('todolist/add') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'text=' + encodeURIComponent(text) + '&csrf_token=' + encodeURIComponent(getCsrf())
        })
        .then(res => res.json())
        .then(() => {
            editingId = null;
            fetchTodos();
        });
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

    function startEdit(id, event) {
        event.stopPropagation();
        editingId = id;
        fetchTodos();
    }

    function cancelEdit() {
        editingId = null;
        fetchTodos();
    }

    function submitEdit(id) {
        const input = document.getElementById('editInput');
        const text = input.value.trim();
        if (!text) return;
        fetch('<?= base_url('todolist/update') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id) + '&text=' + encodeURIComponent(text) + '&csrf_token=' + encodeURIComponent(getCsrf())
        })
        .then(res => res.json())
        .then(() => {
            editingId = null;
            fetchTodos();
        });
    }

    // Escape HTML untuk mencegah XSS
    function escapeHtml(text) {
        return text.replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
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
    window.startEdit = startEdit;
    window.cancelEdit = cancelEdit;
    window.submitEdit = submitEdit;

    // Init
    fetchTodos();
    </script>
</body>
</html>