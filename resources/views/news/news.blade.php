@extends('app')

@section('title', 'Tin Tức - FUTA Bus Lines')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/News.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')

    @php
        function formatTimestamp($timestamp)
        {
            return date("H:i d/m/Y", strtotime($timestamp));
        }
    @endphp

    </head>
    <div class="news-container">
        <!-- Hero Section - Tin Nổi Bật -->
        @if ($highlight_news->isNotEmpty())
            @php $hero_news = $highlight_news->first(); @endphp
            <section class="hero-section">
                <div class="row g-0">
                    <div class="col-lg-6">
                        @if($hero_news->hinh_anh && file_exists(public_path('assets/images/' . $hero_news->hinh_anh)))
                            <img src="{{ asset('assets/images/' . $hero_news->hinh_anh) }}"
                                alt="{{ htmlspecialchars($hero_news->tieu_de) }}" class="news-image">
                        @else
                            <img src="{{ asset('assets/images/header.jpg') }}" alt="{{ htmlspecialchars($hero_news->tieu_de) }}"
                                class="news-image"
                                onerror="this.src='https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức'">
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-content">
                            <div class="hero-badge">
                                <i class="fas fa-star"></i> Tin Nổi Bật
                            </div>
                            <h2 class="hero-title">{{ htmlspecialchars($hero_news->tieu_de) }}</h2>
                            <p class="hero-excerpt">
                                {{ htmlspecialchars(mb_substr($hero_news->noi_dung, 0, 200, 'UTF-8')) . '...' }}
                            </p>
                            <div class="hero-meta">
                                <span><i class="fas fa-calendar"></i>
                                    {{ date("d/m/Y", strtotime($hero_news->ngay_dang)) }}</span>
                                <span><i class="fas fa-clock"></i> {{ date("H:i", strtotime($hero_news->ngay_dang)) }}</span>
                            </div>
                            <a href="{{ route('news.show', $hero_news->ma_tin) }}" class="read-more-btn">
                                <i class="fas fa-arrow-right"></i>
                                Đọc Thêm
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Section Header -->
        <div class="section-header">
            <h2><i class="fas fa-newspaper"></i> Tất Cả Tin Tức</h2>
            <p>Khám phá những thông tin mới nhất từ FUTA Bus Lines</p>
        </div>

        <!-- All News Grid -->
        @if ($all_news->isNotEmpty())
            <div class="news-grid">
                @foreach ($all_news as $news)
                    <article class="news-card-modern">
                        @if($news->hinh_anh && file_exists(public_path('assets/images/' . $news->hinh_anh)))
                            <img src="{{ asset('assets/images/' . $news->hinh_anh) }}" alt="{{ htmlspecialchars($news->tieu_de) }}"
                                class="news-image">
                        @else
                            <img src="{{ asset('assets/images/header.jpg') }}" alt="{{ htmlspecialchars($news->tieu_de) }}"
                                class="news-image" onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                        @endif
                        <div class="news-content">
                            <div class="news-meta">
                                <span class="news-category">
                                    <i class="fas fa-tag"></i> Tin tức
                                </span>
                                <span>
                                    <i class="fas fa-calendar"></i> {{ date("d/m/Y", strtotime($news->ngay_dang)) }}
                                </span>
                            </div>
                            <h3 class="news-title">{{ htmlspecialchars($news->tieu_de) }}</h3>
                            <p class="news-excerpt">
                                {{ htmlspecialchars(mb_substr($news->noi_dung, 0, 150, 'UTF-8')) . '...' }}
                            </p>
                            <a href="{{ route('news.show', $news->ma_tin) }}" class="read-more-btn">
                                <i class="fas fa-arrow-right"></i>
                                Đọc Thêm
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="text-center" style="padding: 60px 20px;">
                <i class="fas fa-newspaper" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="color: #666; margin-bottom: 15px;">Chưa có tin tức nào</h3>
                <p style="color: #999;">Hãy quay lại sau để xem những thông tin mới nhất từ FUTA Bus Lines!</p>
            </div>
        @endif

        <!-- Modern Pagination -->
        @if ($total_pages > 1)
            <div class="pagination-modern">
                @if ($current_page > 1)
                    <a href="{{ route('news.news', ['page' => $current_page - 1]) }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i> Trước
                    </a>
                @endif

                @for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++)
                    <a href="{{ route('news.news', ['page' => $i]) }}"
                        class="pagination-btn {{ $i == $current_page ? 'active' : '' }}">
                        {{ $i }}
                    </a>
                @endfor

                @if ($current_page < $total_pages)
                    <a href="{{ route('news.news', ['page' => $current_page + 1]) }}" class="pagination-btn">
                        Sau <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        @endif
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('assets/js/News.js') }}"></script>
@endsection