<?php
require_once __DIR__ . '/../Connect.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class XuatLichTuanExcel extends Connect {
    
    /**
     * Lấy dữ liệu lịch làm việc theo thứ
     */
    public function layLichTheoThu($thu2, $ma_phong_ban, $thu_trong_tuan) {
      
        $ngay_offset = $thu_trong_tuan - 2;
        $ngay_lam = date('Y-m-d', strtotime($thu2 . " +$ngay_offset days"));
        
        $sql = "SELECT 
                    llv.ma_lich,
                    llv.ma_nhan_vien,
                    nv.ho_ten,
                    nv.email,
                    llv.ma_ca,
                    ca.ten_ca,
                    ca.gio_bat_dau,
                    ca.gio_ket_thuc,
                    ca.he_so_luong,
                    llv.ngay_lam,
                    llv.ghi_chu,
                    pb.ten_phong_ban
                FROM lichlamviec llv
                INNER JOIN nhanvien nv ON llv.ma_nhan_vien = nv.ma_nhan_vien
                INNER JOIN calamviec ca ON llv.ma_ca = ca.ma_ca
                INNER JOIN phongban pb ON nv.ma_phong_ban = pb.ma_phong_ban
                WHERE nv.ma_phong_ban = $ma_phong_ban
                AND llv.ngay_lam = '$ngay_lam'
                AND nv.trang_thai = 'DANG_LAM'
                ORDER BY ca.gio_bat_dau ASC, nv.ho_ten ASC";
        
        $result = $this->select($sql);
        $data = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    /**
     * Xuất file Excel cho một thứ
     */
    public function xuatExcelTheoThu($thu2, $ma_phong_ban, $thu_trong_tuan) {
        if (ob_get_length()) ob_clean();
        ob_start();
        $ngay_offset = $thu_trong_tuan - 2;
        $ngay_lam = date('Y-m-d', strtotime($thu2 . " +$ngay_offset days"));
        $ten_thu = $this->layTenThu($thu_trong_tuan);
        
        $lich = $this->layLichTheoThu($thu2, $ma_phong_ban, $thu_trong_tuan);
        
        if (empty($lich)) {
            return [
                'success' => false,
                'message' => "Không có dữ liệu lịch làm việc cho $ten_thu"
            ];
        }
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $ten_phong_ban = $lich[0]['ten_phong_ban'];
        
        // Tiêu đề
        $sheet->setCellValue('A1', 'LỊCH LÀM VIỆC ' . strtoupper($ten_thu));
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thông tin
        $sheet->setCellValue('A2', 'Phòng ban: ' . $ten_phong_ban);
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('E2', 'Ngày: ' . date('d/m/Y', strtotime($ngay_lam)));
        $sheet->mergeCells('E2:G2');
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        
        // Header
        $headers = ['STT', 'Mã NV', 'Họ Tên', 'Email', 'Ca Làm', 'Giờ', 'Ghi Chú'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A4:G4')->applyFromArray($headerStyle);
        
        // Nhóm theo ca
        $grouped = [];
        foreach ($lich as $row) {
            $ma_ca = $row['ma_ca'];
            if (!isset($grouped[$ma_ca])) {
                $grouped[$ma_ca] = [
                    'ten_ca' => $row['ten_ca'],
                    'gio' => $row['gio_bat_dau'] . ' - ' . $row['gio_ket_thuc'],
                    'nhan_vien' => []
                ];
            }
            $grouped[$ma_ca]['nhan_vien'][] = $row;
        }
        
        // Điền dữ liệu
        $rowNum = 5;
        $stt = 1;
        
        foreach ($grouped as $ma_ca => $ca_data) {
            foreach ($ca_data['nhan_vien'] as $nv) {
                $sheet->setCellValue('A' . $rowNum, $stt);
                $sheet->setCellValue('B' . $rowNum, $nv['ma_nhan_vien']);
                $sheet->setCellValue('C' . $rowNum, $nv['ho_ten']);
                $sheet->setCellValue('D' . $rowNum, $nv['email']);
                $sheet->setCellValue('E' . $rowNum, $ca_data['ten_ca']);
                $sheet->setCellValue('F' . $rowNum, $ca_data['gio']);
                $sheet->setCellValue('G' . $rowNum, $nv['ghi_chu'] ?? '');
                
                $stt++;
                $rowNum++;
            }
            
            if ($rowNum < count($lich) + 5) {
                $sheet->getStyle('A' . ($rowNum - 1) . ':G' . ($rowNum - 1))
                      ->getBorders()->getBottom()
                      ->setBorderStyle(Border::BORDER_MEDIUM);
            }
        }
        
        // Style dữ liệu
        $dataRange = 'A5:G' . ($rowNum - 1);
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ];
        $sheet->getStyle($dataRange)->applyFromArray($dataStyle);
        
        $sheet->getStyle('A5:B' . ($rowNum - 1))
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Độ rộng cột
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(25);
        
        // Tổng kết
        $rowNum++;
        $sheet->setCellValue('A' . $rowNum, 'Tổng số nhân viên: ' . ($stt - 1));
        $sheet->mergeCells('A' . $rowNum . ':G' . $rowNum);
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
        
        // Tên file
        $ngay_format = date('Ymd', strtotime($ngay_lam));
        $ten_file = "Lich_{$ten_thu}_{$ngay_format}.xlsx";
        
        // Xuất file
        $writer = new Xlsx($spreadsheet);
        if (ob_get_length()) ob_end_clean();
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $ten_file . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Xuất tất cả các thứ (ZIP)
     */
    public function xuatTatCaCacThu($thu2, $ma_phong_ban) {
        $files = [];
        $temp_dir = sys_get_temp_dir() . '/lich_tuan_' . time();
        
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777, true);
        }
        
        for ($thu = 2; $thu <= 7; $thu++) {
            $lich = $this->layLichTheoThu($thu2, $ma_phong_ban, $thu);
            
            if (!empty($lich)) {
                $ngay_offset = $thu - 2;
                $ngay_lam = date('Y-m-d', strtotime($thu2 . " +$ngay_offset days"));
                $ten_thu = $this->layTenThu($thu);
                $ngay_format = date('Ymd', strtotime($ngay_lam));
                
                $file_path = $temp_dir . "/Lich_{$ten_thu}_{$ngay_format}.xlsx";
                $this->taoFileExcel($lich, $ten_thu, $ngay_lam, $file_path);
                $files[] = $file_path;
            }
        }
        
        if (empty($files)) {
            return ['success' => false, 'message' => 'Không có dữ liệu'];
        }
        
        if (count($files) == 1) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . basename($files[0]) . '"');
            readfile($files[0]);
            unlink($files[0]);
            rmdir($temp_dir);
            exit;
        }
        
        $zip_file = $temp_dir . '/Lich_Tuan_' . date('Ymd', strtotime($thu2)) . '.zip';
        $zip = new ZipArchive();
        
        if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
            
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment;filename="' . basename($zip_file) . '"');
            readfile($zip_file);
            
            foreach ($files as $file) {
                unlink($file);
            }
            unlink($zip_file);
            rmdir($temp_dir);
            exit;
        }
        
        return ['success' => false, 'message' => 'Không thể tạo ZIP'];
    }
    
    /**
     * Tạo file Excel
     */
    private function taoFileExcel($lich, $ten_thu, $ngay_lam, $file_path) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $ten_phong_ban = $lich[0]['ten_phong_ban'];
        
        $sheet->setCellValue('A1', 'LỊCH LÀM VIỆC ' . strtoupper($ten_thu));
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'Phòng ban: ' . $ten_phong_ban);
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('E2', 'Ngày: ' . date('d/m/Y', strtotime($ngay_lam)));
        $sheet->mergeCells('E2:G2');
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        
        $headers = ['STT', 'Mã NV', 'Họ Tên', 'Email', 'Ca Làm', 'Giờ', 'Ghi Chú'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle('A4:G4')->applyFromArray($headerStyle);
        
        $rowNum = 5;
        $stt = 1;
        foreach ($lich as $row) {
            $sheet->setCellValue('A' . $rowNum, $stt);
            $sheet->setCellValue('B' . $rowNum, $row['ma_nhan_vien']);
            $sheet->setCellValue('C' . $rowNum, $row['ho_ten']);
            $sheet->setCellValue('D' . $rowNum, $row['email']);
            $sheet->setCellValue('E' . $rowNum, $row['ten_ca']);
            $sheet->setCellValue('F' . $rowNum, $row['gio_bat_dau'] . ' - ' . $row['gio_ket_thuc']);
            $sheet->setCellValue('G' . $rowNum, $row['ghi_chu'] ?? '');
            $stt++;
            $rowNum++;
        }
        
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(25);
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($file_path);
    }
    
    /**
     * Lấy tên thứ
     */
    private function layTenThu($thu) {
        $ten_thu_map = [
            2 => 'Thu_2',
            3 => 'Thu_3',
            4 => 'Thu_4',
            5 => 'Thu_5',
            6 => 'Thu_6',
            7 => 'Thu_7'
        ];
        return $ten_thu_map[$thu] ?? 'Thu_' . $thu;
    }
}
?>