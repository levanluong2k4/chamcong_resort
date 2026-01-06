<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Nhân Viên - <?php echo $employee->getHoTen(); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        :root { --primary-color: #0f4c81; --light-bg: #f5f7fb; --border-color: #e0e0e0; }
        body { font-family: 'Inter', sans-serif; background-color: var(--light-bg); }
        
       
        .logo-container { display: flex; align-items: center; gap: 12px; }
        .logo { width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .brand-name { font-weight: 700; font-size: 1.1rem; letter-spacing: 0.5px; }
        .brand-subtitle { font-size: 0.75rem; opacity: 0.7; }
        
        .user-info { padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.05); }
        .user-avatar { width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 15px; border: 3px solid rgba(255,255,255,0.2); background-size: cover; background-position: center; }
        .user-name { font-weight: 600; font-size: 1rem; margin-bottom: 5px; }
        .user-role { font-size: 0.8rem; opacity: 0.8; margin-bottom: 10px; }
        
        .nav-menu { padding: 20px 15px; }
        .nav-item { display: flex; align-items: center; padding: 12px 15px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; margin-bottom: 8px; transition: all 0.2s; font-weight: 500; }
        .nav-item:hover, .nav-item.active { background: rgba(255,255,255,0.15); color: #fff; transform: translateX(5px); }
        .nav-item i { width: 25px; margin-right: 10px; text-align: center; }

        .main-content { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
      
        
        .topbar-actions { display: flex; align-items: center; gap: 15px; }
        
        /* Content Area */
        .content-area { padding: 30px; flex: 1; }
        .btn-back { background: #fff; border: 1px solid #ced4da; padding: 8px 16px; border-radius: 8px; color: #495057; font-weight: 500; text-decoration: none; transition: all 0.2s; }
        .btn-back:hover { background: #f8f9fa; border-color: #0f4c81; color: #0f4c81; }
        
        /* Profile Card Styles (Giữ nguyên vẻ đẹp của trang chi tiết) */
        .profile-header-card { background: linear-gradient(135deg, #407a75 0%, #407a75 100%); padding: 10px; color: white; border-radius: 15px 15px 0 0; text-align: center; }
        .profile-img-lg { width: 130px; height: 130px; border-radius: 50%; border: 5px solid rgba(255,255,255,0.3); object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.15); background-color: #fff; margin-bottom: 15px; }
        .info-card { border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-radius: 15px; background: #fff; overflow: hidden; margin-top: 20px; }
        .detail-row { padding: 15px 0; border-bottom: 1px solid #eee; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #666; }
        .detail-value { color: #333; font-weight: 500; }
        .section-title { color: #0f4c81; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f0f2f5; display: inline-block; }
    </style>
</head>

<body>
<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
        <div class="content-area">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?controller=employee&action=index" class="btn-back">
                   Quay lại 
                </a>
                
                <?php 
// Kiểm tra xem người dùng hiện tại có quyền chỉnh sửa không
$currentUserId = $_SESSION['user_id'] ?? null;
$currentRole = $_SESSION['vai_tro'] ?? null; // Lấy từ session
$allowedRoles = ['QUAN_LY', 'NHAN_SU', 'ADMIN'];

// Nút chỉnh sửa chỉ hiện cho Quản lý/Nhân sự/Admin
if ($currentRole && in_array($currentRole, $allowedRoles)): 
?>
<a href="?controller=employee&action=edit&id=<?php echo $employee->getMaNhanVien(); ?>" class="btn btn-warning text-white shadow-sm">
 Chỉnh Sửa Hồ Sơ
</a>
<?php endif; ?>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9">
                    <div class="card info-card">
                        
                        <div class="profile-header-card">
                            <?php 
                                $avatarPath = "/pic2/" . ($employee->getAnhDaiDen() ? $employee->getAnhDaiDen() : 'default.jpg');
                            ?>
                            <img src="<?php echo $avatarPath; ?>" alt="Avatar" class="profile-img-lg">
                            <h2 class="mb-1 fw-bold"><?php echo $employee->getHoTen(); ?></h2>
                           
                        </div>

                        <div class="card-body p-5">
                            <div class="row">
                              
                            </div>
                            
                             <div class="detail-row row align-items-center">
                                <div class="col-sm-4 detail-label">Email </div>
                                <div class="col-sm-8 detail-value">
                                 <?php echo $employee->getEmail(); ?>
                                </div>
                            </div>

                            <div class="detail-row row align-items-center">
                                <div class="col-sm-4 detail-label" style="font-size: 15px;">Mã Nhân Viên</div>
                                <div class="col-sm-8 detail-value">
                                    <span class="badge  text-black"  style="font-size: 15px;"> <?php echo $employee->getMaNhanVien(); ?></span>
                                </div>
                            </div>
                            
                            <div class="detail-row row align-items-center">
                                <div class="col-sm-4 detail-label">Số Điện Thoại</div>
                                <div class="col-sm-8 detail-value" style="font-family: monospace; font-size: 1rem;">
                                    <?php echo $employee->getSoDienThoai(); ?>
                                </div>
                            </div>

                            <div class="detail-row row align-items-center">
                                <div class="col-sm-4 detail-label">Phòng Ban</div>
                                <div class="col-sm-8 detail-value fw-bold ">
                                    <?php echo isset($deptName) ? $deptName : ''; ?>
                                </div>
                            </div>

                            <div class="detail-row row align-items-center">
                                <div class="col-sm-4 detail-label">Chức Vụ</div>
                                <div class="col-sm-8 detail-value">
                                    <?php 
                                        if($employee->getVaiTro() == 'QUAN_LY' || $employee->getVaiTro() == 'quản lý') 
                                            echo '<span class="badge  px-3 py-2">Quản Lý</span>';
                                        elseif($employee->getVaiTro() == 'NHAN_SU' || $employee->getVaiTro() == 'nhân sự') 
                                            echo '<span class="badge  text-dark px-3 py-2">Nhân Sự</span>';
                                        else 
                                            echo '<span class="badge text-dark px-3 py-2 f-5" >Nhân Viên</span>';
                                    ?>
                                </div>
                            </div>

                            <div class="detail-row row align-items-center">
                                <div class="col-sm-4 detail-label">Ngày Vào Làm</div>
                                <div class="col-sm-8 detail-value">
                                    <i class="far fa-calendar-alt me-2 text-muted"></i>
                                    <?php echo date('d/m/Y', strtotime($employee->getNgayVaoLam())); ?>
                                </div>
                            </div>

                            <div class="detail-row row align-items-center">
                                <div class="col-sm-4 detail-label">Trạng Thái</div>
                                <div class="col-sm-8 detail-value">
                                    <?php if ($employee->getTrangThai() == 'DANG_LAM'): ?>
                                        <span class="text-success fw-bold"> Đang làm việc</span>
                                    <?php else: ?>
                                        <span class="text-danger fw-bold"> Đã nghỉ việc</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS để active menu items nếu cần
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Remove active class from all items logic could go here
            });
        });
    </script>
</body>
</html>