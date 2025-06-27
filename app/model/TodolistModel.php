<?php

/**
 * Model ToDoList dummy untuk ProductivityApp.
 * Menyediakan data to-do statis dan fitur CRUD ke session (tanpa database).
 *
 * @package ProductivityApp
 */
class TodolistModel
{
    /**
     * Ambil semua todo dari session.
     * @return array
     */
    public static function all()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Data dummy awal jika belum ada di session
        if (!isset($_SESSION['todos'])) {
            $_SESSION['todos'] = [
                [
                    'id' => 1,
                    'text' => 'Belajar PHP',
                    'done' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'text' => 'Ngopi dulu ☕',
                    'done' => true,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ];
        }
        return $_SESSION['todos'];
    }

    /**
     * Tambah todo baru ke session.
     * @param string $text
     * @return array Todo baru
     */
    public static function add($text)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $todos = self::all();
        $newId = count($todos) > 0 ? max(array_column($todos, 'id')) + 1 : 1;
        $todo = [
            'id' => $newId,
            'text' => $text,
            'done' => false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        array_unshift($_SESSION['todos'], $todo);
        return $todo;
    }

    /**
     * Toggle status done todo.
     * @param int $id
     * @return bool
     */
    public static function toggle($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        foreach ($_SESSION['todos'] as &$todo) {
            if ($todo['id'] == $id) {
                $todo['done'] = !$todo['done'];
                return true;
            }
        }
        return false;
    }

    /**
     * Hapus todo berdasarkan id.
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        foreach ($_SESSION['todos'] as $i => $todo) {
            if ($todo['id'] == $id) {
                array_splice($_SESSION['todos'], $i, 1);
                return true;
            }
        }
        return false;
    }
}