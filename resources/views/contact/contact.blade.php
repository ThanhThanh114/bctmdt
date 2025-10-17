@extends('app')

@section('title', 'Liên Hệ - FUTA Bus Lines')
<link rel="stylesheet" href="{{ asset('assets/css/Contact.css') }}">
@section('content')

<!-- Contact Info Section -->
<section class="main-content" id="contactInfo" style="padding-top: 100px;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 40px;">
        <div class="section-header" id="sectionHeader1">
            <h2>Liên Hệ Với Chúng Tôi</h2>
            <p>Kết nối với chúng tôi qua nhiều kênh khác nhau để được hỗ trợ tốt nhất và nhanh chóng</p>
        </div>

        <div class="contact-grid" id="contactGrid">
            <div class="contact-card" data-card="1">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Trụ Sở Chính</h3>
                <p>Số 01 Tô Hiến Thành, Phường 3</p>
                <p>Quận 10, TP. Hồ Chí Minh</p>
                <a href="https://maps.google.com" target="_blank">Xem bản đồ chi tiết</a>
            </div>

            <div class="contact-card" data-card="2">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3>Hotline</h3>
                <p><a href="tel:19006067">19006067</a></p>
                <p><a href="tel:02838386852">(028) 3838 6852</a></p>
                <p>Hỗ trợ khách hàng 24/7</p>
            </div>

            <div class="contact-card" data-card="3">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p><a href="mailto:hotro@futa.vn">hotro@futa.vn</a></p>
                <p><a href="mailto:info@futa.vn">info@futa.vn</a></p>
                <p>Phản hồi nhanh trong 2 giờ</p>
            </div>

            <div class="contact-card" data-card="4">
                <div class="contact-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>Live Chat</h3>
                <p>Chat trực tiếp với nhân viên</p>
                <p>Thời gian: 6:00 - 22:00</p>
                <a href="#" onclick="startLiveChat()">Bắt đầu chat ngay</a>
            </div>

            <div class="contact-card" data-card="5">
                <div class="contact-icon">
                    <i class="fab fa-facebook-f"></i>
                </div>
                <h3>Mạng Xã Hội</h3>
                <p><a href="#" target="_blank">Facebook</a></p>
                <p><a href="#" target="_blank">Zalo OA</a></p>
                <p>Theo dõi tin tức và khuyến mãi</p>
            </div>

            <div class="contact-card" data-card="6">
                <div class="contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Giờ Làm Việc</h3>
                <p><strong>Thứ 2 - Chủ Nhật:</strong> 5:00 - 22:00</p>
                <p><strong>Lễ Tết:</strong> 6:00 - 20:00</p>
                <p>Bán vé và hỗ trợ khách hàng</p>
            </div>
        </div>
    </div>
</section>
<!-- Contact Form Section -->
<section class="contact-form-section" id="contactForm">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 40px;">
        <div class="section-header" id="sectionHeader2">
            <h2>Gửi Tin Nhắn</h2>
            <p>Có câu hỏi hoặc góp ý? Hãy để lại thông tin, chúng tôi sẽ liên hệ ngay với bạn</p>
        </div>

        <div class="form-container">
            <div class="form-info" id="formInfo">
                <h3>Tại Sao Chọn Chúng Tôi?</h3>
                <p>Với hơn 15 năm kinh nghiệm trong lĩnh vực vận tải hành khách, chúng tôi cam kết mang đến dịch vụ tốt
                    nhất với công nghệ hiện đại và đội ngũ chuyên nghiệp.</p>

                <div class="contact-info-list" id="infoList">
                    <div class="info-item" data-info="1">
                        <i class="fas fa-shield-alt"></i>
                        <span>Bảo mật thông tin tuyệt đối</span>
                    </div>
                    <div class="info-item" data-info="2">
                        <i class="fas fa-reply"></i>
                        <span>Phản hồi nhanh trong 2 giờ</span>
                    </div>
                    <div class="info-item" data-info="3">
                        <i class="fas fa-headset"></i>
                        <span>Hỗ trợ chuyên nghiệp 24/7</span>
                    </div>
                    <div class="info-item" data-info="4">
                        <i class="fas fa-award"></i>
                        <span>Dịch vụ đạt chuẩn quốc tế</span>
                    </div>
                </div>
            </div>

            <div class="contact-form" id="mainForm">
                <h3>Liên Hệ Ngay</h3>

                @if(session('success'))
                <div class="alert success" id="successAlert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert error" id="errorAlert">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="form-group">
                        <label for="branch">Chi nhánh quan tâm</label>
                        <select name="branch" id="branch" required>
                            <option value="">-- Chọn chi nhánh --</option>
                            <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                            <option value="Hà Nội">Hà Nội</option>
                            <option value="Đà Nẵng">Đà Nẵng</option>
                            <option value="Cần Thơ">Cần Thơ</option>
                            <option value="Nha Trang">Nha Trang</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullname">Họ và tên</label>
                            <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}"
                                placeholder="Nhập họ tên đầy đủ" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                placeholder="Nhập số điện thoại" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="Nhập địa chỉ email" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Tiêu đề</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                            placeholder="Nhập tiêu đề tin nhắn" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Nội dung tin nhắn</label>
                        <textarea name="message" id="message" rows="6"
                            placeholder="Nhập nội dung chi tiết tin nhắn của bạn..."
                            required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Gửi tin nhắn ngay
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- FAQ Section -->
<section class="faq-section" id="faqSection">
    <div class="container">
        <div class="section-header" id="sectionHeader3">
            <h2>Câu Hỏi Thường Gặp</h2>
            <p>Những thắc mắc phổ biến từ khách hàng và giải đáp chi tiết</p>
        </div>

        <div class="faq-container" id="faqContainer">
            <div class="faq-item" data-faq="1">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Làm sao để đặt vé online một cách nhanh chóng?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Bạn có thể đặt vé trực tuyến thông qua website hoặc ứng dụng mobile của chúng tôi. Chỉ cần chọn
                        tuyến đường, thời gian, ghế ngồi và thanh toán online an toàn.</p>
                </div>
            </div>

            <div class="faq-item" data-faq="2">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Chính sách hoàn/đổi vé như thế nào?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Khách hàng có thể hoàn/đổi vé trước giờ khởi hành 4 tiếng với phí 10% giá vé. Đổi vé miễn phí
                        trước 24 giờ và hoàn tiền 100% nếu chuyến xe bị hủy.</p>
                </div>
            </div>

            <div class="faq-item" data-faq="3">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Có những hình thức thanh toán nào được hỗ trợ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Chúng tôi hỗ trợ đa dạng hình thức thanh toán: thẻ ATM nội địa, Visa/Mastercard, ví điện tử
                        (Momo, ZaloPay, VNPay), và thanh toán trực tiếp tại quầy.</p>
                </div>
            </div>

            <div class="faq-item" data-faq="4">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Có dịch vụ đưa đón tận nhà không?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Có, chúng tôi có dịch vụ đưa đón tận nhà tại các khu vực nội thành với phí phụ thu hợp lý. Liên
                        hệ hotline để biết thêm chi tiết và đặt lịch.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js"></script>
<script src="{{ asset('assets/js/Contact.js') }}"></script>
@endsection