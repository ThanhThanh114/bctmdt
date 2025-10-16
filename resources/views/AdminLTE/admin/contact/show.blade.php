@extends('layouts.admin')
@section('title', 'Chi tiết Liên hệ')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chi tiết Liên hệ #{{ $contact->id }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contact.index') }}">Liên hệ</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Nội dung liên hệ</h3>
                    </div>
                    <div class="card-body">
                        <h4>{{ $contact->subject }}</h4>
                        <hr>
                        <p style="white-space: pre-wrap;">{{ $contact->message }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">Thông tin người gửi</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Tên:</strong><br>{{ $contact->fullname }}</p>
                        <p><strong>Email:</strong><br>{{ $contact->email }}</p>
                        <p><strong>SĐT:</strong><br>{{ $contact->phone ?? 'Không có' }}</p>
                        <p><strong>Ngày
                                gửi:</strong><br>{{ \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary btn-block"><i
                                class="fas fa-arrow-left"></i> Quay lại</a>
                        <a href="mailto:{{ $contact->email }}" class="btn btn-primary btn-block mt-2"><i
                                class="fas fa-reply"></i> Trả lời Email</a>
                        <form action="{{ route('admin.contact.destroy', $contact->id) }}" method="POST" class="mt-2"
                            onsubmit="return confirm('Xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block"><i class="fas fa-trash"></i>
                                Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection