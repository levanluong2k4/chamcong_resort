<?php
// Controller/DoiLichController.php

require_once __DIR__ . '/../Model/Connect.php';
require_once __DIR__ . '/../Model/lich/DoiLichModel.php';

class DoiLichController {
    private $model;
    
    public function __construct() {
        $this->model = new DoiLichModel();
    }
    
    /**
     * Trang chính - Hiển thị giao diện
     */
    public function index() {
        // Kiểm tra quyền
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=login&action=index');
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $vai_tro = $_SESSION['vai_tro'] ?? 'NHAN_VIEN';
        
        // Lấy phòng ban của user
        $ma_phong_ban = $this->model->getPhongBanByNhanVien($user_id);
        
        // Lấy tháng/năm hiện tại
        $thang = date('m');
        $nam = date('Y');
        
        // Include view
        require_once __DIR__ . '/../View/doilich/DoiLichView.php';
    }
    
    /**
     * API: Lấy danh sách tuần trong tháng
     */
    public function getWeeksInMonth() {
        header('Content-Type: application/json');
        
        if (!isset($_POST['thang']) || !isset($_POST['nam'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
            exit;
        }
        
        $thang = intval($_POST['thang']);
        $nam = intval($_POST['nam']);
        
        $result = $this->model->getWeeksInMonth($thang, $nam);
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }
    
    /**
     * API: Lấy lịch theo tuần
     */
    public function getLichTheoTuan() {
        header('Content-Type: application/json');
        
        if (!isset($_POST['tuan']) || !isset($_POST['thang']) || 
            !isset($_POST['nam']) || !isset($_POST['ma_phong_ban'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
            exit;
        }
        
        $tuan = intval($_POST['tuan']);
        $thang = intval($_POST['thang']);
        $nam = intval($_POST['nam']);
        $ma_phong_ban = intval($_POST['ma_phong_ban']);
        
        // ✅ Lấy dữ liệu đầy đủ (bao gồm cả ngày nghỉ)
        $result = $this->model->getLichTheoTuanDayDu($tuan, $thang, $nam, $ma_phong_ban);
        
        // Nhóm dữ liệu theo nhân viên
        $groupedData = [];
        while ($row = $result->fetch_assoc()) {
            $ma_nv = $row['ma_nhan_vien'];
            
            if (!isset($groupedData[$ma_nv])) {
                $groupedData[$ma_nv] = [
                    'ma_nhan_vien' => $row['ma_nhan_vien'],
                    'ho_ten' => $row['ho_ten'],
                    'email' => $row['email'],
                    'ten_phong_ban' => $row['ten_phong_ban'],
                    'lich' => []
                ];
            }
            
            $groupedData[$ma_nv]['lich'][] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => array_values($groupedData)
        ]);
        exit;
    }
    
    /**
     * API: Đổi ca giữa 2 nhân viên
     */
    public function doiCa() {
        header('Content-Type: application/json');
        
        if (!isset($_POST['ca1']) || !isset($_POST['ca2'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
            exit;
        }
        
        $ca1 = json_decode($_POST['ca1'], true);
        $ca2 = json_decode($_POST['ca2'], true);
        
        // ✅ Xác định loại đổi
        if ($ca1['type'] === 'CA' && $ca2['type'] === 'CA') {
            // Trường hợp 1: Đổi ca giữa 2 nhân viên (cả 2 đều có ca)
            $result = $this->model->doiCaGiua2Lich($ca1['ma_lich'], $ca2['ma_lich']);
        } 
        else if (($ca1['type'] === 'CA' && $ca2['type'] === 'OFF') || 
                 ($ca1['type'] === 'OFF' && $ca2['type'] === 'CA')) {
            // Trường hợp 2: Chuyển ca từ người có ca sang người OFF
            $caCoLich = $ca1['type'] === 'CA' ? $ca1 : $ca2;
            $caOff = $ca1['type'] === 'OFF' ? $ca1 : $ca2;
            
            $result = $this->model->chuyenCaSangNguoiOff(
                $caCoLich['ma_lich'], 
                $caCoLich['ma_nhan_vien'],
                $caOff['ma_nhan_vien'],
                $caOff['ngay']
            );
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Không thể đổi giữa 2 ô OFF']);
            exit;
        }
        
        echo json_encode($result);
        exit;
    }
    
    /**
     * API: Lấy danh sách ca làm việc
     */
    public function getCaLamViec() {
        header('Content-Type: application/json');
        
        $result = $this->model->getAllCaLamViec();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }
    
    /**
     * API: Lấy danh sách phòng ban
     */
    public function getPhongBan() {
        header('Content-Type: application/json');
        
        $result = $this->model->getAllPhongBan();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }
}
?>