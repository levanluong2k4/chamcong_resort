
<?php
require_once(__DIR__ . '/../Connect.php');
require_once(__DIR__ . '/../../object/phongban.php');
class ModelDepartment extends Connect{

    private string $table = 'phongban';

    public function getdepartmentby_user_id($id){
        $sql="select * from $this->table where ma_phong_ban=$id ";
        $result=$this->select($sql);
        if($result->num_rows>0){
            $row=mysqli_fetch_assoc($result);
            return new Department($row);
        }

        return null;
    }







}



?>

