<?php
namespace App\Middleware;

class Middleware
{
    /**
     * Generate dan ambil CSRF token untuk form.
     * @return string
     */
    public static function csrfToken()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validasi CSRF token dari request POST.
     * Jika tidak valid, hentikan eksekusi.
     */
    public static function verifyCsrf()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $token = $_POST['csrf_token'] ?? '';
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(419);
            exit('CSRF token tidak valid.');
        }
    }

    /**
     * Throttle: Batasi jumlah request per IP per endpoint.
     * @param string $key Kunci unik (misal: nama endpoint)
     * @param int $maxRequest Maksimal request
     * @param int $seconds Jangka waktu dalam detik
     */
    public static function throttle($key, $maxRequest = 30, $seconds = 60)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $throttleKey = "throttle_{$key}_{$ip}";
        $now = time();

        if (!isset($_SESSION[$throttleKey])) {
            $_SESSION[$throttleKey] = [
                'count' => 1,
                'start' => $now
            ];
        } else {
            $data = &$_SESSION[$throttleKey];
            if ($now - $data['start'] > $seconds) {
                // Reset window
                $data['count'] = 1;
                $data['start'] = $now;
            } else {
                $data['count']++;
                if ($data['count'] > $maxRequest) {
                    http_response_code(429);
                    exit('Terlalu banyak request. Silakan coba lagi nanti.');
                }
            }
        }
    }

    /**
     * Sanitasi input string (hapus tag HTML, trim, dsb).
     * @param string $input
     * @return string
     */
    public static function sanitizeString($input)
    {
        return trim(strip_tags($input));
    }

    /**
     * Validasi input angka (float/int).
     * @param mixed $input
     * @return float|null
     */
    public static function sanitizeNumber($input)
    {
        if (is_numeric($input)) {
            return $input + 0;
        }
        return null;
    }

    /**
     * Validasi panjang string.
     * @param string $input
     * @param int $max
     * @return string
     */
    public static function limitLength($input, $max = 255)
    {
        return mb_substr($input, 0, $max);
    }

    /**
     * Validasi input tidak boleh kosong.
     * @param string $input
     * @return bool
     */
    public static function notEmpty($input)
    {
        return strlen(trim($input)) > 0;
    }
}