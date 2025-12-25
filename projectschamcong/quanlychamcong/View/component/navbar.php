<div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo">

                </div>
                <div>
                    <div class="brand-name">Resort Rosa Alba</div>
                    <div class="brand-subtitle">Hệ Thống Quản Lý</div>
                </div>
            </div>
        </div>
        <?php
        $imagePath = $_SESSION["Anh"];
        $baseUrl = '/thuctap/chamcong_resort';
        $fullImagePath = $baseUrl . '/pic2/' . $imagePath;
        $initials = strtoupper(substr($_SESSION['user_name'], 0, 1));
        ?>

        <div class="user-info">
            <div class="user-avatar"
            style="<?php echo !empty($imagePath) ? "background-image: url('{$fullImagePath}');" : ''; ?>"
         data-initials="<?php echo $initials; ?>">
        
        <?php if(empty($imagePath)): ?>
            <span class="avatar-text"><?php echo $initials; ?></span>
        <?php endif; ?>

            </div>
            <div class="user-name"><?php echo $_SESSION['user_name']??"" ?></div>
            <div class="user-role"><?php echo $_SESSION['vai_tro']??"" ?></div>
            <div class="dept-badge">
                <?php echo $_SESSION['icon'];
                echo $_SESSION["ten_phong_ban"];
                ?>
            </div>
        </div>

        <div class="nav-menu">
            <a href="#" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Trang Chủ</span>
            </a>
            <a href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=LichCoDinh&action=index" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Lịch cố định</span>
            </a>
            <a href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=LichTuan&action=index" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Phân Ca Làm Việc</span>
            </a>
            <a href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=DoiLich&action=index" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Đổi ca làm việc</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-file-invoice"></i>
                <span>Đơn Xin Nghỉ</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Nhân Viên Của Tôi</span>
            </a>
            <a href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=ChamCong&action=index" class="nav-item">
                <i class="fas fa-clock"></i>
                <span>Chấm công</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-chart-bar"></i>
                <span>Báo Cáo Bộ Phận</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-user-circle"></i>
                <span>Hồ Sơ Cá Nhân</span>
            </a>
        </div>
    </div>