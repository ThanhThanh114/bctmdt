@extends('layouts.admin')

@section('title', 'Quản lý Vé khuyến mãi')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Vé khuyến mãi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vé khuyến mãi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Tổng vé KM</p>
                    </div>
                    <div class="icon"><i class="fas fa-gift"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['today'] }}</h3>
                        <p>Sử dụng hôm nay</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-day"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['this_month'] }}</h3>
                        <p>Sử dụng tháng này</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách vé khuyến mãi đã sử dụng</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                <!-- Search Form -->
                <form method="GET" action="{{ route('staff.promotions.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="ma_km" class="form-control">
                                <option value="">-- Chọn khuyến mãi --</option>
                                @foreach($khuyenMai as $km)
                                <option value="{{ $km->ma_km }}" {{ request('ma_km') == $km->ma_km ? 'selected' : '' }}>
                                    {{ $km->ten_km }} ({{ $km->giam_gia }}%)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Lọc
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('staff.promotions.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng</th>
                                <th>Mã vé</th>
                                <th>Khuyến mãi</th>
                                <th>Giảm giá</th>
                                <th>Chuyến xe</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($veKhuyenMai as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->datVe && $item->datVe->user)
                                    <strong>{{ $item->datVe->user->fullname ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $item->datVe->user->email }}</small>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->datVe)
                                    <code>{{ $item->datVe->ma_ve }}</code>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->khuyenMai)
                                    <strong>{{ $item->khuyenMai->ten_km }}</strong><br>
                                    <small class="text-muted">{{ $item->khuyenMai->ma_code }}</small>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->khuyenMai)
                                    <span class="badge badge-success">{{ $item->khuyenMai->giam_gia }}%</span>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->datVe && $item->datVe->chuyenXe)
                                    {{ $item->datVe->chuyenXe->ten_xe }}<br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->datVe->chuyenXe->ngay_di)->format('d/m/Y') }}
                                    </small>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->datVe)
                                    {{ \Carbon\Carbon::parse($item->datVe->ngay_dat)->format('d/m/Y H:i') }}
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->datVe)
                                    <div class="btn-group">
                                        <a href="{{ route('staff.bookings.show', $item->datVe->id) }}"
                                            class="btn btn-sm btn-info" title="Xem chi tiết vé">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>

                                        @if($item->datVe->trang_thai != 'Đã hủy')
                                        <button type="button" class="btn btn-sm btn-warning"
                                            onclick="editPromotion({{ $item->id }}, '{{ $item->datVe->ma_ve }}', '{{ $item->ma_km }}')"
                                            title="Đổi khuyến mãi">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>

                                        <form action="{{ route('staff.bookings.update-status', $item->datVe->id) }}"
                                            method="POST" style="display:inline-block;"
                                            onsubmit="return confirm('Hủy vé này?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Đã hủy">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hủy vé">
                                                <i class="fas fa-times"></i> Hủy
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $veKhuyenMai->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Promotion Modal -->
<div class="modal fade" id="editPromotionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa mã khuyến mãi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editPromotionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mã vé:</label>
                        <input type="text" class="form-control" id="edit_ma_ve" readonly>
                    </div>
                    <div class="form-group">
                        <label>Mã khuyến mãi hiện tại:</label>
                        <input type="text" class="form-control" id="edit_current_km" readonly>
                    </div>
                    <div class="form-group">
                        <label>Mã khuyến mãi mới:</label>
                        <select class="form-control" name="ma_km" id="edit_ma_km" required>
                            <option value="">-- Chọn mã khuyến mãi --</option>
                            @foreach($khuyenMaiList ?? [] as $km)
                            <option value="{{ $km->ma_km }}">
                                {{ $km->ma_km }} - {{ $km->ten_km }}
                                @if($km->giam_gia)
                                ({{ $km->giam_gia }}%)
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editPromotion(id, maVe, maKm) {
        $('#edit_ma_ve').val(maVe);
        $('#edit_current_km').val(maKm);
        $('#edit_ma_km').val('');
        $('#editPromotionForm').attr('action', '/staff/promotions/' + id);
        $('#editPromotionModal').modal('show');
    }

    $(document).ready(function() {
        $('#editPromotionForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editPromotionModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra: ' + (xhr.responseJSON?.message || 'Vui lòng thử lại'));
                }
            });
        });
    });
</script>
@endpush