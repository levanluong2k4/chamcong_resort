<?php
require_once __DIR__ . '/../Model/lich/LichTuanModel.php';
require_once __DIR__ . '/../Model/lich/XuatLichTuanExcel.php';

class LichTuanController {
    private $model;
    private $excelModel;
    
    public function __construct() {
        $this->model = new LichTuanModel();
        $this->excelModel = new XuatLichTuanExcel();
    }
    
    /**
     * Hiển thị trang quản lý lịch tuần
     */
    public function index() {
        $ma_phong_ban = $_SESSION['ma_phong_ban'];
        include __DIR__ . '/../View/lich_tuan/index.php';
    }
    
    /**
     * API: Lấy thông tin tuần tiếp theo
     */
    public function layThongTinTuan() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $ma_phong_ban = $_POST['ma_phong_ban'] ?? $_GET['ma_phong_ban'] ?? 0;
            
            if (!$ma_phong_ban) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vui lòng chọn phòng ban'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            $thu2 = $this->model->layThu2TuanTiepTheo();
            $da_ton_tai = $this->model->kiemTraLichTuanDaTonTai($thu2, $ma_phong_ban);
            
            $ngay_trong_tuan = [];
            for ($i = 0; $i < 7; $i++) { // ✅ 7 ngày thay vì 6
                $ngay = date('Y-m-d', strtotime($thu2 . " +$i days"));
                
                // Map thứ: 0->Thứ 2, 1->Thứ 3, ..., 6->Chủ Nhật
                if ($i == 6) {
                    $thu = 1; // Chủ Nhật
                } else {
                    $thu = $i + 2; // Thứ 2-7
                }
                
                $ngay_trong_tuan[] = [
                    'ngay' => $ngay,
                    'thu' => $thu,
                    'ngay_hien_thi' => date('d/m/Y', strtotime($ngay))
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'thu_2' => $thu2,
                    'chu_nhat' => date('Y-m-d', strtotime($thu2 . ' +6 days')), // ✅ Thêm Chủ Nhật
                    'ngay_trong_tuan' => $ngay_trong_tuan,
                    'da_ton_tai' => $da_ton_tai
                ]
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
     * API: Lấy danh sách nhân viên
     */
    public function layDanhSachNhanVien() {
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
     * API: Lấy danh sách ca
     */
    public function layDanhSachCa() {
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
     * ✅ API: Lấy lịch tuần - PHÂN BIỆT CHẾ ĐỘ
     */
    public function layLichTuan() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $ma_phong_ban = $_POST['ma_phong_ban'] ?? $_GET['ma_phong_ban'] ?? 0;
            $thu2 = $_POST['thu2'] ?? $_GET['thu2'] ?? '';
            
            if (!$ma_phong_ban || !$thu2) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Thiếu thông tin'
                ]);
                exit;
            }
            
            // ✅ KIỂM TRA: Lịch đã tồn tại chưa?
            $da_ton_tai = $this->model->kiemTraLichTuanDaTonTai($thu2, $ma_phong_ban);
            
            if ($da_ton_tai) {
                // ✅ CHẾ ĐỘ SỬA: Load từ DB
                $data = $this->model->layLichTuanDaTao($thu2, $ma_phong_ban);
                error_log("📝 CHẾ ĐỘ SỬA - Load từ DB");
                
            } else {
                // ✅ CHẾ ĐỘ TẠO MỚI: Load từ lịch cố định
                $data = $this->model->layLichTuanTuLichCoDinh($thu2, $ma_phong_ban);
                error_log("🆕 CHẾ ĐỘ TẠO - Load từ lịch cố định");
            }
            
            error_log("✅ Số ngày nghỉ phép: " . count($data['nghi_phep']));
            error_log("✅ Số ngày có lịch: " . count($data['lich_tuan']));
            
            echo json_encode([
                'success' => true,
                'data' => $data
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("❌ Lỗi layLichTuan: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * ✅ API: Lưu lịch tuần (TẠO hoặc CẬP NHẬT)
     */
    public function luuLichTuan() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (!$data || !isset($data['ma_phong_ban']) || !isset($data['thu2']) || !isset($data['lich'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            $ma_phong_ban = $data['ma_phong_ban'];
            $thu2 = $data['thu2'];
            $lich = $data['lich'];
            
            // ✅ Kiểm tra chế độ
            $da_ton_tai = $this->model->kiemTraLichTuanDaTonTai($thu2, $ma_phong_ban);
            
            $result = $this->model->luuLichTuan($ma_phong_ban, $thu2, $lich);
            
            if ($result) {
                $message = $da_ton_tai ? 
                    '✅ Cập nhật lịch tuần thành công!' : 
                    '✅ Tạo lịch tuần thành công!';
                
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'is_update' => $da_ton_tai
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => '❌ Lỗi khi lưu lịch tuần'
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
     * XUẤT EXCEL - Xuất một thứ cụ thể
     */
    public function xuatExcelTheoThu() {
        if (ob_get_length()) ob_clean();
        
        try {
            $thu2 = $_GET['thu2'] ?? null;
            $ma_phong_ban = $_GET['ma_phong_ban'] ?? $_SESSION['ma_phong_ban'] ?? null;
            $thu = $_GET['thu'] ?? 2;
            
            if (!$thu2 || !$ma_phong_ban) {
                die('Thiếu thông tin thu2 hoặc ma_phong_ban');
            }
            
            $this->excelModel->xuatExcelTheoThu($thu2, $ma_phong_ban, $thu);
            
        } catch (Exception $e) {
            die('Lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * XUẤT EXCEL - Xuất tất cả các thứ (ZIP)
     */
    public function xuatExcelTatCa() {
        try {
            $thu2 = $_GET['thu2'] ?? null;
            $ma_phong_ban = $_GET['ma_phong_ban'] ?? $_SESSION['ma_phong_ban'] ?? null;
            
            if (!$thu2 || !$ma_phong_ban) {
                die(json_encode([
                    'success' => false,
                    'message' => 'Thiếu thông tin thu2 hoặc ma_phong_ban'
                ]));
            }
            
            $result = $this->excelModel->xuatTatCaCacThu($thu2, $ma_phong_ban);
            
            if (isset($result['success']) && !$result['success']) {
                die(json_encode($result));
            }
            
        } catch (Exception $e) {
            die(json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]));
        }
    }
    
    /**
     * API: Lấy danh sách các thứ có dữ liệu
     */
    public function layDanhSachThuCoData() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $thu2 = $_GET['thu2'] ?? null;
            $ma_phong_ban = $_GET['ma_phong_ban'] ?? $_SESSION['ma_phong_ban'] ?? null;
            
            if (!$thu2 || !$ma_phong_ban) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Thiếu thông tin'
                ]);
                exit;
            }
            
            $available_days = [];
            
            // ✅ Lặp qua 7 ngày: Thứ 2-7 và Chủ Nhật
            $days_to_check = [2, 3, 4, 5, 6, 7, 1]; // 1 = Chủ Nhật
            
            foreach ($days_to_check as $index => $thu) {
                $lich = $this->excelModel->layLichTheoThu($thu2, $ma_phong_ban, $thu);
                if (!empty($lich)) {
                    // ✅ Tính offset chính xác: index 0-6 tương ứng +0 đến +6 ngày
                    $ngay_lam = date('Y-m-d', strtotime($thu2 . " +$index days"));
                    
                    $available_days[] = [
                        'thu' => $thu,
                        'ten_thu' => $this->getTenThuDayDu($thu),
                        'ngay' => date('d/m/Y', strtotime($ngay_lam)),
                        'so_nhan_vien' => count($lich)
                    ];
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => $available_days
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
     * Helper: Lấy tên thứ đầy đủ
     */
    private function getTenThuDayDu($thu) {
        $ten = [
            1=> 'Chủ Nhật',
            2 => 'Thứ Hai',
            3 => 'Thứ Ba',
            4 => 'Thứ Tư',
            5 => 'Thứ Năm',
            6 => 'Thứ Sáu',
            7 => 'Thứ Bảy'
        ];
        return $ten[$thu] ?? 'Thứ ' . $thu;
    }
}
?>