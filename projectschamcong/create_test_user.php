<?php
require_once 'quanlychamcong/Model/Connect.php';

$connect = new Connect();
$conn = $connect->getConnection();

// Tạo tài khoản test
$test_email = 'test@example.com';
$test_password = '123456'; // Password gốc
$hashed_password = password_hash($test_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO nhanvien (ho_ten, email, mat_khau_hash, vai_tro, ma_phong_ban, trang_thai, ngay_tao)
        VALUES ('Nguyễn Văn Test', ?, ?, 'user', 1, 1, NOW())
        ON DUPLICATE KEY UPDATE mat_khau_hash = VALUES(mat_khau_hash)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $test_email, $hashed_password);

if($stmt->execute()){
    echo "✅ Tài khoản test đã được tạo thành công!<br>";
    echo "📧 Email: test@example.com<br>";
    echo "🔒 Password: 123456<br>";
    echo "🔑 Hash: " . $hashed_password . "<br>";
} else {
    echo "❌ Lỗi tạo tài khoản: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
