@extends('layouts.admin')

@section('title', 'Chi tiết tin tức')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.news.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $tinTuc->tieu_de }}</h3>
                <div class="card-tools">
                    <a href="{{ route('staff.news.edit', $tinTuc->ma_tin) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($tinTuc->hinh_anh)
                <div class="text-center mb-3">
                    <img src="{{ asset($tinTuc->hinh_anh) }}" alt="{{ $tinTuc->tieu_de }}" class="img-fluid" style="max-width: 600px;">
                </div>
                @endif

                <dl class="row">
                    <dt class="col-sm-2">Nhà xe:</dt>
                    <dd class="col-sm-10">{{ $tinTuc->nhaXe->ten_nha_xe ?? 'Tất cả' }}</dd>

                    <dt class="col-sm-2">Tác giả:</dt>
                    <dd class="col-sm-10">{{ $tinTuc->user->fullname ?? $tinTuc->user->username ?? 'N/A' }}</dd>

                    <dt class="col-sm-2">Ngày đăng:</dt>
                    <dd class="col-sm-10">{{ \Carbon\Carbon::parse($tinTuc->ngay_dang)->format('d/m/Y H:i') }}</dd>
                </dl>

                <hr>

                <h5>Nội dung:</h5>
                <div class="content-text">
                    {!! nl2br(e($tinTuc->noi_dung)) !!}
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('staff.news.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('staff.news.edit', $tinTuc->ma_tin) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <form action="{{ route('staff.news.destroy', $tinTuc->ma_tin) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa tin tức?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection