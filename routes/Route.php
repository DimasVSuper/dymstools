<?php

require_once __DIR__ . '/Router.php';

/**
 * Facade statis untuk Router.
 * Memudahkan pendaftaran dan dispatch route tanpa harus membuat instance Router manual.
 *
 * @package ProductivityApp
 */
class Route
{
    /**
     * @var Router $router
     * Instance router utama (singleton).
     */
    private static $router;

    /**
     * Inisialisasi router jika belum ada.
     *
     * @return void
     */
    public static function init()
    {
        if (!self::$router) {
            self::$router = new Router();
        }
    }

    /**
     * Daftarkan route GET.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler untuk route
     * @return void
     */
    public static function get($path, $handler)
    {
        self::init();
        self::$router->get($path, $handler);
    }

    /**
     * Daftarkan route POST.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler untuk route
     * @return void
     */
    public static function post($path, $handler)
    {
        self::init();
        self::$router->post($path, $handler);
    }

    /**
     * Daftarkan route PUT.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler untuk route
     * @return void
     */
    public static function put($path, $handler)
    {
        self::init();
        self::$router->put($path, $handler);
    }

    /**
     * Daftarkan route PATCH.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler untuk route
     * @return void
     */
    public static function patch($path, $handler)
    {
        self::init();
        self::$router->patch($path, $handler);
    }

    /**
     * Daftarkan route DELETE.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler untuk route
     * @return void
     */
    public static function delete($path, $handler)
    {
        self::init();
        self::$router->delete($path, $handler);
    }

    /**
     * Dispatch request saat ini ke handler yang sesuai.
     *
     * @return mixed Hasil dari handler jika ditemukan, atau output 404 jika tidak.
     */
    public static function dispatch()
    {
        self::init();
        self::$router->dispatch();
    }
}