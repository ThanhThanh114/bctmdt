@extends('layouts.admin')

@section('title', 'Chi tiết Trạm xe')
@section('page-title', 'Chi tiết Trạm xe')
@section('breadcrumb', 'Trạm xe')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin trạm xe</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.tram-xe.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th style="width: 200px">Mã trạm:</th>
                            <td>{{ $tram->ma_tram_xe }}</td>
                        </tr>
                        <tr>
                            <th>Tên trạm:</th>
                            <td><strong>{{ $tram->ten_tram }}</strong></td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td>{{ $tram->dia_chi ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
