<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Rosa Alba - Tạo/Sửa Đơn Tăng Ca</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        /* --- COPY STYLE GIAO DIỆN MỚI --- */
        :root {
            --primary-color: #0f4c81; 
            --main-teal: #407a75;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0; 
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            color: #333;
        }

        /* Card Style */
        .widget-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.05); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .widget-header {
            background: linear-gradient(135deg, var(--main-teal) 0%, var(--main-teal) 100%);
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .widget-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
            margin: 0;
        }

        /* Form Labels */
        .form-label {
            font-weight: 600;
            color: #0f4c81;
            font-size: 0.9rem;
        }

        /* Table Styles (Giống trang danh sách) */
        .table-custom {
            width: 100%;
            border-collapse: collapse; 
        }
        .table-custom thead th {
            background-color: #f1f5f9; 
            color: #0f4c81; 
            font-weight: 700;
            font-size: 0.85rem;
            padding: 12px;
            border-bottom: 2px solid #cbd5e1; 
            vertical-align: middle;
        }
        .table-custom tbody td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color); 
        }

        /* Input Styles */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--main-teal);
            box-shadow: 0 0 0 0.2rem rgba(64, 122, 117, 0.25);
        }

        /* Buttons */
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: white;
            color: var(--main-teal);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--main-teal) 0%, #2a5a56 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            color: white;
        }

        /* Logic display */
        #allocation-section { display: <?php echo isset($is_edit) ? 'block' : 'none'; ?>; }
    </style>
</head>

<body>

<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
        <div class="content-area">
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <?php echo isset($is_edit) ? '<i class="fas fa-edit me-2"></i> Cập Nhật Đơn #' . $request['ma_don_tang_ca'] : ' Tạo Đơn Tăng Ca Mới'; ?>
                    </div>
                   
                </div>

                <div class="widget-body p-4">
                    <form action="?controller=overtime&action=<?php echo isset($is_edit) ? 'update' : 'store'; ?>" method="POST">
                        
                        <?php if(isset($is_edit)): ?>
                            <input type="hidden" name="ma_don" value="<?php echo $request['ma_don_tang_ca']; ?>">
                        <?php endif; ?>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Ngày Tăng Ca <span class="text-danger">*</span></label>
                                <input type="date" name="ngay_tang_ca" id="ngay_tang_ca" class="form-control" 
                                       value="<?php echo isset($is_edit) ? $request['ngay_tang_ca'] : date('Y-m-d'); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ca Tăng Ca <span class="text-danger">*</span></label>
                                <select name="ma_ca" class="form-select" required>
                                    <option value="">Chọn ca làm việc</option>
                                    <?php 
                                    $shifts->data_seek(0);
                                    while($c = $shifts->fetch_assoc()): 
                                        $selected = (isset($is_edit) && $request['ma_ca'] == $c['ma_ca']) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $c['ma_ca']; ?>" <?php echo $selected; ?>>
                                            <?php echo $c['ten_ca']; ?> (<?php echo date('H:i', strtotime($c['gio_bat_dau'])) . ' - ' . date('H:i', strtotime($c['gio_ket_thuc'])); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tổng giờ tăng ca</label>
                                <div class="input-group">
                                    <input type="text" name="tong_gio_can" id="tong_gio_can" class="form-control bg-light fw-bold" 
                                           value="<?php echo isset($is_edit) ? $request['tong_gio_can'] : ''; ?>" readonly required>
                                    <span class="input-group-text">Giờ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Lý do tăng ca</label>
                                <input type="text" name="ly_do" class="form-control" placeholder=""
                                       value="<?php echo isset($is_edit) ? $request['ly_do'] : ''; ?>">
                            </div>
                        </div>

                        <div id="allocation-section" class="mt-5">
                          

                            <div class="table-responsive rounded-3 border">
                                <table class="table table-custom table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 30px">STT</th>
                                            <th style="width: 30%">Họ và tên</th>
                                            <th style="width: 30%">Số giờ</th>
                                            <th style="width: 40%">Công việc </th>
                                        </tr>
                                    </thead>
                                    <tbody id="employee-table-body">
                                        <?php 
                                        $index = 1;
                                        $employees->data_seek(0);
                                        while($e = $employees->fetch_assoc()): 
                                            // Logic điền dữ liệu cũ
                                            $val_gio = '';
                                            $val_note = '';
                                            if (isset($is_edit) && !empty($selected_data) && isset($selected_data[$e['ma_nhan_vien']])) {
                                                $val_gio = $selected_data[$e['ma_nhan_vien']]['so_gio'];
                                                $val_note = $selected_data[$e['ma_nhan_vien']]['ghi_chu'];
                                            }
                                            
                                            // Highlight dòng nếu nhân viên đã được chọn
                                            $rowClass = ($val_gio != '' && $val_gio > 0) ? 'table-info' : '';
                                        ?>
                                            <tr class="<?php echo $rowClass; ?>">
                                                <td class="text-center text-muted"><?php echo $index++; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                       
                                                        <span class="fw-bold text-dark"><?php echo $e['ho_ten']; ?></span>
                                                    </div>
                                                    <input type="hidden" name="nhan_vien[]" value="<?php echo $e['ma_nhan_vien']; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="so_gio[]" class="form-control hour-input text-center" 
                                                           placeholder="" value="<?php echo $val_gio; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="ghi_chu[]" class="form-control" placeholder="" 
                                                           value="<?php echo $val_note; ?>">
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-3">
                            <a href="?controller=overtime&action=index" class="btn btn-light border px-4">Hủy</a>
                            <button type="submit" class="btn btn-submit px-4">
                                <?php echo isset($is_edit) ? ' Cập Nhật' : ' Gửi Đơn'; ?>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectShift = document.querySelector('select[name="ma_ca"]');
            const allocationSection = document.getElementById('allocation-section');
            const totalHoursInput = document.getElementById('tong_gio_can');
            const employeeRows = document.querySelectorAll('#employee-table-body tr');

            // Khi chọn Ca làm việc
            selectShift.addEventListener('change', function () {
                if (this.value !== "") {
                    allocationSection.style.display = 'block';
                    // Hiệu ứng fade in nhẹ
                    allocationSection.style.opacity = 0;
                    setTimeout(() => { 
                        allocationSection.style.transition = 'opacity 0.5s';
                        allocationSection.style.opacity = 1; 
                    }, 10);
                } else {
                    allocationSection.style.display = 'none';
                }
            });

            // Tính tổng giờ tự động
            document.getElementById('employee-table-body').addEventListener('input', function (e) {
                if (e.target && e.target.classList.contains('hour-input')) {
                    calculateTotalHours();
                    
                    // Highlight dòng đang nhập liệu
                    const row = e.target.closest('tr');
                    if (parseFloat(e.target.value) > 0) {
                        row.classList.add('table-active'); // Hoặc table-info nếu muốn xanh
                        row.style.backgroundColor = '#eef7ff';
                    } else {
                        row.classList.remove('table-active');
                        row.style.backgroundColor = '';
                    }
                }
            });

            function calculateTotalHours() {
                let total = 0;
                const hourInputs = document.querySelectorAll('.hour-input');
                hourInputs.forEach(input => {
                    let val = parseFloat(input.value);
                    if (!isNaN(val)) {
                        total += val;
                    }
                });
                totalHoursInput.value = total;
            }
        });
    </script>
</body>
</html>