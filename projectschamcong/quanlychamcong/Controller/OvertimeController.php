<?php
require_once __DIR__ . '/../Model/overtime/ModelOvertime.php';
require_once __DIR__ . '/../Model/emloyee/emloyee.php';

require_once __DIR__ . '/../Model/department/ModelDepartment.php'; 

class OvertimeController {
    private $model;

    public function __construct() {
        $this->model = new ModelOvertime();
    }

 
    public function index() {
       
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=login');
            exit();
        }

      

       
        $objModelEmloyee = new ModelEmloyee();
        $objEmployee = $objModelEmloyee->getEmloyeeById($_SESSION['user_id']);

       
       $userRoleGoc = $objEmployee->getVaiTro(); 
        $userPhongBan = $objEmployee->getMaPhongBan();
        $userId = $_SESSION['user_id'];

        if (!$objEmployee) {
            echo "Không tìm thấy thông tin nhân viên.";
            exit();
        }

        $objModelDepartment = new ModelDepartment();
        $objDepartment = $objModelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());
        
       
        $departmentIcons = [
            'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>',
            'Marketing' => '<i class="fas fa-bullhorn  me-2"></i>',
            'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>',
            'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>',
            'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>',
            'Spa & Massage' => '<i class="fas fa-spa me-2"></i>',
            'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>',
            'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>',
            'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>',
            'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>',
        ];

       
        $vai_tro_hien_thi = "Nhân viên";
       switch ($userRoleGoc) { 
            case 'QUAN_LY': $vai_tro_hien_thi = "Quản lý"; break;
            case 'NHAN_SU': $vai_tro_hien_thi = "Nhân sự"; break;
            case 'ADMIN': $vai_tro_hien_thi = "Giám đốc"; break;
        }
        
        $objEmployee->setVaiTro($vai_tro_hien_thi);


       
       $requests = $this->model->getAllRequests($userRoleGoc, $userPhongBan, $userId);
        
   

  
    if ($userRoleGoc == 'ADMIN' || $userRoleGoc == 'NHAN_SU') {
       
        $statsRaw = $this->model->getAllOverviewStats();
    } else {
      
        $statsRaw = $this->model->getOverviewStats($userPhongBan);
    }
        $stats = [
            'tong_duyet' => $statsRaw['tong_can'], 
            'da_dung'    => $statsRaw['da_dung'],  
            'con_lai'    => $statsRaw['tong_du']  
        ];

      
        $percentUsed = ($stats['tong_duyet'] > 0) ? ($stats['da_dung'] / $stats['tong_duyet']) * 100 : 0;


  $tong_gio_cong_don = $this->model->getTongGioTangCaByDept($objEmployee->getMaPhongBan());
        

 
    $employeeHistory = $this->model->getEmployeeOvertimeHistory($userRoleGoc, $userPhongBan);
     

    
        require_once __DIR__ . '/../View/overtime/index.php';
    }

  
    public function create() {
        if (!isset($_SESSION['user_id'])) header('Location: ?controller=login');
        
      
        $objModelEmloyee = new ModelEmloyee();
        $objEmployee = $objModelEmloyee->getEmloyeeById($_SESSION['user_id']);
        
        $objModelDepartment = new ModelDepartment();
        $objDepartment = $objModelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());
        
        $departmentIcons = [
            'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>',
            'Marketing' => '<i class="fas fa-bullhorn  me-2"></i>',
            'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>',
            'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>',
            'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>',
            'Spa & Massage' => '<i class="fas fa-spa me-2"></i>',
            'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>',
            'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>',
            'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>',
            'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>',
        ];
       
        $employees = $this->model->getEmployeesByDept($objEmployee->getMaPhongBan());
        $shifts = $this->model->getShifts();
        
        require_once __DIR__ . '/../View/overtime/create.php';
    }

  
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tong_gio_can = $_POST['tong_gio_can'];
            $nhan_vien_ids = $_POST['nhan_vien'] ?? []; 
            $gio_dang_ky = $_POST['so_gio'] ?? []; 
            $ghi_chu_arr = $_POST['ghi_chu'] ?? [];


           $tong_gio_da_phan = 0;
foreach ($gio_dang_ky as $gio) {
    // Ép kiểu về float để đảm bảo an toàn nếu người dùng nhập số lẻ hoặc bỏ trống
    $tong_gio_da_phan += (float)$gio; 
}
            $so_gio_du = $tong_gio_can - $tong_gio_da_phan;
            
            if ($so_gio_du < 0) {
                echo "<script>alert('Tổng giờ nhân viên vượt quá tổng giờ cần!'); window.history.back();</script>";
                return;
            }

            $objModelEmloyee = new ModelEmloyee();
            $user = $objModelEmloyee->getEmloyeeById($_SESSION['user_id']);
            

            $data = [
                'ma_nguoi_tao' => $_SESSION['user_id'],
                'ma_phong_ban' => $user->getMaPhongBan(),
                'ngay_tang_ca' => $_POST['ngay_tang_ca'],
                'ma_ca' => $_POST['ma_ca'],
                'tong_gio_can' => $tong_gio_can,
                'so_gio_du' => $so_gio_du,
                'ly_do' => $_POST['ly_do']
            ];
         

            $ma_don = $this->model->createRequest($data);

            if ($ma_don) {
                foreach ($nhan_vien_ids as $key => $ma_nv) {
                    $gio = $gio_dang_ky[$key];
                    $note = isset($ghi_chu_arr[$key]) ? $ghi_chu_arr[$key] : '';
                    if ($gio > 0) {
                        $this->model->addDetail($ma_don, $ma_nv, $gio,$note);
                    }
                }
                header('Location: ?controller=overtime&action=index');
            } else {
                echo "Lỗi tạo đơn";
            }
        }
    }

   
   public function detail() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?controller=login');
        exit();
    }

   
    $objModelEmloyee = new ModelEmloyee();
    $objEmployee = $objModelEmloyee->getEmloyeeById($_SESSION['user_id']);
    
    if (!$objEmployee) {
        die("Không tìm thấy thông tin nhân viên.");
    }

    $userRoleGoc = $objEmployee->getVaiTro(); 
    $_SESSION['role'] = $userRoleGoc;

   
    $objModelDepartment = new ModelDepartment();
    $objDepartment = $objModelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());

  
    $departmentIcons = [
        'Hành Chính - Nhân Sự' => '<i class="fas fa-users-cog me-2"></i>',
        'Marketing' => '<i class="fas fa-bullhorn me-2"></i>',
        'Kỹ Thuật' => '<i class="fas fa-tools me-2"></i>',
        'Bảo Vệ' => '<i class="fas fa-shield-alt me-2"></i>',
        'Bể Bơi' => '<i class="fas fa-swimming-pool me-2"></i>',
        'Spa & Massage' => '<i class="fas fa-spa me-2"></i>',
        'Bar & Lounge' => '<i class="fas fa-cocktail me-2"></i>',
        'Nhà Hàng' => '<i class="fas fa-utensils me-2"></i>',
        'Buồng Phòng' => '<i class="fas fa-bed me-2"></i>',
        'Lễ Tân' => '<i class="fas fa-concierge-bell me-2"></i>',
    ];

  
    $id = $_GET['id'];
    $request = $this->model->getRequestById($id);
    if (!$request) {
        die("Đơn tăng ca không tồn tại.");
    }

    $details = $this->model->getEmployeesInRequest($id);
   
    $employees = $this->model->getEmployeesByDept($request['ma_phong_ban']);

   
    $tong_gio_can = $request['tong_gio_can'];
    $gio_da_phan_bo = $tong_gio_can - $request['so_gio_du'];
    $gio_con_lai = $request['so_gio_du'];

    $stats = [
        'tong_duyet' => $tong_gio_can,
        'da_dung'    => $gio_da_phan_bo,
        'con_lai'    => $gio_con_lai
    ];

    $percentUsed = ($tong_gio_can > 0) ? ($gio_da_phan_bo / $tong_gio_can) * 100 : 0;
    $tong_gio_cong_don = $this->model->getTongGioTangCaByDept($request['ma_phong_ban']);

   
    require_once __DIR__ . '/../View/overtime/detail.php';
}

    
public function approve() {
    
    $objModelEmloyee = new ModelEmloyee();
    $user = $objModelEmloyee->getEmloyeeById($_SESSION['user_id']);
    $role = $user->getVaiTro();

    if (!in_array($role, ['ADMIN', 'NHAN_SU'])) {
        echo "<script>alert('Bạn không có quyền thực hiện thao tác này!'); window.history.back();</script>";
        return;
    }

    $id = $_GET['id'];
    $request = $this->model->getRequestById($id);

    if ($request && $request['trang_thai'] == 'CHO_DUYET') {
      
        $this->model->updateStatus($id, 'DA_DUYET');

     
        $details = $this->model->getEmployeesInRequest($id);
        while ($row = $details->fetch_assoc()) {
            $this->model->updateEmployeeBalance($row['ma_nhan_vien'], $row['so_gio_dang_ky'], '+');
        }
        
        echo "<script>alert('Duyệt thành công! Giờ tăng ca đã được cộng cho nhân viên.'); window.location.href='?controller=overtime&action=index';</script>";
    } else {
        header("Location: ?controller=overtime&action=index");
    }
}

 
    public function remove_employee() {
        $id_detail = $_GET['id_detail'];
        $detail = $this->model->getDetailById($id_detail);
        $request = $this->model->getRequestById($detail['ma_don_tang_ca']);

        if ($detail) {
            $this->model->deleteDetail($id_detail);
            $this->model->updateExcessHours($detail['ma_don_tang_ca'], $detail['so_gio_dang_ky'], '+');

            if ($request['trang_thai'] == 'DA_DUYET') {
                $this->model->updateEmployeeBalance($detail['ma_nhan_vien'], $detail['so_gio_dang_ky'], '-');
            }

            header("Location: ?controller=overtime&action=detail&id=" . $detail['ma_don_tang_ca']);
        }
    }

   
    public function add_employee() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_don = $_POST['ma_don'];
            $ma_nv = $_POST['ma_nhan_vien'];
            $so_gio = $_POST['so_gio'];
            $ghi_chu = $_POST['ghi_chu'] ?? '';

            $request = $this->model->getRequestById($ma_don);

            if ($so_gio > $request['so_gio_du']) {
                echo "<script>alert('Số giờ nhập lớn hơn số giờ dư hiện có!'); window.history.back();</script>";
                return;
            }

            $this->model->addDetail($ma_don, $ma_nv, $so_gio, $ghi_chu);
            $this->model->updateExcessHours($ma_don, $so_gio, '-');

            if ($request['trang_thai'] == 'DA_DUYET') {
                $this->model->updateEmployeeBalance($ma_nv, $so_gio, '+');
            }

            header("Location: ?controller=overtime&action=detail&id=$ma_don");
        }
    }
  
    public function reject() {
        $id = $_GET['id'];
        $request = $this->model->getRequestById($id);

        if ($request['trang_thai'] == 'CHO_DUYET') {
            $this->model->updateStatus($id, 'TU_CHOI');
            echo "<script>alert('Đã từ chối đơn tăng ca!'); window.location.href='?controller=overtime&action=index';</script>";
        } else {
             header("Location: ?controller=overtime&action=index");
        }
    }

       public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
           
            $request = $this->model->getRequestById($id);

            if (!$request) {
                echo "<script>alert('Đơn không tồn tại!'); window.history.back();</script>";
                return;
            }

           
            if ($request['trang_thai'] == 'DA_DUYET') {
                 $details = $this->model->getEmployeesInRequest($id);
                 if ($details) {
                     while ($row = $details->fetch_assoc()) {
                     
                         $this->model->updateEmployeeBalance($row['ma_nhan_vien'], $row['so_gio_dang_ky'], '-');
                     }
                 }
            }

            
            if ($this->model->deleteRequest($id)) {
                $msg = ($request['trang_thai'] == 'DA_DUYET') 
                    ? 'Đã xóa đơn ĐÃ DUYỆT và cập nhật lại quỹ giờ nhân viên!' 
                    : 'Xóa đơn thành công!';
                
                echo "<script>alert('$msg'); window.location.href='?controller=overtime&action=index';</script>";
            } else {
                echo "<script>alert('Lỗi khi xóa đơn!'); window.location.href='?controller=overtime&action=index';</script>";
            }
        }
    }

public function update_employee_detail() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ma_chi_tiet = $_POST['ma_chi_tiet'];
        $so_gio_moi = (float)$_POST['so_gio'];
        $ghi_chu = $_POST['ghi_chu'];

        $old_detail = $this->model->getDetailById($ma_chi_tiet);
        $ma_don = $old_detail['ma_don_tang_ca'];
        $so_gio_cu = (float)$old_detail['so_gio_dang_ky'];
        $ma_nv = $old_detail['ma_nhan_vien'];

        $request = $this->model->getRequestById($ma_don);
        $quy_gio_hien_tai = (float)$request['so_gio_du'];

        // Tính toán sự thay đổi
        $chenh_lech = $so_gio_moi - $so_gio_cu;

        // Nếu tăng thêm giờ ($chenh_lech > 0) thì mới check xem quỹ giờ còn đủ không
        if ($chenh_lech > 0 && $chenh_lech > $quy_gio_hien_tai) {
            echo "<script>alert('Lỗi: Số giờ tăng thêm vượt quá quỹ giờ còn lại của đơn!'); window.history.back();</script>";
            return;
        }

        if ($this->model->updateDetail($ma_chi_tiet, $so_gio_moi, $ghi_chu)) {
            // Cập nhật quỹ giờ còn lại (so_gio_du) của đơn tổng
            // Nếu tăng giờ nhân viên ($chenh_lech > 0) -> trừ bớt quỹ giờ đơn tổng (-)
            // Nếu giảm giờ nhân viên ($chenh_lech < 0) -> trả lại quỹ giờ đơn tổng (+)
            $this->model->updateExcessHours($ma_don, abs($chenh_lech), ($chenh_lech > 0 ? '-' : '+'));

            if ($request['trang_thai'] == 'DA_DUYET') {
                // Cập nhật số dư phép tích lũy của nhân viên
                $this->model->updateEmployeeBalance($ma_nv, abs($chenh_lech), ($chenh_lech > 0 ? '+' : '-'));
            }
            header("Location: ?controller=overtime&action=detail&id=$ma_don");
        }
    }
}



}
?>