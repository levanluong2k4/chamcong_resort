<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Nghỉ Phép | Rosa Alba</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
        <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
        <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        /* --- STYLE MỚI THEO MẪU NHÂN VIÊN --- */
        :root {
            --primary-color: #0f4c81; 
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
            --teal-theme: #407a75;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
        }

        /* Override main-content để padding đẹp hơn */
        .main-content {
            padding: 20px;
        }

        /* Card chứa bảng */
        .widget-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.05); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .widget-header {
            background: linear-gradient(135deg, var(--teal-theme) 0%, var(--teal-theme) 100%);
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .widget-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
        }

        /* --- TABLE STYLES --- */
        .table-responsive {
            padding: 0;
            border-radius: 0 0 12px 12px;
        }

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
            border-right: 1px solid #e2e8f0; 
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-custom tbody td {
            padding: 16px;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border-color); 
            border-right: 1px solid var(--border-color);  
            background-color: #fff;
        }

        .table-custom thead th:last-child,
        .table-custom tbody td:last-child {
            border-right: none;
        }
        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:nth-of-type(even) td {
            background-color: #fcfcfc;
        }

        .table-custom tbody tr:hover td {
            background-color: #eef7ff !important; 
            cursor: default;
        }

        /* Button Gradient */
        .btn-gradient {
            background: linear-gradient(135deg, var(--teal-theme) 0%, var(--teal-theme) 100%);
            border: none;
            box-shadow: 0 4px 6px rgba(64, 122, 117, 0.2);
            transition: all 0.2s ease;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(64, 122, 117, 0.25);
            color: #fff;
        }

        /* Status Badges */
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

        /* Màu sắc trạng thái */
        .status-cho-duyet { color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; }
        .status-cho-duyet .status-dot { background-color: #ffc107; box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2); }

        .status-da-duyet { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
        .status-da-duyet .status-dot { background-color: #28a745; box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2); }

        .status-tu-choi { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .status-tu-choi .status-dot { background-color: #dc3545; box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2); }

        /* Typography */
        .fw-500 { font-weight: 500; }
        .text-primary-custom { color: #0f4c81; }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #dee2e6;
        }

        /* Style riêng cho cột STT */
        .col-stt {
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            background-color: #f8f9fa !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-custom thead th, .table-custom tbody td {
                padding: 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    
<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
 


   
        <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
            <div>
                <h4 class="fw-bold mb-1" style="color: white;">
                    <i class="fas fa-list-alt me-2"></i>Đơn Nghỉ Phép
                </h4>
                <p class="text-white mb-0">Bộ Phận <?php echo $objDepartment->getTenPhongBan(); ?></p>
            </div>
            
            <a href="?controller=leave&action=create" class="btn btn-gradient">
                 Tạo Đơn Mới
            </a>
        </div>

        <div class="content-area p-0">
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-history me-2"></i> Danh Sách Đơn Xin Nghỉ
                    </div>
                </div>

                <div class="widget-body p-0">
                    <?php if (empty($historyList)): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p class="mb-0">Chưa có đơn xin nghỉ nào trong lịch sử.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50">STT</th>
                                        <th width="100">Mã đơn</th>
                                        <th width="250">Nhân viên</th>
                                        <th width="160">Ngày gửi</th>
                                        <th width="180">Loại nghỉ</th>
                                        <th width="120" class="text-center">Số ngày</th>
                                        <th>Lý do</th>
                                        <th width="150" class="text-center">Trạng thái</th>
                                        <th width="150" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $stt = 1; // Khởi tạo biến STT
                                    foreach ($historyList as $item): 
                                    ?>
                                        <tr>
                                            <td class="col-stt">
                                                <?php echo $stt++; ?>
                                            </td>

                                            <td>
                                                <span class="fw-bold text-primary-custom"><?php echo $item['ma_don']; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <div class="fw-bold text-dark"><?php echo $item['ho_ten']; ?></div>
                                                    
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-black">
                                                <?php echo date('d/m/Y H:i', strtotime($item['ngay_tao'])); ?>
                                            </td>
                                            <td>
                                                <?php
                                                $loaiNghi = [
                                                    'NGHI_PHEP_THANG' => 'Nghỉ Phép Tháng', 
                                                    'NGHI_OM' => 'Nghỉ Ốm',
                                                    'NGHI_VIEC_RIENG' => 'Nghỉ Việc Riêng',
                                                    'NGHI_LE' => 'Nghỉ Lễ',
                                                    'NGHI_PHEP_TUAN' => 'Nghỉ phép tuần', 
                                                    'NGHI_TET' => 'Nghỉ Tết'
                                                ];
                                                echo '<span class="fw-500">' . ($loaiNghi[$item['loai_nghi']] ?? $item['loai_nghi']) . '</span>';
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <span class=" fw-bold px-3 py-2">
                                                    <?php echo $item['so_ngay_nghi'] + 0; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-black"><?php echo $item['ly_do']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($item['trang_thai'] == 'CHO_DUYET'): ?>
                                                    <span class="status-badge status-cho-duyet">
                                                        <span class="status-dot"></span> Chờ duyệt
                                                    </span>
                                                <?php elseif ($item['trang_thai'] == 'DA_DUYET'): ?>
                                                    <span class="status-badge status-da-duyet">
                                                        <span class="status-dot"></span> Đã duyệt
                                                    </span>
                                                <?php elseif ($item['trang_thai'] == 'TU_CHOI'): ?>
                                                    <span class="status-badge status-tu-choi">
                                                        <span class="status-dot"></span> Từ chối
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="?controller=leave&action=detail&id=<?php echo $item['ma_don']; ?>"
                                                        class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <?php if ($item['trang_thai'] == 'CHO_DUYET'): ?>
                                                        <a href="?controller=leave&action=edit&id=<?php echo $item['ma_don']; ?>"
                                                            class="btn btn-sm btn-outline-warning" title="Sửa">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <a href="?controller=leave&action=delete&id=<?php echo $item['ma_don']; ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn XÓA đơn này không?');"
                                                        title="Xóa">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="d-flex justify-content-end mt-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                             </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.nav-item').forEach(item => {
            if(window.location.href.includes(item.getAttribute('href'))) {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            }
        });
    </script>
</body>

</html>