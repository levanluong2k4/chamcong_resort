import cv2
import numpy as np
import face_recognition
import os
from datetime import datetime
import mysql.connector
from mysql.connector import Error
from PIL import Image, ImageDraw, ImageFont
import uuid
import platform

# =============== CẤU HÌNH BẢO MẬT ===============
# Danh sách địa chỉ MAC được phép chấm công


def get_mac_address():
    """Lấy địa chỉ MAC của máy tính"""
    mac = ':'.join(['{:02x}'.format((uuid.getnode() >> elements) & 0xff)
                    for elements in range(0,2*6,2)][::-1])
    return mac.upper()

def check_device_authorized():
    """Kiểm tra máy tính có được phép chấm công không"""
    current_mac = get_mac_address()
    print(f"Địa chỉ MAC hiện tại: {current_mac}")
    ALLOWED_MAC_ADDRESSES = [
    current_mac,  # MAC thực tế của laptop bạn (Python đọc được)
    "46-92-1A-53-0F-05",  # Wi-Fi (dự phòng)
    "F4-EE-08-B0-E1-BD",  # Ethernet (dự phòng)
]
    # Chuyển tất cả về chữ hoa để so sánh
    allowed_macs_upper = [mac.upper() for mac in ALLOWED_MAC_ADDRESSES]
    
    if current_mac in allowed_macs_upper:
        print("✓ Thiết bị được ủy quyền - Cho phép chấm công")
        return True
    else:
        print("✗ CẢNH BÁO: Thiết bị KHÔNG được ủy quyền!")
        print(f"✗ MAC hiện tại: {current_mac}")
        print(f"✗ Danh sách cho phép: {', '.join(ALLOWED_MAC_ADDRESSES)}")
        return False

# =============== CẤU HÌNH DATABASE ===============
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'quanlychamcong'
}

# =============== HÀM KẾT NỐI DATABASE ===============
def create_connection():
    """Tạo kết nối đến MySQL database"""
    try:
        connection = mysql.connector.connect(**DB_CONFIG)
        if connection.is_connected():
            return connection
    except Error as e:
        print(f"✗ Lỗi kết nối MySQL: {e}")
        return None

# =============== HÀM LẤY DANH SÁCH NHÂN VIÊN ===============
def get_employees_from_db():
    """
    Lấy danh sách nhân viên từ bảng NhanVien
    Returns: dict {ma_nhan_vien: {'ho_ten': str, 'anh_dai_dien': str}}
    """
    connection = create_connection()
    if connection is None:
        return {}
    
    try:
        cursor = connection.cursor(dictionary=True)
        query = """
        SELECT ma_nhan_vien, ho_ten, anh_dai_dien 
        FROM NhanVien 
        WHERE trang_thai = 'DANG_LAM' 
        AND anh_dai_dien IS NOT NULL
        """
        cursor.execute(query)
        results = cursor.fetchall()
        
        employees = {}
        for row in results:
            employees[row['ma_nhan_vien']] = {
                'ho_ten': row['ho_ten'],
                'anh_dai_dien': row['anh_dai_dien']
            }
        
        cursor.close()
        connection.close()
        
        print(f"✓ Đã tải {len(employees)} nhân viên từ database")
        return employees
        
    except Error as e:
        print(f"✗ Lỗi truy vấn database: {e}")
        return {}

# =============== HÀM CHẤM CÔNG ===============
def mark_attendance(ma_nhan_vien, ho_ten):
    """
    Ghi nhận chấm công vào bảng ChamCong
    - Giờ vào: Lấy giờ CHẤM CÔNG SỚM NHẤT trong ngày
    - Giờ ra: Lấy giờ CHẤM CÔNG MUỘN NHẤT trong ngày
    """
    connection = create_connection()
    if connection is None:
        return False
    
    try:
        cursor = connection.cursor()
        now = datetime.now()
        ngay_lam = now.strftime('%Y-%m-%d')
        gio_hien_tai = now
        
        # Kiểm tra đã có bản ghi chấm công hôm nay chưa
        check_query = """
        SELECT ma_cham_cong, gio_vao, gio_ra 
        FROM ChamCong 
        WHERE ma_nhan_vien = %s AND ngay_lam = %s
        """
        cursor.execute(check_query, (ma_nhan_vien, ngay_lam))
        result = cursor.fetchone()
        
        if result is None:
            # Chưa có bản ghi - TẠO MỚI với giờ vào
            insert_query = """
            INSERT INTO ChamCong (ma_nhan_vien, ngay_lam, gio_vao, trang_thai, da_sua_thu_cong, ghi_chu) 
            VALUES (%s, %s, %s, 'DI_LAM', FALSE, 'Chấm công tự động')
            """
            cursor.execute(insert_query, (ma_nhan_vien, ngay_lam, gio_hien_tai))
            connection.commit()
            print(f"✓ CHẤM CÔNG VÀO: {ho_ten} - {now.strftime('%H:%M:%S')}")
            return True
            
        else:
            # Đã có bản ghi - CÂP NHẬT
            ma_cham_cong = result[0]
            gio_vao_cu = result[1]
            gio_ra_cu = result[2]
            
            # Cập nhật giờ vào nếu giờ hiện tại SỚM HƠN giờ vào cũ
            if gio_vao_cu is None or gio_hien_tai < gio_vao_cu:
                update_query = """
                UPDATE ChamCong 
                SET gio_vao = %s 
                WHERE ma_cham_cong = %s
                """
                cursor.execute(update_query, (gio_hien_tai, ma_cham_cong))
                connection.commit()
                print(f"✓ CẬP NHẬT GIỜ VÀO SỚM HƠN: {ho_ten} - {now.strftime('%H:%M:%S')}")
                return True
            
            # Cập nhật giờ ra nếu giờ hiện tại MUỘN HƠN giờ ra cũ (hoặc chưa có giờ ra)
            if gio_ra_cu is None or gio_hien_tai > gio_ra_cu:
                update_query = """
                UPDATE ChamCong 
                SET gio_ra = %s 
                WHERE ma_cham_cong = %s
                """
                cursor.execute(update_query, (gio_hien_tai, ma_cham_cong))
                connection.commit()
                print(f"✓ CẬP NHẬT GIỜ RA MUỘN HƠN: {ho_ten} - {now.strftime('%H:%M:%S')}")
                return True
            
            # Nếu không sớm hơn và không muộn hơn
            print(f"→ {ho_ten} - đã chấm công giờ vào")
            return False
        
    except Error as e:
        print(f"✗ Lỗi ghi chấm công: {e}")
        return False
    finally:
        if cursor:
            cursor.close()
        if connection:
            connection.close()

# =============== LOAD ẢNH VÀ ENCODING TỪ DATABASE ===============
def load_face_encodings():
    """
    Đọc ảnh đại diện từ thư mục pic2 dựa trên tên file trong database
    Returns: 
        - encodeList: danh sách encoding
        - employee_ids: danh sách ma_nhan_vien tương ứng
        - employee_names: danh sách ho_ten tương ứng
    """
    PHOTO_FOLDER = "chamcong_resort/pic2"  # Thư mục chứa ảnh
    
    employees = get_employees_from_db()
    
    encodeList = []
    employee_ids = []
    employee_names = []
    
    for ma_nv, info in employees.items():
        anh_filename = info['anh_dai_dien']
        
        # Tạo đường dẫn đầy đủ: pic2/filename
        anh_path = os.path.join(PHOTO_FOLDER, anh_filename)
        
        # Kiểm tra file tồn tại
        if not os.path.exists(anh_path):
            print(f"✗ Không tìm thấy ảnh: {anh_path}")
            continue
        
        # Đọc ảnh
        img = cv2.imread(anh_path)
        if img is None:
            print(f"✗ Không thể đọc ảnh: {anh_path}")
            continue
        
        # Chuyển sang RGB và encoding
        img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        encodings = face_recognition.face_encodings(img_rgb)
        
        if len(encodings) > 0:
            encodeList.append(encodings[0])
            employee_ids.append(ma_nv)
            employee_names.append(info['ho_ten'])
            print(f"✓ Đã load: {info['ho_ten']} (ID: {ma_nv}) - {anh_filename}")
        else:
            print(f"✗ Không tìm thấy khuôn mặt trong ảnh: {anh_path}")
    
    print(f"\n✓ Tổng cộng: {len(encodeList)} khuôn mặt đã sẵn sàng\n")
    return encodeList, employee_ids, employee_names

# =============== KHỞI TẠO HỆ THỐNG ===============
print("=" * 50)
print("HỆ THỐNG CHẤM CÔNG NHẬN DIỆN KHUÔN MẶT - RESORT")
print("=" * 50)

# Kiểm tra thiết bị có được phép không
if not check_device_authorized():
    print("\n" + "=" * 50)
    print("TRUY CẬP BỊ TỪ CHỐI!")
    print("=" * 50)
    print("\nHướng dẫn lấy địa chỉ MAC của máy bạn:")
    print("1. Windows: Mở CMD → gõ 'ipconfig /all'")
    print("2. Mac/Linux: Mở Terminal → gõ 'ifconfig' hoặc 'ip addr'")
    print("3. Tìm dòng 'Physical Address' hoặc 'ether'")
    print(f"\n→ Sau đó thêm '{get_mac_address()}' vào ALLOWED_MAC_ADDRESSES trong code\n")
    input("Nhấn Enter để thoát...")
    exit()

print("\n" + "=" * 50)
print("✓ XÁC THỰC THIẾT BỊ THÀNH CÔNG")
print("=" * 50 + "\n")

# Load encoding từ database
encodeListKnown, employee_ids, employee_names = load_face_encodings()

if len(encodeListKnown) == 0:
    print("\n✗ CẢNH BÁO: Không có nhân viên nào có ảnh đại diện!")
    print("Vui lòng kiểm tra:")
    print("1. Bảng NhanVien có dữ liệu chưa?")
    print("2. Cột anh_dai_dien có đường dẫn đúng không?")
    print("3. File ảnh có tồn tại không?")
    exit()

# =============== HÀM VẼ CHỮ TIẾNG VIỆT ===============
def put_vietnamese_text(img, text, position, font_path="arial.ttf", font_size=24, color=(255, 255, 255)):
    """
    Vẽ chữ tiếng Việt lên ảnh OpenCV
    """
    # Chuyển từ OpenCV (BGR) sang PIL (RGB)
    img_pil = Image.fromarray(cv2.cvtColor(img, cv2.COLOR_BGR2RGB))
    draw = ImageDraw.Draw(img_pil)
    
    try:
        # Thử load font từ hệ thống
        font = ImageFont.truetype(font_path, font_size)
    except:
        # Nếu không có font, dùng font mặc định
        font = ImageFont.load_default()
    
    draw.text(position, text, font=font, fill=color)
    
    # Chuyển lại sang OpenCV (BGR)
    return cv2.cvtColor(np.array(img_pil), cv2.COLOR_RGB2BGR)

# =============== KHỞI ĐỘNG WEBCAM ===============
cap = cv2.VideoCapture(0)

if not cap.isOpened():
    print("✗ Không thể mở webcam")
    exit()

print("✓ Webcam đã sẵn sàng. Nhấn 'q' để thoát.\n")

# Biến lưu trạng thái chấm công gần đây (tránh chấm công liên tục)
last_attendance = {}
COOLDOWN_SECONDS = 5  # Chỉ cho phép chấm công lại sau 5 giây

# Biến điều khiển thoát sau khi chấm công
attendance_success = False
success_message = ""
success_time = None
DISPLAY_DURATION = 3  # Hiển thị thông báo 3 giây trước khi thoát

while True:
    ret, frame = cap.read()
    if not ret:
        print("✗ Không thể đọc frame từ webcam")
        break
    
    # Nếu đã chấm công thành công, hiển thị thông báo và đếm ngược
    if attendance_success:
        # Tính thời gian còn lại
        elapsed = (datetime.now() - success_time).total_seconds()
        remaining = DISPLAY_DURATION - elapsed
        
        if remaining > 0:
            # Vẽ overlay màu xanh
            overlay = frame.copy()
            cv2.rectangle(overlay, (0, 0), (frame.shape[1], frame.shape[0]), (0, 255, 0), -1)
            frame = cv2.addWeighted(frame, 0.7, overlay, 0.3, 0)
            
            # Hiển thị thông báo lớn giữa màn hình
            h, w = frame.shape[:2]
            
            # Thông báo cảm ơn
            frame = put_vietnamese_text(frame, "CẢM ƠN!", 
                                       (w//2 - 100, h//2 - 80),
                                       font_size=60, color=(255, 255, 255))
            
            # Tên nhân viên
            frame = put_vietnamese_text(frame, success_message, 
                                       (w//2 - 150, h//2),
                                       font_size=40, color=(255, 255, 255))
            
            # Đóng sau
            frame = put_vietnamese_text(frame, f"Đóng sau {int(remaining) + 1}s...", 
                                       (w//2 - 80, h//2 + 60),
                                       font_size=25, color=(255, 255, 255))
            
            cv2.imshow('He Thong Cham Cong Resort', frame)
            
            if cv2.waitKey(1) == ord("q"):
                break
            continue
        else:
            # Hết thời gian, thoát
            print("\n✓ Chấm công thành công. Đang đóng chương trình...")
            break
    
    # Resize để tăng tốc độ xử lý
    framS = cv2.resize(frame, (0, 0), None, fx=0.5, fy=0.5)
    framS = cv2.cvtColor(framS, cv2.COLOR_BGR2RGB)
    
    # Xác định vị trí và encode khuôn mặt
    facecurFrame = face_recognition.face_locations(framS)
    encodecurFrame = face_recognition.face_encodings(framS, facecurFrame)
    
    for encodeFace, faceLoc in zip(encodecurFrame, facecurFrame):
        matches = face_recognition.compare_faces(encodeListKnown, encodeFace)
        faceDis = face_recognition.face_distance(encodeListKnown, encodeFace)
        
        matchIndex = np.argmin(faceDis)
        
        # Ngưỡng nhận diện: < 0.50 là khớp
        if faceDis[matchIndex] < 0.50:
            ma_nv = employee_ids[matchIndex]
            ho_ten = employee_names[matchIndex]
            
            # Kiểm tra cooldown
            now = datetime.now()
            if ma_nv in last_attendance:
                time_diff = (now - last_attendance[ma_nv]).total_seconds()
                if time_diff < COOLDOWN_SECONDS:
                    name_display = ho_ten
                else:
                    # Đã qua thời gian cooldown, cho phép chấm công
                    if mark_attendance(ma_nv, ho_ten):
                        last_attendance[ma_nv] = now
                        # Kích hoạt chế độ cảm ơn và thoát
                        attendance_success = True
                        success_message = ho_ten
                        success_time = now
                    name_display = ho_ten
            else:
                # Lần đầu nhận diện
                if mark_attendance(ma_nv, ho_ten):
                    last_attendance[ma_nv] = now
                    # Kích hoạt chế độ cảm ơn và thoát
                    attendance_success = True
                    success_message = ho_ten
                    success_time = now
                name_display = ho_ten
            
            color = (0, 255, 0)  # Xanh lá
        else:
            name_display = 'KHONG XAC DINH'
            color = (0, 0, 255)  # Đỏ
        
        # Vẽ khung và tên lên video
        y1, x2, y2, x1 = faceLoc
        y1, x2, y2, x1 = y1 * 2, x2 * 2, y2 * 2, x1 * 2
        
        # Vẽ khung
        cv2.rectangle(frame, (x1, y1), (x2, y2), color, 2)
        cv2.rectangle(frame, (x1, y2 - 35), (x2, y2), color, cv2.FILLED)
        
        # Vẽ chữ tiếng Việt
        frame = put_vietnamese_text(frame, name_display, (x1 + 6, y2 - 30), 
                                     font_size=20, color=(255, 255, 255))
    
    # Hiển thị thông tin trên màn hình
    frame = put_vietnamese_text(frame, f"Nhân viên: {len(encodeListKnown)}", (10, 10),
                                font_size=20, color=(255, 255, 255))
    frame = put_vietnamese_text(frame, datetime.now().strftime('%Y-%m-%d %H:%M:%S'), (10, 40),
                                font_size=20, color=(255, 255, 255))
    
    cv2.imshow('He Thong Cham Cong Resort', frame)
    
    if cv2.waitKey(1) == ord("q"):
        break

cap.release()
cv2.destroyAllWindows()
print("\n✓ Đã đóng chương trình")