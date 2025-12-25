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
            for ($i = 0; $i < 6; $i++) {
                $ngay = date('Y-m-d', strtotime($thu2 . " +$i days"));
                $ngay_trong_tuan[] = [
                    'ngay' => $ngay,
                    'thu' => $i + 2,
                    'ngay_hien_thi' => date('d/m/Y', strtotime($ngay))
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'thu_2' => $thu2,
                    'thu_7' => date('Y-m-d', strtotime($thu2 . ' +5 days')),
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
     * API: Lấy lịch cố định và nghỉ phép
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
            
            $lich_co_dinh = $this->model->layLichCoDinh($ma_phong_ban);
            $nghi_phep = $this->model->layDanhSachNghiPhepTrongTuan($thu2, $ma_phong_ban);
            
            $lich_tuan = [];
            for ($i = 0; $i < 6; $i++) {
                $ngay = date('Y-m-d', strtotime($thu2 . " +$i days"));
                $thu = $i + 2;
                
                if (isset($lich_co_dinh[$thu])) {
                    $lich_tuan[$ngay] = [];
                    
                    foreach ($lich_co_dinh[$thu] as $ma_ca => $nhan_vien_list) {
                        $nv_nghi = $nghi_phep[$ngay] ?? [];
                        $nv_lam_viec = array_diff($nhan_vien_list, $nv_nghi);
                        
                        $lich_tuan[$ngay][$ma_ca] = array_values($nv_lam_viec);
                    }
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'lich_tuan' => $lich_tuan,
                    'nghi_phep' => $nghi_phep
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
     * API: Lưu lịch tuần
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
            
            $result = $this->model->luuLichTuan($ma_phong_ban, $thu2, $lich);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Tạo lịch tuần thành công!'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Lỗi khi tạo lịch tuần'
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
        // THÊM DÒNG NÀY
        if (ob_get_length()) ob_clean();
        
        try {
            $thu2 = $_GET['thu2'] ?? null;
            $ma_phong_ban = $_GET['ma_phong_ban'] ?? $_SESSION['ma_phong_ban'] ?? null;
            $thu = $_GET['thu'] ?? 2;
            
            if (!$thu2 || !$ma_phong_ban) {
                // XÓA json_encode, chỉ die với text
                die('Thiếu thông tin thu2 hoặc ma_phong_ban');
            }
            
            // Gọi model xuất Excel
            $this->excelModel->xuatExcelTheoThu($thu2, $ma_phong_ban, $thu);
            
        } catch (Exception $e) {
            // Chỉ die với text, không json
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
            
            for ($thu = 2; $thu <= 7; $thu++) {
                $lich = $this->excelModel->layLichTheoThu($thu2, $ma_phong_ban, $thu);
                if (!empty($lich)) {
                    $ngay_offset = $thu - 2;
                    $ngay_lam = date('Y-m-d', strtotime($thu2 . " +$ngay_offset days"));
                    
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