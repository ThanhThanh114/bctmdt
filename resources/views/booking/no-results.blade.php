@extends('layouts.header-search')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/DatVe.css') }}?v={{ time() }}">
@endpush
@section('content')
    <main class="new-background-color min-h-screen py-8">
        <div class="layout">
            <div class="max-w-2xl mx-auto">
                <!-- Icon và thông báo -->
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="mb-6">
                        <i class="fas fa-bus-alt text-gray-300" style="font-size: 120px;"></i>
                    </div>
                    
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">
                        Không tìm thấy chuyến xe
                    </h2>
                    
                    <p class="text-gray-600 mb-6 text-lg">
                        Rất tiếc, chúng tôi không tìm thấy chuyến xe phù hợp với yêu cầu của bạn.
                    </p>

                    @if(!empty($start) || !empty($end) || !empty($date))
                        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                            <h3 class="font-bold text-gray-700 mb-3">
                                <i class="fas fa-search orange mr-2"></i>
                                Thông tin tìm kiếm của bạn:
                            </h3>
                            <ul class="space-y-2 text-gray-600">
                                @if(!empty($start))
                                    <li>
                                        <i class="fas fa-map-marker-alt orange mr-2"></i>
                                        <strong>Điểm đi:</strong> {{ $start }}
                                    </li>
                                @endif
                                @if(!empty($end))
                                    <li>
                                        <i class="fas fa-map-marker-alt orange mr-2"></i>
                                        <strong>Điểm đến:</strong> {{ $end }}
                                    </li>
                                @endif
                                @if(!empty($date))
                                    <li>
                                        <i class="fas fa-calendar orange mr-2"></i>
                                        <strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                            <h4 class="font-bold text-blue-800 mb-2">
                                <i class="fas fa-lightbulb mr-2"></i>Gợi ý:
                            </h4>
                            <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                                <li>Thử thay đổi ngày đi</li>
                                <li>Kiểm tra lại điểm đi và điểm đến</li>
                                <li>Liên hệ hotline để được hỗ trợ: <strong>1900 6067</strong></li>
                            </ul>
                        </div>

                        <div class="flex gap-4 justify-center mt-8">
                            <a href="/" class="btn-primary px-8 py-3 rounded-lg font-bold inline-flex items-center gap-2">
                                <i class="fas fa-search"></i>
                                Tìm kiếm lại
                            </a>
                            <a href="/" class="btn-secondary px-8 py-3 rounded-lg font-bold inline-flex items-center gap-2">
                                <i class="fas fa-home"></i>
                                Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
