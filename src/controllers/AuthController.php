<?php
class AuthController extends Controller {
    public function login() {
        if (isset($_SESSION['AD_ID'])) {
            redirect('/user'); 
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // POST
            $username = $_POST['username'];
            $password =$_POST['password'];
            $userModel = $this->model('User');
            $user = $userModel->findByUsername($username);

            if ($user && (password_verify($password, $user['password']))) {
                $_SESSION['AD_ID'] = $user['user_id'];
                $_SESSION['AD_USERNAME'] = $user['username'];
                $_SESSION['AD_FIRSTNAME'] = $user['firstname'];
                $_SESSION['AD_LASTNAME'] = $user['lastname'];
                $_SESSION['AD_IMAGE'] = $user['image'];
                $_SESSION['AD_LOGIN'] = date('Y-m-d H:i:s');
                $_SESSION['AD_PERMISSION'] = $user['permission'];
                $userModel->updateLastLogin($user['user_id']);
                redirect('/user');
            } else {
                $data['error'] = 'รหัสผ่านไม่ถูกต้องหรือชื่อผู้ใช้ไม่ถูกต้อง';
                $this->view('auth/login', $data, false);
            }
        } else {
            // GET
            $this->view('auth/login', [], FALSE);
        }
    }

    public function logout() {
        session_destroy();
        redirect('/login'); 
    }
}