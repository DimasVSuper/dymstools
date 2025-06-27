<?php
/**
 * Routing utama aplikasi ProductivityApp.
 * Menangani autentikasi, registrasi, tools, ToDoList AJAX, dan fallback 404.
 *
 * @package ProductivityApp
 */

require_once __DIR__ . '/Route.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/../controller/LoginController.php';
require_once __DIR__ . '/../controller/TodolistController.php';
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../middleware/Middleware.php';

use App\Middleware\Middleware;

// ==================== AUTH & USER (GET/POST) ====================

/**
 * Halaman utama (login).
 */
Route::get('/', function() {
    Middleware::guestOnly();
    view('login');
});

/**
 * Halaman login.
 */
Route::get('/login', function() {
    Middleware::guestOnly();
    view('login');
});

/**
 * Proses login user (POST).
 */
Route::post('/login', function() {
    Middleware::guestOnly();
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
 * Proses logout user (POST).
 */
Route::post('/logout', function() {
    session_destroy();
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'redirect' => '/login']);
    exit;
});

/**
 * Halaman registrasi.
 */
Route::get('/register', function() {
    Middleware::guestOnly();
    view('registry');
});

/**
 * Proses registrasi user baru (POST).
 */
Route::post('/register', function() {
    Middleware::guestOnly();
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

// ==================== TOOLS & FITUR (GET) ====================

/**
 * Halaman home setelah login/tamu.
 * Tidak pakai Middleware::check() agar tamu bisa akses home.
 */
Route::get('/home', function() {
    view('home');
});

/**
 * Halaman ToDoList.
 */
Route::get('/todolist', function() {
    Middleware::check();
    view('todolist.todolist');
});

/**
 * Halaman Kalkulator.
 */
Route::get('/calculator', function() {
    Middleware::check();
    view('calculator.calculator');
});

/**
 * Halaman Timer.
 */
Route::get('/timer', function() {
    Middleware::check();
    view('Timer.Timer');
});

/**
 * Halaman QR Code Generator.
 */
Route::get('/qr', function() {
    Middleware::check();
    view('qr.qr');
});

/**
 * Halaman Unit Converter.
 */
Route::get('/unit', function(){
    Middleware::check();
    view('converter.unit');
});

// ==================== SECTION: TODOLIST AJAX (CRUD) ====================

/**
 * Ambil semua todo (AJAX GET).
 */
Route::get('/todolist/ajax', function() {
    Middleware::check();
    TodolistController::index();
});

/**
 * Tambah todo baru (AJAX POST).
 */
Route::post('/todolist/add', function() {
    Middleware::check();
    TodolistController::add();
});

/**
 * Toggle status done todo (AJAX POST).
 */
Route::post('/todolist/toggle', function() {
    Middleware::check();
    TodolistController::toggle();
});

/**
 * Hapus todo (AJAX POST).
 */
Route::post('/todolist/delete', function() {
    Middleware::check();
    TodolistController::delete();
});

// ==================== 404 (GET) ====================

/**
 * Tampilkan halaman 404 jika route tidak ditemukan.
 */
Route::get('/404', function() {
    http_response_code(404);
    view('404');
});