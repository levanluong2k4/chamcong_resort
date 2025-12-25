<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Lịch Cố Định Theo Tuần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">



    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .day-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .day-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .shift-section {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            position: relative;
        }

        .shift-section:last-child {
            border-bottom: none;
        }

        .shift-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-remove-shift {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 10;
        }

        .btn-remove-shift:hover {
            background: #c82333;
        }

        .btn-add-shift {
            background: white;
            color: #667eea;
            border: 2px dashed #667eea;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 10px 20px;
            width: calc(100% - 40px);
            justify-content: center;
        }

        .btn-add-shift:hover {
            background: #f0f0ff;
            border-color: #764ba2;
        }

        .dual-listbox {
            display: flex;
            gap: 15px;
            align-items: stretch;
        }

        .listbox {
            flex: 1;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            min-height: 250px;
            background: #f8f9fa;
        }

        .listbox-title {
            font-size: 14px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .listbox-actions {
            display: flex;
            gap: 5px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .btn-bulk-action {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            white-space: nowrap;
            flex: 1;
            justify-content: center;
            min-width: 100px;
        }

        .btn-select-all {
            background: #6c757d;
            color: white;
        }

        .btn-select-all:hover {
            background: #5a6268;
        }

        .btn-move-selected {
            background: #0d6efd;
            color: white;
        }

        .btn-move-selected:hover {
            background: #0b5ed7;
        }

        .btn-remove-selected {
            background: #dc3545;
            color: white;
        }

        .btn-remove-selected:hover {
            background: #c82333;
        }

        .btn-move-all {
            background: #198754;
            color: white;
        }

        .btn-move-all:hover {
            background: #157347;
        }

        .btn-remove-all {
            background: #fd7e14;
            color: white;
        }

        .btn-remove-all:hover {
            background: #e8590c;
        }

        .employee-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 8px;
            cursor: move;
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .employee-item.selected {
            background: #e7f3ff;
            border-color: #0d6efd;
        }

        .employee-checkbox {
            margin-right: 8px;
            cursor: pointer;
            width: 16px;
            height: 16px;
        }

        .employee-item:hover {
            background: #e7f3ff;
            border-color: #0d6efd;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
        }

        .employee-item.dragging {
            opacity: 0.5;
        }

        .listbox.drag-over {
            background: #e7f3ff;
            border-color: #0d6efd;
        }

        .btn-remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-remove:hover {
            background: #c82333;
        }

        .badge-count {
            background: #0d6efd;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 13px;
        }

        .btn-save-all {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);
        }

        .alert-fixed {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            min-width: 300px;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.show {
            display: flex;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .ca-option {
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .ca-option:hover {
            background: #e7f3ff;
            border-color: #0d6efd;
        }

        .employee-list-container {
            max-height: 300px;
            overflow-y: auto;
        }

        /* STYLE CHO NHÂN VIÊN ĐÃ NGHỈ */
        .employee-item.disabled {
            cursor: not-allowed !important;
            opacity: 0.6;
            background: #f8d7da !important;
            border-color: #f5c2c7 !important;
        }

        .employee-item.disabled:hover {
            transform: none !important;
            box-shadow: none !important;
            background: #f8d7da !important;
        }

        .employee-item.disabled .employee-checkbox {
            cursor: not-allowed;
        }
    </style>
</head>

<body>


    <?php require_once __DIR__ . '/../component/navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <?php require_once __DIR__ . '/../component/topbar.php'; ?>
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner-border text-light" style="width: 3rem; height: 3rem;"></div>
        </div>

        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0 text-light"><i class="fas fa-calendar-week me-2"></i>Quản Lý Lịch Cố Định Theo Tuần</h2>
                        <select class="form-select w-auto" id="phongBanSelect">
                            <option value="1">Lễ Tân</option>
                            <option value="2">Buồng Phòng</option>
                            <option value="3" selected>Nhà Hàng</option>
                            <option value="4">Bar & Lounge</option>
                            <option value="5">Spa & Massage</option>
                            <option value="6">Bể Bơi</option>
                            <option value="7">Bảo Vệ</option>
                            <option value="8">Kỹ Thuật</option>
                            <option value="9">Marketing</option>
                            <option value="10">Hành Chính - Nhân Sự</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="scheduleContainer"></div>
        </div>

        <button class="btn btn-primary btn-save-all" onclick="luuToanBoLich()">
            <i class="fas fa-save me-2"></i>Lưu Toàn Bộ Lịch
        </button>

        <!-- Modal chọn ca -->
        <div class="modal" id="modalChonCa">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="fas fa-plus-circle me-2"></i>Chọn Ca Muốn Thêm
                </div>
                <div id="danhSachCaModal"></div>
                <div style="margin-top: 20px; text-align: right;">
                    <button class="btn btn-secondary" onclick="dongModal()">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Biến toàn cục
        let danhSachNhanVien = [];
        let danhSachCa = [];
        let lichCoDinh = {};
        let currentPhongBan = 3;
        let caHienThi = {};
        let thuDangChon = null;
        let draggedElement = null;

        const CA_MAC_DINH = [1, 2];

        const thuTrongTuan = [{
                id: 2,
                ten: 'Thứ 2'
            },
            {
                id: 3,
                ten: 'Thứ 3'
            },
            {
                id: 4,
                ten: 'Thứ 4'
            },
            {
                id: 5,
                ten: 'Thứ 5'
            },
            {
                id: 6,
                ten: 'Thứ 6'
            },
            {
                id: 7,
                ten: 'Thứ 7'
            },
            {
                id: 8,
                ten: 'Chủ nhật'
            }
        ];

        /**
         * Kiểm tra nhân viên có nghỉ vào thứ này không
         */
        function kiemTraNhanVienNghiThu(nhanVien, thu) {
            // Kiểm tra nghỉ phép tuần (theo THU)
            if (nhanVien.thu_nghi && nhanVien.thu_nghi.length > 0) {
                if (nhanVien.thu_nghi.includes(thu.toString())) {
                    return {
                        nghi: true,
                        loai: 'NGHI_PHEP_TUAN',
                        ly_do: 'Nghỉ phép tuần'
                    };
                }
            }

            // Kiểm tra nghỉ lễ/tết (theo khoảng ngày)
            if (nhanVien.khoang_ngay_nghi_arr && nhanVien.khoang_ngay_nghi_arr.length > 0) {
                for (let khoang of nhanVien.khoang_ngay_nghi_arr) {
                    // Tính thứ của các ngày trong khoảng
                    const tuNgay = new Date(khoang.tu_ngay);
                    const denNgay = new Date(khoang.den_ngay);

                    let ngayHienTai = new Date(tuNgay);
                    while (ngayHienTai <= denNgay) {
                        // DAYOFWEEK: 0=CN, 1=T2, 2=T3,..., 6=T7
                        // Chuyển sang: 2=T2, 3=T3,..., 7=T7, 8=CN
                        let thuJS = ngayHienTai.getDay();
                        let thuHeThong = thuJS === 0 ? 8 : thuJS + 1;

                        if (thuHeThong === thu) {
                            return {
                                nghi: true,
                                loai: 'NGHI_LE',
                                ly_do: `Nghỉ lễ (${khoang.tu_ngay} - ${khoang.den_ngay})`
                            };
                        }

                        ngayHienTai.setDate(ngayHienTai.getDate() + 1);
                    }
                }
            }

            return {
                nghi: false
            };
        }

        async function loadData() {
            showLoading(true);

            try {
                const nvResponse = await fetch('router.php?controller=LichCoDinh&action=layDanhSachNhanVien&ma_phong_ban=' + currentPhongBan);
                const nvText = await nvResponse.text();
                console.log('Response nhân viên:', nvText);
                const nvData = JSON.parse(nvText);

                if (!nvData.success) {
                    throw new Error(nvData.message || 'Lỗi load nhân viên');
                }
                danhSachNhanVien = nvData.data || [];

                const caResponse = await fetch('router.php?controller=LichCoDinh&action=layDanhSachCa');
                const caText = await caResponse.text();
                console.log('Response ca:', caText);
                const caData = JSON.parse(caText);

                if (!caData.success) {
                    throw new Error(caData.message || 'Lỗi load ca');
                }
                danhSachCa = caData.data || [];

                const lichResponse = await fetch('router.php?controller=LichCoDinh&action=layLichCoDinh&ma_phong_ban=' + currentPhongBan);
                const lichText = await lichResponse.text();
                console.log('Response lịch:', lichText);
                const lichData = JSON.parse(lichText);

                if (!lichData.success) {
                    throw new Error(lichData.message || 'Lỗi load lịch');
                }
                lichCoDinh = lichData.data || {};

                khoiTaoCaHienThi();
                renderSchedule();
            } catch (error) {
                console.error('Lỗi chi tiết:', error);
                showAlert('danger', 'Lỗi: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        function khoiTaoCaHienThi() {
            thuTrongTuan.forEach(thu => {
                if (!caHienThi[thu.id]) {
                    caHienThi[thu.id] = [...CA_MAC_DINH];
                }

                if (lichCoDinh[thu.id]) {
                    Object.keys(lichCoDinh[thu.id]).forEach(maCa => {
                        const ca = parseInt(maCa);
                        if (!caHienThi[thu.id].includes(ca)) {
                            caHienThi[thu.id].push(ca);
                        }
                    });
                }
            });
        }

        function renderSchedule() {
            const container = document.getElementById('scheduleContainer');
            container.innerHTML = '';

            thuTrongTuan.forEach(thu => {
                const dayCard = document.createElement('div');
                dayCard.className = 'day-card';
                dayCard.innerHTML = `
                    <div class="day-header">
                        <i class="fas fa-calendar-day me-2"></i>${thu.ten}
                        <span class="badge-count">${getTongNhanVienTrongNgay(thu.id)} nhân viên</span>
                    </div>
                    ${renderShifts(thu.id)}
                `;
                container.appendChild(dayCard);
            });

            initDragAndDrop();
        }

        function renderShifts(thu) {
    const caList = caHienThi[thu] || CA_MAC_DINH;

    let html = '';
    caList.forEach(maCa => {
        const ca = danhSachCa.find(c => parseInt(c.ma_ca) === maCa);
        if (!ca) return;

        const isDeletable = !CA_MAC_DINH.includes(maCa);

        html += `
            <div class="shift-section">
                ${isDeletable ? `<button class="btn-remove-shift" onclick="xoaCa(${thu}, ${maCa})">
                    <i class="fas fa-times"></i> Xóa ca
                </button>` : ''}
                <div class="shift-title">
                    <span>
                        <i class="fas fa-clock me-2" style="color: #0d6efd;"></i>
                        ${ca.ten_ca} (${ca.gio_bat_dau} - ${ca.gio_ket_thuc})
                    </span>
                    <span class="badge bg-info">${getNhanVienTrongCa(thu, ca.ma_ca).length} người</span>
                </div>
                <div class="dual-listbox">
                    <div class="listbox" data-thu="${thu}" data-ca="0" data-target-ca="${maCa}">
                        <div class="listbox-title">
                            <span>Chưa xếp ca</span>
                            <span class="badge bg-secondary">${getNhanVienChuaXep(thu).length}</span>
                        </div>
                        <div class="listbox-actions">
                            <button class="btn-bulk-action btn-select-all" onclick="chonTatCa(${thu}, 0, ${maCa})">
                                <i class="fas fa-check-square"></i> Chọn tất cả
                            </button>
                        </div>
                        <div class="listbox-actions">
                            <button class="btn-bulk-action btn-move-all" onclick="xepCaTatCa(${thu}, ${maCa})">
                                <i class="fas fa-angles-right"></i> Xếp tất cả
                            </button>
                            <button class="btn-bulk-action btn-move-selected" onclick="xepCaHangLoat(${thu}, 0, ${maCa})">
                                <i class="fas fa-arrow-right"></i> Xếp đã chọn
                            </button>
                        </div>
                        <div class="employee-list-container">
                            ${renderNhanVienList(getNhanVienChuaXep(thu), thu, 0)}
                        </div>
                    </div>
                    <div class="listbox" data-thu="${thu}" data-ca="${ca.ma_ca}">
                        <div class="listbox-title">
                            <span>Đã xếp ca</span>
                            <span class="badge bg-primary">${getNhanVienTrongCa(thu, ca.ma_ca).length}</span>
                        </div>
                        <div class="listbox-actions">
                            <button class="btn-bulk-action btn-select-all" onclick="chonTatCa(${thu}, ${ca.ma_ca})">
                                <i class="fas fa-check-square"></i> Chọn tất cả
                            </button>
                        </div>
                        <div class="listbox-actions">
                            <button class="btn-bulk-action btn-remove-all" onclick="boXepCaTatCa(${thu}, ${maCa})">
                                <i class="fas fa-angles-left"></i> Bỏ tất cả
                            </button>
                            <button class="btn-bulk-action btn-remove-selected" onclick="xoaKhoiCaHangLoat(${thu}, ${ca.ma_ca})">
                                <i class="fas fa-arrow-left"></i> Bỏ đã chọn
                            </button>
                        </div>
                        <div class="employee-list-container">
                            ${renderNhanVienList(getNhanVienTrongCa(thu, ca.ma_ca), thu, ca.ma_ca)}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    html += `
        <button class="btn-add-shift" onclick="moModalChonCa(${thu})">
            <i class="fas fa-plus-circle"></i> Thêm Ca
        </button>
    `;

    return html;
}


        function renderNhanVienList(danhSach, thu, ca) {
            if (danhSach.length === 0) {
                return '<div style="text-align: center; color: #999; padding: 20px;">Không có nhân viên</div>';
            }

            return danhSach.map(nv => {
                const trangThaiNghi = kiemTraNhanVienNghiThu(nv, thu);
                const isDisabled = ca === 0 && trangThaiNghi.nghi;

                let classExtra = '';
                let style = '';
                let tooltip = '';

                if (isDisabled) {
                    classExtra = 'disabled';
                    style = 'opacity: 0.5; cursor: not-allowed;';
                    tooltip = `title="${trangThaiNghi.ly_do}"`;
                }

                return `
                    <div class="employee-item ${classExtra}" 
                         draggable="${!isDisabled}" 
                         data-id="${nv.ma_nhan_vien}" 
                         data-thu="${thu}" 
                         data-ca="${ca}"
                         style="${style}"
                         ${tooltip}>
                        <div style="display: flex; align-items: center;">
                            <input type="checkbox" 
                                   class="employee-checkbox" 
                                   ${isDisabled ? 'disabled' : ''}
                                   onclick="toggleCheckbox(event, ${nv.ma_nhan_vien}, ${thu}, ${ca})">
                            <span>
                                <i class="fas ${isDisabled ? 'fa-user-slash' : 'fa-user'} me-2"></i>
                                ${nv.ho_ten}
                                ${isDisabled ? '<span class="badge bg-danger ms-2" style="font-size: 10px;">OFF</span>' : ''}
                            </span>
                        </div>
                        ${ca > 0 ? `<button class="btn-remove" onclick="xoaNhanVien(${thu}, ${ca}, ${nv.ma_nhan_vien})">
                            <i class="fas fa-times"></i>
                        </button>` : ''}
                    </div>
                `;
            }).join('');
        }

        function moModalChonCa(thu) {
            thuDangChon = thu;
            const modal = document.getElementById('modalChonCa');
            const container = document.getElementById('danhSachCaModal');

            const caHienThiThu = caHienThi[thu] || [];
            const caChuaHienThi = danhSachCa.filter(ca => !caHienThiThu.includes(parseInt(ca.ma_ca)));

            if (caChuaHienThi.length === 0) {
                showAlert('info', 'Đã hiển thị tất cả các ca');
                return;
            }

            container.innerHTML = caChuaHienThi.map(ca => `
                <div class="ca-option" onclick="themCa(${thu}, ${ca.ma_ca})">
                    <strong><i class="fas fa-clock me-2"></i>${ca.ten_ca}</strong><br>
                    <small class="text-muted">${ca.gio_bat_dau} - ${ca.gio_ket_thuc}</small>
                </div>
            `).join('');

            modal.classList.add('show');
        }

        function dongModal() {
            document.getElementById('modalChonCa').classList.remove('show');
        }

        function themCa(thu, maCa) {
            if (!caHienThi[thu]) caHienThi[thu] = [];
            if (!caHienThi[thu].includes(maCa)) {
                caHienThi[thu].push(maCa);
            }
            dongModal();
            renderSchedule();
            showAlert('success', 'Đã thêm ca');
        }

        function xoaCa(thu, maCa) {
            if (CA_MAC_DINH.includes(maCa)) {
                showAlert('warning', 'Không thể xóa ca mặc định');
                return;
            }

            if (confirm('Xóa ca này? Nhân viên trong ca sẽ bị xóa khỏi lịch.')) {
                if (lichCoDinh[thu] && lichCoDinh[thu][maCa]) {
                    delete lichCoDinh[thu][maCa];
                }

                caHienThi[thu] = caHienThi[thu].filter(ca => ca !== maCa);

                renderSchedule();
                showAlert('success', 'Đã xóa ca');
            }
        }

        function toggleCheckbox(event, maNV, thu, ca) {
            event.stopPropagation();
            const item = event.target.closest('.employee-item');
            if (event.target.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        }

        function chonTatCa(thu, ca, targetCa) {
    let listbox;
    
    if (ca === 0) {
        // Tìm listbox "chưa xếp ca" của ca cụ thể (dựa vào targetCa)
        listbox = document.querySelector(`.listbox[data-thu="${thu}"][data-ca="0"][data-target-ca="${targetCa}"]`);
    } else {
        // Tìm listbox "đã xếp ca"
        listbox = document.querySelector(`.listbox[data-thu="${thu}"][data-ca="${ca}"]`);
    }
    
    if (!listbox) {
        console.error('Không tìm thấy listbox');
        return;
    }
    
    const checkboxes = listbox.querySelectorAll('.employee-checkbox:not(:disabled)');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
        const item = checkbox.closest('.employee-item');
        if (!allChecked) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });
}
function xepCaHangLoat(thu, caCu, caMoi) {
    let listbox;
    
    if (caCu === 0) {
        // Tìm listbox "chưa xếp ca" của ca cụ thể
        listbox = document.querySelector(`.listbox[data-thu="${thu}"][data-ca="0"][data-target-ca="${caMoi}"]`);
    } else {
        listbox = document.querySelector(`.listbox[data-thu="${thu}"][data-ca="${caCu}"]`);
    }
    
    if (!listbox) {
        console.error('Không tìm thấy listbox');
        showAlert('danger', 'Lỗi: Không tìm thấy danh sách nhân viên');
        return;
    }
    
    const selectedCheckboxes = listbox.querySelectorAll('.employee-checkbox:checked:not(:disabled)');

    if (selectedCheckboxes.length === 0) {
        showAlert('warning', 'Vui lòng chọn ít nhất 1 nhân viên chưa nghỉ');
        return;
    }

    const selectedIds = Array.from(selectedCheckboxes).map(cb => {
        return parseInt(cb.closest('.employee-item').dataset.id);
    });

    // Lọc bỏ nhân viên đã nghỉ
    const idsHopLe = selectedIds.filter(id => {
        const nhanVien = danhSachNhanVien.find(nv => parseInt(nv.ma_nhan_vien) === id);
        if (nhanVien) {
            const trangThaiNghi = kiemTraNhanVienNghiThu(nhanVien, thu);
            return !trangThaiNghi.nghi;
        }
        return true;
    });

    if (idsHopLe.length === 0) {
        showAlert('warning', 'Tất cả nhân viên đã chọn đều đang nghỉ vào ngày này');
        return;
    }

    // Xóa khỏi tất cả các ca cũ
    if (lichCoDinh[thu]) {
        Object.keys(lichCoDinh[thu]).forEach(ca => {
            lichCoDinh[thu][ca] = lichCoDinh[thu][ca].filter(id => !idsHopLe.includes(id));
        });
    }

    // Thêm vào ca mới
    if (!lichCoDinh[thu]) lichCoDinh[thu] = {};
    if (!lichCoDinh[thu][caMoi]) lichCoDinh[thu][caMoi] = [];

    idsHopLe.forEach(id => {
        if (!lichCoDinh[thu][caMoi].includes(id)) {
            lichCoDinh[thu][caMoi].push(id);
        }
    });

    renderSchedule();

    const soNguoiBoQua = selectedIds.length - idsHopLe.length;
    let message = `Đã xếp ${idsHopLe.length} nhân viên vào ca`;
    if (soNguoiBoQua > 0) {
        message += ` (Bỏ qua ${soNguoiBoQua} người đang nghỉ)`;
    }
    showAlert('success', message);
}
        function xepCaTatCa(thu, ca) {
            const nhanVienChuaXep = getNhanVienChuaXep(thu);

            // Lọc bỏ nhân viên đã nghỉ
            const nhanVienHopLe = nhanVienChuaXep.filter(nv => {
                const trangThaiNghi = kiemTraNhanVienNghiThu(nv, thu);
                return !trangThaiNghi.nghi;
            });

            if (nhanVienHopLe.length === 0) {
                showAlert('info', 'Không có nhân viên nào chưa xếp ca (hoặc tất cả đều đang nghỉ)');
                return;
            }

            const soNguoiBoQua = nhanVienChuaXep.length - nhanVienHopLe.length;
            let confirmMessage = `Xếp ${nhanVienHopLe.length} nhân viên vào ca này?`;
            if (soNguoiBoQua > 0) {
                confirmMessage += `\n(Bỏ qua ${soNguoiBoQua} người đang nghỉ)`;
            }

            if (confirm(confirmMessage)) {
                if (!lichCoDinh[thu]) lichCoDinh[thu] = {};
                if (!lichCoDinh[thu][ca]) lichCoDinh[thu][ca] = [];

                nhanVienHopLe.forEach(nv => {
                    const id = parseInt(nv.ma_nhan_vien);
                    if (!lichCoDinh[thu][ca].includes(id)) {
                        lichCoDinh[thu][ca].push(id);
                    }
                });

                renderSchedule();
                showAlert('success', `Đã xếp ${nhanVienHopLe.length} nhân viên vào ca`);
            }
        }

        function boXepCaTatCa(thu, ca) {
            const nhanVienTrongCa = getNhanVienTrongCa(thu, ca);

            if (nhanVienTrongCa.length === 0) {
                showAlert('info', 'Không có nhân viên nào trong ca này');
                return;
            }

            if (confirm(`Bỏ xếp ca cho tất cả ${nhanVienTrongCa.length} nhân viên?`)) {
                if (lichCoDinh[thu] && lichCoDinh[thu][ca]) {
                    lichCoDinh[thu][ca] = [];
                }

                renderSchedule();
                showAlert('success', `Đã bỏ xếp ca cho ${nhanVienTrongCa.length} nhân viên`);
            }
        }

        function xoaKhoiCaHangLoat(thu, ca) {
            const listbox = document.querySelector(`.listbox[data-thu="${thu}"][data-ca="${ca}"]`);
            const selectedCheckboxes = listbox.querySelectorAll('.employee-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                showAlert('warning', 'Vui lòng chọn ít nhất 1 nhân viên');
                return;
            }

            const selectedIds = Array.from(selectedCheckboxes).map(cb => {
                return parseInt(cb.closest('.employee-item').dataset.id);
            });

            if (confirm(`Xóa ${selectedIds.length} nhân viên khỏi ca này?`)) {
                if (lichCoDinh[thu] && lichCoDinh[thu][ca]) {
                    lichCoDinh[thu][ca] = lichCoDinh[thu][ca].filter(id => !selectedIds.includes(id));
                }

                renderSchedule();
                showAlert('success', `Đã xóa ${selectedIds.length} nhân viên khỏi ca`);
            }
        }

        function getTongNhanVienTrongNgay(thu) {
            const lich = lichCoDinh[thu] || {};
            const uniqueIds = new Set();
            Object.values(lich).forEach(arr => arr.forEach(id => uniqueIds.add(id)));
            return uniqueIds.size;
        }

        function getNhanVienTrongCa(thu, ca) {
            const ids = (lichCoDinh[thu] && lichCoDinh[thu][ca]) || [];
            return danhSachNhanVien.filter(nv => ids.includes(parseInt(nv.ma_nhan_vien)));
        }

        function getNhanVienChuaXep(thu) {
            const lich = lichCoDinh[thu] || {};
            const daXep = new Set();
            Object.values(lich).forEach(arr => arr.forEach(id => daXep.add(parseInt(id))));
            return danhSachNhanVien.filter(nv => !daXep.has(parseInt(nv.ma_nhan_vien)));
        }

        function xoaNhanVien(thu, ca, maNV) {
            if (!lichCoDinh[thu] || !lichCoDinh[thu][ca]) return;
            lichCoDinh[thu][ca] = lichCoDinh[thu][ca].filter(id => id !== maNV);
            renderSchedule();
            showAlert('success', 'Đã xóa nhân viên khỏi ca làm việc');
        }

        function initDragAndDrop() {
            const items = document.querySelectorAll('.employee-item:not(.disabled)');
            const listboxes = document.querySelectorAll('.listbox');

            items.forEach(item => {
                item.addEventListener('dragstart', e => {
                    draggedElement = e.target;
                    e.target.classList.add('dragging');
                });

                item.addEventListener('dragend', e => {
                    e.target.classList.remove('dragging');
                });
            });

            listboxes.forEach(box => {
                box.addEventListener('dragover', e => {
                    e.preventDefault();
                    box.classList.add('drag-over');
                });

                box.addEventListener('dragleave', e => {
                    box.classList.remove('drag-over');
                });

                box.addEventListener('drop', e => {
                    e.preventDefault();
                    box.classList.remove('drag-over');

                    if (!draggedElement) return;

                    const maNV = parseInt(draggedElement.dataset.id);
                    const thu = parseInt(box.dataset.thu);
                    const ca = parseInt(box.dataset.ca);

                    // Kiểm tra nhân viên có đang nghỉ không
                    const nhanVien = danhSachNhanVien.find(nv => parseInt(nv.ma_nhan_vien) === maNV);
                    if (nhanVien) {
                        const trangThaiNghi = kiemTraNhanVienNghiThu(nhanVien, thu);
                        if (ca > 0 && trangThaiNghi.nghi) {
                            showAlert('warning', `${nhanVien.ho_ten} đã nghỉ vào ${thuTrongTuan.find(t => t.id === thu).ten}. ${trangThaiNghi.ly_do}`);
                            return;
                        }
                    }

                    if (lichCoDinh[thu]) {
                        Object.keys(lichCoDinh[thu]).forEach(oldCa => {
                            lichCoDinh[thu][oldCa] = lichCoDinh[thu][oldCa].filter(id => id !== maNV);
                        });
                    }

                    if (ca > 0) {
                        if (!lichCoDinh[thu]) lichCoDinh[thu] = {};
                        if (!lichCoDinh[thu][ca]) lichCoDinh[thu][ca] = [];
                        if (!lichCoDinh[thu][ca].includes(maNV)) {
                            lichCoDinh[thu][ca].push(maNV);
                        }
                    }

                    renderSchedule();
                    showAlert('success', 'Đã di chuyển nhân viên');
                });
            });
        }

        async function luuToanBoLich() {
            if (confirm('Bạn có chắc muốn lưu toàn bộ lịch cố định?')) {
                showLoading(true);

                try {
                    const response = await fetch('router.php?controller=LichCoDinh&action=luuLichCoDinh', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            ma_phong_ban: currentPhongBan,
                            lich: lichCoDinh
                        })
                    });

                    const text = await response.text();
                    console.log('Response lưu:', text);
                    const result = JSON.parse(text);

                    if (result.success) {
                        showAlert('success', result.message);
                    } else {
                        showAlert('danger', result.message);
                    }
                } catch (error) {
                    console.error('Lỗi:', error);
                    showAlert('danger', 'Lỗi khi lưu dữ liệu: ' + error.message);
                } finally {
                    showLoading(false);
                }
            }
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-fixed`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        function showLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            if (show) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
        }

        // Khởi động
        loadData();

        document.getElementById('phongBanSelect').addEventListener('change', function() {
            currentPhongBan = parseInt(this.value);
            loadData();
        });
    </script>
</body>

</html>