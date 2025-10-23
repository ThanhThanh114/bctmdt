@extends('layouts.admin')

@section('title', 'User Dashboard')

@section('page-title', 'Dashboard Người dùng')
@section('breadcrumb', 'Người dùng')

@section('content')
@include('AdminLTE.user.partials.info-boxes')

<div class="row">
    <div class="col-md-8">
        @include('AdminLTE.user.partials.upcoming-trips')
        @include('AdminLTE.user.partials.recent-bookings')
    </div>

    <div class="col-md-4">
        @include('AdminLTE.user.partials.user-info')
        @include('AdminLTE.user.partials.upgrade-section')
        @include('AdminLTE.user.partials.popular-routes')
        @include('AdminLTE.user.partials.profile-edit')
        @include('AdminLTE.user.partials.password-change')
        @include('AdminLTE.user.partials.quick-actions')
        @include('AdminLTE.user.partials.spending-chart')
    </div>
</div>
@endsection

@include('AdminLTE.user.partials.scripts')
