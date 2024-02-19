@extends('user/master')
@section('content')
<div class="card">
    <h5 class="card-header">Danh Sách Thực Phẩm Đã Nhận</h5>
    <div class="card-body">
        <div class="table-responsive" style="min-height: 500px;">
            <table  class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle">STT</th>
                        <th class="text-center align-middle">Tên Thực Phẩm</th>
                        <th class="text-center align-middle">Hình Ảnh</th>
                        <th class="text-center align-middle">Số Lượng</th>
                        <th class="text-center align-middle">Trạng Thái</th>
                        <th class="text-center align-middle">Tên Người Tặng</th>
                        <th class="text-center align-middle">Thời Gian Ấn Nhận</th>
                        <th class="text-center align-middle">Thời Gian Nhận </th>
                        <th class="text-center align-middle">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($received_food as $foodTransaction)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td class="text-center">{{ $foodTransaction->food->title }}</td>
                            <td class="text-center">
                                <img style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; object-position: center center;" alt="Ảnh Thực Phẩm" src="{{ $foodTransaction->food->image_url }}">
                            </td>
                            <td class="text-center">{{ $foodTransaction->quantity_received }}</td>
                            <td class="text-center">
                            @if($foodTransaction->status==0 && $foodTransaction->donor_status==1)
                                <span class="badge bg-label-warning me-1">Người Tặng Đã Xác Nhận</span>
                            @elseif($foodTransaction->status == 0)
                                <span class="badge bg-label-warning me-1">Đang Đợi Xác Nhận</span>
                            @elseif($foodTransaction->status == 1)
                                <span class="badge bg-label-success me-1">Đã Lấy</span>
                            @elseif($foodTransaction->status == 2 and $foodTransaction->donor_status==2)
                            <span class="badge bg-label-danger me-1">Người Tặng Từ Chối</span>
                            @elseif($foodTransaction->status == 2)
                                <span class="badge bg-label-danger me-1">Đã Hủy Nhận</span>
                            @elseif($foodTransaction->status == 3)
                                <span class="badge bg-label-danger me-1">Hết Thời Gian Nhận</span>
                            @elseif($foodTransaction->status == 4)
                            <span class="badge bg-label-danger me-1">Thực Phẩm Này Đã Bị Khóa</span>
                            @endif
                            </td>
                            <td class="text-center">{{ $foodTransaction->food->user->username}}</td>
                            <td class="text-center">{{ $foodTransaction->created_at }}</td>
                            <td class="text-center">{{ $foodTransaction->pickup_time }}</td>
                            <td class="text-center">
                                <a href="{{ route('detail_received',['received_id' => $foodTransaction->id]) }}" class="btn btn-info">Xem</a>
                                    @if($foodTransaction->status!=4)
                                        @if($foodTransaction->status==0)
                                        <button type="button" id="cancel_received" class="btn btn-warning show_model" data-item-id="{{$foodTransaction->id}}">Hủy</button>
                                        @endif
                                        @if($foodTransaction->status==1 && $foodTransaction->donor_status==1 &&$foodTransaction->receiver_status==0)
                                        <button type="button" id="btn_rate" class="btn btn-success show_model_rate" data-item-id="{{$foodTransaction->id}}">Rate</button>
                                        @endif
                                    @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- model -->
        <div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Xác Nhận Hủy Nhận Thực Phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label  class="form-label text-warning">Bạn Có Chắc Chắn Muốn Hủy Nhận Thực Phẩm Này, Chỉ 
                                    5 Tiếng Sau Bạn Mới Có Thể Nhận Lại Thực Phẩm Này!
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-confirm btn-primary" data-bs-dismiss="modal">Xác Nhận</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- model Rate -->
        <div class="modal fade" id="basicModal_rate" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Đánh Giá Thực Phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-2">
                                <label  class="form-label text-success">
                                    Cảm ơn bạn đã đồng hành cùng FoodShare, Vui lòng đánh giá chính xác để góp phần xây dựng cộng đồng tốt đẹp hơn.
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="rate">
                                <input type="radio" id="star5" name="rate" value="5" />
                                <label for="star5" title="text">5 stars</label>
                                <input type="radio" id="star4" name="rate" value="4" />
                                <label for="star4" title="text">4 stars</label>
                                <input type="radio" id="star3" name="rate" value="3" />
                                <label for="star3" title="text">3 stars</label>
                                <input type="radio" id="star2" name="rate" value="2" />
                                <label for="star2" title="text">2 stars</label>
                                <input type="radio" id="star1" name="rate" value="1" />
                                <label for="star1" title="text">1 star</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Nội dung đánh giá</label>
                                <textarea class="form-control" id="rating-content" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-confirm-rate btn-primary" data-bs-dismiss="modal">Đánh Giá</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- {{-- Toast --}} -->
        <div class="bs-toast toast toast-placement-ex m-2 fade bg-warning top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">FoodShare</div>
                <small>now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col text-center">
        <small class="text-light fw-semibold">Trang: {{ $received_food->currentPage() }}/{{ $received_food->lastPage() }}</small>
        <div class="demo-inline-spacing">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($received_food->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $received_food->previousPageUrl() }}" aria-label="Previous">
                                <i class="tf-icon bx bx-chevrons-left"></i>
                            </a>
                        </li>
                    @endif
                    @if ($received_food->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $received_food->previousPageUrl() }}" aria-label="Previous">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>
                    @endif
                    @foreach ($received_food->getUrlRange(1, $received_food->lastPage()) as $page => $url)
                        @if ($page == $received_food->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                    @if ($received_food->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $received_food->nextPageUrl() }}" aria-label="Next">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevron-right"></i></span>
                        </li>
                    @endif
                    @if ($received_food->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $received_food->url($received_food->lastPage()) }}" aria-label="Last">
                                <i class="tf-icon bx bx-chevrons-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
$(document).ready(function () {
    var collectsuccess = sessionStorage.getItem('collectsuccess');
    var cancel_success = sessionStorage.getItem('cancel_success');
    var rating_success = sessionStorage.getItem('rating_success');
    function showToast(message, type) {
        var toast = document.querySelector('.bs-toast');
        var toastBody = toast.querySelector('.toast-body');
        toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
        toast.classList.add('bg-' + type);
        toastBody.textContent = message;
        var bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }
    if (collectsuccess !== null) {
        var textContent = "Bạn Đã Nhận Thực Phẩm Thành Công, Vui lòng đợi người tặng xác nhận";
        showToast(textContent, 'success');
        sessionStorage.removeItem('collectsuccess');
    }
    if (cancel_success !== null) {
        var textContent = "Bạn Đã Hủy Nhận Thực Phẩm Thành Công";
        showToast(textContent, 'success');
        sessionStorage.removeItem('cancel_success');
    }
    if (rating_success !== null) {
        var textContent = "Bạn Đã Đánh Giá Thành Công";
        showToast(textContent, 'success');
        sessionStorage.removeItem('rating_success');
    }
    var canceledItemId = null;  
    $(".show_model").click(function () {
        var itemId = $(this).data("item-id");
        canceledItemId = itemId; 
        $("#basicModal").modal("show");
    });

    var rateItemId = null; 
    $(".show_model_rate").click(function () {
        var itemId = $(this).data("item-id");
        rateItemId = itemId; 
        $("#basicModal_rate").modal("show");
    });
    $(".btn-confirm").click(function () {
        if (canceledItemId !== null) {
            $.ajax({
                type: 'GET',
                url: '/receiving/cancel_received/' + canceledItemId,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.errors) {
                        showToast(response.errors, 'danger');
                    } else if (response.message) {
                        sessionStorage.setItem('cancel_success', 'Hủy Nhận Hàng Thành Công');
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
    // rating
    $(".btn-confirm-rate").click(function () {
        if (rateItemId !== null) {
            var rating = $("input[name='rate']:checked").val();
            var reviewContent = $("#rating-content").val();
            $.ajax({
                type: 'POST',
                url: '/receiving/rating',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: {
                    rating: rating, 
                    reviewContent: reviewContent,
                    transaction_id:rateItemId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.error) {
                        showToast(response.error, 'danger');
                    } else if (response.message) {
                        sessionStorage.setItem('rating_success', 'Bạn Đã Đánh Giá Thành Công');
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