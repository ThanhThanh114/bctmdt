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

    <div class="news-page-wrapper">
        <!-- Category Tabs & Search -->
        <div class="news-header-section">
            <div class="container-custom">
                <div class="news-tabs-container">
                    <!-- Category Tabs -->
                    <div class="news-tabs">
                        <div class="news-tab active" data-category="all">
                            <img src="/images/shape.png" alt="" class="tab-icon">
                            Tin Tức Tổng Hợp
                        </div>
                        <div class="news-tab" data-category="futa-bus-lines">
                            FUTA Bus Lines
                        </div>
                        <div class="news-tab" data-category="futa-city-bus">
                            FUTA City Bus
                        </div>
                        <div class="news-tab" data-category="promotion">
                            Khuyến Mãi
                        </div>
                        <div class="news-tab" data-category="awards">
                            Giải Thưởng
                        </div>
                        <div class="news-tab" data-category="rest-stop">
                            Trạm Dừng
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="news-search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="inputSearchNews" class="search-input" placeholder="Tìm kiếm tin tức...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured News Section -->
        <div class="container-custom">
            <div class="section-title-wrapper">
                <h2 class="section-title">Tin Tức Nổi Bật</h2>
                <div class="title-line"></div>
            </div>

            @if ($highlight_news->isNotEmpty())
                <div class="featured-news-grid">
                    <!-- Main Featured News (Large) - 1 ô to bên trái -->
                    @php $hero_news = $highlight_news->first(); @endphp
                    <div class="featured-main">
                        <a href="{{ route('news.show', $hero_news->ma_tin) }}" class="featured-card">
                            <div class="featured-image">
                                @if($hero_news->hinh_anh)
                                    @if(filter_var($hero_news->hinh_anh, FILTER_VALIDATE_URL))
                                        <img src="{{ $hero_news->hinh_anh }}" alt="{{ htmlspecialchars($hero_news->tieu_de) }}"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức'">
                                    @else
                                        <img src="{{ asset($hero_news->hinh_anh) }}" alt="{{ htmlspecialchars($hero_news->tieu_de) }}"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức'">
                                    @endif
                                @else
                                    <img src="{{ asset('assets/images/header.jpg') }}"
                                        alt="{{ htmlspecialchars($hero_news->tieu_de) }}" loading="lazy"
                                        onerror="this.src='https://via.placeholder.com/800x600/FF5722/ffffff?text=Tin+Tức'">
                                @endif
                            </div>
                            <div class="featured-content">
                                <h3 class="featured-title">{{ htmlspecialchars($hero_news->tieu_de) }}</h3>
                                <p class="featured-excerpt">
                                    {{ htmlspecialchars(mb_substr(strip_tags($hero_news->noi_dung), 0, 200, 'UTF-8')) . '...' }}
                                </p>
                                <span class="featured-time">{{ date("H:i d/m/Y", strtotime($hero_news->ngay_dang)) }}</span>
                            </div>
                        </a>
                    </div>

                    <!-- Side Featured News (Small) - 4 ô nhỏ bên phải -->
                    <div class="featured-side">
                        @foreach ($highlight_news->slice(1, 4) as $news)
                            <a href="{{ route('news.show', $news->ma_tin) }}" class="featured-card-small">
                                <div class="featured-image-small">
                                    @if($news->hinh_anh)
                                        @if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL))
                                            <img src="{{ $news->hinh_anh }}" alt="{{ htmlspecialchars($news->tieu_de) }}" loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        @else
                                            <img src="{{ asset($news->hinh_anh) }}" alt="{{ htmlspecialchars($news->tieu_de) }}"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        @endif
                                    @else
                                        <img src="{{ asset('assets/images/header.jpg') }}" alt="{{ htmlspecialchars($news->tieu_de) }}"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                    @endif
                                </div>
                                <div class="featured-content-small">
                                    <h4 class="featured-title-small">{{ htmlspecialchars($news->tieu_de) }}</h4>
                                    <span class="featured-time">{{ date("H:i d/m/Y", strtotime($news->ngay_dang)) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- FUTA City Bus Spotlight -->
            @if ($highlight_news->count() > 5)
                <div class="spotlight-section">
                    <div class="spotlight-badge">
                        <h3>Tiêu Điểm</h3>
                        <p>FUTA City Bus</p>
                    </div>
                    <div class="spotlight-carousel">
                        @foreach ($highlight_news->slice(5, 5) as $news)
                            <a href="{{ route('news.show', $news->ma_tin) }}" class="spotlight-card">
                                <div class="spotlight-image">
                                    @if($news->hinh_anh)
                                        @if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL))
                                            <img src="{{ $news->hinh_anh }}" alt="{{ htmlspecialchars($news->tieu_de) }}" loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        @else
                                            <img src="{{ asset($news->hinh_anh) }}" alt="{{ htmlspecialchars($news->tieu_de) }}"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                        @endif
                                    @else
                                        <img src="{{ asset('assets/images/header.jpg') }}" alt="{{ htmlspecialchars($news->tieu_de) }}"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/400x300/FF5722/ffffff?text=Tin+Tức'">
                                    @endif
                                </div>
                                <div class="spotlight-content">
                                    <h4>{{ htmlspecialchars($news->tieu_de) }}</h4>
                                    <span class="spotlight-time">{{ date("H:i d/m/Y", strtotime($news->ngay_dang)) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- All News Section -->
            <div class="section-title-wrapper">
                <h2 class="section-title">Tất Cả Tin Tức</h2>
                <div class="title-line"></div>
            </div>

            @if ($all_news->isNotEmpty())
                <div class="all-news-grid">
                    @foreach ($all_news as $news)
                        <article class="news-card-horizontal">
                            <div class="news-card-image">
                                @if($news->hinh_anh)
                                    @if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL))
                                        <img src="{{ $news->hinh_anh }}" alt="{{ htmlspecialchars($news->tieu_de) }}" loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                    @else
                                        <img src="{{ asset($news->hinh_anh) }}" alt="{{ htmlspecialchars($news->tieu_de) }}" loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                    @endif
                                @else
                                    <img src="{{ asset('assets/images/header.jpg') }}" alt="{{ htmlspecialchars($news->tieu_de) }}"
                                        loading="lazy"
                                        onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                @endif
                            </div>
                            <div class="news-card-content">
                                <h3 class="news-card-title">
                                    <a href="{{ route('news.show', $news->ma_tin) }}">
                                        {{ htmlspecialchars($news->tieu_de) }}
                                    </a>
                                </h3>
                                <p class="news-card-excerpt">
                                    {{ htmlspecialchars(mb_substr(strip_tags($news->noi_dung), 0, 200, 'UTF-8')) . '...' }}
                                </p>
                                <span class="news-card-time">{{ date("H:i d/m/Y", strtotime($news->ngay_dang)) }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="no-news-message">
                    <i class="fas fa-newspaper"></i>
                    <h3>Chưa có tin tức nào</h3>
                    <p>Hãy quay lại sau để xem những thông tin mới nhất từ FUTA Bus Lines!</p>
                </div>
            @endif

            <!-- Pagination -->
            @if ($total_pages > 1)
                <div class="pagination-wrapper">
                    <nav class="pagination-nav">
                        <!-- Previous Button -->
                        @if ($current_page > 1)
                            <a href="{{ route('news.news', ['page' => $current_page - 1]) }}"
                                class="pagination-btn pagination-prev">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @else
                            <span class="pagination-btn pagination-prev disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @endif

                        <!-- Page Numbers -->
                        @for ($i = 1; $i <= min(5, $total_pages); $i++)
                            <a href="{{ route('news.news', ['page' => $i]) }}"
                                class="pagination-btn {{ $i == $current_page ? 'active' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor

                        @if ($total_pages > 5)
                            @if ($current_page < $total_pages - 2)
                                <span class="pagination-dots">...</span>
                            @endif

                            @if ($total_pages > 6)
                                <a href="{{ route('news.news', ['page' => $total_pages - 1]) }}"
                                    class="pagination-btn {{ ($total_pages - 1) == $current_page ? 'active' : '' }}">
                                    {{ $total_pages - 1 }}
                                </a>
                            @endif

                            <a href="{{ route('news.news', ['page' => $total_pages]) }}"
                                class="pagination-btn {{ $total_pages == $current_page ? 'active' : '' }}">
                                {{ $total_pages }}
                            </a>
                        @endif

                        <!-- Next Button -->
                        @if ($current_page < $total_pages)
                            <a href="{{ route('news.news', ['page' => $current_page + 1]) }}"
                                class="pagination-btn pagination-next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-btn pagination-next disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/News.js') }}"></script>
@endsection