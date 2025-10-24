@extends('layouts.admin')

@section('content')
@include('AdminLTE.admin.partials.hidden-data-holders')
@include('AdminLTE.admin.partials.debug-info')
@include('AdminLTE.admin.partials.metric-cards')
@include('AdminLTE.admin.partials.transport-stats')
@include('AdminLTE.admin.partials.management-modules')
@include('AdminLTE.admin.partials.revenue-bars')
@include('AdminLTE.admin.partials.top-lists')
@include('AdminLTE.admin.partials.recent-users-top-customers')
@include('AdminLTE.admin.partials.map-section')
@include('AdminLTE.admin.partials.recent-trips')
@include('AdminLTE.admin.partials.recent-bookings')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/admin-dashboard.js') }}"></script>
@endpush
