@extends('layouts.staff')

@section('title', 'Staff Trip Details')

@section('page-title', 'Chi tiết chuyến xe')
@section('breadcrumb', 'Nhân viên')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin chuyến xe</h3>
                <div class="card-tools">
                    <a href="{{ route('staff.trips.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Tuyến đường:</th>
                                <td>{{ $trip->route_name }}</td>
                            </tr>
                            <tr>
                                <th>Ngày đi:</th>
                                <td>{{ $trip->ngay_di ? \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Giờ đi:</th>
                                <td><strong class="text-primary">{{ $trip->gio_di ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Nhà xe:</th>
                                <td>{{ $trip->nhaXe->ten_nha_xe ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Giá vé:</th>
                                <td><strong class="text-success">{{ number_format($trip->gia_ve ?? 0, 0, ',', '.') }} VNĐ</strong></td>
                            </tr>
                            <tr>
                                <th>Số chỗ:</th>
                                <td>{{ $trip->available_seats }}/{{ $trip->so_cho }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($trip->status == 'active')
                                        <span class="badge badge-success">Hoạt động</span>
                                    @else
                                        <span class="badge badge-secondary">Không hoạt động</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Thông tin tuyến đường</h5>
                            </div>
                            <div class="card-body text-center">
                                @if($trip->tramDi && $trip->tramDen)
                                <div class="mb-3">
                                    <i class="fas fa-map-marker-alt text-success fa-2x"></i>
                                    <h6 class="mt-2">{{ $trip->tramDi->ten_tram ?? 'N/A' }}</h6>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-arrow-down text-primary"></i>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-map-marker-alt text-danger fa-2x"></i>
                                    <h6 class="mt-2">{{ $trip->tramDen->ten_tram ?? 'N/A' }}</h6>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
