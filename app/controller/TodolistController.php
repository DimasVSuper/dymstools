<?php
require_once __DIR__ . '/../model/TodolistModel.php';
require_once __DIR__ . '/../middleware/Middleware.php';

use App\Middleware\Middleware;

/**
 * Controller ToDoList untuk DymsTools.
 * Handle AJAX request untuk CRUD ToDoList (session based).
 */
class TodolistController
{
    /**
     * Ambil semua todo (GET).
     */
    public static function index()
    {
        Middleware::throttle('todolist_get', 30, 60); // Maks 30 request/menit per IP
        header('Content-Type: application/json');
        echo json_encode(TodolistModel::getAll());
        exit;
    }

    /**
     * Tambah todo baru (POST).
     */
    public static function add()
    {
        Middleware::throttle('todolist_add', 10, 60); // Maks 10 request/menit per IP
        Middleware::verifyCsrf();
        $text = Middleware::sanitizeString($_POST['text'] ?? '');
        $text = Middleware::limitLength($text, 60);
        if (!Middleware::notEmpty($text)) {
            http_response_code(400);
            echo json_encode(['error' => 'Teks tidak boleh kosong']);
            exit;
        }
        $todo = TodolistModel::add($text);
        header('Content-Type: application/json');
        echo json_encode($todo);
        exit;
    }

    /**
     * Toggle status done (POST).
     */
    public static function toggle()
    {
        Middleware::throttle('todolist_toggle', 20, 60); // Maks 20 request/menit per IP
        Middleware::verifyCsrf();
        $id = Middleware::sanitizeNumber($_POST['id'] ?? 0);
        $success = TodolistModel::toggle($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    /**
     * Hapus todo (POST).
     */
    public static function delete()
    {
        Middleware::throttle('todolist_delete', 10, 60); // Maks 10 request/menit per IP
        Middleware::verifyCsrf();
        $id = Middleware::sanitizeNumber($_POST['id'] ?? 0);
        $success = TodolistModel::delete($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    /**
     * Update todo (POST).
     */
    public static function update()
    {
        Middleware::throttle('todolist_update', 10, 60); // Maks 10 request/menit per IP
        Middleware::verifyCsrf();
        $id = Middleware::sanitizeNumber($_POST['id'] ?? 0);
        $text = Middleware::sanitizeString($_POST['text'] ?? '');
        $text = Middleware::limitLength($text, 60);
        if (!Middleware::notEmpty($text)) {
            http_response_code(400);
            echo json_encode(['error' => 'Teks tidak boleh kosong']);
            exit;
        }
        $success = TodolistModel::update($id, ['text' => $text]);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}