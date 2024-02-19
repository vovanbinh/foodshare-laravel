@extends('user/master')
@section('content')
<div class="col-md-12">
    <div class="card mb-4"> 
        <form id="form_add_food" enctype="multipart/form-data">
             @csrf <h5 class="card-header">Add
        Donate Food</h5> <div class="card-body"> <div class="mb-3">
        <label for="title" class="form-label">Tiêu đề</label>
        <input
        type="text"
        name="title"
        class="form-control"
        placeholder="Nhập tiêu đề"
        />
        </div>
        <div class="mb-3">
            <label for="food_type" class="col-md-5 col-form-label">Loại Thực Phẩm</label>
            <select class="form-select" name="food_type" aria-label="Default select example">
            <option value="1">Đã Chế Biến</option>
            <option value="2">Chưa Chế Biến</option>
            <option value="3">Đồ Ăn Nhanh</option>
            <option value="4">Hải Sản</option>
            </select>
        </div>
        <div class="mb-3">
            <div>
                <label for="description" class="form-label">Mô Tả</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="mb-3">
            <label for="quantity" class="col-md-2 col-form-label">Số Lượng</label>
            <div class="col-md-12">
                <input name="quantity" class="form-control" type="number" value="1" />
            </div>
        </div>
        <div class="mb-3">
            <label for="expiry_date" class="col-md-2 col-form-label">Thời Gian Hết Hạn</label>
            <div class="col-md-12">
                <input class="form-control" name="expiry_date" type="datetime-local" />
            </div>
        </div>
        <div class="mb-3">
            <label for="confirm_time" class="col-md-5 col-form-label">Thời Gian Chấp Nhận</label>
            <div id="defaultFormControlHelp" class="form-text">ex: 30 phút sau khi người nhận xác nhận, nếu sau 30 phút
                người nhận không đến lấy sẽ tự động hủy giao dịch.</div>
            <select class="form-control" name="confirm_time">
                <option value="30">30 phút </option>
                <option value="60">1 tiếng</option>
                <option value="90">1 tiếng 30 phút</option>
                <option value="120">2 tiếng</option>
                <option value="150">2 tiếng 30 phút</option>
                <option value="180">3 tiếng</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="location_id" class="col-md-2 col-form-label">Địa Điểm Nhận</label>
            <div id="defaultFormControlHelp" class="form-text">Tỉnh/Thành phố</div>
            <select style=" max-height: 200px; overflow-y: auto;" class="form-select" name="province_id"
                aria-label="Default select example">
                <option value="null">Chọn Tỉnh/Thành Phố</option>
                @foreach($provinces as $key => $province)
                <option value="{{$province->id}}">{{$province->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <div id="defaultFormControlHelp" class="form-text">Quận/Huyện</div>
            <select style=" max-height: 200px; overflow-y: auto;" class="form-select" name="district_id"
                aria-label="Default select example">
            </select>
        </div>
        <div class="mb-3">
            <div id="defaultFormControlHelp" class="form-text">Phường/Xã</div>
            <select style=" max-height: 200px; overflow-y: auto;" class="form-select" name="ward_id"
                aria-label="Default select example">
            </select>
        </div>
        <div class="mb-3">
            <div id="defaultFormControlHelp" class="form-text">Địa điểm cụ thể (Số Nhà, Đường)</div>
            <input type="text" name="location" class="form-control" placeholder="Nhập địa chỉ cụ thể" />
        </div>
        <div class="mb-3">
            <label for="contact_information" class="col-md-6 col-form-label">Thông tin liên hệ (Số Điện Thoại hoặc Đường
                Link Mạng Xã Hội)</label>
            <input type="text" class="form-control" name="contact_information"
                placeholder="Nhập số điện thoại hoặc link mạng xã hội" />
        </div>
        <div class="mb-3">
            <label for="image_url" class="col-md-5 col-form-label">Ảnh Thực Phẩm</label>
            <input type="file" name="image_url" class="form-control" id="inputImage" />
        </div>
        <div class="mb-3">
            <div id="image">

            </div>
        </div>
        <div class="mb-3">
            <label for="images_food" class="col-md-5 col-form-label">Ảnh Mô Tả</label>
            <input type="file" name="images_food[]" class="form-control" id="inputImages" multiple />
        </div>
        <div id="images" class="mb-3">
            <!-- Các ảnh đã chọn sẽ được hiển thị ở đây -->
        </div>
        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-success">Add Food</button>
        </div>
    </div>
    </form>
    <!-- toast -->
    <div class="bs-toast toast toast-placement-ex m-2 fade bg-warning top-0 end-0" role="alert" aria-live="assertive"
        aria-atomic="true" data-delay="2000">
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
    // hiển thị 1 ảnh
    const inputImage = document.getElementById('inputImage');
    const imageContainer = document.getElementById('image');
    const maxWidth = 300;
    inputImage.addEventListener('change', function (event) {
        const selectedImage = event.target.files[0];
        if (selectedImage) {
            const imgElement = document.createElement('img');
            imgElement.src = URL.createObjectURL(selectedImage);
            imgElement.classList.add('max-width-image'); // Thêm lớp CSS cho phần tử img
            imgElement.style.maxWidth = maxWidth + 'px'; // Đặt giới hạn chiều rộng thông qua inline CSS
            imgElement.style.borderRadius = 1 + 'em';
            while (imageContainer.firstChild) {
                imageContainer.removeChild(imageContainer.firstChild);
            }
            imageContainer.appendChild(imgElement);
        }
    });
    //hiển thị nhiều ảnh
    const inputImages = document.getElementById('inputImages');
    const imagesContainer = document.getElementById('images');
    const height = 200;

    inputImages.addEventListener('change', function (event) {
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
    //thêm food
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
        $('#form_add_food').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: '/donate/add-donated-food',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: formData,
                processData: false, // Không xử lý dữ liệu
                contentType: false, // Không thiết lập kiểu nội dung
                dataType: 'json',
                success: function (response) {
                    if (response.error) {
                        showToast(response.error, 'danger');
                    }
                    else if (response.errors) {
                        response.errors.reverse().forEach(function (errorMessage) {
                            showToast(errorMessage, 'danger');
                        });
                    } else if (response.message) {
                        sessionStorage.setItem('add_donated_food_success', 'Thêm Thực Phẩm Thành Công');
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

        document.querySelector('select[name="province_id"]').addEventListener('change', function () {
            var selectedProvince = this.value;
            $.ajax({
                url: "/get-district/" + selectedProvince,
                method: "GET",
                dataType: "json",
                success: function (data) {
                    var districtSelect = $('select[name="district_id"]');
                    districtSelect.empty();
                    districtSelect.append('<option value="">Chọn quận/huyện</option>');
                    $.each(data, function (key, value) {
                        districtSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                    var wardSelect = $('select[name="ward_id"]');
                    wardSelect.empty();
                }
            });

        });
    });
</script>
<script>
    document.querySelector('select[name="district_id"]').addEventListener('change', function () {
        var selectedDistrict = this.value;
        $.ajax({
            url: "/get-ward/" + selectedDistrict,
            method: "GET",
            dataType: "json",
            success: function (data) {
                var wardSelect = $('select[name="ward_id"]');
                wardSelect.empty();
                wardSelect.append('<option value="">Chọn phường/xã</option>');
                $.each(data, function (key, value) {
                    wardSelect.append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    });
</script>
@endsection