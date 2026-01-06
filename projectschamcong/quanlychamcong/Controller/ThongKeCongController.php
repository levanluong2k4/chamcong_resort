<?php
// ===================================================================
// File: Controller/LichCoDinhController.php
// ===================================================================

require_once __DIR__ . '/../Model/tinhcong/ThongKeCongModel.php';


class ThongKeCongController {
    
    private $model;
    
    public function __construct() {
        $this->model = new ThongKeCongModel();
    }
    
    // Hiển thị trang thống kê
    public function index() {
        include __DIR__ . '/../View/ThongKeCong/index.php';
    }
    
    // Lấy danh sách phòng ban
    public function getPhongBan() {
        $data = $this->model->getAllPhongBan();
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
    }
    
    // Thống kê công theo phòng ban và tháng
    public function thongKeCong() {
        $ma_phong_ban = $_POST['ma_phong_ban'] ?? 'all';
        $thang = $_POST['thang'] ?? date('m');
        $nam = $_POST['nam'] ?? date('Y');
        
        $data = $this->model->thongKeCongTheoThang($ma_phong_ban, $thang, $nam);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'thang' => $thang,
            'nam' => $nam,
            'data' => $data,
            'tong_nhan_vien' => count($data)
        ], JSON_UNESCAPED_UNICODE);
    }
    
    // Thống kê tổng hợp theo phòng ban
    public function thongKeTheoPhongBan() {
        $thang = $_POST['thang'] ?? date('m');
        $nam = $_POST['nam'] ?? date('Y');
        
        $data = $this->model->thongKeTongHopTheoPhongBan($thang, $nam);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
    }
    
    // Xuất Excel thống kê công
    public function xuatExcel() {
        // Kiểm tra PhpSpreadsheet đã cài chưa
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            die('Vui lòng cài đặt PhpSpreadsheet: composer require phpoffice/phpspreadsheet');
        }
        
        $ma_phong_ban = $_GET['ma_phong_ban'] ?? 'all';
        $thang = $_GET['thang'] ?? date('m');
        $nam = $_GET['nam'] ?? date('Y');
        
        // Lấy dữ liệu
        $data = $this->model->thongKeCongTheoThang($ma_phong_ban, $thang, $nam);
        
        // Tạo file Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'BẢNG THỐNG KÊ CÔNG THÁNG ' . $thang . '/' . $nam);
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Tiêu đề cột - Hàng 1
        $headers1 = ['STT', 'Họ Tên', 'Phòng Ban', 'Tổng Ngày', 'Ngày Đi Làm (Tính Công)', '', '', '', 'Ngày Không Đi (Trừ Công)', '', '', 'Tổng Giờ', 'Tỷ Lệ'];
        $col = 'A';
        foreach ($headers1 as $header) {
            $sheet->setCellValue($col . '3', $header);
            $col++;
        }
        
        // Merge cells cho tiêu đề nhóm
        $sheet->mergeCells('E3:H3'); // Ngày đi làm
        $sheet->mergeCells('I3:K3'); // Ngày không đi
        
        // Tiêu đề cột - Hàng 2
        $headers2 = ['', '', '', '', 'Tổng', 'Đúng Giờ', 'Đi Trễ', 'Về Sớm', 'Vắng Mặt', 'Nghỉ Phép', 'Quên Chấm', '', ''];
        $col = 'A';
        foreach ($headers2 as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        
        // Merge các ô cột 1-4 cho 2 hàng tiêu đề
        $sheet->mergeCells('A3:A4');
        $sheet->mergeCells('B3:B4');
        $sheet->mergeCells('C3:C4');
        $sheet->mergeCells('D3:D4');
        $sheet->mergeCells('L3:L4');
        $sheet->mergeCells('M3:M4');
        
        // Style cho header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
        ];
        $sheet->getStyle('A3:M4')->applyFromArray($headerStyle);
        
        // Dữ liệu
        $row = 5;
        $stt = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $stt++);
            $sheet->setCellValue('B' . $row, $item['ho_ten']);
            $sheet->setCellValue('C' . $row, $item['ten_phong_ban']);
            $sheet->setCellValue('D' . $row, $item['tong_ngay_co_lich']);
            $sheet->setCellValue('E' . $row, $item['so_ngay_di_lam']);
            $sheet->setCellValue('F' . $row, $item['so_ngay_dung_gio']);
            $sheet->setCellValue('G' . $row, $item['so_ngay_di_tre']);
            $sheet->setCellValue('H' . $row, $item['so_ngay_ve_som']);
            $sheet->setCellValue('I' . $row, $item['so_ngay_vang_mat']);
            $sheet->setCellValue('J' . $row, $item['so_ngay_nghi_phep']);
            $sheet->setCellValue('K' . $row, $item['so_ngay_quen_cham']);
            $sheet->setCellValue('L' . $row, $item['tong_gio_lam']);
            $sheet->setCellValue('M' . $row, $item['ty_le_di_lam'] . '%');
            $row++;
        }
        
        // Auto size columns
        foreach(range('A','M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Border cho toàn bộ bảng
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ]
        ];
        $sheet->getStyle('A3:M' . ($row-1))->applyFromArray($styleArray);
        
        // Xuất file
        $filename = 'thong_ke_cong_thang_' . $thang . '_' . $nam . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
?>
    
   