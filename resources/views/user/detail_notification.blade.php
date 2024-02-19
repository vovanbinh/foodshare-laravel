@extends('user/master')
@section('content')
<div class="card mb-4">
    <h5 class="card-header">Chi Tiết Thông Báo</h5>
    <div class="card-body">
      <small class="text-light fw-semibold">Thời gian: <p>{{$notification->created_at}}</p></small>
      <small class="text-light fw-semibold">Nội Dung</small>
      <div class="demo-inline-spacing">
        <div class="alert alert-primary" role="alert">{{$notification->message}}</div>
      </div>
      <hr class="mt-2 mb-0">
      <div class="demo-inline-spacing">
        @if($transaction->donor_status == 0)
        <a href="/confirm-notification/{{$transaction->id}}" class="btn rounded-pill btn-success">Đồng Ý</a>
        <a href="/cancel-notification/{{$transaction->id}}" class="btn rounded-pill btn-danger">Từ Chối</a>
        @elseif($donor=="donor" || $home == "home")
        <a href="/index" class="btn rounded-pill btn-success">Trang Chủ</a>
        @else
        <a href="/receiving/detail-received/{{$transaction->id}}" class="btn rounded-pill btn-info">Xem Thực Phẩm Đã Nhận</a>
        @endif
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