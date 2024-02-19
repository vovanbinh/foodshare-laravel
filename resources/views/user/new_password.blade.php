<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>New Password</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <div class="card">
            <div class="card-body">
               {{-- Toast --}}
                <div class="bs-toast toast toast-placement-ex m-2 fade bg-warning top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                    <div class="toast-header">
                        <i class="bx bx-bell me-2"></i>
                        <div class="me-auto fw-semibold">FoodShare</div>
                        <small>now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body"></div>
                </div>
              <div class="app-brand justify-content-center">
                <a href="{{ route('index')}}" class="app-brand-link gap-2">
                  <img alt="logo" style="max-width:50px;" src="/assets/img/icons/brands/logo.png">
                  <span class="app-brand-text demo text-body fw-bolder">FoodShare</span>
                </a>
              </div>
              <h4 class="mb-2">Welcome to FoodShare👋</h4>
              <p class="mb-4">Vui Lòng Tạo Mới Mật Khẩu</p>
              <form id="form_newpass" class="mb-3">
                <div class="mb-3">
                    <label for="password" class="form-label">Mật Khẩu Mới</label>
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <label for="rpassword" class="form-label">Nhập Lại Mật Khẩu Mới</label>
                    <input
                      type="password"
                      id="rpassword"
                      class="form-control"
                      name="rpassword"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="rpassword"
                    />
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary d-grid w-100" ">Xác Nhận</button>
                </div>
            </form>
              <p class="text-center">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                  <span>Create an account</span>
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
  <script>
    $(document).ready(function () {
        var confim_forgot_code = sessionStorage.getItem('confim-forgot-code');
        function showToast(message, type) {
            var toast = document.querySelector('.bs-toast');
            var toastBody = toast.querySelector('.toast-body');
            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
            toast.classList.add('bg-' + type);
            toastBody.textContent = message;
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
        if (confim_forgot_code !== null) {
            var textContent = "Xác nhận mã thành công, vui lòng thay đổi mật khẩu";
            showToast(textContent, 'success');
            sessionStorage.removeItem('confim-forgot-code');
        }
        $('#form_newpass').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize(); 
            $.ajax({
                type: 'POST',
                url: '/new-password-forgot',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: formData, 
                dataType: 'json',
                success: function (response) {
                    if (response.errors) {
                        response.errors.reverse().forEach(function(errorMessage) {
                        showToast(errorMessage, 'danger');
                    });
                }
                    else if (response.message) {
                        sessionStorage.setItem('new_password_forgot', 'done');
                        window.location.href = '{{route("login") }}';
                    }
                },
                error: function (error) {
                  if (error.status === 422) {
                      var errors = error.responseJSON.errors;
                      console.log(errors);
                  }
                }
            });
        });
    });
    </script>
</html>
