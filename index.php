<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/app/routes/helper.php';
require_once __DIR__ . '/app/routes/web.php';
Route::dispatch();