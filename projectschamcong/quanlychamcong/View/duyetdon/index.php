<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt Đơn Xin Nghỉ - Resort Rosa Alba</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/basic.css">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/department.css">

    <style>
        :root {
            --primary-color: #0f4c81;
            --secondary-color: #1a6cb5;
            --light-bg: #f5f7fb;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
        }

        /* --- Layout --- */
      

        .content-area {
            padding: 30px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* --- Topbar --- */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }


        /* --- Widget Cards (Giống trang Profile) --- */
        .widget-card {
            width: 100%;
            background: #fff;
            border-radius: 16px;
            border: none;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            /* Để bo góc header không bị lòi ra */
            transition: transform 0.2s;
        }

        .widget-header {
            padding: 18px 25px;
            background: linear-gradient(135deg, #407a75 0%, #407a75 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .widget-header.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);
        }

        .widget-title {
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .widget-body {
            padding: 25px;
        }

        /* --- Table Styling --- */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #eaeaea;
            padding: 15px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 0.9rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fbff;
        }

        /* --- Custom Elements --- */
        .avatar-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0f4c81 0%, #2980b9 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 3px 6px rgba(15, 76, 129, 0.2);
        }

        .badge-soft {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .badge-soft.bg-success {
            background-color: #e8f5e9 !important;
            color: #1b5e20;
        }

        .badge-soft.bg-danger {
            background-color: #ffebee !important;
            color: #c62828;
        }

        .badge-soft.bg-secondary {
            background-color: #f5f5f5 !important;
            color: #616161;
        }

        /* --- Buttons --- */
        .btn-action {
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
        }

        .btn-approve {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(40, 167, 69, 0.2);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        .btn-reject {
            background: #fff;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn-reject:hover {
            background: #dc3545;
            color: white;
        }

        .btn-delete-hist {
            color: #adb5bd;
            transition: 0.2s;
        }

        .btn-delete-hist:hover {
            color: #dc3545;
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <?php require_once __DIR__ . '/../component/navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <?php require_once __DIR__ . '/../component/topbar.php'; ?>


        <?php if ($userRole != 'NHAN_SU'): ?>
            <div class="widget-card">
                <div class="widget-header">
                    <div class="widget-title">
                        <i class="fas fa-clock me-2"></i> Đơn Chờ Xử Lý
                        <span class="badge bg-white text-primary ms-2 rounded-pill"><?php echo count($topList); ?></span>
                    </div>
                </div>
                <div class="widget-body">
                    <?php if (empty($topList)): ?>
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty" width="80" style="opacity: 0.5; margin-bottom: 15px;">
                            <p class="text-muted fw-medium">Hiện không có yêu cầu nào cần xử lý.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead>
                                    <tr>
                                        <th>Nhân Viên</th>
                                        <th>Loại Nghỉ</th>
                                        <th>Thời Gian</th>
                                        <th>Số ngày nghỉ</th>
                                        <th>Lý Do</th>
                                        <th class="text-end" style="min-width: 180px;">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $mapLoaiNghi = [
                                        'NGHI_PHEP_THANG' => 'Nghỉ Phép Tháng',
                                        'NGHI_OM' => 'Nghỉ Ốm',
                                        'NGHI_VIEC_RIENG' => 'Việc Riêng',
                                        'NGHI_LE' => 'Nghỉ Lễ',
                                        'NGHI_PHEP_TUAN' => 'Nghỉ Phép Tuần',
                                        'NGHI_TET' => 'Nghỉ Tết'
                                    ];
                                    ?>
                                    <?php foreach ($topList as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        <?php echo substr($item['ho_ten'], 0, 1); ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?php echo $item['ho_ten']; ?></div>
                                                        <small class="text-muted"><i class="far fa-building me-1"></i><?php echo $item['ten_phong_ban']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class=""><?php echo isset($mapLoaiNghi[$item['loai_nghi']]) ? $mapLoaiNghi[$item['loai_nghi']] : $item['loai_nghi']; ?></span></td>
                                            <td>
                                                <div class="fw-medium text-dark">
                                                    <?php echo date('d/m', strtotime($item['ngay_bat_dau'])) . ' - ' . date('d/m', strtotime($item['ngay_ket_thuc'])); ?>
                                                </div>

                                            </td>

                                            <td>
                                                <div class="fw-medium text-dark">
                                                    <?php echo $item['so_ngay_nghi'] + 0; ?> ngày</small>
                                                </div>
                                            </td>
                                            <td class="text-muted" style="max-width: 250px;">

                                                <?php echo $item['ly_do']; ?>
                                            </td>

                                            <td class="text-end">
                                                <form action="?controller=duyetdon&action=process" method="POST" style="display:inline;">
                                                    <input type="hidden" name="ma_don" value="<?php echo $item['ma_don']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn-action btn-approve me-1" onclick="return confirm('Xác nhận duyệt đơn này?')">
                                                        Duyệt
                                                    </button>
                                                    <button type="button" class="btn-action btn-reject" onclick="openRejectModal(<?php echo $item['ma_don']; ?>, '<?php echo $item['ho_ten']; ?>')">
                                                        Từ Chối
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="widget-card">
            <div class="widget-header <?php echo ($userRole == 'NHAN_SU') ? 'bg-danger' : ''; ?>">
                <div class="widget-title">
                    <i class="fas fa-history me-2"></i>
                    <?php echo ($userRole == 'NHAN_SU') ? 'Danh Sách Đơn (HR View)' : 'Lịch Sử Duyệt Đơn'; ?>
                </div>
            </div>
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Họ Tên</th>
                                <th>Phòng Ban</th>
                                <th>Ngày Duyệt</th>
                                <th class="text-center">Trạng Thái</th>
                                <th>Loại Phép</th>
                                <th>Số Ngày</th>
                                <th class="text-center" style="min-width: 100px;">Thực hiện</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bottomListPaged as $hist): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo $hist['ho_ten']; ?></div>
                                    </td>
                                    <td><span class="text-muted small"><?php echo isset($hist['ten_phong_ban']) ? $hist['ten_phong_ban'] : '---'; ?></span></td>
                                    <td><?php echo ($hist['ngay_xu_ly']) ? date('d/m/Y H:i', strtotime($hist['ngay_xu_ly'])) : '---'; ?></td>
                                    <td class="text-center">
                                        <?php
                                        $statusClass = ['DA_DUYET' => 'bg-success', 'TU_CHOI' => 'bg-danger'];
                                        $statusLabel = ['DA_DUYET' => 'Đã Duyệt', 'TU_CHOI' => 'Từ Chối'];
                                        echo '<span class="badge badge-soft ' . ($statusClass[$hist['trang_thai']] ?? 'bg-secondary') . '">' . ($statusLabel[$hist['trang_thai']] ?? $hist['trang_thai']) . '</span>';
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        $mapLoaiNghi = [
                                            'NGHI_PHEP_THANG' => 'Nghỉ Phép Tháng',
                                            'NGHI_OM' => 'Nghỉ Ốm',
                                            'NGHI_VIEC_RIENG' => 'Việc Riêng',
                                            'NGHI_LE' => 'Nghỉ Lễ',
                                            'NGHI_PHEP_TUAN' => 'Nghỉ Phép Tuần',
                                            'NGHI_TET' => 'Nghỉ Tết'
                                        ];
                                        echo $mapLoaiNghi[$hist['loai_nghi']] ?? $hist['loai_nghi'];
                                        ?>
                                    </td>
                                    <td><span class="fw-bold text-dark"><?php echo $hist['so_ngay_nghi'] + 0; ?> ngày</span></td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-link p-0 me-2 text-primary"
                                            onclick='showDetailInline(<?php echo json_encode($hist); ?>)'
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <?php if ($userRole != 'NHAN_SU'): ?>
                                            <a href="?controller=duyetdon&action=delete&id=<?php echo $hist['ma_don']; ?>"
                                                class="btn btn-link btn-delete-hist p-0"
                                                onclick="return confirm('Bạn có chắc muốn xóa đơn này khỏi lịch sử?');"
                                                title="Xóa lịch sử">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=duyetdon&action=index&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=duyetdon&action=index&page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=duyetdon&action=index&page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

        <div id="detail-section" class="widget-card" style="display: none; margin-top: 25px; ">
        <div class="widget-header bg-white border-bottom">
            <div class="widget-title text-white">
                <i class="fas fa-info-circle me-2"></i> Chi Tiết Đơn Nghỉ
            </div>
            <button type="button" class="btn-close" onclick="closeDetailSection()" aria-label="Close"></button>
        </div>
        <div class="widget-body">
            <div class="row">
                <div class="col-md-4 border-end">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle me-3" style="width: 50px; height: 50px; font-size: 1.2rem;" id="inline_avatar">
                            A
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" id="inline_name">Tên Nhân Viên</h5>
                            <small class="text-muted" id="inline_dept">Phòng ban</small>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="small text-muted fw-bold mb-2">TRẠNG THÁI HIỆN TẠI</label>
                        <div id="inline_status"></div>
                    </div>
                </div>

                <div class="col-md-8 ps-md-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold">LOẠI NGHỈ</label>
                            <div id="inline_type" class="fw-medium text-black fs-5"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold">TỔNG SỐ NGÀY</label>
                            <div id="inline_days" class="fw-bold fs-5"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold">BẮT ĐẦU</label>
                            <div id="inline_start" class="text-dark"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold">KẾT THÚC</label>
                            <div id="inline_end" class="text-dark"></div>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded border mb-3">
                        <label class="small text-muted fw-bold mb-1">LÝ DO:</label>
                        <div id="inline_reason" class="fst-italic text-dark"></div>
                    </div>

                    <div id="inline_reject_area" style="display:none;">
                        <div class="bg-danger bg-opacity-10 p-3 rounded border border-danger">
                            <label class="small text-danger fw-bold mb-1"><i class="fas fa-exclamation-circle me-2"></i>LÝ DO TỪ CHỐI:</label>
                            <div id="inline_reject_reason" class="text-danger fw-bold"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3 pt-3 border-top">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeDetailSection()">Đóng lại</button>
            </div>
        </div>
    </div>


    </div>


    
    </div>
    </div>
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <form action="?controller=duyetdon&action=process" method="POST">
                    <div class="modal-header bg-danger text-white" style="border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Từ Chối Đơn</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="ma_don" id="modal_ma_don">
                        <input type="hidden" name="action" value="reject">
                        <p class="mb-3">Bạn đang từ chối đơn nghỉ của nhân viên: <strong id="modal_ten_nv" class="text-danger"></strong></p>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">LÝ DO TỪ CHỐI</label>
                            <textarea name="ly_do_tu_choi" class="form-control" rows="4" required placeholder="Vui lòng nhập lý do cụ thể..." style="background: #f8f9fa; border: 1px solid #e9ecef;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 pe-4">
                        <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-danger fw-bold px-4">Xác Nhận Từ Chối</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script>
       

        // Hàm mở Modal từ chối
        function openRejectModal(id, name) {
            document.getElementById('modal_ma_don').value = id;
            document.getElementById('modal_ten_nv').innerText = name;

            const modalElement = document.getElementById('rejectModal');

            if (!rejectModalInstance) {
                rejectModalInstance = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
            }
            rejectModalInstance.show();
        }

        // --- HÀM MỚI: HIỂN THỊ CHI TIẾT BÊN DƯỚI (INLINE) ---
        const mapLoaiNghi = {
            'NGHI_PHEP_THANG': 'Nghỉ Phép Tháng',
            'NGHI_OM': 'Nghỉ Ốm',
            'NGHI_VIEC_RIENG': 'Việc Riêng',
            'NGHI_LE': 'Nghỉ Lễ',
            'NGHI_PHEP_TUAN': 'Nghỉ Phép Tuần',
            'NGHI_TET': 'Nghỉ Tết'
        };

        function showDetailInline(data) {
            const section = document.getElementById('detail-section');

            // 1. Điền dữ liệu
            document.getElementById('inline_avatar').innerText = data.ho_ten.charAt(0);
            document.getElementById('inline_name').innerText = data.ho_ten;
            document.getElementById('inline_dept').innerText = data.ten_phong_ban || '---';

            document.getElementById('inline_type').innerText = mapLoaiNghi[data.loai_nghi] || data.loai_nghi;
            document.getElementById('inline_days').innerText = data.so_ngay_nghi + ' ngày';

            document.getElementById('inline_start').innerText = data.ngay_bat_dau;
            document.getElementById('inline_end').innerText = data.ngay_ket_thuc;
            document.getElementById('inline_reason').innerText = data.ly_do;

            // 2. Xử lý trạng thái (Đổi màu Badge và Viền khung)
            const statusElem = document.getElementById('inline_status');
            if (data.trang_thai === 'DA_DUYET') {
                statusElem.innerHTML = '<span class="badge bg-success px-3 py-2 rounded-pill">Đã Duyệt</span>';
                section.style.borderLeftColor = '#198754';
            } else if (data.trang_thai === 'TU_CHOI') {
                statusElem.innerHTML = '<span class="badge bg-danger px-3 py-2 rounded-pill">Đã Từ Chối</span>';
                section.style.borderLeftColor = '#dc3545';
            } else {
                statusElem.innerHTML = '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill">' + data.trang_thai + '</span>';
                section.style.borderLeftColor = '#ffc107';
            }

            // 3. Lý do từ chối
            const rejectArea = document.getElementById('inline_reject_area');
            if (data.trang_thai === 'TU_CHOI' && data.ly_do_tu_choi) {
                rejectArea.style.display = 'block';
                document.getElementById('inline_reject_reason').innerText = data.ly_do_tu_choi;
            } else {
                rejectArea.style.display = 'none';
            }

            // 4. Hiển thị và cuộn xuống
            section.style.display = 'block';
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function closeDetailSection() {
            document.getElementById('detail-section').style.display = 'none';
        }
    </script>
</body>

</html>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <form action="?controller=duyetdon&action=process" method="POST">
                <div class="modal-header bg-danger text-white" style="border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Từ Chối Đơn</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="ma_don" id="modal_ma_don">
                    <input type="hidden" name="action" value="reject">
                    <p class="mb-3">Bạn đang từ chối đơn nghỉ của nhân viên: <strong id="modal_ten_nv" class="text-danger"></strong></p>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">LÝ DO TỪ CHỐI</label>
                        <textarea name="ly_do_tu_choi" class="form-control" rows="4" required placeholder="Vui lòng nhập lý do cụ thể..." style="background: #f8f9fa; border: 1px solid #e9ecef;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 pe-4">
                    <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Xác Nhận Từ Chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
    let rejectModalInstance = null;

    function openRejectModal(id, name) {
        document.getElementById('modal_ma_don').value = id;
        document.getElementById('modal_ten_nv').innerText = name;

        const modalElement = document.getElementById('rejectModal');

        if (!rejectModalInstance) {
            rejectModalInstance = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
        }
        rejectModalInstance.show();
    }
</script>
</body>

</html>