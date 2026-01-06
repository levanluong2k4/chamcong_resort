<?php

require_once(__DIR__ . '/../Connect.php');
require_once (__DIR__ .'/../../object/emloyee.php');
class ModelEmloyee extends Connect {
    // private string $table='nhanvien';




    // public function getEmloyeeById($id) {
    //     $sql="SELECT * FROM $this->table  WHERE ma_nhan_vien=$id";
    //     $result=$this->select($sql);
    //     if($result->num_rows>0){
    //         $row=mysqli_fetch_assoc($result);
    //         return new Employee($row);
    //     }
    //     return null;
    // }
    private string $table='nhanvien';




    public function getEmloyeeById($id) {
        $sql="SELECT * FROM $this->table  WHERE ma_nhan_vien=$id";
        $result=$this->select($sql);
        if($result->num_rows>0){
            $row=mysqli_fetch_assoc($result);
            return new Employee($row);
        }
        return null;
    }


    // Trong class ModelEmloyee
public function checkEmailExists($email) {
    $sql = "SELECT ma_nhan_vien FROM nhanvien WHERE email = '$email'";
    $result = $this->select($sql);
    return ($result && $result->num_rows > 0);
}

// public function createEmployee($hoTen, $email, $matKhauHash, $vaiTro, $maPhongBan, $maQuanLy, $sdt, $anhDaiDien, $ngayVaoLam) {
//     date_default_timezone_set('Asia/Ho_Chi_Minh');
//     $ngayTao = date('Y-m-d H:i:s');
//     $trangThai = 'DANG_LAM';

//     $sql = "INSERT INTO nhanvien (ho_ten, email, mat_khau_hash, vai_tro, ma_phong_ban, ma_nguoi_quan_ly, so_dien_thoai, anh_dai_dien, ngay_vao_lam, trang_thai, ngay_tao) 
//             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
//     $stmt = $this->conn->prepare($sql);
//     $stmt->bind_param("ssssiisssss", $hoTen, $email, $matKhauHash, $vaiTro, $maPhongBan, $maQuanLy, $sdt, $anhDaiDien, $ngayVaoLam, $trangThai, $ngayTao);
    
//     return $stmt->execute();
// }

// Tìm đến hàm createEmployee trong class ModelEmloyee và thay thế bằng đoạn này:

public function createEmployee($hoTen, $email, $matKhauHash, $vaiTro, $maPhongBan, $maQuanLy, $sdt, $anhDaiDien, $ngayVaoLam, $trangThai) { // <--- Thêm $trangThai vào tham số
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $ngayTao = date('Y-m-d H:i:s');
    
    // Bỏ dòng $trangThai = 'DANG_LAM'; cũ đi

    $sql = "INSERT INTO nhanvien (ho_ten, email, mat_khau_hash, vai_tro, ma_phong_ban, ma_nguoi_quan_ly, so_dien_thoai, anh_dai_dien, ngay_vao_lam, trang_thai, ngay_tao) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $this->conn->prepare($sql);
    // Lưu ý: bind_param vẫn giữ nguyên số lượng tham số, chỉ thay đổi giá trị truyền vào
    $stmt->bind_param("ssssiisssss", $hoTen, $email, $matKhauHash, $vaiTro, $maPhongBan, $maQuanLy, $sdt, $anhDaiDien, $ngayVaoLam, $trangThai, $ngayTao);
    
    return $stmt->execute();
}

// Thêm hàm này vào class ModelEmloyee

    // Lấy danh sách nhân viên theo phòng ban (Dành cho Quản lý và Nhân sự thông thường)
    // public function getEmployeesByDepartment($maPhongBan) {
    //     $data = [];
    //     // Lấy thông tin nhân viên + tên phòng ban
    //     $sql = "SELECT nv.*, pb.ten_phong_ban 
    //             FROM $this->table nv 
    //             JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
    //             WHERE nv.ma_phong_ban = '$maPhongBan' 
    //             ORDER BY nv.ma_nhan_vien DESC";
                
    //     $result = $this->select($sql);
    //     if ($result && $result->num_rows > 0) {
    //         while ($row = $result->fetch_assoc()) {
    //             // Chuyển mỗi dòng thành đối tượng Employee nếu muốn, hoặc để array
    //             // Ở đây mình trả về array kết hợp để dễ hiển thị ở view
    //             $data[] = $row;
    //         }
    //     }
    //     return $data;
    // }
public function getEmployeesByDepartment($maPhongBan, $keyword = '') {
        $data = [];
        $sql = "SELECT nv.*, pb.ten_phong_ban 
                FROM $this->table nv 
                JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                WHERE nv.ma_phong_ban = '$maPhongBan'";
        
        // Thêm điều kiện tìm kiếm nếu có keyword
        if (!empty($keyword)) {
            $sql .= " AND nv.ho_ten LIKE '%$keyword%'";
        }

        $sql .= " ORDER BY nv.ma_nhan_vien DESC";
                
        $result = $this->select($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
    // Lấy danh sách cho Admin (Chỉ hiện Nhân sự và Quản lý)
    // public function getEmployeesForAdmin() {
    //     $data = [];
    //     $sql = "SELECT nv.*, pb.ten_phong_ban 
    //             FROM $this->table nv 
    //             LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
    //             WHERE nv.vai_tro IN ('NHAN_SU', 'QUAN_LY') 
    //             ORDER BY nv.vai_tro, nv.ma_phong_ban";
                
    //     $result = $this->select($sql);
    //     if ($result && $result->num_rows > 0) {
    //         while ($row = $result->fetch_assoc()) {
    //             $data[] = $row;
    //         }
    //     }
    //     return $data;
    // }

    // 2. SỬA HÀM: Lấy danh sách cho Admin (CÓ TÌM KIẾM)
    public function getEmployeesForAdmin($keyword = '') {
        $data = [];
        $sql = "SELECT nv.*, pb.ten_phong_ban 
                FROM $this->table nv 
                LEFT JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                WHERE nv.vai_tro IN ('NHAN_SU', 'QUAN_LY')";

        // Thêm điều kiện tìm kiếm
        if (!empty($keyword)) {
            $sql .= " AND nv.ho_ten LIKE '%$keyword%'";
        }

        $sql .= " ORDER BY nv.vai_tro, nv.ma_phong_ban";
                
        $result = $this->select($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // 3. THÊM HÀM: Xóa nhân viên (Xóa vĩnh viễn)
    public function deleteEmployee($id) {
        $sql = "DELETE FROM $this->table WHERE ma_nhan_vien = '$id'";
        return $this->conn->query($sql);
    }
    // Lấy mật khẩu hiện tại để so sánh
    public function getPasswordHash($id) {
        $sql = "SELECT mat_khau_hash FROM nhanvien WHERE ma_nhan_vien = '$id'";
        $result = $this->select($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['mat_khau_hash'];
        }
        return null;
    }

    // Cập nhật mật khẩu mới (đã hash)
    public function updatePassword($userId, $newPasswordHash) {
        $newPasswordHash = mysqli_real_escape_string($this->conn, $newPasswordHash);
        $sql = "UPDATE nhanvien SET mat_khau_hash = '$newPasswordHash' WHERE ma_nhan_vien = '$userId'";
        return $this->conn->query($sql);
    }
// Thêm vào trong class ModelEmloyee

    public function updateEmployee($id, $hoTen, $sdt, $vaiTro, $trangThai) {
        // Cập nhật thông tin cơ bản (Không cho phép sửa Email và Password ở đây để bảo mật)
        $sql = "UPDATE nhanvien 
                SET ho_ten = ?, 
                    so_dien_thoai = ?, 
                    vai_tro = ?, 
                    trang_thai = ?
                WHERE ma_nhan_vien = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $hoTen, $sdt, $vaiTro, $trangThai, $id);
        
        return $stmt->execute();
    }
}





?>