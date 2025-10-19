@extends('layouts.admin')
@section('title', 'Chi tiết Tin tức')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chi tiết Tin tức</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tintuc.index') }}">Tin tức</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">{{ $tinTuc->tieu_de }}</h3>
                        </div>
                        <div class="card-body">
                            @if($tinTuc->hinh_anh)
                                <div class="mb-3">
                                    @if(filter_var($tinTuc->hinh_anh, FILTER_VALIDATE_URL))
                                        {{-- URL ảnh --}}
                                        <img src="{{ $tinTuc->hinh_anh }}" alt="Image" class="img-fluid"
                                            onerror="this.src='https://via.placeholder.com/800x400/ccc/666?text=Lỗi+tải+ảnh'">
                                        <p class="text-muted mt-2"><small><i class="fas fa-link"></i> URL:
                                                {{ $tinTuc->hinh_anh }}</small></p>
                                    @else
                                        {{-- File upload --}}
                                        <img src="{{ asset($tinTuc->hinh_anh) }}" alt="Image" class="img-fluid"
                                            onerror="this.src='https://via.placeholder.com/800x400/ccc/666?text=Lỗi+tải+ảnh'">
                                        <p class="text-muted mt-2"><small><i class="fas fa-file-image"></i> File:
                                                {{ basename($tinTuc->hinh_anh) }}</small></p>
                                    @endif
                                </div>
                            @endif
                            <div style="white-space: pre-wrap;">{{ $tinTuc->noi_dung }}</div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                Đăng bởi <strong>{{ $tinTuc->user->fullname ?? 'N/A' }}</strong>
                                vào {{ \Carbon\Carbon::parse($tinTuc->ngay_dang)->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Thông tin</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Mã tin:</strong><br>{{ $tinTuc->ma_tin }}</p>
                            <p><strong>Nhà xe:</strong><br>{{ $tinTuc->nhaXe->ten_nha_xe ?? 'Không có' }}</p>
                            <p><strong>Tác giả:</strong><br>{{ $tinTuc->user->fullname ?? 'N/A' }}</p>
                            <p><strong>Ngày
                                    đăng:</strong><br>{{ \Carbon\Carbon::parse($tinTuc->ngay_dang)->format('d/m/Y H:i:s') }}
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.tintuc.index') }}" class="btn btn-secondary btn-block"><i
                                    class="fas fa-arrow-left"></i> Quay lại</a>
                            <a href="{{ route('admin.tintuc.edit', $tinTuc->ma_tin) }}"
                                class="btn btn-warning btn-block mt-2"><i class="fas fa-edit"></i> Chỉnh sửa</a>
                            <form action="{{ route('admin.tintuc.destroy', $tinTuc->ma_tin) }}" method="POST" class="mt-2"
                                onsubmit="return confirm('Xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block"><i class="fas fa-trash"></i>
                                    Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection