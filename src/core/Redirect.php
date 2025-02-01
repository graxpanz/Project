<?php
class Redirect {
    public static function to($url) {
        header("Location: $url");
        exit();
    }

    public static function back() {
        $previous = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        self::to($previous);
    }

    public static function with($type, $message) {
        Session::flash($type, $message);
        return new static();
    }
}

function redirect($url = null) {
    if ($url === null) {
        return new Redirect();
    }
    Redirect::to($url);
}