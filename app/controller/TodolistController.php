<?php
require_once __DIR__ . '/../model/TodolistModel.php';
require_once __DIR__ . '/../middleware/Middleware.php';

use App\Middleware\Middleware;

/**
 * Controller ToDoList untuk ProductivityApp.
 * Handle AJAX request untuk CRUD ToDoList (session based).
 */
class TodolistController
{
    /**
     * Ambil semua todo (GET).
     */
    public static function index()
    {
        header('Content-Type: application/json');
        echo json_encode(TodolistModel::all());
        exit;
    }

    /**
     * Tambah todo baru (POST).
     */
    public static function add()
    {
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
        Middleware::verifyCsrf();
        $id = Middleware::sanitizeNumber($_POST['id'] ?? 0);
        $success = TodolistModel::delete($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}