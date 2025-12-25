<?php
// router.php - File định tuyến chính của hệ thống

require_once __DIR__ . '/../Controller/LoginController.php';
require_once __DIR__ . '/../Controller/HomeController.php';
require_once __DIR__ . '/../Controller/ShiftScheduleController.php';
require_once __DIR__ . '/../Controller/lichcodinh.php';
require_once __DIR__ . '/../Controller/LichTuanController.php';
require_once __DIR__ . '/../Controller/DoiLichController.php';
require_once __DIR__ . '/../Controller/ChamCongController.php';

session_start();

$controller = $_GET['controller'] ?? $_POST['controller'] ?? 'login';
$action = $_GET['action'] ?? $_POST['action'] ?? 'index';
$user_id = $_SESSION['user_id'] ?? -1;

// Debug
// echo "<pre>Controller: $controller | Action: $action | User ID: $user_id</pre>";

// Uncomment để bật authentication
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
        
    case 'ShiftSchedule':
        $controllerObj = new ShiftScheduleController();
    
        switch ($action) {
            case 'index':
                $controllerObj->index();
                break;
    
            case 'tao-lich-tu-dong':
                $controllerObj->taoLichTuDong();
                exit;
    
            case 'xem-lich':
                $controllerObj->xemLich();
                exit;
    
            case 'xem-lich-phong-ban':
                $controllerObj->xemLichphongban();
                exit;
    
            case 'doi-ca':
                $controllerObj->doiCa();
                exit;
    
            case 'doi-ca-2nv':
                $controllerObj->doiCaGiua2NV();
                exit;
    
            default:
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Action không tồn tại'
                ]);
                exit;
        }
        break;

    case 'LichCoDinh':
        $controllerLCD = new LichCoDinhController();
        
        switch ($action) {
            case 'index':
                $controllerLCD->index();
                break;
                
            case 'layDanhSachNhanVien':
                $controllerLCD->layDanhSachNhanVien();
                break;
                
            case 'layDanhSachCa':
                $controllerLCD->layDanhSachCa();
                break;
                
            case 'layLichCoDinh':
                $controllerLCD->layLichCoDinh();
                break;
                
            case 'luuLichCoDinh':
                $controllerLCD->luuLichCoDinh();
                break;
                
            case 'xoaLichCoDinh':
                $controllerLCD->xoaLichCoDinh();
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Action không tồn tại']);
                exit;
        }
        break;

    case 'LichTuan':
        $controllerLT = new LichTuanController();
        
        switch ($action) {
            case 'index':
                $controllerLT->index();
                break;
                
            case 'layThongTinTuan':
                $controllerLT->layThongTinTuan();
                break;
                
            case 'layDanhSachNhanVien':
                $controllerLT->layDanhSachNhanVien();
                break;
                
            case 'layDanhSachCa':
                $controllerLT->layDanhSachCa();
                break;
                
            case 'layLichTuan':
                $controllerLT->layLichTuan();
                break;
                
            case 'luuLichTuan':
                $controllerLT->luuLichTuan();
                break;
                case 'xuatExcelTheoThu':
                    $controllerLT->xuatExcelTheoThu();  // ✅ ĐÚNG
                    break;
                    
                case 'xuatExcelTatCa':
                    $controllerLT->xuatExcelTatCa();    // ✅ ĐÚNG
                    break;
                    
                case 'layDanhSachThuCoData':
                    $controllerLT->layDanhSachThuCoData();  // ✅ ĐÚNG
                    break;
                
            default:
                http_response_code(404);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Action không tồn tại: ' . $action
                ], JSON_UNESCAPED_UNICODE);
                exit;
        }
        break;
        case 'ChamCong':
            $controllerCC = new ChamCongController();
            
            switch ($action) {
                case 'index':
                    $controllerCC->index();
                    break;
                    
                case 'getDuLieuChamCong':
                    $controllerCC->getDuLieuChamCong();
                    break;
                    
                case 'getPhongBan':
                    $controllerCC->getPhongBan();
                    break;
                    
                case 'chamCongVao':
                    $controllerCC->chamCongVao();
                    break;
                    
                case 'chamCongRa':
                    $controllerCC->chamCongRa();
                    break;
                    
                default:
                    http_response_code(404);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Action không tồn tại: ' . $action
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
            }
            break;

        case 'DoiLich':
            $controllerDL = new DoiLichController();
            
            switch ($action) {
                case 'index':
                    $controllerDL->index();
                    break;
                    
                case 'getWeeksInMonth':
                    $controllerDL->getWeeksInMonth();
                    break;
                    
                case 'getLichTheoTuan':
                    $controllerDL->getLichTheoTuan();
                    break;
                    
                case 'doiCa':
                    $controllerDL->doiCa();
                    break;
                    
                case 'getCaLamViec':
                    $controllerDL->getCaLamViec();
                    break;
                    
                case 'getPhongBan':
                    $controllerDL->getPhongBan();
                    break;
                    
                default:
                    http_response_code(404);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Action không tồn tại: ' . $action
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
            }
            break;
        
    default:
        header('Location: ?controller=login&action=index');
        exit();
}
?>