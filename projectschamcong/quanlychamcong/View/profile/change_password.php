<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu - Resort Rosa Alba</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/lichtuan.css">


</head>
<style>
     /* --- Main Content Styles --- */
     
       
        /* Widget Card giống trang Profile */
        .widget-card {
            background: #ffffff0d;
            border-radius: 16px;
            border: none;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden; /* Để bo góc header không bị tràn */
        }

        .widget-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .widget-title {
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Style cho nút Back */
        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background: #fff;
            color: #0f4c81;
        }

        /* Style cho Input Password Toggle */
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            color: #0f4c81;
        }
        
        .form-control:focus {
            border-color: #0f4c81;
            box-shadow: 0 0 0 0.25rem rgba(15, 76, 129, 0.15);
        }

        .btn-toggle-password {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-left: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10; /* Đảm bảo nút nằm trên */
        }
        
        .btn-toggle-password:hover {
            background-color: #f8f9fa;
            color: #0f4c81;
        }

        /* Sửa lại border input để khớp với nút toggle */
        .input-password-field {
            border-right: none;
        }
</style>
<body>
   
<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
 

   
       

        

        <div class="content-area">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="widget-card">
                        <div class="widget-header">
                            <div class="widget-title">Thông Tin Mật Khẩu</div>
                        </div>
                        <div class="widget-body p-4">
                            <form action="?controller=profile&action=change_password" method="POST"
                                onsubmit="return validateClientSide()">

                                <div class="mb-3">
                                    <label for="old_password" class="form-label fw-bold">Mật khẩu hiện tại</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control input-password-field" id="old_password" name="old_password"
                                            required placeholder="Nhập mật khẩu cũ..."
                                            value="<?php echo isset($currentPasswordValue) ? htmlspecialchars($currentPasswordValue) : ''; ?>">
                                        
                                        <button class="btn btn-toggle-password" type="button" onclick="togglePassword('old_password', 'icon_old')">
                                            <i class="fas fa-eye" id="icon_old"></i>
                                        </button>
                                    </div>
                                </div>

                                <hr class="my-4" style="border-top: 1px dashed #ccc;">

                                <div class="mb-3">
                                    <label for="new_password" class="form-label fw-bold">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control input-password-field" id="new_password"
                                            name="new_password" required placeholder="Nhập mật khẩu mới...">
                                        
                                        <button class="btn btn-toggle-password" type="button" onclick="togglePassword('new_password', 'icon_new')">
                                            <i class="fas fa-eye" id="icon_new"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-muted mt-2 small">
                                        <i class="fas fa-info-circle text-info"></i> Yêu cầu: 8-20 ký tự, gồm chữ Hoa, Số và Ký tự đặc biệt.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                        <input type="password" class="form-control input-password-field" id="confirm_password"
                                            name="confirm_password" required placeholder="Nhập lại mật khẩu mới...">
                                        
                                        <button class="btn btn-toggle-password" type="button" onclick="togglePassword('confirm_password', 'icon_confirm')">
                                            <i class="fas fa-eye" id="icon_confirm"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-bold text-white shadow-sm"
                                        style="background: linear-gradient(135deg,#407a75 0%,#407a75 100%); border: none;">
                                        <i class="fas fa-save me-2"></i> Lưu Thay Đổi
                                    </button>
                                    <a href="?controller=profile&action=index"
                                        class="btn btn-light btn-lg text-muted border">Hủy Bỏ</a>
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
        // Hàm ẩn hiện mật khẩu
        function togglePassword(inputId, iconId) {
            const inputField = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (inputField.type === "password") {
                inputField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash"); // Đổi icon thành mắt gạch chéo
            } else {
                inputField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye"); // Đổi icon thành mắt mở
            }
        }

        function validateClientSide() {
            var newPass = document.getElementById("new_password").value;
            var confirmPass = document.getElementById("confirm_password").value;
            var regex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,20}$/;

            if (!regex.test(newPass)) {
                alert("Mật khẩu mới không đạt yêu cầu!\n- Độ dài: 8-20 ký tự\n- Phải có chữ HOA\n- Phải có SỐ\n- Phải có ký tự ĐẶC BIỆT");
                return false;
            }
            if (newPass !== confirmPass) {
                alert("Mật khẩu xác nhận không khớp!");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>