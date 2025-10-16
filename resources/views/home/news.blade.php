<!-- ======================== 4. TIN TỨC MỚI ======================== -->
<section class="news-section" style="background-color: #FFF7F5; padding: 60px 0;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="news-header" style="text-align: center; margin-bottom: 50px;">
            <h2
                style="color: #FF6F3C; font-size: 36px; font-weight: 700; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">
                TIN TỨC MỚI
            </h2>
            <p style="color: #666; font-size: 18px; font-weight: 400;">
                Cập nhật thông tin mới nhất về chuyến đi và dịch vụ
            </p>
        </div>

        @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="swiper newsSwiper">
                <div class="swiper-wrapper">
                    @foreach($latestNews as $news)
                        <div class="swiper-slide">
                            <div class="news-card">
                                <div class="news-image">
                                    @if($news->hinh_anh)
                                        <img src="{{ asset('storage/' . $news->hinh_anh) }}" alt="{{ $news->tieu_de }}">
                                    @else
                                        <img src="{{ asset('assets/images/default-news.jpg') }}" alt="{{ $news->tieu_de }}">
                                    @endif
                                    <div class="news-overlay"></div>
                                </div>
                                <div class="news-content">
                                    <div class="news-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ date('d/m/Y', strtotime($news->ngay_dang)) }}
                                    </div>
                                    <h3 class="news-title">{{ $news->tieu_de }}</h3>
                                    <p class="news-excerpt">{{ Str::limit(strip_tags($news->noi_dung), 120) }}</p>
                                    <a href="{{ route('news.show', $news->ma_tin) }}" class="news-link">
                                        Đọc thêm
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        @else
            <div style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-newspaper" style="font-size: 48px; margin-bottom: 20px; color: #FF6F3C;"></i>
                <p style="font-size: 18px;">Chưa có tin tức mới</p>
            </div>
        @endif
    </div>
</section>

<style>
    /* News Section Styles */
    .news-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(255, 111, 60, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .news-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(255, 111, 60, 0.2);
    }

    .news-image {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
    }

    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .news-card:hover .news-image img {
        transform: scale(1.1);
    }

    .news-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.3) 100%);
    }

    .news-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .news-date {
        color: #FF6F3C;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .news-title {
        color: #333;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 56px;
    }

    .news-excerpt {
        color: #666;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #FF6F3C;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: gap 0.3s ease;
    }

    .news-link:hover {
        gap: 12px;
        color: #FF9500;
    }

    .news-link i {
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .news-link:hover i {
        transform: translateX(4px);
    }

    /* Swiper Customization */
    .newsSwiper {
        padding: 0 50px 60px;
    }

    .swiper-button-next,
    .swiper-button-prev {
        width: 50px;
        height: 50px;
        background: rgba(255, 111, 60, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        color: white !important;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
        font-weight: 900;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(255, 149, 0, 0.9);
        transform: scale(1.1);
    }

    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: #FF6F3C;
        opacity: 0.3;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        background: #FF6F3C;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .news-header h2 {
            font-size: 28px;
        }

        .news-header p {
            font-size: 16px;
        }

        .newsSwiper {
            padding: 0 10px 50px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: 40px;
            height: 40px;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 16px;
        }

        .news-image {
            height: 200px;
        }

        .news-title {
            font-size: 18px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var newsSwiper = new Swiper('.newsSwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    });
</script>
<!-- ======================== END 4. TIN TỨC MỚI ======================== -->

<!-- ======================== 5. FUTA CONNECT ======================== -->
<section class="futa-connect-new">
    <div class="futa-connect-container">
        <h2 class="futa-connect-title">KẾT NỐI FUTA GROUP</h2>
        <p class="futa-connect-description">
            Kết nối đa dạng hệ sinh thái FUTA Group qua App FUTA: mua vé Xe Phương Trang, Xe Buýt, Xe Hợp Đồng, Giao
            Hàng,...
        </p>

        <div class="futa-connect-images">
            <div class="futa-desktop-image">
                <img src="https://cdn.futabus.vn/futa-busline-cms-dev/1_ketnoi_3c401512ac/1_ketnoi_3c401512ac.svg"
                    alt="futa connect" loading="lazy">
            </div>

            <div class="futa-mobile-image">
                <img src="https://cdn.futabus.vn/futa-busline-web-cms-prod/Mobile_8c827bf843/Mobile_8c827bf843.png"
                    alt="futa connect" loading="lazy">
            </div>
        </div>
    </div>
</section>
<!-- ======================== END 5. FUTA CONNECT ======================== -->