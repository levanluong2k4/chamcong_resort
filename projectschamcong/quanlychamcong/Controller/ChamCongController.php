<?php

require_once __DIR__ . '/../Model/chamcong/ChamCongModel.php';

class ChamCongController {
    private $model;
    
    public function __construct() {
        $this->model = new ChamCongModel();
    }
    
    public function index() {
        require_once __DIR__ . '/../View/ChamCong/index.php';
    }
    
    public function getDuLieuChamCong() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $ngay = $_GET['ngay'] ?? date('Y-m-d');
            $ma_phong_ban = $_GET['ma_phong_ban'] ?? null;
            
            $config = $this->model->getCauHinh();
            $schedules = $this->model->getLichLamViec($ngay, $ma_phong_ban);
            $attendance = $this->model->getChamCong($ngay, $ma_phong_ban);
            $leaves = $this->model->getDonNghiPhep($ngay);
            $holiday = $this->model->getNgayNghi($ngay);
            
            $result = [];
            foreach ($schedules as $schedule) {
                $ma_nv = $schedule['ma_nhan_vien'];
                
                $cc = $attendance[$ma_nv] ?? null;
                $gio_vao = $cc ? $cc['gio_vao'] : null;
                $gio_ra = $cc ? $cc['gio_ra'] : null;
                
                $isOnLeave = isset($leaves[$ma_nv]);
                $leaveInfo = $leaves[$ma_nv] ?? null;
                $isHoliday = $holiday !== null;
                
                $status = $this->tinhTrangThai(
                    $schedule['gio_bat_dau'],
                    $gio_vao,
                    $gio_ra,
                    $isOnLeave,
                    $isHoliday,
                    $config
                );
                
                $so_phut_tre = 0;
                if ($status['trang_thai'] === 'DI_TRE' && $gio_vao) {
                    $so_phut_tre = $this->tinhSoPhutTre(
                        $schedule['gio_bat_dau'],
                        $gio_vao
                    );
                }
                
                $tong_gio_lam = 0;
                if ($gio_vao && $gio_ra) {
                    $tong_gio_lam = $this->tinhTongGioLam(
                        $gio_vao,
                        $gio_ra,
                        $schedule['gio_bat_dau'],
                        $schedule['gio_ket_thuc'],
                        $config
                    );
                }
                
                $result[] = [
                    'ma_lich' => $schedule['ma_lich'],
                    'ma_nhan_vien' => $ma_nv,
                    'ho_ten' => $schedule['ho_ten'],
                    'email' => $schedule['email'],
                    'ma_phong_ban' => $schedule['ma_phong_ban'],
                    'ten_phong_ban' => $schedule['ten_phong_ban'],
                    'ten_ca' => $schedule['ten_ca'],
                    'gio_bat_dau' => $schedule['gio_bat_dau'],
                    'gio_ket_thuc' => $schedule['gio_ket_thuc'],
                    'gio_vao' => $gio_vao,
                    'gio_ra' => $gio_ra,
                    'trang_thai' => $status['trang_thai'],
                    'so_phut_tre' => $so_phut_tre,
                    'tong_gio_lam' => $tong_gio_lam,
                    'is_on_leave' => $isOnLeave,
                    'is_holiday' => $isHoliday,
                    'leave_info' => $leaveInfo,
                    'ghi_chu' => $status['ghi_chu']
                ];
            }
            
            $stats = $this->model->getThongKe($ngay, $ma_phong_ban);
            
            echo json_encode([
                'success' => true,
                'data' => $result,
                'statistics' => $stats,
                'config' => $config,
                'is_holiday' => $holiday,
                'ngay' => $ngay
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function getPhongBan() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $departments = $this->model->getPhongBan();
            
            echo json_encode([
                'success' => true,
                'data' => $departments
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function chamCongVao() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $ma_nhan_vien = $data['ma_nhan_vien'] ?? null;
            $ngay_lam = $data['ngay_lam'] ?? date('Y-m-d');
            $gio_vao = $data['gio_vao'] ?? date('H:i:s');
            
            if (!$ma_nhan_vien) {
                throw new Exception('Thiếu mã nhân viên');
            }
            
            $result = $this->model->chamCongVao($ma_nhan_vien, $ngay_lam, $gio_vao);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Chấm công vào thành công'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('Không thể chấm công');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function chamCongRa() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $ma_nhan_vien = $data['ma_nhan_vien'] ?? null;
            $ngay_lam = $data['ngay_lam'] ?? date('Y-m-d');
            $gio_ra = $data['gio_ra'] ?? date('H:i:s');
            
            if (!$ma_nhan_vien) {
                throw new Exception('Thiếu mã nhân viên');
            }
            
            $result = $this->model->chamCongRa($ma_nhan_vien, $ngay_lam, $gio_ra);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Chấm công ra thành công'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('Không thể chấm công');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    private function tinhTrangThai($gio_ca, $gio_vao, $gio_ra, $isOnLeave, $isHoliday, $config) {
        // 1. Kiểm tra ngày nghỉ lễ
        if ($isHoliday) {
            return ['trang_thai' => 'NGHI_PHEP', 'ghi_chu' => 'Ngày nghỉ lễ'];
        }
        
        // 2. Kiểm tra có đơn nghỉ phép
        if ($isOnLeave) {
            return ['trang_thai' => 'NGHI_PHEP', 'ghi_chu' => 'Có đơn nghỉ phép đã duyệt'];
        }
        
        // 3. Kiểm tra quên chấm công (chỉ có 1 trong 2)
        if (($gio_vao && !$gio_ra) || (!$gio_vao && $gio_ra)) {
            $ghi_chu = '';
            if ($gio_vao && !$gio_ra) {
                $ghi_chu = '⚠️ Quên chấm công RA';
            } else {
                $ghi_chu = '⚠️ Quên chấm công VÀO';
            }
            
            return ['trang_thai' => 'QUEN_CHAM_CONG', 'ghi_chu' => $ghi_chu];
        }
        
        // 4. Vắng mặt (không chấm gì cả)
        if (!$gio_vao && !$gio_ra) {
            return ['trang_thai' => 'VANG_MAT', 'ghi_chu' => '❌ Không có chấm công'];
        }
        
        // 5. Có chấm đủ cả vào và ra - Tính trạng thái
        $phut_ca = $this->timeToMinutes($gio_ca);
        $phut_vao = $this->timeToMinutes($gio_vao);
        $phut_tre = $phut_vao - $phut_ca;
        
        // Đi đúng giờ hoặc trong khoảng cho phép
        if ($phut_tre <= $config['SO_PHUT_DUOC_PHEP_TRE']) {
            $ghi_chu = '';
            if ($phut_tre < 0) {
                $ghi_chu = '✅ Đến sớm ' . abs($phut_tre) . ' phút';
            } else if ($phut_tre > 0) {
                $ghi_chu = '✅ Đến đúng giờ (trễ ' . $phut_tre . ' phút - trong khoảng cho phép)';
            } else {
                $ghi_chu = '✅ Đến đúng giờ';
            }
            
            return ['trang_thai' => 'DI_LAM', 'ghi_chu' => $ghi_chu];
        }
        
        // Đi trễ
        return ['trang_thai' => 'DI_TRE', 'ghi_chu' => '⏰ Trễ ' . $phut_tre . ' phút'];
    }
    private function timeToMinutes($time) {
        if (!$time) return 0;
        
        // Xử lý datetime format: 2025-12-24 13:51:49
        if (strpos($time, ' ') !== false) {
            $time = explode(' ', $time)[1]; // Lấy phần giờ
        }
        
        $parts = explode(':', $time);
        $hours = (int)$parts[0];
        $minutes = (int)$parts[1];
        
        return $hours * 60 + $minutes;
    }
    
    private function tinhSoPhutTre($gio_ca, $gio_vao) {
        $phut_ca = $this->timeToMinutes($gio_ca);
        $phut_vao = $this->timeToMinutes($gio_vao);
        
        return max(0, $phut_vao - $phut_ca);
    }
    
    private function tinhTongGioLam($gio_vao, $gio_ra, $gio_bat_dau_ca, $gio_ket_thuc_ca, $config) {
        if (!$gio_vao || !$gio_ra) {
            return 0;
        }
        
        $phut_vao = $this->timeToMinutes($gio_vao);
        $phut_ra = $this->timeToMinutes($gio_ra);
        $phut_ca_bat_dau = $this->timeToMinutes($gio_bat_dau_ca);
        $phut_ca_ket_thuc = $this->timeToMinutes($gio_ket_thuc_ca);
        
        // Xử lý ca qua đêm
        if ($phut_ca_ket_thuc < $phut_ca_bat_dau) {
            $phut_ca_ket_thuc += 24 * 60;
        }
        if ($phut_ra < $phut_vao) {
            $phut_ra += 24 * 60;
        }
        
        // Tính giờ vào thực tế
        if ($phut_vao < $phut_ca_bat_dau) {
            $phut_vao_thuc_te = $phut_ca_bat_dau;
        } else if ($phut_vao > $phut_ca_bat_dau + $config['SO_PHUT_DUOC_PHEP_TRE']) {
            $phut_vao_thuc_te = $phut_vao;
        } else {
            $phut_vao_thuc_te = $phut_ca_bat_dau;
        }
        
        // Tính giờ ra thực tế
        if ($phut_ra > $phut_ca_ket_thuc) {
            $phut_ra_thuc_te = $phut_ca_ket_thuc;
        } else if ($phut_ra < $phut_ca_ket_thuc - $config['SO_PHUT_DUOC_PHEP_VE_SOM']) {
            $phut_ra_thuc_te = $phut_ra;
        } else {
            $phut_ra_thuc_te = $phut_ca_ket_thuc;
        }
        
        $tong_phut = $phut_ra_thuc_te - $phut_vao_thuc_te;
        
        return round($tong_phut / 60, 2);
    }
}
?>