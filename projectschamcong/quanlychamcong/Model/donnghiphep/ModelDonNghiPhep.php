<?php
require_once __DIR__ . '/../Connect.php'; 
// DÒNG GÂY LỖI: require_once __DIR__ . '/../soduphep/ModelSoDuPhep.php'; 
// -> Bạn hãy xóa dòng trên đi vì Model này không dùng đến Số Dư Phép.

class ModelDonNghiPhep extends Connect {
    public function index() {
        $sql = "SELECT * FROM donnghiphep";
        $result = $this->select($sql);
        return $result;
    }
    
    // 1. Lấy danh sách đơn chờ duyệt (Cho Quản Lý)
    public function getPendingRequestsByManager($managerId) {
        $sql = "SELECT DISTINCT d.*, n.ho_ten, n.anh_dai_dien, n.vai_tro, p.ten_phong_ban 
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban
                WHERE n.ma_nguoi_quan_ly = '$managerId' 
                AND d.trang_thai = 'CHO_DUYET'
                ORDER BY d.ngay_tao DESC";
                
        $result = $this->select($sql);
        $data = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

   // 2. Cập nhật trạng thái đơn (Duyệt/Từ chối)
    public function updateStatus($maDon, $status, $nguoiDuyetId, $lyDoTuChoi = null) {
        date_default_timezone_set('Asia/Ho_Chi_Minh'); 
        $ngayXuLy = date('Y-m-d H:i:s');
        
        // TRƯỜNG HỢP 1: TỪ CHỐI (Có lý do)
        if ($lyDoTuChoi) {
            $sql = "UPDATE donnghiphep 
                    SET trang_thai = ?, 
                        nguoi_duyet = ?, 
                        ngay_xu_ly = ?, 
                        ly_do_tu_choi = ? 
                    WHERE ma_don = ?";
            $stmt = $this->conn->prepare($sql);
            // 5 tham số: s (chuỗi), i (số), s (chuỗi), s (chuỗi), i (số)
            $stmt->bind_param("sissi", $status, $nguoiDuyetId, $ngayXuLy, $lyDoTuChoi, $maDon);
        } 
        // TRƯỜNG HỢP 2: DUYỆT (Không có lý do)
        else {
            $sql = "UPDATE donnghiphep 
                    SET trang_thai = ?, 
                        nguoi_duyet = ?, 
                        ngay_xu_ly = ? 
                    WHERE ma_don = ?";
            $stmt = $this->conn->prepare($sql);
            
            // --- SỬA LẠI DÒNG NÀY ---
            // Chỉ có 4 tham số: s (chuỗi), i (số), s (chuỗi), i (số)
            // Bỏ biến $lyDoTuChoi thừa đi
            $stmt->bind_param("sisi", $status, $nguoiDuyetId, $ngayXuLy, $maDon);
        }

        return $stmt->execute();
    }
    // 3. Hàm lấy lịch sử duyệt
    public function getHistoryRequestsByManager($managerId) {
        $sql = "SELECT d.*, 
                       n.ho_ten, 
                       n.anh_dai_dien,
                       p.ten_phong_ban,
                       nd.ho_ten AS ten_nguoi_duyet 
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban 
                LEFT JOIN nhanvien nd ON d.nguoi_duyet = nd.ma_nhan_vien 
                WHERE d.nguoi_duyet = '$managerId' 
                AND n.ma_nguoi_quan_ly = '$managerId' 
                AND d.trang_thai IN ('DA_DUYET', 'TU_CHOI')
                GROUP BY d.ma_nhan_vien, d.ngay_bat_dau, d.ngay_ket_thuc 
                ORDER BY d.ngay_xu_ly DESC LIMIT 20"; 
        
        $result = $this->select($sql);
        $data = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // 4. Tạo đơn mới
    public function createDonNghi($maNhanVien, $loaiNghi, $ngayBatDau, $ngayKetThuc, $lyDo, $fileName = null) {
        $start = strtotime($ngayBatDau);
        $end = strtotime($ngayKetThuc);
        $diff = $end - $start;
        $soNgayNghi = round($diff / (60 * 60 * 24), 2);
        
        if ($soNgayNghi <= 0) $soNgayNghi = 0.5;

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $ngayTao = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO donnghiphep (ma_nhan_vien, loai_nghi, ngay_bat_dau, ngay_ket_thuc, so_ngay_nghi, ly_do, file_dinh_kem, trang_thai, ngay_tao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'CHO_DUYET', ?)";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssdsss", $maNhanVien, $loaiNghi, $ngayBatDau, $ngayKetThuc, $soNgayNghi, $lyDo, $fileName, $ngayTao);
        
        return $stmt->execute();
    }

    // 5. Lịch sử cá nhân
    public function getHistoryByEmployee($maNhanVien) {
        $sql = "SELECT DISTINCT d.*, 
                       nv.ho_ten, 
                       pb.ten_phong_ban,
                       nd.ho_ten AS ten_nguoi_duyet
                FROM donnghiphep d
                JOIN nhanvien nv ON d.ma_nhan_vien = nv.ma_nhan_vien
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                LEFT JOIN nhanvien nd ON d.nguoi_duyet = nd.ma_nhan_vien
                WHERE d.ma_nhan_vien = '$maNhanVien' 
                ORDER BY d.ngay_tao DESC";
                
        $result = $this->select($sql);
        $data = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // 6. Lấy TOÀN BỘ lịch sử đơn cho HR
    public function getAllHistoryForHR() {
        $sql = "SELECT DISTINCT d.*, n.ho_ten, n.ma_nhan_vien, p.ten_phong_ban, nd.ho_ten AS ten_nguoi_duyet
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban
                LEFT JOIN nhanvien nd ON d.nguoi_duyet = nd.ma_nhan_vien
                WHERE d.trang_thai IN ('DA_DUYET', 'TU_CHOI') 
                ORDER BY d.ngay_xu_ly DESC LIMIT 50";
                
        $result = $this->select($sql);
        $data = []; 
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // 7. Hàm xóa đơn
    public function deleteDon($maDon) {
      // Chỉ kiểm tra nếu không tồn tại biến maDon
if (!isset($maDon)) return false;
        $sql = "DELETE FROM donnghiphep WHERE ma_don = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDon);
        return $stmt->execute();
    }

    // 8. Lấy thông tin chi tiết
    public function getDonById($maDon) {
        $sql = "SELECT d.*, n.ho_ten, p.ten_phong_ban 
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban
                WHERE d.ma_don = '$maDon'";
        $result = $this->select($sql);
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }
    
    // 9. Cập nhật đơn
    public function updateDonNghi($maDon, $loaiNghi, $ngayBatDau, $ngayKetThuc, $lyDo, $fileName = null) {
        $start = strtotime($ngayBatDau);
        $end = strtotime($ngayKetThuc);
        $diff = $end - $start;
        $soNgayNghi = round($diff / (60 * 60 * 24), 2);
        if ($soNgayNghi <= 0) $soNgayNghi = 0.5;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        if ($fileName) {
            $sql = "UPDATE donnghiphep 
                    SET loai_nghi = ?, ngay_bat_dau = ?, ngay_ket_thuc = ?, so_ngay_nghi = ?, ly_do = ?, file_dinh_kem = ?
                    WHERE ma_don = ? AND trang_thai = 'CHO_DUYET'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssdssi", $loaiNghi, $ngayBatDau, $ngayKetThuc, $soNgayNghi, $lyDo, $fileName, $maDon);
        } else {
            $sql = "UPDATE donnghiphep 
                    SET loai_nghi = ?, ngay_bat_dau = ?, ngay_ket_thuc = ?, so_ngay_nghi = ?, ly_do = ?
                    WHERE ma_don = ? AND trang_thai = 'CHO_DUYET'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssdsi", $loaiNghi, $ngayBatDau, $ngayKetThuc, $soNgayNghi, $lyDo, $maDon);
        }
        return $stmt->execute();
    }


    // [MỚI] 1. Lấy đơn chờ duyệt dành cho Giám Đốc (Chỉ lấy của Quản lý và Nhân sự)
    public function getPendingRequestsForDirector() {
        $sql = "SELECT DISTINCT d.*, n.ho_ten, n.anh_dai_dien, n.vai_tro, p.ten_phong_ban 
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban
                WHERE d.trang_thai = 'CHO_DUYET'
                AND n.vai_tro IN ('QUAN_LY', 'NHAN_SU') 
                ORDER BY d.ngay_tao DESC";
                
        $result = $this->select($sql);
        $data = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // [MỚI] 2. Lấy TOÀN BỘ lịch sử (Dành cho Giám Đốc xem tất cả)
    public function getAllHistoryForDirector() {
        $sql = "SELECT DISTINCT d.*, 
                       n.ho_ten, 
                       n.anh_dai_dien,
                       n.vai_tro,
                       p.ten_phong_ban,
                       nd.ho_ten AS ten_nguoi_duyet 
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban
                LEFT JOIN nhanvien nd ON d.nguoi_duyet = nd.ma_nhan_vien
                WHERE d.trang_thai IN ('DA_DUYET', 'TU_CHOI')
                ORDER BY d.ngay_xu_ly DESC"; // Không giới hạn LIMIT để xem hết hoặc phân trang sau
        
        $result = $this->select($sql);
        $data = [];
        if($result) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    
    // PHẦN SỬA LỖI CHO ADMIN / GIÁM ĐỐC
    // ---------------------------------------------------------

    // 1. Lấy đơn chờ duyệt dành cho Admin/Giám Đốc (Chỉ lấy của QUAN_LY và NHAN_SU)
    public function getPendingForAdmin() {
        // SỬA LỖI: Không dùng $this->connect(), dùng trực tiếp $this->select()
        
        // Lọc: Trang thái CHO_DUYET VÀ vai trò người nộp đơn là QUAN_LY hoặc NHAN_SU
        $sql = "SELECT d.*, n.ho_ten, n.vai_tro, p.ten_phong_ban 
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban
                WHERE d.trang_thai = 'CHO_DUYET' 
                AND n.vai_tro IN ('QUAN_LY', 'NHAN_SU') 
                ORDER BY d.ngay_tao ASC";
        
        // Sử dụng hàm select() có sẵn trong class Connect
        $result = $this->select($sql); 
        
        $list = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }

    // 2. Lấy toàn bộ lịch sử cho Admin/Giám Đốc xem (Thấy hết tất cả mọi người)
    public function getAllHistoryForAdmin() {
        // SỬA LỖI: Không dùng $this->connect()
        
        // Không lọc vai trò, lấy tất cả đơn đã xử lý (DA_DUYET hoặc TU_CHOI)
        $sql = "SELECT d.*, n.ho_ten, n.vai_tro, p.ten_phong_ban 
                FROM donnghiphep d
                JOIN nhanvien n ON d.ma_nhan_vien = n.ma_nhan_vien
                LEFT JOIN phongban p ON n.ma_phong_ban = p.ma_phong_ban
                WHERE d.trang_thai IN ('DA_DUYET', 'TU_CHOI')
                ORDER BY d.ngay_xu_ly DESC, d.ngay_tao DESC";
        
        // Sử dụng hàm select() có sẵn trong class Connect
        $result = $this->select($sql);

        $list = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }
}
?>