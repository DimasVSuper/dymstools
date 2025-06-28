<?php

/**
 * Model ToDoList dummy untuk DymsTools.
 * Menyediakan fitur CRUD ke session (tanpa database).
 *
 * @package DymsTools
 */
class TodolistModel
{
    /**
     * Ambil semua todo dari session.
     * @return array
     */
    public static function getAll()
    {
        if (!isset($_SESSION['todos'])) {
            $_SESSION['todos'] = [];
        }
        return $_SESSION['todos'];
    }

    /**
     * Ambil satu todo berdasarkan id.
     * @param int $id
     * @return array|null
     */
    public static function get($id)
    {
        $todos = self::getAll();
        foreach ($todos as $todo) {
            if ($todo['id'] == $id) {
                return $todo;
            }
        }
        return null;
    }

    /**
     * Tambah todo baru ke session.
     * @param string $text
     * @return array Todo baru
     */
    public static function add($text)
    {
        $todos = self::getAll();
        $newId = count($todos) > 0 ? max(array_column($todos, 'id')) + 1 : 1;
        $todo = [
            'id' => $newId,
            'text' => $text,
            'done' => false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        array_unshift($todos, $todo);
        $_SESSION['todos'] = $todos;
        return $todo;
    }

    /**
     * Update todo berdasarkan id.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $todos = self::getAll();
        foreach ($todos as $i => $todo) {
            if ($todo['id'] == $id) {
                $todos[$i] = array_merge($todo, $data);
                $_SESSION['todos'] = $todos;
                return true;
            }
        }
        return false;
    }

    /**
     * Toggle status done todo.
     * @param int $id
     * @return bool
     */
    public static function toggle($id)
    {
        $todos = self::getAll();
        foreach ($todos as $i => $todo) {
            if ($todo['id'] == $id) {
                $todos[$i]['done'] = !$todo['done'];
                $_SESSION['todos'] = $todos;
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
        $todos = self::getAll();
        foreach ($todos as $i => $todo) {
            if ($todo['id'] == $id) {
                array_splice($todos, $i, 1);
                $_SESSION['todos'] = $todos;
                return true;
            }
        }
        return false;
    }

    /**
     * Hapus semua todo.
     */
    public static function clear()
    {
        $_SESSION['todos'] = [];
    }
}