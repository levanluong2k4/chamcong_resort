<?php 
require_once __DIR__ . '/../Connect.php';

class ThongKeCongModel extends Connect {
    
    /**
     * Lấy danh sách tất cả phòng ban
     */
    public function getAllPhongBan() {
        $sql = "SELECT ma_phong_ban, ten_phong_ban 
                FROM phongban 
                ORDER BY ten_phong_ban";
        
        $result = $this->select($sql);
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
  
    public function thongKeCongTheoThang($ma_phong_ban, $thang, $nam) {
        $sql = "
            SELECT 
                nv.ma_nhan_vien,
                nv.ho_ten,
                nv.email,
                pb.ten_phong_ban,
                
                -- Số ngày có lịch làm việc trong tháng
                COUNT(DISTINCT llv.ngay_lam) as tong_ngay_co_lich,
                
                -- ===== NGÀY ĐI LÀM (TÍNH CÔNG) =====
                -- Tổng số ngày đi làm (bao gồm cả đi trễ, về sớm, quên chấm)
                SUM(CASE 
                    WHEN cc.trang_thai IN ('DI_LAM', 'DI_TRE', 'VE_SOM', 'QUEN_CHAM_CONG','DI_LAM_NGAY_LE') THEN 1 
                    ELSE 0 
                END) as so_ngay_di_lam,
                
                -- ⭐ SỬA: Đổi tên thành so_ngay_dung_gio thay vì so_ngay_di_lam
                SUM(CASE WHEN cc.trang_thai = 'DI_LAM' THEN 1 ELSE 0 END) as so_ngay_dung_gio,
                SUM(CASE WHEN cc.trang_thai = 'DI_LAM_NGAY_LE' THEN 1 ELSE 0 END) as so_ngay_di_lam_ngay_le,
                SUM(CASE WHEN cc.trang_thai = 'DI_TRE' THEN 1 ELSE 0 END) as so_ngay_di_tre,
                SUM(CASE WHEN cc.trang_thai = 'VE_SOM' THEN 1 ELSE 0 END) as so_ngay_ve_som,
                SUM(CASE WHEN cc.trang_thai = 'QUEN_CHAM_CONG' THEN 1 ELSE 0 END) as so_ngay_quen_cham,
                
                -- ===== NGÀY KHÔNG ĐI (TRỪ CÔNG) =====
                SUM(CASE WHEN cc.trang_thai = 'VANG_MAT' THEN 1 ELSE 0 END) as so_ngay_vang_mat,
                SUM(CASE WHEN cc.trang_thai in ('NGHI_PHEP','NGHI_PHEP_TUAN') THEN 1 ELSE 0 END) as so_ngay_nghi_phep,
                SUM(CASE WHEN cc.trang_thai ='NGHI_PHEP_DON' THEN 1 ELSE 0 END) as so_ngay_nghi_phep_don,
                
                -- ===== THÔNG TIN PHỤ =====
                COALESCE(SUM(cc.tong_gio_lam), 0) as tong_gio_lam,
                COALESCE(SUM(cc.so_phut_tre), 0) as tong_phut_tre,
                COALESCE(SUM(cc.so_phut_ve_som), 0) as tong_phut_ve_som,
                
                MIN(TIME(cc.gio_vao)) as gio_vao_som_nhat,
                MAX(TIME(cc.gio_ra)) as gio_ra_muon_nhat,
                
                MIN(
                    CASE 
                        WHEN cc.gio_vao IS NOT NULL 
                        THEN TIME_TO_SEC(TIME(cc.gio_vao))
                        ELSE 999999
                    END
                ) as phut_vao_som_nhat,
                
                MAX(
                    CASE 
                        WHEN cc.gio_ra IS NOT NULL 
                        THEN TIME_TO_SEC(TIME(cc.gio_ra))
                        ELSE 0
                    END
                ) as phut_ra_muon_nhat
                
            FROM nhanvien nv
            INNER JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
            LEFT JOIN lichlamviec llv ON nv.ma_nhan_vien = llv.ma_nhan_vien
                AND MONTH(llv.ngay_lam) = ?
                AND YEAR(llv.ngay_lam) = ?
            LEFT JOIN chamcong cc ON nv.ma_nhan_vien = cc.ma_nhan_vien
                AND llv.ngay_lam = cc.ngay_lam
            WHERE nv.trang_thai != 'NGHI_VIEC'
        ";
        
        // Thêm điều kiện lọc phòng ban
        if ($ma_phong_ban != 'all') {
            $sql .= " AND nv.ma_phong_ban = ?";
        }
        
        $sql .= "
            GROUP BY nv.ma_nhan_vien, nv.ho_ten, nv.email, pb.ten_phong_ban
            ORDER BY 
                tong_gio_lam DESC,
                phut_vao_som_nhat ASC,
                phut_ra_muon_nhat DESC
        ";
        
        $stmt = $this->conn->prepare($sql);
        
        if ($ma_phong_ban != 'all') {
            $stmt->bind_param("iii", $thang, $nam, $ma_phong_ban);
        } else {
            $stmt->bind_param("ii", $thang, $nam);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        $rank = 1;
        
        while ($row = $result->fetch_assoc()) {
            $row['so_ngay_khong_di_lam'] = $row['so_ngay_vang_mat'] + $row['so_ngay_nghi_phep'] + $row['so_ngay_nghi_phep_don'];
            $row['so_ngay_cong'] = $row['so_ngay_di_lam'];
            
            if ($row['tong_ngay_co_lich'] > 0) {
                $row['ty_le_di_lam'] = round(($row['so_ngay_di_lam'] / $row['tong_ngay_co_lich']) * 100, 1);
            } else {
                $row['ty_le_di_lam'] = 0;
            }
            
            $row['tong_gio_lam'] = round($row['tong_gio_lam'], 2);
            $row['thu_hang'] = $rank++;
            
            if ($row['phut_vao_som_nhat'] < 999999) {
                $row['gio_vao_som_nhat_hien_thi'] = gmdate("H:i", $row['phut_vao_som_nhat']);
            } else {
                $row['gio_vao_som_nhat_hien_thi'] = '--:--';
            }
            
            if ($row['phut_ra_muon_nhat'] > 0) {
                $row['gio_ra_muon_nhat_hien_thi'] = gmdate("H:i", $row['phut_ra_muon_nhat']);
            } else {
                $row['gio_ra_muon_nhat_hien_thi'] = '--:--';
            }
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * Thống kê tổng hợp theo phòng ban
     */
    public function thongKeTongHopTheoPhongBan($thang, $nam) {
        $sql = "
            SELECT 
                pb.ma_phong_ban,
                pb.ten_phong_ban,
                COUNT(DISTINCT nv.ma_nhan_vien) as tong_nhan_vien,
                
                -- Tổng ngày đi làm của phòng ban
                SUM(CASE 
                    WHEN cc.trang_thai IN ('DI_LAM', 'DI_TRE', 'VE_SOM', 'QUEN_CHAM_CONG','DI_LAM_NGAY_LE') THEN 1 
                    ELSE 0 
                END) as tong_ngay_di_lam,
                
                -- Tổng ngày vắng mặt
                SUM(CASE 
                    WHEN cc.trang_thai = 'VANG_MAT' THEN 1 
                    ELSE 0 
                END) as tong_ngay_vang_mat,
                
                -- Tổng ngày nghỉ phép
                SUM(CASE 
                    WHEN cc.trang_thai IN ('NGHI_PHEP','NGHI_PHEP_TUAN','NGHI_PHEP_DON') THEN 1 
                    ELSE 0 
                END) as tong_ngay_nghi_phep,
                
                
                -- Tổng giờ làm việc
                COALESCE(SUM(cc.tong_gio_lam), 0) as tong_gio_lam_phong_ban
                
            FROM phongban pb
            LEFT JOIN nhanvien nv ON pb.ma_phong_ban = nv.ma_phong_ban
                AND nv.trang_thai != 'NGHI_VIEC'
            LEFT JOIN chamcong cc ON nv.ma_nhan_vien = cc.ma_nhan_vien
                AND MONTH(cc.ngay_lam) = ?
                AND YEAR(cc.ngay_lam) = ?
            GROUP BY pb.ma_phong_ban, pb.ten_phong_ban
            ORDER BY pb.ten_phong_ban
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $thang, $nam);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $row['tong_gio_lam_phong_ban'] = round($row['tong_gio_lam_phong_ban'], 2);
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * Lấy chi tiết chấm công của 1 nhân viên trong tháng
     */
    public function getChiTietChamCongNhanVien($ma_nhan_vien, $thang, $nam) {
        $sql = "
            SELECT 
                cc.ngay_lam,
                DAYNAME(cc.ngay_lam) as thu,
                ca.ten_ca,
                ca.gio_bat_dau,
                ca.gio_ket_thuc,
                cc.gio_vao,
                cc.gio_ra,
                cc.trang_thai,
                cc.so_phut_tre,
                cc.so_phut_ve_som,
                cc.tong_gio_lam,
                cc.ghi_chu
            FROM chamcong cc
            LEFT JOIN lichlamviec llv ON cc.ma_nhan_vien = llv.ma_nhan_vien 
                AND cc.ngay_lam = llv.ngay_lam
            LEFT JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
            WHERE cc.ma_nhan_vien = ?
                AND MONTH(cc.ngay_lam) = ?
                AND YEAR(cc.ngay_lam) = ?
            ORDER BY cc.ngay_lam
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $ma_nhan_vien, $thang, $nam);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
}
?>