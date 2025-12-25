<?php
require_once(__DIR__ . '/../Model/Connect.php');
require_once __DIR__ . '/../Model/quanlybophan/ModelShiftSchedule.php';

class ShiftScheduleController extends Connect
{

    /**
     * Lấy thông tin số Chủ Nhật trong tháng
     */
    public function getThongTinChuNhat()
    {
        header('Content-Type: application/json');

        $thang = $_GET['thang'] ?? date('m');
        $nam = $_GET['nam'] ?? date('Y');

        $model = new ShiftScheduleModel();

        // Kiểm tra và tạo dữ liệu nếu chưa có
        $stmt = $this->conn->prepare(
            "SELECT * FROM thongkechunhat WHERE thang = ? AND nam = ?"
        );
        $stmt->bind_param("ii", $thang, $nam);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            // Tạo dữ liệu mới
            $this->conn->query("CALL TinhSoChuNhatTrongThang($thang, $nam)");
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
        }

        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Hiển thị trang tạo lịch
     */
    public function index()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            header('Location: ?controller=Login');
            exit;
        }

        // Lấy thông tin user và phòng ban
        $stmt = $this->conn->prepare(
            "SELECT nv.*, pb.ten_phong_ban 
             FROM NhanVien nv
             LEFT JOIN PhongBan pb ON nv.ma_phong_ban = pb.ma_phong_ban
             WHERE nv.ma_nhan_vien = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!in_array($user['vai_tro'], ['QUAN_LY', 'NHAN_SU', 'ADMIN'])) {
            die('Bạn không có quyền truy cập');
        }

        $model = new ShiftScheduleModel();
        $shifts = $model->getAllShifts();

        // Lấy danh sách nhân viên trong phòng ban
        $nhan_viens = $model->getNhanVienTheoPhongBan($user['ma_phong_ban']);

        require_once __DIR__ . '/../View/department/taocalamviec.php';
    }

    /**
     * Tạo lịch tự động (API)
     */
    public function taoLichTuDong()
    {
        header('Content-Type: application/json');

        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        // Lấy phòng ban của user
        $stmt = $this->conn->prepare("SELECT ma_phong_ban, vai_tro FROM nhanvien WHERE ma_nhan_vien = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!in_array($user['vai_tro'], ['QUAN_LY', 'NHAN_SU', 'ADMIN'])) {
            echo json_encode(['success' => false, 'message' => 'Không có quyền']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $loai_tao = $data['loai_tao'] ?? 'thang'; // 'thang' hoặc 'tuan'
        $thang = $data['thang'] ?? date('m');
        $nam = $data['nam'] ?? date('Y');
        $tuan = $data['tuan'] ?? null; // Tuần thứ mấy trong tháng (1-5)

        $config = $data['config'] ?? [
            0 => null,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 1,
            5 => 1,
            6 => 2,
        ];

        // Số nhân viên mỗi ca
        $so_nv_moi_ca = $data['so_nv_moi_ca'] ?? [];

        // Danh sách nhân viên nghỉ cố định
        $nhan_vien_nghi_co_dinh = $data['nhan_vien_nghi_co_dinh'] ?? [];

        $model = new ShiftScheduleModel();

        // Kiểm tra tuần hiện tại
        // Kiểm tra tuần hiện tại
        if ($loai_tao === 'tuan') {
            $ngay_hien_tai = new DateTime();
            $thang_hien_tai = (int)$ngay_hien_tai->format('m');
            $nam_hien_tai = (int)$ngay_hien_tai->format('Y');

            // Tính tuần hiện tại trong tháng
            $ngay_bat_dau = clone $ngay_hien_tai;
            $ngay_bat_dau->modify('this week');
            $tuan_hien_tai = ceil($ngay_bat_dau->format('j') / 7);

            // So sánh theo thứ tự: năm -> tháng -> tuần
            $is_qua_khu = false;

            if ($nam < $nam_hien_tai) {
                $is_qua_khu = true;
            } elseif ($nam == $nam_hien_tai) {
                if ($thang < $thang_hien_tai) {
                    $is_qua_khu = true;
                } elseif ($thang == $thang_hien_tai && $tuan <= $tuan_hien_tai) {
                    $is_qua_khu = true;
                }
            }

            if ($is_qua_khu) {
                echo json_encode([
                    'success' => false,
                    'message' => "Không thể tạo lịch cho tuần trong quá khứ! (Tuần hiện tại: Tuần $tuan_hien_tai - Tháng $thang_hien_tai/$nam_hien_tai)"
                ]);
                return;
            }

            // Kiểm tra tuần đã có lịch chưa
            $da_co_lich = $model->kiemTraTuanDaCoLich($user['ma_phong_ban'], $thang, $nam, $tuan);
            if ($da_co_lich) {
                echo json_encode([
                    'success' => false,
                    'message' => "Tuần $tuan (Tháng $thang/$nam) đã có lịch làm việc"
                ]);
                return;
            }
        }

        // Tạo lịch
        if ($loai_tao === 'tuan') {
            $result = $model->taoLichTheoTuan(
                $user['ma_phong_ban'],
                $thang,
                $nam,
                $tuan,
                $config,
                $so_nv_moi_ca,
                $nhan_vien_nghi_co_dinh
            );
        } else {
            $result = $model->taoLichTheoThang(
                $user['ma_phong_ban'],
                $thang,
                $nam,
                $config,
                $so_nv_moi_ca,
                $nhan_vien_nghi_co_dinh
            );
        }

        echo json_encode($result);
    }

    /**
     * Xem lịch làm việc theo nhân viên
     */
    public function xemLich()
    {
        header('Content-Type: application/json');

        $model = new ShiftScheduleModel();

        $ma_nhan_vien = $_GET['ma_nhan_vien'] ?? null;
        $thang = $_GET['thang'] ?? date('m');
        $nam   = $_GET['nam'] ?? date('Y');

        $tu_ngay  = "$nam-$thang-01";
        $den_ngay = date('Y-m-t', strtotime($tu_ngay));

        $lich = $model->getLichLamViec($ma_nhan_vien, $tu_ngay, $den_ngay);

        echo json_encode([
            'success' => true,
            'data' => $lich
        ]);
    }

    /**
     * Đổi ca làm việc
     */
    public function doiCa()
    {
        header('Content-Type: application/json');

        $nguoi_doi = $_SESSION['user_id'] ?? null;
        if (!$nguoi_doi) {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $ma_lich   = $data['ma_lich'] ?? null;
        $ma_ca_moi = $data['ma_ca_moi'] ?? null;
        $ly_do     = $data['ly_do'] ?? 'Không có lý do';

        if (!$ma_lich || !$ma_ca_moi) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            return;
        }

        $model = new ShiftScheduleModel();
        $result = $model->doiCaLamViec($ma_lich, $ma_ca_moi, $ly_do, $nguoi_doi);

        echo json_encode($result);
    }

    /**
     * Đổi ca giữa 2 nhân viên
     */
    public function doiCaGiua2NV()
    {
        header('Content-Type: application/json');

        $nguoi_doi = $_SESSION['user_id'] ?? null;
        if (!$nguoi_doi) {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $ma_lich_1 = $data['ma_lich_1'] ?? null;
        $ma_lich_2 = $data['ma_lich_2'] ?? null;
        $ma_nhan_vien1 = $data['ma_nhan_vien1'] ?? null;
        $ma_nhan_vien2 = $data['ma_nhan_vien2'] ?? null;
        $ly_do     = $data['ly_do'] ?? 'Đổi ca giữa 2 nhân viên';

        if (!$ma_lich_1 || !$ma_lich_2) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            return;
        }

        $model = new ShiftScheduleModel();
        $result = $model->doiCaGiua2NhanVien($ma_lich_1, $ma_lich_2, $ma_nhan_vien1, $ma_nhan_vien2, $ly_do, $nguoi_doi);

        echo json_encode($result);
    }

    /**
     * Xem lịch phòng ban (chỉ quản lý)
     */
    public function xemLichphongban()
    {
        header('Content-Type: application/json');

        $nguoi_xem = $_SESSION['user_id'] ?? null;
        if (!$nguoi_xem) {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
            return;
        }

        $stmt = $this->conn->prepare("SELECT vai_tro, ma_phong_ban FROM NhanVien WHERE ma_nhan_vien = ?");
        $stmt->bind_param("s", $nguoi_xem);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!in_array($user['vai_tro'], ['QUAN_LY', 'NHAN_SU', 'ADMIN'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền']);
            return;
        }

        $thang = $_GET['thang'] ?? date('m');
        $nam   = $_GET['nam'] ?? date('Y');
        $ma_phong_ban = $user['ma_phong_ban'];

        $tu_ngay  = "$nam-$thang-01";
        $den_ngay = date('Y-m-t', strtotime($tu_ngay));

        $stmt = $this->conn->prepare(
            "SELECT l.*, n.ho_ten, n.vai_tro, c.ten_ca, c.gio_bat_dau, c.gio_ket_thuc
             FROM LichLamViec l
             JOIN nhanvien n ON l.ma_nhan_vien = n.ma_nhan_vien
             JOIN calamviec c ON l.ma_ca = c.ma_ca
             WHERE n.ma_phong_ban = ?
             AND l.ngay_lam BETWEEN ? AND ?
             ORDER BY l.ngay_lam, n.ho_ten"
        );

        $stmt->bind_param("sss", $ma_phong_ban, $tu_ngay, $den_ngay);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $data]);
    }
}
