<?php
// ===================================================================
// File: Controller/LichCoDinhController.php
// ===================================================================

require_once __DIR__ . '/../Model/lich/LichCoDinhModel.php';

class LichCoDinhController {
    private $model;
    
    public function __construct() {
        $this->model = new LichCoDinhModel();
    }
    
    /**
     * Hiển thị trang quản lý lịch cố định
     */
    public function index() {
        // Lấy phòng ban của user hiện tại
        $ma_phong_ban = $_SESSION['ma_phong_ban'] ?? 3;
        include __DIR__ . '/../View/lich_co_dinh/index.php';
    }
    
    /**
     * API: Lấy danh sách nhân viên theo phòng ban
     */
    public function layDanhSachNhanVien() {
        // Clear output buffer
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $ma_phong_ban = $_POST['ma_phong_ban'] ?? $_GET['ma_phong_ban'] ?? 0;
            
            if (!$ma_phong_ban) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vui lòng chọn phòng ban'
                ]);
                exit;
            }
            
            $result = $this->model->layDanhSachNhanVien($ma_phong_ban);
            echo json_encode([
                'success' => true,
                'data' => $result
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * API: Lấy danh sách ca làm việc
     */
    public function layDanhSachCa() {
        // Clear output buffer
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $result = $this->model->layDanhSachCa();
            echo json_encode([
                'success' => true,
                'data' => $result
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * API: Lấy lịch cố định hiện tại
     */
    public function layLichCoDinh() {
        // Clear output buffer
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $ma_phong_ban = $_POST['ma_phong_ban'] ?? $_GET['ma_phong_ban'] ?? 0;
            
            if (!$ma_phong_ban) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vui lòng chọn phòng ban'
                ]);
                exit;
            }
            
            $result = $this->model->layLichCoDinh($ma_phong_ban);
            echo json_encode([
                'success' => true,
                'data' => $result
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * API: Lưu toàn bộ lịch cố định
     */
    public function luuLichCoDinh() {
        // Clear output buffer
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Lấy dữ liệu JSON từ body
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (!$data || !isset($data['ma_phong_ban']) || !isset($data['lich'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            $ma_phong_ban = $data['ma_phong_ban'];
            $lich = $data['lich'];
            
            $result = $this->model->luuLichCoDinh($ma_phong_ban, $lich);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Lưu lịch cố định thành công!'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Lỗi khi lưu lịch cố định'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * API: Xóa lịch cố định của 1 nhân viên trong 1 ngày
     */
    public function xoaLichCoDinh() {
        header('Content-Type: application/json; charset=utf-8');
        
        $ma_nhan_vien = $_POST['ma_nhan_vien'] ?? 0;
        $thu_trong_tuan = $_POST['thu_trong_tuan'] ?? 0;
        $ma_ca = $_POST['ma_ca'] ?? 0;
        
        if (!$ma_nhan_vien || !$thu_trong_tuan || !$ma_ca) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu thông tin'
            ]);
            exit;
        }
        
        $result = $this->model->xoaLichCoDinh($ma_nhan_vien, $thu_trong_tuan, $ma_ca);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa lịch cố định'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi xóa lịch cố định'
            ]);
        }
        exit;
    }
}


?>
