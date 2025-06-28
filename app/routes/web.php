<?php
/**
 * Routing utama aplikasi ProductivityApp tanpa login/register.
 * Semua fitur dapat diakses tanpa autentikasi.
 */

require_once __DIR__ . '/Route.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/../controller/TodolistController.php';
require_once __DIR__ . '/../controller/NoteController.php';

// ==================== HALAMAN UTAMA & FITUR ====================

/**
 * Halaman utama (home).
 */
Route::get('/', function() {
    view('home');
});
Route::get('/home', function() {
    view('home');
});

/**
 * Halaman ToDoList.
 */
Route::get('/todolist', function() {
    view('todolist.todolist');
});

/**
 * Halaman Kalkulator.
 */
Route::get('/calculator', function() {
    view('calculator.calculator');
});

/**
 * Halaman Timer.
 */
Route::get('/timer', function() {
    view('Timer.Timer');
});

/**
 * Halaman QR Code Generator.
 */
Route::get('/qr', function() {
    view('qr.qr');
});

/**
 * Halaman Unit Converter.
 */
Route::get('/unit', function(){
    view('converter.unit');
});

// ==================== SECTION: TODOLIST AJAX (CRUD) ====================

/**
 * Ambil semua todo (AJAX GET).
 */
Route::get('/todolist/ajax', function() {
    TodolistController::index();
});

/**
 * Tambah todo baru (AJAX POST).
 */
Route::post('/todolist/add', function() {
    TodolistController::add();
});

/**
 * Toggle status done todo (AJAX POST).
 */
Route::post('/todolist/toggle', function() {
    TodolistController::toggle();
});

/**
 * Hapus todo (AJAX POST).
 */
Route::post('/todolist/delete', function() {
    TodolistController::delete();
});

/**
 * Update todo (AJAX POST).
 */
Route::post('/todolist/update', function() {
    TodolistController::update();
});

// ==================== SECTION: NOTE PUBLIK ====================

/**
 * Halaman Note Publik.
 */
Route::get('/note', function() {
    view('note.note');
});

/**
 * Ambil semua note (AJAX GET).
 */
Route::get('/note/ajax', function() {
    NoteController::index();
});

/**
 * Tambah note baru (AJAX POST).
 */
Route::post('/note/add', function() {
    NoteController::add();
});

/**
 * Hapus note (AJAX POST).
 */
Route::post('/note/delete', function() {
    NoteController::delete();
});

// ==================== 404 (GET) ====================

/**
 * Tampilkan halaman 404 jika route tidak ditemukan.
 */
Route::get('/404', function() {
    http_response_code(404);
    view('404');
});