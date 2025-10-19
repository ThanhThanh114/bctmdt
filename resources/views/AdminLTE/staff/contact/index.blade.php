@extends('layouts.admin')

@section('title', 'Quản lý liên hệ')

@section('page-title', 'Quản lý liên hệ')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Quản lý liên hệ</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-envelope"></i> Danh sách liên hệ</h3>
        <div class="card-tools">
            <span class="badge badge-primary">{{ $contacts->total() }} liên hệ</span>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 120px;">Chi nhánh</th>
                    <th style="width: 150px;">Thông tin liên hệ</th>
                    <th style="width: 200px;">Tiêu đề & Nội dung</th>
                    <th style="width: 130px;">Thời gian</th>
                    <th style="width: 220px;" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td>
                        <strong>#{{ $contact->id }}</strong>
                    </td>
                    <td>
                        @if($contact->branch)
                        <span class="badge badge-info">
                            <i class="fas fa-map-marker-alt"></i> {{ $contact->branch }}
                        </span>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div>
                            <i class="fas fa-user text-primary"></i> <strong>{{ $contact->fullname }}</strong>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-envelope"></i> {{ $contact->email }}
                        </div>
                        @if($contact->phone)
                        <div class="text-muted small">
                            <i class="fas fa-phone"></i> {{ $contact->phone }}
                        </div>
                        @endif
                    </td>
                    <td>
                        @if($contact->subject)
                        <div class="mb-1">
                            <strong class="text-dark">{{ Str::limit($contact->subject, 40) }}</strong>
                        </div>
                        @endif
                        <div class="text-muted small" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ Str::limit($contact->message, 80) }}
                        </div>
                    </td>
                    <td>
                        <div>
                            <i class="far fa-calendar-alt text-info"></i>
                            {{ $contact->created_at ? \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y') : 'N/A' }}
                        </div>
                        <div class="text-muted small">
                            <i class="far fa-clock"></i>
                            {{ $contact->created_at ? \Carbon\Carbon::parse($contact->created_at)->format('H:i:s') : '' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info" onclick="viewContact({{ $contact->id }})" title="Xem chi tiết">
                                <i class="fas fa-eye"></i> Xem
                            </button>
                            <button class="btn btn-success" onclick="replyContact({{ $contact->id }})" title="Trả lời liên hệ">
                                <i class="fas fa-reply"></i> Trả lời
                            </button>
                        </div>
                        <form action="{{ route('staff.contact.destroy', $contact->id) }}" method="POST" style="display: inline;" class="ml-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa liên hệ này?')" title="Xóa liên hệ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có liên hệ nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contacts->hasPages())
    <div class="card-footer clearfix">
        {{ $contacts->links() }}
    </div>
    @endif
</div>

<!-- View Contact Modal -->
<div class="modal fade" id="viewContactModal" tabindex="-1" role="dialog" aria-labelledby="viewContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewContactModalLabel">Chi tiết liên hệ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contactDetails">
                <!-- Contact details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Contact Modal -->
<div class="modal fade" id="replyContactModal" tabindex="-1" role="dialog" aria-labelledby="replyContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyContactModalLabel">Trả lời liên hệ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="replyForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email người nhận:</label>
                        <input type="email" class="form-control" id="replyEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tiêu đề:</label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label>Nội dung trả lời:</label>
                        <textarea class="form-control" name="message" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Gửi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewContact(id) {
        // Load contact details via AJAX
        $.ajax({
            url: '/staff/contact/' + id,
            type: 'GET',
            success: function(data) {
                $('#contactDetails').html(`
                <dl class="row">
                    <dt class="col-sm-3">Chi nhánh:</dt>
                    <dd class="col-sm-9">${data.branch || 'N/A'}</dd>
                    
                    <dt class="col-sm-3">Họ tên:</dt>
                    <dd class="col-sm-9">${data.fullname}</dd>
                    
                    <dt class="col-sm-3">Email:</dt>
                    <dd class="col-sm-9">${data.email}</dd>
                    
                    <dt class="col-sm-3">Điện thoại:</dt>
                    <dd class="col-sm-9">${data.phone || 'N/A'}</dd>
                    
                    <dt class="col-sm-3">Tiêu đề:</dt>
                    <dd class="col-sm-9">${data.subject || 'N/A'}</dd>
                    
                    <dt class="col-sm-3">Nội dung:</dt>
                    <dd class="col-sm-9">${data.message}</dd>
                    
                    <dt class="col-sm-3">Ngày gửi:</dt>
                    <dd class="col-sm-9">${data.created_at || 'N/A'}</dd>
                </dl>
            `);
                $('#viewContactModal').modal('show');
            },
            error: function() {
                alert('Không thể tải thông tin liên hệ');
            }
        });
    }

    function replyContact(id) {
        // Load contact for reply
        $.ajax({
            url: '/staff/contact/' + id,
            type: 'GET',
            success: function(data) {
                $('#replyEmail').val(data.email);
                $('#replyForm').attr('action', '/staff/contact/' + id + '/reply');
                $('#replyContactModal').modal('show');
            },
            error: function() {
                alert('Không thể tải thông tin liên hệ');
            }
        });
    }
</script>
@endpush