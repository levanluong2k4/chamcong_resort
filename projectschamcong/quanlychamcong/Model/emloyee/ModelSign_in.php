<?php
require_once(__DIR__ . '/../Connect.php');
require_once __DIR__ . '/../../object/emloyee.php';

class ModelSign_in extends Connect {
  
    public function sign_in( $email, $password ,$remember) {
      
        // $objEmployee=new Employee($data);

        $sql="SELECT * FROM nhanvien WHERE email='".$email."' AND mat_khau_hash='".$password."'";
        $result=$this->select($sql);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            
            $_SESSION['user_id']= $row['ma_nhan_vien'];
            $_SESSION['user_name']= $row['ho_ten'];
            $_SESSION['vai_tro']= $row['vai_tro'];
    
        
            $row=mysqli_fetch_assoc($result);
            if($remember){
                setcookie('email', $email, time() + 3600 * 24 * 30, '/');
                setcookie('password', $password, time() + 3600 * 24 * 30, '/');
            }
            else{
                setcookie('email', '', time() - 3600 * 24 * 30, '/');
                setcookie('password', '', time() - 3600 * 24 * 30, '/');
            }
          

            // $objEmployee=new Employee($row);
            return $result;
        }
        return null;
    }
}
?>