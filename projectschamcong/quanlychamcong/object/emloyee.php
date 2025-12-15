<?php

class Employee {
    private $id;
    private $ho_ten;
    private $email;
    private $mat_khau_hash;
    private $vai_tro;
    private $ma_phong_ban;
    private $ma_nguoi_quan_ly;
    private $so_dien_thoai;
    private $anh_dai_dien;
    private $ngay_vao_lam;
    private $trang_thai;
    private $ngay_tao;
    private $remember;


    public function __construct($row)
    {
        $this->id = $row['ma_nhan_vien']??null;
        $this->ho_ten = $row['ho_ten']??null;
        $this->email = $row['email']??null;
        $this->mat_khau_hash = $row['mat_khau_hash']??null;
        $this->vai_tro = $row['vai_tro']??null;
        $this->ma_phong_ban = $row['ma_phong_ban']??null;
        $this->ma_nguoi_quan_ly = $row['ma_nguoi_quan_ly']??null;
        $this->so_dien_thoai = $row['so_dien_thoai']??null;
        $this->anh_dai_dien = $row['anh_dai_dien']??null;
        $this->ngay_vao_lam = $row['ngay_vao_lam']??null;
        $this->trang_thai = $row['trang_thai']??null;
        $this->ngay_tao = $row['ngay_tao']??null;
        
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

    /**
     * Get the value of trang_thai
     */
    public function getTrangThai()
    {
        return $this->trang_thai;
    }

    /**
     * Set the value of trang_thai
     */
    public function setTrangThai($trang_thai): self
    {
        $this->trang_thai = $trang_thai;

        return $this;
    }

    /**
     * Get the value of ngay_vao_lam
     */
    public function getNgayVaoLam()
    {
        return $this->ngay_vao_lam;
    }

    /**
     * Set the value of ngay_vao_lam
     */
    public function setNgayVaoLam($ngay_vao_lam): self
    {
        $this->ngay_vao_lam = $ngay_vao_lam;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of ho_ten
     */
    public function getHoTen()
    {
        return $this->ho_ten;
    }

    /**
     * Set the value of ho_ten
     */
    public function setHoTen($ho_ten): self
    {
        $this->ho_ten = $ho_ten;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of mat_khau_hash
     */
    public function getMatKhauHash()
    {
        return $this->mat_khau_hash;
    }

    /**
     * Set the value of mat_khau_hash
     */
    public function setMatKhauHash($mat_khau_hash): self
    {
        $this->mat_khau_hash = $mat_khau_hash;

        return $this;
    }

    /**
     * Get the value of vai_tro
     */
    public function getVaiTro()
    {
        return $this->vai_tro;
    }

    /**
     * Set the value of vai_tro
     */
    public function setVaiTro($vai_tro): self
    {
        $this->vai_tro = $vai_tro;

        return $this;
    }

    /**
     * Get the value of ma_phong_ban
     */
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
     * Get the value of ma_nguoi_quan_ly
     */
    public function getMaNguoiQuanLy()
    {
        return $this->ma_nguoi_quan_ly;
    }

    /**
     * Set the value of ma_nguoi_quan_ly
     */
    public function setMaNguoiQuanLy($ma_nguoi_quan_ly): self
    {
        $this->ma_nguoi_quan_ly = $ma_nguoi_quan_ly;

        return $this;
    }

    /**
     * Get the value of so_dien_thoai
     */
    public function getSoDienThoai()
    {
        return $this->so_dien_thoai;
    }

    /**
     * Set the value of so_dien_thoai
     */
    public function setSoDienThoai($so_dien_thoai): self
    {
        $this->so_dien_thoai = $so_dien_thoai;

        return $this;
    }

    /**
     * Get the value of anh_dai_dien
     */
    public function getAnhDaiDen()
    {
        return $this->anh_dai_dien;
    }

    /**
     * Set the value of anh_dai_dien
     */
    public function setAnhDaiDen($anh_dai_dien): self
    {
        $this->anh_dai_dien = $anh_dai_dien;

        return $this;
    }

   
  

    /**
     * Get the value of remember
     */
    public function getRemember()
    {
        return $this->remember;
    }

    /**
     * Set the value of remember
     */
    public function setRemember($remember): self
    {
        $this->remember = $remember;

        return $this;
    }
}

?>