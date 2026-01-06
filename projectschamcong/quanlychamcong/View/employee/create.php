<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Rosa Alba - Tạo Tài Khoản Nhân Viên</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
       <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    
    <style>
        /* CSS Bổ sung để đồng bộ với giao diện Dashboard */
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
            background-color: #407a75;
        
        }

        .widget-title {
            font-size: 1.1rem;
            font-weight: 700;
            color:white;
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

        /* Nút bấm Gradient đẹp mắt */
        .btn-gradient {
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s;
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
        
        /* Card thông báo thông tin read-only */
        .info-card-readonly {
            background-color: #f8f9fa;
            border: 1px dashed #cbd5e0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
          

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="widget-card">
                        <div class="widget-header">
                            <div class="widget-title">Thông Tin Nhân Viên Mới</div>
                        </div>
                        <div class="widget-body">
                            <!-- <form action="?controller=employee&action=store" method="POST" enctype="multipart/form-data">
                                
                            

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Họ và Tên </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"></span>
                                            <input type="text" name="ho_ten" class="form-control border-start-0" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email: </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"></span>
                                            <input type="email" name="email" class="form-control border-start-0" placeholder="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại </span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"></span>
                                            <input type="text" name="so_dien_thoai" class="form-control border-start-0" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ngày vào làm </label>
                                        <input type="date" name="ngay_vao_lam" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Chức vụ</label>
                                        <select name="vai_tro" class="form-select">
                                            <option value="NHAN_VIEN" selected>Nhân Viên</option>
                                            </select>

                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ảnh đại diện</label>
                                        <input type="file" name="anh_dai_dien" class="form-control" accept="image/*">
                                      
                                    </div>
                                </div>

                               

                                <div class="d-flex justify-content-end gap-3 mt-4">
                                    <a href="?controller=home&action=index" class="btn btn-light px-4 py-2 border">
                                       Hủy
                                    </a>
                                    <button type="submit" class="btn-gradient">
                                        Gửi 
                                    </button>
                                </div>
                            </form> -->
                        
                        <form action="?controller=employee&action=store" method="POST" enctype="multipart/form-data">
    
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"></span>
                <input type="text" name="ho_ten" class="form-control border-start-0" required>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <div class="input-group">
            
                <input type="email" name="email" class="form-control border-start-0" required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
            <div class="input-group">
               
                <input type="text" name="so_dien_thoai" class="form-control border-start-0" required>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Ngày vào làm <span class="text-danger">*</span></label>
            <input type="date" name="ngay_vao_lam" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Phòng Ban </label>
            <div class="input-group">
               
                <input type="text" class="form-control border-start-0 bg-light" 
                       value="<?php echo isset($department) ? $department->getTenPhongBan() : 'Không xác định'; ?>" 
                       readonly disabled>
            </div>
         
        </div>
        <div class="col-md-6">
            <label class="form-label">Chức vụ</label>
            <select name="vai_tro" class="form-select">
                <option value="NHAN_VIEN" selected>Nhân Viên</option>
               
                </select>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <label class="form-label">Trạng thái làm việc</label>
            <select name="trang_thai" class="form-select">
                <option value="DANG_LAM" selected>Đang làm </option>
               
                <option value="DA_NGHI"> Đã nghỉ</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Ảnh đại diện</label>
            <input type="file" name="anh_dai_dien" class="form-control" accept="image/*">
        </div>
    </div>

    <div class="d-flex justify-content-end gap-3 mt-4">
        <a href="?controller=home&action=index" class="btn btn-light px-4 py-2 border">Hủy</a>
        <button type="submit" class="btn-gradient">
           Tạo Nhân Viên
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
    <script>
        // Xử lý active menu (nếu cần)
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Xóa active cũ
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                // Thêm active mới
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>