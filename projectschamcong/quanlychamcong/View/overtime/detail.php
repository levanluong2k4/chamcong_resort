<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Rosa Alba - Chi Tiết Đơn Tăng Ca</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        /* --- GIAO DIỆN MỚI (TEAL THEME) --- */
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

        /* Stat Cards Modern */
        .stat-card-modern {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: 100%;
            transition: transform 0.2s;
            border-left: 4px solid transparent;
        }
        .stat-card-modern:hover { transform: translateY(-5px); }
        .stat-card-blue { border-left-color: #0d6efd; }
        .stat-card-green { border-left-color: #198754; }
        .stat-card-red { border-left-color: #dc3545; }

        .stat-icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .stat-value { font-size: 1.5rem; font-weight: 700; margin-bottom: 0; }
        .stat-label { color: #6c757d; font-size: 0.85rem; font-weight: 500; }

        /* Table Custom */
        .table-custom { width: 100%; border-collapse: collapse; }
        .table-custom thead th {
            background-color: #f1f5f9; 
            color: #0f4c81; 
            font-weight: 700;
            font-size: 0.85rem;
            padding: 15px;
            border-bottom: 2px solid #cbd5e1; 
        }
        .table-custom tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color); 
            font-size: 0.95rem;
        }

        /* Status Badges (Dot Style) */
        .status-badge {
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 5px 12px;
            border-radius: 20px;
        }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; }
        
        .status-approved { color: #198754; background-color: #d1e7dd; }
        .status-approved .status-dot { background-color: #198754; }

        .status-rejected { color: #dc3545; background-color: #f8d7da; }
        .status-rejected .status-dot { background-color: #dc3545; }

        .status-pending { color: #fd7e14; background-color: #ffecb5; }
        .status-pending .status-dot { background-color: #fd7e14; }

        /* Form Elements */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
        }
        .form-control:focus { border-color: var(--main-teal); box-shadow: 0 0 0 0.2rem rgba(64,122,117,0.25); }

        /* Label Info */
        .info-label { font-size: 0.85rem; color: #6c757d; margin-bottom: 2px; }
        .info-value { font-size: 1rem; font-weight: 600; color: #212529; }

        /* Avatar */
        .avatar-sm {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 0.8rem;
            margin-right: 10px;
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
            
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="stat-card-modern ">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-value text-primary"><?= number_format($stats['tong_duyet'], 0) ?> giờ</div>
                                <div class="stat-label">Tổng quỹ tăng ca</div>
                            </div>
                            <div class="stat-icon-circle bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card-modern ">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-value text-success"><?= number_format($stats['da_dung'], 0) ?> giờ</div>
                                <div class="stat-label">Đã phân bổ</div>
                            </div>
                            <div class="stat-icon-circle bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percentUsed ?>%"></div>
                            </div>
                            <div class="text-end mt-1 text-muted" style="font-size: 11px;">
                                Đã dùng <?= number_format($percentUsed, 1) ?>%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card-modern ">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-value text-danger"><?= number_format($stats['con_lai'], 1) ?> giờ</div>
                                <div class="stat-label">Quỹ giờ còn lại</div>
                            </div>
                            <div class="stat-icon-circle bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title"> Thông Tin Đơn</div>
                    <div class="d-flex gap-2">
                        <?php 
                        $currentRole = $_SESSION['role'] ?? ''; 
                        if($request['trang_thai'] == 'CHO_DUYET' && in_array($currentRole, ['ADMIN', 'NHAN_SU'])): 
                        ?>
                            <a href="?controller=overtime&action=approve&id=<?php echo $request['ma_don_tang_ca']; ?>" 
                               class="btn btn-light text-success fw-bold btn-sm" 
                               onclick="return confirm('Xác nhận DUYỆT đơn này và cộng giờ cho nhân viên?');">
                               <i class="fas fa-check me-1"></i> DUYỆT
                            </a>
                            <a href="?controller=overtime&action=reject&id=<?php echo $request['ma_don_tang_ca']; ?>" 
                               class="btn btn-light text-danger fw-bold btn-sm" 
                               onclick="return confirm('Bạn có chắc chắn muốn TỪ CHỐI đơn này?');">
                               <i class="fas fa-times me-1"></i> TỪ CHỐI
                            </a>
                        <?php endif; ?>
                        
                        <a href="?controller=overtime&action=index" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
                
                <div class="widget-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3 col-6 ">
                            <div class="info-label">Ngày tăng ca </div>
                            <div class="info-value">
                             
                                <?php echo date('d/m/Y', strtotime($request['ngay_tang_ca'])); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 ">
                            <div class="info-label">Ca làm việc</div>
                            <div class="info-value">
                             
                                <?php echo $request['ten_ca']; ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 ">
                            <div class="info-label">Lý do</div>
                            <div class="info-value text-truncate" title="<?php echo $request['ly_do']; ?>">
                                <?php echo $request['ly_do'] ?: 'Không có ghi chú'; ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="info-label">Trạng thái</div>
                            <div class="mt-1">
                                <?php if($request['trang_thai'] == 'DA_DUYET'): ?>
                                    <span class="status-badge status-approved"><span class="status-dot"></span> Đã Duyệt</span>
                                <?php elseif($request['trang_thai'] == 'TU_CHOI'): ?>
                                    <span class="status-badge status-rejected"><span class="status-dot"></span> Từ Chối</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending"><span class="status-dot"></span> Chờ Duyệt</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title"> Danh Sách Nhân Viên Tham Gia</div>
                </div>
                <div class="widget-body p-0">
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Nhân Viên</th>
                                    <th>Số Giờ Đăng Ký</th>
                                    <th>Trạng Thái</th>
                                    <th>Công Việc </th>
                                    <?php if($currentRole == 'QUAN_LY'): ?>
                                        <th class="text-end">Thao Tác</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grouped_details = [];
                                $details->data_seek(0);
                                while($d = $details->fetch_assoc()) {
                                    $ma_nv = $d['ma_nhan_vien'];
                                    if (!isset($grouped_details[$ma_nv])) {
                                        $grouped_details[$ma_nv] = $d;
                                    } else {
                                        $grouped_details[$ma_nv]['so_gio_dang_ky'] += $d['so_gio_dang_ky'];
                                        $grouped_details[$ma_nv]['ghi_chu'] .= " | " . $d['ghi_chu'];
                                    }
                                }
                                echo "<script> const dsNhanVienDaCo = " . json_encode($grouped_details) . "; </script>";

                                foreach($grouped_details as $d): 
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light text-primary border">
                                                <?php 
                                                    $words = explode(' ', $d['ho_ten']);
                                                    echo strtoupper(substr(end($words), 0, 1)); 
                                                ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $d['ho_ten']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold "><?php echo $d['so_gio_dang_ky'] + 0; ?> giờ</span>
                                    </td>
                                    <td>
                                        <?php if($request['trang_thai'] == 'DA_DUYET'): ?>
                                            <span class="text-success small fw-bold"> Chấp nhận</span>
                                        <?php elseif($request['trang_thai'] == 'TU_CHOI'): ?>
                                            <span class="text-danger small fw-bold"> Từ chối</span>
                                        <?php else: ?>
                                            <span class="text-warning small fw-bold"> Chờ duyệt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?php echo $d['ghi_chu'] ?: '-'; ?></span>
                                    </td>
                                    <?php if($currentRole == 'QUAN_LY'): ?>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                                onclick="openEditModal('<?= $d['ma_chi_tiet'] ?>', '<?= $d['so_gio_dang_ky'] ?>', '<?= $d['ghi_chu'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?controller=overtime&action=remove_employee&id_detail=<?php echo $d['ma_chi_tiet']; ?>" 
                                           class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa nhân viên này khỏi danh sách?');">
                                           <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if($request['so_gio_du'] > 0 && $currentRole == 'QUAN_LY'): ?>
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title"><i class="fas fa-user-plus me-2"></i> Thêm Nhân Viên Vào Đơn</div>
                </div>
                <div class="widget-body p-4">
                    <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                        <form id="formAddEmployee" action="?controller=overtime&action=add_employee" method="POST" class="row g-3 align-items-end">
                            <input type="hidden" name="ma_don" value="<?php echo $request['ma_don_tang_ca']; ?>">
                            <input type="hidden" id="quy_gio_con_lai" value="<?php echo $request['so_gio_du']; ?>">
                            
                            <div class="col-md-4">
                                <label class="form-label">Chọn Nhân Viên</label>
                                <select name="ma_nhan_vien" id="select_nhan_vien" class="form-select" required>
                                    <option value="">Tìm nhân viên </option>
                                    <?php 
                                    $employees->data_seek(0);
                                    while($e = $employees->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $e['ma_nhan_vien']; ?>"><?php echo $e['ho_ten']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Số Giờ</label>
                                <input type="text" id="input_so_gio" class="form-control" placeholder="" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Công việc </label>
                                <input type="text" name="ghi_chu" id="input_ghi_chu" class="form-control" placeholder="">
                            </div>
                            
                            <div class="col-md-2">
                                <button type="button" onclick="validateAndSubmit()" class="btn btn-success w-100 fw-bold" style="background-color: #198754;">
                                      Thêm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="?controller=overtime&action=update_employee_detail" method="POST" class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary">Chỉnh sửa giờ tăng ca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="ma_chi_tiet" id="edit_ma_chi_tiet">
                    <div class="mb-3">
                        <label class="form-label">Số giờ</label>
                        <input type="number" step="0.5" name="so_gio" id="edit_so_gio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Công việc / Ghi chú</label>
                        <textarea name="ghi_chu" id="edit_ghi_chu" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--main-teal); border: none;">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Active Sidebar Item
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Validate Add Employee Form
        function validateAndSubmit() {
            var soGioNhap = parseFloat(document.getElementById('input_so_gio').value);
            var quyGio = parseFloat(document.getElementById('quy_gio_con_lai').value);

            if (isNaN(soGioNhap) || soGioNhap <= 0) {
                alert("Vui lòng nhập số giờ hợp lệ!");
                return;
            }

            if (quyGio <= 0) {
                alert("Không thể thêm! Quỹ giờ của đơn này đã hết.");
                return;
            }

            if (soGioNhap > quyGio) {
                alert("Lỗi: Bạn nhập " + soGioNhap + "h nhưng quỹ giờ còn lại chỉ còn " + quyGio + "h. Vui lòng điều chỉnh lại!");
                return;
            }

            document.getElementById('formAddEmployee').submit();
        }

        // Open Edit Modal
        function openEditModal(id, gio, note) {
            document.getElementById('edit_ma_chi_tiet').value = id;
            document.getElementById('edit_so_gio').value = gio;
            document.getElementById('edit_ghi_chu').value = note;
            var myModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            myModal.show();
        }
    </script>
</body>
</html>