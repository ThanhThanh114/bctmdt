@extends('app')

@section('title', 'FUTA Bus Demo - Trang Chủ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/Index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Search-form.css') }}">
@endpush

@section('content')
    <div class="main-container">
        @include('home.search-form')

        @include('home.promo-slider')

        @include('home.stats')

        @include('home.popular-routes')

        @include('home.news')
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/js/Search-form.js') }}"></script>
@endsection