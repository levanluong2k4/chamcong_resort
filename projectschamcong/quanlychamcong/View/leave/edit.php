<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Đơn Nghỉ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    
    <style>
        /* CSS bổ sung nhỏ để hiển thị text thời gian tính toán */
        #totalDuration {
            font-weight: bold;
            color: #0d6efd;
        }
        /* Readonly input style giống style cũ */
        .form-control[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
        }
    </style>
</head>

<body>
   

        <div class="content-area">
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-pen-fancy"></i> Form Cập Nhật Thông Tin
                    </div>
                </div>

                <div class="widget-body p-4">
                    <form action="?controller=leave&action=update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="ma_don" value="<?php echo $don['ma_don']; ?>">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Họ tên nhân viên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" value="<?php echo $objEmployee->getHoTen(); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Phòng ban</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                                    <input type="text" class="form-control" value="<?php echo $objDepartment->getTenPhongBan(); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Loại nghỉ</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-list"></i></span>
                                    <select name="loai_nghi" class="form-select" required>
                                        <option value="NGHI_PHEP_THANG" <?php echo ($don['loai_nghi'] == 'NGHI_PHEP_THANG') ? 'selected' : ''; ?>>Nghỉ Phép Tháng</option>
                                        <option value="NGHI_OM" <?php echo ($don['loai_nghi'] == 'NGHI_OM') ? 'selected' : ''; ?>>Nghỉ Ốm</option>
                                        <option value="NGHI_VIEC_RIENG" <?php echo ($don['loai_nghi'] == 'NGHI_VIEC_RIENG') ? 'selected' : ''; ?>>Nghỉ Việc Riêng</option>
                                        <option value="NGHI_PHEP_TUAN" <?php echo ($don['loai_nghi'] == 'NGHI_PHEP_TUAN') ? 'selected' : ''; ?>>Nghỉ Phép Tuần</option>
                                        <option value="NGHI_LE" <?php echo ($don['loai_nghi'] == 'NGHI_LE') ? 'selected' : ''; ?>>Nghỉ Lễ</option>
                                        <option value="NGHI_TET" <?php echo ($don['loai_nghi'] == 'NGHI_TET') ? 'selected' : ''; ?>>Nghỉ Tết</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Ngày bắt đầu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="text" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control date-picker" 
                                           value="<?php echo date('Y-m-d H:i', strtotime($don['ngay_bat_dau'])); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Ngày kết thúc</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-check"></i></span>
                                    <input type="text" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control date-picker" 
                                           value="<?php echo date('Y-m-d H:i', strtotime($don['ngay_ket_thuc'])); ?>" required>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted">Tổng thời gian dự kiến: <span id="totalDuration">---</span></small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Lý do nghỉ</label>
                            <textarea name="ly_do" class="form-control" rows="4" required style="resize: none;"><?php echo htmlspecialchars($don['ly_do']); ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">File đính kèm</label>
                            <?php if($don['file_dinh_kem']): ?>
                                <div class="alert alert-light border d-flex align-items-center mb-2">
                                    <i class="fas fa-paperclip me-2 text-primary"></i>
                                    <span class="text-truncate me-auto"><?php echo $don['file_dinh_kem']; ?></span>
                                    <span class="badge bg-secondary">Hiện tại</span>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="file_dinh_kem" class="form-control">
                            <div class="form-text">Để trống nếu bạn không muốn thay đổi file đính kèm.</div>
                        </div>

                        <hr class="my-4">

                        <div class="text-end">
                            <a href="?controller=leave&action=index" class="btn btn-secondary px-4 me-2">
                                <i class="fas fa-times me-1"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
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
            enableTime: true,           
            dateFormat: "Y-m-d H:i",    
            time_24hr: true,           
            locale: "vn",               
            minuteIncrement: 30,
            // Quan trọng: Không set defaultDate ở đây để nó lấy value từ PHP
            onClose: function(selectedDates, dateStr, instance) {
                calculateDuration();   
            }
        };

        // Kích hoạt cho cả 2 ô input
        flatpickr(".date-picker", config);

        // Hàm tính toán thời gian
        function calculateDuration() {
            const startVal = document.getElementById('ngay_bat_dau').value;
            const endVal = document.getElementById('ngay_ket_thuc').value;
            const durationSpan = document.getElementById('totalDuration');

            if (startVal && endVal) {
                const start = new Date(startVal);
                const end = new Date(endVal);

                if (end > start) {
                    const diffMs = end - start;
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
        
        // Gọi tính toán ngay khi trang tải xong để hiển thị dữ liệu cũ
        window.addEventListener('load', function() {
            calculateDuration();
        });
    </script>
</body>
</html>