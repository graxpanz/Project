<?php
class Controller {
    protected $allowedRoutes = ['login', 'logout', 'register', 'forgot-password', 'assets']; // Add any public routes here

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $currentRoute = $this->getCurrentRoute();
        if (!in_array($currentRoute, $this->allowedRoutes)) {
            if (!isset($_SESSION['AD_ID']) || empty($_SESSION['AD_ID'])) {
                redirect('/login'); 
                exit;
            } else {
                $session = explode(", " , $_SESSION['AD_PERMISSION']);
                if (!in_array($currentRoute, $session)) {
                    $this->view('errors/404', ["url" => "/" . $session[0]], FALSE);
                    exit;
                }
            }
        }
    }

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

    public function dateFormat($date) {
        return date('d/m/Y H:i:s', strtotime($date));
    }

    /**
     * Render error page
     * @param string $error Error page name (e.g., '404', '500')
     * @return void
     */
    protected function renderError($error) {
        $errorPath = "../views/errors/{$error}.php";
        if (file_exists($errorPath)) {
            require_once $errorPath;
        } else {
            echo "Error page not found: {$error}";
        }
    }

    /**
     * Get current route from URL
     * @return string
     */
    protected function getCurrentRoute() {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        return !empty($segments[0]) ? $segments[0] : 'login';
    }
}