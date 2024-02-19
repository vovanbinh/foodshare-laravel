@extends('user/master')
@section('content')
<div class="card mb-4">
    <h5 class="card-header">Tri Ân</h5>
    <div class="card-body">
      <small class="text-light fw-semibold">Nội Dung</small>
      <div class="demo-inline-spacing">
        <div class="alert alert-primary" role="alert">{{$notification}}</div>
      </div>
      <hr class="mt-2 mb-0">
      <div class="demo-inline-spacing">
        <a href="/index" class="btn rounded-pill btn-info">Trang Chủ</a>
      </div>
    </div>
  </div>
@endsection