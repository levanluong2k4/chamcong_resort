<?php
require_once(__DIR__ . '/../Connect.php');
require_once __DIR__ . '/../../object/emloyee.php';
require_once __DIR__ . '/../json/ResponseHelper.php';

class ModelSign_in extends Connect {
  
    public function sign_in($email, $password, $remember) {
        try {
            $sql = "SELECT * FROM nhanvien WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                ResponseHelper::error('Lỗi database: ' . $this->conn->error, 'database_error', 500);
            }
            
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result_email = $stmt->get_result();
            
            if($result_email->num_rows > 0) {
                $emloyee = $result_email->fetch_assoc();

                if($password == $emloyee['mat_khau_hash']) {
                   
                    $_SESSION['user_id'] = $emloyee['ma_nhan_vien'];
                    $_SESSION['user_name'] = $emloyee['ho_ten'];
                    $_SESSION['vai_tro'] = $emloyee['vai_tro'];
                    $_SESSION['Anh']= $emloyee['anh_dai_dien'];
                    $_SESSION['ma_phong_ban']= $emloyee['ma_phong_ban'];
                    
                    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
                    
                    if($remember) {
                
                        $token = bin2hex(random_bytes(32));
                        
                        $sql_update = "UPDATE nhanvien SET token = ? WHERE ma_nhan_vien = ?";
                        $stmt_update = $this->conn->prepare($sql_update);
                        $stmt_update->bind_param("si", $token, $emloyee['ma_nhan_vien']);
                        $stmt_update->execute();
                        
                        setcookie('remember_token', $token, time() + 3600 * 24 * 30, '/', '', $isSecure, true);
                        
                    } else {
                      
                        $sql_update = "UPDATE nhanvien SET token = NULL WHERE ma_nhan_vien = ?";
                        $stmt_update = $this->conn->prepare($sql_update);
                        $stmt_update->bind_param("i", $emloyee['ma_nhan_vien']);
                        $stmt_update->execute();
                        
                       
                        setcookie('remember_token', '', time() - 3600, '/', '', $isSecure, true);
                    }
                    
                    ResponseHelper::success([
                        'admin' => ($emloyee['vai_tro'] === 'admin'),
                        'user_name' => $emloyee['ho_ten']
                    ], 'Đăng nhập thành công');
                    
                } else {
                    $_SESSION['old_email'] = $email;
                    ResponseHelper::error('Mật khẩu không đúng.', 'password', 401);
                }
            } else {
                $_SESSION['old_email'] = $email;
                ResponseHelper::error('Email chưa được đăng ký.', 'email_not_found', 404);
            }
            
        } catch (Exception $e) {
            ResponseHelper::error('Lỗi hệ thống: ' . $e->getMessage(), 'system_error', 500);
        }
    }
    
    //  Kiểm tra token - Thêm validation
    public function checkRememberToken($token) {
        // Validate token format (phải là hex 64 ký tự)
        if(empty($token) || !ctype_xdigit($token) || strlen($token) !== 64) {
            return false;
        }
        
        $sql = "SELECT * FROM nhanvien WHERE token = ? AND token IS NOT NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $emloyee = $result->fetch_assoc();
            
           
            $_SESSION['user_id'] = $emloyee['ma_nhan_vien'];
            $_SESSION['user_name'] = $emloyee['ho_ten'];
            $_SESSION['vai_tro'] = $emloyee['vai_tro'];
            $_SESSION['ma_phong_ban']= $emloyee['ma_phong_ban'];
            
          
            
            return true;
        }
        
        return false;
    }
    
 
    public function sign_out() {
       
        
        if(isset($_SESSION['user_id'])) {
           
            
         
         
            $sql = "UPDATE nhanvien SET token = NULL WHERE ma_nhan_vien = ?";
            $stmt = $this->conn->prepare($sql);
            
           
            
            $stmt->bind_param("i", $userId);
            $success = $stmt->execute();
            
         
            
            
            
            return $success;
        } else {
            error_log("ERROR: user_id NOT in session!");
            return false;
        }
    }
}
?>