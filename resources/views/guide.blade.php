<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hướng dẫn mua vé - FUTA Bus Lines</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/Login.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Login_Enhanced.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
    /* Guide Page Styles */
    .guide-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .guide-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .guide-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ef5222;
        margin-bottom: 10px;
    }

    .guide-subtitle {
        font-size: 1.2rem;
        color: #666;
    }

    .guide-section {
        margin-bottom: 60px;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 600;
        color: #00613D;
        text-align: center;
        margin-bottom: 30px;
    }

    .app-download {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        margin: 40px 0;
    }

    .app-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }

    .app-links {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .app-link img {
        height: 40px;
        width: auto;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 40px 0;
    }

    .feature-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: #ef5222;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .feature-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .feature-desc {
        color: #666;
        line-height: 1.6;
    }

    .steps-section {
        background: #FFF7F5;
        padding: 60px 0;
        margin: 60px 0;
    }

    .steps-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .steps-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: #00613D;
        margin-bottom: 50px;
    }

    .step-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 60px;
        overflow-x: auto;
        padding: 20px 0;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0 15px;
        min-width: 120px;
    }

    .step-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #C1C1CC;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 15px;
        position: relative;
    }

    .step-circle.active {
        background: #ef5222;
    }

    .step-text {
        text-align: center;
        font-size: 0.9rem;
        color: #333;
        font-weight: 500;
        max-width: 120px;
    }

    .step-arrow {
        margin: 0 10px;
    }

    .step-content {
        text-align: center;
        margin: 40px 0;
    }

    .step-content-title {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 30px;
    }

    .step-image {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .company-info {
        background: #FFF7F5;
        padding: 40px;
        border-radius: 15px;
        margin: 40px 0;
    }

    .company-title {
        text-align: center;
        font-size: 1.8rem;
        font-weight: 600;
        color: #ef5222;
        margin-bottom: 20px;
    }

    .company-text {
        color: #333;
        line-height: 1.8;
        text-align: justify;
    }

    .back-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        background: rgba(239, 82, 34, 0.9);
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .back-btn:hover {
        background: #ef5222;
        transform: scale(1.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .guide-title {
            font-size: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .app-links {
            flex-direction: column;
            align-items: center;
        }

        .step-progress {
            flex-wrap: wrap;
            justify-content: center;
        }

        .step-item {
            margin: 10px;
        }

        .step-circle {
            width: 60px;
            height: 60px;
            font-size: 1.2rem;
        }

        .step-text {
            font-size: 0.8rem;
        }
    }
    </style>
</head>

<body>
    <button class="back-btn" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
    </button>

    <div class="guide-container">
        <div class="guide-header">
            <h1 class="guide-title">HƯỚNG DẪN MUA VÉ XE TRÊN WEBSITE</h1>
            <p class="guide-subtitle">
                <a href="https://futabus.vn/" style="color: #00613D; text-decoration: none;">futabus.vn</a>
            </p>
        </div>

        <div class="app-download">
            <p class="app-title">QUÉT MÃ QR TẢI APP DÀNH CHO KHÁCH HÀNG</p>
            <div style="display: flex; justify-content: center; gap: 20px; margin: 20px 0;">
                <img src="/images/guideCharge/logo_futa.png" alt="FUTA Logo" style="width: 120px; height: auto;">
                <img src="/images/guideCharge/qr_app.png" alt="QR Code" style="width: 120px; height: auto;">
            </div>
            <div class="app-links">
                <a target="_blank" href="http://onelink.to/futa.android" rel="noreferrer">
                    <img alt="Google Play"
                        src="https://cdn.futabus.vn/futa-busline-cms-dev/CH_Play_712783c88a/CH_Play_712783c88a.svg">
                </a>
                <a target="_blank" href="http://onelink.to/futa.ios" rel="noreferrer">
                    <img alt="App Store"
                        src="https://cdn.futabus.vn/futa-busline-cms-dev/App_Store_60da92cb12/App_Store_60da92cb12.svg">
                </a>
            </div>
        </div>

        <div class="company-info">
            <p class="company-title">UY TÍNH – CHẤT LƯỢNG – DANH DỰ</p>
            <div class="company-text">
                <p>Công Ty Cổ phần Xe Khách Phương Trang - FUTA Bus Lines xin gửi lời cảm ơn chân thành đến Quý Khách
                    hàng đã tin tưởng và sử dụng dịch vụ của chúng tôi. Chúng tôi luôn hoạt động với tôn chỉ "Chất lượng
                    là danh dự" và nỗ lực không ngừng để mang đến trải nghiệm dịch vụ tối ưu dành cho Khách hàng.</p>
                <p>Chúng tôi không chỉ đảm bảo các chuyến xe an toàn, chất lượng và đúng hẹn, mà còn chú trọng đến trải
                    nghiệm mua vé của Khách hàng. Chúng tôi đã cải tiến website mua vé trực tuyến <a
                        href="https://futabus.vn/" style="color: #ef5222;">Thông tin vé | Ticket Information | FUTA Bus
                        Lines | Tổng Đài đặt vé và Chăm Sóc Khách Hàng 19006067</a> để đảm bảo việc mua vé dễ dàng và
                    tiện lợi hơn bao giờ hết.</p>
                <p>Bên cạnh đó, chúng tôi tự hào giới thiệu ứng dụng mua vé FUTA Bus, giúp Khách hàng tiết kiệm thời
                    gian mua vé. Qua ứng dụng này, Khách hàng có thể tra cứu thông tin về lịch trình, chọn ghế/giường và
                    thanh toán nhanh chóng, thuận tiện trên điện thoại di động. Chúng tôi tin rằng với ứng dụng mua vé
                    FUTA Bus và website <a href="https://futabus.vn/" style="color: #ef5222;">Thông tin vé | Ticket
                        Information | FUTA Bus Lines | Tổng Đài đặt vé và Chăm Sóc Khách Hàng 19006067</a> đã được cải
                    tiến sẽ mang lại trải nghiệm tốt và giúp Khách hàng tiết kiệm thời gian quý báu.</p>
            </div>
        </div>

        <div class="guide-section">
            <h2 class="section-title">Bước 1: Những trải nghiệm nổi bật mà Ứng Dụng Mua Vé FUTA Bus và Website
                futabus.vn mang lại</h2>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Khách hàng chủ động về lịch trình của mình</h3>
                    <p class="feature-desc">Từ điểm đón, điểm trả khách đến thời gian hành trình.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chair"></i>
                    </div>
                    <h3 class="feature-title">Khách hàng được chọn và chủ động vị trí, số ghế ngồi trên xe</h3>
                    <p class="feature-desc">Lựa chọn ghế ngồi phù hợp với nhu cầu của bạn.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-couch"></i>
                    </div>
                    <h3 class="feature-title">Không phải xếp hàng những dịp Lễ, Tết</h3>
                    <p class="feature-desc">Đặt vé mọi lúc mọi nơi, tránh đông đúc.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="feature-title">Dễ dàng kết hợp và nhận ưu đãi khi sử dụng dịch vụ khác</h3>
                    <p class="feature-desc">Taxi, Trạm Dừng, Vận Chuyển Hàng Hoá...</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="feature-icon">Khi đăng ký thành viên, khách hàng còn nhận nhiều ưu đãi</h3>
                    <p class="feature-desc">Cũng như nhiều phần quà hấp dẫn.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title">Dễ dàng góp ý để nâng cao chất lượng dịch vụ</h3>
                    <p class="feature-desc">Ý kiến của bạn giúp chúng tôi phục vụ tốt hơn.</p>
                </div>
            </div>
        </div>

        <div class="steps-section">
            <div class="steps-container">
                <h2 class="steps-title">Bước 2: Những bước để giúp khách hàng trải nghiệm mua vé nhanh</h2>

                <div class="step-progress">
                    <div class="step-item">
                        <div class="step-circle active">01</div>
                        <div class="step-text">Truy cập vào địa chỉ futabus.vn</div>
                    </div>
                    <div class="step-arrow">→</div>
                    <div class="step-item">
                        <div class="step-circle">02</div>
                        <div class="step-text">Chọn thông tin hành trình</div>
                    </div>
                    <div class="step-arrow">→</div>
                    <div class="step-item">
                        <div class="step-circle">03</div>
                        <div class="step-text">Chọn ghế, điểm đón trả, thông tin hành khách</div>
                    </div>
                    <div class="step-arrow">→</div>
                    <div class="step-item">
                        <div class="step-circle">04</div>
                        <div class="step-text">Chọn phương thức thanh toán</div>
                    </div>
                    <div class="step-arrow">→</div>
                    <div class="step-item">
                        <div class="step-circle">05</div>
                        <div class="step-text">Mua vé xe thành công</div>
                    </div>
                </div>

                <div class="step-content">
                    <h3 class="step-content-title">Bước 1: Truy cập địa chỉ futabus.vn</h3>
                    <img src="/images/guideCharge/web/step1/step1.png" alt="Bước 1" class="step-image">
                </div>

                <div class="step-content">
                    <h3 class="step-content-title">Tải ứng dụng tại futabus.vn hoặc tìm ứng dụng Futa Bus trên Google
                        Play hoặc Apple store</h3>
                    <div class="app-links" style="margin: 20px 0;">
                        <a target="_blank" href="http://onelink.to/futa.android" rel="noreferrer">
                            <img alt="Google Play"
                                src="https://cdn.futabus.vn/futa-busline-cms-dev/CH_Play_712783c88a/CH_Play_712783c88a.svg">
                        </a>
                        <a target="_blank" href="http://onelink.to/futa.ios" rel="noreferrer">
                            <img alt="App Store"
                                src="https://cdn.futabus.vn/futa-busline-cms-dev/App_Store_60da92cb12/App_Store_60da92cb12.svg">
                        </a>
                    </div>
                </div>

                <div class="step-content">
                    <h3 class="step-content-title">Bước 2: Chọn thông tin hành trình</h3>
                    <img src="/images/guideCharge/web/step1/step2_1.png" alt="Bước 2.1" class="step-image"
                        style="margin-bottom: 30px;">
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                1</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn điểm khởi hành</p>
                        </div>
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                2</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn điểm đến</p>
                        </div>
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                3</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn ngày đi</p>
                        </div>
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                4</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn ngày về</p>
                        </div>
                    </div>
                    <img src="/images/guideCharge/web/step1/step2_2.png" alt="Bước 2.2" class="step-image"
                        style="margin-top: 30px;">
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                1</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn giờ đi</p>
                        </div>
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                2</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn loại xe</p>
                        </div>
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                3</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn điểm đón</p>
                        </div>
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                4</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn chuyến đi</p>
                        </div>
                        <div style="text-align: center;">
                            <div
                                style="width: 60px; height: 60px; border: 2px dashed #F2754E; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px; font-weight: bold; color: #F2754E;">
                                5</div>
                            <p style="font-size: 1.5rem; font-weight: 600; color: #111111;">Chọn nhanh số ghế</p>
                        </div>
                    </div>
                </div>

                <div class="step-content">
                    <h3 class="step-content-title">Bước 3: Chọn ghế, điểm đón trả, thông tin hành khách</h3>
                    <img src="/images/guideCharge/web/step1/step3.png" alt="Bước 3" class="step-image">
                </div>

                <div class="step-content">
                    <h3 class="step-content-title">Bước 4: Chọn phương thức thanh toán</h3>
                    <img src="/images/guideCharge/web/step1/step4.png" alt="Bước 4" class="step-image">
                </div>

                <div class="step-content">
                    <h3 class="step-content-title">Bước 5: Mua vé thành công</h3>
                    <img src="/images/guideCharge/web/step1/step5.png" alt="Bước 5" class="step-image">
                </div>

                <div class="company-info" style="margin-top: 60px;">
                    <h3 class="company-title" style="font-size: 2rem; margin-bottom: 30px;">Bước 3: Vé xe sẽ được gửi về
                        Email. Quý khách vui lòng kiểm tra Email để nhận vé</h3>
                    <img src="/images/guideCharge/web/step1/co_step3.PNG" alt="Email confirmation"
                        style="width: 100%; max-width: 800px; margin: 0 auto; display: block; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/login_enhanced.js') }}?v={{ time() }}"></script>
</body>

</html>