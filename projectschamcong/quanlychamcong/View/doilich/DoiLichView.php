<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Lịch & Đổi Ca Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        body {
            min-height: 100vh;
            padding: 20px 0;
            background: #f5f7fa;
        }

        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #5568d3;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .calendar-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: top;
            min-width: 100px;
        }

        /* ✅ Chế độ kéo thả */
        .shift-badge {
            font-size: 11px;
            padding: 8px 10px;
            border-radius: 8px;
            display: block;
            margin: 4px 0;
            cursor: grab;
            transition: all 0.3s;
            position: relative;
            user-select: none;
        }

        .shift-badge:active {
            cursor: grabbing;
        }

        .shift-badge:hover:not(.disabled) {
            transform: scale(1.05);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        /* ✅ Đang kéo */
        .shift-badge.dragging {
            opacity: 0.5;
            transform: scale(0.95);
        }

        /* ✅ Vùng thả hợp lệ */
        .calendar-table td.drag-over {
            background: #e3f2fd !important;
            border: 3px dashed #2196F3 !important;
        }

        /* ✅ Vùng thả không hợp lệ */
        .calendar-table td.drag-invalid {
            background: #ffebee !important;
            border: 3px dashed #f44336 !important;
        }

        .shift-badge.selected {
            border: 3px solid #ff6b6b;
            box-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
        }

        .shift-badge.disabled {
            opacity: 0.5;
            cursor: not-allowed !important;
            background: #e9ecef !important;
            color: #6c757d !important;
        }

        .shift-badge.disabled:hover {
            transform: none !important;
        }

        /* Badge màu theo ca */
        .badge-ca-1, .badge-ca-5 {
            background: #fff3cd;
            color: #856404;
        }

        .badge-ca-2, .badge-ca-6 {
            background: #cfe2ff;
            color: #084298;
        }

        .badge-ca-3 {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-ca-4 {
            background: #d4edda;
            color: #155724;
        }

        .badge-ca-7 {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-ca-8, .badge-ca-9 {
            background: #e2e3e5;
            color: #383d41;
        }

        .badge-ca-10 {
            background: #cce5ff;
            color: #004085;
        }

        .employee-name {
            font-weight: 600;
            color: #667eea;
            position: sticky;
            left: 0;
            background: white;
            z-index: 1;
        }

        /* OFF có thể chọn/kéo */
        .shift-badge.off-selectable {
            cursor: grab;
        }

        .shift-badge.off-selectable:active {
            cursor: grabbing;
        }

        .shift-badge.off-selectable:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
        }

        .shift-badge.off-selectable.selected {
            border: 3px solid #ff6b6b;
            box-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
        }

        /* Tooltip thông tin */
        .shift-badge[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
        }

        /* Chế độ kéo thả badge */
        .mode-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            font-weight: 600;
            z-index: 1000;
        }

        .mode-indicator i {
            margin-right: 8px;
        }
    </style>
</head>

<body>
     <!-- Sidebar -->
     <?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>
    <div class="container-fluid main-container mt-4">
        <div class="page-header">
            <h2><i class="bi bi-arrow-left-right"></i> Đổi Lịch & Đổi Ca Nhân Viên</h2>
            <p class="mb-0">Kéo thả ca hoặc chọn 2 ca cùng thứ để hoán đổi</p>
        </div>

        <div class="p-4">
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tháng</label>
                        <select id="thang_filter" class="form-select">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == date('m') ? 'selected' : '' ?>>Tháng <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Năm</label>
                        <select id="nam_filter" class="form-select">
                            <?php 
                            $currentYear = date('Y');
                            for ($i = $currentYear - 1; $i <= $currentYear + 1; $i++): 
                            ?>
                                <option value="<?= $i ?>" <?= $i == $currentYear ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tuần</label>
                        <select id="tuan_filter" class="form-select">
                            <option value="">-- Chọn tuần --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">&nbsp;</label>
                        <button class="btn btn-primary w-100" onclick="loadLich()">
                            <i class="bi bi-search"></i> Xem Lịch
                        </button>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-success" id="btn-doi-ca" onclick="xacNhanDoiCa()" disabled>
                        <i class="bi bi-arrow-left-right"></i> Đổi Ca (Đã chọn: <span id="so-luong-chon">0</span>)
                    </button>
                    <button class="btn btn-secondary" onclick="huyChon()">
                        <i class="bi bi-x-circle"></i> Hủy Chọn
                    </button>
                    <span class="text-muted ms-3">
                        <i class="bi bi-info-circle"></i> Kéo thả ca để đổi nhanh hoặc click chọn 2 ca
                    </span>
                </div>
            </div>

            <div id="lich-container">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar3 fs-1"></i>
                    <p class="mt-3">Chọn tháng, tuần và nhấn "Xem Lịch"</p>
                </div>
            </div>
        </div>
    </div>


   
</div>
    <input type="hidden" id="ma_phong_ban" value="<?= $ma_phong_ban ?? 1 ?>">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let selectedCells = [];
        let lichData = [];
        let draggedElement = null;
        let draggedData = null;

        $(document).ready(function() {
            loadWeeks();

            $('#thang_filter, #nam_filter').on('change', function() {
                loadWeeks();
            });
        });

        function loadWeeks() {
            const thang = $('#thang_filter').val();
            const nam = $('#nam_filter').val();

            $.post('router.php', {
                controller: 'DoiLich',
                action: 'getWeeksInMonth',
                thang: thang,
                nam: nam
            }, function(res) {
                if (res.success) {
                    let options = '<option value="">-- Chọn tuần --</option>';

                    if (res.data && res.data.length > 0) {
                        const lastIndex = res.data.length - 1;

                        res.data.forEach((week, index) => {
                            const start = new Date(week.ngay_bat_dau).toLocaleDateString('vi-VN');
                            const end = new Date(week.ngay_ket_thuc).toLocaleDateString('vi-VN');
                            const selected = index === lastIndex ? 'selected' : '';

                            options += `
                                <option value="${week.tuan}" ${selected}>
                                    Tuần ${week.tuan} (${start} - ${end})
                                </option>
                            `;
                        });
                    } else {
                        options += '<option value="" disabled>Không có lịch trong tháng này</option>';
                    }

                    $('#tuan_filter').html(options);

                    if (res.data && res.data.length > 0) {
                        loadLich();
                    }
                }
            }, 'json');
        }

        function loadLich() {
            const tuan = $('#tuan_filter').val();
            const thang = $('#thang_filter').val();
            const nam = $('#nam_filter').val();
            const ma_phong_ban = $('#ma_phong_ban').val();

            if (!tuan) {
                Swal.fire('Thông báo', 'Vui lòng chọn tuần', 'warning');
                return;
            }

            $.post('router.php', {
                controller: 'DoiLich',
                action: 'getLichTheoTuan',
                tuan: tuan,
                thang: thang,
                nam: nam,
                ma_phong_ban: ma_phong_ban
            }, function(res) {
                if (res.success) {
                    lichData = res.data;
                    hienThiLich(res.data);
                }
            }, 'json').fail(function(xhr) {
                console.error('Response:', xhr.responseText);
                Swal.fire('Lỗi', 'Không thể tải lịch', 'error');
            });
        }

        function hienThiLich(data) {
            if (data.length === 0) {
                $('#lich-container').html('<div class="alert alert-info">Không có nhân viên trong phòng ban này</div>');
                return;
            }

            const dates = {};
            data.forEach(nv => {
                nv.lich.forEach(l => {
                    if (!dates[l.ngay_lam]) {
                        dates[l.ngay_lam] = {
                            ngay: l.ngay_lam,
                            thu: l.ten_thu
                        };
                    }
                });
            });

            const sortedDates = Object.values(dates).sort((a, b) => new Date(a.ngay) - new Date(b.ngay));
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            let html = '<div class="table-responsive"><table class="calendar-table">';
            html += '<thead><tr><th style="min-width:150px;">Nhân viên</th>';

            sortedDates.forEach(d => {
                const dateObj = new Date(d.ngay);
                html += `<th class="text-center">${d.thu}<br><small>${dateObj.toLocaleDateString('vi-VN')}</small></th>`;
            });
            html += '</tr></thead><tbody>';

            data.forEach(nv => {
                html += `<tr><td class="employee-name">${nv.ho_ten}</td>`;

                const lichMap = {};
                nv.lich.forEach(l => {
                    lichMap[l.ngay_lam] = l;
                });

                sortedDates.forEach(d => {
                    const lich = lichMap[d.ngay];
                    const ngayLam = new Date(d.ngay);
                    ngayLam.setHours(0, 0, 0, 0);
                    const isAfterToday = ngayLam > today;

                    if (!lich) {
                        html += '<td class="text-center text-muted">-</td>';
                        return;
                    }

                    const coNghiDon = lich.co_don_nghi == 1;
                    const coNghiLe = lich.la_ngay_nghi_le == 1;
                    const coNghiTuan = lich.la_ngay_nghi_tuan == 1;

                    // ❌ Không có ca làm việc → Hiển thị "OFF"
                    if (!lich.ma_ca) {
                        let offReason = '';
                        let offColor = '#6c757d';
                        let canSwap = false;

                        if (coNghiDon) {
                            offReason = 'Nghỉ phép (đơn)';
                            offColor = '#ffc107';
                            canSwap = false;
                        } else if (coNghiLe) {
                            offReason = lich.ly_do_nghi_le || 'Nghỉ lễ';
                            offColor = '#dc3545';
                            canSwap = isAfterToday;
                        } else if (coNghiTuan) {
                            offReason = lich.ly_do_nghi_tuan || 'Nghỉ phép tuần';
                            offColor = '#17a2b8';
                            canSwap = isAfterToday;
                        } else {
                            offReason = 'Không có ca';
                            offColor = '#e9ecef';
                            canSwap = isAfterToday; // ✅ Cho phép đổi nếu > ngày hiện tại
                        }

                        const offId = `off-${nv.ma_nhan_vien}-${d.ngay}`;
                        const draggable = canSwap ? 'draggable="true"' : '';
                        const hoverClass = canSwap ? 'off-selectable' : 'disabled';
                        const cursorStyle = canSwap ? 'cursor: grab;' : 'cursor: not-allowed;';

                        html += `<td data-ngay="${d.ngay}" data-thu="${d.thu}" data-ma-nv="${nv.ma_nhan_vien}" class="drop-zone">
                            <div class="shift-badge ${hoverClass}" 
                                 id="${offId}"
                                 ${draggable}
                                 onclick="chonOff('${offId}', ${nv.ma_nhan_vien}, '${d.ngay}', '${d.thu}')"
                                 title="${canSwap ? 'Kéo thả hoặc click để nhận ca' : offReason}"
                                 data-type="OFF"
                                 data-ma-nv="${nv.ma_nhan_vien}"
                                 data-ngay="${d.ngay}"
                                 data-thu="${d.thu}"
                                 style="background: ${offColor} !important; color: #fff !important; ${cursorStyle}">
                                <strong><i class="bi bi-x-circle"></i> OFF</strong><br>
                                <small>${offReason}</small>
                            </div>
                        </td>`;
                        return;
                    }

                    // ✅ Có ca làm việc
                    const isDisabled = lich.da_cham_cong == 1 || !isAfterToday || coNghiDon;
                    const disabledClass = isDisabled ? 'disabled' : '';
                    const draggable = isDisabled ? '' : 'draggable="true"';

                    let badgeInfo = '';
                    let badgeStyle = '';

                    if (lich.da_cham_cong == 1) {
                        badgeInfo = '<small class="text-success"><i class="bi bi-check-circle"></i> Đã chấm công</small>';
                    } else if (!isAfterToday) {
                        badgeInfo = '<small class="text-muted"><i class="bi bi-clock-history"></i> Đã qua ngày</small>';
                    } else if (coNghiDon) {
                        badgeInfo = '<small class="text-warning"><i class="bi bi-calendar-x"></i> Nghỉ phép</small>';
                        badgeStyle = 'border: 2px solid #ffc107;';
                    } else if (coNghiLe) {
                        badgeInfo = `<small class="text-danger"><i class="bi bi-gift"></i> ${lich.ly_do_nghi_le || 'Nghỉ lễ'}</small>`;
                    } else if (coNghiTuan) {
                        badgeInfo = `<small class="text-info"><i class="bi bi-calendar-week"></i> ${lich.ly_do_nghi_tuan || 'Nghỉ phép tuần'}</small>`;
                    }

                    const titleText = isDisabled ? 'Không thể đổi ca' : 'Kéo thả hoặc click để đổi ca';

                    html += `<td data-ngay="${d.ngay}" data-thu="${d.thu}" data-ma-nv="${nv.ma_nhan_vien}" class="drop-zone">
                        <div class="shift-badge badge-ca-${lich.ma_ca} ${disabledClass}" 
                             id="cell-${lich.ma_lich}"
                             ${draggable}
                             onclick="chonCa(${lich.ma_lich}, ${nv.ma_nhan_vien}, '${d.ngay}', '${d.thu}')"
                             title="${titleText}"
                             data-type="CA"
                             data-ma-lich="${lich.ma_lich}"
                             data-ma-nv="${nv.ma_nhan_vien}"
                             data-ngay="${d.ngay}"
                             data-thu="${d.thu}"
                             style="${badgeStyle}">
                            <strong>${lich.ten_ca}</strong><br>
                            <small>${lich.gio_bat_dau} - ${lich.gio_ket_thuc}</small>
                            ${badgeInfo ? '<br>' + badgeInfo : ''}
                        </div>
                    </td>`;
                });
                html += '</tr>';
            });

            html += '</tbody></table></div>';
            $('#lich-container').html(html);

            // ✅ Gắn sự kiện kéo thả
            initDragAndDrop();
        }

        // ✅ Khởi tạo kéo thả
        function initDragAndDrop() {
            const badges = document.querySelectorAll('.shift-badge:not(.disabled)');

            badges.forEach(badge => {
                badge.addEventListener('dragstart', handleDragStart);
                badge.addEventListener('dragend', handleDragEnd);
            });

            const dropZones = document.querySelectorAll('.drop-zone');
            dropZones.forEach(zone => {
                zone.addEventListener('dragover', handleDragOver);
                zone.addEventListener('dragleave', handleDragLeave);
                zone.addEventListener('drop', handleDrop);
            });
        }

        function handleDragStart(e) {
            draggedElement = e.target;
            draggedData = {
                type: e.target.getAttribute('data-type'),
                ma_lich: e.target.getAttribute('data-ma-lich'),
                ma_nhan_vien: parseInt(e.target.getAttribute('data-ma-nv')),
                ngay: e.target.getAttribute('data-ngay'),
                thu: e.target.getAttribute('data-thu'),
                id: e.target.id
            };

            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        }

        function handleDragEnd(e) {
            e.target.classList.remove('dragging');
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.classList.remove('drag-over', 'drag-invalid');
            });
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';

            const targetBadge = e.currentTarget.querySelector('.shift-badge');
            
            if (!targetBadge || targetBadge === draggedElement) {
                return;
            }

            // Kiểm tra có thể thả không
            const targetType = targetBadge.getAttribute('data-type');
            const targetThu = e.currentTarget.getAttribute('data-thu');
            const targetMaNv = parseInt(e.currentTarget.getAttribute('data-ma-nv'));
            const targetDisabled = targetBadge.classList.contains('disabled');

            const canDrop = !targetDisabled && 
                            
                           draggedData.ma_nhan_vien !== targetMaNv;

            if (canDrop) {
                e.currentTarget.classList.add('drag-over');
                e.currentTarget.classList.remove('drag-invalid');
            } else {
                e.currentTarget.classList.add('drag-invalid');
                e.currentTarget.classList.remove('drag-over');
            }
        }

        function handleDragLeave(e) {
            e.currentTarget.classList.remove('drag-over', 'drag-invalid');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over', 'drag-invalid');

            const targetBadge = e.currentTarget.querySelector('.shift-badge');
            
            if (!targetBadge || targetBadge === draggedElement) {
                return;
            }

            const targetData = {
                type: targetBadge.getAttribute('data-type'),
                ma_lich: targetBadge.getAttribute('data-ma-lich'),
                ma_nhan_vien: parseInt(targetBadge.getAttribute('data-ma-nv')),
                ngay: targetBadge.getAttribute('data-ngay'),
                thu: targetBadge.getAttribute('data-thu'),
                id: targetBadge.id
            };

            // Kiểm tra điều kiện
            const targetDisabled = targetBadge.classList.contains('disabled');
            
            if (targetDisabled) {
                Swal.fire('Không thể đổi', 'Ô đích đã bị khóa', 'error');
                return;
            }

          

            if (draggedData.ma_nhan_vien === targetData.ma_nhan_vien) {
                Swal.fire('Không thể đổi', 'Không thể đổi ca của cùng 1 nhân viên', 'error');
                return;
            }

            // Thực hiện đổi ca
            xuLyDoiCaDragDrop(draggedData, targetData);
        }

        function xuLyDoiCaDragDrop(ca1, ca2) {
            let messageHtml = '';
            if (ca1.type === 'CA' && ca2.type === 'CA') {
                messageHtml = `Đổi ca giữa 2 nhân viên vào <strong>${ca1.thu}</strong>?`;
            } else if (ca1.type === 'OFF' && ca2.type === 'CA') {
                messageHtml = `Chuyển ca từ nhân viên có ca sang nhân viên OFF vào <strong>${ca1.thu}</strong>?`;
            } else if (ca1.type === 'CA' && ca2.type === 'OFF') {
                messageHtml = `Chuyển ca từ nhân viên có ca sang nhân viên OFF vào <strong>${ca1.thu}</strong>?`;
            } else {
                Swal.fire('Lỗi', 'Không thể đổi giữa 2 ô OFF', 'error');
                return;
            }

            Swal.fire({
                title: 'Xác nhận đổi ca',
                html: messageHtml,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    xuLyDoiCaAPI(ca1, ca2);
                }
            });
        }

        function xuLyDoiCaAPI(ca1, ca2) {
            Swal.fire({
                title: 'Đang xử lý...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post('router.php', {
                controller: 'DoiLich',
                action: 'doiCa',
                ca1: JSON.stringify(ca1),
                ca2: JSON.stringify(ca2)
            }, function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: res.message,
                        timer: 2000
                    }).then(() => {
                        huyChon();
                        loadLich();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: res.message
                    });
                }
            }, 'json').fail(function(xhr) {
                console.error('Response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể kết nối đến server'
                });
            });
        }

        // ✅ Chọn ca bằng click (giữ nguyên)
        function chonCa(maLich, maNhanVien, ngay, thu) {
            const cell = $(`#cell-${maLich}`);

            if (cell.hasClass('selected')) {
                cell.removeClass('selected');
                selectedCells = selectedCells.filter(c => c.ma_lich != maLich);
            } else {
                if (selectedCells.length >= 2) {
                    Swal.fire('Thông báo', 'Chỉ được chọn tối đa 2 ca để đổi', 'warning');
                    return;
                }

                if (selectedCells.length === 1) {
                   

                    if (selectedCells[0].ma_nhan_vien === maNhanVien) {
                        Swal.fire('Thông báo', 'Không thể đổi ca của cùng 1 nhân viên', 'warning');
                        return;
                    }
                }

                cell.addClass('selected');
                selectedCells.push({
                    id: `cell-${maLich}`,
                    type: 'CA',
                    ma_lich: maLich,
                    ma_nhan_vien: maNhanVien,
                    ngay: ngay,
                    thu: thu
                });
            }

            $('#so-luong-chon').text(selectedCells.length);
            $('#btn-doi-ca').prop('disabled', selectedCells.length !== 2);
        }

        function chonOff(offId, maNhanVien, ngay, thu) {
            const cell = $(`#${offId}`);

            if (cell.hasClass('selected')) {
                cell.removeClass('selected');
                selectedCells = selectedCells.filter(c => c.id != offId);
            } else {
                if (selectedCells.length >= 2) {
                    Swal.fire('Thông báo', 'Chỉ được chọn tối đa 2 ca để đổi', 'warning');
                    return;
                }

                if (selectedCells.length === 1) {
                  

                    if (selectedCells[0].ma_nhan_vien === maNhanVien) {
                        Swal.fire('Thông báo', 'Không thể đổi ca của cùng 1 nhân viên', 'warning');
                        return;
                    }
                }

                cell.addClass('selected');
                selectedCells.push({
                    id: offId,
                    type: 'OFF',
                    ma_lich: null,
                    ma_nhan_vien: maNhanVien,
                    ngay: ngay,
                    thu: thu
                });
            }

            $('#so-luong-chon').text(selectedCells.length);
            $('#btn-doi-ca').prop('disabled', selectedCells.length !== 2);
        }

        function huyChon() {
            selectedCells.forEach(c => {
                if (c.type === 'CA') {
                    $(`#cell-${c.ma_lich}`).removeClass('selected');
                } else if (c.type === 'OFF') {
                    $(`#${c.id}`).removeClass('selected');
                }
            });
            selectedCells = [];
            $('#so-luong-chon').text(0);
            $('#btn-doi-ca').prop('disabled', true);
        }

        function xacNhanDoiCa() {
            if (selectedCells.length !== 2) {
                Swal.fire('Thông báo', 'Vui lòng chọn đúng 2 ca để đổi', 'warning');
                return;
            }

            const ca1 = selectedCells[0];
            const ca2 = selectedCells[1];

            let messageHtml = '';
            if (ca1.type === 'CA' && ca2.type === 'CA') {
                messageHtml = `Đổi ca giữa 2 nhân viên </strong>?`;
            } else if (ca1.type === 'OFF' && ca2.type === 'CA') {
                messageHtml = `Chuyển ca từ nhân viên có ca sang nhân viên OFF vào <strong>${ca1.thu}</strong>?<br>
                      <small class="text-muted">(Nhân viên có ca sẽ nghỉ, nhân viên OFF sẽ nhận ca)</small>`;
            } else if (ca1.type === 'CA' && ca2.type === 'OFF') {
                messageHtml = `Chuyển ca từ nhân viên có ca sang nhân viên OFF vào <strong>${ca1.thu}</strong>?<br>
                      <small class="text-muted">(Nhân viên có ca sẽ nghỉ, nhân viên OFF sẽ nhận ca)</small>`;
            } else {
                Swal.fire('Lỗi', 'Không thể đổi giữa 2 ô OFF', 'error');
                return;
            }

            Swal.fire({
                title: 'Xác nhận đổi ca',
                html: messageHtml,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    xuLyDoiCaAPI(ca1, ca2);
                }
            });
        }
        function xuLyDoiCa() {
            const ca1 = selectedCells[0];
            const ca2 = selectedCells[1];

            Swal.fire({
                title: 'Đang xử lý...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post('router.php', {
                controller: 'DoiLich',
                action: 'doiCa',
                ca1: JSON.stringify(ca1), // ✅ Gửi toàn bộ object
                ca2: JSON.stringify(ca2)
            }, function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: res.message,
                        timer: 2000
                    }).then(() => {
                        huyChon();
                        loadLich();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: res.message
                    });
                }
            }, 'json').fail(function(xhr) {
                console.error('Response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể kết nối đến server'
                });
            });
        }


        function chonOff(offId, maNhanVien, ngay, thu) {
            const cell = $(`#${offId}`);

            // Nếu đã chọn rồi thì bỏ chọn
            if (cell.hasClass('selected')) {
                cell.removeClass('selected');
                selectedCells = selectedCells.filter(c => c.id != offId);
            } else {
                // Kiểm tra đã chọn 2 ca chưa
                if (selectedCells.length >= 2) {
                    Swal.fire('Thông báo', 'Chỉ được chọn tối đa 2 ca để đổi', 'warning');
                    return;
                }

               

                cell.addClass('selected');
                selectedCells.push({
                    id: offId,
                    type: 'OFF', // ✅ Đánh dấu là OFF
                    ma_lich: null,
                    ma_nhan_vien: maNhanVien,
                    ngay: ngay,
                    thu: thu
                });
            }

            // Cập nhật UI
            $('#so-luong-chon').text(selectedCells.length);
            $('#btn-doi-ca').prop('disabled', selectedCells.length !== 2);
        }
    </script>

</body>

</html>