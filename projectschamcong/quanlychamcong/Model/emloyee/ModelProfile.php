<?php
require_once(__DIR__ . '/../Connect.php');

class ModelProfile extends Connect {
    
    public function getProfile($user_id) {
        $sql = "SELECT nv.*, pb.ten_phong_ban, ql.ho_ten as ten_quan_ly 
                FROM nhanvien nv 
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban 
                LEFT JOIN nhanvien ql ON nv.ma_nguoi_quan_ly = ql.ma_nhan_vien 
                WHERE nv.ma_nhan_vien = '$user_id'";
        
        $result = $this->select($sql);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getPersonalSchedule($user_id) {
        $sql = "SELECT l.*, c.ten_ca, c.gio_bat_dau, c.gio_ket_thuc 
                FROM lichlamviec l 
                JOIN calamviec c ON l.ma_ca = c.ma_ca 
                WHERE l.ma_nhan_vien = '$user_id' 
                ORDER BY l.ngay_lam DESC LIMIT 30"; 
        
        $result = $this->select($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getTotalOvertimeHours($user_id) {
        $sql = "SELECT SUM(ct.so_gio_dang_ky) as tong_gio 
                FROM chitiet_tang_ca ct 
                JOIN don_tang_ca d ON ct.ma_don_tang_ca = d.ma_don_tang_ca 
                WHERE ct.ma_nhan_vien = '$user_id' 
                AND d.trang_thai = 'DA_DUYET'";

        try {
            $result = $this->select($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['tong_gio'] != null ? $row['tong_gio'] : 0;
            }
        } catch (Exception $e) {
            return 0;
        }
        return 0;
    }

    public function getPersonalOvertimeSchedule($user_id) {
        $sql = "SELECT dtc.ngay_tang_ca, ct.so_gio_dang_ky, dtc.ly_do, c.ten_ca
                FROM chitiet_tang_ca ct 
                JOIN don_tang_ca dtc ON ct.ma_don_tang_ca = dtc.ma_don_tang_ca 
                LEFT JOIN calamviec c ON dtc.ma_ca = c.ma_ca
                WHERE ct.ma_nhan_vien = '$user_id' 
                AND dtc.trang_thai = 'DA_DUYET'
                ORDER BY dtc.ngay_tang_ca DESC";

        $result = $this->select($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getPersonalLeaves($user_id) {
        $sql = "SELECT * FROM donnghiphep 
                WHERE ma_nhan_vien = '$user_id' 
                AND trang_thai = 'DA_DUYET'
                ORDER BY ngay_bat_dau DESC";

        $result = $this->select($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // ============================================
    // THÊM CÁC HÀM MỚI CHO CHẤM CÔNG VÀ PHÉP
    // ============================================

    /**
     * Lấy thông tin số dư phép trong năm hiện tại
     */
    public function getLeaveBalance($user_id) {
        $current_year = date('Y');
        $current_month = date('n');
        
        $sql = "SELECT * FROM soduphep 
                WHERE ma_nhan_vien = '$user_id' 
                AND nam = $current_year 
                AND thang = $current_month
                LIMIT 1";
        
        $result = $this->select($sql);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        // Nếu chưa có bản ghi, trả về giá trị mặc định
        return [
            'so_ngay_phep_duoc_huong' => 12,
            'so_ngay_phep_da_dung' => 0,
            'so_ngay_phep_con_lai' => 12,
            'so_gio_tang_ca_tich_luy' => 0,
            'so_gio_tang_ca_con_lai' => 0,
            'so_ngay_cong' => 0
        ];
    }

    /**
     * Lấy lịch sử chấm công trong tháng hiện tại
     */
    public function getAttendanceHistory($user_id, $limit = 30) {
        $sql = "SELECT 
                    cc.*,
                    llv.ma_ca,
                    ca.ten_ca,
                    ca.gio_bat_dau as gio_ca_bat_dau,
                    ca.gio_ket_thuc as gio_ca_ket_thuc
                FROM chamcong cc
                LEFT JOIN lichlamviec llv ON cc.ma_nhan_vien = llv.ma_nhan_vien 
                    AND cc.ngay_lam = llv.ngay_lam
                LEFT JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
                WHERE cc.ma_nhan_vien = '$user_id'
                ORDER BY cc.ngay_lam DESC
                LIMIT $limit";
        
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
     * Thống kê chấm công trong tháng hiện tại
     */
    public function getAttendanceStats($user_id) {
        $current_month = date('Y-m');
        
        $sql = "SELECT 
                    COUNT(*) as tong_ngay_cham_cong,
                    SUM(CASE WHEN trang_thai = 'DI_LAM' THEN 1 ELSE 0 END) as ngay_di_lam,
                    SUM(CASE WHEN trang_thai = 'DI_TRE' THEN 1 ELSE 0 END) as ngay_di_tre,
                    SUM(CASE WHEN trang_thai = 'VE_SOM' THEN 1 ELSE 0 END) as ngay_ve_som,
                    SUM(CASE WHEN trang_thai = 'VANG_MAT' THEN 1 ELSE 0 END) as ngay_vang_mat,
                    SUM(CASE WHEN trang_thai IN ('NGHI_PHEP', 'NGHI_PHEP_DON', 'NGHI_PHEP_TUAN') THEN 1 ELSE 0 END) as ngay_nghi_phep,
                    SUM(CASE WHEN trang_thai = 'DI_LAM_NGAY_LE' THEN 1 ELSE 0 END) as ngay_lam_le,
                    SUM(tong_gio_lam) as tong_gio_lam,
                    SUM(so_phut_tre) as tong_phut_tre,
                    SUM(so_phut_ve_som) as tong_phut_ve_som
                FROM chamcong 
                WHERE ma_nhan_vien = '$user_id' 
                AND DATE_FORMAT(ngay_lam, '%Y-%m') = '$current_month'";
        
        $result = $this->select($sql);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return [
            'tong_ngay_cham_cong' => 0,
            'ngay_di_lam' => 0,
            'ngay_di_tre' => 0,
            'ngay_ve_som' => 0,
            'ngay_vang_mat' => 0,
            'ngay_nghi_phep' => 0,
            'ngay_lam_le' => 0,
            'tong_gio_lam' => 0,
            'tong_phut_tre' => 0,
            'tong_phut_ve_som' => 0
        ];
    }

    /**
     * Lấy lịch chấm công theo tháng (để hiển thị lịch)
     */
    public function getMonthlyAttendance($user_id, $year, $month) {
        $sql = "SELECT 
                    cc.*,
                    llv.ma_ca,
                    ca.ten_ca
                FROM chamcong cc
                LEFT JOIN lichlamviec llv ON cc.ma_nhan_vien = llv.ma_nhan_vien 
                    AND cc.ngay_lam = llv.ngay_lam
                LEFT JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
                WHERE cc.ma_nhan_vien = '$user_id'
                AND YEAR(cc.ngay_lam) = $year
                AND MONTH(cc.ngay_lam) = $month
                ORDER BY cc.ngay_lam ASC";
        
        $result = $this->select($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
?>