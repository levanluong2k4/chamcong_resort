<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Rosa Alba - Tạo Đơn Xin Nghỉ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/lichtuan.css">


</style>
    <style>
        /* CSS Bổ sung */
        .widget-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: none;
            margin-bottom: 20px;
        }

        .widget-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .widget-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .widget-body {
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #718096;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #4a5568;
        }

        #totalDuration {
            font-weight: bold;
            color: black;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>

<body>
   
<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
   


       


  

        <div class="content-area">
            <a href="?controller=leave&action=index"  style="background-color: white; color: black; text-decoration: none; padding:10px; border-radius:7px;">
                Quay lại 
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="widget-card">
                        <div class="widget-header">
                            <div class="widget-title">Thông Tin Đơn Nghỉ Phép</div>
                        </div>
                        <div class="widget-body">
                            <form action="?controller=leave&action=store" method="POST" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Họ và Tên</label>
                                        <input type="text" class="form-control bg-light"
                                            value="<?php echo $objEmployee->getHoTen(); ?>" readonly disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Phòng Ban</label>
                                        <input type="text" class="form-control bg-light" value="<?php
                                        // Code an toàn: Kiểm tra kiểu dữ liệu để tránh lỗi
                                        if (is_object($objDepartment) && method_exists($objDepartment, 'getTenPhongBan')) {
                                            echo $objDepartment->getTenPhongBan();
                                        } elseif (is_array($objDepartment)) {
                                            echo isset($objDepartment['ten_phong_ban']) ? $objDepartment['ten_phong_ban'] : 'Chưa cập nhật';
                                        } else {
                                            echo 'Chưa cập nhật';
                                        }
                                        ?>" readonly disabled>
                                    </div>
                                </div>
                                <div class="mb-4">


                                    <select name="loai_nghi" class="form-select" required>
                                        <option value="">Loại nghỉ phép</option>
                                        <option value="NGHI_PHEP_THANG">Nghỉ Phép Tháng</option>
                                        <option value="NGHI_OM">Nghỉ Ốm</option>
                                        <option value="NGHI_VIEC_RIENG">Nghỉ Việc Riêng</option>
                                        <option value="NGHI_CHE_DO">Nghỉ chế độ</option>
                                        <option value="NGHI_KHONG_LUONG">Nghỉ không lương</option>
                                      
                                     
                                    </select>
                                </div>


                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Ngày và giờ bắt đầu 
                                        <input type="text" name="ngay_bat_dau" id="ngay_bat_dau"
                                            class="form-control date-picker" placeholder="Chọn giờ bắt đầu..." required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Ngày & Giờ Kết thúc <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="ngay_ket_thuc" id="ngay_ket_thuc"
                                            class="form-control date-picker" placeholder="Chọn giờ kết thúc..."
                                            required>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small class="text-black">Tổng thời gian :  <span id="totalDuration"></span></small>
                                         
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Lý do xin nghỉ phép </label>
                                    <textarea name="ly_do" class="form-control" rows="4"
                                        placeholder="" required></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Ảnh , file:</label>
                                    <input type="file" name="file_dinh_kem" class="form-control">
                                 
                                </div>

                                <div class="d-flex justify-content-end gap-3 mt-5">
                                    <a href="?controller=leave&action=index" class="btn btn-light px-4 py-2 border">Hủy
                                        </a>
                                    <button type="submit" class="btn-gradient">
                                       Gửi Đơn
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>

    <script>
        // Cấu hình Flatpickr
        const config = {
            enableTime: true,           // Cho phép chọn giờ
            dateFormat: "Y-m-d H:i",    // Định dạng gửi về server (Năm-Tháng-Ngày Giờ:Phút 24h)
            time_24hr: true,            // BẮT BUỘC CHẾ ĐỘ 24 GIỜ
            locale: "vn",               // Tiếng Việt
            minuteIncrement: 30,        // Chỉnh bước nhảy phút là 30 (hoặc để 1 nếu muốn chi tiết)
            defaultDate: new Date(),    // Mặc định là giờ hiện tại
            onClose: function (selectedDates, dateStr, instance) {
                calculateDuration();    // Tính toán ngay khi đóng lịch
            }
        };

        // Kích hoạt cho cả 2 ô input
        flatpickr(".date-picker", config);

        // Hàm tính toán thời gian (Đã nâng cấp để đọc format của Flatpickr)
        function calculateDuration() {
            const startVal = document.getElementById('ngay_bat_dau').value;
            const endVal = document.getElementById('ngay_ket_thuc').value;
            const durationSpan = document.getElementById('totalDuration');

            if (startVal && endVal) {
                // Chuyển chuỗi string "2025-12-26 23:00" thành đối tượng Date
                const start = new Date(startVal);
                const end = new Date(endVal);

                if (end > start) {
                    const diffMs = end - start;
                    // Tính toán chi tiết
                    const totalMinutes = Math.floor(diffMs / (1000 * 60));

                    const days = Math.floor(totalMinutes / (24 * 60));
                    const hours = Math.floor((totalMinutes % (24 * 60)) / 60);
                    const minutes = totalMinutes % 60;

                    let result = [];
                    if (days > 0) result.push(`<b>${days}</b> ngày`);
                    if (hours > 0) result.push(`<b>${hours}</b> giờ`);
                    if (minutes > 0) result.push(`<b>${minutes}</b> phút`);

                    durationSpan.innerHTML = result.join(" ");
                    durationSpan.className = "text-primary fw-bold";
                } else if (end.getTime() === start.getTime()) {
                    durationSpan.textContent = "0 giờ";
                    durationSpan.className = "text-warning";
                } else {
                    durationSpan.textContent = "Lỗi: Ngày kết thúc phải sau ngày bắt đầu!";
                    durationSpan.className = "text-danger";
                }
            } else {
                durationSpan.textContent = "---";
                durationSpan.className = "";
            }
        }

        // Gọi tính toán lần đầu khi trang tải
        window.addEventListener('load', function () {
            calculateDuration();
        });
    </script>
</body>

</html>