<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Lịch Làm Việc Tuần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/lichtuan.css">




   
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0"><i class="fas fa-calendar-week me-2"></i>Tạo Lịch Làm Việc Tuần</h2>
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

                    <div class="week-info-card" id="weekInfoCard">
                        <h3><i class="fas fa-calendar-alt me-2"></i>Tuần Tiếp Theo</h3>
                        <div class="week-range" id="weekRange">Đang tải...</div>
                    </div>
                    <div class="export-buttons">
    <button class="export-btn export-btn-info" onclick="showExportModal()">
        <span class="icon">📊</span>
        Xuất Excel
    </button>
    
    <button class="export-btn export-btn-success" onclick="exportAllDays()">
        <span class="icon">📦</span>
        Xuất Toàn Bộ (ZIP)
    </button>
</div>

                    <div id="warningAlert" style="display: none;"></div>
                </div>
            </div>

            <div id="scheduleContainer"></div>
        </div>

        <button class="btn btn-success btn-save-all" onclick="luuLichTuan()">
            <i class="fas fa-save me-2"></i>Tạo Lịch Tuần
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
        <div id="exportModal" class="export-modal">
    <div class="export-modal-content">
        <div class="export-modal-header">
            <h2>📊 Xuất Lịch Làm Việc</h2>
            <span class="close-modal" onclick="closeExportModal()">&times;</span>
        </div>
        
        <div class="loading-spinner" id="loadingSpinner">
            <div class="spinner"></div>
            <p>Đang tải dữ liệu...</p>
        </div>
        
        <div id="dayListContainer" class="day-list-container" style="display: none;">
            <!-- Danh sách các thứ sẽ được load vào đây -->
        </div>
        
        <div id="emptyMessage" class="empty-message" style="display: none;">
            <p>Không có dữ liệu lịch làm việc</p>
        </div>
    </div>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let danhSachNhanVien = [];
        let danhSachCa = [];
        let lichTuan = {};
        let nghiPhep = {};
        let currentPhongBan = 3;
        let caHienThi = {};
        let ngayDangChon = null;
        let thongTinTuan = null;

        const CA_MAC_DINH = [1, 2];

        async function loadData() {
            showLoading(true);

            try {
                // Load thông tin tuần
                const tuanResponse = await fetch('router.php?controller=LichTuan&action=layThongTinTuan&ma_phong_ban=' + currentPhongBan);
                const tuanData = await JSON.parse(await tuanResponse.text());

                if (!tuanData.success) {
                    throw new Error(tuanData.message || 'Lỗi load thông tin tuần');
                }
                thongTinTuan = tuanData.data;

                // Hiển thị thông tin tuần
                document.getElementById('weekRange').innerHTML = `
                    <i class="fas fa-calendar-check me-2"></i>
                    Từ ${formatDate(thongTinTuan.thu_2)} đến ${formatDate(thongTinTuan.thu_7)}
                `;

                // Xử lý chế độ tạo mới hoặc chỉnh sửa
                if (thongTinTuan.da_ton_tai) {
                    // Chế độ chỉnh sửa
                    document.getElementById('warningAlert').innerHTML = `
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Chế độ chỉnh sửa:</strong> 
                        Lịch tuần này đã tồn tại. Bạn có thể điều chỉnh và cập nhật lại.
                    `;
                    document.getElementById('warningAlert').className = 'alert alert-info';
                    document.getElementById('warningAlert').style.borderLeft = '4px solid #0dcaf0';
                    document.getElementById('warningAlert').style.background = '#cff4fc';
                    document.getElementById('warningAlert').style.display = 'block';

                    // Đổi nút thành "Cập Nhật"
                    document.querySelector('.btn-save-all').innerHTML = `
                        <i class="fas fa-sync-alt me-2"></i>Cập Nhật Lịch Tuần
                    `;
                    document.querySelector('.btn-save-all').classList.remove('btn-success');
                    document.querySelector('.btn-save-all').classList.add('btn-warning');

                    // Đổi tiêu đề trang
                    document.querySelector('h2').innerHTML = `
                        <i class="fas fa-edit me-2"></i>Chỉnh Sửa Lịch Làm Việc Tuần
                    `;
                } else {
                    // Chế độ tạo mới
                    document.getElementById('warningAlert').style.display = 'none';

                    // Giữ nguyên nút "Tạo Lịch"
                    document.querySelector('.btn-save-all').innerHTML = `
                        <i class="fas fa-save me-2"></i>Tạo Lịch Tuần
                    `;
                    document.querySelector('.btn-save-all').classList.remove('btn-warning');
                    document.querySelector('.btn-save-all').classList.add('btn-success');

                    // Tiêu đề tạo mới
                    document.querySelector('h2').innerHTML = `
                        <i class="fas fa-calendar-week me-2"></i>Tạo Lịch Làm Việc Tuần
                    `;
                }

                // Load nhân viên
                const nvResponse = await fetch('router.php?controller=LichTuan&action=layDanhSachNhanVien&ma_phong_ban=' + currentPhongBan);
                const nvData = await JSON.parse(await nvResponse.text());

                if (!nvData.success) {
                    throw new Error(nvData.message || 'Lỗi load nhân viên');
                }
                danhSachNhanVien = nvData.data || [];

                // Load ca làm việc
                const caResponse = await fetch('router.php?controller=LichTuan&action=layDanhSachCa');
                const caData = await JSON.parse(await caResponse.text());

                if (!caData.success) {
                    throw new Error(caData.message || 'Lỗi load ca');
                }
                danhSachCa = caData.data || [];

                // Load lịch tuần từ lịch cố định
                const lichResponse = await fetch('router.php?controller=LichTuan&action=layLichTuan&ma_phong_ban=' + currentPhongBan + '&thu2=' + thongTinTuan.thu_2);
                const lichData = await JSON.parse(await lichResponse.text());

                if (!lichData.success) {
                    throw new Error(lichData.message || 'Lỗi load lịch');
                }
                lichTuan = lichData.data.lich_tuan || {};
                nghiPhep = lichData.data.nghi_phep || {};

                khoiTaoCaHienThi();
                renderSchedule();
            } catch (error) {
                console.error('Lỗi chi tiết:', error);
                showAlert('danger', 'Lỗi: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function khoiTaoCaHienThi() {
            thongTinTuan.ngay_trong_tuan.forEach(item => {
                const ngay = item.ngay;
                if (!caHienThi[ngay]) {
                    caHienThi[ngay] = [...CA_MAC_DINH];
                }

                if (lichTuan[ngay]) {
                    Object.keys(lichTuan[ngay]).forEach(maCa => {
                        const ca = parseInt(maCa);
                        if (!caHienThi[ngay].includes(ca)) {
                            caHienThi[ngay].push(ca);
                        }
                    });
                }
            });
        }

        function renderSchedule() {
            const container = document.getElementById('scheduleContainer');
            container.innerHTML = '';

            thongTinTuan.ngay_trong_tuan.forEach(item => {
                const dayCard = document.createElement('div');
                dayCard.className = 'day-card';

                const tenThu = ['', '', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'][item.thu];

                dayCard.innerHTML = `
                    <div class="day-header">
                        <div>
                            <i class="fas fa-calendar-day me-2"></i>${tenThu}
                            <span class="day-date">(${item.ngay_hien_thi})</span>
                        </div>
                        <span class="badge-count">${getTongNhanVienTrongNgay(item.ngay)} nhân viên</span>
                    </div>
                    ${renderShifts(item.ngay)}
                `;
                container.appendChild(dayCard);
            });

            initDragAndDrop();
        }

        function renderShifts(ngay) {
            const caList = caHienThi[ngay] || CA_MAC_DINH;

            let html = '';
            caList.forEach((maCa, index) => {
                const ca = danhSachCa.find(c => parseInt(c.ma_ca) === maCa);
                if (!ca) return;

                const isDeletable = !CA_MAC_DINH.includes(maCa);

                html += `
                    <div class="shift-section">
                        ${isDeletable ? `<button class="btn-remove-shift" onclick="xoaCa('${ngay}', ${maCa})">
                            <i class="fas fa-times"></i> Xóa ca
                        </button>` : ''}
                        <div class="shift-title">
                            <span>
                                <i class="fas fa-clock me-2" style="color: #0d6efd;"></i>
                                ${ca.ten_ca} (${ca.gio_bat_dau} - ${ca.gio_ket_thuc})
                            </span>
                            <span class="badge bg-info">${getNhanVienTrongCa(ngay, ca.ma_ca).length} người</span>
                        </div>
                        <div class="dual-listbox">
                            <div class="listbox" data-ngay="${ngay}" data-ca="0" data-target-ca="${maCa}">
                                <div class="listbox-title">
                                    <span>Chưa xếp ca</span>
                                    <span class="badge bg-secondary">${getNhanVienChuaXep(ngay).length}</span>
                                </div>
                                <div class="listbox-actions">
                                    <button class="btn-bulk-action btn-select-all" onclick="chonTatCa('${ngay}', 0, ${maCa})">
                                        <i class="fas fa-check-square"></i> Chọn tất cả
                                    </button>
                                </div>
                                <div class="listbox-actions">
                                    <button class="btn-bulk-action btn-move-all" onclick="xepCaTatCa('${ngay}', ${maCa})">
                                        <i class="fas fa-angles-right"></i> Xếp tất cả
                                    </button>
                                 <button class="btn-bulk-action btn-move-selected" onclick="xepCaHangLoatTheoListbox('${ngay}', ${maCa})">
    <i class="fas fa-arrow-right"></i> Xếp đã chọn
</button>
                                </div>
                                <div class="employee-list-container">
                                    ${renderNhanVienList(getNhanVienChuaXep(ngay), ngay, 0, maCa)}
                                </div>
                            </div>
                            <div class="listbox" data-ngay="${ngay}" data-ca="${ca.ma_ca}">
                                <div class="listbox-title">
                                    <span>Đã xếp ca</span>
                                    <span class="badge bg-primary">${getNhanVienTrongCa(ngay, ca.ma_ca).length}</span>
                                </div>
                                <div class="listbox-actions">
                                    <button class="btn-bulk-action btn-select-all" onclick="chonTatCa('${ngay}', ${ca.ma_ca})">
                                        <i class="fas fa-check-square"></i> Chọn tất cả
                                    </button>
                                </div>
                                <div class="listbox-actions">
                                    <button class="btn-bulk-action btn-remove-all" onclick="boXepCaTatCa('${ngay}', ${maCa})">
                                        <i class="fas fa-angles-left"></i> Bỏ tất cả
                                    </button>
                                    <button class="btn-bulk-action btn-remove-selected" onclick="xoaKhoiCaHangLoat('${ngay}', ${ca.ma_ca})">
                                        <i class="fas fa-arrow-left"></i> Bỏ đã chọn
                                    </button>
                                </div>
                                <div class="employee-list-container">
                                    ${renderNhanVienList(getNhanVienTrongCa(ngay, ca.ma_ca), ngay, ca.ma_ca)}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `
                <button class="btn-add-shift" onclick="moModalChonCa('${ngay}')">
                    <i class="fas fa-plus-circle"></i> Thêm Ca
                </button>
            `;

            return html;
        }


        function moModalChonCa(ngay) {
            ngayDangChon = ngay;
            const modal = document.getElementById('modalChonCa');
            const container = document.getElementById('danhSachCaModal');

            const caHienThiNgay = caHienThi[ngay] || [];
            const caChuaHienThi = danhSachCa.filter(ca => !caHienThiNgay.includes(parseInt(ca.ma_ca)));

            if (caChuaHienThi.length === 0) {
                showAlert('info', 'Đã hiển thị tất cả các ca');
                return;
            }

            container.innerHTML = caChuaHienThi.map(ca => `
                <div class="ca-option" onclick="themCa('${ngay}', ${ca.ma_ca})">
                    <strong><i class="fas fa-clock me-2"></i>${ca.ten_ca}</strong><br>
                    <small class="text-muted">${ca.gio_bat_dau} - ${ca.gio_ket_thuc}</small>
                </div>
            `).join('');

            modal.classList.add('show');
        }

        function dongModal() {
            document.getElementById('modalChonCa').classList.remove('show');
        }

        function themCa(ngay, maCa) {
            if (!caHienThi[ngay]) caHienThi[ngay] = [];
            if (!caHienThi[ngay].includes(maCa)) {
                caHienThi[ngay].push(maCa);
            }
            dongModal();
            renderSchedule();
            showAlert('success', 'Đã thêm ca');
        }

        function xoaCa(ngay, maCa) {
            if (CA_MAC_DINH.includes(maCa)) {
                showAlert('warning', 'Không thể xóa ca mặc định');
                return;
            }

            if (confirm('Xóa ca này? Nhân viên trong ca sẽ bị xóa khỏi lịch.')) {
                if (lichTuan[ngay] && lichTuan[ngay][maCa]) {
                    delete lichTuan[ngay][maCa];
                }

                caHienThi[ngay] = caHienThi[ngay].filter(ca => ca !== maCa);

                renderSchedule();
                showAlert('success', 'Đã xóa ca');
            }
        }

        function renderNhanVienList(danhSach, ngay, ca, targetCa) {
            if (danhSach.length === 0) {
                return '<div style="text-align: center; color: #999; padding: 20px;">Không có nhân viên</div>';
            }

            return danhSach.map(nv => {
                const isOnLeave = nghiPhep[ngay] && nghiPhep[ngay].includes(parseInt(nv.ma_nhan_vien));
                const itemClass = isOnLeave ? 'employee-item on-leave' : 'employee-item';

                return `
                    <div class="${itemClass}" draggable="${!isOnLeave}" data-id="${nv.ma_nhan_vien}" data-ngay="${ngay}" data-ca="${ca}">
                        <div style="display: flex; align-items: center;">
                            <input type="checkbox" class="employee-checkbox" ${isOnLeave ? 'disabled' : ''} onclick="toggleCheckbox(event, ${nv.ma_nhan_vien}, '${ngay}', ${ca})">
                            <span>
                                <i class="fas fa-user me-2"></i>${nv.ho_ten}
                                ${isOnLeave ? '<span class="badge-on-leave">Nghỉ phép</span>' : ''}
                            </span>
                        </div>
                        ${ca > 0 && !isOnLeave ? `<button class="btn-remove" onclick="xoaNhanVien('${ngay}', ${ca}, ${nv.ma_nhan_vien})">
                            <i class="fas fa-times"></i>
                        </button>` : ''}
                    </div>
                `;
            }).join('');
        }

        function toggleCheckbox(event, maNV, ngay, ca) {
            event.stopPropagation();
            const item = event.target.closest('.employee-item');
            if (event.target.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        }

        function chonTatCa(ngay, ca, targetCa) {
            // Nếu ca = 0, tìm TẤT CẢ listbox "chưa xếp ca" của ngày này
            // Vì tất cả các ca đều hiển thị chung danh sách "chưa xếp ca"
            let checkboxes;
            if (ca === 0) {
                const allUnassignedBoxes = document.querySelectorAll(`.listbox[data-ngay="${ngay}"][data-ca="0"]`);
                checkboxes = [];
                allUnassignedBoxes.forEach(box => {
                    checkboxes.push(...box.querySelectorAll('.employee-checkbox:not([disabled])'));
                });
            } else {
                const listbox = document.querySelector(`.listbox[data-ngay="${ngay}"][data-ca="${ca}"]`);
                if (!listbox) return;
                checkboxes = listbox.querySelectorAll('.employee-checkbox:not([disabled])');
            }

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

        function xepCaHangLoatTheoListbox(ngay, caMoi) {
            console.log('=== DEBUG xepCaHangLoatTheoListbox ===');
            console.log('ngay:', ngay, 'caMoi:', caMoi);

            // Tìm listbox với selector đầy đủ
            let selector = `.listbox[data-ngay="${ngay}"][data-ca="0"][data-target-ca="${caMoi}"]`;
            console.log('Selector:', selector);

            let listbox = document.querySelector(selector);
            console.log('Listbox found:', listbox);

            if (!listbox) {
                console.log('Không tìm thấy! Kiểm tra tất cả listbox:');
                const allListboxes = document.querySelectorAll(`.listbox[data-ngay="${ngay}"]`);
                allListboxes.forEach((box, index) => {
                    console.log(`Listbox ${index}:`, {
                        'data-ngay': box.getAttribute('data-ngay'),
                        'data-ca': box.getAttribute('data-ca'),
                        'data-target-ca': box.getAttribute('data-target-ca')
                    });
                });

                showAlert('danger', 'Lỗi: Không tìm thấy danh sách nhân viên');
                return;
            }

            const selectedCheckboxes = listbox.querySelectorAll('.employee-checkbox:checked:not([disabled])');
            console.log('Found checkboxes:', selectedCheckboxes.length);

            if (selectedCheckboxes.length === 0) {
                showAlert('warning', 'Vui lòng chọn ít nhất 1 nhân viên');
                return;
            }

            const selectedIds = Array.from(selectedCheckboxes).map(cb => {
                return parseInt(cb.closest('.employee-item').dataset.id);
            });

            console.log('Selected IDs:', selectedIds);

            // Xóa khỏi tất cả các ca cũ
            if (lichTuan[ngay]) {
                Object.keys(lichTuan[ngay]).forEach(ca => {
                    lichTuan[ngay][ca] = lichTuan[ngay][ca].filter(id => !selectedIds.includes(id));
                });
            }

            // Thêm vào ca mới
            if (!lichTuan[ngay]) lichTuan[ngay] = {};
            if (!lichTuan[ngay][caMoi]) lichTuan[ngay][caMoi] = [];

            selectedIds.forEach(id => {
                if (!lichTuan[ngay][caMoi].includes(id)) {
                    lichTuan[ngay][caMoi].push(id);
                }
            });

            renderSchedule();
            showAlert('success', `Đã xếp ${selectedIds.length} nhân viên vào ca`);
        }

        function xepCaHangLoat(ngay, caCu, caMoi) {
            // Tìm TẤT CẢ checkbox đã chọn từ MỌI listbox "chưa xếp ca" của ngày này
            let selectedCheckboxes;
            if (caCu === 0) {
                const allUnassignedBoxes = document.querySelectorAll(`.listbox[data-ngay="${ngay}"][data-ca="0"]`);
                selectedCheckboxes = [];
                allUnassignedBoxes.forEach(box => {
                    selectedCheckboxes.push(...box.querySelectorAll('.employee-checkbox:checked:not([disabled])'));
                });
            } else {
                const listbox = document.querySelector(`.listbox[data-ngay="${ngay}"][data-ca="${caCu}"]`);
                if (!listbox) {
                    console.error('Không tìm thấy listbox');
                    return;
                }
                selectedCheckboxes = listbox.querySelectorAll('.employee-checkbox:checked:not([disabled])');
            }

            console.log('Found checkboxes:', selectedCheckboxes.length);

            if (selectedCheckboxes.length === 0) {
                showAlert('warning', 'Vui lòng chọn ít nhất 1 nhân viên');
                return;
            }

            const selectedIds = Array.from(selectedCheckboxes).map(cb => {
                return parseInt(cb.closest('.employee-item').dataset.id);
            });

            // Loại bỏ duplicate IDs (vì có thể checkbox bị trùng ở nhiều listbox)
            const uniqueIds = [...new Set(selectedIds)];

            console.log('Selected IDs:', uniqueIds);

            if (lichTuan[ngay]) {
                Object.keys(lichTuan[ngay]).forEach(ca => {
                    lichTuan[ngay][ca] = lichTuan[ngay][ca].filter(id => !uniqueIds.includes(id));
                });
            }

            if (!lichTuan[ngay]) lichTuan[ngay] = {};
            if (!lichTuan[ngay][caMoi]) lichTuan[ngay][caMoi] = [];

            uniqueIds.forEach(id => {
                if (!lichTuan[ngay][caMoi].includes(id)) {
                    lichTuan[ngay][caMoi].push(id);
                }
            });

            renderSchedule();
            showAlert('success', `Đã xếp ${uniqueIds.length} nhân viên vào ca`);
        }

        function xepCaTatCa(ngay, ca) {
            const nhanVienChuaXep = getNhanVienChuaXep(ngay);

            if (nhanVienChuaXep.length === 0) {
                showAlert('info', 'Không có nhân viên nào chưa xếp ca');
                return;
            }

            if (confirm(`Xếp tất cả ${nhanVienChuaXep.length} nhân viên vào ca này?`)) {
                if (!lichTuan[ngay]) lichTuan[ngay] = {};
                if (!lichTuan[ngay][ca]) lichTuan[ngay][ca] = [];

                nhanVienChuaXep.forEach(nv => {
                    const id = parseInt(nv.ma_nhan_vien);
                    if (!lichTuan[ngay][ca].includes(id)) {
                        lichTuan[ngay][ca].push(id);
                    }
                });

                renderSchedule();
                showAlert('success', `Đã xếp ${nhanVienChuaXep.length} nhân viên vào ca`);
            }
        }

        function boXepCaTatCa(ngay, ca) {
            const nhanVienTrongCa = getNhanVienTrongCa(ngay, ca);

            if (nhanVienTrongCa.length === 0) {
                showAlert('info', 'Không có nhân viên nào trong ca này');
                return;
            }

            if (confirm(`Bỏ xếp ca cho tất cả ${nhanVienTrongCa.length} nhân viên?`)) {
                if (lichTuan[ngay] && lichTuan[ngay][ca]) {
                    lichTuan[ngay][ca] = [];
                }

                renderSchedule();
                showAlert('success', `Đã bỏ xếp ca cho ${nhanVienTrongCa.length} nhân viên`);
            }
        }

        function xoaKhoiCaHangLoat(ngay, ca) {
            const listbox = document.querySelector(`.listbox[data-ngay="${ngay}"][data-ca="${ca}"]`);
            const selectedCheckboxes = listbox.querySelectorAll('.employee-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                showAlert('warning', 'Vui lòng chọn ít nhất 1 nhân viên');
                return;
            }

            const selectedIds = Array.from(selectedCheckboxes).map(cb => {
                return parseInt(cb.closest('.employee-item').dataset.id);
            });

            if (confirm(`Xóa ${selectedIds.length} nhân viên khỏi ca này?`)) {
                if (lichTuan[ngay] && lichTuan[ngay][ca]) {
                    lichTuan[ngay][ca] = lichTuan[ngay][ca].filter(id => !selectedIds.includes(id));
                }

                renderSchedule();
                showAlert('success', `Đã xóa ${selectedIds.length} nhân viên khỏi ca`);
            }
        }

        function getTongNhanVienTrongNgay(ngay) {
            const lich = lichTuan[ngay] || {};
            const uniqueIds = new Set();
            Object.values(lich).forEach(arr => arr.forEach(id => uniqueIds.add(id)));
            return uniqueIds.size;
        }

        function getNhanVienTrongCa(ngay, ca) {
            const ids = (lichTuan[ngay] && lichTuan[ngay][ca]) || [];
            return danhSachNhanVien.filter(nv => ids.includes(parseInt(nv.ma_nhan_vien)));
        }

        function getNhanVienChuaXep(ngay) {
            const lich = lichTuan[ngay] || {};
            const daXep = new Set();
            Object.values(lich).forEach(arr => arr.forEach(id => daXep.add(parseInt(id))));
            return danhSachNhanVien.filter(nv => !daXep.has(parseInt(nv.ma_nhan_vien)));
        }

        function xoaNhanVien(ngay, ca, maNV) {
            if (!lichTuan[ngay] || !lichTuan[ngay][ca]) return;
            lichTuan[ngay][ca] = lichTuan[ngay][ca].filter(id => id !== maNV);
            renderSchedule();
            showAlert('success', 'Đã xóa nhân viên khỏi ca làm việc');
        }

        let draggedElement = null;

        function initDragAndDrop() {
            const items = document.querySelectorAll('.employee-item:not(.on-leave)');
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
                    const ngay = box.dataset.ngay;
                    const ca = parseInt(box.dataset.ca);

                    if (lichTuan[ngay]) {
                        Object.keys(lichTuan[ngay]).forEach(oldCa => {
                            lichTuan[ngay][oldCa] = lichTuan[ngay][oldCa].filter(id => id !== maNV);
                        });
                    }

                    if (ca > 0) {
                        if (!lichTuan[ngay]) lichTuan[ngay] = {};
                        if (!lichTuan[ngay][ca]) lichTuan[ngay][ca] = [];
                        if (!lichTuan[ngay][ca].includes(maNV)) {
                            lichTuan[ngay][ca].push(maNV);
                        }
                    }

                    renderSchedule();
                    showAlert('success', 'Đã di chuyển nhân viên');
                });
            });
        }

        async function luuLichTuan() {
            const isEdit = thongTinTuan.da_ton_tai;
            const confirmMessage = isEdit ?
                'Bạn có chắc muốn cập nhật lịch tuần này?' :
                'Bạn có chắc muốn tạo lịch tuần này?';

            if (confirm(confirmMessage)) {
                showLoading(true);

                try {
                    const response = await fetch('router.php?controller=LichTuan&action=luuLichTuan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            ma_phong_ban: currentPhongBan,
                            thu2: thongTinTuan.thu_2,
                            lich: lichTuan
                        })
                    });

                    const text = await response.text();
                    console.log('Response lưu:', text);
                    const result = JSON.parse(text);

                    if (result.success) {
                        const successMessage = isEdit ?
                            'Cập nhật lịch tuần thành công!' :
                            result.message;
                        showAlert('success', successMessage);
                        setTimeout(() => {
                            loadData(); // Reload lại để cập nhật trạng thái
                        }, 1500);
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

        loadData();

        document.getElementById('phongBanSelect').addEventListener('change', function() {
            currentPhongBan = parseInt(this.value);
            loadData();
        });





// Hàm hiển thị modal xuất Excel
function showExportModal() {
    // Sử dụng biến global đã có: thongTinTuan và currentPhongBan
    if (!thongTinTuan || !thongTinTuan.thu_2 || !currentPhongBan) {
        alert('Vui lòng chọn phòng ban và tạo lịch tuần trước');
        return;
    }
    
    const modal = document.getElementById('exportModal');
    const loading = document.getElementById('loadingSpinner');
    const container = document.getElementById('dayListContainer');
    const emptyMsg = document.getElementById('emptyMessage');
    
    modal.style.display = 'block';
    loading.style.display = 'block';
    container.style.display = 'none';
    emptyMsg.style.display = 'none';
    
    // Load danh sách các thứ có dữ liệu - SỬA ĐỔI URL
    fetch(`router.php?controller=LichTuan&action=layDanhSachThuCoData&thu2=${thongTinTuan.thu_2}&ma_phong_ban=${currentPhongBan}`)
        .then(res => res.json())
        .then(data => {
            loading.style.display = 'none';
            
            if (data.success && data.data.length > 0) {
                renderDayListExport(data.data);
                container.style.display = 'block';
            } else {
                emptyMsg.style.display = 'block';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            loading.style.display = 'none';
            emptyMsg.style.display = 'block';
        });
}

// Render danh sách các thứ
function renderDayListExport(days) {
    const container = document.getElementById('dayListContainer');
    let html = '<h3 style="margin-bottom: 15px; color: #333;">Chọn ngày để xuất:</h3>';
    
    days.forEach(day => {
        html += `
            <div class="day-item">
                <div class="day-info">
                    <div class="day-name">${day.ten_thu}</div>
                    <div class="day-date">${day.ngay}</div>
                </div>
                <span class="day-count">${day.so_nhan_vien} NV</span>
                <button class="btn-export-day" onclick="exportSingleDay(${day.thu})">
                    Xuất Excel
                </button>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Đóng modal
function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
}

// Xuất Excel cho một thứ
function exportSingleDay(thu) {
    if (!thongTinTuan || !thongTinTuan.thu_2 || !currentPhongBan) {
        alert('Thiếu thông tin');
        return;
    }
    
    const url = `router.php?controller=LichTuan&action=xuatExcelTheoThu&thu2=${thongTinTuan.thu_2}&ma_phong_ban=${currentPhongBan}&thu=${thu}`;
    window.location.href = url;
    
    setTimeout(() => {
        closeExportModal();
    }, 1000);
}

// Xuất tất cả các thứ (ZIP)
function exportAllDays() {
    if (!thongTinTuan || !thongTinTuan.thu_2 || !currentPhongBan) {
        alert('Vui lòng chọn phòng ban và tạo lịch tuần trước');
        return;
    }
    
    if (confirm('Bạn muốn xuất tất cả các ngày trong tuần thành file ZIP?')) {
        const url = `router.php?controller=LichTuan&action=xuatExcelTatCa&thu2=${thongTinTuan.thu_2}&ma_phong_ban=${currentPhongBan}`;
        window.location.href = url;
    }
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
    const modal = document.getElementById('exportModal');
    if (event.target == modal) {
        closeExportModal();
    }
}

// ===== KẾT THÚC CODE XUẤT EXCEL =====


    </script>
</body>

</html>