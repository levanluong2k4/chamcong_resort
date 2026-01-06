<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Model/emloyee/emloyee.php';
require_once __DIR__ . '/../Model/department/ModelDepartment.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmployeeController {
    private $modelEmployee;
    private $modelDepartment;

    public function __construct() {
        $this->modelEmployee = new ModelEmloyee();
        $this->modelDepartment = new ModelDepartment();
    }

    // 1. Hiển thị form tạo nhân viên
    public function create() {
        // if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] != 'QUAN_LY') {
        //     die("Bạn không có quyền truy cập chức năng này.");
        // }
        // Cho phép QUAN_LY, NHAN_SU, ADMIN
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['vai_tro'], ['QUAN_LY', 'NHAN_SU', 'ADMIN'])) {
    die("Bạn không có quyền truy cập chức năng này.");
}

        
        $manager = $this->modelEmployee->getEmloyeeById($_SESSION['user_id']);
        $department = $this->modelDepartment->getdepartmentby_user_id($manager->getMaPhongBan());

        require_once __DIR__ . '/../View/employee/create.php';
    }

    // 2. Xử lý lưu và gửi mail
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $managerId = $_SESSION['user_id'];
            $manager = $this->modelEmployee->getEmloyeeById($managerId);
            
           
            $hoTen = $_POST['ho_ten'];
            $email = $_POST['email'];
            $sdt = $_POST['so_dien_thoai'];
            $vaiTro = $_POST['vai_tro']; 
            $ngayVaoLam = $_POST['ngay_vao_lam'];
            $trangThai = isset($_POST['trang_thai']) ? $_POST['trang_thai'] : 'DANG_LAM';
           
            $maPhongBan = $manager->getMaPhongBan(); 
            $maQuanLy = $managerId;

          
            if ($this->modelEmployee->checkEmailExists($email)) {
                echo "<script>alert('Email đã tồn tại!'); window.history.back();</script>";
                return;
            }

            // Xử lý upload ảnh đại diện
            $anhDaiDien = 'default.jpg'; 
            if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
                $targetDir = __DIR__ . "/../../uploads/avatars/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                
                $ext = pathinfo($_FILES["anh_dai_dien"]["name"], PATHINFO_EXTENSION);
                $fileName = time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES["anh_dai_dien"]["tmp_name"], $targetDir . $fileName)) {
                    $anhDaiDien = $fileName;
                }
            }

            //SINH MẬT KHẨU TẠM THỜI 
            $rawPassword = $this->generateRandomPassword(8); 
            $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT); 

          
            $result = $this->modelEmployee->createEmployee(
                $hoTen, $email, $hashedPassword, $vaiTro, 
                $maPhongBan, $maQuanLy, $sdt, $anhDaiDien, $ngayVaoLam,
                $trangThai
            );

            if ($result) {
                
                $sendMail = $this->sendWelcomeEmail($email, $hoTen, $rawPassword);
                
                if ($sendMail === true) {
                    echo "<script>
                        alert('Tạo tài khoản thành công! Mật khẩu đã gửi tới email nhân viên.');
                        window.location.href = '?controller=home&action=index';
                    </script>";
                } else {
                    echo "<script>
                        alert('Tạo tài khoản thành công nhưng GỬI MAIL THẤT BẠI: $sendMail');
                        window.location.href = '?controller=home&action=index';
                    </script>";
                }
            } else {
                echo "<script>alert('Lỗi Database!'); window.history.back();</script>";
            }
        }
    }

    // Hàm sinh mật khẩu ngẫu nhiên
    private function generateRandomPassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        return substr(str_shuffle($chars), 0, $length);
    }

    
    private function sendWelcomeEmail($toEmail, $name, $password) {
        $mail = new PHPMailer(true);
         $app_password = 'zgrf kwum mkpp vqer';   
        try {
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'xuannam1234zz@gmail.com'; 
            $mail->Password   = $app_password;    
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

           
            $mail->setFrom('xuannam1234zz@gmail.com', 'Resort Rosa Alba');
            $mail->addAddress($toEmail, $name);

            
            $mail->isHTML(true);
            $mail->Subject = 'Thông tin tài khoản nhân viên mới';
            $mail->Body    = "
                <h3>Chào mừng $name gia nhập Resort Rosa Alba!</h3>
                <p>Tài khoản của bạn đã được khởi tạo thành công.</p>
                <p>Thông tin đăng nhập:</p>
                <ul>
                    <li><strong>Email:</strong> $toEmail</li>
                    <li><strong>Mật khẩu tạm thời:</strong> <span style='color:red; font-size:16px;'>$password</span></li>
                </ul>
               
                <p>Trân trọng,<br>Phòng Quản Lý.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }


    // Thêm hàm index vào Class EmployeeController
    // Trong class EmployeeController
    // public function index() {
    //     if (!isset($_SESSION['user_id'])) {
    //         header('Location: ?controller=login&action=index');
    //         exit();
    //     }

    //     $userId = $_SESSION['user_id'];
        
    //     // Lấy thông tin người đang đăng nhập
    //     $currentUser = $this->modelEmployee->getEmloyeeById($userId);
    //     $userRole = $currentUser->getVaiTro(); 
    //     $deptId = $currentUser->getMaPhongBan();

    //     $employees = [];
    //     $title = "";

    //     // 1. Lấy TOÀN BỘ danh sách trước
    //     if ($userRole == 'ADMIN') {
    //         $employees = $this->modelEmployee->getEmployeesForAdmin();
    //         $title = "Danh sách Quản lý & Nhân sự";
    //     } else {
    //         $employees = $this->modelEmployee->getEmployeesByDepartment($deptId);
    //         $deptModel = new ModelDepartment();
    //         $dept = $deptModel->getdepartmentby_user_id($deptId);
    //         $deptName = $dept ? $dept->getTenPhongBan() : "";
    //         $title = "Danh sách nhân viên - Bộ phận " . $deptName;
    //     }

    //     // --- XỬ LÝ PHÂN TRANG TẠI ĐÂY ---
    //     $limit = 5; // Số lượng người mỗi trang
    //     $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    //     if ($page < 1) $page = 1;

    //     $totalEmployees = count($employees);
    //     $totalPages = ceil($totalEmployees / $limit);

    //     // Tính vị trí bắt đầu cắt mảng
    //     $offset = ($page - 1) * $limit;

    //     // Cắt mảng employees chỉ lấy phần cần hiển thị
    //     $employeesPaged = array_slice($employees, $offset, $limit);

    //     // Truyền các biến cần thiết sang View
    //     // $employeesPaged: Danh sách nhân viên của trang hiện tại
    //     // $totalPages: Tổng số trang
    //     // $page: Trang hiện tại
    //     // $stt: Số thứ tự bắt đầu (để STT không bị reset về 1 ở trang 2)
    //     $stt = $offset + 1;

    //     require_once __DIR__ . '/../View/employee/index.php';
    // }
    // 1.  HÀM INDEX: Xử lý tìm kiếm và phân trang
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=login&action=index');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $currentUser = $this->modelEmployee->getEmloyeeById($userId);
        $userRole = $currentUser->getVaiTro(); 
        $deptId = $currentUser->getMaPhongBan();

       
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

        $employees = [];
        $title = "";

        if ($userRole == 'ADMIN') { 
            $employees = $this->modelEmployee->getEmployeesForAdmin($keyword);
            $title = "Danh sách Quản lý & Nhân sự";
        } else {
            $employees = $this->modelEmployee->getEmployeesByDepartment($deptId, $keyword);
            $deptModel = new ModelDepartment();
            $dept = $deptModel->getdepartmentby_user_id($deptId);
            $deptName = $dept ? $dept->getTenPhongBan() : "";
            $title = "Danh sách nhân viên - Bộ phận " . $deptName;
        }

        
        $limit = 5; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $totalEmployees = count($employees);
        $totalPages = ceil($totalEmployees / $limit);
        $offset = ($page - 1) * $limit;
        $employeesPaged = array_slice($employees, $offset, $limit);
        $stt = $offset + 1;

        require_once __DIR__ . '/../View/employee/index.php';
    }

    // 2. THÊM HÀM DELETE
    public function delete() {
        if (!isset($_GET['id'])) {
            echo "<script>alert('Không tìm thấy ID nhân viên!'); window.history.back();</script>";
            return;
        }
        
      
        // if ($_SESSION['vai_tro'] != 'QUAN_LY') {
        //     echo "<script>alert('Bạn không có quyền xóa!'); window.history.back();</script>";
        //     return;
        // }
        if (!in_array($_SESSION['vai_tro'], ['QUAN_LY', 'NHAN_SU', 'ADMIN'])) {
    echo "<script>alert('Bạn không có quyền xóa!'); window.history.back();</script>";
    return;
}

        $id = $_GET['id'];
        $result = $this->modelEmployee->deleteEmployee($id);

        if ($result) {
            echo "<script>alert('Xóa nhân viên thành công!'); window.location.href='?controller=employee&action=index';</script>";
        } else {
            echo "<script>alert('Xóa thất bại! Có thể nhân viên này đang có dữ liệu chấm công.'); window.history.back();</script>";
        }
    }



    // 1. Xem chi tiết nhân viên
    public function detail() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=employee&action=index');
            exit();
        }

        $id = $_GET['id'];
       
        $employee = $this->modelEmployee->getEmloyeeById($id);
        
        
        $deptName = "Chưa xác định";
        if($employee && $employee->getMaPhongBan()) {
             $dept = $this->modelDepartment->getdepartmentby_user_id($employee->getMaPhongBan());
             if($dept) $deptName = $dept->getTenPhongBan();
        }

        if (!$employee) {
            echo "<script>alert('Nhân viên không tồn tại!'); window.history.back();</script>";
            return;
        }

        require_once __DIR__ . '/../View/employee/detail.php';
    }

    // 2. Hiển thị Form Edit nv
    public function edit() {
       
        // if (!isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] != 'QUAN_LY') {
        //     echo "<script>alert('Bạn không có quyền chỉnh sửa!'); window.history.back();</script>";
        //     return;
        // }
        
        if (!isset($_SESSION['vai_tro']) || !in_array($_SESSION['vai_tro'], ['QUAN_LY', 'NHAN_SU', 'ADMIN'])) {
            echo "<script>alert('Bạn không có quyền chỉnh sửa!'); window.history.back();</script>";
            return;
        }

        if (!isset($_GET['id'])) {
            header('Location: ?controller=employee&action=index');
            exit();
        }

        $id = $_GET['id'];
        $employee = $this->modelEmployee->getEmloyeeById($id);

        if (!$employee) {
            echo "<script>alert('Nhân viên không tồn tại!'); window.history.back();</script>";
            return;
        }

        require_once __DIR__ . '/../View/employee/edit.php';
    }

    // 3. Xử lý cập nhật dữ liệu
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ma_nhan_vien'];
            $hoTen = $_POST['ho_ten'];
            $sdt = $_POST['so_dien_thoai'];
            $vaiTro = $_POST['vai_tro'];
            $trangThai = $_POST['trang_thai'];

            $result = $this->modelEmployee->updateEmployee($id, $hoTen, $sdt, $vaiTro, $trangThai);

            if ($result) {
                echo "<script>
                        alert('Cập nhật thông tin thành công!');
                        window.location.href = '?controller=employee&action=detail&id=$id';
                      </script>";
            } else {
                echo "<script>alert('Lỗi cập nhật!'); window.history.back();</script>";
            }
        }
    }
}
?>