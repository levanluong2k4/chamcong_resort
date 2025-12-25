<?php
// Model/lich/DoiLichModel.php
require_once __DIR__ . '/../Connect.php';
class DoiLichModel extends Connect {
   
    /**
     * Lấy danh sách tất cả phòng ban
     */
    public function getAllPhongBan() {
        $sql = "SELECT ma_phong_ban, ten_phong_ban, mo_ta 
                FROM phongban 
                ORDER BY ten_phong_ban";
        return $this->select($sql);
    }
    
    /**
     * Lấy danh sách nhân viên theo phòng ban
     */
    public function getNhanVienByPhongBan($ma_phong_ban) {
        $ma_phong_ban = intval($ma_phong_ban);
        $sql = "SELECT 
                    nv.ma_nhan_vien,
                    nv.ho_ten,
                    nv.email,
                    nv.trang_thai,
                    pb.ten_phong_ban
                FROM nhanvien nv
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                WHERE nv.ma_phong_ban = $ma_phong_ban 
                AND nv.trang_thai IN ('DANG_LAM', 'TAM_NGHI')
                ORDER BY nv.ho_ten ASC";
        return $this->select($sql);
    }
    
    /**
     * Lấy thông tin các tuần trong tháng (chỉ tuần hiện tại và tuần sau nếu đã tạo lịch)
     */
    public function getWeeksInMonth($thang, $nam) {
        $thang = intval($thang);
        $nam = intval($nam);
        
        $sql = "SELECT DISTINCT 
                    tuan,
                    MIN(ngay_lam) as ngay_bat_dau,
                    MAX(ngay_lam) as ngay_ket_thuc,
                    COUNT(DISTINCT ma_nhan_vien) as so_nhan_vien
                FROM lichlamviec
                WHERE MONTH(ngay_lam) = $thang 
                AND YEAR(ngay_lam) = $nam
                AND ngay_lam >= CURDATE()
                GROUP BY tuan
                ORDER BY tuan
                LIMIT 2";
        return $this->select($sql);
    }
    
    /**
     * Lấy lịch tất cả nhân viên theo tuần và phòng ban
     */
    public function getLichTheoTuan($tuan, $thang, $nam, $ma_phong_ban) {
        $tuan = intval($tuan);
        $thang = intval($thang);
        $nam = intval($nam);
        $ma_phong_ban = intval($ma_phong_ban);
        
        $sql = "SELECT 
                    llv.ma_lich,
                    llv.ma_nhan_vien,
                    nv.ho_ten,
                    nv.email,
                    pb.ten_phong_ban,
                    llv.ngay_lam,
                    llv.tuan,
                    DAYOFWEEK(llv.ngay_lam) as thu,
                    CASE DAYOFWEEK(llv.ngay_lam)
                        WHEN 2 THEN 'T2'
                        WHEN 3 THEN 'T3'
                        WHEN 4 THEN 'T4'
                        WHEN 5 THEN 'T5'
                        WHEN 6 THEN 'T6'
                        WHEN 7 THEN 'T7'
                        WHEN 1 THEN 'CN'
                    END as ten_thu,
                    ca.ma_ca,
                    ca.ten_ca,
                    ca.gio_bat_dau,
                    ca.gio_ket_thuc,
                    ca.he_so_luong,
                    
                    -- Kiểm tra đã chấm công chưa
                    IF(cc.ma_cham_cong IS NOT NULL, 1, 0) as da_cham_cong,
                    
                    -- Kiểm tra ngày đã qua chưa
                    IF(llv.ngay_lam < CURDATE(), 1, 0) as da_qua,
                    
                    -- ❌ Kiểm tra có đơn nghỉ đã duyệt không (KHÔNG được đổi)
                    IF(dp.ma_don IS NOT NULL, 1, 0) as co_don_nghi,
                    dp.loai_nghi,
                    dp.ly_do as ly_do_nghi,
                    
                    -- ✅ Kiểm tra nghỉ lễ/Tết (được phép đổi - chỉ hiển thị thông tin)
                    IF(nn_le.ma_ngay_nghi IS NOT NULL, 1, 0) as la_ngay_nghi_le,
                    nn_le.loai_nghi as loai_nghi_le,
                    nn_le.ly_do as ly_do_nghi_le,
                    
                    -- ✅ Kiểm tra nghỉ phép tuần (được phép đổi - chỉ hiển thị thông tin)
                    IF(nn_tuan.ma_ngay_nghi IS NOT NULL, 1, 0) as la_ngay_nghi_tuan,
                    nn_tuan.ly_do as ly_do_nghi_tuan
                    
                FROM lichlamviec llv
                JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                LEFT JOIN chamcong cc ON cc.ma_nhan_vien = llv.ma_nhan_vien 
                    AND cc.ngay_lam = llv.ngay_lam
                
                -- ❌ JOIN đơn nghỉ phép (KHÔNG được đổi)
                LEFT JOIN donnghiphep dp ON dp.ma_nhan_vien = llv.ma_nhan_vien
                    AND dp.trang_thai = 'DA_DUYET'
                    AND llv.ngay_lam BETWEEN DATE(dp.ngay_bat_dau) AND DATE(dp.ngay_ket_thuc)
                
                -- ✅ JOIN nghỉ lễ/Tết (ĐƯỢC phép đổi)
                LEFT JOIN ngaynghi nn_le ON (
                    nn_le.ma_nhan_vien IS NULL
                    AND nn_le.da_duyet = 1
                    AND nn_le.loai_nghi IN ('NGHI_LE', 'NGHI_TET')
                    AND DAY(llv.ngay_lam) = DAY(nn_le.ngay_bat_dau)
                    AND MONTH(llv.ngay_lam) = MONTH(nn_le.ngay_bat_dau)
                )
                
                -- ✅ JOIN nghỉ phép tuần (ĐƯỢC phép đổi)
                LEFT JOIN ngaynghi nn_tuan ON (
                    nn_tuan.ma_nhan_vien = llv.ma_nhan_vien
                    AND nn_tuan.da_duyet = 1
                    AND nn_tuan.loai_nghi = 'NGHI_PHEP_TUAN'
                    AND nn_tuan.THU = CAST(DAYOFWEEK(llv.ngay_lam) AS CHAR)
                )
                
                WHERE llv.tuan = $tuan
                AND MONTH(llv.ngay_lam) = $thang
                AND YEAR(llv.ngay_lam) = $nam
                AND nv.ma_phong_ban = $ma_phong_ban
                AND nv.trang_thai IN ('DANG_LAM', 'TAM_NGHI')
                ORDER BY nv.ho_ten, llv.ngay_lam";
        
        return $this->select($sql);
    }
    /**
 * Lấy lịch đầy đủ bao gồm cả ngày nghỉ
 */
public function getLichTheoTuanDayDu($tuan, $thang, $nam, $ma_phong_ban) {
    $tuan = intval($tuan);
    $thang = intval($thang);
    $nam = intval($nam);
    $ma_phong_ban = intval($ma_phong_ban);
    
    $sql = "
    WITH RECURSIVE 
    -- ✅ Tạo danh sách 7 ngày trong tuần
    ngay_trong_tuan AS (
        SELECT 
            DATE_ADD(
                DATE_SUB(
                    STR_TO_DATE(CONCAT($nam, '-', LPAD($thang, 2, '0'), '-01'), '%Y-%m-%d'),
                    INTERVAL DAYOFWEEK(STR_TO_DATE(CONCAT($nam, '-', LPAD($thang, 2, '0'), '-01'), '%Y-%m-%d')) - 2 DAY
                ),
                INTERVAL (($tuan - 1) * 7) DAY
            ) as ngay_lam
        UNION ALL
        SELECT DATE_ADD(ngay_lam, INTERVAL 1 DAY)
        FROM ngay_trong_tuan
        WHERE DATE_ADD(ngay_lam, INTERVAL 1 DAY) <= DATE_ADD(
            DATE_SUB(
                STR_TO_DATE(CONCAT($nam, '-', LPAD($thang, 2, '0'), '-01'), '%Y-%m-%d'),
                INTERVAL DAYOFWEEK(STR_TO_DATE(CONCAT($nam, '-', LPAD($thang, 2, '0'), '-01'), '%Y-%m-%d')) - 2 DAY
            ),
            INTERVAL (($tuan - 1) * 7 + 6) DAY
        )
    ),
    -- ✅ Danh sách nhân viên trong phòng ban
    ds_nhan_vien AS (
        SELECT ma_nhan_vien, ho_ten, email, ma_phong_ban
        FROM nhanvien
        WHERE ma_phong_ban = $ma_phong_ban
        AND trang_thai IN ('DANG_LAM', 'TAM_NGHI')
    )
    
    -- ✅ CROSS JOIN để tạo full grid: Mỗi nhân viên x Mỗi ngày
    SELECT 
        llv.ma_lich,
        nv.ma_nhan_vien,
        nv.ho_ten,
        nv.email,
        pb.ten_phong_ban,
        ntt.ngay_lam,
        $tuan as tuan,
        DAYOFWEEK(ntt.ngay_lam) as thu,
        CASE DAYOFWEEK(ntt.ngay_lam)
            WHEN 2 THEN 'T2'
            WHEN 3 THEN 'T3'
            WHEN 4 THEN 'T4'
            WHEN 5 THEN 'T5'
            WHEN 6 THEN 'T6'
            WHEN 7 THEN 'T7'
            WHEN 1 THEN 'CN'
        END as ten_thu,
        
        -- Thông tin ca (NULL nếu không có lịch)
        ca.ma_ca,
        ca.ten_ca,
        ca.gio_bat_dau,
        ca.gio_ket_thuc,
        ca.he_so_luong,
        
        -- Kiểm tra đã chấm công
        IF(cc.ma_cham_cong IS NOT NULL, 1, 0) as da_cham_cong,
        
        -- Kiểm tra ngày đã qua
        IF(ntt.ngay_lam < CURDATE(), 1, 0) as da_qua,
        
        -- ❌ Đơn nghỉ phép (KHÔNG được đổi)
        IF(dp.ma_don IS NOT NULL, 1, 0) as co_don_nghi,
        dp.loai_nghi,
        dp.ly_do as ly_do_nghi,
        
        -- ✅ Nghỉ lễ/Tết (ĐƯỢC đổi)
        IF(nn_le.ma_ngay_nghi IS NOT NULL, 1, 0) as la_ngay_nghi_le,
        nn_le.loai_nghi as loai_nghi_le,
        nn_le.ly_do as ly_do_nghi_le,
        
        -- ✅ Nghỉ phép tuần (ĐƯỢC đổi)
        IF(nn_tuan.ma_ngay_nghi IS NOT NULL, 1, 0) as la_ngay_nghi_tuan,
        nn_tuan.ly_do as ly_do_nghi_tuan
        
    FROM ngay_trong_tuan ntt
    CROSS JOIN ds_nhan_vien nv
    LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
    
    -- LEFT JOIN lịch làm việc (có thể NULL)
    LEFT JOIN lichlamviec llv ON llv.ma_nhan_vien = nv.ma_nhan_vien 
        AND llv.ngay_lam = ntt.ngay_lam
    LEFT JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
    
    -- LEFT JOIN chấm công
    LEFT JOIN chamcong cc ON cc.ma_nhan_vien = nv.ma_nhan_vien 
        AND cc.ngay_lam = ntt.ngay_lam
    
    -- ❌ Đơn nghỉ phép
    LEFT JOIN donnghiphep dp ON dp.ma_nhan_vien = nv.ma_nhan_vien
        AND dp.trang_thai = 'DA_DUYET'
        AND ntt.ngay_lam BETWEEN DATE(dp.ngay_bat_dau) AND DATE(dp.ngay_ket_thuc)
    
    -- ✅ Nghỉ lễ/Tết
    LEFT JOIN ngaynghi nn_le ON (
        nn_le.ma_nhan_vien IS NULL
        AND nn_le.da_duyet = 1
        AND nn_le.loai_nghi IN ('NGHI_LE', 'NGHI_TET')
        AND DAY(ntt.ngay_lam) = DAY(nn_le.ngay_bat_dau)
        AND MONTH(ntt.ngay_lam) = MONTH(nn_le.ngay_bat_dau)
    )
    
    -- ✅ Nghỉ phép tuần
    LEFT JOIN ngaynghi nn_tuan ON (
        nn_tuan.ma_nhan_vien = nv.ma_nhan_vien
        AND nn_tuan.da_duyet = 1
        AND nn_tuan.loai_nghi = 'NGHI_PHEP_TUAN'
        AND nn_tuan.THU = CAST(DAYOFWEEK(ntt.ngay_lam) AS CHAR)
    )
    
    ORDER BY nv.ho_ten, ntt.ngay_lam
    ";
    
    return $this->select($sql);
}
    
    /**
     * Kiểm tra lịch có thể đổi không (chưa chấm công và chưa qua)
     */
    public function kiemTraCoTheDoiLich($ma_lich) {
        $ma_lich = intval($ma_lich);
        
        $sql = "SELECT 
                    llv.ma_lich,
                    llv.ngay_lam,
                    llv.ma_nhan_vien,
                    IF(cc.ma_cham_cong IS NOT NULL, 1, 0) as da_cham_cong,
                    IF(llv.ngay_lam < CURDATE(), 1, 0) as da_qua
                FROM lichlamviec llv
                LEFT JOIN chamcong cc ON cc.ma_nhan_vien = llv.ma_nhan_vien 
                    AND cc.ngay_lam = llv.ngay_lam
                WHERE llv.ma_lich = $ma_lich";
        
        $result = $this->select($sql);
        if ($result && $row = $result->fetch_assoc()) {
            // Chỉ được đổi nếu chưa chấm công VÀ chưa qua ngày
            return ($row['da_cham_cong'] == 0 && $row['da_qua'] == 0);
        }
        return false;
    }
    
    /**
     * Đổi ca giữa 2 lịch làm việc
     */
    public function doiCaGiua2Lich($ma_lich_1, $ma_lich_2) {
        $ma_lich_1 = intval($ma_lich_1);
        $ma_lich_2 = intval($ma_lich_2);
        
        // Kiểm tra cả 2 lịch có thể đổi không
        if (!$this->kiemTraCoTheDoiLich($ma_lich_1)) {
            return ['success' => false, 'message' => 'Lịch 1 đã chấm công hoặc đã qua ngày, không thể đổi'];
        }
        
        if (!$this->kiemTraCoTheDoiLich($ma_lich_2)) {
            return ['success' => false, 'message' => 'Lịch 2 đã chấm công hoặc đã qua ngày, không thể đổi'];
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Lấy thông tin 2 lịch
            $sql1 = "SELECT * FROM lichlamviec WHERE ma_lich = $ma_lich_1";
            $sql2 = "SELECT * FROM lichlamviec WHERE ma_lich = $ma_lich_2";
            
            $result1 = $this->select($sql1);
            $result2 = $this->select($sql2);
            
            $lich1 = $result1->fetch_assoc();
            $lich2 = $result2->fetch_assoc();
            
            if (!$lich1 || !$lich2) {
                throw new Exception("Không tìm thấy lịch làm việc");
            }
            
            // Kiểm tra cùng thứ
            if (date('N', strtotime($lich1['ngay_lam'])) != date('N', strtotime($lich2['ngay_lam']))) {
                throw new Exception("Chỉ được đổi ca cùng thứ trong tuần");
            }
            
            // Đổi ca cho nhau
            $update1 = "UPDATE lichlamviec SET 
                        ma_ca = {$lich2['ma_ca']},
                        ma_lich_co_dinh = NULL,
                        ghi_chu = CONCAT(
                            IFNULL(ghi_chu, ''), 
                            ' | Đổi ca với NV#{$lich2['ma_nhan_vien']} lúc ', NOW()
                        )
                        WHERE ma_lich = $ma_lich_1";
            
            $update2 = "UPDATE lichlamviec SET 
                        ma_ca = {$lich1['ma_ca']},
                        ma_lich_co_dinh = NULL,
                        ghi_chu = CONCAT(
                            IFNULL(ghi_chu, ''), 
                            ' | Đổi ca với NV#{$lich1['ma_nhan_vien']} lúc ', NOW()
                        )
                        WHERE ma_lich = $ma_lich_2";
            
            if (!$this->execute($update1) || !$this->execute($update2)) {
                throw new Exception("Lỗi cập nhật database");
            }
            
            $this->conn->commit();
            return ['success' => true, 'message' => 'Đổi ca thành công'];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Lấy tất cả ca làm việc
     */
    public function getAllCaLamViec() {
        $sql = "SELECT * FROM calamviec ORDER BY gio_bat_dau";
        return $this->select($sql);
    }
    
    /**
     * Lấy phòng ban của nhân viên
     */
    public function getPhongBanByNhanVien($ma_nhan_vien) {
        $ma_nhan_vien = intval($ma_nhan_vien);
        $sql = "SELECT ma_phong_ban FROM nhanvien WHERE ma_nhan_vien = $ma_nhan_vien";
        $result = $this->select($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['ma_phong_ban'];
        }
        return null;
    }
    /**
 * Chuyển ca từ nhân viên có lịch sang nhân viên OFF
 */
public function chuyenCaSangNguoiOff($ma_lich_nguon, $ma_nv_nguon, $ma_nv_nhan, $ngay_lam) {
    $ma_lich_nguon = intval($ma_lich_nguon);
    $ma_nv_nguon = intval($ma_nv_nguon);
    $ma_nv_nhan = intval($ma_nv_nhan);
    
    // Kiểm tra lịch nguồn có thể đổi không
    if (!$this->kiemTraCoTheDoiLich($ma_lich_nguon)) {
        return ['success' => false, 'message' => 'Lịch nguồn đã chấm công hoặc đã qua ngày'];
    }
    
    $this->conn->begin_transaction();
    
    try {
        // Lấy thông tin lịch nguồn
        $sql = "SELECT * FROM lichlamviec WHERE ma_lich = $ma_lich_nguon";
        $result = $this->select($sql);
        $lichNguon = $result->fetch_assoc();
        
        if (!$lichNguon) {
            throw new Exception("Không tìm thấy lịch làm việc nguồn");
        }
        
        // ✅ Bước 1: Tạo lịch mới cho nhân viên nhận (người OFF)
        $insertSql = "INSERT INTO lichlamviec 
                     (ma_nhan_vien, ma_ca, ngay_lam, tuan, ma_lich_co_dinh, ghi_chu)
                     VALUES 
                     ($ma_nv_nhan, {$lichNguon['ma_ca']}, '{$lichNguon['ngay_lam']}', 
                      {$lichNguon['tuan']}, NULL, 
                      'Nhận ca từ NV#{$ma_nv_nguon} lúc " . date('Y-m-d H:i:s') . "')";
        
        if (!$this->execute($insertSql)) {
            throw new Exception("Lỗi tạo lịch mới cho nhân viên nhận");
        }
        
        // ✅ Bước 2: Xóa lịch của nhân viên nguồn (chuyển thành OFF)
        $deleteSql = "DELETE FROM lichlamviec WHERE ma_lich = $ma_lich_nguon";
        
        if (!$this->execute($deleteSql)) {
            throw new Exception("Lỗi xóa lịch của nhân viên chuyển ca");
        }
        
        $this->conn->commit();
        return ['success' => true, 'message' => 'Chuyển ca thành công'];
        
    } catch (Exception $e) {
        $this->conn->rollback();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
}
?>
