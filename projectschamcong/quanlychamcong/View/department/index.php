<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Rosa Alba - Department Manager Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/projectschamcong/quanlychamcong/View/css/department.css">
</head>
<style>
  
</style>

<body>
    <!-- Sidebar -->
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

        <div class="user-info">
            <div class="user-avatar"
            style="background-image: url(/pic2/<?php echo $objEmployee->getAnhDaiDen()?>);"
            >
               
            </div>
            <div class="user-name"><?php echo $objEmployee->getHoTen() ?></div>
            <div class="user-role"><?php echo $objEmployee->getVaiTro() ?></div>
            <div class="dept-badge">
                <?php echo $departmentIcons[$objDepartment->getTenPhongBan()]; 
                echo $objDepartment->getTenPhongBan(); 
                ?>
            </div>
        </div>

        <div class="nav-menu">
            <a href="#" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Trang Chủ</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Phân Ca Làm Việc</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-file-invoice"></i>
                <span>Đơn Xin Nghỉ</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Nhân Viên Của Tôi</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-clock"></i>
                <span>Điều Chỉnh Giờ</span>
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

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <div class="page-title">
                    <i class="fas fa-home"></i> Trang Chủ Quản Lý
                </div>
                <div class="dept-title">Bộ Phận Dịch Vụ Ăn Uống</div>
            </div>
            <div class="topbar-actions">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </button>
                <a href="./" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">28</div>
                            <div class="stat-label">Đang Làm Việc</div>
                        </div>
                        <div class="stat-icon working">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">2</div>
                            <div class="stat-label">Đi Muộn Hôm Nay</div>
                        </div>
                        <div class="stat-icon late">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">3</div>
                            <div class="stat-label">Đang Nghỉ Phép</div>
                        </div>
                        <div class="stat-icon leave">
                            <i class="fas fa-umbrella-beach"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">4</div>
                            <div class="stat-label">Chờ Phê Duyệt</div>
                        </div>
                        <div class="stat-icon pending">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">35</div>
                            <div class="stat-label">Tổng Nhân Viên</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shift Scheduling Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-calendar-week"></i>
                        Lịch Phân Ca - Bộ Phận F&B
                    </div>
                    <div class="view-toggle">
                        <button class="view-btn active">Tuần</button>
                        <button class="view-btn">Tháng</button>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="calendar-controls">
                        <div class="calendar-nav">
                            <button class="btn-calendar"><i class="fas fa-chevron-left"></i></button>
                            <span class="calendar-month">09-15 Tháng 12, 2025</span>
                            <button class="btn-calendar"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn-calendar">Hôm Nay</button>
                            <button class="btn-calendar btn-primary">
                                <i class="fas fa-plus"></i> Phân Ca
                            </button>
                            <button class="btn-calendar btn-primary">
                                <i class="fas fa-magic"></i> Tự Động
                            </button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header">T2 9</div>
                        <div class="calendar-day-header">T3 10</div>
                        <div class="calendar-day-header">T4 11</div>
                        <div class="calendar-day-header">T5 12</div>
                        <div class="calendar-day-header">T6 13</div>
                        <div class="calendar-day-header">T7 14</div>
                        <div class="calendar-day-header">CN 15</div>

                        <div class="calendar-day">
                            <div class="day-number">9</div>
                            <div class="shift-slots">
                                <div class="shift-slot shift-morning">
                                    <i class="fas fa-sun"></i> Sáng
                                    <span class="staff-count">8</span>
                                </div>
                                <div class="shift-slot shift-afternoon">
                                    <i class="fas fa-cloud-sun"></i> Chiều
                                    <span class="staff-count">7</span>
                                </div>
                                <div class="shift-slot shift-night">
                                    <i class="fas fa-moon"></i> Tối
                                    <span class="staff-count">5</span>
                                </div>
                            </div>
                        </div>

                        <div class="calendar-day">
                            <div class="day-number">10</div>
                            <div class="shift-slots">
                                <div class="shift-slot shift-morning">
                                    <i class="fas fa-sun"></i> Sáng
                                    <span class="staff-count">9</span>
                                </div>
                                <div class="shift-slot shift-afternoon">
                                    <i class="fas fa-cloud-sun"></i> Chiều
                                    <span class="staff-count">8</span>
                                </div>
                                <div class="shift-slot shift-night">
                                    <i class="fas fa-moon"></i> Tối
                                    <span class="staff-count">6</span>
                                </div>
                            </div>
                        </div>

                        <div class="calendar-day">
                            <div class="day-number">11</div>
                            <div class="shift-slots">
                                <div class="shift-slot shift-morning">
                                    <i class="fas fa-sun"></i> Sáng
                                    <span class="staff-count">8</span>
                                </div>
                                <div class="shift-slot shift-split">
                                    <i class="fas fa-exchange-alt"></i> Gãy
                                    <span class="staff-count">4</span>
                                </div>
                                <div class="shift-slot shift-night">
                                    <i class="fas fa-moon"></i> Tối
                                    <span class="staff-count">5</span>
                                </div>
                            </div>
                        </div>

                        <div class="calendar-day today">
                            <div class="day-number">12</div>
                            <div class="shift-slots">
                                <div class="shift-slot shift-morning">
                                    <i class="fas fa-sun"></i> Sáng
                                    <span class="staff-count">10</span>
                                </div>
                                <div class="shift-slot shift-afternoon">
                                    <i class="fas fa-cloud-sun"></i> Chiều
                                    <span class="staff-count">8</span>
                                </div>
                                <div class="shift-slot shift-night">
                                    <i class="fas fa-moon"></i> Tối
                                    <span class="staff-count">6</span>
                                </div>
                            </div>
                        </div>

                        <div class="calendar-day">
                            <div class="day-number">13</div>
                            <div class="shift-slots">
                                <div class="shift-slot shift-morning">
                                    <i class="fas fa-sun"></i> Sáng
                                    <span class="staff-count">9</span>
                                </div>
                                <div class="shift-slot shift-afternoon">
                                    <i class="fas fa-cloud-sun"></i> Chiều
                                    <span class="staff-count">7</span>
                                </div>
                                <div class="shift-slot shift-night">
                                    <i class="fas fa-moon"></i> Tối
                                    <span class="staff-count">5</span>
                                </div>
                            </div>
                        </div>

                        <div class="calendar-day">
                            <div class="day-number">14</div>
                            <div class="shift-slots">
                                <div class="shift-slot shift-morning">
                                    <i class="fas fa-sun"></i> Sáng
                                    <span class="staff-count">8</span>
                                </div>
                                <div class="shift-slot shift-afternoon">
                                    <i class="fas fa-cloud-sun"></i> Chiều
                                    <span class="staff-count">8</span>
                                </div>
                                <div class="shift-slot shift-night">
                                    <i class="fas fa-moon"></i> Tối
                                    <span class="staff-count">6</span>
                                </div>
                            </div>
                        </div>

                        <div class="calendar-day">
                            <div class="day-number">15</div>
                            <div class="shift-slots">
                                <div class="shift-slot shift-morning">
                                    <i class="fas fa-sun"></i> Sáng
                                    <span class="staff-count">7</span>
                                </div>
                                <div class="shift-slot shift-afternoon">
                                    <i class="fas fa-cloud-sun"></i> Chiều
                                    <span class="staff-count">6</span>
                                </div>
                                <div class="shift-slot shift-night">
                                    <i class="fas fa-moon"></i> Tối
                                    <span class="staff-count">5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Requests Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-clipboard-list"></i>
                        Đơn Xin Nghỉ Chờ Phê Duyệt
                    </div>
                    <button class="btn-view">Xem Tất Cả</button>
                </div>
                <div class="widget-body">
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>Nhân Viên</th>
                                <th>Loại Nghỉ</th>
                                <th>Thời Gian</th>
                                <th>Số Ngày</th>
                                <th>Lý Do</th>
                                <th>Trạng Thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-avatar">TH</div>
                                        <div class="employee-details">
                                            <span class="employee-name">Trần Hoàng</span>
                                            <span class="employee-position">Phục Vụ</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="leave-type-badge leave-annual">Nghỉ Phép</span></td>
                                <td>18-20/12/2025</td>
                                <td>3 ngày</td>
                                <td>Du lịch gia đình</td>
                                <td><span class="status-badge status-pending">Chờ Duyệt</span></td>
                                <td>
                                    <button class="btn-action btn-view" onclick="viewLeaveDetails(1)">
                                        <i class="fas fa-eye"></i> Xem
                                    </button>
                                    <button class="btn-action btn-approve" onclick="approveLeave(1)">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                    <button class="btn-action btn-reject" onclick="rejectLeave(1)">
                                        <i class="fas fa-times"></i> Từ Chối
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-avatar">NM</div>
                                        <div class="employee-details">
                                            <span class="employee-name">Nguyễn Mai</span>
                                            <span class="employee-position">Đầu Bếp</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="leave-type-badge leave-sick">Nghỉ Ốm</span></td>
                                <td>13/12/2025</td>
                                <td>1 ngày</td>
                                <td>Khám bệnh</td>
                                <td><span class="status-badge status-pending">Chờ Duyệt</span></td>
                                <td>
                                    <button class="btn-action btn-view" onclick="viewLeaveDetails(2)">
                                        <i class="fas fa-eye"></i> Xem
                                    </button>
                                    <button class="btn-action btn-approve" onclick="approveLeave(2)">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                    <button class="btn-action btn-reject" onclick="rejectLeave(2)">
                                        <i class="fas fa-times"></i> Từ Chối
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-avatar">PT</div>
                                        <div class="employee-details">
                                            <span class="employee-name">Phạm Tuấn</span>
                                            <span class="employee-position">Pha Chế</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="leave-type-badge leave-emergency">Khẩn Cấp</span></td>
                                <td>14-15/12/2025</td>
                                <td>2 ngày</td>
                                <td>Việc gia đình</td>
                                <td><span class="status-badge status-pending">Chờ Duyệt</span></td>
                                <td>
                                    <button class="btn-action btn-view" onclick="viewLeaveDetails(3)">
                                        <i class="fas fa-eye"></i> Xem
                                    </button>
                                    <button class="btn-action btn-approve" onclick="approveLeave(3)">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                    <button class="btn-action btn-reject" onclick="rejectLeave(3)">
                                        <i class="fas fa-times"></i> Từ Chối
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-avatar">LH</div>
                                        <div class="employee-details">
                                            <span class="employee-name">Lê Hương</span>
                                            <span class="employee-position">Phục Vụ</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="leave-type-badge leave-annual">Nghỉ Phép</span></td>
                                <td>22-24/12/2025</td>
                                <td>3 ngày</td>
                                <td>Việc cá nhân</td>
                                <td><span class="status-badge status-pending">Chờ Duyệt</span></td>
                                <td>
                                    <button class="btn-action btn-view" onclick="viewLeaveDetails(4)">
                                        <i class="fas fa-eye"></i> Xem
                                    </button>
                                    <button class="btn-action btn-approve" onclick="approveLeave(4)">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                    <button class="btn-action btn-reject" onclick="rejectLeave(4)">
                                        <i class="fas fa-times"></i> Từ Chối
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- My Team Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-users"></i>
                        Đội Ngũ Của Tôi - Bộ Phận F&B (35 Thành Viên)
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <select class="btn-calendar" style="padding: 8px 12px;">
                            <option>Tất Cả Vị Trí</option>
                            <option>Đầu Bếp</option>
                            <option>Phục Vụ</option>
                            <option>Pha Chế</option>
                        </select>
                        <select class="btn-calendar" style="padding: 8px 12px;">
                            <option>Tất Cả Trạng Thái</option>
                            <option>Đang Làm Việc</option>
                            <option>Nghỉ Phép</option>
                            <option>Ngoài Ca</option>
                        </select>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="team-grid">
                        <div class="team-card">
                            <div class="team-status status-online"></div>
                            <div class="team-member-header">
                                <div class="team-avatar">TH</div>
                                <div class="team-info">
                                    <h4>Trần Hoàng</h4>
                                    <div class="team-position">Phục Vụ Cao Cấp</div>
                                </div>
                            </div>
                            <div class="team-stats">
                                <div class="team-stat">
                                    <div class="team-stat-value">96%</div>
                                    <div class="team-stat-label">Chuyên Cần</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">18h</div>
                                    <div class="team-stat-label">Tuần Này</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">5</div>
                                    <div class="team-stat-label">Ngày Phép</div>
                                </div>
                            </div>
                            <div class="team-actions">
                                <button><i class="fas fa-calendar"></i> Phân Ca</button>
                                <button><i class="fas fa-clock"></i> Giờ Công</button>
                                <button><i class="fas fa-user"></i> Hồ Sơ</button>
                            </div>
                        </div>

                        <div class="team-card">
                            <div class="team-status status-online"></div>
                            <div class="team-member-header">
                                <div class="team-avatar">NM</div>
                                <div class="team-info">
                                    <h4>Nguyễn Mai</h4>
                                    <div class="team-position">Trưởng Bếp</div>
                                </div>
                            </div>
                            <div class="team-stats">
                                <div class="team-stat">
                                    <div class="team-stat-value">98%</div>
                                    <div class="team-stat-label">Chuyên Cần</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">22h</div>
                                    <div class="team-stat-label">Tuần Này</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">3</div>
                                    <div class="team-stat-label">Ngày Phép</div>
                                </div>
                            </div>
                            <div class="team-actions">
                                <button><i class="fas fa-calendar"></i> Phân Ca</button>
                                <button><i class="fas fa-clock"></i> Giờ Công</button>
                                <button><i class="fas fa-user"></i> Hồ Sơ</button>
                            </div>
                        </div>

                        <div class="team-card">
                            <div class="team-status status-offline"></div>
                            <div class="team-member-header">
                                <div class="team-avatar">PT</div>
                                <div class="team-info">
                                    <h4>Phạm Tuấn</h4>
                                    <div class="team-position">Pha Chế</div>
                                </div>
                            </div>
                            <div class="team-stats">
                                <div class="team-stat">
                                    <div class="team-stat-value">92%</div>
                                    <div class="team-stat-label">Chuyên Cần</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">16h</div>
                                    <div class="team-stat-label">Tuần Này</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">7</div>
                                    <div class="team-stat-label">Ngày Phép</div>
                                </div>
                            </div>
                            <div class="team-actions">
                                <button><i class="fas fa-calendar"></i> Phân Ca</button>
                                <button><i class="fas fa-clock"></i> Giờ Công</button>
                                <button><i class="fas fa-user"></i> Hồ Sơ</button>
                            </div>
                        </div>

                        <div class="team-card">
                            <div class="team-status status-online"></div>
                            <div class="team-member-header">
                                <div class="team-avatar">LH</div>
                                <div class="team-info">
                                    <h4>Lê Hương</h4>
                                    <div class="team-position">Phục Vụ</div>
                                </div>
                            </div>
                            <div class="team-stats">
                                <div class="team-stat">
                                    <div class="team-stat-value">94%</div>
                                    <div class="team-stat-label">Chuyên Cần</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">20h</div>
                                    <div class="team-stat-label">Tuần Này</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">4</div>
                                    <div class="team-stat-label">Ngày Phép</div>
                                </div>
                            </div>
                            <div class="team-actions">
                                <button><i class="fas fa-calendar"></i> Phân Ca</button>
                                <button><i class="fas fa-clock"></i> Giờ Công</button>
                                <button><i class="fas fa-user"></i> Hồ Sơ</button>
                            </div>
                        </div>

                        <div class="team-card">
                            <div class="team-status status-online"></div>
                            <div class="team-member-header">
                                <div class="team-avatar">VK</div>
                                <div class="team-info">
                                    <h4>Võ Khoa</h4>
                                    <div class="team-position">Phó Bếp</div>
                                </div>
                            </div>
                            <div class="team-stats">
                                <div class="team-stat">
                                    <div class="team-stat-value">97%</div>
                                    <div class="team-stat-label">Chuyên Cần</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">24h</div>
                                    <div class="team-stat-label">Tuần Này</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">2</div>
                                    <div class="team-stat-label">Ngày Phép</div>
                                </div>
                            </div>
                            <div class="team-actions">
                                <button><i class="fas fa-calendar"></i> Phân Ca</button>
                                <button><i class="fas fa-clock"></i> Giờ Công</button>
                                <button><i class="fas fa-user"></i> Hồ Sơ</button>
                            </div>
                        </div>

                        <div class="team-card">
                            <div class="team-status status-offline"></div>
                            <div class="team-member-header">
                                <div class="team-avatar">ĐT</div>
                                <div class="team-info">
                                    <h4>Đặng Tuấn</h4>
                                    <div class="team-position">Phục Vụ</div>
                                </div>
                            </div>
                            <div class="team-stats">
                                <div class="team-stat">
                                    <div class="team-stat-value">89%</div>
                                    <div class="team-stat-label">Chuyên Cần</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">14h</div>
                                    <div class="team-stat-label">Tuần Này</div>
                                </div>
                                <div class="team-stat">
                                    <div class="team-stat-value">8</div>
                                    <div class="team-stat-label">Ngày Phép</div>
                                </div>
                            </div>
                            <div class="team-actions">
                                <button><i class="fas fa-calendar"></i> Phân Ca</button>
                                <button><i class="fas fa-clock"></i> Giờ Công</button>
                                <button><i class="fas fa-user"></i> Hồ Sơ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
    // Navigation active state
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // View toggle
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Leave approval functions
    function viewLeaveDetails(id) {
        console.log('Viewing leave request:', id);
        // AJAX call to load detailed leave information
        alert('Opening detailed leave request view...');
    }

    function approveLeave(id) {
        if (confirm('Approve this leave request?')) {
            console.log('Approving leave request:', id);
            // AJAX call to approve
            alert('Leave request approved successfully!');
            // Refresh data
        }
    }

    function rejectLeave(id) {
        const reason = prompt('Enter rejection reason:');
        if (reason) {
            console.log('Rejecting leave request:', id, 'Reason:', reason);
            // AJAX call to reject with comment
            alert('Leave request rejected with comments.');
            // Refresh data
        }
    }

    // Calendar day click
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', function() {
            console.log('Opening shift details for:', this.querySelector('.day-number').textContent);
            // Open modal to view/edit shifts for this day
        });
    });

    // Auto-refresh dashboard data
    function refreshDashboard() {
        console.log('Refreshing dashboard data...');
        // AJAX calls to update stats, schedule, and team status
    }

    setInterval(refreshDashboard, 60000); // Refresh every 60 seconds
    </script>
</body>

</html>