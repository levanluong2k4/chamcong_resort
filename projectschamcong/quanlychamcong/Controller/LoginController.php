<?php 
require_once __DIR__ . '/../Model/emloyee/ModelSign_in.php';

class LoginController {
    
    public function index() {
     
        if(isset($_SESSION['user_id'])) {
            header('Location: /thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=home&action=index');
            exit();
        }
        
      
        if(isset($_COOKIE['remember_token']) && !empty($_COOKIE['remember_token'])) {
            $modelSign_in = new ModelSign_in();
            
           
            if($modelSign_in->checkRememberToken($_COOKIE['remember_token'])) {
               
                header('Location: /thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=home&action=index');
                exit();
            } else {
                
                $this->clearRememberCookie();
            }
        }
        
        // Hiển thị trang đăng nhập
        require_once __DIR__ . '/../View/sign_in_up/sign_in.php';
    }
    
    public function sign_in() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            require_once __DIR__ . '/../Model/json/ResponseHelper.php';
            ResponseHelper::error('Method Not Allowed', 'method_error', 405);
        }
        
        $modelSign_in = new ModelSign_in();
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']) ? true : false;
        
        $modelSign_in->sign_in($email, $password, $remember);
    }
    
  
    public function sign_out() {
       
        if(isset($_SESSION['user_id'])) {
            $modelSign_in = new ModelSign_in();
            $result = $modelSign_in->sign_out();
         
        } else {
            error_log("WARNING: No user_id in session!");
        }
        
        // Xóa cookie
        $this->clearRememberCookie();
        
        // Xóa session
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Chuyển về trang đăng nhập
        header('Location: ?controller=login&action=index');
        exit();
    }
    

    private function clearRememberCookie() {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        
     
        setcookie('remember_token', '', time() - 3600, '/', '', $isSecure, true);
        setcookie('remember_token', '', time() - 3600, '', '', $isSecure, true);
        
     
        unset($_COOKIE['remember_token']);
    }
}
?>