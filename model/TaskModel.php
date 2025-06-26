<?php

/**
 * Model dummy untuk Todolist pada ProductivityApp.
 * Menyediakan data dan operasi dasar todolist menggunakan session (tanpa database).
 *
 * @package ProductivityApp
 */
class TodolistModel
{
    /**
     * Ambil semua todolist milik user tertentu.
     *
     * @param string $username Username pemilik todolist
     * @return array Daftar todolist
     */
    public static function all($username)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['todolists'][$username] ?? [];
    }

    /**
     * Tambah todolist baru untuk user.
     *
     * @param string $username Username pemilik todolist
     * @param string $title Judul todolist
     * @return array Todolist yang baru ditambahkan
     */
    public static function add($username, $title)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $newTodolist = [
            'id' => uniqid(),
            'title' => $title,
            'created_at' => date('Y-m-d H:i:s'),
            'done' => false
        ];
        $_SESSION['todolists'][$username][] = $newTodolist;
        return $newTodolist;
    }

    /**
     * Tandai todolist sebagai selesai.
     *
     * @param string $username Username pemilik todolist
     * @param string $id ID todolist
     * @return bool True jika berhasil, false jika tidak ditemukan
     */
    public static function markDone($username, $id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['todolists'][$username])) return false;
        foreach ($_SESSION['todolists'][$username] as &$todo) {
            if ($todo['id'] === $id) {
                $todo['done'] = true;
                return true;
            }
        }
        return false;
    }

    /**
     * Hapus todolist berdasarkan ID.
     *
     * @param string $username Username pemilik todolist
     * @param string $id ID todolist
     * @return bool True jika berhasil dihapus, false jika tidak ditemukan
     */
    public static function delete($username, $id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['todolists'][$username])) return false;
        foreach ($_SESSION['todolists'][$username] as $i => $todo) {
            if ($todo['id'] === $id) {
                array_splice($_SESSION['todolists'][$username], $i, 1);
                return true;
            }
        }
        return false;
    }
}