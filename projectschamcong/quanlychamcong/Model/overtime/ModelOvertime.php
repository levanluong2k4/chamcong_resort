<?php
require_once __DIR__ . '/../../Model/Connect.php';

class ModelOvertime extends Connect {

   
    public function getAllRequests($vai_tro, $ma_phong_ban, $ma_nhan_vien) {
        
        $sql = "SELECT dtc.*, nv.ho_ten as nguoi_tao, pb.ten_phong_ban, ca.ten_ca 
                FROM don_tang_ca dtc
                JOIN nhanvien nv ON dtc.ma_nguoi_tao = nv.ma_nhan_vien
                JOIN phongban pb ON dtc.ma_phong_ban = pb.ma_phong_ban
                LEFT JOIN calamviec ca ON dtc.ma_ca = ca.ma_ca
                WHERE 1=1"; 
        if ($vai_tro == 'ADMIN' || $vai_tro == 'NHAN_SU') {
           
        } 
       
        elseif ($vai_tro == 'QUAN_LY') {
            $sql .= " AND dtc.ma_phong_ban = $ma_phong_ban";
        } 
        
        else {
            $sql .= " AND dtc.ma_nguoi_tao = $ma_nhan_vien";
        }
        $sql .= " ORDER BY dtc.ma_ca ASC, dtc.ngay_tao DESC";
        
        return $this->select($sql);
    }

  
   
    public function getRequestById($id) {
        $sql = "SELECT dtc.*, ca.ten_ca FROM don_tang_ca dtc 
                JOIN calamviec ca ON dtc.ma_ca = ca.ma_ca
                WHERE ma_don_tang_ca = $id";
        $result = $this->select($sql);
        return $result->fetch_assoc();
    }

    
    public function getEmployeesInRequest($id) {
        $sql = "SELECT ct.*, nv.ho_ten, nv.anh_dai_dien 
                FROM chitiet_tang_ca ct
                JOIN nhanvien nv ON ct.ma_nhan_vien = nv.ma_nhan_vien
                WHERE ct.ma_don_tang_ca = $id";
        return $this->select($sql);
    }

   
    public function createRequest($data) {
        $sql = "INSERT INTO don_tang_ca (ma_nguoi_tao, ma_phong_ban, ngay_tang_ca, ma_ca, tong_gio_can, so_gio_du, ly_do, trang_thai)
                VALUES ({$data['ma_nguoi_tao']}, {$data['ma_phong_ban']}, '{$data['ngay_tang_ca']}', {$data['ma_ca']}, {$data['tong_gio_can']}, {$data['so_gio_du']}, '{$data['ly_do']}', 'CHO_DUYET')";
        

        echo "<pre>SQL: $sql</pre>";
       
       // Thử execute trực tiếp
    if ($this->conn->query($sql)) {
        $insert_id = $this->conn->insert_id;
        echo "<pre>Insert ID: $insert_id</pre>";
        return $insert_id;
    } else {
        // In lỗi MySQL
        echo "<pre>MySQL Error: " . $this->conn->error . "</pre>";
        return false;
    }
    }

  
   

   public function addDetail($ma_don, $ma_nv, $so_gio, $ghi_chu = '') {
    // 1. Kiểm tra xem nhân viên này đã có trong đơn này chưa
    $sql_check = "SELECT * FROM chitiet_tang_ca WHERE ma_don_tang_ca = $ma_don AND ma_nhan_vien = $ma_nv";
    $result = $this->select($sql_check);

    if ($result && $row = $result->fetch_assoc()) {
        // 2. Nếu đã có, tiến hành CỘNG DỒN giờ và nối thêm ghi chú
        $ma_chi_tiet = $row['ma_chi_tiet'];
        $so_gio_moi = (float)$row['so_gio_dang_ky'] + (float)$so_gio;
        
        // Gộp ghi chú cũ và mới (nếu mới khác cũ)
        $ghi_chu_moi = $row['ghi_chu'];
        if (!empty($ghi_chu) && strpos($ghi_chu_moi, $ghi_chu) === false) {
            $ghi_chu_moi .= " | " . $ghi_chu;
        }
        
        $sql_update = "UPDATE chitiet_tang_ca SET so_gio_dang_ky = $so_gio_moi, ghi_chu = '$ghi_chu_moi' WHERE ma_chi_tiet = $ma_chi_tiet";
        return $this->execute($sql_update);
    } else {
        
        $sql_insert = "INSERT INTO chitiet_tang_ca (ma_don_tang_ca, ma_nhan_vien, so_gio_dang_ky, ghi_chu)
                       VALUES ($ma_don, $ma_nv, $so_gio, '$ghi_chu')";
        return $this->execute($sql_insert);
    }
}
   
public function updateStatus($id, $status) {
   
    $id = (int)$id;
    $sql = "UPDATE don_tang_ca SET trang_thai = '$status' WHERE ma_don_tang_ca = $id";
    return $this->execute($sql);
}

   
  public function updateEmployeeBalance($ma_nv, $so_gio, $operation = '+') {
        // BỎ dòng lấy năm: $nam = date('Y');
        
        // Kiểm tra xem nhân viên đã có dòng số dư chưa (không cần check năm nữa)
        $check = $this->select("SELECT * FROM soduphep WHERE ma_nhan_vien = $ma_nv");
        
        if ($check->num_rows == 0) {
            // Tạo mới nếu chưa có (bỏ cột nam trong câu lệnh INSERT)
            $this->execute("INSERT INTO soduphep (ma_nhan_vien) VALUES ($ma_nv)");
        }

        // Cập nhật cộng/trừ (bỏ điều kiện AND nam = ...)
        if ($operation == '+') {
            $sql = "UPDATE soduphep SET so_gio_tang_ca_tich_luy = so_gio_tang_ca_tich_luy + $so_gio, 
                    so_gio_tang_ca_con_lai = so_gio_tang_ca_con_lai + $so_gio
                    WHERE ma_nhan_vien = $ma_nv";
        } else {
            $sql = "UPDATE soduphep SET so_gio_tang_ca_tich_luy = so_gio_tang_ca_tich_luy - $so_gio, 
                    so_gio_tang_ca_con_lai = so_gio_tang_ca_con_lai - $so_gio
                    WHERE ma_nhan_vien = $ma_nv";
        }
        return $this->execute($sql);
    }

  
    public function deleteDetail($ma_chi_tiet) {
        return $this->execute("DELETE FROM chitiet_tang_ca WHERE ma_chi_tiet = $ma_chi_tiet");
    }

  
    public function getDetailById($id) {
        $result = $this->select("SELECT * FROM chitiet_tang_ca WHERE ma_chi_tiet = $id");
        return $result->fetch_assoc();
    }

   
    public function updateExcessHours($ma_don, $so_gio, $operation = '+') {
        if ($operation == '+') {
          
            $sql = "UPDATE don_tang_ca SET so_gio_du = so_gio_du + $so_gio WHERE ma_don_tang_ca = $ma_don";
        } else {
          
            $sql = "UPDATE don_tang_ca SET so_gio_du = so_gio_du - $so_gio WHERE ma_don_tang_ca = $ma_don";
        }
        return $this->execute($sql);
    }
    
  
    public function getEmployeesByDept($ma_phong_ban) {
        return $this->select("SELECT ma_nhan_vien, ho_ten FROM nhanvien WHERE ma_phong_ban = $ma_phong_ban AND trang_thai = 'DANG_LAM'");
    }
    
   
    public function getShifts() {
        return $this->select("SELECT * FROM calamviec");
    }
 
    
public function getTongGioTangCaByDept($ma_phong_ban) {
    
    $thang_nay = date('Y-m'); 
    
    $sql = "SELECT SUM(tong_gio_can) as tong_gio 
            FROM don_tang_ca 
            WHERE ma_phong_ban = $ma_phong_ban 
            AND trang_thai = 'DA_DUYET'
            AND ngay_tang_ca LIKE '$thang_nay%'";
            
    $result = $this->select($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['tong_gio'] ?? 0;
    }
    return 0;
}

public function getOverviewStats($ma_phong_ban) {
  
    $sql = "SELECT 
                SUM(tong_gio_can) as tong_can,
                SUM(so_gio_du) as tong_du
            FROM don_tang_ca 
            WHERE ma_phong_ban = $ma_phong_ban 
            AND trang_thai = 'DA_DUYET'"; 
    
    $result = $this->select($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $tong_can = $row['tong_can'] ?? 0;
        $tong_du = $row['tong_du'] ?? 0;
        return [
            'tong_can' => $tong_can,
            'tong_du' => $tong_du,
            'da_dung' => $tong_can - $tong_du 
        ];
    }
    return ['tong_can' => 0, 'tong_du' => 0, 'da_dung' => 0];
}

  
  public function deleteRequest($ma_don) {
      
        // 1. Xóa chi tiết đơn trước
        $sqlDetail = "DELETE FROM chitiet_tang_ca WHERE ma_don_tang_ca = $ma_don";
        $this->execute($sqlDetail);

        // 2. Xóa đơn chính
        $sqlMaster = "DELETE FROM don_tang_ca WHERE ma_don_tang_ca = $ma_don";
        $result = $this->execute($sqlMaster);

        // 3. LOGIC MỚI: Reset lại bộ đếm Auto Increment
        if ($result) {
            // Lấy ID lớn nhất hiện tại trong bảng
            $queryMax = "SELECT MAX(ma_don_tang_ca) as max_id FROM don_tang_ca";
            $resMax = $this->select($queryMax);
            
            $next_id = 1; // Mặc định về 1 nếu bảng trống
            if ($resMax && $row = $resMax->fetch_assoc()) {
                if ($row['max_id'] !== null) {
                    $next_id = $row['max_id'] + 1;
                }
            }

            // Chạy lệnh SQL để đặt lại bộ đếm
            // Lưu ý: Lệnh này sẽ đặt auto_increment về số tiếp theo hợp lý
            $this->execute("ALTER TABLE don_tang_ca AUTO_INCREMENT = $next_id");
        }

        return $result;
    }



public function getAllOverviewStats() {
    $sql = "SELECT 
                SUM(tong_gio_can) as tong_can,
                SUM(so_gio_du) as tong_du
            FROM don_tang_ca
            WHERE trang_thai = 'DA_DUYET'"; 
    
    $result = $this->select($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $tong_can = $row['tong_can'] ?? 0;
        $tong_du = $row['tong_du'] ?? 0;
        return [
            'tong_can' => $tong_can,
            'tong_du' => $tong_du,
            'da_dung' => $tong_can - $tong_du
        ];
    }
    return ['tong_can' => 0, 'tong_du' => 0, 'da_dung' => 0];
}


public function getEmployeeOvertimeHistory($vai_tro, $ma_phong_ban) {
        // BỎ dòng lấy năm: $nam = date('Y');
        
        // Sửa câu lệnh SQL: Bỏ đoạn "AND sdp.nam = $nam" trong LEFT JOIN
        $sql = "SELECT nv.ma_nhan_vien, nv.ho_ten, pb.ten_phong_ban,
                       IFNULL(sdp.so_gio_tang_ca_tich_luy, 0) as tong_tich_luy
                FROM nhanvien nv
                JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                LEFT JOIN soduphep sdp ON nv.ma_nhan_vien = sdp.ma_nhan_vien 
                WHERE nv.trang_thai = 'DANG_LAM' 
                AND IFNULL(sdp.so_gio_tang_ca_tich_luy, 0) > 0"; 

        if ($vai_tro == 'QUAN_LY') {
            $sql .= " AND nv.ma_phong_ban = $ma_phong_ban";
        }

        $sql .= " ORDER BY tong_tich_luy DESC";
        
        return $this->select($sql);
    }

// public function getEmployeeOvertimeHistory($vai_tro, $ma_phong_ban) {
//     // Sử dụng LEFT JOIN cho bảng phongban và xử lý trường hợp tên phòng ban bị NULL
//     $sql = "SELECT 
//                 nv.ma_nhan_vien, 
//                 nv.ho_ten, 
//                 IFNULL(pb.ten_phong_ban, 'Chưa phân loại') as ten_phong_ban, 
//                 SUM(ct.so_gio_dang_ky) as tong_tich_luy
//             FROM nhanvien nv
//             LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban 
//             JOIN chitiet_tang_ca ct ON nv.ma_nhan_vien = ct.ma_nhan_vien
//             JOIN don_tang_ca dtc ON ct.ma_don_tang_ca = dtc.ma_don_tang_ca
//             WHERE nv.trang_thai = 'DANG_LAM' 
//             AND dtc.trang_thai = 'DA_DUYET'";

//     // Phân quyền dữ liệu
//     if ($vai_tro == 'QUAN_LY') {
//         $sql .= " AND nv.ma_phong_ban = $ma_phong_ban";
//     }

//     // Gom nhóm
//     $sql .= " GROUP BY nv.ma_nhan_vien, nv.ho_ten, pb.ten_phong_ban";
    
//     // Điều kiện hiển thị
//     $sql .= " HAVING tong_tich_luy > 0 ORDER BY tong_tich_luy DESC";
    
//     return $this->select($sql);
// }
public function updateDetail($ma_chi_tiet, $so_gio_moi, $ghi_chu) {
   
    $sql = "UPDATE chitiet_tang_ca 
            SET so_gio_dang_ky = $so_gio_moi, ghi_chu = '$ghi_chu' 
            WHERE ma_chi_tiet = $ma_chi_tiet";
    return $this->execute($sql);
}





}
?>