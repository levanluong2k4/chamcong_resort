<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Cá Nhân - Resort Rosa Alba</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/lichtuan.css">

    <style>
        body {
            font-family: 'Inter', sans-serif; 
            background-color: #f5f7fb;
        }

        .widget-card {
            background: #fff;
            border-radius: 16px; 
            border: none;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .widget-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            border-radius: 16px 16px 0 0;
        }

        .widget-title {
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .profile-info-item { 
            padding: 12px 15px; 
            border-radius: 10px;
            margin-bottom: 10px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .profile-info-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .profile-info-icon { 
            width: 36px;
            height: 36px; 
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            color: #fff; 
            border-radius: 10px; 
            display: flex; 
            align-items: center;
            justify-content: center; 
            margin-right: 12px; 
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* Phần số dư phép trong thẻ thông tin */
        .leave-balance-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }

        .leave-balance-title {
            font-weight: 600;
            color: #407a75;
            font-size: 0.95rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .leave-stat-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 8px;
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .leave-stat-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        

        .leave-stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .leave-stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Nút chuyển đổi view */
        .view-toggle-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .view-toggle-btn {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 12px;
            background: #fff;
            color: #6c757d;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .view-toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .view-toggle-btn.active {
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(64, 122, 117, 0.3);
        }

        .view-toggle-btn i {
            font-size: 1.2rem;
        }

        /* Content sections */
        .view-content {
            display: none;
        }

        .view-content.active {
            display: block;
            animation: fadeIn 0.4s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #calendar {
            padding: 20px;
            background: #fafbfc;
            border-radius: 12px;
        }

        .fc .fc-toolbar-title { font-size: 1.4rem; font-weight: 700; color: #0f4c81; text-transform: capitalize; }
        .fc .fc-button-primary { background: linear-gradient(135deg, #407a75 0%, #407a75 100%); border: none; border-radius: 10px; text-transform: capitalize; font-weight: 500; box-shadow: 0 2px 6px rgba(64, 122, 117, 0.2) !important; padding: 8px 18px; transition: all 0.3s ease; }
        .fc .fc-button-primary:hover { background: linear-gradient(135deg, #356660 0%, #356660 100%); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(64, 122, 117, 0.3) !important; }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background: linear-gradient(135deg, #2a524e 0%, #2a524e 100%); box-shadow: 0 2px 4px rgba(64, 122, 117, 0.4) !important; }
        .fc-theme-standard td, .fc-theme-standard th { border-color: #e8edf2; }
        .fc .fc-scrollgrid { border: 1px solid #e8edf2; border-radius: 10px; overflow: hidden; }
        .fc-col-header-cell { background: linear-gradient(180deg, #f8f9fa 0%, #f0f2f5 100%); padding: 12px 0 !important; }
        .fc-col-header-cell-cushion { color: #407a75; font-weight: 700; text-decoration: none; padding: 12px 0 !important; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .fc-daygrid-day { transition: background-color 0.2s ease; }
        .fc-daygrid-day:hover { background-color: #f8f9fa; }
        .fc-daygrid-day-number { color: #2c3e50; font-weight: 600; padding: 10px !important; text-decoration: none; font-size: 0.95rem; }
        .fc .fc-day-today { background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%) !important; }
        .fc .fc-day-today .fc-daygrid-day-number { color: #407a75; font-weight: 700; background: #ffd700; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 4px; }
        .fc-event:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .fc-daygrid-day-frame { min-height: 100px; }
        
        .legend-badge { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 5px; }
        .legend-item { font-size: 0.85rem; color: #666; margin-left: 15px; display: flex; align-items: center; }

        .calendar-widget-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .calendar-legend { display: flex; gap: 15px; flex-wrap: wrap; }
        .calendar-legend .legend-item { margin: 0; color: rgba(255,255,255,0.9); font-size: 0.8rem; }

        .fc-event { background: none !important; border: none !important; box-shadow: none !important; margin-bottom: 6px !important; cursor: pointer; }
        .event-custom-card { padding: 6px 10px; border-radius: 8px; color: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.08); border-left: 4px solid rgba(0,0,0,0.15); transition: all 0.2s ease; display: flex; flex-direction: column; overflow: hidden; }
        .event-custom-card:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 8px 15px rgba(0,0,0,0.15); filter: brightness(1.05); }
        .event-time-row { font-size: 0.75rem; opacity: 0.9; margin-bottom: 2px; display: flex; align-items: center; font-weight: 500; }
        .event-title-row { font-size: 0.85rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .event-time-icon { margin-right: 4px; font-size: 0.7rem; }

        .text-purple { color: #9c27b0 !important; }
        .text-pink { color: #e91e63 !important; }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }

        .stat-card.success { border-color: #28a745; }
        .stat-card.warning { border-color: #ffc107; }
        .stat-card.info { border-color: #17a2b8; }
        .stat-card.danger { border-color: #dc3545; }
        .stat-card.purple { border-color: #9c27b0; }
        .stat-card.pink { border-color: #e91e63; }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .summary-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .summary-card i {
            font-size: 1.5rem;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <?php 
        if (!isset($modelProfile)) {
            require_once __DIR__ . '/../../Model/profile/ModelProfile.php';
            $modelProfile = new ModelProfile();
        }
        $leaves = $modelProfile->getPersonalLeaves($profile['ma_nhan_vien']);
        
        $hienThiVaiTro = '';
        switch($profile['vai_tro']) {
            case 'QUAN_LY': $hienThiVaiTro = 'Quản Lý'; break;
            case 'NHAN_VIEN': $hienThiVaiTro = 'Nhân Viên'; break;
            case 'NHAN_SU': $hienThiVaiTro = 'Nhân Sự'; break;
            case 'ADMIN': $hienThiVaiTro = 'Quản Trị Viên'; break;
            default: $hienThiVaiTro = $profile['vai_tro'];
        }

        $hienThiTrangThai = '';
        $mauTrangThai = '';
        if($profile['trang_thai'] == 'DANG_LAM') {
            $hienThiTrangThai = 'Đang làm việc';
            $mauTrangThai = '#28a745';
        } else if ($profile['trang_thai'] == 'NGHI_VIEC') {
            $hienThiTrangThai = 'Đã nghỉ việc';
            $mauTrangThai = '#dc3545';
        } else {
            $hienThiTrangThai = 'Tạm khóa';
            $mauTrangThai = '#ffc107';
        }

        $ngayVaoLam = isset($profile['ngay_vao_lam']) ? date('d/m/Y', strtotime($profile['ngay_vao_lam'])) : 'Chưa cập nhật';

        $events = [];

        if (!empty($schedules)) {
            foreach ($schedules as $s) {
                $color = '#42a5f5'; 
                if (stripos($s['ten_ca'], 'chiều') !== false) $color = '#ffa726'; 
                if (stripos($s['ten_ca'], 'tối') !== false) $color = '#78909c'; 
                if (stripos($s['ten_ca'], 'đêm') !== false) $color = '#2c3e50';

                $events[] = [
                    'title' => $s['ten_ca'], 
                    'start' => $s['ngay_lam'] . 'T' . $s['gio_bat_dau'],
                    'end'   => $s['ngay_lam'] . 'T' . $s['gio_ket_thuc'],
                    'backgroundColor' => $color,
                    'description' => "Thời gian: " . date('H:i', strtotime($s['gio_bat_dau'])) . " - " . date('H:i', strtotime($s['gio_ket_thuc']))
                ];
            }
        }

        if (!empty($overtimeSchedules)) {
            foreach ($overtimeSchedules as $ot) {
                $otColor = '#e91e63'; 
                $tenCa = isset($ot['ten_ca']) ? $ot['ten_ca'] : 'Chưa xác định';
                $soGio = floatval($ot['so_gio_dang_ky']);

                $events[] = [
                    'title' => "Tăng ca: " . $soGio . " giờ", 
                    'start' => $ot['ngay_tang_ca'], 
                    'backgroundColor' => $otColor,
                    'borderColor' => $otColor,
                    'display' => 'block',
                    'description' => "Ca đăng ký: " . $tenCa
                ];
            }
        }

        if (!empty($leaves)) {
            $mapLoaiNghi = [
                'NGHI_PHEP_THANG' => 'Phép năm',
                'NGHI_OM' => 'Nghỉ ốm',
                'NGHI_VIEC_RIENG' => 'Việc riêng',
                'NGHI_LE' => 'Nghỉ lễ',
                'NGHI_TET' => 'Nghỉ Tết',
                'NGHI_KHONG_LUONG' => 'Không lương'
            ];

            foreach ($leaves as $l) {
                $leaveColor = '#dc3545';
                $tenLoai = isset($mapLoaiNghi[$l['loai_nghi']]) ? $mapLoaiNghi[$l['loai_nghi']] : $l['loai_nghi'];
                $endDate = date('Y-m-d', strtotime($l['ngay_ket_thuc']));
                
                $events[] = [
                    'title' => "Nghỉ: " . $tenLoai,
                    'start' => $l['ngay_bat_dau'],
                    'end'   => $endDate,
                    'backgroundColor' => $leaveColor,
                    'borderColor' => $leaveColor,
                    'allDay' => true,
                    'description' => "Lý do: " . $l['ly_do'] . "\nThời gian: " . date('d/m', strtotime($l['ngay_bat_dau'])) . " đến " . date('d/m', strtotime($l['ngay_ket_thuc']))
                ];
            }
        }

        $jsonEvents = json_encode($events);
    ?>
    
    <?php require_once __DIR__ . '/../component/navbar.php'; ?>

    <div class="main-content">
        <?php require_once __DIR__ . '/../component/topbar.php'; ?>

        <div class="content-area">
            <div class="row">
                <!-- THÔNG TIN CÁ NHÂN -->
                <div class="col-lg-4 mb-4">
                    <div class="widget-card h-100">
                        <div class="widget-header">
                            <div class="widget-title"><i class="fas fa-id-card me-2"></i> Thông Tin Chung</div>
                        </div>
                        <div class="widget-body p-4">
                            <div class="profile-info-item">
                                <div class="profile-info-icon"><i class="fas fa-toggle-on"></i></div>
                                <div>
                                    <div class="text-muted small" style="font-size: 0.75rem">Trạng Thái</div>
                                    <div class="fw-semibold" style="font-size: 0.9rem; color: <?php echo $mauTrangThai; ?>">
                                        <?php echo $hienThiTrangThai; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-info-item">
                                <div class="profile-info-icon"><i class="fas fa-briefcase"></i></div>
                                <div>
                                    <div class="text-muted small" style="font-size: 0.75rem">Chức Vụ</div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem"><?php echo $hienThiVaiTro; ?></div>
                                </div>
                            </div>

                            <div class="profile-info-item">
                                <div class="profile-info-icon"><i class="fas fa-calendar-check"></i></div>
                                <div>
                                    <div class="text-muted small" style="font-size: 0.75rem">Ngày Vào Làm</div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem"><?php echo $ngayVaoLam; ?></div>
                                </div>
                            </div>

                            <div class="profile-info-item">
                                <div class="profile-info-icon"><i class="far fa-envelope"></i></div>
                                <div>
                                    <div class="text-muted small" style="font-size: 0.75rem">Email</div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem; word-break: break-word;"><?php echo $profile['email']; ?></div>
                                </div>
                            </div>

                            <div class="profile-info-item">
                                <div class="profile-info-icon"><i class="fas fa-phone-alt"></i></div>
                                <div>
                                    <div class="text-muted small" style="font-size: 0.75rem">Số Điện Thoại</div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem"><?php echo $profile['so_dien_thoai']; ?></div>
                                </div>
                            </div>

                            <div class="profile-info-item">
                                <div class="profile-info-icon"><i class="far fa-building"></i></div>
                                <div>
                                    <div class="text-muted small" style="font-size: 0.75rem">Phòng Ban</div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem"><?php echo $profile['ten_phong_ban']; ?></div>
                                </div>
                            </div>

                           

                            <!-- SỐ DƯ PHÉP NĂM -->
                            <div class="leave-balance-section">
                                <div class="leave-balance-title">
                                    <i class="fas fa-umbrella-beach me-2"></i>
                                    Số Dư Phép Năm <?php echo date('Y'); ?>
                                </div>
                                
                                <div class="leave-stat-item granted">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="leave-stat-label">
                                            <i class="fas fa-gift me-1"></i>Được hưởng
                                        </div>
                                        <div class="leave-stat-number ">
                                            <?php echo $leaveBalance['so_ngay_phep_duoc_huong']; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="leave-stat-item used">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="leave-stat-label">
                                            <i class="fas fa-minus-circle me-1"></i>Đã dùng
                                        </div>
                                        <div class="leave-stat-number text-warning">
                                            <?php echo $leaveBalance['so_ngay_phep_da_dung']; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="leave-stat-item remaining">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="leave-stat-label">
                                            <i class="fas fa-check-circle me-1"></i>Còn lại
                                        </div>
                                        <div class="leave-stat-number text-primary">
                                            <?php echo $leaveBalance['so_ngay_phep_con_lai']; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="leave-stat-item overtime">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="leave-stat-label">
                                            <i class="fas fa-clock me-1"></i>Tăng ca tích lũy
                                        </div>
                                        <div class="leave-stat-number text-danger">
                                            <?php echo $leaveBalance['so_gio_tang_ca_tich_luy']; ?>h
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="?controller=profile&action=change_password" class="btn w-100 text-white fw-bold" 
                                   style="background: linear-gradient(135deg, #407a75 0%, #407a75 100%); border-radius: 10px; padding: 10px;">
                                    <i class="fas fa-key me-2"></i>Đổi Mật Khẩu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NỘI DUNG CHÍNH VỚI NÚT CHUYỂN ĐỔI -->
                <div class="col-lg-8">
                    <!-- NÚT CHUYỂN ĐỔI VIEW -->
                    <div class="view-toggle-container">
                        <button class="view-toggle-btn active" onclick="switchView('calendar')">
                            <i class="far fa-calendar-alt"></i>
                            <span>Lịch Làm Việc</span>
                        </button>
                        <button class="view-toggle-btn" onclick="switchView('attendance')">
                            <i class="fas fa-user-check"></i>
                            <span>Chấm Công</span>
                        </button>
                    </div>

                    <!-- VIEW LỊCH LÀM VIỆC -->
                    <div id="calendarView" class="view-content active">
                        <div class="widget-card">
                            <div class="calendar-widget-header">
                                <div class="widget-title">
                                    <i class="far fa-calendar-alt me-2"></i> Lịch Làm Việc
                                </div>
                                <div class="calendar-legend">
                                    <div class="legend-item"><span class="legend-badge" style="background: #42a5f5"></span> Sáng</div>
                                    <div class="legend-item"><span class="legend-badge" style="background: #ffa726"></span> Chiều</div>
                                    <div class="legend-item"><span class="legend-badge" style="background: #78909c"></span> Tối</div>
                                    <div class="legend-item"><span class="legend-badge" style="background: #2c3e50"></span> Đêm</div>
                                    <div class="legend-item"><span class="legend-badge" style="background: #e91e63"></span> Tăng ca</div>
                                </div>
                            </div>
                            <div class="widget-body p-3">
                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>

                    <!-- VIEW CHẤM CÔNG -->
                    <div id="attendanceView" class="view-content">
                        <!-- THỐNG KÊ CHẤM CÔNG -->
                  

                        <!-- LỊCH SỬ CHẤM CÔNG -->
                        <div class="widget-card">
                            <div class="widget-header">
                                <div class="widget-title">
                                    <i class="fas fa-history me-2"></i>
                                    Lịch Sử Chấm Công (30 ngày gần nhất)
                                </div>
                            </div>
                            <div class="widget-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead style="background: #f8f9fa;">
                                            <tr>
                                                <th width="100">Ngày</th>
                                                <th width="80">Thứ</th>
                                                <th width="120">Ca làm</th>
                                                <th width="80">Giờ vào</th>
                                                <th width="80">Giờ ra</th>
                                                <th width="80">Giờ làm</th>
                                                <th width="130">Trạng thái</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($attendanceHistory)): ?>
                                                <?php foreach ($attendanceHistory as $att): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo date('d/m/Y', strtotime($att['ngay_lam'])); ?></strong>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                $thu = ['', 'CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                                                                echo $thu[date('w', strtotime($att['ngay_lam']))];
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $att['ten_ca'] ?? '-'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $att['gio_vao'] ? date('H:i', strtotime($att['gio_vao'])) : '-'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $att['gio_ra'] ? date('H:i', strtotime($att['gio_ra'])) : '-'; ?>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo $att['tong_gio_lam'] ? round($att['tong_gio_lam'], 1) . 'h' : '-'; ?></strong>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $statusClass = '';
                                                                $statusText = '';
                                                                $statusIcon = '';
                                                                
                                                                switch($att['trang_thai']) {
                                                                    case 'DI_LAM':
                                                                        $statusClass = 'success';
                                                                        $statusText = 'Đi làm';
                                                                        $statusIcon = 'check-circle';
                                                                        break;
                                                                    case 'DI_TRE':
                                                                        $statusClass = 'warning';
                                                                        $statusText = 'Đi trễ';
                                                                        $statusIcon = 'clock';
                                                                        break;
                                                                    case 'VE_SOM':
                                                                        $statusClass = 'info';
                                                                        $statusText = 'Về sớm';
                                                                        $statusIcon = 'running';
                                                                        break;
                                                                    case 'VANG_MAT':
                                                                        $statusClass = 'danger';
                                                                        $statusText = 'Vắng mặt';
                                                                        $statusIcon = 'times-circle';
                                                                        break;
                                                                    case 'NGHI_PHEP':
                                                                    case 'NGHI_PHEP_DON':
                                                                    case 'NGHI_PHEP_TUAN':
                                                                        $statusClass = 'secondary';
                                                                        $statusText = 'Nghỉ phép';
                                                                        $statusIcon = 'bed';
                                                                        break;
                                                                    case 'DI_LAM_NGAY_LE':
                                                                        $statusClass = 'primary';
                                                                        $statusText = 'Làm ngày lễ';
                                                                        $statusIcon = 'star';
                                                                        break;
                                                                    default:
                                                                        $statusClass = 'secondary';
                                                                        $statusText = $att['trang_thai'];
                                                                        $statusIcon = 'question-circle';
                                                                }
                                                            ?>
                                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                                <i class="fas fa-<?php echo $statusIcon; ?> me-1"></i>
                                                                <?php echo $statusText; ?>
                                                            </span>
                                                        </td>
                                                      
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center py-5">
                                                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                                        <p class="text-muted mt-2 mb-0">Chưa có dữ liệu chấm công</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Chuyển đổi view
        function switchView(viewName) {
            // Ẩn tất cả views
            document.querySelectorAll('.view-content').forEach(view => {
                view.classList.remove('active');
            });
            
            // Bỏ active trên tất cả buttons
            document.querySelectorAll('.view-toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Hiện view được chọn
            if (viewName === 'calendar') {
                document.getElementById('calendarView').classList.add('active');
                document.querySelectorAll('.view-toggle-btn')[0].classList.add('active');
            } else if (viewName === 'attendance') {
                document.getElementById('attendanceView').classList.add('active');
                document.querySelectorAll('.view-toggle-btn')[1].classList.add('active');
            }
        }

        // FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var myEvents = <?php echo $jsonEvents; ?>;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                height: 'auto',
                contentHeight: 650,
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'prev,today,next'
                },
                buttonText: {
                    today: 'Hôm nay',
                    month: 'Tháng',
                    week: 'Tuần',
                    list: 'DS'
                },
                events: myEvents,
                dayMaxEvents: 3, 
                
                eventContent: function(arg) {
                    let timeText = arg.timeText; 
                    let title = arg.event.title; 
                    let color = arg.event.backgroundColor; 
                    
                    let customHtml = document.createElement('div');
                    customHtml.className = 'event-custom-card';
                    customHtml.style.backgroundColor = color;
                    
                    let timeIcon = timeText ? '<i class="far fa-clock event-time-icon"></i>' : '';
                    
                    customHtml.innerHTML = `
                        <div class="event-time-row">${timeIcon} ${timeText}</div>
                        <div class="event-title-row">${title}</div>
                    `;
                    
                    return { domNodes: [customHtml] };
                },

                eventClick: function(info) {
                    var eventObj = info.event;
                    var moTa = eventObj.extendedProps.description || "Không có thông tin chi tiết";
                    
                    alert(eventObj.title + '\n' + moTa);
                    info.jsEvent.preventDefault();
                },
                
                eventDidMount: function(info) {
                    if(info.event.extendedProps.description) {
                        info.el.setAttribute('title', info.event.extendedProps.description);
                    }
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>