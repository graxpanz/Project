<?php
class AuthController extends Controller {
    public function login() {
        if (isset($_SESSION['AD_ID'])) {
            redirect('/dashboard'); 
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // POST
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->findByUsername($username);

            if ($user && $password == $user['password']) {
                $_SESSION['AD_ID'] = $user['u_id'];
                $_SESSION['AD_FIRSTNAME'] = $user['firstname'];
                $_SESSION['AD_LASTNAME'] = $user['lastname'];
                $_SESSION['AD_USERNAME'] = $user['username'];
                $_SESSION['AD_IMAGE'] = $user['image'];
                $_SESSION['AD_STATUS'] = $user['status'];
                $_SESSION['AD_LOGIN'] = date('Y-m-d H:i:s');

                redirect('/dashboard');
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