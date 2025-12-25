<?php
require_once(__DIR__ . '/../Connect.php');

class ShiftScheduleModel extends Connect {

    /**
     * Lấy thông tin số Chủ Nhật trong tháng từ bảng thongkechunhat
     */
    public function countSundaysInMonth($thang, $nam) {
        // Kiểm tra trong bảng thongkechunhat trước
        $stmt = $this->conn->prepare(
            "SELECT so_chu_nhat, so_ngay_nghi_tuan 
             FROM thongkechunhat 
             WHERE thang = ? AND nam = ?"
        );
        $stmt->bind_param("ii", $thang, $nam);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result) {
            return (int)$result['so_chu_nhat'];
        }
        
        // Nếu chưa có, tính và lưu vào bảng
        $stmt = $this->conn->prepare("CALL TinhSoChuNhatTrongThang(?, ?)");
        $stmt->bind_param("ii", $thang, $nam);
        $stmt->execute();
        
        // Lấy lại kết quả
        $stmt = $this->conn->prepare(
            "SELECT so_chu_nhat FROM thongkechunhat WHERE thang = ? AND nam = ?"
        );
        $stmt->bind_param("ii", $thang, $nam);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return (int)$result['so_chu_nhat'];
    }
    
    /**
     * Lấy thông tin số ngày nghỉ tuần
     */
    public function getSoNgayNghiTuan($thang, $nam) {
        // Truy vấn từ bảng thongkechunhat thay vì view
        $stmt = $this->conn->prepare(
            "SELECT so_ngay_nghi_tuan FROM thongkechunhat 
             WHERE thang = ? AND nam = ?"
        );
        $stmt->bind_param("ii", $thang, $nam);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result) {
            return (int)$result['so_ngay_nghi_tuan'];
        }
        
        // Nếu chưa có, tính và lưu vào bảng
        $stmt_call = $this->conn->prepare("CALL TinhSoChuNhatTrongThang(?, ?)");
        $stmt_call->bind_param("ii", $thang, $nam);
        $stmt_call->execute();
        $stmt_call->close(); // Phải close sau khi gọi procedure
        
        // Lấy lại kết quả từ bảng
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? (int)$result['so_ngay_nghi_tuan'] : 1; // Mặc định 1 nếu lỗi
    }
    
    /**
     * Lấy thông tin Chủ Nhật tháng này và tháng sau từ view
     */
    public function getThongTinChuNhat() {
        $rs = $this->conn->query("SELECT * FROM v_ChuNhatThangNay");
        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Lấy danh sách nhân viên theo phòng ban (bao gồm vai trò)
     */
    public function getNhanVienTheoPhongBan($ma_phong_ban) {
        $stmt = $this->conn->prepare(
            "SELECT ma_nhan_vien, ho_ten, email, vai_tro
             FROM nhanvien
             WHERE ma_phong_ban = ?
             AND trang_thai = 'DANG_LAM'
             ORDER BY vai_tro DESC, ho_ten"
        );
        $stmt->bind_param("s", $ma_phong_ban);
        $stmt->execute();

        $data = [];
        $rs = $stmt->get_result();
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Lấy danh sách ca làm việc
     */
    public function getAllShifts() {
        $rs = $this->conn->query("SELECT * FROM calamviec ORDER BY gio_bat_dau");
        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Kiểm tra tuần đã có lịch chưa
     */
    public function kiemTraTuanDaCoLich($ma_phong_ban, $thang, $nam, $tuan) {
        $ngay_dau_thang = new DateTime("$nam-$thang-01");
        $ngay_dau_tuan = clone $ngay_dau_thang;
        $ngay_dau_tuan->modify('+' . (($tuan - 1) * 7) . ' days');
        
        $ngay_cuoi_tuan = clone $ngay_dau_tuan;
        $ngay_cuoi_tuan->modify('+6 days');
        
        $ngay_bat_dau = $ngay_dau_tuan->format('Y-m-d');
        $ngay_ket_thuc = $ngay_cuoi_tuan->format('Y-m-d');

        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as count FROM lichlamviec l
             JOIN nhanvien n ON l.ma_nhan_vien = n.ma_nhan_vien
             WHERE n.ma_phong_ban = ?
             AND l.ngay_lam BETWEEN ? AND ?"
        );
        $stmt->bind_param("sss", $ma_phong_ban, $ngay_bat_dau, $ngay_ket_thuc);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['count'] > 0;
    }

    /**
     * Tạo lịch làm việc theo tuần
     */
    public function taoLichTheoTuan($ma_phong_ban, $thang, $nam, $tuan, $config, $so_nv_moi_ca, $nhan_vien_nghi_co_dinh) {
        try {
            $this->conn->begin_transaction();

            $nhan_viens = $this->getNhanVienTheoPhongBan($ma_phong_ban);
            if (empty($nhan_viens)) {
                throw new Exception("Không có nhân viên trong phòng ban");
            }

            // Lấy số ngày nghỉ tuần từ bảng thongkechunhat
            $so_ngay_nghi_tuan = $this->getSoNgayNghiTuan($thang, $nam);
            
            // Tính ngày bắt đầu và kết thúc của tuần
            $ngay_dau_thang = new DateTime("$nam-$thang-01");
            $ngay_dau_tuan = clone $ngay_dau_thang;
            $ngay_dau_tuan->modify('+' . (($tuan - 1) * 7) . ' days');
            
            $ngay_cuoi_tuan = clone $ngay_dau_tuan;
            $ngay_cuoi_tuan->modify('+6 days');
            
            // // Không vượt quá cuối tháng
            // $ngay_cuoi_thang = new DateTime($ngay_dau_thang->format('Y-m-t'));
            // if ($ngay_cuoi_tuan > $ngay_cuoi_thang) {
            //     $ngay_cuoi_tuan = $ngay_cuoi_thang;
            // }

            // Phân loại nhân viên theo vai trò
            $nv_quan_ly = []; // NHAN_SU, QUAN_LY - nghỉ CN
            $nv_thuong = []; // NHAN_VIEN - random ngày nghỉ
            
            foreach ($nhan_viens as $nv) {
                if (in_array($nv['vai_tro'], ['NHAN_SU', 'QUAN_LY'])) {
                    $nv_quan_ly[] = $nv;
                } else {
                    $nv_thuong[] = $nv;
                }
            }

            // Tính số ngày nghỉ cho mỗi nhân viên thường trong tuần
            $so_ngay_nghi_nv_thuong = $so_ngay_nghi_tuan;
            
            // Random ngày nghỉ cho từng nhân viên thường trong tuần
            $ngay_nghi_cua_nv = [];
            $ngay_trong_tuan = [];
            $current_temp = clone $ngay_dau_tuan;
            while ($current_temp <= $ngay_cuoi_tuan) {
                $ngay_trong_tuan[] = $current_temp->format('Y-m-d');
                $current_temp->modify('+1 day');
            }
            
            foreach ($nv_thuong as $nv) {
                $ngay_nghi_random = array_rand(array_flip($ngay_trong_tuan), min($so_ngay_nghi_nv_thuong, count($ngay_trong_tuan)));
                if (!is_array($ngay_nghi_random)) {
                    $ngay_nghi_random = [$ngay_nghi_random];
                }
                $ngay_nghi_cua_nv[$nv['ma_nhan_vien']] = $ngay_nghi_random;
            }

            $current = clone $ngay_dau_tuan;
            $day_counter = 0;
            
            while ($current <= $ngay_cuoi_tuan) {
                $ngay = $current->format('Y-m-d');
                $thu = (int)$current->format('w');

                // Lọc nhân viên nghỉ cố định cho ngày này
                $nv_nghi_ngay_nay = array_filter($nhan_vien_nghi_co_dinh, function($item) use ($ngay) {
                    return $item['ngay'] == $ngay;
                });
                $ma_nv_nghi_co_dinh = array_column($nv_nghi_ngay_nay, 'ma_nhan_vien');

                $ma_ca = $config[$thu] ?? 1;
                $so_nv_can = $so_nv_moi_ca[$ma_ca] ?? 1;

                // Xử lý nhân viên quản lý (nghỉ Chủ Nhật)
                if ($thu == 0) {
                    // Chủ Nhật - Quản lý nghỉ, nhân viên thường có thể làm
                    $nhan_viens_co_the_lam = array_filter($nv_thuong, function($nv) use ($ma_nv_nghi_co_dinh, $ngay, $ngay_nghi_cua_nv) {
                        if (in_array($nv['ma_nhan_vien'], $ma_nv_nghi_co_dinh)) return false;
                        if (in_array($ngay, $ngay_nghi_cua_nv[$nv['ma_nhan_vien']] ?? [])) return false;
                        return true;
                    });
                } else {
                    // Ngày thường
                    $nv_ql_co_the_lam = array_filter($nv_quan_ly, function($nv) use ($ma_nv_nghi_co_dinh) {
                        return !in_array($nv['ma_nhan_vien'], $ma_nv_nghi_co_dinh);
                    });
                    
                    $nv_thuong_co_the_lam = array_filter($nv_thuong, function($nv) use ($ma_nv_nghi_co_dinh, $ngay, $ngay_nghi_cua_nv) {
                        if (in_array($nv['ma_nhan_vien'], $ma_nv_nghi_co_dinh)) return false;
                        if (in_array($ngay, $ngay_nghi_cua_nv[$nv['ma_nhan_vien']] ?? [])) return false;
                        return true;
                    });
                    
                    $nhan_viens_co_the_lam = array_merge($nv_ql_co_the_lam, $nv_thuong_co_the_lam);
                }

                // Chọn nhân viên cho ca
                $nhan_viens_lam = $this->chonNhanVienLuanPhien($nhan_viens_co_the_lam, $so_nv_can, $day_counter);

                foreach ($nhan_viens_lam as $nv) {
                    $this->themLichLamViec($nv['ma_nhan_vien'], $ma_ca, $ngay);
                }

                $day_counter++;
                $current->modify('+1 day');
            }

            $this->conn->commit();
            return [
                'success' => true,
                'message' => "Đã tạo lịch tuần $tuan tháng $thang/$nam"
            ];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Tạo lịch làm việc theo tháng
     */
    public function taoLichTheoThang($ma_phong_ban, $thang, $nam, $config, $so_nv_moi_ca, $nhan_vien_nghi_co_dinh) {
        try {
            $this->conn->begin_transaction();

            $nhan_viens = $this->getNhanVienTheoPhongBan($ma_phong_ban);
            if (empty($nhan_viens)) {
                throw new Exception("Không có nhân viên trong phòng ban");
            }

            // Xóa lịch cũ
            $this->xoaLichThang($ma_phong_ban, $thang, $nam);

            // Lấy số ngày nghỉ tuần từ bảng thongkechunhat
            $so_chu_nhat = $this->countSundaysInMonth($thang, $nam);
            $so_ngay_nghi_tuan = $this->getSoNgayNghiTuan($thang, $nam);

            $ngay_dau = new DateTime("$nam-$thang-01");
            $ngay_cuoi = new DateTime($ngay_dau->format('Y-m-t'));

            // Phân loại nhân viên theo vai trò
            $nv_quan_ly = [];
            $nv_thuong = [];
            
            foreach ($nhan_viens as $nv) {
                if (in_array($nv['vai_tro'], ['NHAN_SU', 'QUAN_LY'])) {
                    $nv_quan_ly[] = $nv;
                } else {
                    $nv_thuong[] = $nv;
                }
            }

            // Random ngày nghỉ cho nhân viên thường trong tháng
            $so_ngay_trong_thang = (int)$ngay_cuoi->format('j');
            $so_ngay_nghi_nv_thuong = $so_chu_nhat; // Mặc định nghỉ bằng số CN
            
            if ($so_ngay_nghi_tuan == 2) {
                // Nếu tháng có >= 5 CN, mỗi NV thường được nghỉ 5 ngày
                $so_ngay_nghi_nv_thuong = 5;
            }
            
            // Danh sách các ngày trong tháng (trừ CN để tránh trùng)
            $ngay_trong_thang = [];
            $chu_nhat_trong_thang = [];
            $current_temp = clone $ngay_dau;
            while ($current_temp <= $ngay_cuoi) {
                $ngay_str = $current_temp->format('Y-m-d');
                if ((int)$current_temp->format('w') == 0) {
                    $chu_nhat_trong_thang[] = $ngay_str;
                } else {
                    $ngay_trong_thang[] = $ngay_str;
                }
                $current_temp->modify('+1 day');
            }
            
            // Random ngày nghỉ cho từng nhân viên thường
            $ngay_nghi_cua_nv = [];
            foreach ($nv_thuong as $nv) {
                // Nghỉ các Chủ Nhật + thêm ngày random
                $ngay_nghi = $chu_nhat_trong_thang;
                
                // Nếu cần nghỉ thêm (tháng có >= 5 CN)
                $so_ngay_them = $so_ngay_nghi_nv_thuong - count($chu_nhat_trong_thang);
                if ($so_ngay_them > 0 && count($ngay_trong_thang) > 0) {
                    $ngay_random = array_rand(array_flip($ngay_trong_thang), min($so_ngay_them, count($ngay_trong_thang)));
                    if (!is_array($ngay_random)) {
                        $ngay_random = [$ngay_random];
                    }
                    $ngay_nghi = array_merge($ngay_nghi, $ngay_random);
                }
                
                $ngay_nghi_cua_nv[$nv['ma_nhan_vien']] = $ngay_nghi;
            }

            $current = clone $ngay_dau;
            $day_counter = 0;
            
            while ($current <= $ngay_cuoi) {
                $ngay = $current->format('Y-m-d');
                $thu = (int)$current->format('w');

                // Lọc nhân viên nghỉ cố định
                $nv_nghi_ngay_nay = array_filter($nhan_vien_nghi_co_dinh, function($item) use ($ngay) {
                    return $item['ngay'] == $ngay;
                });
                $ma_nv_nghi_co_dinh = array_column($nv_nghi_ngay_nay, 'ma_nhan_vien');

                $ma_ca = $config[$thu] ?? 1;
                $so_nv_can = $so_nv_moi_ca[$ma_ca] ?? 1;

                // Xử lý theo vai trò
                if ($thu == 0) {
                    // Chủ Nhật - Quản lý nghỉ, nhân viên thường xem random
                    $nhan_viens_co_the_lam = array_filter($nv_thuong, function($nv) use ($ma_nv_nghi_co_dinh, $ngay, $ngay_nghi_cua_nv) {
                        if (in_array($nv['ma_nhan_vien'], $ma_nv_nghi_co_dinh)) return false;
                        if (in_array($ngay, $ngay_nghi_cua_nv[$nv['ma_nhan_vien']] ?? [])) return false;
                        return true;
                    });
                } else {
                    // Ngày thường
                    $nv_ql_co_the_lam = array_filter($nv_quan_ly, function($nv) use ($ma_nv_nghi_co_dinh) {
                        return !in_array($nv['ma_nhan_vien'], $ma_nv_nghi_co_dinh);
                    });
                    
                    $nv_thuong_co_the_lam = array_filter($nv_thuong, function($nv) use ($ma_nv_nghi_co_dinh, $ngay, $ngay_nghi_cua_nv) {
                        if (in_array($nv['ma_nhan_vien'], $ma_nv_nghi_co_dinh)) return false;
                        if (in_array($ngay, $ngay_nghi_cua_nv[$nv['ma_nhan_vien']] ?? [])) return false;
                        return true;
                    });
                    
                    $nhan_viens_co_the_lam = array_merge($nv_ql_co_the_lam, $nv_thuong_co_the_lam);
                }

                // Chọn nhân viên
                $nhan_viens_lam = $this->chonNhanVienLuanPhien($nhan_viens_co_the_lam, $so_nv_can, $day_counter);

                foreach ($nhan_viens_lam as $nv) {
                    $this->themLichLamViec($nv['ma_nhan_vien'], $ma_ca, $ngay);
                }

                $day_counter++;
                $current->modify('+1 day');
            }

            $this->conn->commit();
            return [
                'success' => true,
                'message' => "Đã tạo lịch tháng $thang/$nam (Mỗi NV quản lý nghỉ $so_chu_nhat CN, NV thường nghỉ $so_ngay_nghi_nv_thuong ngày)"
            ];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Chọn nhân viên luân phiên công bằng
     */
    private function chonNhanVienLuanPhien($nhan_viens, $so_luong, $day_counter) {
        $nhan_viens = array_values($nhan_viens);
        $total = count($nhan_viens);
        $result = [];
        
        for ($i = 0; $i < min($so_luong, $total); $i++) {
            $index = ($day_counter + $i) % $total;
            $nv = $nhan_viens[$index];
            $nv['index'] = $index;
            $result[] = $nv;
        }
        
        return $result;
    }

    /**
     * Thêm / cập nhật lịch làm việc
     */
    private function themLichLamViec($ma_nhan_vien, $ma_ca, $ngay) {
        $stmt = $this->conn->prepare(
            "INSERT INTO lichlamviec(ma_nhan_vien, ma_ca, ngay_lam)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE ma_ca = VALUES(ma_ca)"
        );
        $stmt->bind_param("iis", $ma_nhan_vien, $ma_ca, $ngay);
        return $stmt->execute();
    }

    /**
     * Lấy lịch làm việc của nhân viên
     */
    public function getLichLamViec($ma_nhan_vien, $tu_ngay, $den_ngay) {
        $stmt = $this->conn->prepare(
            "SELECT l.*, c.ten_ca, c.gio_bat_dau, c.gio_ket_thuc
             FROM lichlamviec l
             JOIN CaLamViec c ON l.ma_ca = c.ma_ca
             WHERE l.ma_nhan_vien = ?
             AND l.ngay_lam BETWEEN ? AND ?
             ORDER BY l.ngay_lam"
        );
        $stmt->bind_param("iss", $ma_nhan_vien, $tu_ngay, $den_ngay);
        $stmt->execute();

        $data = [];
        $rs = $stmt->get_result();
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Xóa lịch làm việc theo tháng
     */
    public function xoaLichThang($ma_phong_ban, $thang, $nam) {
        $ngay_dau  = "$nam-$thang-01";
        $ngay_cuoi = date('Y-m-t', strtotime($ngay_dau));

        $stmt = $this->conn->prepare(
            "DELETE l FROM lichlamviec l
             JOIN NhanVien n ON l.ma_nhan_vien = n.ma_nhan_vien
             WHERE n.ma_phong_ban = ?
             AND l.ngay_lam BETWEEN ? AND ?"
        );
        $stmt->bind_param("sss", $ma_phong_ban, $ngay_dau, $ngay_cuoi);
        return $stmt->execute();
    }

    /**
     * Đổi ca làm việc
     */
    public function doiCaLamViec($ma_lich, $ma_ca_moi, $ly_do, $nguoi_doi) {
        try {
            $this->conn->begin_transaction();

            $stmt = $this->conn->prepare(
                "UPDATE lichlamviec
                 SET ma_ca = ?, ghi_chu = CONCAT(IFNULL(ghi_chu,''),' | ',?)
                 WHERE ma_lich = ?"
            );
            $stmt->bind_param("isi", $ma_ca_moi, $ly_do, $ma_lich);
            $stmt->execute();

            $this->conn->commit();
            return ['success' => true, 'message' => 'Đổi ca thành công'];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Đổi ca giữa 2 nhân viên
     */
    public function doiCaGiua2NhanVien($ma_lich_1, $ma_lich_2, $ma_nhan_vien1, $ma_nhan_vien2, $ly_do, $nguoi_doi) {
        try {
            $this->conn->begin_transaction();

            $this->conn->query("UPDATE lichlamviec SET ma_nhan_vien = $ma_nhan_vien2 WHERE ma_lich = $ma_lich_1");
            $this->conn->query("UPDATE lichlamviec SET ma_nhan_vien = $ma_nhan_vien1 WHERE ma_lich = $ma_lich_2");

            $this->conn->commit();
            return ['success' => true, 'message' => 'Đổi ca thành công'];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}