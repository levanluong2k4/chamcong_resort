<?php 
require_once __DIR__ . '/../Model/emloyee/ModelSign_in.php';


class LoginController {
    public function index() {
        require_once __DIR__ . '/../View/sign_in_up/sign_in.php';
    }
    
    public function sign_in() {
        $modelSign_in = new ModelSign_in();
        $email = $_POST['email'];
        $password = $_POST['mat_khau_hash'];
        $remember= $_POST['remember']??false;
        $employee = $modelSign_in->sign_in($email,$password,$remember); 
      

        
        if($employee != null){ 
         
           
            header('Location: /thuctap/projectschamcong/quanlychamcong/Router/router.php?controller=home&action=index');
            exit();
        } else {
            // header('Location: /thuctap/projectschamcong/quanlychamcong/Router/router.php?controller=home&action=index');
            // exit();
            echo '<pre>';
print_r($_SESSION);
print_r($_POST);

echo '</pre>';
            exit();
        }
    }
    
    public function sign_out() {
        session_destroy();
        setcookie('email', '', time() - 3600, '/');
        setcookie('password', '', time() - 3600, '/');
        header('Location: ?controller=login&action=index');
        exit();
    }
}
?>