<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Công - Tính Lương</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .stat-card i {
            font-size: 2rem;
            opacity: 0.8;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .badge-success-custom {
            background: #10b981;
            color: white;
        }
        .badge-warning-custom {
            background: #f59e0b;
            color: white;
        }
        .badge-danger-custom {
            background: #ef4444;
            color: white;
        }
        .badge-info-custom {
            background: #3b82f6;
            color: white;
        }
        .badge-purple-custom {
            background: #8b5cf6;
            color: white;
        }
    </style>
</head>
<body>

    <?php require_once __DIR__ . '/../component/navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <?php require_once __DIR__ . '/../component/topbar.php'; ?>
        <div class="container-fluid">
            <!-- Header -->
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">
                        <i class="fas fa-chart-bar text-primary"></i>
                        Thống Kê Công - Nhân Viên
                    </h2>
                    
                    <!-- Bộ lọc -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Phòng Ban</label>
                            <select id="phongBanFilter" class="form-select">
                                <option value="all">Tất cả phòng ban</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tháng</label>
                            <select id="thangFilter" class="form-select">
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == date('m') ? 'selected' : '' ?>>
                                        Tháng <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Năm</label>
                            <select id="namFilter" class="form-select">
                                <?php for($y = 2024; $y <= 2026; $y++): ?>
                                    <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>>
                                        <?= $y ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button id="btnTimKiem" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Thống Kê
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button id="btnXuatExcel" class="btn btn-success w-100">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng thống kê chi tiết -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Bảng chấm công nhân viên</h5>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" class="align-middle">STT</th>
                                    <th rowspan="2" class="align-middle">Họ Tên</th>
                                    <th rowspan="2" class="align-middle">Phòng Ban</th>
                                    <th rowspan="2" class="align-middle">Tổng Ngày</th>
                                    <th colspan="6" class="text-center bg-success">Ngày Đi Làm (Tính Công)</th>
                                    <th colspan="3" class="text-center bg-danger">Ngày Không Đi (Trừ Công)</th>
                                    <th rowspan="2" class="align-middle">Tổng Giờ</th>
                                </tr>
                                <tr>
                                    <th class="bg-success bg-opacity-75">Tổng</th>
                                    <th class="bg-info bg-opacity-50">Đúng Giờ</th>
                                    <th class="bg-warning bg-opacity-50">Đi Trễ</th>
                                    <th class="bg-warning bg-opacity-50">Về Sớm</th>
                                    <th class="bg-purple bg-opacity-50" style="background-color: #8b5cf6 !important;">Ngày Lễ</th>
                                    <th class="bg-secondary bg-opacity-50">Quên Chấm</th>
                                    <th class="bg-danger bg-opacity-75">Vắng Mặt</th>
                                    <th class="bg-primary bg-opacity-50">Nghỉ Phép</th>
                                    <th class="bg-dark bg-opacity-50">Nghỉ Phép Đơn</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="14" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            loadPhongBan();
            loadThongKe();
            
            $('#btnTimKiem').click(function() {
                loadThongKe();
            });
            
            $('#btnXuatExcel').click(function() {
                xuatExcel();
            });
        });
        
        function loadPhongBan() {
            $.ajax({
                url: '?controller=ThongKeCong&action=getPhongBan',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let html = '<option value="all">Tất cả phòng ban</option>';
                        response.data.forEach(function(pb) {
                            html += `<option value="${pb.ma_phong_ban}">${pb.ten_phong_ban}</option>`;
                        });
                        $('#phongBanFilter').html(html);
                    }
                }
            });
        }
        
        function loadThongKe() {
            const ma_phong_ban = $('#phongBanFilter').val();
            const thang = $('#thangFilter').val();
            const nam = $('#namFilter').val();
            
            $.ajax({
                url: '?controller=ThongKeCong&action=thongKeCong',
                method: 'POST',
                data: {
                    ma_phong_ban: ma_phong_ban,
                    thang: thang,
                    nam: nam
                },
                success: function(response) {
                    if (response.success) {
                        renderTable(response.data);
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi tải dữ liệu!');
                }
            });
        }
        
        function renderTable(data) {
            let html = '';
            
            if (data.length === 0) {
                html = '<tr><td colspan="14" class="text-center">Không có dữ liệu</td></tr>';
            } else {
                data.sort(function(a, b) {
                    return parseFloat(b.tong_gio_lam) - parseFloat(a.tong_gio_lam);
                });
                
                data.forEach(function(item, index) {
                    const gioTre = (item.tong_phut_tre / 60).toFixed(1);
                    const gioSom = (item.tong_phut_ve_som / 60).toFixed(1);
                    const rowClass = index < 3 ? 'table-success' : '';
                    
                    html += `
                        <tr class="${rowClass}">
                            <td class="text-center">${index + 1}</td>
                            <td>
                                <strong>${item.ho_ten}</strong>
                                <br><small class="text-muted">${item.email}</small>
                            </td>
                            <td>${item.ten_phong_ban}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">${item.tong_ngay_co_lich}</span>
                            </td>
                            
                            <!-- NGÀY ĐI LÀM (TÍNH CÔNG) -->
                            <td class="text-center bg-success bg-opacity-10">
                                <span class="badge badge-success-custom fs-6">${item.so_ngay_di_lam}</span>
                                <br><small class="text-muted">ngày công</small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info-custom">${item.so_ngay_dung_gio}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning-custom">${item.so_ngay_di_tre}</span>
                                ${item.so_ngay_di_tre > 0 ? '<br><small class="text-warning">(' + gioTre + 'h)</small>' : ''}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning-custom">${item.so_ngay_ve_som}</span>
                                ${item.so_ngay_ve_som > 0 ? '<br><small class="text-warning">(' + gioSom + 'h)</small>' : ''}
                            </td>
                            
                            <!-- ⭐ MỚI: Ngày làm ngày lễ -->
                            <td class="text-center bg-purple bg-opacity-10">
                                <span class="badge badge-purple-custom">${item.so_ngay_di_lam_ngay_le || 0}</span>
                                ${item.so_ngay_di_lam_ngay_le > 0 ? '<br><small class="text-muted">Ngày lễ</small>' : ''}
                            </td>
                            
                            <!-- ⭐ MỚI: Quên chấm công -->
                            <td class="text-center bg-secondary bg-opacity-10">
                                <span class="badge bg-secondary">${item.so_ngay_quen_cham || 0}</span>
                                ${item.so_ngay_quen_cham > 0 ? '<br><small class="text-muted">Vẫn tính công</small>' : ''}
                            </td>
                            
                            <!-- NGÀY KHÔNG ĐI (TRỪ CÔNG) -->
                            <td class="text-center bg-danger bg-opacity-10">
                                <span class="badge badge-danger-custom">${item.so_ngay_vang_mat}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">${item.so_ngay_nghi_phep}</span>
                                ${item.so_ngay_nghi_phep > 0 ? '<br><small class="text-muted">Có phép</small>' : ''}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark">${item.so_ngay_nghi_phep_don || 0}</span>
                            </td>
                            
                            <!-- TỔNG GIỜ -->
                            <td class="text-center">
                                <strong class="text-primary fs-5">${item.tong_gio_lam}h</strong>
                                ${index < 3 ? '<br><small class="text-success fw-bold">⭐ TOP ' + (index + 1) + '</small>' : ''}
                            </td>
                        </tr>
                    `;
                });
            }
            
            $('#tableBody').html(html);
        }
        
        function xuatExcel() {
            const ma_phong_ban = $('#phongBanFilter').val();
            const thang = $('#thangFilter').val();
            const nam = $('#namFilter').val();
            
            window.location.href = `?controller=ThongKeCong&action=xuatExcel&ma_phong_ban=${ma_phong_ban}&thang=${thang}&nam=${nam}`;
        }
    </script>
</body>
</html>