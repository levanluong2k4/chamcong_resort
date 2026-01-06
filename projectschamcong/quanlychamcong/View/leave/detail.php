<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Nghỉ | Rosa Alba</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        /* --- COPY STYLE TỪ TRANG TRƯỚC ĐỂ ĐỒNG BỘ --- */
        :root {
            --primary-color: #0f4c81; 
            --teal-theme: #407a75;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
        }

        .main-content {
            padding: 20px;
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
            background: linear-gradient(135deg, var(--teal-theme) 0%, var(--teal-theme) 100%);
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
        }

        /* Avatar styles */
        .avatar-detail {
            width: 50px; 
            height: 50px; 
            background-color: #f1f5f9; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: var(--teal-theme);
            font-size: 1.2rem;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Label & Value text */
        .label-custom {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .value-custom {
            font-size: 1rem;
            font-weight: 500;
            color: #212529;
        }

        /* Status Badge (Style chấm tròn đồng bộ) */
        .status-badge {
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 15px;
            border-radius: 20px;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-cho-duyet { color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; }
        .status-cho-duyet .status-dot { background-color: #ffc107; }

        .status-da-duyet { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
        .status-da-duyet .status-dot { background-color: #28a745; }

        .status-tu-choi { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .status-tu-choi .status-dot { background-color: #dc3545; }

        /* Reason Box */
        .reason-box {
            background-color: #f8f9fa;
            border-left: 4px solid var(--teal-theme);
            padding: 15px;
            border-radius: 4px;
            color: #495057;
        }

        /* Buttons */
        .btn-back {
            background: #fff;
            border: 1px solid #ced4da;
            padding: 6px 15px;
            border-radius: 6px;
            color: #495057;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: #e9ecef;
            color: #212529;
        }

        .info-card-result {
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    
<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-light" style="width: 3rem; height: 3rem;"></div>
    </div>
        <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
            <div>
                <h4 class="fw-bold mb-1" style="color:white;">
                    <i class="fas fa-info-circle me-2"></i>Xem Đơn Nghỉ
                </h4>
           
            </div>
          
        </div>

        <div class="content-area p-0">
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-file-alt me-2"></i> Thông Tin Chi Tiết
                    </div>
                </div>

                <div class="widget-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-detail me-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="label-custom">Người làm đơn</div>
                                    <div class="value-custom fs-5"><?php echo $don['ho_ten']; ?></div>
                                    <small class="text-muted">Bộ phận <?php echo $objDepartment->getTenPhongBan(); ?></small>
                                </div>
                            </div>
                        </div>

                          <div class="col-md-4">
                            <div class="label-custom">Bộ phận</div>
                            <div class="value-custom text-primary">
                              <?php echo $objDepartment->getTenPhongBan(); ?>
                              
                            </div>
                        </div>
                        

                        <div class="col-md-4">
                            <div class="label-custom">Loại nghỉ</div>
                            <div class="value-custom text-primary">
                                <?php 
                                $loaiNghiLabel = [
                                    'NGHI_PHEP_THANG' => 'Nghỉ Phép Tháng', 
                                    'NGHI_OM' => 'Nghỉ Ốm',
                                    'NGHI_VIEC_RIENG' => 'Nghỉ Việc Riêng', 
                                    'NGHI_PHEP_TUAN' => 'Nghỉ Phép Tuần',
                                    'NGHI_LE' => 'Nghỉ Lễ', 
                                    'NGHI_TET' => 'Nghỉ Tết'
                                ];
                                echo $loaiNghiLabel[$don['loai_nghi']] ?? $don['loai_nghi'];
                                ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="label-custom">Trạng thái</div>
                            <div>
                                <?php if($don['trang_thai'] == 'CHO_DUYET'): ?>
                                    <span class="status-badge status-cho-duyet">
                                        <span class="status-dot"></span> Chờ duyệt
                                    </span>
                                <?php elseif($don['trang_thai'] == 'DA_DUYET'): ?>
                                    <span class="status-badge status-da-duyet">
                                        <span class="status-dot"></span> Đã duyệt
                                    </span>
                                <?php elseif($don['trang_thai'] == 'TU_CHOI'): ?>
                                    <span class="status-badge status-tu-choi">
                                        <span class="status-dot"></span> Từ chối
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-secondary opacity-10">

                    <div class="row g-4 mb-4 mt-1">
                        <div class="col-md-4">
                            <div class="label-custom">Từ ngày</div>
                            <div class="value-custom">
                           
                                <?php echo date('H:i - d/m/Y', strtotime($don['ngay_bat_dau'])); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="label-custom">Đến ngày</div>
                            <div class="value-custom">
                             
                                <?php echo date('H:i - d/m/Y', strtotime($don['ngay_ket_thuc'])); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="label-custom">Tổng thời gian</div>
                            <div class="value-custom text-danger">
                                <?php echo $don['so_ngay_nghi']+0; ?> ngày
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="label-custom">Lý do xin nghỉ</div>
                        <div class="reason-box mt-2">
                            <?php echo nl2br($don['ly_do']); ?>
                        </div>
                    </div>

                    <?php if(!empty($don['file_dinh_kem'])): ?>
                    <div class="mb-4">
                        <div class="label-custom">Tệp đính kèm</div>
                        <div class="mt-2">
                            <a href="/projectschamcong/uploads/<?php echo $don['file_dinh_kem']; ?>" target="_blank" class="btn btn-light btn-sm border text-primary fw-bold">
                                <i class="fas fa-paperclip me-1"></i> <?php echo $don['file_dinh_kem']; ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($don['trang_thai'] != 'CHO_DUYET'): ?>
                        <div class="info-card-result <?php echo ($don['trang_thai'] == 'TU_CHOI') ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?>">
                            <div class="label-custom <?php echo ($don['trang_thai'] == 'TU_CHOI') ? 'text-danger' : 'text-success'; ?> mb-2">
                               Kết quả xử lý
                            </div>
                            
                            <?php if($don['trang_thai'] == 'TU_CHOI'): ?>
                                <div class="fw-bold mb-1">Đơn đã bị từ chối.</div>
                                <div><strong>Lý do:</strong> <?php echo !empty($don['ly_do_tu_choi']) ? nl2br($don['ly_do_tu_choi']) : 'Không có lý do cụ thể.'; ?></div>
                            <?php elseif($don['trang_thai'] == 'DA_DUYET'): ?>
                                <div class="fw-bold">Đơn đã được phê duyệt thành công.</div>
                            <?php endif; ?>
                            
                            <div class="mt-2">
                               Thời gian xử lý: <?php echo isset($don['ngay_xu_ly']) ? date('H:i d/m/Y', strtotime($don['ngay_xu_ly'])) : '---'; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-end gap-2 mt-5 pt-3 border-top">
                        <?php if($don['trang_thai'] == 'CHO_DUYET'): ?>
                            <a href="?controller=leave&action=delete&id=<?php echo $don['ma_don']; ?>" 
                               class="btn btn-outline-danger"
                               onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này không?');">
                                <i class="fas fa-trash-alt me-1"></i> Hủy Đơn
                            </a>
                            <a href="?controller=leave&action=edit&id=<?php echo $don['ma_don']; ?>" class="btn btn-warning text-white fw-bold">
                                <i class="fas fa-edit me-1"></i> Chỉnh sửa
                            </a>
                        <?php endif; ?>
                        
                        <a href="?controller=leave&action=index" class="btn btn-secondary">
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Active menu script
        document.querySelectorAll('.nav-item').forEach(item => {
            if(window.location.href.includes(item.getAttribute('href'))) {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            }
        });
    </script>
</body>
</html>