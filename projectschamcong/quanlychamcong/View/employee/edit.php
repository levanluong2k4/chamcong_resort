<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Nhân Viên - <?php echo $employee->getHoTen(); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        :root { --primary-color: #0f4c81; --light-bg: #f5f7fb; --border-color: #e0e0e0; }
        body { font-family: 'Inter', sans-serif; background-color: var(--light-bg); }
        
        /* Sidebar & Layout Styles (Copy từ trang Detail) */
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
        .content-area { padding: 30px; flex: 1; }
        
        /* Form Specific Styles */
        .btn-back { background: #fff; border: 1px solid #ced4da; padding: 8px 16px; border-radius: 8px; color: #495057; font-weight: 500; text-decoration: none; transition: all 0.2s; }
        .btn-back:hover { background: #f8f9fa; border-color: #0f4c81; color: #0f4c81; }

        /* Card Styles giống trang Detail */
        .profile-header-card { background: linear-gradient(135deg, #407a75 0%, #407a75 100%); padding: 10px; color: white; border-radius: 15px 15px 0 0; text-align: center; }
        .profile-img-lg { width: 130px; height: 130px; border-radius: 50%; border: 5px solid rgba(255,255,255,0.3); object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.15); background-color: #fff; margin-bottom: 15px; }
        .info-card { border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-radius: 15px; background: #fff; overflow: hidden; margin-top: 20px; }
        
        .form-label { font-weight: 600; color: #0f4c81; margin-bottom: 8px; }
        .form-control, .form-select { padding: 12px; border-radius: 8px; border: 1px solid #dee2e6; }
        .form-control:focus, .form-select:focus { border-color: #0f4c81; box-shadow: 0 0 0 0.2rem rgba(15, 76, 129, 0.15); }
        
        .btn-save {
            background: linear-gradient(135deg,#407a75 0%, #407a75 100%);
            color: white; border: none; padding: 12px 40px;
            font-weight: 600; letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(15, 76, 129, 0.3);
            transition: all 0.3s;
        }
      
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo"><i class="fas fa-hotel fa-lg text-white"></i></div>
                <div>
                    <div class="brand-name">Resort Rosa Alba</div>
                    <div class="brand-subtitle">Hệ Thống Quản Lý</div>
                </div>
            </div>
        </div>

        <div class="user-info">
            <div class="user-avatar" style="background-image: url(/pic2/<?php echo isset($_SESSION['anh_dai_dien']) ? $_SESSION['anh_dai_dien'] : 'default.jpg'; ?>);"></div>
            <div class="user-name"><?php echo isset($_SESSION['ho_ten']) ? $_SESSION['ho_ten'] : 'User Admin'; ?></div>
            <div class="user-role"><?php echo isset($_SESSION['vai_tro']) ? $_SESSION['vai_tro'] : 'Quản Lý'; ?></div>
        </div>

        <div class="nav-menu">
            <a href="?controller=home&action=index" class="nav-item">
                <i class="fas fa-home"></i> <span>Trang Chủ</span>
            </a>

            <?php 
            $currentRole = isset($_SESSION['vai_tro']) ? $_SESSION['vai_tro'] : '';
            $allowedRoles = ['quản lý', 'nhân sự', 'Quản Trị Viên', 'QUAN_LY', 'NHAN_SU', 'QUAN_TRI_VIEN'];
            if (in_array($currentRole, $allowedRoles)) { 
            ?>
                <a href="?controller=duyetdon&action=index" class="nav-item">
                    <i class="fas fa-file-invoice"></i> <span>Duyệt Đơn</span>
                </a>
            <?php } ?>

            <?php if (in_array($currentRole, $allowedRoles)) { ?>
                <a href="?controller=employee&action=index" class="nav-item active">
                    <i class="fas fa-users"></i> <span>Nhân Viên Của Tôi</span>
                </a>
            <?php } ?>

            <a href="?controller=leave&action=index" class="nav-item">
                <i class="fas fa-file-invoice"></i> <span>Đơn Xin Nghỉ Phép</span>
            </a>

            <a href="?controller=profile&action=index" class="nav-item"> 
                <i class="fas fa-user-circle"></i> <span>Hồ Sơ Cá Nhân</span>
            </a>

            <?php if (in_array($currentRole, $allowedRoles)) { ?>
                <a href="?controller=overtime&action=index" class="nav-item">
                    <i class="fas fa-chart-bar"></i> <span>Tăng ca</span>
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div>
                <div class="page-title">
                    <i class="fas fa-user-edit"></i> Cập Nhật Thông Tin Nhân Viên
                </div>
            </div>
            <div class="topbar-actions">
                <button class="btn btn-light position-relative rounded-circle shadow-sm" style="width: 40px; height: 40px;">
                    <i class="fas fa-bell text-secondary"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </button>
                <a href="?controller=login&action=sign_out" class="btn btn-outline-danger border-0 rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <div class="content-area">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="?controller=employee&action=detail&id=<?php echo $employee->getMaNhanVien(); ?>" class="btn-back">
                 Quay lại
                </a>
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
                            <p class="mb-0 opacity-75">
                                <span class=" text-white">
                                    Mã nhân viên: <?php echo $employee->getMaNhanVien(); ?>
                                </span>
                            </p>
                        </div>

                        <div class="card-body p-5">
                            <form action="?controller=employee&action=update" method="POST">
                                <input type="hidden" name="ma_nhan_vien" value="<?php echo $employee->getMaNhanVien(); ?>">

                                <div class="row g-4">
                                   

                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label></label>
                                        <div class="input-group">
                                         
                                            <input type="email" class="form-control bg-light" value="<?php echo $employee->getEmail(); ?>" disabled>
                                        </div>
                                        
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                          
                                            <input type="text" name="ho_ten" class="form-control" required 
                                                   value="<?php echo $employee->getHoTen(); ?>" placeholder="Nhập họ và tên">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label></label>
                                        <div class="input-group">
                                         
                                            <input type="text" name="so_dien_thoai" class="form-control" 
                                                   value="<?php echo $employee->getSoDienThoai(); ?>" placeholder="Nhập số điện thoại">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Vai Trò <span class="text-danger">*</span></label></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-badge text-primary"></i></span>
                                            <select name="vai_tro" class="form-select">
                                                <option value="NHAN_VIEN" <?php echo ($employee->getVaiTro() == 'NHAN_VIEN') ? 'selected' : ''; ?>>Nhân Viên</option>
                                                <option value="NHAN_SU" <?php echo ($employee->getVaiTro() == 'NHAN_SU') ? 'selected' : ''; ?>>Nhân Sự</option>
                                                <option value="QUAN_LY" <?php echo ($employee->getVaiTro() == 'QUAN_LY') ? 'selected' : ''; ?>>Quản Lý</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="p-3 border rounded bg-light">
                                            <label class="form-label mb-3 d-block">Trạng Thái Làm Việc</label>
                                            <div class="d-flex gap-5">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="trang_thai" id="statusActive" value="DANG_LAM" 
                                                        <?php echo ($employee->getTrangThai() == 'DANG_LAM') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label text-success fw-bold" for="statusActive">
                                                     Đang làm 
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="trang_thai" id="statusInactive" value="DA_NGHI"
                                                        <?php echo ($employee->getTrangThai() != 'DANG_LAM') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label text-danger fw-bold" for="statusInactive">
                                                        Đã nghỉ 
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4 text-center">
                                        <button type="submit" class="btn btn-save rounded-pill">
                                             Lưu Thay Đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Active menu logic
            });
        });
    </script>
</body>
</html>