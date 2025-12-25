<?php 
require_once __DIR__ . '/../Connect.php';

class LichCoDinhModel extends Connect {
    
    /**
     * Lấy danh sách nhân viên đang làm việc theo phòng ban
     * KÈM THEO thông tin ngày nghỉ
     */
    public function layDanhSachNhanVien($ma_phong_ban) {
        $sql = "SELECT 
                    nv.ma_nhan_vien,
                    nv.ho_ten,
                    nv.email,
                    nv.trang_thai,
                    pb.ten_phong_ban,
                    -- Lấy danh sách thứ nghỉ (NGHI_PHEP_TUAN)
                    GROUP_CONCAT(
                        DISTINCT CASE 
                            WHEN nn.loai_nghi = 'NGHI_PHEP_TUAN' AND nn.da_duyet = 1 
                            THEN nn.THU 
                        END
                    ) AS thu_nghi_phep,
                    -- Lấy danh sách khoảng ngày nghỉ (NGHI_LE, NGHI_TET)
                    GROUP_CONCAT(
                        DISTINCT CASE 
                            WHEN nn.loai_nghi IN ('NGHI_LE', 'NGHI_TET') AND nn.da_duyet = 1 
                            THEN CONCAT(DATE_FORMAT(nn.ngay_bat_dau, '%Y-%m-%d'), '|', DATE_FORMAT(nn.ngay_ket_thuc, '%Y-%m-%d'))
                        END
                        SEPARATOR ';'
                    ) AS khoang_ngay_nghi
                FROM nhanvien nv
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                LEFT JOIN ngaynghi nn ON nv.ma_nhan_vien = nn.ma_nhan_vien 
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND nv.trang_thai = 'DANG_LAM'
                GROUP BY nv.ma_nhan_vien, nv.ho_ten, nv.email, nv.trang_thai, pb.ten_phong_ban
                ORDER BY nv.ho_ten ASC";
        
        $result = $this->select($sql);
        $data = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Xử lý thu_nghi_phep thành mảng
                $thu_nghi = [];
                if (!empty($row['thu_nghi_phep'])) {
                    $thu_nghi = array_filter(explode(',', $row['thu_nghi_phep']));
                }
                $row['thu_nghi'] = $thu_nghi;
                
                // Xử lý khoang_ngay_nghi thành mảng
                $ngay_nghi = [];
                if (!empty($row['khoang_ngay_nghi'])) {
                    $khoang_list = explode(';', $row['khoang_ngay_nghi']);
                    foreach ($khoang_list as $khoang) {
                        if (!empty($khoang)) {
                            $parts = explode('|', $khoang);
                            if (count($parts) === 2) {
                                $ngay_nghi[] = [
                                    'tu_ngay' => $parts[0],
                                    'den_ngay' => $parts[1]
                                ];
                            }
                        }
                    }
                }
                $row['khoang_ngay_nghi_arr'] = $ngay_nghi;
                
                // Xóa field tạm
                unset($row['thu_nghi_phep']);
                unset($row['khoang_ngay_nghi']);
                
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
     * Lấy lịch cố định hiện tại của phòng ban
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
     * Lưu toàn bộ lịch cố định
     */
    public function luuLichCoDinh($ma_phong_ban, $lich) {
        $this->conn->begin_transaction();
        
        try {
            // 1. Xóa tất cả lịch cố định của phòng ban này
            $sql_delete = "DELETE lcd FROM lichlamvieccoding lcd
                          INNER JOIN nhanvien nv ON lcd.ma_nhan_vien = nv.ma_nhan_vien
                          WHERE nv.ma_phong_ban = $ma_phong_ban";
            
            $this->execute($sql_delete);
            
            // 2. Thêm lịch mới
            foreach ($lich as $thu => $caList) {
                foreach ($caList as $ma_ca => $nhanVienList) {
                    foreach ($nhanVienList as $ma_nhan_vien) {
                        $sql_insert = "INSERT INTO lichlamvieccoding 
                                      (ma_nhan_vien, thu_trong_tuan, ma_ca, ghi_chu) 
                                      VALUES 
                                      ($ma_nhan_vien, $thu, $ma_ca, 'Lịch cố định')";
                        
                        $this->execute($sql_insert);
                    }
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    
    /**
     * Xóa lịch cố định của 1 nhân viên
     */
    public function xoaLichCoDinh($ma_nhan_vien, $thu_trong_tuan, $ma_ca) {
        $sql = "DELETE FROM lichlamvieccoding 
                WHERE ma_nhan_vien = $ma_nhan_vien 
                AND thu_trong_tuan = $thu_trong_tuan 
                AND ma_ca = $ma_ca";
        
        return $this->execute($sql);
    }
}
?>