<?php


require_once(__DIR__ . '/../Connect.php');

class ChamCongModel extends Connect {
    
    // Lấy cấu hình hệ thống
    public function getCauHinh() {
        $sql = "SELECT ten_tham_so, gia_tri 
                FROM cauhinh 
                WHERE ten_tham_so IN ('SO_PHUT_DUOC_PHEP_TRE', 'SO_PHUT_DUOC_PHEP_VE_SOM')";
        
        $result = $this->select($sql);
        $config = [];
        
        while ($row = $result->fetch_assoc()) {
            $config[$row['ten_tham_so']] = (int)$row['gia_tri'];
        }
        
        return $config;
    }
    
    // Lấy danh sách phòng ban
    public function getPhongBan() {
        $sql = "SELECT ma_phong_ban, ten_phong_ban 
                FROM phongban 
                ORDER BY ten_phong_ban";
        
        $result = $this->select($sql);
        $departments = [];
        
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        
        return $departments;
    }
    
    // Lấy lịch làm việc theo ngày
    public function getLichLamViec($ngay, $ma_phong_ban = null) {
        $sql = "SELECT 
                    llv.ma_lich,
                    llv.ma_nhan_vien,
                    nv.ho_ten,
                    nv.email,
                    nv.trang_thai as trang_thai_nv,
                    pb.ma_phong_ban,
                    pb.ten_phong_ban,
                    llv.ma_ca,
                    ca.ten_ca,
                    ca.gio_bat_dau,
                    ca.gio_ket_thuc,
                    ca.he_so_luong,
                    llv.ngay_lam,
                    llv.ghi_chu
                FROM lichlamviec llv
                INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                INNER JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                WHERE llv.ngay_lam = ?
                AND nv.trang_thai != 'NGHI_VIEC'";
        
        if ($ma_phong_ban) {
            $sql .= " AND pb.ma_phong_ban = ?";
        }
        
        $sql .= " ORDER BY pb.ten_phong_ban, nv.ho_ten";
        
        $stmt = $this->conn->prepare($sql);
        
        if ($ma_phong_ban) {
            $stmt->bind_param("si", $ngay, $ma_phong_ban);
        } else {
            $stmt->bind_param("s", $ngay);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $schedules = [];
        while ($row = $result->fetch_assoc()) {
            $schedules[] = $row;
        }
        
        return $schedules;
    }
    
    // Lấy thông tin chấm công theo ngày
    public function getChamCong($ngay, $ma_phong_ban = null) {
        $sql = "SELECT 
                    cc.ma_cham_cong,
                    cc.ma_nhan_vien,
                    cc.ngay_lam,
                    TIME(cc.gio_vao) as gio_vao,
                    TIME(cc.gio_ra) as gio_ra,
                    cc.trang_thai,
                    cc.so_phut_tre,
                    cc.so_phut_ve_som,
                    cc.tong_gio_lam,
                    cc.ghi_chu
                FROM chamcong cc
                INNER JOIN nhanvien nv ON cc.ma_nhan_vien = nv.ma_nhan_vien
                WHERE cc.ngay_lam = ?";
        
        if ($ma_phong_ban) {
            $sql .= " AND nv.ma_phong_ban = ?";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($ma_phong_ban) {
            $stmt->bind_param("si", $ngay, $ma_phong_ban);
        } else {
            $stmt->bind_param("s", $ngay);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $attendance = [];
        while ($row = $result->fetch_assoc()) {
            $attendance[$row['ma_nhan_vien']] = $row;
        }
        
        return $attendance;
    }
    // Kiểm tra đơn nghỉ phép
    public function getDonNghiPhep($ngay) {
        $sql = "SELECT 
                    dp.ma_don,
                    dp.ma_nhan_vien,
                    dp.loai_nghi,
                    DATE(dp.ngay_bat_dau) as ngay_bat_dau,
                    DATE(dp.ngay_ket_thuc) as ngay_ket_thuc,
                    dp.ly_do
                FROM donnghiphep dp
                WHERE dp.trang_thai = 'DA_DUYET'
                AND ? BETWEEN DATE(dp.ngay_bat_dau) AND DATE(dp.ngay_ket_thuc)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $ngay);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $leaves = [];
        while ($row = $result->fetch_assoc()) {
            $leaves[$row['ma_nhan_vien']] = $row;
        }
        
        return $leaves;
    }
    
    // Kiểm tra ngày nghỉ lễ
    public function getNgayNghi($ngay) {
        $sql = "SELECT 
                    ma_ngay_nghi,
                    loai_nghi,
                    ly_do
                FROM ngaynghi
                WHERE ma_nhan_vien IS NULL
                AND da_duyet = 1
                AND ? BETWEEN ngay_bat_dau AND ngay_ket_thuc";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $ngay);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    // Tạo hoặc cập nhật chấm công
    public function saveChamCong($data) {
        $sql = "INSERT INTO chamcong 
                (ma_nhan_vien, ngay_lam, gio_vao, gio_ra, trang_thai, 
                 so_phut_tre, so_phut_ve_som, tong_gio_lam, ghi_chu)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                gio_vao = VALUES(gio_vao),
                gio_ra = VALUES(gio_ra),
                trang_thai = VALUES(trang_thai),
                so_phut_tre = VALUES(so_phut_tre),
                so_phut_ve_som = VALUES(so_phut_ve_som),
                tong_gio_lam = VALUES(tong_gio_lam),
                ghi_chu = VALUES(ghi_chu)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "issssiiis",
            $data['ma_nhan_vien'],
            $data['ngay_lam'],
            $data['gio_vao'],
            $data['gio_ra'],
            $data['trang_thai'],
            $data['so_phut_tre'],
            $data['so_phut_ve_som'],
            $data['tong_gio_lam'],
            $data['ghi_chu']
        );
        
        return $stmt->execute();
    }
    
    // Chấm công vào
    public function chamCongVao($ma_nhan_vien, $ngay_lam, $gio_vao) {
        $sql = "INSERT INTO chamcong 
                (ma_nhan_vien, ngay_lam, gio_vao, trang_thai)
                VALUES (?, ?, ?, 'DI_LAM')
                ON DUPLICATE KEY UPDATE
                gio_vao = VALUES(gio_vao)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $ma_nhan_vien, $ngay_lam, $gio_vao);
        
        return $stmt->execute();
    }
    
    // Chấm công ra
    public function chamCongRa($ma_nhan_vien, $ngay_lam, $gio_ra) {
        $sql = "UPDATE chamcong 
                SET gio_ra = ?
                WHERE ma_nhan_vien = ? 
                AND ngay_lam = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $gio_ra, $ma_nhan_vien, $ngay_lam);
        
        return $stmt->execute();
    }
    
    // Lấy thống kê chấm công
    public function getThongKe($ngay, $ma_phong_ban = null) {
        $sql = "SELECT 
                    COUNT(DISTINCT llv.ma_nhan_vien) as tong_nhan_vien,
                    
                    -- Đi làm: Có chấm đủ 2 lần và đúng giờ
                    COUNT(DISTINCT CASE 
                        WHEN cc.trang_thai = 'DI_LAM' 
                        AND cc.gio_vao IS NOT NULL 
                        AND cc.gio_ra IS NOT NULL 
                        THEN cc.ma_nhan_vien 
                    END) as di_lam,
                    
                    -- Đi trễ: Có chấm đủ nhưng trễ
                    COUNT(DISTINCT CASE 
                        WHEN cc.trang_thai = 'DI_TRE' 
                        AND cc.gio_vao IS NOT NULL 
                        AND cc.gio_ra IS NOT NULL 
                        THEN cc.ma_nhan_vien 
                    END) as di_tre,
                    
                    -- Về sớm: Có chấm đủ nhưng về sớm
                    COUNT(DISTINCT CASE 
                        WHEN cc.trang_thai = 'VE_SOM' 
                        AND cc.gio_vao IS NOT NULL 
                        AND cc.gio_ra IS NOT NULL 
                        THEN cc.ma_nhan_vien 
                    END) as ve_som,
                    
                    -- Vắng mặt: Không chấm gì cả hoặc trạng thái VANG_MAT
                    COUNT(DISTINCT CASE 
                        WHEN (cc.ma_cham_cong IS NULL) 
                        OR (cc.trang_thai = 'VANG_MAT' 
                            AND cc.gio_vao IS NULL 
                            AND cc.gio_ra IS NULL)
                        THEN llv.ma_nhan_vien 
                    END) as vang_mat,
                    
                    -- Nghỉ phép: Có đơn nghỉ đã duyệt
                    COUNT(DISTINCT CASE 
                        WHEN cc.trang_thai = 'NGHI_PHEP' 
                        THEN cc.ma_nhan_vien 
                    END) as nghi_phep,
                     COUNT(DISTINCT CASE 
                        WHEN cc.trang_thai = 'NGHI_PHEP_DON' 
                        THEN cc.ma_nhan_vien 
                    END) as nghi_phep_don,
                    
                    -- Quên chấm công: Chỉ có 1 trong 2 (vào hoặc ra)
                    COUNT(DISTINCT CASE 
                        WHEN cc.ma_cham_cong IS NOT NULL 
                        AND (
                            (cc.gio_vao IS NOT NULL AND cc.gio_ra IS NULL) 
                            OR (cc.gio_vao IS NULL AND cc.gio_ra IS NOT NULL)
                        )
                        THEN cc.ma_nhan_vien 
                    END) as quen_cham_cong
                    
                FROM lichlamviec llv
                INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                LEFT JOIN chamcong cc ON llv.ma_nhan_vien = cc.ma_nhan_vien 
                    AND llv.ngay_lam = cc.ngay_lam
                WHERE llv.ngay_lam = ?
                AND nv.trang_thai != 'NGHI_VIEC'";
        
        if ($ma_phong_ban) {
            $sql .= " AND nv.ma_phong_ban = ?";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($ma_phong_ban) {
            $stmt->bind_param("si", $ngay, $ma_phong_ban);
        } else {
            $stmt->bind_param("s", $ngay);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    public function getSoDuPhep($ngay, $ma_phong_ban = null) {
        $nam = date('Y', strtotime($ngay));
        $thang = date('m', strtotime($ngay));
        
        $sql = "SELECT 
                    sdp.ma_nhan_vien,
                    sdp.nam,
                    sdp.thang,
                    sdp.so_ngay_phep_duoc_huong,
                    sdp.so_ngay_phep_da_dung,
                    sdp.so_ngay_phep_con_lai,
                    sdp.so_gio_tang_ca_tich_luy,
                    sdp.so_gio_tang_ca_con_lai,
                    sdp.so_ngay_cong
                FROM soduphep sdp
                INNER JOIN nhanvien nv ON sdp.ma_nhan_vien = nv.ma_nhan_vien
                WHERE sdp.nam = ?
                AND sdp.thang = ?
                AND nv.trang_thai != 'NGHI_VIEC'";
        
        if ($ma_phong_ban) {
            $sql .= " AND nv.ma_phong_ban = ?";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($ma_phong_ban) {
            $stmt->bind_param("iii", $nam, $thang, $ma_phong_ban);
        } else {
            $stmt->bind_param("ii", $nam, $thang);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $soduphep = [];
        while ($row = $result->fetch_assoc()) {
            $soduphep[$row['ma_nhan_vien']] = $row;
        }
        
        return $soduphep;
    }
}
?>

