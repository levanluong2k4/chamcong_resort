<?php

require_once(__DIR__ . '/../Connect.php');
require_once (__DIR__ .'/../../object/emloyee.php');
class ModelEmloyee extends Connect {
    private string $table='nhanvien';




    public function getEmloyeeById($id) {
        $sql="SELECT * FROM $this->table  WHERE ma_nhan_vien=$id";
        $result=$this->select($sql);
        if($result->num_rows>0){
            $row=mysqli_fetch_assoc($result);
            return new Employee($row);
        }
        return null;
    }
}



?>