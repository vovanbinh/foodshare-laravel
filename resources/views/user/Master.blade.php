@include('user/header')
@section('style')
<style>
    .dropdown-menu .dropdown-item span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-height: 3.6em; /* Hai dòng văn bản với kích thước phông chữ mặc định */
        line-height: 1.8em; /* Chiều cao của mỗi dòng văn bản */
    }
</style>
@endsection
  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        @include('user/User_left_menu')
        <!-- / Menu -->
        <!-- Layout container -->
        <div class="layout-page">
        <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <form id="search-form" method="get" action="{{ route('search') }}">
              @csrf
                <div class="navbar-nav align-items-center">
                  <div class="nav-item d-flex align-items-center">
                    <button type="submit" id="btn-search" name="btn_search" style="background:white; border:none;"><i class="bx bx-search fs-4 lh-0"></i></button>
                    <input
                      type="text"
                      name="searchContent"
                      class="form-control border-0 shadow-none"
                      placeholder="Search..."
                      aria-label="Search..."
                      value="{{ session('searchContent', '') }}"
                    />
                  </div>
                </div>
              </form>
              <!-- /Search -->
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                @if(!session('username'))
                  <a class="nav-link  hide-arrow" href="{{route('login')}}">
                    Login
                  </a>
                @else
                <li class="nav-item navbar-dropdown dropdown">
                  <a class="nav-link btn rounded-pill btn-icon btn-outline-info mt-1 dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" id="notification-dropdown-toggle" style="margin-right:5px; position: relative;">
                      <span class="tf-icons bx bx-bell"></span>
                      <span id="notification-count" class="badge bg-danger rounded-pill position-absolute" style="top: -7px; right: -7px"></span></span>
                  </a>
                  <ul class="dropdown-menu drop-notification dropdown-menu-end" style=" max-height: 300px; overflow-y: auto;">
                  </ul>
                </li>
               
                <!-- user -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" >
                    <div class="avatar avatar-online">
                      <img style="min-width:40px; min-height:40px;" src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" height="50" width="50"/>
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img style="min-width:40px; min-height:40px;" src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" height="50" width="50"/>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block" id="usernamePlaceholder"></span>
                            <small class="text-muted">Người dùng</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{route('show_profice')}}">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{Route('logout')}}">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                  @endif
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-12 mb-4 order-0">
                  @yield('content')
                </div>
              </div>
            </div>
    @include('user/Footer')
    @yield('js')
    <script>
      $(document).ready(function() {
          $.ajax({
              url: "{{ route('getNotificationCount') }}",
              method: "GET",
              dataType: "json",
              success: function(data) {
                  if (data.notificationCount > 0) {
                      $('#notification-count').text(data.notificationCount);
                  }
                  if (data.notifications && data.notifications.length > 0) {
                    var $dropdownMenu = $('.drop-notification');
                    $dropdownMenu.empty();
                    data.notifications.forEach(function(notification, index) {
                      var content = notification.message;
                      var maxLength = 35;
                      var truncatedContent = content.length > maxLength ? content.substring(0, maxLength) + "..." : content;
                      var $newItem;
                      if (notification.is_read == 0) {
                        $newItem = $('<li style="max-height: 40px;"><a class="dropdown-item text-info" href="/notifications/detail/' + notification.id + '"><p class="align-middle">' + truncatedContent + '</p></a></li>');
                      } else {
                        $newItem = $('<li style="max-height: 40px;"><a class="dropdown-item" href="/notifications/detail/' + notification.id + '"><p class="align-middle">' + truncatedContent + '</p></a></li>');
                      }
                      $dropdownMenu.append($newItem);
                      if (index < data.notifications.length - 1) {
                        $dropdownMenu.append('<li class="dropdown-divider"></li>');
                      }
                    });
                  }
              }
          });
      });
    </script>
     <script>
     $(document).ready(function() {
        $.ajax({
            url: "{{ route('getInfomation') }}",
            method: "GET",
            dataType: "json",
            success: function(data) {
                if (data.user) {
                   if (data.user.image) {
                      var userAvatarElements = document.querySelectorAll('.avatar img');
                      userAvatarElements.forEach(function(element) {
                          element.src = data.user.image;
                      });
                    } 
                    document.getElementById("usernamePlaceholder").innerText = data.user.username;
                }
            }
        });
    });
    </script>
</body>
</html>

