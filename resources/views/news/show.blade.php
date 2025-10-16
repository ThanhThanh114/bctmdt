@extends('app')

@section('title', $news->tieu_de)

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')

@php
function formatTimestamp($timestamp)
{
    return date("H:i d/m/Y", strtotime($timestamp));
}
@endphp
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 50px 0;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        <!-- Back Button -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('news.news') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #6c757d; text-decoration: none; font-size: 0.9rem; transition: color 0.3s ease;">
                <i class="fas fa-arrow-left"></i>
                Quay lại danh sách tin tức
            </a>
        </div>

        <!-- Article Card -->
        <article style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <!-- Article Header -->
            <div style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 40px; text-align: center;">
                <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 20px; line-height: 1.2;">
                    {{ htmlspecialchars($news->tieu_de) }}
                </h1>
                <div style="display: flex; justify-content: center; gap: 30px; opacity: 0.9;">
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar"></i>
                        {{ formatTimestamp($news->ngay_dang) }}
                    </span>
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-tag"></i>
                        Tin tức
                    </span>
                </div>
            </div>

            <!-- Article Image -->
            <div style="width: 100%; height: 400px; overflow: hidden;">
                <img src="{{ asset('assets/images/' . $news->hinh_anh) }}"
                     alt="{{ htmlspecialchars($news->tieu_de) }}"
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Article Content -->
            <div style="padding: 40px;">
                <div style="font-size: 1.1rem; line-height: 1.8; color: #333; margin-bottom: 30px;">
                    {!! nl2br(e($news->noi_dung)) !!}
                </div>

                <!-- Article Footer -->
                <div style="border-top: 1px solid #e9ecef; padding-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="color: #6c757d; font-size: 0.9rem;">
                        <strong>FUTA Bus Lines</strong> - Cùng bạn trên mọi nẻo đường
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <button onclick="window.print()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer; font-size: 0.9rem;">
                            <i class="fas fa-print"></i> In bài viết
                        </button>
                        <button onclick="shareArticle()" style="background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer; font-size: 0.9rem;">
                            <i class="fas fa-share"></i> Chia sẻ
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related News Section -->
        <div style="margin-top: 50px; text-align: center;">
            <h3 style="color: #1a1a1a; margin-bottom: 30px; font-size: 1.8rem;">Tin tức liên quan</h3>
            <p style="color: #666; margin-bottom: 40px;">Khám phá thêm những thông tin hữu ích khác từ FUTA Bus Lines</p>
            <a href="{{ route('news.news') }}" style="display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(45deg, #ff6600, #ff8533); color: white; padding: 15px 30px; border-radius: 30px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-newspaper"></i>
                Xem tất cả tin tức
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/News.js') }}"></script>
@endsection
