<?php
require_once __DIR__ . '/../Model/donnghiphep/ModelDonNghiPhep.php';
require_once __DIR__ . '/../Model/emloyee/emloyee.php';
require_once __DIR__ . '/../Model/department/ModelDepartment.php';

// DÒNG GÂY LỖI: require_once __DIR__ . '/../Model/soduphep/ModelSoDuPhep.php';
// -> Đã xóa hoặc comment dòng trên để tránh lỗi Fatal Error.

class LeaveController {
    private $modelDonNghi;
    private $modelEmployee;
    private $modelDepartment;
    // private $modelSoDu; // -> Tạm thời bỏ thuộc tính này

    public function __construct() {
        $this->modelDonNghi = new ModelDonNghiPhep();
        $this->modelEmployee = new ModelEmloyee();
        $this->modelDepartment = new ModelDepartment();
    }

    // Hàm lấy thông tin chung (User, Phòng ban) để đỡ lặp code
    private function getCommonData() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=login&action=index');
            exit();
        }
        $userId = $_SESSION['user_id'];
        $objEmployee = $this->modelEmployee->getEmloyeeById($userId);
        $objDepartment = $this->modelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());
        
        // Logic hiển thị vai trò
        $vai_tro_hien_thi = match($objEmployee->getVaiTro()) {
            'QUAN_LY' => 'Quản Lý',
            'NHAN_SU' => 'Nhân Sự',
            'GIAM_DOC' => 'Giám Đốc',
            default => 'Nhân Viên'
        };
        $objEmployee->setVaiTro($vai_tro_hien_thi);

        return [$userId, $objEmployee, $objDepartment];
    }

    // TRANG 1: DANH SÁCH & NÚT TẠO
    public function index() {
        list($userId, $objEmployee, $objDepartment) = $this->getCommonData();
        
        // Lấy lịch sử nghỉ phép của nhân viên này
        $historyList = $this->modelDonNghi->getHistoryByEmployee($userId);

        // Icon phòng ban
        $departmentIcons = [ 'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>', 'Marketing' => '<i class="fas fa-bullhorn me-2"></i>', 'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>', 'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>', 'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>', 'Spa & Massage' => '<i class="fas fa-spa me-2"></i>', 'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>', 'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>', 'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>', 'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>' ];

        // --- SỬA ĐOẠN NÀY ---
        // Vì chưa có ModelSoDuPhep, ta tạm thời để số phép mặc định là 12
        $phepConHienTai = 12; 
        
        /* Code cũ gây lỗi (khi nào có file ModelSoDuPhep thì mở lại):
        $namHienTai = date('Y');
        $thongTinPhep = $this->modelSoDu->getSoDuPhep($userId, $namHienTai);
        $phepConHienTai = $thongTinPhep ? $thongTinPhep['so_ngay_phep_con_lai'] : 12;
        */
        // --------------------

        require_once __DIR__ . '/../View/leave/index.php';
    }

    // TRANG 2: FORM ĐIỀN ĐƠN
    public function create() {
        list($userId, $objEmployee, $objDepartment) = $this->getCommonData();
        $historyList = $this->modelDonNghi->getHistoryByEmployee($userId);
       
        
        $departmentIcons = [ 'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>', 'Marketing' => '<i class="fas fa-bullhorn me-2"></i>', 'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>', 'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>', 'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>', 'Spa & Massage' => '<i class="fas fa-spa me-2"></i>', 'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>', 'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>', 'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>', 'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>' ];

        require_once __DIR__ . '/../View/leave/create.php';
    }

    // XỬ LÝ LƯU ĐƠN
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maNhanVien = $_SESSION['user_id'];
            $loaiNghi = $_POST['loai_nghi'];
            $ngayBatDau = $_POST['ngay_bat_dau']; 
            $ngayKetThuc = $_POST['ngay_ket_thuc'];
            $lyDo = $_POST['ly_do'];
            $fileName = null;

            if (strtotime($ngayBatDau) > strtotime($ngayKetThuc)) {
                echo "<script>alert('Ngày kết thúc phải lớn hơn ngày bắt đầu!'); window.history.back();</script>";
                return;
            }

            if (isset($_FILES['file_dinh_kem']) && $_FILES['file_dinh_kem']['error'] == 0) {
                $targetDir = __DIR__ . "/../../uploads/"; 
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                
                $extension = pathinfo($_FILES["file_dinh_kem"]["name"], PATHINFO_EXTENSION);
                $fileName = time() . '_' . $maNhanVien . '.' . $extension;
                $targetFile = $targetDir . $fileName;

                if (!move_uploaded_file($_FILES["file_dinh_kem"]["tmp_name"], $targetFile)) {
                    echo "<script>alert('Lỗi upload file!'); window.history.back();</script>";
                    return;
                }
            }

            $result = $this->modelDonNghi->createDonNghi($maNhanVien, $loaiNghi, $ngayBatDau, $ngayKetThuc, $lyDo, $fileName);

            if ($result) {
                echo "<script>alert('Gửi đơn thành công!'); window.location.href = '?controller=leave&action=index';</script>";
            } else {
                echo "<script>alert('Lỗi!'); window.history.back();</script>";
            }
        }
    }

    //Hàm xoá đơn
   public function delete() {
    if (isset($_GET['id'])) {
        $maDon = $_GET['id'];
        // Gọi hàm xóa
        $result = $this->modelDonNghi->deleteDon($maDon);

        if ($result) {
             echo "<script>alert('Đã xóa đơn thành công!'); window.location.href = '?controller=leave&action=index';</script>";
        } else {
             // Thêm dòng này để biết nếu lỗi
             echo "<script>alert('Lỗi: Không thể xóa đơn này (ID: $maDon)'); window.location.href = '?controller=leave&action=index';</script>";
        }
    }
}

    // CHI TIẾT ĐƠN
    public function detail() {
        list($userId, $objEmployee, $objDepartment) = $this->getCommonData();
        
        if (isset($_GET['id'])) {
            $maDon = $_GET['id'];
            $don = $this->modelDonNghi->getDonById($maDon);

            if (!$don || $don['ma_nhan_vien'] != $userId) {
                echo "<script>alert('Không tìm thấy đơn hoặc bạn không có quyền xem!'); window.location.href = '?controller=leave&action=index';</script>";
                return;
            }

            require_once __DIR__ . '/../View/leave/detail.php';
        }
    }

    // FORM SỬA
    public function edit() {
        list($userId, $objEmployee, $objDepartment) = $this->getCommonData();
        
        if (isset($_GET['id'])) {
            $maDon = $_GET['id'];
            $don = $this->modelDonNghi->getDonById($maDon);

            if (!$don || $don['ma_nhan_vien'] != $userId) {
                echo "<script>alert('Không có quyền truy cập!'); window.location.href = '?controller=leave&action=index';</script>";
                return;
            }
            if ($don['trang_thai'] != 'CHO_DUYET') {
                echo "<script>alert('Chỉ có thể sửa đơn khi chưa được duyệt!'); window.location.href = '?controller=leave&action=index';</script>";
                return;
            }

            $departmentIcons = [ 'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>', 'Marketing' => '<i class="fas fa-bullhorn me-2"></i>', 'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>', 'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>', 'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>', 'Spa & Massage' => '<i class="fas fa-spa me-2"></i>', 'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>', 'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>', 'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>', 'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>' ];

            require_once __DIR__ . '/../View/leave/edit.php';
        }
    }

    // CẬP NHẬT ĐƠN
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maDon = $_POST['ma_don'];
            $loaiNghi = $_POST['loai_nghi'];
            $ngayBatDau = $_POST['ngay_bat_dau']; 
            $ngayKetThuc = $_POST['ngay_ket_thuc'];
            $lyDo = $_POST['ly_do'];
            $fileName = null;

            if (strtotime($ngayBatDau) > strtotime($ngayKetThuc)) {
                echo "<script>alert('Ngày kết thúc phải lớn hơn ngày bắt đầu!'); window.history.back();</script>";
                return;
            }

            if (isset($_FILES['file_dinh_kem']) && $_FILES['file_dinh_kem']['error'] == 0) {
                $targetDir = __DIR__ . "/../../uploads/"; 
                $extension = pathinfo($_FILES["file_dinh_kem"]["name"], PATHINFO_EXTENSION);
                $fileName = time() . '_' . $_SESSION['user_id'] . '.' . $extension;
                move_uploaded_file($_FILES["file_dinh_kem"]["tmp_name"], $targetDir . $fileName);
            }

            $result = $this->modelDonNghi->updateDonNghi($maDon, $loaiNghi, $ngayBatDau, $ngayKetThuc, $lyDo, $fileName);

            if ($result) {
                echo "<script>alert('Cập nhật đơn thành công!'); window.location.href = '?controller=leave&action=index';</script>";
            } else {
                echo "<script>alert('Lỗi khi cập nhật!'); window.history.back();</script>";
            }
        }
    }
}
?>