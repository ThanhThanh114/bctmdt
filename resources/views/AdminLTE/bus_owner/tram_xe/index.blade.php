@extends('layouts.admin')

@section('title', 'Quản lý Trạm xe')
@section('page-title', 'Danh sách Trạm xe')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Trạm xe</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt mr-2"></i>Danh sách trạm xe
                </h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm trạm xe...">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="tramXeTable">
                    <thead>
                        <tr>
                            <th style="width: 100px">Mã trạm</th>
                            <th>Tên trạm</th>
                            <th>Tỉnh/Thành phố</th>
                            <th>Địa chỉ</th>
                            <th style="width: 100px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tramXe as $tram)
                        <tr>
                            <td><span class="badge badge-primary">{{ $tram->ma_tram_xe }}</span></td>
                            <td><strong>{{ $tram->ten_tram }}</strong></td>
                            <td>{{ $tram->tinh_thanh ?? 'N/A' }}</td>
                            <td>{{ $tram->dia_chi ?? 'Chưa cập nhật' }}</td>
                            <td>
                                <a href="{{ route('bus-owner.tram-xe.show', $tram->ma_tram_xe) }}"
                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-info-circle mr-2"></i>Chưa có trạm xe nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tramXe->hasPages())
            <div class="card-footer clearfix">
                <div class="float-left">
                    <p class="text-muted">
                        Hiển thị {{ $tramXe->firstItem() }} - {{ $tramXe->lastItem() }}
                        trong tổng số {{ $tramXe->total() }} trạm xe
                    </p>
                </div>
                <div class="float-right">
                    {{ $tramXe->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Real-time search
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tramXeTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush