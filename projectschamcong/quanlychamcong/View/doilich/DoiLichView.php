<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Lịch & Đổi Ca Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    
    
    <style>
        body {
            
            min-height: 100vh;
            padding: 20px 0;
        }
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .page-header {
           
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
        }
        .calendar-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: top;
            min-width: 100px;
        }
        .shift-badge {
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 8px;
            display: block;
            margin: 4px 0;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .shift-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        .shift-badge.selected {
            border: 3px solid #ff6b6b;
            box-shadow: 0 0 10px rgba(255,107,107,0.5);
        }
        .shift-badge.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #e9ecef !important;
            color: #6c757d !important;
        }
        .badge-ca-1, .badge-ca-5 { background: #fff3cd; color: #856404; }
        .badge-ca-2, .badge-ca-6 { background: #cfe2ff; color: #084298; }
        .badge-ca-3 { background: #d1ecf1; color: #0c5460; }
        .badge-ca-4 { background: #d4edda; color: #155724; }
        .badge-ca-7 { background: #f8d7da; color: #721c24; }
        .badge-ca-8, .badge-ca-9 { background: #e2e3e5; color: #383d41; }
        .badge-ca-10 { background: #cce5ff; color: #004085; }
        .employee-name {
            font-weight: 600;
            color: #667eea;
            position: sticky;
            left: 0;
            background: white;
            z-index: 1;
        }
        .shift-badge.nghi {
    background: #ffc107 !important;
    color: #000 !important;
    font-weight: bold;
    border: 2px dashed #ff9800;
    /* Badge ca làm việc bình thường */
.shift-badge {
    font-size: 11px;
    padding: 8px 10px;
    border-radius: 8px;
    display: block;
    margin: 4px 0;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

/* ✅ Badge có thông tin nghỉ NHƯNG vẫn cho đổi */
.shift-badge:not(.disabled) {
    cursor: pointer;
}

.shift-badge:not(.disabled):hover {
    transform: scale(1.05);
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

/* ❌ Badge DISABLED - không cho đổi */
.shift-badge.disabled {
    opacity: 0.5;
    cursor: not-allowed !important;
    background: #e9ecef !important;
    color: #6c757d !important;
}

.shift-badge.disabled:hover {
    transform: none !important;
}

/* Badge được chọn */
.shift-badge.selected {
    border: 3px solid #ff6b6b;
    box-shadow: 0 0 10px rgba(255,107,107,0.5);
}

/* Thông tin bổ sung (nghỉ lễ, nghỉ tuần) */
.shift-badge small.text-danger,
.shift-badge small.text-info {
    font-weight: 600;
    display: block;
    margin-top: 4px;
    padding-top: 4px;
    border-top: 1px dashed rgba(0,0,0,0.2);
}

}

.shift-badge.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
/* OFF có thể chọn */
.shift-badge.off-selectable:hover {
    transform: scale(1.05);
    box-shadow: 0 3px 10px rgba(0,0,0,0.3);
    opacity: 0.9;
}

.shift-badge.off-selectable.selected {
    border: 3px solid #ff6b6b;
    box-shadow: 0 0 10px rgba(255,107,107,0.5);
}
    </style>
</head>
<body>


<?php require_once __DIR__ . '/../component/navbar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <?php require_once __DIR__ . '/../component/topbar.php'; ?>



   
        <div class="page-header">
            <h2><i class="bi bi-arrow-left-right"></i> Đổi Lịch & Đổi Ca Nhân Viên</h2>
            <p class="mb-0">Chọn 2 ca cùng thứ để hoán đổi</p>
        </div>

        <div class="p-4">
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tháng</label>
                        <select id="thang_filter" class="form-select">
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                $selected = ($i == $thang) ? 'selected' : '';
                                echo "<option value='$i' $selected>Tháng $i</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Năm</label>
                        <select id="nam_filter" class="form-select">
                            <?php
                            for ($i = $nam - 1; $i <= $nam + 1; $i++) {
                                $selected = ($i == $nam) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
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

<input type="hidden" id="ma_phong_ban" value="<?= $ma_phong_ban ?>">

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let selectedCells = [];
let lichData = [];

$(document).ready(function() {
    loadWeeks();
    
    $('#thang_filter, #nam_filter').on('change', function() {
        loadWeeks();
    });
});

function loadWeeks() {
    const thang = $('#thang_filter').val();
    const nam = $('#nam_filter').val();
    
    console.log('Loading weeks for:', thang, nam); // Debug
    
    $.post('router.php', {
        controller: 'DoiLich',
        action: 'getWeeksInMonth',
        thang: thang,
        nam: nam
    }, function(res) {
        console.log('Response:', res); // Debug
        
        if (res.success) {
            let options = '<option value="">-- Chọn tuần --</option>';
            
            if (res.data && res.data.length > 0) {
                res.data.forEach(week => {
                    const start = new Date(week.ngay_bat_dau).toLocaleDateString('vi-VN');
                    const end = new Date(week.ngay_ket_thuc).toLocaleDateString('vi-VN');
                    options += `<option value="${week.tuan}">Tuần ${week.tuan} (${start} - ${end})</option>`;
                });
            } else {
                options += '<option value="" disabled>Không có lịch trong tháng này</option>';
            }
            
            $('#tuan_filter').html(options);
        } else {
            console.error('Error:', res.message);
            Swal.fire('Lỗi', res.message || 'Không thể tải danh sách tuần', 'error');
        }
    }, 'json').fail(function(xhr, status, error) {
        console.error('Response:', xhr.responseText); 
        console.error('AJAX Error:', status, error);
        Swal.fire('Lỗi', 'Không thể kết nối đến server', 'error');
    });
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
    
    $.post('router.php', { // ✅ Sửa
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
        console.error('Response:', xhr.responseText); // ✅ Debug
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
                let canSwap = false; // ✅ Kiểm tra có thể đổi không
                
                if (coNghiDon) {
                    // ❌ Nghỉ phép theo đơn - KHÔNG được đổi
                    offReason = 'Nghỉ phép (đơn)';
                    offColor = '#ffc107';
                    canSwap = false;
                } else if (coNghiLe) {
                    // ✅ Nghỉ lễ - ĐƯỢC đổi
                    offReason = lich.ly_do_nghi_le || 'Nghỉ lễ';
                    offColor = '#dc3545';
                    canSwap = !lich.da_qua; // Chỉ đổi nếu chưa qua ngày
                } else if (coNghiTuan) {
                    // ✅ Nghỉ phép tuần - ĐƯỢC đổi
                    offReason = lich.ly_do_nghi_tuan || 'Nghỉ phép tuần';
                    offColor = '#17a2b8';
                    canSwap = !lich.da_qua; // Chỉ đổi nếu chưa qua ngày
                } else {
                    offReason = 'Không có ca';
                    offColor = '#e9ecef';
                    canSwap = false;
                }
                
                // ✅ Nếu được phép đổi → thêm onclick và ID
                const offId = `off-${nv.ma_nhan_vien}-${d.ngay}`;
                const onclick = canSwap ? `onclick="chonOff('${offId}', ${nv.ma_nhan_vien}, '${d.ngay}', '${d.thu}')"` : '';
                const cursorStyle = canSwap ? 'cursor: pointer;' : 'cursor: not-allowed;';
                const hoverClass = canSwap ? 'off-selectable' : '';
                
                html += `<td>
                    <div class="shift-badge ${hoverClass}" 
                         id="${offId}"
                         ${onclick}
                         title="${canSwap ? 'Click để chọn (đổi ca hoặc nhận ca)' : offReason}"
                         style="background: ${offColor} !important; color: #fff !important; ${cursorStyle}">
                        <strong><i class="bi bi-x-circle"></i> OFF</strong><br>
                        <small>${offReason}</small>
                    </div>
                </td>`;
                return;
            }
            
            // ✅ Có ca làm việc
            const isDisabled = lich.da_cham_cong == 1 || lich.da_qua == 1 || coNghiDon;
            const disabledClass = isDisabled ? 'disabled' : '';
            
            let badgeInfo = '';
            let badgeStyle = '';
            
            if (lich.da_cham_cong == 1) {
                badgeInfo = '<small class="text-success"><i class="bi bi-check-circle"></i> Đã chấm công</small>';
            } else if (lich.da_qua == 1) {
                badgeInfo = '<small class="text-muted"><i class="bi bi-clock-history"></i> Đã qua</small>';
            } else if (coNghiDon) {
                badgeInfo = '<small class="text-warning"><i class="bi bi-calendar-x"></i> Nghỉ phép</small>';
                badgeStyle = 'border: 2px solid #ffc107;';
            } else if (coNghiLe) {
                badgeInfo = `<small class="text-danger"><i class="bi bi-gift"></i> ${lich.ly_do_nghi_le || 'Nghỉ lễ'}</small>`;
            } else if (coNghiTuan) {
                badgeInfo = `<small class="text-info"><i class="bi bi-calendar-week"></i> ${lich.ly_do_nghi_tuan || 'Nghỉ phép tuần'}</small>`;
            }
            
            const onclick = isDisabled ? '' : `onclick="chonCa(${lich.ma_lich}, ${nv.ma_nhan_vien}, '${d.ngay}', '${d.thu}')"`;
            const titleText = isDisabled ? 'Không thể đổi ca' : 'Click để chọn';
            
            html += `<td>
                <div class="shift-badge badge-ca-${lich.ma_ca} ${disabledClass}" 
                     id="cell-${lich.ma_lich}"
                     ${onclick}
                     title="${titleText}"
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
}


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
            if (selectedCells[0].thu !== thu) {
                Swal.fire('Thông báo', 'Chỉ được đổi ca cùng thứ trong tuần', 'warning');
                return;
            }
            
            if (selectedCells[0].ma_nhan_vien === maNhanVien) {
                Swal.fire('Thông báo', 'Không thể đổi ca của cùng 1 nhân viên', 'warning');
                return;
            }
        }
        
        cell.addClass('selected');
        selectedCells.push({
            id: `cell-${maLich}`,
            type: 'CA', // ✅ Đánh dấu là CA
            ma_lich: maLich,
            ma_nhan_vien: maNhanVien,
            ngay: ngay,
            thu: thu
        });
    }
    
    $('#so-luong-chon').text(selectedCells.length);
    $('#btn-doi-ca').prop('disabled', selectedCells.length !== 2);
}
// function huyChon() {
//     selectedCells.forEach(c => {
//         $(`#cell-${c.ma_lich}`).removeClass('selected');
//     });
//     selectedCells = [];
//     $('#so-luong-chon').text(0);
//     $('#btn-doi-ca').prop('disabled', true);
// }
function huyChon() {
    selectedCells.forEach(c => {
        // ✅ Xóa selected cho cả CA và OFF
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
    
    // ✅ Xác định loại đổi
    let messageHtml = '';
    if (ca1.type === 'CA' && ca2.type === 'CA') {
        messageHtml = `Đổi ca giữa 2 nhân viên vào <strong>${ca1.thu}</strong>?`;
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
            xuLyDoiCa();
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
        
        // Nếu đã chọn 1 ca, kiểm tra cùng thứ
        if (selectedCells.length === 1) {
            if (selectedCells[0].thu !== thu) {
                Swal.fire('Thông báo', 'Chỉ được đổi ca cùng thứ trong tuần', 'warning');
                return;
            }
            
            // Kiểm tra không phải cùng nhân viên
            if (selectedCells[0].ma_nhan_vien === maNhanVien) {
                Swal.fire('Thông báo', 'Không thể đổi ca của cùng 1 nhân viên', 'warning');
                return;
            }
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