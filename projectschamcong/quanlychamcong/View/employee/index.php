<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Nhân Viên | Rosa Alba</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        :root {
            --primary-color: #0f4c81; 
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0; 
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
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
            background: linear-gradient(135deg, #407a75 0%,#407a75 100%);
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

        .col-stt {
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            width: 60px;
            background-color: #f8f9fa !important; 
        }

        /* Avatar */
        .avatar-sm {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Badge Roles */
        .badge-role {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            min-width: 90px;
            text-align: center;
            border: 1px solid transparent;
        }
    

        /* Button Gradient */
        .btn-gradient {
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            border: none;
            box-shadow: 0 4px 6px rgba(15, 76, 129, 0.2);
            transition: all 0.2s ease;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(15, 76, 129, 0.25);
            color: #fff;
        }

        /* Status Styles */
        .status-badge {
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-active { color: #2e7d32; }
        .status-active .status-dot { background-color: #2e7d32; box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.15); }
        .status-inactive { color: #c62828; }
        .status-inactive .status-dot { background-color: #c62828; box-shadow: 0 0 0 2px rgba(198, 40, 40, 0.15); }

        /* Btn Back */
        .btn-back {
            background: #fff;
            border: 1px solid #ced4da;
            padding: 8px 16px;
            border-radius: 8px;
            color: #495057;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: #f8f9fa;
            border-color: #0f4c81;
            color: #0f4c81;
        }
        
        /* CSS PHÂN TRANG (Pagination) */
        .pagination-container {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end; /* Căn phải */
            background: #fff;
        }
        
        .pagination .page-item .page-link {
            color: #0f4c81;
            border: 1px solid #dee2e6;
            margin: 0 2px;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .pagination .page-item.active .page-link {
            background-color:#407a75;
            border-color: #407a75;
            color: #fff;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
        }
        
        .pagination .page-link:hover {
            background-color: #e9ecef;
            color: #0a3a63;
        }
        
        .pagination .page-item:first-child .page-link, 
        .pagination .page-item:last-child .page-link {
            border-radius: 6px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-custom thead th, .table-custom tbody td {
                padding: 10px;
                font-size: 0.85rem;
            }
            .pagination-container {
                justify-content: center;
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
  

        <!-- <div class="content-area">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?controller=home&action=index" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại
                </a>
                
                <?php if (isset($userRole) && $userRole == 'QUAN_LY'): ?>
                <a href="?controller=employee&action=create" class="btn btn-gradient">
                    <i class="fas fa-user-plus me-2"></i> Thêm Nhân Viên
                </a>
                <?php endif; ?>
            </div>

            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-list-ul me-2"></i> Danh Sách Nhân Viên
                    </div>
                </div>
                
                <div class="widget-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th class="text-center" width="60">STT</th> 
                                    <th width="280">Họ và Tên</th>
                                    <th width="150">Số Điện Thoại</th>
                                    <th width="220">Email</th>
                                    <th width="140" class="text-center">Chức Vụ</th>
                                    <th width="140">Ngày Vào Làm</th>
                                    <th width="140">Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($employeesPaged)): ?>
                                    
                                    <?php foreach ($employeesPaged as $emp): ?>
                                    <tr>
                                        <td class="col-stt">
                                            <?php echo $stt++; ?>
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center">
                                            
                                                
                                                <div>
                                                    <div class="fw-bold" style="color: #0f4c81;">
                                                        <?php echo $emp['ho_ten']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td style="font-family: monospace; font-size: 0.95rem;">
                                            <?php echo $emp['so_dien_thoai']; ?>
                                        </td>

                                        <td>
                                            <?php echo $emp['email']; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php 
                                                $roleClass = '';
                                                $roleText = '';
                                                if($emp['vai_tro'] == 'QUAN_LY') { 
                                                    $roleClass = 'role-quan-ly'; 
                                                    $roleText = 'Quản Lý';
                                                } else if($emp['vai_tro'] == 'NHAN_SU') { 
                                                    $roleClass = 'role-nhan-su'; 
                                                    $roleText = 'Nhân Sự';
                                                } else { 
                                                    $roleClass = 'role-nhan-vien'; 
                                                    $roleText = 'Nhân Viên';
                                                }
                                            ?>
                                            <span class="badge-role <?php echo $roleClass; ?>">
                                                <?php echo $roleText; ?>
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <span class="text-secondary">
                                                <?php echo date('d/m/Y', strtotime($emp['ngay_vao_lam'])); ?>
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <?php if ($emp['trang_thai'] == 'DANG_LAM'): ?>
                                                <span class="status-badge status-active">
                                                    <span class="status-dot"></span> Đang làm
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge status-inactive">
                                                    <span class="status-dot"></span> Đã nghỉ
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-users-slash"></i>
                                                <p class="mb-0">Chưa có nhân viên nào trong danh sách</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <?php if ($totalPages > 1): ?>
                <div class="pagination-container">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=employee&action=index&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=employee&action=index&page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=employee&action=index&page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
                </div>
        </div> -->
    <div class="content-area">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?controller=home&action=index" class="btn btn-back">
                    Quay lại
                </a>
               
             <?php if (isset($userRole) && in_array($userRole, ['QUAN_LY', 'NHAN_SU', 'ADMIN'])): ?>
                <a href="?controller=employee&action=create" class="btn btn-gradient">
                 Thêm Nhân Viên
                </a>
                <?php endif; ?>
            </div>

            <div class=" mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                <div class="card-body py-3">
                    <form action="" method="GET" class="row g-2 align-items-center">
                        <input type="hidden" name="controller" value="employee">
                        <input type="hidden" name="action" value="index">
                        
                        <div class="col-md-4">
                            <label class="fw-bold mb-1" style="font-size: 0.9rem; color: white;">Tìm kiếm nhân viên:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                                       placeholder="Nhập tên nhân viên..." 
                                       value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 fw-500" style="background-color:#407a75; border: none; margin-top: 25px;">
                                Tìm Kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-list-ul me-2"></i> Danh Sách Nhân Viên
                    </div>
                </div>
                
                <div class="widget-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th class="text-center" width="50">STT</th> 
                                    <th width="250">Họ và Tên</th>
                                    <th width="120">SĐT</th>
                                    <th width="200">Email</th>
                                    <th width="120" class="text-center">Chức Vụ</th>
                                    <th width="120">Ngày Vào</th>
                                    <th width="120">Trạng Thái</th>
                                    <th width="150" class="text-center">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($employeesPaged)): ?>
                                    
                                    <?php foreach ($employeesPaged as $emp): ?>
                                    <tr>
                                        <td class="col-stt"><?php echo $stt++; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="fw-bold" style="color: black;">
                                                    <?php echo $emp['ho_ten']; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-family: monospace; font-size: 0.95rem;"><?php echo $emp['so_dien_thoai']; ?></td>
                                        <td><?php echo $emp['email']; ?></td>
                                        
                                        <td class="text-center">
                                            <?php 
                                                $roleClass = ''; $roleText = '';
                                                if($emp['vai_tro'] == 'QUAN_LY') { $roleClass = 'role-quan-ly'; $roleText = 'Quản Lý'; }
                                                else if($emp['vai_tro'] == 'NHAN_SU') { $roleClass = 'role-nhan-su'; $roleText = 'Nhân Sự'; }
                                                else { $roleClass = 'role-nhan-vien'; $roleText = 'Nhân Viên'; }
                                            ?>
                                            <span class="badge-role <?php echo $roleClass; ?>"><?php echo $roleText; ?></span>
                                        </td>
                                        
                                        <td><span class="text-black"><?php echo date('d/m/Y', strtotime($emp['ngay_vao_lam'])); ?></span></td>

                                        <td>
                                            <?php if ($emp['trang_thai'] == 'DANG_LAM'): ?>
                                                <span class="status-badge status-active"><span class="status-dot"></span> Đang làm</span>
                                            <?php else: ?>
                                                <span class="status-badge status-inactive"><span class="status-dot"></span> Đã nghỉ</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="?controller=employee&action=detail&id=<?php echo $emp['ma_nhan_vien']; ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                             
                                                    <?php if (in_array($userRole, ['QUAN_LY', 'NHAN_SU', 'ADMIN'])): ?>
                                                    <a href="?controller=employee&action=edit&id=<?php echo $emp['ma_nhan_vien']; ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    
                                                    <a href="?controller=employee&action=delete&id=<?php echo $emp['ma_nhan_vien']; ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên <?php echo $emp['ho_ten']; ?> không? Hành động này không thể hoàn tác!');" 
                                                       title="Xóa">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8"> <div class="empty-state">
                                                <i class="fas fa-users-slash"></i>
                                                <p class="mb-0">Không tìm thấy nhân viên nào phù hợp.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <?php if ($totalPages > 1): ?>
                <div class="pagination-container">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=employee&action=index&page=<?php echo $page - 1; ?>&keyword=<?php echo isset($keyword)?$keyword:''; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=employee&action=index&page=<?php echo $i; ?>&keyword=<?php echo isset($keyword)?$keyword:''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=employee&action=index&page=<?php echo $page + 1; ?>&keyword=<?php echo isset($keyword)?$keyword:''; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
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