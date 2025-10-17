@extends('layouts.admin')

@section('title', 'Quản lý chuyến xe')

@section('page-title', 'Quản lý chuyến xe')
@section('breadcrumb', 'Chuyến xe')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách chuyến xe của {{ $bus_company->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('bus-owner.trips.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Thêm chuyến xe
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-header border-0 bg-light">
                <form method="GET" id="searchForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="small mb-1"><i class="fas fa-search mr-1"></i>Tìm kiếm</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Tên chuyến, tuyến đường..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1"><i class="fas fa-car mr-1"></i>Loại xe</label>
                            <select name="loai_xe" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="Giường nằm" {{ request('loai_xe') == 'Giường nằm' ? 'selected' : '' }}>Giường nằm</option>
                                <option value="Ghế ngồi" {{ request('loai_xe') == 'Ghế ngồi' ? 'selected' : '' }}>Ghế ngồi</option>
                                <option value="Limousine" {{ request('loai_xe') == 'Limousine' ? 'selected' : '' }}>Limousine</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1"><i class="fas fa-route mr-1"></i>Loại chuyến</label>
                            <select name="loai_chuyen" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="Một chiều" {{ request('loai_chuyen') == 'Một chiều' ? 'selected' : '' }}>Một chiều</option>
                                <option value="Khứ hồi" {{ request('loai_chuyen') == 'Khứ hồi' ? 'selected' : '' }}>Khứ hồi</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1"><i class="fas fa-calendar mr-1"></i>Từ ngày</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="small mb-1 d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary mr-1" title="Tìm kiếm">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                            <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-secondary" title="Làm mới">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="dataTable">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Tuyến đường</th>
                            <th>Ngày/Giờ khởi hành</th>
                            <th>Loại xe</th>
                            <th>Giá vé</th>
                            <th>Số ghế</th>
                            <th>Trạng thái</th>
                            <th>Loại chuyến</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trips as $trip)
                        <tr>
                            <td><strong class="text-primary">#{{ $trip->id }}</strong></td>
                            <td>
                                <strong>{{ $trip->ten_xe }}</strong><br>
                                <small class="text-muted">
                                    @if($trip->tramDi && $trip->tramDen)
                                    {{ $trip->tramDi->ten_tram }} → {{ $trip->tramDen->ten_tram }}
                                    @else
                                    Chưa có thông tin tuyến
                                    @endif
                                </small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') }}<br>{{ \Carbon\Carbon::parse($trip->gio_di)->format('H:i') }}
                            </td>
                            <td>{{ $trip->loai_xe ?? 'N/A' }}</td>
                            <td>
                                <strong>{{ number_format($trip->gia_ve) }}đ</strong>
                            </td>
                            <td>
                                @php
                                $available = $trip->so_cho - $trip->so_ve;
                                @endphp
                                <span
                                    class="badge {{ $available > 10 ? 'badge-success' : ($available > 0 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $available }}/{{ $trip->so_cho }}
                                </span>
                            </td>
                            <td>
                                @php
                                try {
                                $tripDate = \Carbon\Carbon::parse($trip->ngay_di)->setTimeFromTimeString($trip->gio_di);
                                $isPast = $tripDate->isPast();
                                } catch (\Exception $e) {
                                $isPast = false;
                                }
                                @endphp
                                @if($isPast)
                                <span class="badge badge-secondary">Đã qua</span>
                                @else
                                <span class="badge badge-success">Sắp khởi hành</span>
                                @endif
                            </td>
                            <td>
                                @if($trip->loai_chuyen == 'Một chiều')
                                <span class="badge badge-info">Một chiều</span>
                                @else
                                <span class="badge badge-primary">Khứ hồi</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('bus-owner.trips.show', $trip->id) }}"
                                        class="btn btn-sm btn-info"
                                        title="Xem chi tiết"
                                        data-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bus-owner.trips.edit', $trip->id) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Chỉnh sửa"
                                        data-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $trip->id }}"
                                        data-name="{{ $trip->ten_xe }}"
                                        title="Xóa"
                                        data-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $trip->id }}"
                                    method="POST"
                                    action="{{ route('bus-owner.trips.destroy', $trip->id) }}"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-bus fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Chưa có chuyến xe nào</p>
                                <a href="{{ route('bus-owner.trips.create') }}" class="btn btn-primary">Thêm chuyến xe
                                    đầu tiên</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $trips->count() }}</h3>
                <p>Tổng chuyến xe</p>
            </div>
            <div class="icon">
                <i class="fas fa-bus"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                @php
                $upcomingTrips = $trips->filter(function($trip) {
                try {
                $tripDate = \Carbon\Carbon::parse($trip->ngay_di)->setTimeFromTimeString($trip->gio_di);
                return $tripDate->isFuture();
                } catch (\Exception $e) {
                return false;
                }
                })->count();
                @endphp
                <h3>{{ $upcomingTrips }}</h3>
                <p>Chuyến sắp tới</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($trips->sum('so_cho')) }}</h3>
                <p>Tổng số ghế</p>
            </div>
            <div class="icon">
                <i class="fas fa-chair"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                @php
                $availableSeats = $trips->sum(function($trip) {
                return $trip->so_cho - $trip->so_ve;
                });
                @endphp
                <h3>{{ number_format($availableSeats) }}</h3>
                <p>Ghế còn trống</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="small-box-footer">
                &nbsp;
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-box {
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
        position: relative;
        display: block;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, .125);
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
    }

    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 3rem;
        color: rgba(255, 255, 255, .15);
    }

    .small-box .inner {
        padding: 10px;
    }

    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }

    .small-box p {
        font-size: 1rem;
        margin: 0;
    }

    .small-box-footer {
        background-color: rgba(0, 0, 0, .1);
        color: rgba(255, 255, 255, .8);
        display: block;
        padding: 3px 10px;
        position: relative;
        text-decoration: none;
        transition: all .15s linear;
    }

    .small-box-footer:hover {
        text-decoration: none;
        color: #fff;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle delete button click with SweetAlert
        $('.btn-delete').on('click', function() {
            const tripId = $(this).data('id');
            const tripName = $(this).data('name');

            Swal.fire({
                title: 'Xác nhận xóa?',
                html: `Bạn có chắc chắn muốn xóa chuyến xe:<br><strong>${tripName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Xóa',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the delete form
                    $(`#delete-form-${tripId}`).submit();
                }
            });
        });

        // Auto-submit form on select change
        $('select[name="loai_xe"], select[name="loai_chuyen"]').on('change', function() {
            $('#searchForm').submit();
        });

        // Handle Enter key in search input
        $('input[name="search"]').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#searchForm').submit();
            }
        });
    });
</script>
@endpush