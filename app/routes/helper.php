<?php
/**
 * Helper function untuk aplikasi ProductivityApp.
 * 
 * @package ProductivityApp
 */

/**
 * Render file view PHP dengan data yang diberikan.
 *
 * @param string $name Nama view, gunakan titik untuk nested folder (misal: 'todolist.index')
 * @param array  $data Data asosiatif yang akan diekstrak ke view
 * @throws Exception Jika file view tidak ditemukan
 * @return void
 */
function view($name, $data = []) {
    $viewFile = __DIR__ . '/../view/' . str_replace('.', '/', $name) . '.view.php';
    if (!file_exists($viewFile)) {
        throw new Exception("View file not found: $viewFile");
    }
    extract($data);
    include $viewFile;
}
/**
 * Helper function untuk mendapatkan URL dasar aplikasi.
 *
 * @param string $path Path tambahan yang ingin ditambahkan ke URL dasar
 * @return string URL dasar aplikasi dengan path tambahan
 */
function base_url($path = '') {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if ($base === '/' || $base === '\\') $base = '';
    return $base . '/' . ltrim($path, '/');
}