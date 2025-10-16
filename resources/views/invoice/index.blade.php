@extends('app')

@section('title', 'Tra cứu hóa đơn - FUTA Bus Lines')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/HoaDon.css') }}">

<div class="container">
    <h2 class="page-title">Tra cứu hóa đơn</h2>

    @if (session('error_message'))
    <div class="error-message">
        <p>{{ session('error_message') }}</p>
    </div>
    @endif

    <form action="{{ route('invoice.check') }}" method="POST" class="invoice-form">
        @csrf
        <div class="form-group">
            <label><i class="fa fa-user-secret"></i> Mã số bí mật</label>
            <input type="text" name="ma_bimat" placeholder="Nhập mã bí mật (ví dụ: VE001)" required>
        </div>
        {{-- <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fa fa-search"></i> Tra cứu
            </button>
        </div> --}}
    </form>
</div>
@endsection
