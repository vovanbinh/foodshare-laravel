@extends('user/master')
@section('content')
<div class="col-md-12">
    <div class="card mb-4">
        <form id="form_update_food" enctype="multipart/form-data">
        @csrf
            <input hidden name="id" type="text" value="{{$food->id}}">
            <h5 class="card-header">Chỉnh Sửa Thông Tin Thực Phẩm</h5>
            <div class="card-body">
                <p class="text-warning">Lưu ý: Chỉ Được Chỉnh Sửa Được Những Thông Tin Cho Phép</p>
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <label class="form-control alert-primary">{{$food->title}}</label>
                </div>
                <div class="mb-3">
                    <label for="food_type" class="col-md-5 col-form-label ">Loại Thực Phẩm</label>
                    <label class="form-control alert-primary">
                    @if($food->food_type == 1)
                    Đã Chế Biến
                    @elseif($food->food_type == 2)
                    Chưa Chế Biến
                    @elseif($food->food_type == 3)
                    Đồ Ăn Nhanh
                    @elseif($food->food_type == 3)
                    Hải Sản
                    @endif
                    </label>
                </div>
                <div class="mb-3">
                    <div>
                        <label for="description" class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control"  rows="3">{{$food->description}}</textarea>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="col-md-2 col-form-label">Số Lượng</label>
                    <div class="col-md-12">
                        <input name="quantity" class="form-control" type="number" value="{{$food->quantity}}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="expiry_date" class="col-md-2 col-form-label">Thời Gian Hết Hạn</label>
                    <div class="col-md-12">
                        <input
                        class="form-control"
                        name="expiry_date"
                        type="datetime-local"
                        value="{{$food->expiry_date}}"
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirm_time" class="col-md-5 col-form-label">Thời Gian Chấp Nhận</label>
                    <div id="defaultFormControlHelp" class="form-text">ex: 30 phút sau khi người nhận xác nhận, nếu sau 30 phút người nhận không đến lấy sẽ tự động hủy giao dịch.</div>
                    <select class="form-control" name="confirm_time">
                        <option value="30" @if($food->remaining_time_to_accept == 30) selected @endif>30 phút</option>
                        <option value="60" @if($food->remaining_time_to_accept == 60) selected @endif>1 tiếng</option>
                        <option value="90" @if($food->remaining_time_to_accept == 90) selected @endif>1 tiếng 30 phút</option>
                        <option value="120" @if($food->remaining_time_to_accept == 120) selected @endif>2 tiếng</option>
                        <option value="150" @if($food->remaining_time_to_accept == 150) selected @endif>2 tiếng 30 phút</option>
                        <option value="180" @if($food->remaining_time_to_accept == 180) selected @endif>3 tiếng</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="location_id" class="col-md-2 col-form-label">Địa Điểm Nhận</label>
                    <div id="defaultFormControlHelp" class="form-text">Tỉnh/Thành phố</div>
                    <select style=" max-height: 200px; overflow-y: auto;" class="form-select" name="province_id"  aria-label="Default select example">
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" @if($province->id == $province_old->id) selected @endif>{{ $province->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div id="defaultFormControlHelp" class="form-text">Quận/Huyện</div>
                    <select style=" max-height: 200px; overflow-y: auto;" class="form-select" name="district_id"  aria-label="Default select example">
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" @if($district_old && $district->id == $district_old->id) selected @endif>{{ $district->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div id="defaultFormControlHelp" class="form-text">Phường/Xã</div>
                    <select style=" max-height: 200px; overflow-y: auto;" class="form-select" name="ward_id"  aria-label="Default select example">
                    @foreach($wards as $ward)
                        <option value="{{ $ward->id }}" @if($ward_old && $ward->id == $ward_old->id) selected @endif>{{ $ward->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div id="defaultFormControlHelp" class="form-text">Địa điểm cụ thể (Số Nhà, Đường)</div>
                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        placeholder="Nhập địa chỉ cụ thể"
                    />
                </div>
                <div class="mb-3">
                    <label for="contact_information" class="col-md-6 col-form-label">Thông tin liên hệ (Số Điện Thoại hoặc Đường Link Mạng Xã Hội)</label>
                    <input
                        type="text"
                        class="form-control"
                        name="contact_information"
                        placeholder="Nhập số điện thoại hoặc link mạng xã hội"
                        value="{{$food->contact_information}}"
                    />
                </div>
                <div class="mb-3">
                    <label for="image_url" class="col-md-5 col-form-label">Ảnh Thực Phẩm</label>
                    <input type="file" name="image_url" class="form-control" id="inputImage" />
                </div>
                <div  class="mb-3 row">
                    <div class="col-5">
                        <p>Ảnh Hiện Tại</p>
                        <img alt="Ảnh Thực Phẩm" class="col-12" style="border-radius:1em;" src="{{$food->image_url}}">
                    </div>
                    <div class="col-5">
                        <p id="imagep" class="text-info" style="display:none;">Ảnh Mới</p>
                        <div id="image" class="col-12">
                            
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="images_food" class="col-md-5 col-form-label">Ảnh Mô Tả</label>
                    <input type="file" name="images_food[]" class="form-control" id="inputImages" multiple/>
                </div>
                <div class="mb-3">
                @if (!empty($imageUrls))
                <p>Ảnh Hiện Tại</p>
                @foreach ($imageUrls as $imageUrl)
                    <img style="height: 150px; width: auto; border-radius: 1em; margin-top: 5px;" src="{{ $imageUrl }}">
                @endforeach
                @endif
                </div>
                <p id="imagesp" class="text-info" style="display:none;">Ảnh Mô Tả Mới</p>
                <div id="images" class="mb-3">
                    <!-- Các ảnh đã chọn sẽ được hiển thị ở đây -->
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-success">Update Food</button>
                </div>
            </div>
        </form>
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
    </div>
</div>
@endsection
@section('js')
<script>
    //hiển thị ảnh chính
    const inputImage = document.getElementById('inputImage');
    const imageContainer = document.getElementById('image');
    const imagep = document.getElementById('imagep');
    const imageclass = "col-12"; 
    inputImage.addEventListener('change', function (event) {
        const selectedImage = event.target.files[0];
        if (selectedImage) { 
            const imgElement = document.createElement('img');
            imgElement.src = URL.createObjectURL(selectedImage);
            imgElement.classList.add('max-width-image'); // Thêm lớp CSS cho phần tử img
            imgElement.style.borderRadius = '1em'; // Sửa đổi border-radius thành '1em'
            imgElement.classList.add(imageclass);
            while (imageContainer.firstChild) {
                imageContainer.removeChild(imageContainer.firstChild);
            }
            imageContainer.appendChild(imgElement);
            imagep.style.display = 'block';
        }
    });
    //hiển thị nhiều ảnh
    const inputImages = document.getElementById('inputImages');
    const imagesContainer = document.getElementById('images');
    const imagesp = document.getElementById('imagesp');
    const height = 150; 

    inputImages.addEventListener('change', function (event) {
        imagesp.style.display = 'block';
        while (imagesContainer.firstChild) {
            imagesContainer.removeChild(imagesContainer.firstChild);
        }
        const selectedImages = event.target.files;
        for (let i = 0; i < selectedImages.length; i++) {
            const imgElement = document.createElement('img');
            imgElement.src = URL.createObjectURL(selectedImages[i]);
            imgElement.classList.add('max-width-image'); // Thêm lớp CSS cho phần tử img
            imgElement.style.maxHeight = height + 'px'; // Đặt giới hạn chiều rộng thông qua inline CSS
            imgElement.style.marginRight = 5 + 'px';
            imgElement.style.borderRadius = 1 + 'em';
            imgElement.style.marginTop = 5 + 'px';
            imagesContainer.appendChild(imgElement);
        }
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
        $('#form_update_food').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '/donate/update-donated-food',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: formData, 
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function (response) {
                    if(response.error){
                        showToast(response.error, 'danger');
                    }
                    else if (response.errors) {
                         response.errors.reverse().forEach(function(errorMessage) {
                        showToast(errorMessage, 'danger');
                    });
                    } else if (response.message) {
                        window.location.href = '{{ route("show_donate_list") }}';
                    }
                },
                error: function (error) {
                  if (error.status === 422) {
                      var errors = error.responseJSON.errors;
                  }
                } 
            });
        });
        document.querySelector('select[name="province_id"]').addEventListener('change', function() {
        var selectedProvince = this.value;
        $.ajax({
            url: "/get-district/" + selectedProvince,
            method: "GET",
            dataType: "json",
            success: function(data) {
                var districtSelect = $('select[name="district_id"]');
                districtSelect.empty();
                districtSelect.append('<option value="">Chọn quận/huyện</option>');
                $.each(data, function(key, value) {
                    districtSelect.append('<option value="' + key + '">' + value + '</option>');
                });
                var wardSelect = $('select[name="ward_id"]');
                wardSelect.empty();
                wardSelect.append('<option value="">Chọn phường/xã</option');
            }
        });

    });
});
</script>
<script>
    document.querySelector('select[name="district_id"]').addEventListener('change', function() {
            var selectedDistrict = this.value;
            console.log(selectedDistrict);
            $.ajax({
                url: "/get-ward/" + selectedDistrict,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    var wardSelect = $('select[name="ward_id"]');
                    wardSelect.empty();
                    wardSelect.append('<option value="">Chọn phường/xã</option');
                    $.each(data, function(key, value) {
                        wardSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        });
</script>
@endsection