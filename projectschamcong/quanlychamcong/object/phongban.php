<?php



class Department {
    private $ma_phong_ban;
    private $ten_phong_ban;
    private $mo_ta;
    private $ngay_tao;

public function __construct($row)
{
    $this->ma_phong_ban = $row['ma_phong_ban']??null;
    $this->ten_phong_ban = $row['ten_phong_ban']??null;
    $this->mo_ta = $row['mo_ta']??null;
    $this->ngay_tao = $row['ngay_tao']??null;

    /**
     * Get the value of ma_phong_ban
     */
    
}

public function getMaPhongBan()
    {
        return $this->ma_phong_ban;
    }

    /**
     * Set the value of ma_phong_ban
     */
    public function setMaPhongBan($ma_phong_ban): self
    {
        $this->ma_phong_ban = $ma_phong_ban;

        return $this;
    }

    /**
     * Get the value of ten_phong_ban
     */
    public function getTenPhongBan()
    {
        return $this->ten_phong_ban;
    }

    /**
     * Set the value of ten_phong_ban
     */
    public function setTenPhongBan($ten_phong_ban): self
    {
        $this->ten_phong_ban = $ten_phong_ban;

        return $this;
    }

    /**
     * Get the value of mo_ta
     */
    public function getMoTa()
    {
        return $this->mo_ta;
    }

    /**
     * Set the value of mo_ta
     */
    public function setMoTa($mo_ta): self
    {
        $this->mo_ta = $mo_ta;

        return $this;
    }

    /**
     * Get the value of ngay_tao
     */
    public function getNgayTao()
    {
        return $this->ngay_tao;
    }

    /**
     * Set the value of ngay_tao
     */
    public function setNgayTao($ngay_tao): self
    {
        $this->ngay_tao = $ngay_tao;

        return $this;
    }



}

?>