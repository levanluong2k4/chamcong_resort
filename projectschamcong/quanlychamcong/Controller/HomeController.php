<?php
require_once __DIR__ . '/../Model/emloyee/emloyee.php';
require_once __DIR__ . '/../Model/department/ModelDepartment.php';

class HomeController
{
    public function index()
    {
        // if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == -1){
        //     header('Location: ?controller=login&action=index');
        //     exit();
        // }

        $objModelEmloyee = new ModelEmloyee();
        $objEmployee = $objModelEmloyee->getEmloyeeById($_SESSION['user_id']);



        if (!$objEmployee) {
            $_SESSION['error'] = 'Không tìm thấy nhân viên!';
            header('Location: ?controller=login&action=index');
            exit();
        }

        $objModelDepartment = new ModelDepartment();
        $objDepartment = $objModelDepartment->getdepartmentby_user_id($objEmployee->getMaPhongBan());

        $vai_tro = "nhân viên";
        switch ($objEmployee->getVaiTro()) {
            case 'QUAN_LY':
                $vai_tro = "quản lý";


                break;
            case 'NHAN_SU':
                $vai_tro = "nhân sự";

                break;
            case 'GIAM_DOC':
                $vai_tro = "giám đốc";
                break;
        }
        $objEmployee->setVaiTro($vai_tro);
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
     



        require_once __DIR__ . '/../View/department/index.php';
    }
}
