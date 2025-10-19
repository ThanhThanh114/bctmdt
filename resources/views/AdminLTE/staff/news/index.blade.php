@extends('layouts.admin')

@section('title', 'Quản lý Tin tức')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tin tức</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Tổng tin tức</p>
                    </div>
                    <div class="icon"><i class="fas fa-newspaper"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['today'] }}</h3>
                        <p>Đăng hôm nay</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-day"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['this_month'] }}</h3>
                        <p>Đăng tháng này</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách tin tức</h3>
                <div class="card-tools">
                    <a href="{{ route('staff.news.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tiêu đề tin tức"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="nha_xe" class="form-control">
                                <option value="">-- Nhà xe --</option>
                                @foreach($nhaXe as $nx)
                                <option value="{{ $nx->ma_nha_xe }}"
                                    {{ request('nha_xe') == $nx->ma_nha_xe ? 'selected' : '' }}>{{ $nx->ten_nha_xe }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="tac_gia" class="form-control">
                                <option value="">-- Tác giả --</option>
                                @foreach($tacGia as $tg)
                                <option value="{{ $tg->id }}" {{ request('tac_gia') == $tg->id ? 'selected' : '' }}>
                                    {{ $tg->fullname ?? $tg->username }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i>
                                Tìm</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Nhà xe</th>
                                <th>Tác giả</th>
                                <th>Ngày đăng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tinTuc as $item)
                            <tr>
                                <td><strong>{{ $item->tieu_de }}</strong></td>
                                <td>{{ $item->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                                <td>{{ $item->user->fullname ?? $item->user->username ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->ngay_dang)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('staff.news.show', $item->ma_tin) }}"
                                        class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('staff.news.edit', $item->ma_tin) }}"
                                        class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('staff.news.destroy', $item->ma_tin) }}" method="POST"
                                        style="display:inline-block;" onsubmit="return confirm('Xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $tinTuc->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection