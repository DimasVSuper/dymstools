<
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Note Publik | DymsTools</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background: #f8fafc; margin: 0; }
        .note-container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(34,34,59,0.07); padding: 32px 20px; }
        .note-title { font-size: 2rem; color: #3a86ff; font-weight: 700; margin-bottom: 12px; }
        .note-form { display: flex; gap: 8px; margin-bottom: 18px; }
        .note-form input { flex: 1; padding: 10px; border-radius: 6px; border: 1px solid #e0e0e0; font-size: 1rem; }
        .note-form button { padding: 10px 20px; border-radius: 6px; border: none; background: #3a86ff; color: #fff; font-weight: 600; cursor: pointer; }
        .note-list {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            justify-content: flex-start;
        }
        .note-item {
            background: #fffbe7;
            border-radius: 12px 28px 12px 28px;
            box-shadow: 0 2px 10px rgba(67,97,238,0.10), 0 1.5px 0 #f7d774 inset;
            padding: 18px 16px 14px 16px;
            min-width: 180px;
            max-width: 220px;
            min-height: 80px;
            position: relative;
            display: flex;
            flex-direction: column;
            word-break: break-word;
            transition: box-shadow 0.2s;
            border: 1.5px solid #f7d774;
        }
        .note-item:before {
            content: "";
            display: block;
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 18px;
            background: repeating-linear-gradient(
                135deg,
                #f7d774,
                #f7d774 4px,
                #fffbe7 4px,
                #fffbe7 8px
            );
            border-radius: 12px 28px 0 0;
        }
        .note-text {
            flex: 1;
            margin-bottom: 10px;
            font-size: 1.08em;
            color: #22223b;
        }
        .note-time {
            color: #bfa900;
            font-size: 0.93em;
            margin-bottom: 4px;
            text-align: right;
        }
        .note-delete {
            background: none;
            border: none;
            color: #e63946;
            font-size: 1.2em;
            cursor: pointer;
            position: absolute;
            top: 7px;
            right: 10px;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .note-delete:hover { opacity: 1; }
        .note-empty { color: #b0b0b0; font-style: italic; text-align: center; margin-top: 24px; width: 100%; }
        .note-home { display: inline-block; margin-top: 18px; background: #4361ee; color: #fff; padding: 10px 22px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: background 0.2s; }
        .note-home:active { background: #22223b; }
    </style>
</head>
<body>
    <div class="note-container">
        <div class="note-title">Note Publik</div>
        <form class="note-form" id="noteForm" autocomplete="off">
            <input type="text" id="noteInput" placeholder="Tulis catatan..." maxlength="200" required>
            <button type="submit">Tambah</button>
        </form>
        <div class="note-list" id="noteList"></div>
        <a href="<?= base_url('home') ?>" class="note-home">← Balik ke Home</a>
    </div>
    <script>
    function fetchNotes() {
        fetch('<?= base_url('note/ajax') ?>')
            .then(res => res.json())
            .then(renderNotes);
    }
    function renderNotes(notes) {
        const list = document.getElementById('noteList');
        if (!notes || notes.length === 0) {
            list.innerHTML = '<div class="note-empty">Belum ada catatan.</div>';
            return;
        }
        list.innerHTML = '';
        notes.forEach(note => {
            const div = document.createElement('div');
            div.className = 'note-item';
            div.innerHTML = `
                <button class="note-delete" onclick="deleteNote(${note.id})" title="Hapus">🗑️</button>
                <div class="note-text">${escapeHtml(note.text)}</div>
                <div class="note-time">${formatTime(note.created_at)}</div>
            `;
            list.appendChild(div);
        });
    }
    function addNote(text) {
        fetch('<?= base_url('note/add') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'text=' + encodeURIComponent(text)
        })
        .then(res => res.json())
        .then(() => fetchNotes());
    }
    function deleteNote(id) {
        fetch('<?= base_url('note/delete') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(() => fetchNotes());
    }
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
    function formatTime(dt) {
        if (!dt) return '';
        // Mendukung format waktu dari file JSON (Y-m-d H:i:s)
        const d = new Date(dt.replace(' ', 'T'));
        if (isNaN(d.getTime())) return dt;
        return d.toLocaleString('id-ID', { dateStyle: 'short', timeStyle: 'short' });
    }
    document.getElementById('noteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('noteInput');
        const text = input.value.trim();
        if (!text) return;
        addNote(text);
        input.value = '';
    });
    window.deleteNote = deleteNote;
    fetchNotes();
    </script>
</body>
</html>