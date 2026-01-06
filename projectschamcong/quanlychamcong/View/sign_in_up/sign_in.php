<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Rosa Alba - Đăng Nhập</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/View/css/login.css">
</head>

<body>
    <!-- Video Background -->
    <div class="video-background">
        <video id="bgVideo" autoplay muted loop playsinline>
            <source src="https://rosaalbaresort.com/wp-content/uploads/2023/09/CLIP-DUONG-HAM.mp4" type="video/mp4">
        </video>
    </div>
    <div class="video-overlay"></div>

    <!-- Main Container -->
    <div class="login-container">
        <!-- Login Form -->
        <div class="form-container">
            <p class="title">Đăng Nhập</p>
            <p class="subtitle">Chào mừng trở lại! Vui lòng đăng nhập để tiếp tục</p>
            
         
            <form id="loginForm" action="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=login&action=sign_in" method="post" class="form">
                
           
                <div id="errorMessage" class="error-message" style="display: none;"></div>
                
                <div class="input-group">
                    <label for="email">Email Đăng Nhập</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user icon_input"></i>
                        <input type="email" name="email" id="email" placeholder="Nhập email đăng nhập" 
                               value="<?php echo $_SESSION['old_email'] ?? ''; ?>" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="password">Mật Khẩu</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock icon_input"></i>
                    
                        <input type="password" name="password" id="password" placeholder="Nhập mật khẩu" required>
                     
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>
                
                <div class="forgot">
                    <label class="remember-me">
                        <input type="checkbox" id="remember" name="remember" value="1">
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="?controller=login&action=forgot_password">Quên mật khẩu?</a>
                </div>
                
              
                <button type="submit" class="sign" id="loginBtn">
                    <span class="btn-text">Đăng Nhập</span>
                    <span class="btn-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Đang xử lý...
                    </span>
                </button>
            </form>
        </div>

        <!-- Image Carousel (giữ nguyên) -->
        <div class="carousel-container">
            <div class="carousel">
                <div class="carousel-item active">
                    <img src="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/img/vocuc.jpg" alt="Resort Pool">
                    <div class="carousel-overlay">
                        <div class="carousel-title">Bể Bơi Vô Cực</div>
                        <div class="carousel-description">Thư giãn tại bể bơi vô cực với view biển tuyệt đẹp, tận hưởng
                            không gian sang trọng và yên bình.</div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/img/PDT11-scaled.jpg" alt="Luxury Room">
                    <div class="carousel-overlay">
                        <div class="carousel-title">Phòng Nghỉ Cao Cấp</div>
                        <div class="carousel-description">Các phòng được thiết kế tinh tế với đầy đủ tiện nghi hiện đại,
                            mang đến trải nghiệm nghỉ dưỡng đẳng cấp.</div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/img/swim.jpg" alt="Luxury Room">
                    <div class="carousel-overlay">
                        <div class="carousel-title">Quầy pool Side Bar</div>
                        <div class="carousel-description">Một nơi tuyệt vời để quý khách tận hưởng những thức uống mát
                            lạnh và các món ăn nhẹ trong không gian thư giãn thoải mái trên các ghế tắm nắng dưới tán
                            dừa ngay cạnh bể bơi miền nhiệt đới.</div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/img/SPA.jpg" alt="Spa">
                    <div class="carousel-overlay">
                        <div class="carousel-title">Spa & Massage</div>
                        <div class="carousel-description">Dịch vụ spa đẳng cấp quốc tế với các liệu trình chăm sóc sắc
                            đẹp và thư giãn toàn diện.</div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/img/nhahang.jpg" alt="Restaurant">
                    <div class="carousel-overlay">
                        <div class="carousel-title">Nhà Hàng Cao Cấp</div>
                        <div class="carousel-description">Thưởng thức ẩm thực đa quốc gia do các đầu bếp nổi tiếng thế
                            giới chế biến.</div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/img/tuantrangmat.jpg" alt="Restaurant">
                    <div class="carousel-overlay">
                        <div class="carousel-title">Kỳ Nghỉ Trăng Mật</div>
                        <div class="carousel-description">Xin chúc mừng và cùng chia sẻ niềm hạnh phúc với các Bạn – đôi
                            tình nhân vĩnh cửu! Từ thời khắc này, hãy cùng nhau tận hưởng và trải nghiệm mọi điều tuyệt
                            vời nhất tại mỗi nơi Bạn đến, trong đó có chúng tôi – Rosa Alba Resort</div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/thuctap/chamcong_resort/projectschamcong/quanlychamcong/img/bietthu.jpg" alt="Restaurant">
                    <div class="carousel-overlay">
                        <div class="carousel-title">Biệt Thự 3 Phòng Ngủ
                            Hướng Biển</div>
                        <div class="carousel-description">Nâng tầm kỳ nghỉ của bạn và người thân với biệt thự ba phòng
                            ngủ với nội thất sang trọng, diện tích lớn cùng nhiều không gian sinh hoạt. Hòa mình cùng
                            nhiên và thư giãn tại sân hiên với hồ bơi riêng hướng ra cảnh biển tuyệt đẹp. </div>
                    </div>
                </div>
            </div>
            <div class="carousel-dots">
                <span class="dot active" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
                <span class="dot" onclick="currentSlide(4)"></span>
                <span class="dot" onclick="currentSlide(5)"></span>
                <span class="dot" onclick="currentSlide(6)"></span>
            </div>
        </div>
   
    </div>

    <script>
    // Force video autoplay on page load
    window.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('bgVideo');
        video.muted = true;
        video.play().catch(function(error) {
            console.log('Video autoplay failed:', error);
            document.addEventListener('click', function() {
                video.play();
            }, { once: true });
        });
    });

    // Carousel functionality
    let currentIndex = 0;
    const items = document.querySelectorAll('.carousel-item');
    const dots = document.querySelectorAll('.dot');
    const totalItems = items.length;

    function showSlide(index) {
        items.forEach((item, i) => {
            item.classList.remove('active');
            if (i === index) item.classList.add('active');
        });
        dots.forEach((dot, i) => {
            dot.classList.remove('active');
            if (i === index) dot.classList.add('active');
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalItems;
        showSlide(currentIndex);
    }

    function currentSlide(index) {
        currentIndex = index;
        showSlide(currentIndex);
    }

    setInterval(nextSlide, 4000);


    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // ✅ Login form handling với AJAX
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const errorMessage = document.getElementById('errorMessage');
    const btnText = loginBtn.querySelector('.btn-text');
    const btnLoading = loginBtn.querySelector('.btn-loading');

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Ngăn form submit mặc định
        
        // Hide error message
        errorMessage.style.display = 'none';
        
        // Show loading state
        loginBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-block';
        
        // Get form data
        const formData = new FormData(this);
        
        fetch('/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=login&action=sign_in', {
    method: 'POST',
    body: formData
})
.then(response => {
    // ✅ Log để xem response trước
    return response.text().then(text => {
        console.log('Raw response:', text);
        
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('JSON parse error:', e);
            console.error('Response text:', text);
            throw new Error('Server trả về không phải JSON');
        }
    });
})
        .then(data => {
            if (data.success) {
             
                
                // Hiển thị thông báo thành công (optional)
                errorMessage.className = 'success-message';
                errorMessage.textContent = data.message || 'Đăng nhập thành công!';
                errorMessage.style.display = 'block';
                
                // Chuyển hướng sau 500ms
                setTimeout(() => {
                    window.location.href = '/thuctap/chamcong_resort/projectschamcong/quanlychamcong/Router/router.php?controller=profile&action=index';
                }, 500);
                
            } else {
               
                
                // Reset button state
                loginBtn.disabled = false;
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                
                // Hiển thị lỗi
                errorMessage.className = 'error-message';
                errorMessage.textContent = data.message || 'Đăng nhập thất bại!';
                errorMessage.style.display = 'block';
                
                // Focus vào field bị lỗi
                if (data.error_type === 'password') {
                    passwordInput.focus();
                    passwordInput.select();
                } else if (data.error_type === 'email_not_found') {
                    document.getElementById('email').focus();
                }
                
                // Auto hide error sau 5 giây
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Reset button state
            loginBtn.disabled = false;
            btnText.style.display = 'inline-block';
            btnLoading.style.display = 'none';
            
            // Hiển thị lỗi
            errorMessage.className = 'error-message';
            errorMessage.textContent = 'Có lỗi xảy ra, vui lòng thử lại!';
            errorMessage.style.display = 'block';
        });
    });
    </script>
</body>

</html>