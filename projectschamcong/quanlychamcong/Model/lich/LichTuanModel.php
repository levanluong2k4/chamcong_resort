<?php 
require_once __DIR__ . '/../Connect.php';

class LichTuanModel extends Connect {
    
    /**
     * Lấy ngày Thứ 2 của tuần tiếp theo
     */
    public function layThu2TuanTiepTheo() {
        $sql = "SELECT DATE_ADD(CURDATE(), INTERVAL (9 - DAYOFWEEK(CURDATE())) DAY) as thu_2";
        $result = $this->select($sql);
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row['thu_2'];
        }
        return null;
    }
    
    public function kiemTraLichTuanDaTonTai($thu2, $ma_phong_ban) {
        // ✅ SỬA: Bao gồm cả Chủ Nhật (+6 ngày thay vì +5)
        $chu_nhat = date('Y-m-d', strtotime($thu2 . ' +6 days'));
        
        $sql = "SELECT COUNT(*) as count
                FROM lichlamviec llv
                INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND llv.ngay_lam BETWEEN '$thu2' AND '$chu_nhat'";
        
        $result = $this->select($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['count'] > 0;
        }
        
        return false;
    }
    
    /**
     * ✅ LOAD LỊCH ĐÃ TẠO (CHẾ ĐỘ SỬA) - BAO GỒM CHỦ NHẬT
     */
    public function layLichTuanDaTao($thu2, $ma_phong_ban) {
        $chu_nhat = date('Y-m-d', strtotime($thu2 . ' +6 days')); // ✅ +6 ngày
        
        $sql = "SELECT 
                    llv.ngay_lam,
                    llv.ma_ca,
                    llv.ma_nhan_vien
                FROM lichlamviec llv
                INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND llv.ngay_lam BETWEEN '$thu2' AND '$chu_nhat'
                AND nv.trang_thai = 'DANG_LAM'
                ORDER BY llv.ngay_lam, llv.ma_ca";
        
        $result = $this->select($sql);
        $lich_tuan = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ngay = $row['ngay_lam'];
                $ca = (int)$row['ma_ca'];
                $nv = (int)$row['ma_nhan_vien'];
                
                if (!isset($lich_tuan[$ngay])) {
                    $lich_tuan[$ngay] = [];
                }
                if (!isset($lich_tuan[$ngay][$ca])) {
                    $lich_tuan[$ngay][$ca] = [];
                }
                
                $lich_tuan[$ngay][$ca][] = $nv;
            }
        }
        
        $nghi_phep = $this->layDanhSachNghiPhepTrongTuan($thu2, $ma_phong_ban);
        
        return [
            'lich_tuan' => $lich_tuan,
            'nghi_phep' => $nghi_phep
        ];
    }
    
    /**
     * ✅ TẠO LỊCH MỚI TỪ LỊCH CỐ ĐỊNH - BAO GỒM CHỦ NHẬT
     */
    public function layLichTuanTuLichCoDinh($thu2, $ma_phong_ban) {
        $lich_co_dinh = $this->layLichCoDinh($ma_phong_ban);
        $nghi_phep = $this->layDanhSachNghiPhepTrongTuan($thu2, $ma_phong_ban);
        
        $lich_tuan = [];
        
        // ✅ Tạo lịch cho 7 ngày (Thứ 2 -> Chủ Nhật)
        for ($i = 0; $i < 7; $i++) {
            $ngay = date('Y-m-d', strtotime($thu2 . " +$i days"));
            
            // ✅ Map ngày trong tuần
            // $i = 0 -> Thứ 2 (thu = 2)
            // $i = 1 -> Thứ 3 (thu = 3)
            // ...
            // $i = 5 -> Thứ 7 (thu = 7)
            // $i = 6 -> Chủ Nhật (thu = 1 hoặc 8)
            
            if ($i == 6) {
                // Chủ Nhật - Kiểm tra cả thu = 1 và thu = 8
                $nhanVienChuNhat = [];
                
                if (isset($lich_co_dinh[1])) {
                    $nhanVienChuNhat = $lich_co_dinh[1];
                } elseif (isset($lich_co_dinh[8])) {
                    $nhanVienChuNhat = $lich_co_dinh[8];
                }
                
                if (!empty($nhanVienChuNhat)) {
                    $lich_tuan[$ngay] = [];
                    foreach ($nhanVienChuNhat as $ma_ca => $nhan_vien_list) {
                        $lich_tuan[$ngay][$ma_ca] = array_values($nhan_vien_list);
                    }
                }
            } else {
                $thu = $i + 2; // Thứ 2-7
                
                if (isset($lich_co_dinh[$thu])) {
                    $lich_tuan[$ngay] = [];
                    
                    foreach ($lich_co_dinh[$thu] as $ma_ca => $nhan_vien_list) {
                        $lich_tuan[$ngay][$ma_ca] = array_values($nhan_vien_list);
                    }
                }
            }
        }
        
        return [
            'lich_tuan' => $lich_tuan,
            'nghi_phep' => $nghi_phep
        ];
    }
    
    /**
     * Lấy danh sách nhân viên theo phòng ban
     */
    public function layDanhSachNhanVien($ma_phong_ban) {
        $sql = "SELECT 
                    nv.ma_nhan_vien,
                    nv.ho_ten,
                    nv.email,
                    nv.trang_thai,
                    pb.ten_phong_ban
                FROM nhanvien nv
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND nv.trang_thai = 'DANG_LAM'
                ORDER BY nv.ho_ten ASC";
        
        $result = $this->select($sql);
        $data = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    /**
     * Lấy danh sách ca làm việc
     */
    public function layDanhSachCa() {
        $sql = "SELECT 
                    ma_ca,
                    ten_ca,
                    gio_bat_dau,
                    gio_ket_thuc,
                    he_so_luong
                FROM calamviec
                ORDER BY ma_ca ASC";
        
        $result = $this->select($sql);
        $data = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    /**
     * Lấy lịch cố định của phòng ban
     */
    public function layLichCoDinh($ma_phong_ban) {
        $sql = "SELECT 
                    lcd.ma_nhan_vien,
                    lcd.thu_trong_tuan,
                    lcd.ma_ca
                FROM lichlamvieccoding lcd
                INNER JOIN nhanvien nv ON lcd.ma_nhan_vien = nv.ma_nhan_vien
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND nv.trang_thai = 'DANG_LAM'
                ORDER BY lcd.thu_trong_tuan, lcd.ma_ca";
        
        $result = $this->select($sql);
        $lich = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $thu = $row['thu_trong_tuan'];
                $ca = $row['ma_ca'];
                $nv = (int)$row['ma_nhan_vien'];
                
                if (!isset($lich[$thu])) {
                    $lich[$thu] = [];
                }
                if (!isset($lich[$thu][$ca])) {
                    $lich[$thu][$ca] = [];
                }
                
                $lich[$thu][$ca][] = $nv;
            }
        }
        
        return $lich;
    }
    
    /**
     * ✅ Lấy danh sách nhân viên nghỉ phép trong tuần - BAO GỒM CHỦ NHẬT
     */
    public function layDanhSachNghiPhepTrongTuan($thu2, $ma_phong_ban) {
        $chu_nhat = date('Y-m-d', strtotime($thu2 . ' +6 days')); // ✅ +6 ngày
        
        $sql = "SELECT 
                    dp.ma_nhan_vien,
                    DATE(dp.ngay_bat_dau) as ngay_bat_dau,
                    DATE(dp.ngay_ket_thuc) as ngay_ket_thuc
                FROM donnghiphep dp
                INNER JOIN nhanvien nv ON dp.ma_nhan_vien = nv.ma_nhan_vien
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND dp.trang_thai = 'DA_DUYET'
                AND (
                    DATE(dp.ngay_bat_dau) BETWEEN '$thu2' AND '$chu_nhat'
                    OR DATE(dp.ngay_ket_thuc) BETWEEN '$thu2' AND '$chu_nhat'
                    OR ('$thu2' BETWEEN DATE(dp.ngay_bat_dau) AND DATE(dp.ngay_ket_thuc))
                )";
        
        $result = $this->select($sql);
        $nghi_phep = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ma_nv = (int)$row['ma_nhan_vien'];
                $ngay_bat_dau = strtotime($row['ngay_bat_dau']);
                $ngay_ket_thuc = strtotime($row['ngay_ket_thuc']);
                
                for ($date = $ngay_bat_dau; $date <= $ngay_ket_thuc; $date += 86400) {
                    $ngay = date('Y-m-d', $date);
                    
                    if ($ngay >= $thu2 && $ngay <= $chu_nhat) {
                        if (!isset($nghi_phep[$ngay])) {
                            $nghi_phep[$ngay] = [];
                        }
                        if (!in_array($ma_nv, $nghi_phep[$ngay])) {
                            $nghi_phep[$ngay][] = $ma_nv;
                        }
                    }
                }
            }
        }
        
        return $nghi_phep;
    }
    
    /**
     * ✅ Lưu lịch tuần - BAO GỒM CHỦ NHẬT
     */
    public function luuLichTuan($ma_phong_ban, $thu2, $lich) {
        $this->conn->begin_transaction();
        
        try {
            $chu_nhat = date('Y-m-d', strtotime($thu2 . ' +6 days')); // ✅ +6 ngày
            
            // Xóa lịch cũ
            $sql_delete = "DELETE llv FROM lichlamviec llv
                          INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                          WHERE nv.ma_phong_ban = $ma_phong_ban
                          AND llv.ngay_lam BETWEEN '$thu2' AND '$chu_nhat'";
            
            $this->execute($sql_delete);
            
            // Thêm lịch mới
            foreach ($lich as $ngay => $caList) {
                foreach ($caList as $ma_ca => $nhanVienList) {
                    foreach ($nhanVienList as $ma_nhan_vien) {
                        $tuan = ceil(date('d', strtotime($ngay)) / 7);
                        
                        $sql_insert = "INSERT INTO lichlamviec 
                                      (ma_nhan_vien, ma_ca, ngay_lam, tuan, ghi_chu) 
                                      VALUES 
                                      ($ma_nhan_vien, $ma_ca, '$ngay', $tuan, 
                                       'Lịch tuần (tạo/sửa lúc " . date('Y-m-d H:i:s') . ")')";
                        
                        $this->execute($sql_insert);
                    }
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Lỗi lưu lịch tuần: " . $e->getMessage());
            return false;
        }
    }
}
?>