INSERT INTO chamcong (ma_nhan_vien, ngay_lam, trang_thai, ghi_chu)
SELECT 
    llv.ma_nhan_vien,
    llv.ngay_lam,
    'VANG_MAT',  -- Mặc định là vắng mặt, sẽ cập nhật khi chấm công
    'Tạo bảng chấm công tự động cho phòng Nhà Hàng'
FROM lichlamviec llv
INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
WHERE nv.ma_phong_ban = 3  -- Phòng ban Nhà Hàng
AND llv.ngay_lam = '2025-12-29'
AND NOT EXISTS (
    -- Chỉ tạo khi CHƯA có bản ghi chấm công
    SELECT 1 FROM chamcong cc 
    WHERE cc.ma_nhan_vien = llv.ma_nhan_vien 
    AND cc.ngay_lam = llv.ngay_lam
);

-- =====================================================
-- Bước 3: Kiểm tra kết quả
-- =====================================================

SELECT 
    cc.ma_cham_cong,
    cc.ma_nhan_vien,
    nv.ho_ten,
    pb.ten_phong_ban,
    cc.ngay_lam,
    ca.ten_ca,
    ca.gio_bat_dau AS gio_ca_bat_dau,
    ca.gio_ket_thuc AS gio_ca_ket_thuc,
    cc.gio_vao,
    cc.gio_ra,
    cc.trang_thai,
    CASE cc.trang_thai
        WHEN 'DI_LAM' THEN '✅ Đi làm đúng giờ'
        WHEN 'DI_TRE' THEN CONCAT('⏰ Đi trễ ', cc.so_phut_tre, ' phút')
        WHEN 'VE_SOM' THEN CONCAT('🏃 Về sớm ', cc.so_phut_ve_som, ' phút')
        WHEN 'VANG_MAT' THEN '❌ Vắng mặt'
        WHEN 'NGHI_PHEP' THEN '📋 Nghỉ phép'
        WHEN 'QUEN_CHAM_CONG' THEN '⚠️ Quên chấm công'
        ELSE cc.trang_thai
    END AS trang_thai_hien_thi,
    cc.ghi_chu,
    cc.ngay_tao
FROM chamcong cc
INNER JOIN nhanvien nv ON cc.ma_nhan_vien = nv.ma_nhan_vien
INNER JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
LEFT JOIN lichlamviec llv ON cc.ma_nhan_vien = llv.ma_nhan_vien 
    AND cc.ngay_lam = llv.ngay_lam
LEFT JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
WHERE pb.ma_phong_ban = 3  -- Phòng ban Nhà Hàng
AND cc.ngay_lam = '2025-12-29'
ORDER BY nv.ho_ten, ca.gio_bat_dau;

-- =====================================================
-- Bước 4: CẬP NHẬT GIỜ CHẤM CÔNG THEO LỊCH CỐ ĐỊNH
-- Mô phỏng các trường hợp thực tế
-- =====================================================

-- 4.1. Nhân viên ĐI LÀM ĐÚNG GIỜ (ca sáng 6h-14h)
UPDATE chamcong cc
INNER JOIN lichlamviec llv ON cc.ma_nhan_vien = llv.ma_nhan_vien AND cc.ngay_lam = llv.ngay_lam
INNER JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
INNER JOIN nhanvien nv ON cc.ma_nhan_vien = nv.ma_nhan_vien
SET 
    cc.gio_vao = CONCAT(cc.ngay_lam, ' ', ca.gio_bat_dau),
    cc.gio_ra = CONCAT(cc.ngay_lam, ' ', ca.gio_ket_thuc),
    cc.ghi_chu = 'Đi làm đúng giờ'
WHERE nv.ma_phong_ban = 3
AND cc.ngay_lam = '2025-12-29'
AND ca.ma_ca = 1  -- Ca sáng
AND nv.ma_nhan_vien IN (5,93,91,92,10,6,9,10,89, 95, 90, 93); 


UPDATE chamcong cc
INNER JOIN lichlamviec llv ON cc.ma_nhan_vien = llv.ma_nhan_vien AND cc.ngay_lam = llv.ngay_lam
INNER JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
INNER JOIN nhanvien nv ON cc.ma_nhan_vien = nv.ma_nhan_vien
SET 
    cc.gio_vao = CONCAT(cc.ngay_lam, ' ', ca.gio_bat_dau),
    cc.gio_ra = CONCAT(cc.ngay_lam, ' ', ca.gio_ket_thuc),
    cc.ghi_chu = 'Đi làm đúng giờ'
WHERE nv.ma_phong_ban = 3
AND cc.ngay_lam = '2025-12-29'
AND ca.ma_ca = 2  -- Ca sáng
AND nv.ma_nhan_vien IN (89,90,94,21,76,25,83,77); -- 4 nhân viên ca sáng



