@extends('user/master')
@section('content')
@if($foodData['status']==4)
  <div class="alert alert-danger" role="alert">Thực Phẩm Này Đã Bị Khóa</div>
  @else
<div class="row bg-white" style="border-radius: 0.5rem;">
  <div class="col-md-4 pb-3">
    <div class="row">
      <div class="image-slider p-1">
        <img id="current-image" style="width: 420px;  height: 450px; border-radius:2%; object-fit: cover; object-position: center center;" src="{{ $foodData['image_url'] }}">
      </div>
    </div>
    <div class="row center ml-2 ">
      <div class="thumbnails">
        <button type="button"  id="prev-thumbnail" class="btn btn-info p-1">&lt;</button>
        <div class="thumbnail-images">
          @foreach ($imageUrls as $imageUrl)
              <img class="thumbnail" src="{{$imageUrl}}">
          @endforeach
        </div>
        <button type="button" id="next-thumbnail" class="btn btn-info btn-lg p-1">&gt;</button>
      </div>
    </div>
  </div>
  <div class="col-md-8 text-start">
    <div class="mb-2">
        <div class="pt-1 mb-2">
            <div class="row">
              <div class="col-lg-11">
                @php
                $averageRating = null;
                $totalRating = 0;
                $totalRatings = 0;
                foreach ($ratings as $rating) {
                    if (!is_null($rating)) {
                        $totalRating += $rating->rating;
                        $totalRatings++;
                    }
                }
                if ($totalRatings > 0) {
                    $averageRating = $totalRating / $totalRatings;
                }
                @endphp
                    <p class="text mb-0">
                        Điểm Đánh Giá: 
                        @if($averageRating>0)
                        {{$averageRating}}
                        @endif
                        <p class="text mb-0">
                            @if ($averageRating >= 1 && $averageRating < 2)
                                <i class="bx bx-star me-1"></i>
                            @elseif ($averageRating >= 2 && $averageRating < 3)
                                <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
                            @elseif ($averageRating >= 3 && $averageRating < 4)
                                <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
                            @elseif ($averageRating >= 4 && $averageRating < 5)
                                <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
                            @elseif ($averageRating >= 5)
                                <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
                            @else
                                Chưa có đánh giá
                            @endif
                        </p>
                    </p>
                </div>
            </div>
        </div>
        <h5 class="pt-3">Thông Tin Thực Phẩm</h5>
    </div>
    <hr class="my-0">
    <div class="col-md-12">
        <div class="row mt-3">
          <div class="col-md-3">
            <strong class="text-info">Tên Thực Phẩm: </strong>
          </div>
          <div class="col-md-9 text-info " style="padding-left:2em">
            {{$foodData['title']}} <strong class="text-success">Free</strong></p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Địa chỉ nhận: </strong>
          </div>
          <div class="col-md-9" style="padding-left:2em">
            {{$foodData['location']}}, {{$ward->name}}, {{$district->name}}, {{$province->name}}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Mô Tả:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
            {{$foodData['description']}}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Đăng Lúc:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
          {{ date('Y-m-d H:i:s', strtotime($foodData['created_at'])) }}
          </div>
        </div> 
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Hạn Thực Phẩm:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
            {{$foodData['expiry_date']}}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Thời Gian Chấp Nhận Tặng Hàng Sau Khi Ấn Nhận:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
          @if ($foodData['remaining_time_to_accept']==30)
              30 Phút
          @endif
          @if ($foodData['remaining_time_to_accept']== 60)
              1 tiếng
          @endif
          @if ($foodData['remaining_time_to_accept']==90)
              1 tiếng 30 phút
          @endif
          @if ($foodData['remaining_time_to_accept']==120)
              2 tiếng
          @endif
          @if ($foodData['remaining_time_to_accept']==150)
              2 tiếng 30 phút
          @endif
          @if ($foodData['remaining_time_to_accept']==180)
              3 tiếng
          @endif
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
           <strong>Loại Thực Phẩm:</strong>
          </div>
          <div class="col-md-9" style="padding-left:2em">
            @if($foodData['food_type'] == 1)
              Đã Chế Biến
            @elseif($foodData['food_type'] == 2)
              Chưa Chế Biến
            @elseif($foodData['food_type'] == 3)
              Đồ Ăn Nhanh
            @elseif($foodData['food_type'] == 3)
              Hải Sản
            @endif
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Số Lượng Còn:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
            {{$foodData['quantity']}}
          </div>
        </div>
    </div>
  </div>
  <!-- list received -->
  <div class="row">
    @if (count($transactions) > 0)
    <h5 class="card-header">Danh Sách Các Giao Dịch Nhận Thực Phẩm Của Bạn</h5>
        <div class="card-body ">
            <div class="table-responsive" style="min-height:500px;" >
            <table class="table table-bordered" >
                <thead>
                <tr>
                    <th class="text-center align-middle">STT</th>
                    <th class="text-center align-middle">Người Nhận</th>
                    <th class="text-center align-middle">Ảnh Đại Diện</th>
                    <th class="text-center align-middle">Số Lượng Nhận</th>
                    <th class="text-center align-middle">Điểm Đánh Giá</th>
                    <th class="text-center align-middle">Đánh Giá</th>
                    <th class="text-center align-middle">Thời Gian Ấn Nhận</th>
                    <th class="text-center align-middle">Thời Gian Nhận</th>
                    <th class="text-center align-middle">Trạng Thái</th>
                    <th class="text-center align-middle">Thao Tác</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $key => $transaction)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">{{ $transaction->receiver->username }}</td>
                            <td class="text-center">
                              @if( $transaction->receiver->image!=null)
                              <img src="{{ $transaction->receiver->image}}" alt="user-avatar" class="rounded" height="50" width="50" >
                              @else
                              <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="rounded" height="50" width="50" >
                              @endif
                            </td>
                            <td class="text-center">{{ $transaction->quantity_received }}</td>
                            <td class="text-center">
                            @if($ratings[$transaction->id])
                                {{ $ratings[$transaction->id]->rating }} / 5
                            @else
                                Chưa có đánh giá
                            @endif
                            </td>
                            <td class="text-center">
                            @if($ratings[$transaction->id])
                                {{ $ratings[$transaction->id]->review }}
                            @else
                                Chưa có đánh giá
                            @endif
                            </td>
                            <td class="text-center">{{ $transaction->created_at }}</td>
                            <td class="text-center">{{ $transaction->pickup_time }}</td>
                            <td class="text-center">
                                @if($transaction->status == 0)
                                    <span class="badge bg-label-warning me-1">Chưa Lấy</span>
                                @elseif($transaction->status == 1)
                                    <span class="badge bg-label-success me-1">Đã Lấy</span>
                                @elseif($transaction->status == 2)
                                    <span class="badge bg-label-danger me-1">Đã Hủy Nhận</span>
                                @elseif($transaction->status == 3)
                                    <span class="badge bg-label-danger me-1">Bị Hủy Do Hết Thời Gian</span>
                                @endif
                            </td>
                            <td class="text-center">
                            @if($transaction->status == 0 && $transaction->donor_status ==1)
                              <button style="min-width:105px;" type="button" id="cofirm_collect_food" class="btn btn-success show_model" data-item-id="{{$transaction->id}}">Đã Nhận</button>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </table>
            </div>
        </div>
    </div>
    @else
    <h5 class="card-header text-warning">Chưa Có Ai Nhận Thực Phẩm Này</h5>
    @endif
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
  @endif
</div>
@endsection
@section('js')
<script>
  const thumbnailImages = <?php echo json_encode($imageUrls); ?>;
  const currentImage = document.getElementById('current-image');
  const thumbnailImagesContainer = document.querySelector('.thumbnail-images');
  const prevThumbnailButton = document.getElementById('prev-thumbnail');
  const nextThumbnailButton = document.getElementById('next-thumbnail');
  let startIndex = 0;

  function showMainImage() {
    currentImage.src = '{{ $foodData["image_url"] }}';
  }

  function showThumbnailImage(index) {
    currentImage.src = (thumbnailImages[index]) ? thumbnailImages[index] : '{{ $foodData["image_url"] }}';
  }

  showMainImage();

  function updateThumbnailImages() {
    thumbnailImagesContainer.innerHTML = '';
    if (thumbnailImages.length === 0) {
      showMainImage(); 
      prevThumbnailButton.style.display = 'none';
      nextThumbnailButton.style.display = 'none';
      return; // Không cần thực hiện tiếp
    }
    const endIndex = Math.min(thumbnailImages.length - 1, startIndex + 2);
    for (let i = startIndex; i <= endIndex; i++) {
      const thumbnail = document.createElement('img');
      thumbnail.className = 'thumbnail';
      thumbnail.src = '../../' + thumbnailImages[i];
      thumbnail.addEventListener('click', () => {
        showThumbnailImage(i);
      });
      thumbnailImagesContainer.appendChild(thumbnail);
    }
    if (thumbnailImages.length <= 3) {
      prevThumbnailButton.style.display = 'none';
      nextThumbnailButton.style.display = 'none';
    } else {
      prevThumbnailButton.style.display = 'block';
      nextThumbnailButton.style.display = 'block';
    }
  }
  updateThumbnailImages();
  prevThumbnailButton.addEventListener('click', () => {
    startIndex--;
    if (startIndex < 0) {
      startIndex = 0;
    }
    updateThumbnailImages();
  });
  nextThumbnailButton.addEventListener('click', () => {
    startIndex++;
    if (startIndex > thumbnailImages.length - 3) {
      startIndex = thumbnailImages.length - 3;
    }
    updateThumbnailImages();
  });
  $(document).ready(function () {
    function showToast(message, type) {
      var toast = document.querySelector('.bs-toast');
      var toastBody = toast.querySelector('.toast-body');
      toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
      toast.classList.add('bg-' + type);
      toastBody.textContent = message;
      var bsToast = new bootstrap.Toast(toast);
      bsToast.show();
    }
    var confirm_collect_food_done = sessionStorage.getItem('confirm_collect_food_done');
    if (confirm_collect_food_done !== null) {
      var textContent = "Bạn Đã Xác Nhận Người Nhận Đã Nhận Thực Phẩm Thành Công";
      showToast(textContent, 'success');
      sessionStorage.removeItem('confirm_collect_food_done');
    }
    $("#cofirm_collect_food").click(function () {
        var received_id = $(this).data("item-id");
        if (received_id !== null) {
          $.ajax({
              type: 'GET',
              url: '/receiving/cofirm-collect-food/' + received_id,
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
              },
              dataType: 'json',
              success: function (response) {
                if (response.errors) {
                    showToast(response.errors, 'danger');
                } else if (response.message) {
                    sessionStorage.setItem('confirm_collect_food_done', 'Xác Thực Nhận Thực Phẩm Thành Công');
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