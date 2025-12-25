<div class="topbar">
            <div>
                <div class="page-title">
                    <i class="fas fa-home"></i> Trang Chủ Quản Lý
                </div>
                <div class="dept-title">Bộ Phận <?php  echo $_SESSION["ten_phong_ban"]; ?></div>
            </div>
            <div class="topbar-actions">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </button>
                <a href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=login&action=sign_out" class="logout-btn" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>