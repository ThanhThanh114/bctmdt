@extends('layouts.admin')

@section('title', 'Quản lý Nhà xe')
@section('page-title', 'Thông tin Nhà xe')
@section('breadcrumb', 'Quản lý Nhà xe')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin chi tiết</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.nha-xe.edit', $busCompany->ma_nha_xe) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 200px">Mã nhà xe:</th>
                            <td>{{ $busCompany->ma_nha_xe }}</td>
                        </tr>
                        <tr>
                            <th>Tên nhà xe:</th>
                            <td><strong>{{ $busCompany->ten_nha_xe }}</strong></td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td>{{ $busCompany->dia_chi ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $busCompany->so_dien_thoai ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $busCompany->email ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thống kê nhanh</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-bus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng chuyến xe</span>
                        <span class="info-box-number">{{ $busCompany->chuyenXe()->count() }}</span>
                    </div>
                </div>

                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Vé đã bán (tháng này)</span>
                        <span class="info-box-number">
                            {{ $busCompany->chuyenXe()
                                ->join('dat_ve', 'chuyen_xe.id', '=', 'dat_ve.chuyen_xe_id')
                                ->whereMonth('dat_ve.ngay_dat', date('m'))
                                ->where('dat_ve.trang_thai', 'Đã thanh toán')
                                ->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection