<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tăng Ca | Resort Rosa Alba</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">


    <style>
        /* --- COPY STYLE TỪ GIAO DIỆN MỚI --- */
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

        /* Stat Cards (Dashboard cũ được làm mới) */
        .stat-card-modern {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: 100%;
            transition: transform 0.2s;
            border-left: 4px solid transparent;
        }
        .stat-card-modern:hover {
            transform: translateY(-5px);
        }
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
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Table Styles */
        .table-custom {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse; 
            color: #212529;
        }

        .table-custom thead th {
            background-color: #f1f5f9; 
            color: #0f4c81; 
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 16px;
            border-bottom: 2px solid #cbd5e1; 
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-custom tbody td {
            padding: 16px;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border-color); 
            background-color: #fff;
        }

        .table-custom tbody tr:hover td {
            background-color: #eef7ff !important; 
        }

        /* Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        /* Màu trạng thái */
        .status-approved { color: #198754; background-color: #d1e7dd; }
        .status-approved .status-dot { background-color: #198754; }

        .status-rejected { color: #dc3545; background-color: #f8d7da; }
        .status-rejected .status-dot { background-color: #dc3545; }

        .status-pending { color: #fd7e14; background-color: #ffecb5; }
        .status-pending .status-dot { background-color: #fd7e14; }

        /* Buttons */
        .btn-create-request {
            background-color: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 6px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-create-request:hover {
            background-color: #fff;
            color: var(--main-teal);
        }

        /* Avatar Small */
        .avatar-xs {
            width: 35px;
            height: 35px;
            background-color: #e0e7ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border-radius: 50%;
            font-size: 0.8rem;
            margin-right: 10px;
        }

        /* Override Text Colors from Old Code */
        .text-white-force { color: #fff !important; }
        .text-dark-force { color: #212529 !important; }
    </style>
</head>

<body>
    
<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
   

    

        <div class="content-area">
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card-modern ">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-value text-primary"><?= number_format($stats['tong_duyet'], 0) ?> giờ</div>
                                <div class="stat-label">
                                    <?= ($userRoleGoc == 'ADMIN' || $userRoleGoc == 'NHAN_SU') ? 'Tổng Quỹ Hệ Thống' : 'Tổng Giờ Được Duyệt' ?>
                                </div>
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
                                <div class="stat-label">Đã Phân Giờ Tăng Ca</div>
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
                                <div class="stat-value text-danger"><?= number_format($stats['con_lai'], 1)+0 ?> giờ</div>
                                <div class="stat-label">Thời gian còn lại</div>
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
                    <div class="widget-title">
                        <i class="fas fa-list-alt me-2"></i> Danh Sách Đơn Tăng Ca
                    </div>
                    <?php if ($userRoleGoc == 'QUAN_LY'): ?>
                        <a href="?controller=overtime&action=create" class="btn-create-request">
                           Tạo Đơn Mới
                        </a>
                    <?php endif; ?>
                </div>

                <div class="widget-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Mã Ca</th>
                                    <th>Người Tạo</th>
                                    <th>Thông tin Ca</th>
                                    <th>Lý do</th>
                                    <th>Ngày tăng ca</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $requests->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary"><?php echo $row['ma_don_tang_ca']; ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs">
                                                    <?php
                                                    $words = explode(' ', $row['nguoi_tao']);
                                                    echo strtoupper(substr(end($words), 0, 2));
                                                    ?>
                                                </div>
                                                <span class="fw-bold" style="color: #0f4c81;"><?php echo $row['nguoi_tao']; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-500"><?php echo $row['ten_ca']; ?></span>
                                        </td>
                                        <td>
                                            <span class="text-secondary" title="<?php echo $row['ly_do']; ?>">
                                                <?php echo mb_strimwidth($row['ly_do'], 0, 30, "..."); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($row['ngay_tang_ca'])); ?>
                                        </td>
                                        <td>
                                            <?php if ($row['trang_thai'] == 'DA_DUYET'): ?>
                                                <span class="status-badge status-approved"><span class="status-dot"></span> Đã Duyệt</span>
                                            <?php elseif ($row['trang_thai'] == 'TU_CHOI'): ?>
                                                <span class="status-badge status-rejected"><span class="status-dot"></span> Từ Chối</span>
                                            <?php else: ?>
                                                <span class="status-badge status-pending"><span class="status-dot"></span> Chờ Duyệt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-2">
                                                <?php if (in_array($userRoleGoc, ['ADMIN', 'NHAN_SU'])): ?>
                                                    <?php if ($row['trang_thai'] == 'CHO_DUYET'): ?>
                                                        <a href="?controller=overtime&action=approve&id=<?php echo $row['ma_don_tang_ca']; ?>"
                                                           class="btn btn-sm btn-outline-success" title="Duyệt"
                                                           onclick="return confirm('Xác nhận DUYỆT đơn tăng ca này?');">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="?controller=overtime&action=reject&id=<?php echo $row['ma_don_tang_ca']; ?>"
                                                           class="btn btn-sm btn-outline-danger" title="Từ chối"
                                                           onclick="return confirm('Xác nhận TỪ CHỐI đơn này?');">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?controller=overtime&action=detail&id=<?php echo $row['ma_don_tang_ca']; ?>"
                                                           class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <a href="?controller=overtime&action=detail&id=<?php echo $row['ma_don_tang_ca']; ?>"
                                                       class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($userRoleGoc == 'QUAN_LY'): ?>
                                                        <a href="?controller=overtime&action=delete&id=<?php echo $row['ma_don_tang_ca']; ?>"
                                                           class="btn btn-sm btn-outline-danger" title="Xóa đơn"
                                                           onclick="return confirm('Bạn có chắc chắn muốn XÓA đơn này không?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                                <?php if ($requests->num_rows == 0): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <div class="py-3">
                                                <i class="fas fa-clipboard-list fa-2x mb-3 text-secondary"></i>
                                                <p class="mb-0">Chưa có đơn tăng ca nào.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="widget-card mt-4">
                <div class="widget-header">
                    <div class="widget-title">
                      
                        <?= (in_array($userRoleGoc, ['ADMIN', 'NHAN_SU'])) ? 'Tổng Giờ Tăng Ca Nhân Viên' : 'Tổng Giờ Tăng Ca  Bộ Phận' ?>
                    </div>
                </div>
                <div class="widget-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Họ Tên Nhân Viên</th>
                                    <?php if (in_array($userRoleGoc, ['ADMIN', 'NHAN_SU'])): ?>
                                        <th>Phòng Ban</th> 
                                    <?php endif; ?>
                                    <th class="text-center">Tổng Giờ </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($employeeHistory && $employeeHistory->num_rows > 0): ?>
                                    <?php while ($emp = $employeeHistory->fetch_assoc()): ?>
                                        <?php if ($emp['tong_tich_luy'] > 0): ?>
                                            <tr>
                                                <td><span class="fw-bold" style="color: #0f4c81;"><?= $emp['ho_ten'] ?></span></td>
                                                <?php if (in_array($userRoleGoc, ['ADMIN', 'NHAN_SU'])): ?>
                                                    <td><span class=""><?= $emp['ten_phong_ban'] ?></span></td>
                                                <?php endif; ?>
                                                <td class="text-center">
                                                    <span class=""><?= number_format($emp['tong_tich_luy'], 0) ?> giờ</span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu tích lũy.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script giữ nguyên
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function (e) {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>