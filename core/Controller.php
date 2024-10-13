<?php
class Controller {
    public function model($model) {
        require_once '../models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = [], $layout = TRUE) {
        $viewPath = "../views/{$view}.php";
        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            require_once $viewPath;
            if ($layout) {
                $content = ob_get_clean();
                require_once "../views/layouts/main.php";
            }
        } else {
            throw new Exception("View file not found: {$viewPath}");
        }
    }
}