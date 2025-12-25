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
     * Lấy danh sách nhân viên xin nghỉ trong tuần
     */
    public function layDanhSachNghiPhepTrongTuan($thu2, $ma_phong_ban) {
        $sql = "SELECT 
                    dnp.ma_nhan_vien,
                    DATE(dnp.ngay_bat_dau) as ngay_nghi
                FROM donnghiphep dnp
                INNER JOIN nhanvien nv ON dnp.ma_nhan_vien = nv.ma_nhan_vien
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND dnp.trang_thai = 'DA_DUYET'
                
                AND DATE(dnp.ngay_bat_dau) BETWEEN '$thu2' 
                    AND DATE_ADD('$thu2', INTERVAL 5 DAY)
                ORDER BY dnp.ngay_bat_dau";
        
        $result = $this->select($sql);
        $nghi_phep = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ngay = $row['ngay_nghi'];
                $ma_nv = (int)$row['ma_nhan_vien'];
                
                if (!isset($nghi_phep[$ngay])) {
                    $nghi_phep[$ngay] = [];
                }
                $nghi_phep[$ngay][] = $ma_nv;
            }
        }
        
        return $nghi_phep;
    }
    
    /**
     * Kiểm tra lịch tuần đã tồn tại chưa
     */
    public function kiemTraLichTuanDaTonTai($thu2, $ma_phong_ban) {
        $thu7 = date('Y-m-d', strtotime($thu2 . ' +5 days'));
        
        $sql = "SELECT COUNT(*) as count
                FROM lichlamviec llv
                INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND llv.ngay_lam BETWEEN '$thu2' AND '$thu7'";
        
        $result = $this->select($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['count'] > 0;
        }
        
        return false;
    }
    
    /**
     * Lưu lịch làm việc tuần
     */
    public function luuLichTuan($ma_phong_ban, $thu2, $lich) {
        $this->conn->begin_transaction();
        
        try {
            $thu7 = date('Y-m-d', strtotime($thu2 . ' +5 days'));
            
            $sql_delete = "DELETE llv FROM lichlamviec llv
                          INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                          WHERE nv.ma_phong_ban = $ma_phong_ban
                          AND llv.ngay_lam BETWEEN '$thu2' AND '$thu7'";
            
            $this->execute($sql_delete);
            
            foreach ($lich as $ngay => $caList) {
                foreach ($caList as $ma_ca => $nhanVienList) {
                    foreach ($nhanVienList as $ma_nhan_vien) {
                        $tuan = ceil(date('d', strtotime($ngay)) / 7);
                        
                        $sql_insert = "INSERT INTO lichlamviec 
                                      (ma_nhan_vien, ma_ca, ngay_lam, tuan, ghi_chu) 
                                      VALUES 
                                      ($ma_nhan_vien, $ma_ca, '$ngay', $tuan, 'Lịch tuần tự động')";
                        
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
}
?>