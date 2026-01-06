<?php
require_once __DIR__ . '/../Model/emloyee/emloyee.php'; 
require_once __DIR__ . '/../Model/emloyee/ModelProfile.php'; 
require_once __DIR__ . '/../Model/department/ModelDepartment.php';
class ProfileController {
    private $modelEmployee;
    
    public function __construct() {
        $this->modelEmployee = new ModelEmloyee();
    }
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=login&action=index');
            exit();
        }

        $user_id = $_SESSION['user_id'];
        
        // 1. Tạo biến $objEmployee (Object) để View dùng cho Sidebar
        $objEmployee = $this->modelEmployee->getEmloyeeById($user_id);
        
        // 2. Tạo biến $objDepartment (Object) để hiển thị tên phòng ban
        $modelDepartment = new ModelDepartment();
        $objDepartment = $modelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());
        
        $modelProfile = new ModelProfile();

        // 3. Lấy thông tin chi tiết profile
        $profile = $modelProfile->getProfile($user_id);
        
        // 4. Lấy lịch làm việc
        $schedules = $modelProfile->getPersonalSchedule($user_id);

        // 5. Tăng ca (chỉ cho QUAN_LY)
        $totalOvertime = 0;
        $overtimeSchedules = [];
        if (in_array($objEmployee->getVaiTro(), ['QUAN_LY', 'ADMIN'])) {
            $totalOvertime = $modelProfile->getTotalOvertimeHours($user_id);
            $overtimeSchedules = $modelProfile->getPersonalOvertimeSchedule($user_id);
        }
        
        // 6. Lịch nghỉ phép đã duyệt
        $leaves = $modelProfile->getPersonalLeaves($user_id);
        
        // ============================================
        // 7. THÊM MỚI: Thông tin chấm công và phép
        // ============================================
        
        // 7.1. Số dư phép
        $leaveBalance = $modelProfile->getLeaveBalance($user_id);
        
        // 7.2. Lịch sử chấm công (30 ngày gần nhất)
        $attendanceHistory = $modelProfile->getAttendanceHistory($user_id, 30);
        
        // 7.3. Thống kê chấm công tháng này
        $attendanceStats = $modelProfile->getAttendanceStats($user_id);
        
        // 7.4. Lịch chấm công theo tháng (cho calendar view)
        $current_year = date('Y');
        $current_month = date('n');
        $monthlyAttendance = $modelProfile->getMonthlyAttendance($user_id, $current_year, $current_month);
        
        $objModelEmloyee = new ModelEmloyee();
        $objEmployee = $objModelEmloyee->getEmloyeeById($_SESSION['user_id']);



        if (!$objEmployee) {
            $_SESSION['error'] = 'Không tìm thấy nhân viên!';
            header('Location: ?controller=login&action=index');
            exit();
        }

        $objModelDepartment = new ModelDepartment();
        $objDepartment = $objModelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());

        $vai_tro = "nhân viên";
        switch ($objEmployee->getVaiTro()) {
            case 'QUAN_LY':
                $vai_tro = "quản lý";


                break;
            case 'NHAN_SU':
                $vai_tro = "nhân sự";

                break;
            case 'GIAM_DOC':
                $vai_tro = "giám đốc";
                break;
        }
        $objEmployee->setVaiTro($vai_tro);
        $departmentIcons = [
            'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>',
            'Marketing' => '<i class="fas fa-bullhorn  me-2"></i>',
            'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>',
            'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>',
            'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>',
            'Spa & Massage' => '<i class="fas fa-spa me-2"></i>',
            'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>',
            'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>',
            'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>',
            'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>',
        ];
        $_SESSION['icon']=$departmentIcons[$objDepartment->getTenPhongBan()];
       
       
       
       
        require_once __DIR__ . '/../View/profile/index.php';
    }

    public function change_password() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=login&action=index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $oldPassInput = $_POST['old_password']; 
            $newPass = $_POST['new_password'];
            $confirmPass = $_POST['confirm_password'];
            $currentDbPass = $this->modelEmployee->getPasswordHash($userId);

            // Kiểm tra mật khẩu cũ
            $isCorrect = false;
            if (password_verify($oldPassInput, $currentDbPass)) {
                $isCorrect = true;
            } elseif ($oldPassInput === $currentDbPass) {
                $isCorrect = true;
            }

            if (!$isCorrect) {
                echo "<script>alert('Mật khẩu cũ không chính xác!'); window.history.back();</script>";
                return;
            }

            // Kiểm tra khớp
            if ($newPass !== $confirmPass) {
                echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.history.back();</script>";
                return;
            }

            // Validate
            $pattern = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,20}$/';
            if (!preg_match($pattern, $newPass)) {
                echo "<script>alert('Mật khẩu không đủ mạnh! Yêu cầu: 8-20 ký tự, 1 hoa, 1 số, 1 ký tự đặc biệt.'); window.history.back();</script>";
                return;
            }

            // Hash và lưu
            $newPassHash = password_hash($newPass, PASSWORD_BCRYPT);
            $result = $this->modelEmployee->updatePassword($userId, $newPassHash);

            if ($result) {
                if(isset($_SESSION['current_password_input'])) {
                    $_SESSION['current_password_input'] = $newPass;
                }
                echo "<script>
                    alert('Đổi mật khẩu thành công!'); 
                    window.location.href='?controller=profile&action=index';
                </script>";
            } else {
                echo "<script>alert('Lỗi hệ thống!'); window.history.back();</script>";
            }
        } else {
            // Hiển thị form
            $user_id = $_SESSION['user_id'];
            $objEmployee = $this->modelEmployee->getEmloyeeById($user_id);
            
            require_once __DIR__ . '/../Model/department/ModelDepartment.php';
            $modelDept = new ModelDepartment();
            $objDepartment = $modelDept->getdepartmentby_user_id($objEmployee->getMaPhongBan());
            
            $departmentIcons = [
                'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>',
                'Marketing' => '<i class="fas fa-bullhorn me-2"></i>',
                'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>',
                'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>',
                'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>',
                'Spa & Massage' => '<i class="fas fa-spa me-2"></i>',
                'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>',
                'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>',
                'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>',
                'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>',
            ];

            $currentPasswordValue = isset($_SESSION['current_password_input']) ? $_SESSION['current_password_input'] : '';
            require_once __DIR__ . '/../View/profile/change_password.php';
        }
    }
}
?>