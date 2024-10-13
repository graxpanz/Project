<?php
class Session
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function flash($type, $message)
    {
        self::start();
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public static function hasFlash()
    {
        return isset($_SESSION['flash_message']);
    }

    public static function getFlash()
    {
        self::start();
        if (self::hasFlash()) {
            $flash = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $flash;
        }
        return null;
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function remove($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_destroy();
    }
}