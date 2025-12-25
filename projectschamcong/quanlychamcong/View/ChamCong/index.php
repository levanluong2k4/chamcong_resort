<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Chấm Công - Resort</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <style>
     

        .container {
            max-width: 1400px;
            margin: 0 auto;
           
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
           
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .controls {
            padding: 30px;
           
            border-bottom: 2px solid #e9ecef;
        }

        .control-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .control-item {
            flex: 1;
            min-width: 200px;
        }

        .control-item label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color:rgb(237, 240, 243);
        }

        .control-item input,
        .control-item select {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .control-item input:focus,
        .control-item select:focus {
            outline: none;
            border-color:rgb(214, 234, 102);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(89, 204, 133, 0.4);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            
        }

        .stat-card {
            
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-card .label {
            color:rgb(228, 233, 236);
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
            border: 1px solid #dee2e6;
            
        }

        th {
            
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
            border: 1px solid #dee2e6;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        tr{
            border-bottom: 1px solid #dee2e6;
        }

        tr:hover {
            background:rgba(76, 175, 117, 0.31);
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            
            color: #11eb43;
        }

        .badge-warning {
            
            color: #856404;
        }

        .badge-danger {
            
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-secondary {
            
            color:rgb(207, 211, 214);
        }

        .badge-purple {
            
            color: #6f42c1;
        }

        .time-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color:rgb(189, 191, 192);
        }

        .loading {
            text-align: center;
            padding: 40px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .note {
            font-size: 0.85em;
            color:rgb(181, 181, 182);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .control-group {
                flex-direction: column;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            th, td {
                padding: 10px;
                font-size: 0.9em;
            }
        }
        .search-box {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 12px 12px 45px !important;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-box::before {
        
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        pointer-events: none;
        z-index: 1;
    }

    /* ===== TABLE HEADER ===== */
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        flex-wrap: wrap;
        gap: 20px;
    }

    /* ===== PAGINATION CONTROLS ===== */
    .pagination-controls {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: rgb(228, 233, 236);
        font-size: 14px;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        white-space: nowrap;
    }

    /* ===== PAGE SIZE SELECTOR ===== */
    .page-size-selector {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-size-selector label {
        color: rgb(228, 233, 236) !important;
        margin-bottom: 0 !important;
        white-space: nowrap;
    }

    .page-size-selector select {
        padding: 8px 12px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 80px;
    }

    .page-size-selector select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* ===== PAGINATION BUTTONS ===== */
    .pagination {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .page-btn {
        padding: 10px 16px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        font-size: 14px;
        min-width: 45px;
        text-align: center;
    }

    .page-btn:hover:not(:disabled) {
        background: #667eea;
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .page-btn.active {
        background: #667eea;
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .page-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        background: rgba(255, 255, 255, 0.05);
    }

    .pagination span {
        color: rgb(228, 233, 236);
        padding: 0 8px;
    }

    /* ===== HIGHLIGHT SEARCH RESULTS ===== */
    .highlight {
        background-color: #ffd700;
        color: #000;
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: bold;
    }

    /* ===== BTN SECONDARY ===== */
    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .pagination-controls {
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
        }

        .pagination {
            width: 100%;
            justify-content: center;
        }

        .page-btn {
            padding: 8px 12px;
            font-size: 12px;
            min-width: 40px;
        }

        .search-box {
            width: 100%;
        }

        .pagination-info {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .page-btn {
            padding: 6px 10px;
            font-size: 11px;
            min-width: 35px;
        }

        .pagination {
            gap: 4px;
        }
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

    <div class="container">
        <div class="header">
            <h1> HỆ THỐNG CHẤM CÔNG RESORT</h1>
            <p>Quản lý chấm công nhân viên thông minh</p>
        </div>

        <div class="controls">
            <div class="control-group">
                <div class="control-item">
                    <label>📅 Chọn Ngày:</label>
                    <input type="date" id="dateInput" value="<?php echo date('Y-m-d'); ?>" />
                </div>
                <div class="control-item">
                    <label>🏢 Phòng Ban:</label>
                    <select id="departmentFilter">
                        <option value="">Tất cả phòng ban</option>
                    </select>
                </div>
                <div class="control-item">
                    <label>📊 Trạng Thái:</label>
                    <select id="statusFilter">
                        <option value="">Tất cả trạng thái</option>
                        <option value="DI_LAM">Đi làm</option>
                        <option value="DI_TRE">Đi trễ</option>
                        <option value="VANG_MAT">Vắng mặt</option>
                        <option value="NGHI_PHEP">Nghỉ phép</option>
                        <option value="QUEN_CHAM_CONG">Quên chấm công</option>
                    </select>
                </div>
              
                <div class="control-item" style="padding-top: 30px;">
                    <button class="btn btn-primary" onclick="loadAttendance()"> Xem Chấm Công</button>
                </div>
                <div class="control-item " style="padding-top: 30px;">
                    <button class="btn btn-secondary" onclick="resetFilters()"> Đặt lại</button>
                </div>
            </div>
        </div>

        <div class="stats" id="statsContainer" style="display: none;">
            <div class="stat-card">
                <div class="label">Tổng Nhân Viên</div>
                <div class="number" style="color: #667eea;" id="totalEmployees">0</div>
            </div>
            <div class="stat-card">
                <div class="label">Đi Làm</div>
                <div class="number" style="color: #28a745;" id="presentCount">0</div>
            </div>
            <div class="stat-card">
                <div class="label">Đi Trễ</div>
                <div class="number" style="color: #ffc107;" id="lateCount">0</div>
            </div>
            <div class="stat-card">
                <div class="label">Vắng Mặt</div>
                <div class="number" style="color: #dc3545;" id="absentCount">0</div>
            </div>
            <div class="stat-card">
                <div class="label">Nghỉ Phép</div>
                <div class="number" style="color: #17a2b8;" id="leaveCount">0</div>
            </div>
            <div class="stat-card">
                <div class="label">Quên Chấm Công</div>
                <div class="number" style="color: #6f42c1;" id="forgotCount">0</div>
            </div>
        </div>

        <div class="table-container">
      
            <div id="loadingState" class="loading" style="display: none;">
                <div class="spinner"></div>
                <p style="margin-top: 20px;">Đang tải dữ liệu...</p>
            </div>
          
            <div id="tableContent">
           


            </div>
        </div>
    </div>

    <script>
    // Global variables
    let allData = [];
    let filteredData = [];
    let currentPage = 1;
    let pageSize = 10;
    let searchTerm = '';
    let configData = {};

    // Load danh sách phòng ban khi trang được tải
    window.addEventListener('load', function() {
        loadDepartments();
        loadAttendance();
    });

    // Load danh sách phòng ban
    async function loadDepartments() {
        try {
            const response = await fetch('?controller=ChamCong&action=getPhongBan');
            const result = await response.json();
            
            if (result.success) {
                const select = document.getElementById('departmentFilter');
                result.data.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.ma_phong_ban;
                    option.textContent = dept.ten_phong_ban;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Lỗi load phòng ban:', error);
        }
    }

    // Load dữ liệu chấm công
    async function loadAttendance() {
        const selectedDate = document.getElementById('dateInput').value;
        const departmentFilter = document.getElementById('departmentFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;

        if (!selectedDate) {
            alert('Vui lòng chọn ngày!');
            return;
        }

        // Show loading
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('tableContent').innerHTML = '';
        document.getElementById('statsContainer').style.display = 'none';

        try {
            let url = `?controller=ChamCong&action=getDuLieuChamCong&ngay=${selectedDate}`;
            if (departmentFilter) {
                url += `&ma_phong_ban=${departmentFilter}`;
            }

            const response = await fetch(url);
            const result = await response.json();

            if (result.success) {
                allData = result.data;
                configData = result.config;

                // Apply filters
                applyFilters(statusFilter);

                // Update statistics
                updateStatistics(result.statistics);

                // Reset to page 1
                currentPage = 1;

                // Render table
                renderTable(selectedDate);

                document.getElementById('statsContainer').style.display = 'grid';
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Lỗi:', error);
            alert('Lỗi: ' + error.message);
        } finally {
            document.getElementById('loadingState').style.display = 'none';
        }
    }

    // Apply filters
    function applyFilters(statusFilter) {
        filteredData = allData;

        // Filter by status
        if (statusFilter) {
            filteredData = filteredData.filter(item => item.trang_thai === statusFilter);
        }

        // Filter by search term
        if (searchTerm) {
            const term = searchTerm.toLowerCase();
            filteredData = filteredData.filter(item => {
                return (
                    item.ho_ten.toLowerCase().includes(term) ||
                    item.ma_nhan_vien.toString().includes(term) ||
                    item.email.toLowerCase().includes(term) ||
                    item.ten_phong_ban.toLowerCase().includes(term)
                );
            });
        }
    }

    // Handle search
    function handleSearch() {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) return;
        
        searchTerm = searchInput.value;
        const statusFilter = document.getElementById('statusFilter').value;
        const selectedDate = document.getElementById('dateInput').value;

        applyFilters(statusFilter);
        currentPage = 1;
        renderTable(selectedDate);
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('dateInput').value = '<?php echo date('Y-m-d'); ?>';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('statusFilter').value = '';
        
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = '';
        }
        
        searchTerm = '';
        currentPage = 1;
        loadAttendance();
    }

    // Update statistics
    function updateStatistics(stats) {
        document.getElementById('totalEmployees').textContent = stats.tong_nhan_vien || 0;
        document.getElementById('presentCount').textContent = stats.di_lam || 0;
        document.getElementById('lateCount').textContent = stats.di_tre || 0;
        document.getElementById('absentCount').textContent = stats.vang_mat || 0;
        document.getElementById('leaveCount').textContent = stats.nghi_phep || 0;
        document.getElementById('forgotCount').textContent = stats.quen_cham_cong || 0;
    }

    // Get status badge
    function getStatusBadge(status, lateMinutes, checkInTime, checkOutTime) {
        if (status === 'QUEN_CHAM_CONG') {
            if (checkInTime && !checkOutTime) {
                return '<span class="badge badge-purple"> Quên chấm công RA</span>';
            } else if (!checkInTime && checkOutTime) {
                return '<span class="badge badge-purple"> Quên chấm công VÀO</span>';
            } else {
                return '<span class="badge badge-purple"> Quên chấm công</span>';
            }
        }
        
        const badges = {
            'DI_LAM': '<span class="badge badge-success"> Đi làm đúng giờ</span>',
            'DI_TRE': '<span class="badge badge-warning"> Đi trễ ' + lateMinutes + ' phút</span>',
            'VE_SOM': '<span class="badge badge-warning"> Về sớm</span>',
            'VANG_MAT': '<span class="badge badge-danger"> Vắng mặt</span>',
            'NGHI_PHEP': '<span class="badge badge-info"> Nghỉ phép</span>'
        };
        
        return badges[status] || '<span class="badge badge-secondary">' + status + '</span>';
    }

    // Highlight search term
    function highlightText(text, term) {
        if (!term || !text) return text;
        const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return text.toString().replace(regex, '<span class="highlight">$1</span>');
    }

    // Change page
    function changePage(page) {
        currentPage = page;
        const selectedDate = document.getElementById('dateInput').value;
        renderTable(selectedDate);
        
        // Scroll to top of table
        document.getElementById('tableContent').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Change page size
    function changePageSize() {
        const pageSizeSelect = document.getElementById('pageSizeSelect');
        if (!pageSizeSelect) return;
        
        pageSize = parseInt(pageSizeSelect.value);
        currentPage = 1;
        const selectedDate = document.getElementById('dateInput').value;
        renderTable(selectedDate);
    }

    // Render table with pagination
    function renderTable(date) {
        const container = document.getElementById('tableContent');

        if (filteredData.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div style="font-size: 4em; margin-bottom: 20px;"></div>
                    <h3>Không có dữ liệu</h3>
                    <p>Không tìm thấy nhân viên nào theo bộ lọc đã chọn</p>
                </div>
            `;
            return;
        }

        // Calculate pagination
        const totalPages = Math.ceil(filteredData.length / pageSize);
        const startIndex = (currentPage - 1) * pageSize;
        const endIndex = Math.min(startIndex + pageSize, filteredData.length);
        const paginatedData = filteredData.slice(startIndex, endIndex);

        const dayOfWeek = ['Chủ Nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'][new Date(date).getDay()];

        let html = `
            <div class="table-header">
                <div>
                    <h3 style="color:rgb(255, 255, 255); margin-bottom: 5px;"> Bảng Chấm Công - ${dayOfWeek}, ${new Date(date).toLocaleDateString('vi-VN')}</h3>
                    <p class="note">Thời gian được phép trễ: ${configData.SO_PHUT_DUOC_PHEP_TRE} phút | Được phép về sớm: ${configData.SO_PHUT_DUOC_PHEP_VE_SOM} phút</p>
                </div>
                <div class="pagination-controls">
                   
                    <div class="page-size-selector">
                        <label>Hiển thị:</label>
                        <select id="pageSizeSelect" onchange="changePageSize()">
                            <option value="10" ${pageSize === 10 ? 'selected' : ''}>10</option>
                            <option value="25" ${pageSize === 25 ? 'selected' : ''}>25</option>
                            <option value="50" ${pageSize === 50 ? 'selected' : ''}>50</option>
                            <option value="100" ${pageSize === 100 ? 'selected' : ''}>100</option>
                        </select>
                    </div>
                    <div class="search-box">
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Tìm theo tên, mã NV, email..."
                            value="${searchTerm}"
                            onkeyup="handleSearch()"
                        />
                    </div>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã NV</th>
                        <th>Họ Tên</th>
                        <th>Phòng Ban</th>
                        <th>Ca Làm</th>
                        <th>Giờ Vào Ca</th>
                        <th>Giờ Ra Ca</th>
                        <th>Giờ Chấm Vào</th>
                        <th>Giờ Chấm Ra</th>
                        <th>Tổng Giờ</th>
                        <th>Trạng Thái</th>
                        <th>Ghi Chú</th>
                    </tr>
                </thead>
                <tbody>
        `;

        paginatedData.forEach((item, index) => {
            const globalIndex = startIndex + index + 1;
            html += `
                <tr>
                    <td>${globalIndex}</td>
                    <td><strong>#${highlightText(item.ma_nhan_vien.toString(), searchTerm)}</strong></td>
                    <td>${highlightText(item.ho_ten, searchTerm)}</td>
                    <td>${highlightText(item.ten_phong_ban, searchTerm)}</td>
                    <td><span class="badge badge-secondary">${item.ten_ca}</span></td>
                    <td class="time-display">${item.gio_bat_dau}</td>
                    <td class="time-display">${item.gio_ket_thuc}</td>
                    <td class="time-display" style="${!item.gio_vao ? 'color: #dc3545;' : ''}">${item.gio_vao || ' Chưa chấm'}</td>
                    <td class="time-display" style="${!item.gio_ra ? 'color: #dc3545;' : ''}">${item.gio_ra || ' Chưa chấm'}</td>
                    <td class="time-display">${item.tong_gio_lam ? item.tong_gio_lam + 'h' : '---'}</td>
                    <td>${getStatusBadge(item.trang_thai, item.so_phut_tre, item.gio_vao, item.gio_ra)}</td>
                    <td class="note">${item.ghi_chu || ''}</td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

        // Add pagination controls
        if (totalPages > 1) {
            html += `
                <div style="margin-top: 20px; display: flex; justify-content: center; align-items: center; padding: 20px;">
                    <div class="pagination">
                        <button class="page-btn" onclick="changePage(1)" ${currentPage === 1 ? 'disabled' : ''}>
                            ⏮️ Đầu
                        </button>
                        <button class="page-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                            ◀️ Trước
                        </button>
            `;

            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage < maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            if (startPage > 1) {
                html += `<button class="page-btn" onclick="changePage(1)">1</button>`;
                if (startPage > 2) {
                    html += `<span>...</span>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">
                        ${i}
                    </button>
                `;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<span>...</span>`;
                }
                html += `<button class="page-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;
            }

            html += `
                        <button class="page-btn" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                            Sau ▶️
                        </button>
                        <button class="page-btn" onclick="changePage(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''}>
                            Cuối ⏭️
                        </button>
                    </div>
                </div>
            `;
        }

        container.innerHTML = html;
    }
</script>
</body>
</html>