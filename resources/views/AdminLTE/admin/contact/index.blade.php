@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Liên hệ</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Liên hệ</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Tổng liên hệ</p>
                    </div>
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['today'] }}</h3>
                        <p>Hôm nay</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-day"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['this_week'] }}</h3>
                        <p>Tuần này</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-week"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['this_month'] }}</h3>
                        <p>Tháng này</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách liên hệ</h3>
                <div class="card-tools">
                    <button class="btn btn-success btn-sm" onclick="exportContacts()">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tên hoặc email"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="subject" class="form-control" placeholder="Chủ đề"
                                value="{{ request('subject') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block"><i
                                    class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Chủ đề</th>
                                <th>Nội dung</th>
                                <th>Ngày gửi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><strong>{{ $item->fullname }}</strong></td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone ?? 'N/A' }}</td>
                                <td>{{ Str::limit($item->subject, 30) }}</td>
                                <td>{{ Str::limit($item->message, 50) }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.contact.show', $item->id) }}"
                                        class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('admin.contact.destroy', $item->id) }}" method="POST"
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
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $contacts->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>
</section>

<script>
    function exportContacts() {
        window.location.href = "{{ route('admin.contact.export') }}?" + new URLSearchParams(window.location.search)
            .toString();
    }
</script>
@endsection