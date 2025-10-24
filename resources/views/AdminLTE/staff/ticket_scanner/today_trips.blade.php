@extends('layouts.admin')

@section('title', 'Chuyến Xe Hôm Nay')

@section('page-title', 'Chuyến Xe Hôm Nay')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('staff.ticket-scanner.index') }}">Soát vé</a></li>
<li class="breadcrumb-item active">Chuyến xe hôm nay</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                Hiển thị các chuyến xe chạy hôm nay: <strong>{{ date('d/m/Y') }}</strong>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @forelse($tripsWithStats as $item)
            <div class="card mb-3">
                <div class="card-header {{ $item['checked_in'] == $item['total_bookings'] && $item['total_bookings'] > 0 ? 'bg-success' : 'bg-primary' }}">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h5 class="mb-0">
                                <i class="fas fa-bus mr-2"></i>
                                <strong>{{ $item['trip']->ten_xe ?? $item['trip']->ma_xe }}</strong>
                            </h5>
                            <small><i class="fas fa-building mr-1"></i>{{ $item['trip']->nhaXe->ten_nha_xe ?? 'N/A' }}</small>
                        </div>
                        <div class="col-md-4">
                            <span class="text-white">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <strong>{{ $item['trip']->tramDi->ten_tram ?? 'N/A' }}</strong>
                                <i class="fas fa-arrow-right mx-2"></i>
                                <strong>{{ $item['trip']->tramDen->ten_tram ?? 'N/A' }}</strong>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <i class="fas fa-clock mr-1"></i>
                            <strong>{{ $item['trip']->gio_di }}</strong>
                        </div>
                        <div class="col-md-2">
                            <span class="badge badge-light">{{ $item['trip']->loai_xe }}</span>
                            <span class="badge badge-light ml-2">{{ $item['trip']->so_cho }} ghế</span>
                        </div>
                        <div class="col-md-1 text-right">
                            <a href="{{ route('staff.ticket-scanner.trip', $item['trip']->id) }}" 
                               class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <small class="text-muted">
                                <i class="fas fa-user mr-1"></i>{{ $item['trip']->ten_tai_xe ?? 'N/A' }}
                                @if($item['trip']->sdt_tai_xe)
                                    - <a href="tel:{{ $item['trip']->sdt_tai_xe }}">{{ $item['trip']->sdt_tai_xe }}</a>
                                @endif
                            </small>
                        </div>
                        <div class="col-md-7">
                            <div class="row text-center">
                                <div class="col-3">
                                    <h5 class="mb-0 text-info">{{ $item['total_bookings'] }}</h5>
                                    <small class="text-muted">Tổng vé</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0 text-success">{{ $item['checked_in'] }}</h5>
                                    <small class="text-muted">Đã lên</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0 text-warning">{{ $item['not_checked_in'] }}</h5>
                                    <small class="text-muted">Chưa lên</small>
                                </div>
                                <div class="col-3">
                                    @if($item['total_bookings'] > 0)
                                        <h5 class="mb-0 text-primary">{{ number_format(($item['checked_in'] / $item['total_bookings']) * 100, 1) }}%</h5>
                                        <small class="text-muted">Tỷ lệ</small>
                                    @else
                                        <h5 class="mb-0 text-muted">0%</h5>
                                        <small class="text-muted">Tỷ lệ</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @if($item['total_bookings'] > 0)
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ ($item['checked_in'] / $item['total_bookings']) * 100 }}%"
                                     aria-valuenow="{{ $item['checked_in'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $item['total_bookings'] }}">
                                    {{ number_format(($item['checked_in'] / $item['total_bookings']) * 100, 1) }}%
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Không có chuyến xe nào hôm nay</h4>
                </div>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($tripsWithStats->hasPages())
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Hiển thị {{ $tripsWithStats->firstItem() }} - {{ $tripsWithStats->lastItem() }} 
                            trong tổng số {{ $tripsWithStats->total() }} chuyến xe
                        </div>
                        <div>
                            {{ $tripsWithStats->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
