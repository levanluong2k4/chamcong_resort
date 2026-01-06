<?php
require_once __DIR__ . '/../Model/donnghiphep/ModelDonNghiPhep.php';
require_once __DIR__ . '/../Model/soduphep/ModelSoDuPhep.php';
require_once __DIR__ . '/../Model/emloyee/emloyee.php';

class HrLeaveController {
    private $modelDonNghi;
    private $modelSoDu;
    private $modelEmployee;

    public function __construct() {
        $this->modelDonNghi = new ModelDonNghiPhep();
     
        $this->modelEmployee = new ModelEmloyee();
    }

    public function index() {
        // Kiểm tra quyền HR (Giả sử role NHAN_SU)
        if (!isset($_SESSION['user_id'])) header('Location: ?controller=login');
        // TODO: Thêm check quyền role == 'NHAN_SU' ở đây

      
        require_once __DIR__ . '/../View/hr/confirm_leave.php';
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maDon = $_POST['ma_don'];
            $maNhanVien = $_POST['ma_nhan_vien'];
            $soNgayNghi = $_POST['so_ngay_nghi'];
            $action = $_POST['action']; // 'ghi_nhan' hoặc 'khong_luong'
            $namHienTai = date('Y');

            if ($action == 'ghi_nhan') {
                // 1. Kiểm tra số dư phép
                $soDu = $this->modelSoDu->getSoDuPhep($maNhanVien, $namHienTai);
                
                if ($soDu && $soDu['so_ngay_phep_con_lai'] >= $soNgayNghi) {
                    // Đủ phép -> Trừ phép và Hoàn tất
                    $this->modelSoDu->truNgayPhep($maNhanVien, $namHienTai, $soNgayNghi);
                 
                    echo "<script>alert('Đã ghi nhận trừ phép thành công!');</script>";
                } else {
                    echo "<script>alert('Lỗi: Nhân viên không đủ ngày phép! Vui lòng chọn nghỉ không lương.'); window.history.back(); exit;</script>";
                }

            } elseif ($action == 'khong_luong') {
                // Chuyển thành nghỉ không lương và Hoàn tất (Không trừ phép)
              
                echo "<script>alert('Đã ghi nhận nghỉ không lương!');</script>";
            }

            echo "<script>window.location.href = '?controller=hr_leave&action=index';</script>";
        }
    }
}
?>