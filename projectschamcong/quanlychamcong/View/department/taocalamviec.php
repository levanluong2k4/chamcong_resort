<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Rosa Alba - Department Manager Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/ShiftSchedule.css">
</head>

</head>
<body>
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../component/navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php require_once __DIR__ . '/../component/topbar.php'; ?>
        
       
        
        <div class="container">
            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-btn active" onclick="switchTab('create')">
                    <i class="fas fa-calendar-plus"></i> Tạo Lịch Tự Động
                </button>
                <button class="tab-btn" onclick="switchTab('view')">
                    <i class="fas fa-calendar-alt"></i> Xem & Đổi Ca
                </button>
            </div>
            
            <div id="createTab" class="tab-content active">
    <div class="create-panel">
        <h3><i class="fas fa-magic"></i> Tạo Lịch Làm Việc Tự Động</h3>
        
        <div id="createMessage"></div>
        
        <form id="createScheduleForm">
            <!-- Thông tin cơ bản -->
            <div class="form-group">
                <label><i class="fas fa-cog"></i> Loại Tạo Lịch:</label>
                <select id="loai_tao" onchange="updateFormByType()">
                    <option value="thang">Tạo theo Tháng</option>
                    <option value="tuan">Tạo theo Tuần</option>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Tháng:</label>
                    <select id="create_thang" required>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>" <?= ($i == date('m')) ? 'selected' : '' ?>>Tháng <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Năm:</label>
                    <select id="create_nam" required>
                        <?php for ($i = date('Y'); $i <= date('Y') + 2; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            
            <!-- Chọn tuần (chỉ hiện khi chọn tạo theo tuần) -->
            <div class="form-group" id="tuan_group" style="display: none;">
                <label><i class="fas fa-calendar-week"></i> Chọn Tuần:</label>
                <select id="create_tuan">
                    <option value="1">Tuần 1 (Ngày 1-7)</option>
                    <option value="2">Tuần 2 (Ngày 8-14)</option>
                    <option value="3">Tuần 3 (Ngày 15-21)</option>
                    <option value="4">Tuần 4 (Ngày 22-28)</option>
                    <option value="5">Tuần 5 (Ngày 29+)</option>
                </select>
            </div>
            
            <!-- Cấu hình ca theo ngày -->
            <div class="shift-config">
                <h4 class="text-light"><i class="fas fa-cog"></i> Cấu Hình Ca Theo Ngày</h4>
                <p style="font-size: 13px; color:rgb(239, 241, 243); margin-bottom: 15px;">
                    Mỗi ngày có thể có nhiều ca, mỗi ca chọn số lượng nhân viên cần thiết
                </p>
                
                <div id="dayConfigsContainer">
                    <!-- Chủ Nhật -->
                    <div class="day-config-box sunday-config" data-day="0">
                        <div class="day-header">
                            <div class="day-name">🔴 Chủ Nhật</div>
                            <button type="button" class="add-shift-btn" onclick="addShiftToDay(0)">
                                <i class="fas fa-plus"></i> Thêm Ca
                            </button>
                        </div>
                        <div class="shifts-list" id="shifts_0">
                            <div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>
                        </div>
                    </div>

                    <!-- Thứ Hai -->
                    <div class="day-config-box" data-day="1">
                        <div class="day-header">
                            <div class="day-name">📅 Thứ Hai</div>
                            <button type="button" class="add-shift-btn" onclick="addShiftToDay(1)">
                                <i class="fas fa-plus"></i> Thêm Ca
                            </button>
                        </div>
                        <div class="shifts-list" id="shifts_1">
                            <div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>
                        </div>
                    </div>

                    <!-- Thứ Ba -->
                    <div class="day-config-box" data-day="2">
                        <div class="day-header">
                            <div class="day-name">📅 Thứ Ba</div>
                            <button type="button" class="add-shift-btn" onclick="addShiftToDay(2)">
                                <i class="fas fa-plus"></i> Thêm Ca
                            </button>
                        </div>
                        <div class="shifts-list" id="shifts_2">
                            <div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>
                        </div>
                    </div>

                    <!-- Thứ Tư -->
                    <div class="day-config-box" data-day="3">
                        <div class="day-header">
                            <div class="day-name">📅 Thứ Tư</div>
                            <button type="button" class="add-shift-btn" onclick="addShiftToDay(3)">
                                <i class="fas fa-plus"></i> Thêm Ca
                            </button>
                        </div>
                        <div class="shifts-list" id="shifts_3">
                            <div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>
                        </div>
                    </div>

                    <!-- Thứ Năm -->
                    <div class="day-config-box" data-day="4">
                        <div class="day-header">
                            <div class="day-name">📅 Thứ Năm</div>
                            <button type="button" class="add-shift-btn" onclick="addShiftToDay(4)">
                                <i class="fas fa-plus"></i> Thêm Ca
                            </button>
                        </div>
                        <div class="shifts-list" id="shifts_4">
                            <div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>
                        </div>
                    </div>

                    <!-- Thứ Sáu -->
                    <div class="day-config-box" data-day="5">
                        <div class="day-header">
                            <div class="day-name">📅 Thứ Sáu</div>
                            <button type="button" class="add-shift-btn" onclick="addShiftToDay(5)">
                                <i class="fas fa-plus"></i> Thêm Ca
                            </button>
                        </div>
                        <div class="shifts-list" id="shifts_5">
                            <div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>
                        </div>
                    </div>

                    <!-- Thứ Bảy -->
                    <div class="day-config-box" data-day="6">
                        <div class="day-header">
                            <div class="day-name">📅 Thứ Bảy</div>
                            <button type="button" class="add-shift-btn" onclick="addShiftToDay(6)">
                                <i class="fas fa-plus"></i> Thêm Ca
                            </button>
                        </div>
                        <div class="shifts-list" id="shifts_6">
                            <div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chọn nhân viên nghỉ cố định -->
            <div class="shift-config">
                <h4 class="text-light"><i class="fas fa-user-times"></i> Nhân Viên Nghỉ Cố Định (Theo Đơn Xin Phép)</h4>
                <p style="font-size: 13px; color:rgb(227, 230, 233); margin-bottom: 15px;">
                    Chọn nhân viên và ngày họ đã xin phép nghỉ. Hệ thống sẽ không phân ca cho họ vào ngày đó.
                </p>
                
                <div class="employee-grid">
                    <?php foreach ($nhan_viens as $nv): ?>
                    <div class="employee-card">
                        <input type="checkbox" class="emp-checkbox" data-ma="<?= $nv['ma_nhan_vien'] ?>" onchange="toggleEmployeeDate(this)">
                        <span class="emp-name"><?= $nv['ho_ten'] ?> (<?= $nv['vai_tro'] ?>)</span>
                        <input type="date" class="emp-date" data-ma="<?= $nv['ma_nhan_vien'] ?>" disabled>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
         
            
            <div class="action-buttons">
                <button type="submit" class="btn-create btn-primary">
                    <i class="fas fa-magic"></i> Tạo Lịch Tự Động
                </button>
            </div>
        </form>
    </div>
</div>
            
            <!-- Tab 2: Xem & Đổi Ca -->
            <div id="viewTab" class="tab-content">
                <div class="controls">
                    <div class="controls-row">
                        <select id="monthSelect">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= ($i == date('m')) ? 'selected' : '' ?>>
                                    Tháng <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        
                        <select id="yearSelect">
                            <?php for ($i = date('Y'); $i <= date('Y') + 2; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        
                        <button onclick="loadSchedule()"><i class="fas fa-sync-alt"></i> Tải lại</button>
                        <button onclick="clearSelection()"><i class="fas fa-times"></i> Bỏ chọn</button>
                        
                        <div style="margin-left: auto; color: #667eea; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> <span id="selectedCount">0</span> nhân viên được chọn
                        </div>
                    </div>
                </div>
                
                <div class="calendar-container">
                    <div class="calendar-header">
                        <div class="day-name">Chủ Nhật</div>
                        <div class="day-name">Thứ Hai</div>
                        <div class="day-name">Thứ Ba</div>
                        <div class="day-name">Thứ Tư</div>
                        <div class="day-name">Thứ Năm</div>
                        <div class="day-name">Thứ Sáu</div>
                        <div class="day-name">Thứ Bảy</div>
                    </div>
                    
                    <div id="calendarDays" class="calendar-days">
                        <div class="loading">
                            <i class="fas fa-spinner"></i>
                            <p style="margin-top: 15px;">Đang tải lịch...</p>
                        </div>
                    </div>
                    
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #fff5f5; border: 2px solid #ff6b6b;"></div>
                            <span>Chủ Nhật</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f0f4ff; border: 2px solid #667eea;"></div>
                            <span>Đã chọn</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: white; border: 2px solid #51cf66;"></div>
                            <span>Hôm nay</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Actions -->
        <div id="floatingActions" class="floating-actions">
            <h3>⚙️ Hành động</h3>
            <div id="selectedInfo" class="selected-info"></div>
            
            <button class="action-btn btn-change" onclick="openChangeModal()">
                <i class="fas fa-exchange-alt"></i> Đổi ca cho nhân viên đã chọn
            </button>
            <button class="action-btn btn-swap" onclick="openSwapModal()" id="swapBtn" disabled>
                <i class="fas fa-random"></i> Hoán đổi ca giữa 2 nhân viên
            </button>
            <button class="action-btn btn-cancel" onclick="clearSelection()">
                <i class="fas fa-times"></i> Hủy bỏ
            </button>
        </div>
        
        <!-- Modal đổi ca -->
        <div id="changeModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-exchange-alt"></i> Đổi Ca Làm Việc</h2>
                    <button class="close-btn" onclick="closeModal('changeModal')">&times;</button>
                </div>
                
                <div id="changeMessage"></div>
                
                <form id="changeForm">
                    <div class="form-group">
                        <label>Đổi sang ca:</label>
                        <select id="new_shift" required>
                            <option value="">-- Chọn ca mới --</option>
                            <?php foreach ($shifts as $s): ?>
                                <option value="<?= $s['ma_ca'] ?>">
                                    <?= $s['ten_ca'] ?> (<?= substr($s['gio_bat_dau'], 0, 5) ?> - <?= substr($s['gio_ket_thuc'], 0, 5) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Lý do đổi ca:</label>
                        <textarea id="change_reason" required placeholder="Nhập lý do đổi ca..." style="min-height: 100px;"></textarea>
                    </div>
                    
                    <div style="text-align: right; display: flex; gap: 10px;">
                        <button type="button" class="action-btn btn-cancel" onclick="closeModal('changeModal')">Hủy</button>
                        <button type="submit" class="action-btn btn-change"><i class="fas fa-save"></i> Xác nhận đổi</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal hoán đổi ca -->
        <div id="swapModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-random"></i> Hoán Đổi Ca</h2>
                    <button class="close-btn" onclick="closeModal('swapModal')">&times;</button>
                </div>
                
                <div id="swapMessage"></div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="margin-bottom: 15px;">
                        <p style="font-size: 14px; margin-bottom: 5px;"><strong>Nhân viên 1:</strong> <span id="emp1Name"></span></p>
                        <p style="font-size: 13px; color: #667eea;">Ca hiện tại: <span id="emp1Shift"></span></p>
                    </div>
                    
                    <div style="text-align: center; margin: 15px 0;">
                        <i class="fas fa-exchange-alt" style="font-size: 24px; color: #667eea;"></i>
                    </div>
                    
                    <div>
                        <p style="font-size: 14px; margin-bottom: 5px;"><strong>Nhân viên 2:</strong> <span id="emp2Name"></span></p>
                        <p style="font-size: 13px; color: #667eea;">Ca hiện tại: <span id="emp2Shift"></span></p>
                    </div>
                </div>
                
                <form id="swapForm">
                    <div class="form-group">
                        <label>Lý do hoán đổi:</label>
                        <textarea id="swap_reason" required placeholder="Nhập lý do hoán đổi ca..." style="min-height: 100px;"></textarea>
                    </div>
                    
                    <div style="text-align: right; display: flex; gap: 10px;">
                        <button type="button" class="action-btn btn-cancel" onclick="closeModal('swapModal')">Hủy</button>
                        <button type="submit" class="action-btn btn-swap"><i class="fas fa-save"></i> Xác nhận hoán đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
// Danh sách ca (sẽ được load từ PHP)
const shiftsData = <?= json_encode($shifts) ?>;
let shiftCounter = 0;

// Khởi tạo: Thêm 1 ca mặc định cho các ngày thường (trừ CN)
document.addEventListener('DOMContentLoaded', function() {
    // Thêm 1 ca sáng mặc định cho T2-T7
    for (let day = 1; day <= 6; day++) {
        addShiftToDay(day, 1, 2); // Ca Sáng (ma_ca=1), 2 nhân viên
    }
    document.getElementById('createScheduleForm').addEventListener('submit', handleCreateSchedule);
});

// Thêm ca cho một ngày
function addShiftToDay(dayIndex, defaultShift = 1, defaultCount = 2) {
    const container = document.getElementById(`shifts_${dayIndex}`);
    
    // Xóa thông báo "Chưa có ca nào" nếu đây là ca đầu tiên
    const emptyMsg = container.querySelector('.empty-shifts');
    if (emptyMsg) {
        emptyMsg.remove();
    }
    
    shiftCounter++;
    const shiftId = `shift_${shiftCounter}`;
    
    // Tạo options cho select
    let shiftOptions = '';
    shiftsData.forEach(s => {
        const selected = (s.ma_ca == defaultShift) ? 'selected' : '';
        shiftOptions += `<option value="${s.ma_ca}" ${selected}>${s.ten_ca} (${s.gio_bat_dau.substring(0,5)} - ${s.gio_ket_thuc.substring(0,5)})</option>`;
    });
    
    // Tạo HTML cho shift row
    const shiftRow = document.createElement('div');
    shiftRow.className = 'shift-row';
    shiftRow.id = shiftId;
    shiftRow.innerHTML = `
        <select class="shift-select" required>
            ${shiftOptions}
        </select>
        <input type="number" class="shift-count" min="1" value="${defaultCount}" placeholder="Số nhân viên" required>
        <button type="button" class="remove-shift-btn" onclick="removeShift('${shiftId}')">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    container.appendChild(shiftRow);
}

// Xóa ca
function removeShift(shiftId) {
    const shiftRow = document.getElementById(shiftId);
    const container = shiftRow.parentElement;
    
    shiftRow.remove();
    
    // Nếu không còn ca nào, hiển thị lại thông báo
    if (container.querySelectorAll('.shift-row').length === 0) {
        container.innerHTML = '<div class="empty-shifts">Chưa có ca nào. Nhấn "Thêm Ca" để bắt đầu.</div>';
    }
}

// Toggle date input khi check nhân viên
function toggleEmployeeDate(checkbox) {
    const card = checkbox.closest('.employee-card');
    const dateInput = card.querySelector('.emp-date');
    dateInput.disabled = !checkbox.checked;
    if (!checkbox.checked) {
        dateInput.value = '';
    }
}

// Xử lý thay đổi loại tạo lịch
function updateFormByType() {
    const loaiTao = document.getElementById('loai_tao').value;
    const tuanGroup = document.getElementById('tuan_group');
    
    if (loaiTao === 'tuan') {
        tuanGroup.style.display = 'block';
    } else {
        tuanGroup.style.display = 'none';
    }
}

// Xử lý submit form
async function handleCreateSchedule(e) {
    e.preventDefault();
    
    const loaiTao = document.getElementById('loai_tao').value;
    const thang = document.getElementById('create_thang').value;
    const nam = document.getElementById('create_nam').value;
    const tuan = (loaiTao === 'tuan') ? document.getElementById('create_tuan').value : null;
    
    // Thu thập cấu hình ca theo ngày
    const dayConfigs = {};
    for (let day = 0; day <= 6; day++) {
        const container = document.getElementById(`shifts_${day}`);
        const shiftRows = container.querySelectorAll('.shift-row');
        
        dayConfigs[day] = [];
        shiftRows.forEach(row => {
            const maCa = parseInt(row.querySelector('.shift-select').value);
            const soNV = parseInt(row.querySelector('.shift-count').value);
            dayConfigs[day].push({ ma_ca: maCa, so_nv: soNV });
        });
    }
    
    // Thu thập nhân viên nghỉ cố định
    const nhanVienNghiCoDinh = [];
    document.querySelectorAll('.emp-checkbox:checked').forEach(cb => {
        const maNV = cb.dataset.ma;
        const dateInput = cb.closest('.employee-card').querySelector('.emp-date');
        if (dateInput.value) {
            nhanVienNghiCoDinh.push({
                ma_nhan_vien: parseInt(maNV),
                ngay: dateInput.value
            });
        }
    });
    
    const requestData = {
        loai_tao: loaiTao,
        thang: parseInt(thang),
        nam: parseInt(nam),
        tuan: tuan ? parseInt(tuan) : null,
        day_configs: dayConfigs,
        nhan_vien_nghi_co_dinh: nhanVienNghiCoDinh
    };
    
    console.log('📤 Dữ liệu gửi lên server:', requestData);
    
    // Confirm trước khi tạo
    if (!confirm(`Bạn có chắc muốn tạo lịch ${loaiTao === 'thang' ? 'tháng' : 'tuần'} ${loaiTao === 'tuan' ? tuan + ' ' : ''}${thang}/${nam}?\n\nLịch cũ (nếu có) sẽ bị xóa và thay thế!`)) {
        return;
    }
    
    // Show loading
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo lịch...';
    btn.disabled = true;
    
    try {
        const response = await fetch('?controller=ShiftSchedule&action=tao-lich-tu-dong', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('createMessage').innerHTML = 
                `<div class="alert alert-success">
                    <strong><i class="fas fa-check-circle"></i> Thành công!</strong><br>
                    ${result.message}
                </div>`;
            
            // Reset form sau 2s và chuyển sang tab xem
            setTimeout(() => {
                document.getElementById('createScheduleForm').reset();
                document.getElementById('createMessage').innerHTML = '';
                document.querySelector('.tab-btn:nth-child(2)').click();
            }, 2000);
        } else {
            document.getElementById('createMessage').innerHTML = 
                `<div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-circle"></i> Lỗi!</strong><br>
                    ${result.message}
                </div>`;
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('createMessage').innerHTML = 
            `<div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-circle"></i> Lỗi!</strong><br>
                Không thể kết nối đến server: ${error.message}
            </div>`;
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}


let scheduleData = {};
let selectedShifts = [];

// Thêm vào DOMContentLoaded (sau phần khởi tạo ca mặc định)
document.getElementById('monthSelect').addEventListener('change', loadSchedule);
document.getElementById('yearSelect').addEventListener('change', loadSchedule);

const changeForm = document.getElementById('changeForm');
if (changeForm) {
    changeForm.addEventListener('submit', handleChangeSubmit);
}

const swapForm = document.getElementById('swapForm');
if (swapForm) {
    swapForm.addEventListener('submit', handleSwapSubmit);
}

// Chuyển tab
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.tab-btn').classList.add('active');
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    if (tab === 'create') {
        document.getElementById('createTab').classList.add('active');
    } else {
        document.getElementById('viewTab').classList.add('active');
        loadSchedule();
    }
}

// Load lịch
async function loadSchedule() {
    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('yearSelect').value;
    
    try {
        const response = await fetch(`?controller=ShiftSchedule&action=xem-lich-phong-ban&thang=${month}&nam=${year}`);
        const result = await response.json();
        
        if (result.success) {
            scheduleData = {};
            result.data.forEach(item => {
                if (!scheduleData[item.ngay_lam]) scheduleData[item.ngay_lam] = [];
                scheduleData[item.ngay_lam].push(item);
            });
            renderCalendar(month, year);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderCalendar(month, year) {
    const firstDay = new Date(year, month - 1, 1);
    const lastDay = new Date(year, month, 0);
    const daysInMonth = lastDay.getDate();
    const startDayOfWeek = firstDay.getDay();
    const container = document.getElementById('calendarDays');
    container.innerHTML = '';
    const today = new Date();
    
    for (let i = 0; i < startDayOfWeek; i++) container.innerHTML += '<div></div>';
    
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const currentDate = new Date(year, month - 1, day);
        const dayOfWeek = currentDate.getDay();
        const isSunday = dayOfWeek === 0;
        const isToday = (today.getMonth() + 1 == month && today.getFullYear() == year && today.getDate() === day);
        
        let cellClass = 'day-cell';
        if (isSunday) cellClass += ' sunday';
        if (isToday) cellClass += ' today';
        
        const employees = scheduleData[dateStr] || [];
        let employeesHTML = '';
        employees.forEach(emp => {
            employeesHTML += `
                <div class="employee-shift" data-ma-lich="${emp.ma_lich}" data-ma-nhan-vien="${emp.ma_nhan_vien}" 
                     data-ho-ten="${emp.ho_ten}" data-ten-ca="${emp.ten_ca}" data-ngay="${dateStr}" onclick="toggleSelect(this)">
                    <div class="emp-name">${emp.ho_ten}</div>
                    <div class="emp-shift">${emp.ten_ca}</div>
                    <div class="emp-time">${emp.gio_bat_dau.substring(0, 5)} - ${emp.gio_ket_thuc.substring(0, 5)}</div>
                </div>
            `;
        });
        
        container.innerHTML += `
            <div class="${cellClass}">
                <div class="day-number">${day}</div>
                ${employeesHTML || '<div style="color: #999; font-size: 12px; text-align: center; margin-top: 20px;">Không có ca</div>'}
            </div>
        `;
    }
}

function toggleSelect(element) {
    const maLich = element.dataset.maLich;
    const index = selectedShifts.findIndex(s => s.ma_lich == maLich);
    
    if (index > -1) {
        selectedShifts.splice(index, 1);
        element.classList.remove('selected');
    } else {
        if (selectedShifts.length >= 2) {
            alert('⚠️ Chỉ được chọn tối đa 2 nhân viên!');
            return;
        }
        selectedShifts.push({
            ma_lich: maLich,
            ma_nhan_vien: element.dataset.maNhanVien,
            ho_ten: element.dataset.hoTen,
            ten_ca: element.dataset.tenCa,
            ngay: element.dataset.ngay
        });
        element.classList.add('selected');
    }
    updateFloatingActions();
}

function updateFloatingActions() {
    const count = selectedShifts.length;
    document.getElementById('selectedCount').textContent = count;
    const floating = document.getElementById('floatingActions');
    const swapBtn = document.getElementById('swapBtn');
    
    if (count > 0) {
        floating.classList.add('show');
        let infoHTML = '<strong>Đã chọn:</strong><br>';
        selectedShifts.forEach(s => infoHTML += `• ${s.ho_ten} - ${s.ten_ca} (${s.ngay})<br>`);
        document.getElementById('selectedInfo').innerHTML = infoHTML;
        swapBtn.disabled = (count !== 2);
    } else {
        floating.classList.remove('show');
    }
}

function clearSelection() {
    document.querySelectorAll('.employee-shift.selected').forEach(el => el.classList.remove('selected'));
    selectedShifts = [];
    updateFloatingActions();
}

function openChangeModal() {
    if (selectedShifts.length === 0) { alert('⚠️ Vui lòng chọn ít nhất 1 nhân viên!'); return; }
    document.getElementById('changeModal').classList.add('show');
}

async function handleChangeSubmit(e) {
    e.preventDefault();
    const new_shift = document.getElementById('new_shift').value;
    const reason = document.getElementById('change_reason').value;
    
    for (const shift of selectedShifts) {
        await fetch('?controller=ShiftSchedule&action=doi-ca', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ma_lich: shift.ma_lich, ma_ca_moi: new_shift, ly_do: reason })
        });
    }
    
    setTimeout(() => { closeModal('changeModal'); clearSelection(); loadSchedule(); }, 1500);
}

function openSwapModal() {
    if (selectedShifts.length !== 2) { alert('⚠️ Vui lòng chọn đúng 2 nhân viên!'); return; }
    document.getElementById('emp1Name').textContent = selectedShifts[0].ho_ten;
    document.getElementById('emp1Shift').textContent = selectedShifts[0].ten_ca;
    document.getElementById('emp2Name').textContent = selectedShifts[1].ho_ten;
    document.getElementById('emp2Shift').textContent = selectedShifts[1].ten_ca;
    document.getElementById('swapModal').classList.add('show');
}

async function handleSwapSubmit(e) {
    e.preventDefault();
    const reason = document.getElementById('swap_reason').value;
    
    await fetch('?controller=ShiftSchedule&action=doi-ca-2nv', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            ma_lich_1: selectedShifts[0].ma_lich,
            ma_lich_2: selectedShifts[1].ma_lich,
            ma_nhan_vien1: selectedShifts[0].ma_nhan_vien,
            ma_nhan_vien2: selectedShifts[1].ma_nhan_vien,
            ly_do: reason
        })
    });
    
    setTimeout(() => { closeModal('swapModal'); clearSelection(); loadSchedule(); }, 1500);
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
    if (modalId === 'changeModal') document.getElementById('changeForm').reset();
    else if (modalId === 'swapModal') document.getElementById('swapForm').reset();
}
</script>
</body>
</html>