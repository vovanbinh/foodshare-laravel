@extends('user/master')
@section('content')
<div class="row bg-white" style="border-radius: 0.5rem;">
  <div class="col-md-4 pb-3">
    <div class="row">
      <div class="image-slider p-1" id="image-slider">
        <img id="current-image" style="width: 420px; height: 450px; border-radius:2%; object-fit: cover; object-position: center center;" src="{{$food->image_url}}">
      </div>
    </div>
    <div class="row center ml-2 ">
      <div class="thumbnails" id="thumbnail-container">
        <div class="thumbnail-images">
            @foreach ($images as $index => $imageUrl)
              <img  class="thumbnail" src="{{$imageUrl}}" data-index="{{$index}}">
            @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8 text-start">
    <div class="mb-2">
        <div class="pt-1 mb-2">
            <div class="row">
              <div class="col-lg-1">
                <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="rounded" height="50" width="50" >
              </div>
              <div class="col-lg-11">
                <p class="text mb-0 ">Người Tặng: {{$donor->username}}</p>
                <p class="text mb-0 ">
                    @if(filter_var($food->contact_information, FILTER_VALIDATE_URL))
                        <a href="{{$food->contact_information}}" class="text mb-0">Thông Tin Liên Hệ</a>
                    @else
                        <p class="text mb-0">Thông Tin Liên Hệ: {{$food->contact_information}}</p>
                    @endif
                </p>
              </div>
            </div>
        </div>
        <h5 class="pt-3">Thông Tin Thực Phẩm Nhận</h5>
    </div>
    <hr class="my-0">
    <div class="col-md-12">
        <div class="row mt-3">
          <div class="col-md-3">
            <label hidden id="receivedId">{{$foodTransaction->id}}</label>
            <strong class="text-info">Tên Thực Phẩm: </strong>
          </div>
          <div class="col-md-9 text-info " style="padding-left:2em">
            {{$food->title}} <strong class="text-success">Free</strong></p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Địa chỉ nhận: </strong>
          </div>
          <div class="col-md-9" style="padding-left:2em">
            {{$food->location}}, {{$ward->name}}, {{$district->name}}, {{$province->name}}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Mô Tả:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
          {{$food->description}}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Đăng Lúc:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
          {{$food->created_at}}
          </div>
        </div> 
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Hạn Thực Phẩm:</strong> 
          </div>
          <div class="col-md-9" style="padding-left:2em">
          {{$food->expiry_date}}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Thời Gian Còn Lại Để Nhận Thực Phẩm:</strong> 
          </div>
          <div class="col-md-9 text-danger" style="padding-left:2em" id="countdown">
 
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
           <strong>Loại Thực Phẩm:</strong>
          </div>
          <div class="col-md-9" style="padding-left:2em">
            @if($food->food_type == 1)
              Đã Chế Biến
            @elseif($food->food_type == 2)
              Chưa Chế Biến
            @elseif($food->food_type == 3)
              Đồ Ăn Nhanh
            @elseif($food->food_type == 3)
              Hải Sản
            @endif
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
           <strong>Số Lượng Nhận: </strong>
          </div>
          <div class="col-md-9" style="padding-left:2em">
           {{$foodTransaction->quantity_received}}
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-3">
           <strong>Trạng Thái: </strong>
          </div>
          <div class="col-md-9" id="status" style="padding-left:2em">
          @if ($foodTransaction->status == 3)
              Giao Dịch Đã Bị Hủy Bỏ Do Không Đến Lấy
          @elseif ($foodTransaction->status == 1)
              Thực Phẩm Đã Được Nhận
          @elseif ($foodTransaction->status == 0)
              Chưa Lấy Thực Phẩm
          @elseif($foodTransaction->status == 2 and $foodTransaction->donor_status==2)
              Người nhận đã từ chối
          @elseif ($foodTransaction->status == 2)
              Giao Dịch Đã Bị Hủy Bỏ
          @elseif ($foodTransaction->status == 4)
              Thực Phẩm Này Đã Bị Khóa
          @endif
          </div>
        </div>
        @if($rating!=null)
          <div class="row mb-3">
            <div class="col-md-3">
            <strong>Số điểm bạn đánh giá: </strong>
            </div>
            <div class="col-md-9" style="padding-left:2em">
              @if($rating->rating == 1)
              <i class="bx bx-star me-1"></i>
              @elseif($rating->rating == 2)
              <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
              @elseif($rating->rating == 3)
              <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
              @elseif($rating->rating == 4)
              <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
              @elseif($rating->rating == 5)
              <i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i><i class="bx bx-star me-1"></i>
              @endif
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3">
            <strong>Nội dung đánh giá: </strong>
            </div>
            <div class="col-md-9" style="padding-left:2em">
            @if($rating->review==null)
              Không có nội dung
            @else
            {{$rating->review}}
            @endif
            </div>
          </div>
        @endif
        <div class="row text-end mt-3 mb-5" style="padding-left:0.8em">
          <a type="button" href="/receiving/list" class="btn btn-info col-2">Trở Lại</a>
        </div>
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
</div>
@endsection
@section('js')
<script>
  const thumbnailImages = <?php echo json_encode($images); ?>;
  const currentImage = document.getElementById('current-image');
  const thumbnailImagesContainer = document.querySelector('.thumbnail-images');
  const prevThumbnailButton = document.getElementById('prev-thumbnail');
  const nextThumbnailButton = document.getElementById('next-thumbnail');
  let startIndex = 0;
  function showMainImage() {
    currentImage.src = '{{ $food->image_url }}';
  }
  function showThumbnailImage(index) {
    currentImage.src = (thumbnailImages[index]) ? thumbnailImages[index] : '{{ $food->image_url }}';
  }
  showMainImage(); 
  function updateThumbnailImages() {
    thumbnailImagesContainer.innerHTML = '';
    if (thumbnailImages.length === 0) {
      showMainImage(); // Hiển thị hình ảnh chính nếu không có ảnh mô tả
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
</Script>
<script>
      $(document).ready(function () {
        var hasExpired = false; 
        var countdown =  document.getElementById("countdown");
        if('{{ $foodTransaction->status }}' == 2){
            document.getElementById("countdown").innerHTML = "Giao Dịch Đã Bị Hủy Bỏ";
            hasExpired = true;
          } 
        else if ('{{ $foodTransaction->status }}' == 4) {
        document.getElementById("countdown").innerHTML = "Thực Phẩm Này Đã Bị Khóa";
        hasExpired = true;
        }
        else if ('{{ $foodTransaction->donor_status }}' == 0) {
          document.getElementById("countdown").innerHTML = "Vui lòng đợi người tặng xác nhận";
          hasExpired = true;
        }
        else if ('{{ $foodTransaction->status }}' == 3) {
          document.getElementById("countdown").innerHTML = "Đã hết thời gian nhận thực phẩm";
          hasExpired = true;
        }
        else if('{{ $foodTransaction->status }}' == 1){
          document.getElementById("countdown").innerHTML = "Thực Phẩm Đã Được Nhận";
          hasExpired = true;
        } 
        else if('{{ $foodTransaction->status }}' == 2 && '{{ $foodTransaction->donor_status }}' == 2 ){
          document.getElementById("countdown").innerHTML = "Người tặng đã từ chối";
          hasExpired = true;
        } 
        else if('{{ $foodTransaction->status }}' == 0 && '{{ $foodTransaction->donor_status }}' == 1){
            var currentTime = new Date();
            var confirmationTime = new Date('{{ $foodTransaction->donor_confirm_time}}');
            var remainingTimeInMinutes = parseInt('{{ $food->remaining_time_to_accept }}');
            var expirationTime = new Date(confirmationTime.getTime() + remainingTimeInMinutes * 60000);
            function countdown() {
                if (hasExpired) {
                    return; 
                }
                var now = new Date();
                var timeLeft = expirationTime - now;
                if (timeLeft <= 0) {
                    document.getElementById("countdown").innerHTML = "Đã hết thời gian nhận thực phẩm";
                    var receivedId = document.getElementById("receivedId").textContent;
                    $.ajax({
                        type: 'GET',
                        url: '/receiving/rollback_receiving_time/' + receivedId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.error) {
                              showToast(response.error, 'danger');
                            } else if (response.message) {
                              showToast(response.message, 'danger');
                            }
                        },
                        error: function (error) {
                            if (error.status === 422) {
                                var errors = error.responseJSON.errors;
                            }
                        }
                    });
                    hasExpired = true; 
                } else {
                    var minutesLeft = Math.floor((timeLeft / 1000) / 60);
                    var secondsLeft = Math.floor((timeLeft / 1000) % 60);
                    document.getElementById("countdown").innerHTML = minutesLeft + " phút " + secondsLeft + " giây";
                }
            }
            setInterval(countdown, 1000);
        }else
        {
          document.getElementById("countdown").innerHTML = "Hết Thời Gian Nhận Thực Phẩm";
          hasExpired = true;
        }
        function showToast(message, type) {
          var toast = document.querySelector('.bs-toast');
          var toastBody = toast.querySelector('.toast-body');
          toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
          toast.classList.add('bg-' + type);
          toastBody.textContent = message;
          var bsToast = new bootstrap.Toast(toast);
          bsToast.show();
        }
    });
</script>
@endsection