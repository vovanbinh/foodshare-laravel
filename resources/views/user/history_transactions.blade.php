@extends('user/master')
@section('content')
<div class="card">
    <h5 class="card-header">Danh Sách Các Giao Dịch Nhận Thực Phẩm Của Bạn</h5>
    <div class="card-body ">
        <div class="table-responsive" style="min-height:500px;" >
        <table class="table table-bordered" >
            <thead>
            <tr>
                <th class="text-center align-middle">STT</th>
                <th class="text-center align-middle">Tên Thực Phẩm</th>
                <th class="text-center align-middle">Hình Ảnh</th>
                <th class="text-center align-middle">Người Nhận</th>
                <th class="text-center align-middle">Số Lượng Nhận</th>
                <th class="text-center align-middle">Thời Gian Ấn Nhận</th>
                <th class="text-center align-middle">Thời Gian Nhận</th>
                <th class="text-center align-middle">Trạng Thái</th>
                <th class="text-center align-middle">Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $transaction->food->title }}</td>
                        <td class="text-center">
                            <img style="width: 70px; height: 70px; border-radius:50%; object-fit: cover; object-position: center center;" alt="Ảnh Thực Phẩm" src="{{ $transaction->food->image_url }}">
                        </td>
                        <td class="text-center">{{ $transaction->receiver->username }}</td>
                        <td class="text-center">{{ $transaction->quantity_received }}</td>
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
                                <span class="badge bg-label-danger me-1">Bị Hủy Do Hết Thời Gian Nhận</span>
                            @endif
                            </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info" style="min-width:100px;">Đã nhận</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
        </table>
        </div>
    </div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection