<?php
class Connect {
    protected $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "quanlychamcong");

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8");
    }

    public function select($sql) {
        return $this->conn->query($sql);
    }

    public function execute($sql) {
        return $this->conn->query($sql);
    }

    public function getConnection() {
        return $this->conn;
    }
}
