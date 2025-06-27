<?php
/**
 * Router sederhana untuk menangani HTTP request pada aplikasi.
 * Mendukung method GET, POST, PUT, PATCH, DELETE.
 * 
 * @package ProductivityApp
 */
class Router
{
    /**
     * @var array $routes
     * Menyimpan daftar route berdasarkan HTTP method.
     * Contoh: $routes['GET']['/home'] = callable
     */
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    /**
     * Daftarkan route GET.
     *
     * @param string   $path    Path/URI yang ingin ditangani (misal: '/home')
     * @param callable $handler Fungsi handler yang akan dijalankan jika route cocok
     * @return void
     */
    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Daftarkan route POST.
     *
     * @param string   $path    Path/URI yang ingin ditangani (misal: '/login')
     * @param callable $handler Fungsi handler yang akan dijalankan jika route cocok
     * @return void
     */
    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Daftarkan route PUT.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler yang akan dijalankan jika route cocok
     * @return void
     */
    public function put($path, $handler)
    {
        $this->routes['PUT'][$path] = $handler;
    }

    /**
     * Daftarkan route PATCH.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler yang akan dijalankan jika route cocok
     * @return void
     */
    public function patch($path, $handler)
    {
        $this->routes['PATCH'][$path] = $handler;
    }

    /**
     * Daftarkan route DELETE.
     *
     * @param string   $path    Path/URI yang ingin ditangani
     * @param callable $handler Fungsi handler yang akan dijalankan jika route cocok
     * @return void
     */
    public function delete($path, $handler)
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    /**
     * Dispatch request saat ini ke handler yang sesuai.
     * Jika tidak ditemukan, tampilkan halaman 404.
     *
     * @return mixed Hasil dari handler jika ditemukan, atau output 404 jika tidak.
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Hilangkan base path jika aplikasi di subfolder
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        if ($uri === '') $uri = '/';

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            if (is_callable($handler)) {
                return $handler();
            }
        }
        // 404 jika route tidak ditemukan
        http_response_code(404);
        if (function_exists('view')) {
            view('404');
        } else {
            echo "404 Not Found";
        }
    }
}