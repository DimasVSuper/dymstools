<?php
namespace App\Middleware;

/**
 * Middleware untuk keamanan dan validasi aplikasi DymsTools.
 * Menyediakan CSRF protection, rate limiting, dan sanitasi input.
 * 
 * @package DymsTools
 * @author DymsTools Team
 */
class Middleware
{
    /**
     * Generate CSRF token dan simpan di session.
     * 
     * @return string CSRF token (64 karakter hex)
     */
    public static function csrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifikasi CSRF token dari POST request.
     * Akan terminate script jika token tidak valid.
     * 
     * @return void
     * @throws void Menghentikan eksekusi dengan HTTP 403
     */
    public static function verifyCsrf(): void
    {
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $postToken = $_POST['csrf_token'] ?? '';
        
        if (empty($sessionToken) || empty($postToken) || !hash_equals($sessionToken, $postToken)) {
            self::sendErrorResponse(403, 'CSRF token tidak valid');
        }
    }

    /**
     * Rate limiting berdasarkan IP address.
     * Menggunakan session-based storage untuk tracking requests.
     * 
     * @param string $key         Identifier untuk endpoint yang di-throttle
     * @param int    $maxRequest  Maximum requests yang diizinkan (default: 30)
     * @param int    $seconds     Durasi window dalam detik (default: 60)
     * @return void
     * @throws void Menghentikan eksekusi dengan HTTP 429 jika melebihi limit
     */
    public static function throttle(string $key, int $maxRequest = 30, int $seconds = 60): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $cacheKey = "throttle_{$key}_{$ip}";
        
        // Initialize counter untuk IP baru
        if (!isset($_SESSION[$cacheKey])) {
            $_SESSION[$cacheKey] = [
                'count' => 1,
                'reset_time' => time() + $seconds
            ];
            return;
        }
        
        $data = $_SESSION[$cacheKey];
        
        // Reset counter jika window sudah expired
        if (time() > $data['reset_time']) {
            $_SESSION[$cacheKey] = [
                'count' => 1,
                'reset_time' => time() + $seconds
            ];
            return;
        }
        
        // Increment request counter
        $_SESSION[$cacheKey]['count']++;
        
        // Block jika melebihi rate limit
        if ($_SESSION[$cacheKey]['count'] > $maxRequest) {
            $remainingTime = $data['reset_time'] - time();
            self::sendErrorResponse(
                429, 
                "Rate limit exceeded. Try again in {$remainingTime} seconds."
            );
        }
    }

    // ==================== INPUT SANITIZATION ====================

    /**
     * Sanitize string input dengan menghapus HTML tags dan trim whitespace.
     * 
     * @param mixed $input Input yang akan disanitasi
     * @return string|mixed String yang sudah disanitasi atau input asli jika bukan string
     */
    public static function sanitizeString($input)
    {
        if (!is_string($input)) {
            return $input;
        }
        
        return trim(strip_tags($input));
    }

    /**
     * Sanitize dan convert input menjadi number (int atau float).
     * 
     * @param mixed $input Input yang akan dikonversi
     * @return int|float|null Number hasil konversi atau null jika tidak valid
     */
    public static function sanitizeNumber($input)
    {
        if (!is_numeric($input)) {
            return null;
        }
        
        // Return float jika ada desimal, int jika tidak
        return is_float($input + 0) ? (float)$input : (int)$input;
    }

    /**
     * Limit panjang string ke maksimum karakter tertentu.
     * 
     * @param string $text      Text yang akan dipotong
     * @param int    $maxLength Panjang maksimum (default: 255)
     * @return string Text yang sudah dipotong jika perlu
     */
    public static function limitLength(string $text, int $maxLength = 255): string
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        
        return substr($text, 0, $maxLength);
    }

    /**
     * Validasi bahwa input tidak kosong setelah di-trim.
     * 
     * @param mixed $input Input yang akan divalidasi
     * @return bool True jika tidak kosong, false jika kosong
     */
    public static function notEmpty($input): bool
    {
        if (!is_string($input)) {
            return !empty($input);
        }
        
        return !empty(trim($input));
    }

    // ==================== HELPER METHODS ====================

    /**
     * Send JSON error response dan terminate script.
     * 
     * @param int    $httpCode HTTP status code
     * @param string $message  Error message
     * @return void
     */
    private static function sendErrorResponse(int $httpCode, string $message): void
    {
        // Clear any existing output
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Set response headers
        if (!headers_sent()) {
            http_response_code($httpCode);
            header('Content-Type: application/json');
        }
        
        // Send JSON response
        echo json_encode([
            'error' => $message,
            'code' => $httpCode,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        
        exit;
    }

    /**
     * Validasi multiple inputs sekaligus.
     * 
     * @param array $rules Array dengan format ['field' => 'validation_rule']
     * @param array $data  Data yang akan divalidasi
     * @return array Array dengan hasil validasi
     */
    public static function validateInputs(array $rules, array $data): array
    {
        $errors = [];
        $sanitized = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            switch ($rule) {
                case 'required':
                    if (!self::notEmpty($value)) {
                        $errors[$field] = "{$field} tidak boleh kosong";
                    } else {
                        $sanitized[$field] = self::sanitizeString($value);
                    }
                    break;
                    
                case 'number':
                    $sanitized[$field] = self::sanitizeNumber($value);
                    if ($sanitized[$field] === null && $value !== null) {
                        $errors[$field] = "{$field} harus berupa angka";
                    }
                    break;
                    
                case 'string':
                    $sanitized[$field] = self::sanitizeString($value);
                    break;
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $sanitized
        ];
    }
}