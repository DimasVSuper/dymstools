<?php
require_once __DIR__ . '/../model/NoteModel.php';

class NoteController
{
    public static function index()
    {
        header('Content-Type: application/json');
        echo json_encode(NoteModel::getAll());
        exit;
    }

    public static function add()
    {
        \App\Middleware\Middleware::throttle('note_add', 10, 60); // Maks 10 request/menit per IP

        $text = trim(strip_tags($_POST['text'] ?? ''));
        if (!$text) {
            http_response_code(400);
            echo json_encode(['error' => 'Teks tidak boleh kosong']);
            exit;
        }
        $note = NoteModel::add($text);
        header('Content-Type: application/json');
        echo json_encode($note);
        exit;
    }

    public static function delete()
    {
        \App\Middleware\Middleware::throttle('note_delete', 10, 60);

        $id = intval($_POST['id'] ?? 0);
        NoteModel::delete($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}