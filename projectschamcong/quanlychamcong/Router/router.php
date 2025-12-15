<?php 
require_once __DIR__ . '/../Controller/LoginController.php';
require_once __DIR__ . '/../Controller/HomeController.php';

session_start();

// ✅ Nhận action từ cả GET và POST
$controller = $_GET['controller'] ?? $_POST['controller'] ?? 'login';
$action = $_GET['action'] ?? $_POST['action'] ?? 'index';
$user_id = $_SESSION['user_id'] ?? -1;

// Debug (bỏ sau khi fix xong)
// echo "<pre>Controller: $controller | Action: $action | User ID: $user_id</pre>";

// if($user_id == -1 && $controller != 'login'){
//    header('Location: ?controller=login&action=index');
//    exit();
// }

switch ($controller) {
    case 'login':
        $loginController = new LoginController();
        
        if($action == 'index'){
            $loginController->index();
        }
        elseif($action == 'sign_in'){
            $loginController->sign_in();
        }
        elseif($action == 'sign_out'){
            $loginController->sign_out();
        }
        break;
        
    case 'home':
        $homeController = new HomeController();
        
        if($action == 'index'){
            $homeController->index();
        }
        break;
        
    default:
        header('Location: ?controller=login&action=index');
        exit();
}
?>