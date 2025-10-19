@extends('layouts.admin')

@section('title', 'Sửa tin tức')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sửa tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.news.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active">Sửa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin tin tức</h3>
            </div>
            <form action="{{ route('staff.news.update', $tinTuc->ma_tin) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="tieu_de">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tieu_de') is-invalid @enderror"
                            id="tieu_de" name="tieu_de" value="{{ old('tieu_de', $tinTuc->tieu_de) }}" required>
                        @error('tieu_de')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ma_nha_xe">Nhà xe</label>
                        <select class="form-control @error('ma_nha_xe') is-invalid @enderror"
                            id="ma_nha_xe" name="ma_nha_xe">
                            <option value="">-- Tất cả nhà xe --</option>
                            @foreach($nhaXe as $nx)
                            <option value="{{ $nx->ma_nha_xe }}"
                                {{ old('ma_nha_xe', $tinTuc->ma_nha_xe) == $nx->ma_nha_xe ? 'selected' : '' }}>
                                {{ $nx->ten_nha_xe }}
                            </option>
                            @endforeach
                        </select>
                        @error('ma_nha_xe')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh đại diện</label>
                        
                        @if($tinTuc->hinh_anh)
                        <div class="mb-3">
                            <img src="{{ filter_var($tinTuc->hinh_anh, FILTER_VALIDATE_URL) ? $tinTuc->hinh_anh : asset($tinTuc->hinh_anh) }}" 
                                 alt="Current image" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
                        </div>
                        @endif

                        <div class="mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-primary active">
                                    <input type="radio" name="image_type" value="file" checked id="image_type_file"> 
                                    <i class="fas fa-upload"></i> Upload từ máy
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="image_type" value="url" id="image_type_url"> 
                                    <i class="fas fa-link"></i> Nhập URL
                                </label>
                            </div>
                        </div>

                        <!-- Upload file -->
                        <div id="file_upload_section">
                            <div class="custom-file">
                                <input type="file" name="hinh_anh" class="custom-file-input @error('hinh_anh') is-invalid @enderror" 
                                    id="imageFileInput" accept="image/*" onchange="previewImage(event)">
                                <label class="custom-file-label" for="imageFileInput">Chọn ảnh mới...</label>
                            </div>
                            <small class="form-text text-muted">Định dạng: JPG, PNG, GIF, WEBP. Tối đa 5MB. Để trống nếu không muốn thay đổi.</small>
                            @error('hinh_anh')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- URL input -->
                        <div id="url_input_section" style="display: none;">
                            <input type="url" name="image_url" class="form-control @error('image_url') is-invalid @enderror" 
                                placeholder="https://example.com/image.jpg" value="{{ old('image_url') }}"
                                onchange="previewImageUrl(event)">
                            <small class="form-text text-muted">Nhập URL của hình ảnh</small>
                            @error('image_url')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Preview -->
                        <div id="image_preview_container" class="mt-3" style="display: none;">
                            <img id="image_preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="clearImagePreview()">
                                <i class="fas fa-times"></i> Xóa ảnh preview
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="noi_dung">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('noi_dung') is-invalid @enderror"
                            id="noi_dung" name="noi_dung" rows="10" required>{{ old('noi_dung', $tinTuc->noi_dung) }}</textarea>
                        @error('noi_dung')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('staff.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Toggle giữa file upload và URL input
    $('input[name="image_type"]').change(function() {
        if ($(this).val() === 'file') {
            $('#file_upload_section').show();
            $('#url_input_section').hide();
        } else {
            $('#file_upload_section').hide();
            $('#url_input_section').show();
        }
        clearImagePreview();
    });

    // Preview ảnh từ file
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image_preview').attr('src', e.target.result);
                $('#image_preview_container').show();
            };
            reader.readAsDataURL(file);
            
            // Update label
            const fileName = file.name;
            $(event.target).next('.custom-file-label').text(fileName);
        }
    }

    // Preview ảnh từ URL
    function previewImageUrl(event) {
        const url = event.target.value;
        if (url) {
            $('#image_preview').attr('src', url);
            $('#image_preview_container').show();
            
            // Kiểm tra ảnh có load được không
            $('#image_preview').on('error', function() {
                alert('Không thể tải ảnh từ URL này. Vui lòng kiểm tra lại.');
                clearImagePreview();
            });
        }
    }

    // Xóa preview
    function clearImagePreview() {
        $('#image_preview').attr('src', '');
        $('#image_preview_container').hide();
        $('#imageFileInput').val('');
        $('.custom-file-label').text('Chọn ảnh mới...');
        $('input[name="image_url"]').val('');
    }
</script>
@endpush