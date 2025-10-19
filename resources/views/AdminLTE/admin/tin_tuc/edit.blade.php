@extends('layouts.admin')
@section('title', 'Chỉnh sửa Tin tức')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa Tin tức</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tintuc.index') }}">Tin tức</a></li>
                        <li class="breadcrumb-item active">Sửa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.tintuc.update', $tinTuc->ma_tin) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" name="tieu_de"
                                        class="form-control @error('tieu_de') is-invalid @enderror"
                                        value="{{ old('tieu_de', $tinTuc->tieu_de) }}" required>
                                    @error('tieu_de')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label>Nội dung <span class="text-danger">*</span></label>
                                    <textarea name="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror"
                                        rows="15" required>{{ old('noi_dung', $tinTuc->noi_dung) }}</textarea>
                                    @error('noi_dung')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group">
                                    <label>Hình ảnh đại diện</label>

                                    @php
                                        $isCurrentUrl = $tinTuc->hinh_anh && filter_var($tinTuc->hinh_anh, FILTER_VALIDATE_URL);
                                        $isCurrentFile = $tinTuc->hinh_anh && !filter_var($tinTuc->hinh_anh, FILTER_VALIDATE_URL);
                                    @endphp

                                    <div class="mb-3">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-outline-primary {{ !$isCurrentUrl ? 'active' : '' }}">
                                                <input type="radio" name="image_type" value="file" {{ !$isCurrentUrl ? 'checked' : '' }} id="image_type_file">
                                                <i class="fas fa-upload"></i> Upload từ máy
                                            </label>
                                            <label class="btn btn-outline-primary {{ $isCurrentUrl ? 'active' : '' }}">
                                                <input type="radio" name="image_type" value="url" {{ $isCurrentUrl ? 'checked' : '' }} id="image_type_url">
                                                <i class="fas fa-link"></i> Nhập URL
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Upload file -->
                                    <div id="file_upload_section" style="display: {{ $isCurrentUrl ? 'none' : 'block' }};">
                                        <div class="custom-file">
                                            <input type="file" name="hinh_anh"
                                                class="custom-file-input @error('hinh_anh') is-invalid @enderror"
                                                id="imageFileInput" accept="image/*" onchange="previewImage(event)">
                                            <label class="custom-file-label" for="imageFileInput">Chọn ảnh mới...</label>
                                        </div>
                                        <small class="form-text text-muted">Định dạng: JPG, PNG, GIF, WEBP. Tối đa
                                            5MB</small>
                                        @error('hinh_anh')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <!-- URL input -->
                                    <div id="url_input_section" style="display: {{ $isCurrentUrl ? 'block' : 'none' }};">
                                        <input type="url" name="image_url"
                                            class="form-control @error('image_url') is-invalid @enderror"
                                            placeholder="https://example.com/image.jpg"
                                            value="{{ old('image_url', $isCurrentUrl ? $tinTuc->hinh_anh : '') }}"
                                            onchange="previewImageUrl(event)">
                                        <small class="form-text text-muted">Nhập URL của hình ảnh</small>
                                        @error('image_url')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <!-- Preview ảnh hiện tại -->
                                    @if($tinTuc->hinh_anh)
                                        <div id="current_image_container" class="mt-3">
                                            <p class="mb-2"><strong>Ảnh hiện tại:</strong></p>
                                            @if($isCurrentUrl)
                                                <img src="{{ $tinTuc->hinh_anh }}" alt="Current Image" class="img-thumbnail"
                                                    style="max-width: 100%; max-height: 300px;">
                                            @else
                                                <img src="{{ asset($tinTuc->hinh_anh) }}" alt="Current Image" class="img-thumbnail"
                                                    style="max-width: 100%; max-height: 300px;">
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Preview ảnh mới -->
                                    <div id="image_preview_container" class="mt-3" style="display: none;">
                                        <p class="mb-2"><strong>Ảnh mới:</strong></p>
                                        <img id="image_preview" src="" alt="Preview" class="img-thumbnail"
                                            style="max-width: 100%; max-height: 300px;">
                                        <button type="button" class="btn btn-sm btn-danger mt-2"
                                            onclick="clearImagePreview()">
                                            <i class="fas fa-times"></i> Hủy ảnh mới
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nhà xe</label>
                                    <select name="ma_nha_xe" class="form-control">
                                        <option value="">-- Chọn nhà xe --</option>
                                        @foreach($nhaXe as $nx)
                                            <option value="{{ $nx->ma_nha_xe }}" {{ old('ma_nha_xe', $tinTuc->ma_nha_xe) == $nx->ma_nha_xe ? 'selected' : '' }}>{{ $nx->ten_nha_xe }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i>
                                    Lưu</button>
                                <a href="{{ route('admin.tintuc.index') }}" class="btn btn-secondary btn-block"><i
                                        class="fas fa-arrow-left"></i> Hủy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Toggle giữa file upload và URL input
        $('input[name="image_type"]').change(function () {
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
                reader.onload = function (e) {
                    $('#image_preview').attr('src', e.target.result);
                    $('#image_preview_container').show();
                    $('#current_image_container').hide();
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
                $('#current_image_container').hide();

                // Kiểm tra ảnh có load được không
                $('#image_preview').on('error', function () {
                    alert('Không thể tải ảnh từ URL này. Vui lòng kiểm tra lại.');
                    clearImagePreview();
                });
            }
        }

        // Xóa preview
        function clearImagePreview() {
            $('#image_preview').attr('src', '');
            $('#image_preview_container').hide();
            $('#current_image_container').show();
            $('#imageFileInput').val('');
            $('.custom-file-label').text('Chọn ảnh mới...');

            // Không xóa giá trị URL nếu đang ở chế độ URL
            if ($('input[name="image_type"]:checked').val() !== 'url') {
                $('input[name="image_url"]').val('');
            }
        }
    </script>
@endpush