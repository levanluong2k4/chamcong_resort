<?php
require_once __DIR__ . '/../Model/donnghiphep/ModelDonNghiPhep.php';
require_once __DIR__ . '/../Model/emloyee/emloyee.php';
require_once __DIR__ . '/../Model/department/ModelDepartment.php';

class DuyetDonController {
    private $modeldonnghiphep;
    private $modelEmployee;
    private $modelDepartment;

    public function __construct() {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->modeldonnghiphep = new ModelDonNghiPhep();
        $this->modelEmployee = new ModelEmloyee();
        $this->modelDepartment = new ModelDepartment();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=login&action=index');
            exit();
        }
        $userId = $_SESSION['user_id'];

        // Lấy thông tin user hiện tại
        $objEmployee = $this->modelEmployee->getEmloyeeById($userId);
        $objDepartment = $this->modelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());
        
        $realRole = $objEmployee->getVaiTro(); 

        // Hiển thị tên vai trò (Visual)
        $vai_tro_hien_thi = match($realRole) {
            'QUAN_LY' => 'Quản Lý', 
            'NHAN_SU' => 'Nhân Sự', 
            'GIAM_DOC', 'ADMIN' => 'Giám Đốc / Admin',
            default => 'Nhân Viên'
        };
        $objEmployee->setVaiTro($vai_tro_hien_thi);

        // Icon phòng ban (Visual)
        $departmentIcons = [ 'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>', 'Marketing' => '<i class="fas fa-bullhorn me-2"></i>', 'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>', 'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>', 'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>', 'Spa & Massage' => '<i class="fas fa-spa me-2"></i>', 'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>', 'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>', 'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>', 'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>' ];
        
        // --- PHÂN TÁCH DỮ LIỆU ---
        $topList = [];    // Danh sách chờ duyệt
        $bottomList = []; // Danh sách lịch sử
        $countPending = 0;

        // =================================================================================
        // LOGIC PHÂN QUYỀN HIỂN THỊ
        // =================================================================================
        
        // --- TRƯỜNG HỢP 1: ADMIN HOẶC GIÁM ĐỐC ---
        if ($realRole == 'GIAM_DOC' || $realRole == 'ADMIN') {
            // 1. TOP LIST: Chỉ lấy đơn chờ duyệt của QUAN_LY và NHAN_SU
            // (Nhân viên thường thì để Quản lý duyệt)
            $topList = $this->modeldonnghiphep->getPendingForAdmin();
            
            // 2. BOTTOM LIST: Xem lịch sử của TẤT CẢ mọi người (Toàn công ty)
            $bottomList = $this->modeldonnghiphep->getAllHistoryForAdmin();
            
            $countPending = count($topList);
        } 
        
        // --- TRƯỜNG HỢP 2: QUẢN LÝ (Manager) ---
        elseif ($realRole == 'QUAN_LY') {
            // 1. Duyệt đơn của nhân viên cấp dưới thuộc phòng ban mình hoặc do mình quản lý
            $topList = $this->modeldonnghiphep->getPendingRequestsByManager($userId);
            
            // 2. Xem lịch sử mình đã duyệt
            $bottomList = $this->modeldonnghiphep->getHistoryRequestsByManager($userId);
            
            $countPending = count($topList);
        }
        
        // --- TRƯỜNG HỢP 3: NHÂN SỰ (HR) ---
        elseif ($realRole == 'NHAN_SU') {
            // HR thường chỉ xem để chấm công, không duyệt đơn (tùy nghiệp vụ)
            // Nếu muốn HR duyệt được cho nhân viên, dùng hàm giống QUAN_LY
            $topList = []; 
            $bottomList = $this->modeldonnghiphep->getAllHistoryForHR(); 
            $countPending = 0;
        }

        // --- XỬ LÝ PHÂN TRANG CHO LỊCH SỬ ---
        $limit = 5; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $totalRecords = count($bottomList);
        $totalPages = ceil($totalRecords / $limit);

        $offset = ($page - 1) * $limit;
        $bottomListPaged = array_slice($bottomList, $offset, $limit);

        // Biến này để View hiển thị tiêu đề
        $userRole = $realRole; 

        require_once __DIR__ . '/../View/duyetdon/index.php'; 
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $maDon = $_POST['ma_don'] ?? null;
            $action = $_POST['action'] ?? null;

            if (isset($maDon) && $action) {
                if ($action == 'approve' || $action == 'reject') {
                    $lyDo = $_POST['ly_do_tu_choi'] ?? null;
                    $status = ($action == 'approve') ? 'DA_DUYET' : 'TU_CHOI';
                    
                    // Thực hiện cập nhật trạng thái
                    $this->modeldonnghiphep->updateStatus($maDon, $status, $userId, $lyDo);
                } 
                header('Location: ?controller=duyetdon&action=index');
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $maDon = $_GET['id'];
            $result = $this->modeldonnghiphep->deleteDon($maDon);

            if ($result) {
                echo "<script>
                        alert('Đã xóa đơn thành công!'); 
                        window.location.href = '?controller=duyetdon&action=index';
                      </script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra khi xóa!'); window.location.href = '?controller=duyetdon&action=index';</script>";
            }
        }
    }
}
?>