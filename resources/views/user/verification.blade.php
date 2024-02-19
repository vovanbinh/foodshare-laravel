<!DOCTYPE html>
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

    <title>Verification</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
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
          <!-- Register Card -->
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
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="{{ route('index') }}" class="app-brand-link gap-2">
                  <img alt="logo" style="max-width:50px;" src="/assets/img/icons/brands/logo.png">
                  <span class="app-brand-text demo text-body fw-bolder">FoodShare</span>
                </a>
              </div>
              <h4 class="mb-2">Welcome to FoodShare🚀</h4>
              <p class="mb-4">Please check your email and enter the verification code!</p>
              <!-- form -->
              <form id="form_verifi" class="mb-3">
                 @csrf
                <div class="mb-3">
                  <label for="verifi_code" class="form-label">Verifi Code</label>
                  <input
                    type="text"
                    class="form-control"
                    id="verifi_code"
                    name="verifi_code"
                    placeholder="Enter Verifi Code"
                    autofocus
                  />
                </div>
                <button type="submit" id="verifi" class="btn btn-primary d-grid w-100">Verifi</button>
              </form>
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
  <script>
    $(document).ready(function () {
      var notverification = sessionStorage.getItem('notverification');
      var registerdone = sessionStorage.getItem('registerdone');
        function showToast(message, type) {
            var toast = document.querySelector('.bs-toast');
            var toastBody = toast.querySelector('.toast-body');
            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning');
            toast.classList.add('bg-' + type);
            toastBody.textContent = message;
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
        if (notverification !== null) {
            var textContent = "Chúng tôi đã gửi cho bạn mã xác thực vào email, vui lòng kiểm tra và nhập để xác thực.";
            showToast(textContent, 'success');
            sessionStorage.removeItem('notverification');
        }
        if (registerdone !== null) {
            var textContent = "Chúng tôi đã gửi cho bạn mã xác thực vào email, vui lòng kiểm tra và nhập để xác thực.";
            showToast(textContent, 'success');
            sessionStorage.removeItem('registerdone');
        }
        $('#form_verifi').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize(); 
            $.ajax({
                type: 'POST',
                url: '{{ route("verification") }}',
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
                    } else if (response.message) {
                        window.location.href = '{{route("showlogin") }}';
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
