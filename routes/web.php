<?php
/**
 * Routing utama aplikasi ProductivityApp.
 * Menangani autentikasi, registrasi, logout, dan fallback 404.
 *
 * @package ProductivityApp
 */

require_once __DIR__ . '/Route.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/../controller/LoginController.php';
require_once __DIR__ . '/../model/UserModel.php';

// ==================== AUTH ====================

/**
 * Tampilkan halaman login sebagai halaman utama.
 */
Route::get('/', function() {
    view('login');
});

/**
 * Tampilkan halaman login.
 */
Route::get('/login', function() {
    view('login');
});

/**
 * Proses login user.
 * - Jika AJAX: balas JSON.
 * - Jika non-AJAX: redirect atau tampilkan error.
 */
Route::post('/login', function() {
    $isAjax = (
        (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
    );
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $result = LoginController::login($username, $password);

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    } else {
        if ($result['success']) {
            header('Location: ' . $result['redirect']);
            exit;
        } else {
            view('login', ['error' => $result['error']]);
        }
    }
});

/**
 * Tampilkan halaman home setelah login.
 */
Route::get('/home', function() {
    view('home');
});

/**
 * Proses logout user.
 * - Hapus session.
 * - Balas JSON untuk AJAX.
 */
Route::post('/logout', function() {
    session_destroy();
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'redirect' => '/login']);
    exit;
});

// ==================== REGISTER ====================

/**
 * Tampilkan halaman registrasi.
 */
Route::get('/register', function() {
    view('registry');
});

/**
 * Proses registrasi user baru (dummy, simpan ke session).
 * - Validasi konfirmasi password dan username unik.
 * - Jika AJAX: balas JSON.
 * - Jika non-AJAX: tampilkan pesan.
 */
Route::post('/register', function() {
    $isAjax = (
        (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
    );
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi konfirmasi password
    if ($password !== $password_confirm) {
        $error = 'Konfirmasi password tidak cocok';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $error]);
        } else {
            view('registry', ['error' => $error]);
        }
        return;
    }

    // Validasi username unik
    if (UserModel::findByUsername($username)) {
        $error = 'Username sudah terdaftar';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $error]);
        } else {
            view('registry', ['error' => $error]);
        }
        return;
    }

    // Simpan user baru ke session (dummy)
    $_SESSION['registered_users'][] = [
        'id' => rand(100,999),
        'username' => $username,
        'password' => $password,
        'nama' => $username,
        'created_at' => date('Y-m-d H:i:s')
    ];
    $success = 'Registrasi berhasil, silakan login!';
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'redirect' => '/login']);
    } else {
        view('registry', ['success' => $success]);
    }
});

// ==================== 404 FALLBACK ====================

/**
 * Tampilkan halaman 404 jika route tidak ditemukan.
 */
Route::get('/404', function() {
    view('404');
});