@extends('user/master')
@section('content')
<div class="card">
    <h5 class="card-header">Danh Sách Thực Phẩm Đã Tặng</h5>
    <div class="card-body ">
        <div class="table-responsive" style="min-height:500px;" >
        <table class="table table-bordered" >
            <thead>
            <tr>
                <th class="text-center align-middle">STT</th>
                <th class="text-center align-middle">Tên Thực Phẩm</th>
                <th class="text-center align-middle">Loại</th>
                <th class="text-center align-middle">Hình Ảnh</th>
                <th class="text-center align-middle">Số Lượng Còn</th>
                <th class="text-center align-middle">Thời Gian</th>
                <th class="text-center align-middle">Trạng Thái</th>
                <th class="text-center align-middle">Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach($donatedFoods as $food)
                    <tr>
                        <td class="text-center">{{ $loop->index + 1 }}</td>
                        <td class="text-center">{{ $food->title }}</td>
                        <td class="text-center">
                        @if($food->food_type == 1)
                        Đã Chế Biến
                        @elseif($food->food_type == 2)
                        Chưa Chế Biến
                        @elseif($food->food_type == 3)
                        Đồ Ăn Nhanh
                        @elseif($food->food_type == 3)
                        Hải Sản
                        @endif  
                        </td>
                        <td class="text-center">
                            <img style="width: 70px; height: 70px; border-radius:50%; object-fit: cover;
                            object-position: center center;" alt="Ảnh Thực Phẩm" src="{{$food->image_url}}">
                        </td>
                        <td class="text-center">{{ $food->quantity }}</td>
                        <td class="text-center">{{ $food->created_at }}</td>
                        <td class="text-center">
                            @if($food->status == 0)
                            <span class="badge bg-label-success me-1">Đang Mở</span>
                            @elseif($food->status == 1)
                            <span class="badge bg-label-warning me-1">Đã Có Người Nhận</span>
                            @elseif($food->status == 2)
                            <span class="badge bg-label-info me-1">Đã Dừng Tặng</span>
                            @elseif($food->status == 4)
                            <span class="badge bg-label-danger me-1">Đã Bị Khóa</span>
                            @endif    
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                            @if($food->status!=4)
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                    <a href="/donate/detail-donated-food/{{$food->id}}" class="dropdown-item text-success"><i class="bx bx-show-alt me-1"></i> View</a>
                                    @if($food->expiry_date > now()and $food->status!=2)
                                    <a href="/donate/update-donate-food/{{$food->id}}" class="dropdown-item text-info"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    @endif
                                    @if($food->expiry_date > now() and $food->quantity>=1 and $food->status!=2)
                                    <button type="button" id="cancel_received" class="dropdown-item text-danger show_model" data-item-id="{{$food->id}}"><i class="bx bx-stop-circle me-1"></i> Dừng Tặng</button>
                                    @endif
                                    @if($food->expiry_date > now() and $food->quantity>=1 and $food->status==2)
                                    <button type="button" id="continues_received" class="dropdown-item text-info show_model2" data-item-id="{{$food->id}}"><i class="bx bx-lock-open me-1"></i> Tiếp Tục Tặng</button>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
        </table>
        </div>
    </div>
</div>
<!-- model -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Xác Nhận Dừng Tặng Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label text-warning">Bạn Có Chắc Chắn Muốn Dừng Tặng Thực Phẩm Này!
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-confirm_cancel btn-primary" data-bs-dismiss="modal">Xác Nhận</button>
            </div>
        </div>
    </div>
</div>
<!-- model2 -->
<div class="modal fade" id="basicModal2" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Xác Nhận Tiếp Tục Tặng Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label text-warning">Bạn Có Chắc Chắn Muốn Tiếp Tục Tặng Thực Phẩm Này!
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-confirm_continues btn-primary" data-bs-dismiss="modal">Xác Nhận</button>
            </div>
        </div>
    </div>
</div>
<!-- toast -->
<div class="bs-toast toast toast-placement-ex m-2 fade bg-warning top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold">FoodShare</div>
        <small>now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body"></div>
</div>
@endsection
@section('js')
<script>
$(document).ready(function () {
        var cancel_donated_success = sessionStorage.getItem('cancel_donated_success');
        var add_donated_food_success = sessionStorage.getItem('add_donated_food_success');
        var continues_donated_success = sessionStorage.getItem('continues_donated_success');

        function showToast(message, type) {
            var toast = document.querySelector('.bs-toast');
            var toastBody = toast.querySelector('.toast-body');
            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
            toast.classList.add('bg-' + type);
            toastBody.textContent = message;
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
        if (cancel_donated_success !== null) {
            var textContent = "Bạn Đã Dừng Tặng Thực Phẩm Thành Công";
            showToast(textContent, 'success');
            sessionStorage.removeItem('cancel_donated_success');
        }
        if (add_donated_food_success !== null) {
            var textContent = "Bạn Đã Thêm Thực Phẩm Thành Công";
            showToast(textContent, 'success');
            sessionStorage.removeItem('add_donated_food_success'); 
        }
        if (continues_donated_success !== null) {
            var textContent = "Tiếp Tục Tặng Thực Phẩm Thành Công";
            showToast(textContent, 'success');
            sessionStorage.removeItem('continues_donated_success');
        }
        var canceledItemId = null; 
        $(".show_model").click(function () {
            var itemId = $(this).data("item-id");
            canceledItemId = itemId; 
            $("#basicModal").modal("show");
        });

        var continues_id = null; 
        $(".show_model2").click(function () {
            var itemId = $(this).data("item-id");
            continues_id = itemId; 
            $("#basicModal2").modal("show");
        });
        $(".btn-confirm_cancel").click(function () {
            if (canceledItemId !== null) {
                $.ajax({
                    type: 'GET',
                    url: '/donate/update-donate-food-cancel/'+canceledItemId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.errors) {
                            showToast(response.errors, 'danger');
                        } else if (response.message) {
                            sessionStorage.setItem('cancel_donated_success', 'Dừng Tặng Thực Phẩm Thành Công');
                            window.location.reload();
                        }
                    },
                    error: function (error) {
                        if (error.status === 422) {
                            var errors = error.responseJSON.errors;
                            showToast(errors, 'danger');
                        }
                    }
                });
            }
        });
        $(".btn-confirm_continues").click(function () {
            if (continues_id !== null) {
                $.ajax({
                    type: 'GET',
                    url: '/donate/update-donate-food-continues/'+continues_id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.errors) {
                            showToast(response.errors, 'danger');
                        } else if (response.message) {
                            sessionStorage.setItem('continues_donated_success', 'Tiếp Tục Tặng Thực Phẩm Thành Công');
                            window.location.reload();
                        }
                    },
                    error: function (error) {
                        if (error.status === 422) {
                            var errors = error.responseJSON.errors;
                            showToast(errors, 'danger');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection