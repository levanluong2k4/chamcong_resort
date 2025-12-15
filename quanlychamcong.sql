-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 13, 2025 lúc 03:22 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlychamcong`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `calamviec`
--

CREATE TABLE `calamviec` (
  `ma_ca` int(11) NOT NULL,
  `ten_ca` varchar(50) NOT NULL COMMENT 'Ca Sáng, Ca Chiều, Ca Đêm, Ca Hành chính',
  `gio_bat_dau` time NOT NULL COMMENT 'Giờ bắt đầu ca làm việc',
  `gio_ket_thuc` time NOT NULL COMMENT 'Giờ kết thúc ca làm việc',
  `gio_bat_dau_nghi` time DEFAULT NULL COMMENT 'Giờ bắt đầu nghỉ giữa ca (nếu có)',
  `gio_ket_thuc_nghi` time DEFAULT NULL COMMENT 'Giờ kết thúc nghỉ giữa ca',
  `he_so_luong` decimal(3,2) DEFAULT 1.00 COMMENT 'Hệ số tính lương: 1.0 (bình thường), 1.5 (tăng ca), 2.0 (lễ/tết)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhinh`
--

CREATE TABLE `cauhinh` (
  `ma_cau_hinh` int(11) NOT NULL,
  `ten_tham_so` varchar(100) NOT NULL COMMENT 'Tên tham số cấu hình',
  `gia_tri` varchar(255) NOT NULL COMMENT 'Giá trị tham số',
  `mo_ta` text DEFAULT NULL COMMENT 'Mô tả ý nghĩa của tham số',
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cấu hình tham số hệ thống';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chamcong`
--

CREATE TABLE `chamcong` (
  `ma_cham_cong` int(11) NOT NULL,
  `ma_nhan_vien` int(11) NOT NULL COMMENT 'Nhân viên chấm công',
  `ngay_lam` date NOT NULL COMMENT 'Ngày chấm công',
  `gio_vao` datetime DEFAULT NULL COMMENT 'Thời gian chấm công vào',
  `gio_ra` datetime DEFAULT NULL COMMENT 'Thời gian chấm công ra',
  `trang_thai` enum('DI_LAM','DI_TRE','VE_SOM','VANG_MAT') DEFAULT 'VANG_MAT' COMMENT 'Trạng thái: Đi làm đúng giờ, Đi trễ, Về sớm, Vắng mặt',
  `da_sua_thu_cong` tinyint(1) DEFAULT 0 COMMENT 'Đánh dấu nếu HR đã sửa dữ liệu',
  `ghi_chu` varchar(255) DEFAULT NULL COMMENT 'Ghi chú về tình trạng chấm công',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dữ liệu chấm công vào/ra thực tế';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donnghiphep`
--

CREATE TABLE `donnghiphep` (
  `ma_don` int(11) NOT NULL,
  `ma_nhan_vien` int(11) NOT NULL COMMENT 'Nhân viên xin nghỉ',
  `loai_nghi` varchar(50) DEFAULT NULL COMMENT 'Nghỉ phép năm, Nghỉ ốm, Nghỉ việc riêng, Nghỉ không lương',
  `ngay_bat_dau` datetime NOT NULL COMMENT 'Thời gian bắt đầu nghỉ',
  `ngay_ket_thuc` datetime NOT NULL COMMENT 'Thời gian kết thúc nghỉ',
  `ly_do` text DEFAULT NULL COMMENT 'Lý do xin nghỉ',
  `trang_thai` enum('CHO_DUYET','DA_DUYET','TU_CHOI') DEFAULT 'CHO_DUYET' COMMENT 'Trạng thái đơn: Chờ duyệt, Đã duyệt, Từ chối',
  `nguoi_duyet` int(11) DEFAULT NULL COMMENT 'ID người duyệt đơn (Quản lý/Giám đốc)',
  `ly_do_tu_choi` varchar(255) DEFAULT NULL COMMENT 'Lý do từ chối (nếu có)',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_xu_ly` timestamp NULL DEFAULT NULL COMMENT 'Thời gian xử lý đơn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý đơn xin nghỉ phép';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kehoachtangca`
--

CREATE TABLE `kehoachtangca` (
  `ma_ke_hoach` int(11) NOT NULL,
  `ma_phong_ban` int(11) NOT NULL COMMENT 'Phòng ban cần tăng ca',
  `nguoi_dang_ky` int(11) NOT NULL COMMENT 'ID Quản lý bộ phận đăng ký',
  `ngay_tang_ca` date NOT NULL COMMENT 'Ngày dự kiến tăng ca',
  `so_nguoi_can` int(11) NOT NULL COMMENT 'Số lượng nhân viên cần tăng ca',
  `so_gio_du_kien` decimal(4,2) NOT NULL COMMENT 'Số giờ tăng ca dự kiến',
  `ly_do` text DEFAULT NULL COMMENT 'Lý do cần tăng ca (VD: Khách đông, sự kiện đặc biệt...)',
  `trang_thai` enum('CHO_DUYET','DA_DUYET','TU_CHOI') DEFAULT 'CHO_DUYET' COMMENT 'Trạng thái: Chờ HR duyệt -> Đã duyệt -> Từ chối',
  `nhan_su_duyet` int(11) DEFAULT NULL COMMENT 'ID Nhân sự duyệt',
  `ly_do_tu_choi` varchar(255) DEFAULT NULL COMMENT 'Lý do từ chối (nếu có)',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_duyet` timestamp NULL DEFAULT NULL COMMENT 'Thời gian HR duyệt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Kế hoạch tăng ca do Quản lý đăng ký, HR duyệt';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichlamviec`
--

CREATE TABLE `lichlamviec` (
  `ma_lich` int(11) NOT NULL,
  `ma_nhan_vien` int(11) NOT NULL COMMENT 'Nhân viên được xếp ca',
  `ma_ca` int(11) NOT NULL COMMENT 'Ca làm việc được phân công',
  `ngay_lam` date NOT NULL COMMENT 'Ngày làm việc',
  `ghi_chu` varchar(255) DEFAULT NULL COMMENT 'Ghi chú đặc biệt (VD: Làm thêm giờ, thay ca...)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch phân ca làm việc hàng ngày';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichsusuachamcong`
--

CREATE TABLE `lichsusuachamcong` (
  `ma_lich_su` int(11) NOT NULL,
  `ma_cham_cong` int(11) NOT NULL COMMENT 'ID bản ghi chấm công bị sửa',
  `nguoi_sua` int(11) NOT NULL COMMENT 'ID người thực hiện sửa (HR/Admin)',
  `gio_vao_cu` datetime DEFAULT NULL COMMENT 'Giờ vào cũ (trước khi sửa)',
  `gio_vao_moi` datetime DEFAULT NULL COMMENT 'Giờ vào mới (sau khi sửa)',
  `gio_ra_cu` datetime DEFAULT NULL COMMENT 'Giờ ra cũ',
  `gio_ra_moi` datetime DEFAULT NULL COMMENT 'Giờ ra mới',
  `ly_do_sua` varchar(255) NOT NULL COMMENT 'Lý do sửa đổi (bắt buộc)',
  `thoi_gian_sua` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử thay đổi dữ liệu chấm công';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `ma_nhan_vien` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL COMMENT 'Họ và tên đầy đủ',
  `email` varchar(100) NOT NULL COMMENT 'Email đăng nhập hệ thống',
  `mat_khau_hash` varchar(255) NOT NULL COMMENT 'Mật khẩu đã mã hóa',
  `vai_tro` enum('NHAN_VIEN','QUAN_LY','NHAN_SU','GIAM_DOC') DEFAULT 'NHAN_VIEN' COMMENT 'Quyền hạn: Nhân viên, Quản lý phòng ban, Nhân sự, Giám đốc',
  `ma_phong_ban` int(11) DEFAULT NULL COMMENT 'Phòng ban đang làm việc',
  `ma_nguoi_quan_ly` int(11) DEFAULT NULL COMMENT 'ID người quản lý trực tiếp (để duyệt đơn)',
  `so_dien_thoai` varchar(15) DEFAULT NULL COMMENT 'Số điện thoại liên hệ',
  `anh_dai_den` varchar(200) NOT NULL,
  `ngay_vao_lam` date DEFAULT NULL COMMENT 'Ngày bắt đầu làm việc',
  `trang_thai` enum('DANG_LAM','NGHI_VIEC','TAM_NGHI') DEFAULT 'DANG_LAM',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông tin nhân viên và phân quyền hệ thống';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongban`
--

CREATE TABLE `phongban` (
  `ma_phong_ban` int(11) NOT NULL,
  `ten_phong_ban` varchar(100) NOT NULL COMMENT 'Tên phòng ban: Lễ tân, Buồng phòng, F&B...',
  `mo_ta` text DEFAULT NULL COMMENT 'Mô tả chức năng và nhiệm vụ của phòng ban',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý các phòng ban trong resort';

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `calamviec`
--
ALTER TABLE `calamviec`
  ADD PRIMARY KEY (`ma_ca`);

--
-- Chỉ mục cho bảng `cauhinh`
--
ALTER TABLE `cauhinh`
  ADD PRIMARY KEY (`ma_cau_hinh`),
  ADD UNIQUE KEY `ten_tham_so` (`ten_tham_so`);

--
-- Chỉ mục cho bảng `chamcong`
--
ALTER TABLE `chamcong`
  ADD PRIMARY KEY (`ma_cham_cong`),
  ADD UNIQUE KEY `ma_nhan_vien` (`ma_nhan_vien`,`ngay_lam`,`gio_vao`);

--
-- Chỉ mục cho bảng `donnghiphep`
--
ALTER TABLE `donnghiphep`
  ADD PRIMARY KEY (`ma_don`),
  ADD KEY `ma_nhan_vien` (`ma_nhan_vien`),
  ADD KEY `nguoi_duyet` (`nguoi_duyet`);

--
-- Chỉ mục cho bảng `kehoachtangca`
--
ALTER TABLE `kehoachtangca`
  ADD PRIMARY KEY (`ma_ke_hoach`),
  ADD KEY `ma_phong_ban` (`ma_phong_ban`),
  ADD KEY `nguoi_dang_ky` (`nguoi_dang_ky`),
  ADD KEY `nhan_su_duyet` (`nhan_su_duyet`);

--
-- Chỉ mục cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  ADD PRIMARY KEY (`ma_lich`),
  ADD UNIQUE KEY `ma_nhan_vien` (`ma_nhan_vien`,`ngay_lam`),
  ADD KEY `ma_ca` (`ma_ca`);

--
-- Chỉ mục cho bảng `lichsusuachamcong`
--
ALTER TABLE `lichsusuachamcong`
  ADD PRIMARY KEY (`ma_lich_su`),
  ADD KEY `ma_cham_cong` (`ma_cham_cong`),
  ADD KEY `nguoi_sua` (`nguoi_sua`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`ma_nhan_vien`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `ma_phong_ban` (`ma_phong_ban`),
  ADD KEY `ma_nguoi_quan_ly` (`ma_nguoi_quan_ly`);

--
-- Chỉ mục cho bảng `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`ma_phong_ban`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `calamviec`
--
ALTER TABLE `calamviec`
  MODIFY `ma_ca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `cauhinh`
--
ALTER TABLE `cauhinh`
  MODIFY `ma_cau_hinh` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chamcong`
--
ALTER TABLE `chamcong`
  MODIFY `ma_cham_cong` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `donnghiphep`
--
ALTER TABLE `donnghiphep`
  MODIFY `ma_don` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `kehoachtangca`
--
ALTER TABLE `kehoachtangca`
  MODIFY `ma_ke_hoach` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  MODIFY `ma_lich` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lichsusuachamcong`
--
ALTER TABLE `lichsusuachamcong`
  MODIFY `ma_lich_su` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `ma_nhan_vien` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `phongban`
--
ALTER TABLE `phongban`
  MODIFY `ma_phong_ban` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chamcong`
--
ALTER TABLE `chamcong`
  ADD CONSTRAINT `chamcong_ibfk_1` FOREIGN KEY (`ma_nhan_vien`) REFERENCES `nhanvien` (`ma_nhan_vien`);

--
-- Các ràng buộc cho bảng `donnghiphep`
--
ALTER TABLE `donnghiphep`
  ADD CONSTRAINT `donnghiphep_ibfk_1` FOREIGN KEY (`ma_nhan_vien`) REFERENCES `nhanvien` (`ma_nhan_vien`),
  ADD CONSTRAINT `donnghiphep_ibfk_2` FOREIGN KEY (`nguoi_duyet`) REFERENCES `nhanvien` (`ma_nhan_vien`);

--
-- Các ràng buộc cho bảng `kehoachtangca`
--
ALTER TABLE `kehoachtangca`
  ADD CONSTRAINT `kehoachtangca_ibfk_1` FOREIGN KEY (`ma_phong_ban`) REFERENCES `phongban` (`ma_phong_ban`),
  ADD CONSTRAINT `kehoachtangca_ibfk_2` FOREIGN KEY (`nguoi_dang_ky`) REFERENCES `nhanvien` (`ma_nhan_vien`),
  ADD CONSTRAINT `kehoachtangca_ibfk_3` FOREIGN KEY (`nhan_su_duyet`) REFERENCES `nhanvien` (`ma_nhan_vien`);

--
-- Các ràng buộc cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  ADD CONSTRAINT `lichlamviec_ibfk_1` FOREIGN KEY (`ma_nhan_vien`) REFERENCES `nhanvien` (`ma_nhan_vien`),
  ADD CONSTRAINT `lichlamviec_ibfk_2` FOREIGN KEY (`ma_ca`) REFERENCES `calamviec` (`ma_ca`);

--
-- Các ràng buộc cho bảng `lichsusuachamcong`
--
ALTER TABLE `lichsusuachamcong`
  ADD CONSTRAINT `lichsusuachamcong_ibfk_1` FOREIGN KEY (`ma_cham_cong`) REFERENCES `chamcong` (`ma_cham_cong`),
  ADD CONSTRAINT `lichsusuachamcong_ibfk_2` FOREIGN KEY (`nguoi_sua`) REFERENCES `nhanvien` (`ma_nhan_vien`);

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`ma_phong_ban`) REFERENCES `phongban` (`ma_phong_ban`),
  ADD CONSTRAINT `nhanvien_ibfk_2` FOREIGN KEY (`ma_nguoi_quan_ly`) REFERENCES `nhanvien` (`ma_nhan_vien`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
