@extends('user/master')
@section('content')
<div class="row">
    <div class="card mb-4 col-lg-6">
        <h5 class="card-header">Bảng Xếp Hạng Tặng Thực Phẩm</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">STT</th>
                        <th class="text-center align-middle">Username</th>
                        <th class="text-center align-middle">Số Lượng Tặng</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($topUsers as $key => $user)
                        <tr class="@if($key == 0) text-success @endif">
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="text-center align-middle">{{ $user->username }}</td>
                            <td class="text-center align-middle">{{ $user->total_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6 mb-4 order-0" >
        <div class="col-md">
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselExample" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselExample" data-bs-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <img class="d-block w-100" src="../assets/img/elements/13.jpg" alt="First slide" style="border-radius:0.5em;"/>
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Chào mừng bạn đến với Food Share</h3>
                        <p>Hãy tham gia cùng chúng tôi trong sứ mệnh chia sẻ thực phẩm với những người cần. Cùng nhau, chúng ta có thể làm nên sự khác biệt.</p>
                    </div>
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100" src="../assets/img/elements/2.jpg" alt="Second slide"style="border-radius:0.5em;" />
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Chia Sẻ là Yêu Thương</h3>
                        <p>Khám phá niềm vui của việc chia sẻ thực phẩm dư thừa với những người khác cần sự giúp đỡ.</p>
                    </div>
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100" src="../assets/img/elements/18.jpg" alt="Third slide"style="border-radius:0.5em;" />
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Cùng Nhau Tạo Nên Sự Khác Biệt</h3>
                        <p>Food Share là nền tảng để kết nối với những người quan tâm đến việc giảm lãng phí thực phẩm và giúp đỡ người khác.</p>
                    </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>

</script>
@endsection