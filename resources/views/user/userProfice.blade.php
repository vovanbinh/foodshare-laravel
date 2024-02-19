@extends('user/master')
@section('content')
<div class="card mb-4">
    <h5 class="card-header">Thông tin các nhân</h5>
    <!-- Account -->
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
            @if($user->image!=null)
            <img src="{{$user->image}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
            @else
            <img src="../assets/img/avatars/1.png" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
            @endif
            <button type="button" class="btn btn-confirm btn-primary show_model" >Cập Nhật Ảnh Mới</button>
        </div>
    </div>
    <hr class="my-0">
    <div class="card-body">
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                    <i  class="tf-icons bx bx-user"></i> Profice
                </button>
                </li>
                <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false">
                    <i class="tf-icons bx bx-key"></i> Password
                </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel" style="min-height: 300px;">
                <form id="form_update_profice" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label   label for="name" class="form-label">Họ và Tên</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{$user->full_name}}" placeholder="ex: Nguyễn Văn A" >
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <label for="email" class="form-control alert-primary">{{$user->email}}</label>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="phoneNumber">Phone Number</label>
                            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="{{$user->phone_number}}" placeholder="ex: 0911617107">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{$user->address}}" placeholder="ex: 12 Phạm Văn Xảo, Sơn Trà, Đà Nẵng">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input class="form-control" type="date" id="date" name="date" value="{{$user->birthdate}}" >
                        </div>
                    </div>
                    <div class="mt-2" class="d-flex ">
                        <button type="submit" class="btn btn-primary" id="saveChangesBtn" style="display: none;">Thay Đổi</button>
                    </div>
                </form>
                </div>
                <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel" style="min-height: 300px;"> 
                    <form id="form_update_password" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <div class="form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="oldpassword">Mật khẩu cũ</label>
                                        </a>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="oldpassword" class="form-control" name="oldpassword" placeholder="············" aria-describedby="password">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <div class="form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Mật Khẩu Mới</label>
                                        </a>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="············" aria-describedby="password">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <div class="form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="rpassword">Nhập Lại Mật Khẩu Mới</label>
                                        </a>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="rpassword" class="form-control" name="rpassword" placeholder="············" aria-describedby="password">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 mt-4">
                                <button type="submit" class="btn btn-warning" id="update_password_btn">Thay Đổi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
<!-- model -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Thay đổi ảnh đại diện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="new_image" enctype="multipart/form-data">
            @csrf
                <div class="modal-body" style="min-height:300px;">
                    <div class="row">
                        <div class="button-wrapper">
                            <p class="text-muted mb-2">Cho phép JPG, GIF, JPEG or PNG. Max size of 2MB</p>
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Chọn Ảnh</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" name="image" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                            </label>
                            <div id="image">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-confirm btn-primary" data-bs-dismiss="modal">Xác Nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("form_update_profice");
        const saveChangesBtn = document.getElementById("saveChangesBtn");
        const inputs = form.querySelectorAll("input, select, textarea");
        inputs.forEach((input) => {
            input.addEventListener("input", function () {
                saveChangesBtn.style.display = "block";
            });
        });
    });
    $(".show_model").click(function () {
        $("#basicModal").modal("show");
    });
    $("#update_password_btn").click(function () {
        $("#update_password_model").modal("show");
    });
    
    var update_success = sessionStorage.getItem('update_success');
    var update_password_success = sessionStorage.getItem('update_password_success');
    const upload = document.getElementById('upload');
    const imageContainer = document.getElementById('image');
    const height = 200; 
    const maxwidth = 300; 
    upload.addEventListener('change', function (event) {
        const selectedImage = event.target.files[0];
        if (selectedImage) {
            const imgElement = document.createElement('img');
            imgElement.src = URL.createObjectURL(selectedImage);
            imgElement.classList.add('max-width-image'); 
            imgElement.style.height = height + 'px'; 
            imgElement.style.maxWidth = maxwidth + 'px'; 
            imgElement.style.borderRadius = 1 + 'em';
            while (imageContainer.firstChild) {
                imageContainer.removeChild(imageContainer.firstChild);
            }
            imageContainer.appendChild(imgElement);
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
        if (update_success !== null) {
            var textContent = "Cập Nhật Thông Tin Thành Công";
            showToast(textContent, 'success');
            sessionStorage.removeItem('update_success');
        }
        if (update_password_success !== null) {
            var textContent = "Cập Nhật Mật Khẩu Thành Công";
            showToast(textContent, 'success');
            sessionStorage.removeItem('update_password_success');
        }
        $('#new_image').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '/user/newavatar',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: formData, 
                processData: false, // Không xử lý dữ liệu
                contentType: false, // Không thiết lập kiểu nội dung
                dataType: 'json',
                success: function (response) {
                    if(response.error){
                        showToast(response.error, 'danger');
                    } else if (response.message) {
                        window.location.reload();
                    }
                },
                error: function (error) {
                  if (error.status === 422) {
                      var errors = error.responseJSON.errors;
                  }
                } 
            });
        });
        $('#form_update_profice').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '/user/update-profile',
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
                        sessionStorage.setItem('update_success', 'Cập nhật ảnh thành công');
                        window.location.reload();
                    }
                },
                error: function (error) {
                  if (error.status === 422) {
                      var errors = error.responseJSON.errors;
                  }
                } 
            });
        });
        $('#form_update_password').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '/user/update-password',
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
                        sessionStorage.setItem('update_password_success', 'Cập nhật mật khẩu thành công');
                        window.location.reload();
                    }
                },
                error: function (error) {
                  if (error.status === 422) {
                      var errors = error.responseJSON.errors;
                  }
                } 
            });
        });

    });

</script>
@endsection