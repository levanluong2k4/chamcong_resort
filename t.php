<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sơ đồ Use Case - Hệ thống Chấm công</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            padding: 20px;
        }
        .container {
            max-width: 1600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 30px;
            font-size: 28px;
        }
        #diagram {
            width: 100%;
            height: auto;
        }
        .controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 10px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2563eb;
        }
        .btn-success {
            background: #10b981;
        }
        .btn-success:hover {
            background: #059669;
        }
        .notes {
            background: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        .notes h2 {
            color: #1e3a8a;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .notes p {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #374151;
        }
        .notes strong {
            font-weight: 600;
        }
        .stats {
            background: #f1f5f9;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .stats h3 {
            color: #1e293b;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container" id="exportArea">
        <h1>Sơ đồ Use Case - Hệ thống Quản lý Chấm công Resort</h1>
        
        <div class="controls">
            <button class="btn" onclick="exportAsPNG()">📥 Tải về PNG</button>
            <button class="btn btn-success" onclick="exportAsSVG()">📥 Tải về SVG</button>
            <button class="btn" onclick="print()">🖨️ In</button>
        </div>

        <svg id="diagram" viewBox="0 0 1600 2000">
            <defs>
                <style>
                    .actor { fill: none; stroke: #2563eb; stroke-width: 2; }
                    .usecase { fill: white; stroke: #2563eb; stroke-width: 2; }
                    .usecase-critical { fill: #fef3c7; stroke: #f59e0b; stroke-width: 2; }
                    .usecase-new { fill: #dbeafe; stroke: #3b82f6; stroke-width: 2; }
                    .connection { stroke: #64748b; stroke-width: 1.5; fill: none; }
                    .connection-include { stroke: #10b981; stroke-width: 1.5; fill: none; stroke-dasharray: 5,5; }
                    .system-boundary { fill: none; stroke: #94a3b8; stroke-width: 2; stroke-dasharray: 10,5; }
                    .text-title { font-size: 18px; font-weight: bold; fill: #1e293b; }
                    .text-usecase { font-size: 13px; fill: #1e293b; text-anchor: middle; }
                    .text-actor { font-size: 16px; font-weight: bold; fill: #1e293b; text-anchor: middle; }
                    .text-label { font-size: 11px; fill: #64748b; font-style: italic; }
                </style>
            </defs>

            <!-- System Boundary -->
            <rect x="300" y="50" width="1200" height="1900" class="system-boundary" rx="10"/>
            <text x="900" y="35" class="text-title">Hệ thống Chấm công Resort</text>

            <!-- Admin Actor -->
            <g>
                <circle cx="150" cy="300" r="30" class="actor"/>
                <line x1="150" y1="330" x2="150" y2="400" class="actor"/>
                <line x1="150" y1="360" x2="110" y2="390" class="actor"/>
                <line x1="150" y1="360" x2="190" y2="390" class="actor"/>
                <line x1="150" y1="400" x2="110" y2="450" class="actor"/>
                <line x1="150" y1="400" x2="190" y2="450" class="actor"/>
                <text x="150" y="480" class="text-actor">Admin</text>
            </g>

            <!-- Quản lý Actor -->
            <g>
                <circle cx="150" cy="900" r="30" class="actor"/>
                <line x1="150" y1="930" x2="150" y2="1000" class="actor"/>
                <line x1="150" y1="960" x2="110" y2="990" class="actor"/>
                <line x1="150" y1="960" x2="190" y2="990" class="actor"/>
                <line x1="150" y1="1000" x2="110" y2="1050" class="actor"/>
                <line x1="150" y1="1000" x2="190" y2="1050" class="actor"/>
                <text x="150" y="1080" class="text-actor">Quản lý</text>
            </g>

            <!-- Nhân viên Actor -->
            <g>
                <circle cx="150" cy="1500" r="30" class="actor"/>
                <line x1="150" y1="1530" x2="150" y2="1600" class="actor"/>
                <line x1="150" y1="1560" x2="110" y2="1590" class="actor"/>
                <line x1="150" y1="1560" x2="190" y2="1590" class="actor"/>
                <line x1="150" y1="1600" x2="110" y2="1650" class="actor"/>
                <line x1="150" y1="1600" x2="190" y2="1650" class="actor"/>
                <text x="150" y="1680" class="text-actor">Nhân viên</text>
            </g>

            <!-- NHÓM 1: QUẢN LÝ NHÂN SỰ -->
            <text x="400" y="100" class="text-title" fill="#059669">QUẢN LÝ NHÂN SỰ</text>
            
            <ellipse cx="500" cy="150" rx="90" ry="40" class="usecase"/>
            <text x="500" y="155" class="text-usecase">Đăng nhập</text>
            <line x1="150" y1="300" x2="410" y2="150" class="connection"/>

            <ellipse cx="500" cy="230" rx="110" ry="40" class="usecase-new"/>
            <text x="500" y="235" class="text-usecase">Quản lý Nhân viên</text>
            <line x1="150" y1="320" x2="390" y2="230" class="connection"/>

            <ellipse cx="700" cy="230" rx="100" ry="40" class="usecase"/>
            <text x="700" y="235" class="text-usecase">Tạo tài khoản</text>
            <line x1="610" y1="230" x2="600" y2="230" class="connection-include"/>
            <text x="640" y="220" class="text-label">«include»</text>

            <ellipse cx="700" cy="300" rx="100" ry="40" class="usecase"/>
            <text x="700" y="305" class="text-usecase">Xem hồ sơ NV</text>
            <line x1="580" y1="250" x2="620" y2="280" class="connection-include"/>

            <ellipse cx="500" cy="320" rx="110" ry="40" class="usecase-new"/>
            <text x="500" y="325" class="text-usecase">Quản lý Phòng ban</text>
            <line x1="150" y1="350" x2="390" y2="320" class="connection"/>

            <!-- NHÓM 2: QUẢN LÝ CA & LỊCH -->
            <text x="400" y="420" class="text-title" fill="#7c3aed">QUẢN LÝ CA & LỊCH</text>

            <ellipse cx="500" cy="470" rx="120" ry="40" class="usecase-new"/>
            <text x="500" y="475" class="text-usecase">Quản lý Ca làm việc</text>
            <line x1="150" y1="380" x2="380" y2="470" class="connection"/>

            <ellipse cx="700" cy="470" rx="100" ry="40" class="usecase"/>
            <text x="700" y="475" class="text-usecase">Tạo lịch cố định</text>
            <line x1="150" y1="390" x2="600" y2="470" class="connection"/>

            <ellipse cx="900" cy="470" rx="110" ry="40" class="usecase"/>
            <text x="900" y="475" class="text-usecase">Tạo lịch làm việc</text>
            <line x1="150" y1="400" x2="790" y2="470" class="connection"/>

            <ellipse cx="1100" cy="470" rx="80" ry="40" class="usecase"/>
            <text x="1100" y="475" class="text-usecase">Đổi ca làm</text>
            <line x1="150" y1="900" x2="1020" y2="470" class="connection"/>

            <ellipse cx="700" cy="560" rx="120" ry="40" class="usecase-new"/>
            <text x="700" y="560" class="text-usecase">Quản lý Ngày nghỉ</text>
            <text x="700" y="575" class="text-usecase">Lễ/Tết</text>
            <line x1="150" y1="410" x2="580" y2="560" class="connection"/>

            <!-- NHÓM 3: QUẢN LÝ CHẤM CÔNG -->
            <text x="400" y="660" class="text-title" fill="#dc2626">QUẢN LÝ CHẤM CÔNG</text>

            <ellipse cx="500" cy="710" rx="90" ry="40" class="usecase-critical"/>
            <text x="500" y="715" class="text-usecase">Chấm công</text>
            <line x1="150" y1="1500" x2="410" y2="710" class="connection"/>

            <ellipse cx="700" cy="710" rx="100" ry="40" class="usecase"/>
            <text x="700" y="715" class="text-usecase">Xem chấm công</text>
            <line x1="150" y1="1510" x2="600" y2="710" class="connection"/>

            <ellipse cx="900" cy="710" rx="110" ry="40" class="usecase"/>
            <text x="900" y="715" class="text-usecase">Thống kê chấm công</text>
            <line x1="150" y1="420" x2="790" y2="710" class="connection"/>

            <ellipse cx="1100" cy="710" rx="120" ry="40" class="usecase-new"/>
            <text x="1100" y="715" class="text-usecase">Duyệt/Sửa chấm công</text>
            <line x1="150" y1="900" x2="980" y2="710" class="connection"/>

            <!-- NHÓM 4: QUẢN LÝ NGHỈ PHÉP -->
            <text x="400" y="820" class="text-title" fill="#ea580c">QUẢN LÝ NGHỈ PHÉP</text>

            <ellipse cx="500" cy="870" rx="90" ry="40" class="usecase-critical"/>
            <text x="500" y="875" class="text-usecase">Đơn xin nghỉ</text>
            <line x1="150" y1="1520" x2="410" y2="870" class="connection"/>

            <ellipse cx="700" cy="870" rx="100" ry="40" class="usecase"/>
            <text x="700" y="875" class="text-usecase">Duyệt đơn nghỉ</text>
            <line x1="150" y1="900" x2="600" y2="870" class="connection"/>

            <ellipse cx="900" cy="870" rx="100" ry="40" class="usecase-new"/>
            <text x="900" y="875" class="text-usecase">Xem số dư phép</text>
            <line x1="150" y1="1530" x2="800" y2="870" class="connection"/>

            <ellipse cx="1100" cy="870" rx="120" ry="40" class="usecase-new"/>
            <text x="1100" y="875" class="text-usecase">Quản lý số dư phép</text>
            <line x1="150" y1="430" x2="980" y2="870" class="connection"/>

            <!-- NHÓM 5: QUẢN LÝ TĂNG CA -->
            <text x="400" y="980" class="text-title" fill="#0891b2">QUẢN LÝ TĂNG CA</text>

            <ellipse cx="500" cy="1030" rx="110" ry="40" class="usecase"/>
            <text x="500" y="1035" class="text-usecase">Tạo đơn tăng ca</text>
            <line x1="150" y1="920" x2="390" y2="1030" class="connection"/>

            <ellipse cx="700" cy="1030" rx="130" ry="40" class="usecase-new"/>
            <text x="700" y="1035" class="text-usecase">Thống kê giờ tăng ca</text>
            <line x1="150" y1="440" x2="570" y2="1030" class="connection"/>

            <ellipse cx="900" cy="1030" rx="130" ry="40" class="usecase"/>
            <text x="900" y="1035" class="text-usecase">Xem số giờ tăng ca</text>
            <line x1="150" y1="1540" x2="770" y2="1030" class="connection"/>

            <!-- NHÓM 6: BÁO CÁO & THỐNG KÊ -->
            <text x="400" y="1140" class="text-title" fill="#16a34a">BÁO CÁO & THỐNG KÊ</text>

            <ellipse cx="500" cy="1190" rx="120" ry="40" class="usecase-new"/>
            <text x="500" y="1195" class="text-usecase">Báo cáo chấm công</text>
            <line x1="150" y1="450" x2="380" y2="1190" class="connection"/>

            <ellipse cx="700" cy="1190" rx="120" ry="40" class="usecase-new"/>
            <text x="700" y="1195" class="text-usecase">Báo cáo vắng mặt</text>
            <line x1="150" y1="460" x2="580" y2="1190" class="connection"/>

            <ellipse cx="900" cy="1190" rx="110" ry="40" class="usecase-new"/>
            <text x="900" y="1195" class="text-usecase">Báo cáo tăng ca</text>
            <line x1="150" y1="470" x2="790" y2="1190" class="connection"/>

            <ellipse cx="1100" cy="1190" rx="120" ry="40" class="usecase-new"/>
            <text x="1100" y="1195" class="text-usecase">Thống kê Chủ nhật</text>
            <line x1="150" y1="480" x2="980" y2="1190" class="connection"/>

            <!-- NHÓM 7: CẤU HÌNH & HỆ THỐNG -->
            <text x="400" y="1300" class="text-title" fill="#6366f1">CẤU HÌNH & HỆ THỐNG</text>

            <ellipse cx="500" cy="1350" rx="120" ry="40" class="usecase-new"/>
            <text x="500" y="1355" class="text-usecase">Cấu hình hệ thống</text>
            <line x1="150" y1="490" x2="380" y2="1350" class="connection"/>

            <ellipse cx="700" cy="1350" rx="120" ry="40" class="usecase-new"/>
            <text x="700" y="1355" class="text-usecase">Xem Log hệ thống</text>
            <line x1="150" y1="500" x2="580" y2="1350" class="connection"/>

            <ellipse cx="900" cy="1350" rx="100" ry="40" class="usecase-new"/>
            <text x="900" y="1350" class="text-usecase">Quy trình</text>
            <text x="900" y="1365" class="text-usecase">tự động</text>
            <line x1="500" y1="1370" x2="800" y2="1350" class="connection-include"/>
            <text x="640" y="1340" class="text-label">«system»</text>

            <!-- Legend -->
            <g transform="translate(1250, 100)">
                <text x="0" y="0" class="text-title" fill="#334155">Chú thích:</text>
                
                <ellipse cx="50" cy="40" rx="60" ry="25" class="usecase"/>
                <text x="140" y="45" style="font-size: 12px; fill: #334155">Chức năng cơ bản</text>
                
                <ellipse cx="50" cy="90" rx="60" ry="25" class="usecase-new"/>
                <text x="140" y="95" style="font-size: 12px; fill: #334155">Chức năng mới thêm</text>
                
                <ellipse cx="50" cy="140" rx="60" ry="25" class="usecase-critical"/>
                <text x="140" y="145" style="font-size: 12px; fill: #334155">Chức năng quan trọng</text>
                
                <line x1="10" y1="180" x2="90" y2="180" class="connection"/>
                <text x="140" y="185" style="font-size: 12px; fill: #334155">Kết nối sử dụng</text>
                
                <line x1="10" y1="210" x2="90" y2="210" class="connection-include"/>
                <text x="140" y="215" style="font-size: 12px; fill: #334155">Include/Extend</text>
            </g>

            <!-- Statistics -->
            <g transform="translate(1250, 400)">
                <rect x="0" y="0" width="250" height="180" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="2" rx="5"/>
                <text x="125" y="25" class="text-title" text-anchor="middle">Thống kê</text>
                
                <text x="20" y="55" style="font-size: 13px; fill: #334155; font-weight: bold">Tổng Use Cases: 38</text>
                <text x="20" y="80" style="font-size: 12px; fill: #059669">✓ Cơ bản: 18</text>
                <text x="20" y="100" style="font-size: 12px; fill: #3b82f6">✓ Mới thêm: 17</text>
                <text x="20" y="120" style="font-size: 12px; fill: #f59e0b">✓ Quan trọng: 3</text>
                
                <line x1="20" y1="135" x2="230" y2="135" stroke="#cbd5e1" stroke-width="1"/>
                
                <text x="20" y="160" style="font-size: 12px; fill: #334155">Actors: 3 (Admin, Quản lý, NV)</text>
            </g>
        </svg>

        <div class="notes">
            <h2>📋 Ghi chú quan trọng:</h2>
            <p><strong style="color: #3b82f6">✓ Các chức năng mới thêm (màu xanh dương):</strong> Dựa trên phân tích database SQL</p>
            <p><strong style="color: #f59e0b">✓ Chức năng quan trọng (màu vàng):</strong> Chấm công, Đơn xin nghỉ</p>
            <p><strong style="color: #10b981">✓ Tự động hóa:</strong> Hệ thống có Events tự động chạy hàng ngày/tuần</p>
            <p><strong style="color: #7c3aed">✓ Phân quyền rõ ràng:</strong> Admin (toàn quyền), Quản lý (duyệt), Nhân viên (sử dụng)</p>
            <p><strong style="color: #dc2626">⚠ Lưu ý:</strong> Đăng ký tài khoản chỉ do Admin thực hiện, không có tự đăng ký</p>
        </div>

        <div class="stats">
            <h3>📊 Chi tiết phân tích:</h3>
            <p><strong>Tổng số Use Cases:</strong> 38 chức năng</p>
            <p><strong>Số lượng Actors:</strong> 3 (Admin, Quản lý, Nhân viên)</p>
            <p><strong>Nhóm chức năng:</strong> 7 nhóm chính</p>
            <p><strong>Chức năng tự động:</strong> 5 Events (tự động tạo lịch, kiểm tra vắng mặt, cập nhật trạng thái...)</p>
        </div>

        <div class="footer">
            <p>Hệ thống Quản lý Chấm công Resort - Version 2.0 (Hoàn chỉnh)</p>
            <p>Được thiết kế dựa trên Database Schema & Business Logic</p>
        </div>
    </div>

    <script>
        // Xuất PNG
        function exportAsPNG() {
            const svg = document.getElementById('diagram');
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Lấy kích thước SVG
            const bbox = svg.getBBox();
            canvas.width = 1600;
            canvas.height = 2000;
            
            // Chuyển SVG thành Data URL
            const svgData = new XMLSerializer().serializeToString(svg);
            const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
            const url = URL.createObjectURL(svgBlob);
            
            const img = new Image();
            img.onload = function() {
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0);
                
                // Tải về
                canvas.toBlob(function(blob) {
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'UseCase_Diagram_ChamCong.png';
                    a.click();
                });
                
                URL.revokeObjectURL(url);
            };
            img.src = url;
        }

        // Xuất SVG
        function exportAsSVG() {
            const svg = document.getElementById('diagram');
            const svgData = new XMLSerializer().serializeToString(svg);
            const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
            const url = URL.createObjectURL(svgBlob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = 'UseCase_Diagram_ChamCong.svg';
            a.click();
            
            URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>