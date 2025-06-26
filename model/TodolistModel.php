<?php

/**
 * Model user dummy untuk ProductivityApp.
 * Menyediakan data user statis dan fitur register user baru ke session (tanpa database).
 *
 * @package ProductivityApp
 */
class UserModel
{
    /**
     * @var array $users
     * Data user dummy (bisa diganti database nanti).
     */
    private static $users = [
        [
            'id' => 1,
            'username' => 'dimas',
            'password' => 'password123', // plain, untuk dummy saja!
            'nama' => 'Dimas Pratama',
            'created_at' => '2025-06-23 08:00:00'
        ],
        [
            'id' => 2,
            'username' => 'admin',
            'password' => 'admin', // plain, untuk dummy saja!
            'nama' => 'Administrator',
            'created_at' => '2025-06-23 08:30:00'
        ],
    ];

    /**
     * Ambil semua user (dummy + yang didaftarkan via session).
     *
     * @return array
     */
    public static function all()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return array_merge(self::$users, $_SESSION['registered_users'] ?? []);
    }

    /**
     * Cari user berdasarkan username (cek dummy + session).
     *
     * @param string $username
     * @return array|null
     */
    public static function findByUsername($username)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $allUsers = array_merge(self::$users, $_SESSION['registered_users'] ?? []);
        foreach ($allUsers as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }
        return null;
    }

    /**
     * Tambah user baru ke session (dummy register).
     *
     * @param string $username
     * @param string $password
     * @param string $nama
     * @return array User baru yang ditambahkan
     */
    public static function addUser($username, $password, $nama)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $users = array_merge(self::$users, $_SESSION['registered_users'] ?? []);
        $newUser = [
            'id' => count($users) + 1,
            'username' => $username,
            'password' => $password, // plain, untuk dummy saja!
            'nama' => $nama,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $_SESSION['registered_users'][] = $newUser;
        return $newUser;
    }
}