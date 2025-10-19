@extends('app')

@section('title', $news->tieu_de)

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/NewsDetail.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')

    @php
        function formatTimestamp($timestamp)
        {
            return date("H:i d/m/Y", strtotime($timestamp));
        }
    @endphp

    <main class="news-detail-page">
        <div class="news-detail-container">
            <!-- Article Content -->
            <div class="article-wrapper">
                <!-- Article Title -->
                <h3 class="article-title">{{ $news->tieu_de }}</h3>

                <!-- Created Date -->
                <div class="article-date">Created Date: {{ formatTimestamp($news->ngay_dang) }}</div>

                <!-- Featured Image -->
                @if($news->hinh_anh)
                    <div class="article-featured-image">
                        @if(filter_var($news->hinh_anh, FILTER_VALIDATE_URL))
                            <img src="{{ $news->hinh_anh }}" alt="{{ htmlspecialchars($news->tieu_de) }}" loading="lazy"
                                onerror="this.src='https://via.placeholder.com/1200x600/FF5722/ffffff?text=Tin+Tức'">
                        @else
                            <img src="{{ asset($news->hinh_anh) }}" alt="{{ htmlspecialchars($news->tieu_de) }}" loading="lazy"
                                onerror="this.src='https://via.placeholder.com/1200x600/FF5722/ffffff?text=Tin+Tức'">
                        @endif
                    </div>
                @endif

                <!-- Article Excerpt -->
                <div class="article-excerpt">
                    {{ htmlspecialchars(mb_substr(strip_tags($news->noi_dung), 0, 150, 'UTF-8')) }}...
                </div>

                <!-- Article Content -->
                <div class="article-content">
                    @php
                        // Format nội dung: chuyển line break thành paragraph
                        $content = $news->noi_dung;
                        // Loại bỏ khoảng trắng thừa
                        $content = trim($content);
                        // Tách thành các đoạn bằng double line break
                        $paragraphs = preg_split('/\r?\n\r?\n/', $content);
                        // Lọc bỏ đoạn trống
                        $paragraphs = array_filter($paragraphs, function ($p) {
                            return trim($p) !== '';
                        });
                    @endphp

                    @foreach($paragraphs as $paragraph)
                        <p>{!! nl2br(e(trim($paragraph))) !!}</p>
                    @endforeach
                </div>
            </div>

            <!-- Related News Section -->
            <div class="related-news-section">
                <div class="related-header">
                    <div class="related-title">Related News</div>
                    <div class="title-divider"></div>
                    <a href="{{ route('news.news') }}" class="see-all-link">
                        <span>See All</span>
                        <img src="/images/icons/ic_arrow_right.svg" alt="arrow">
                    </a>
                </div>

                <!-- Related News Grid -->
                <div class="related-news-grid">
                    @if(isset($related_news) && $related_news->count() > 0)
                        @foreach($related_news as $related)
                            <a href="{{ route('news.show', $related->ma_tin) }}" class="related-news-card">
                                <div class="related-image">
                                    @if($related->hinh_anh)
                                        @if(filter_var($related->hinh_anh, FILTER_VALIDATE_URL))
                                            <img src="{{ $related->hinh_anh }}" alt="{{ htmlspecialchars($related->tieu_de) }}"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                        @else
                                            <img src="{{ asset($related->hinh_anh) }}" alt="{{ htmlspecialchars($related->tieu_de) }}"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                        @endif
                                    @else
                                        <img src="{{ asset('assets/images/header.jpg') }}"
                                            alt="{{ htmlspecialchars($related->tieu_de) }}" loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/600x400/FF5722/ffffff?text=Tin+Tức'">
                                    @endif
                                </div>
                                <div class="related-content">
                                    <div class="related-news-title">
                                        {{ $related->tieu_de }}
                                    </div>
                                    <div class="related-excerpt">
                                        {{ htmlspecialchars(mb_substr(strip_tags($related->noi_dung), 0, 150, 'UTF-8')) }}...
                                    </div>
                                    <span class="related-time">{{ formatTimestamp($related->ngay_dang) }}</span>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <!-- Fallback: Show placeholder cards -->
                        <div class="no-related-news">
                            <p>Không có tin tức liên quan</p>
                            <a href="{{ route('news.news') }}" class="back-to-news-btn">
                                <i class="fas fa-arrow-left"></i> Quay lại trang tin tức
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/NewsDetail.js') }}"></script>
@endsection