@extends('user/master')
@section('content')
<div class="row">
    <div class="col-md-6 col-lg-3">
        <button class="btn btn-success mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">
            Lọc Thực Phẩm
        </button>
        <button id="reload-button" class="btn btn-icon btn-outline-secondary mb-3" title="Tải lại trang">
            <span class="tf-icons bx bx-reset"></span>
        </button>
    </div>
    <div class="offcanvas offcanvas-top" style="min-height:300px;" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="visibility: visible;" aria-modal="true" role="dialog">
        <div class="offcanvas-header">
        <h5 id="offcanvasTopLabel" class="offcanvas-title">Lọc Thực Phẩm</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
        <form id="search-form" method="get" action="{{ route('search') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="demo-inline-spacing mb-1">
                        <label for="province_id">Tỉnh/Thành Phố</label>
                        <select id="province_id" class="form-select" name="province" style="min-width: 200px;" >
                            <option value="null">Chọn Tỉnh/Thành Phố</option>
                            @foreach($provinces as $key => $province)
                                <option value="{{$province->id}}">{{$province->name}}</option>
                            @endforeach
                            <option value="Tất Cả">Tất Cả</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" id="district" hidden>
                    <div class="demo-inline-spacing mb-1">
                        <label for="district_id">Quận/Huyện</label>
                        <select id="district_id" class="form-select" style="min-width: 200px;" name="district" >
                        
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 " hidden id="ward">
                    <div class="demo-inline-spacing mb-1">
                        <label for="ward_id">Phường/Xã</label>
                        <select id="ward_id" class="form-select" style="min-width: 200px;" name="ward">
                        
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="demo-inline-spacing mb-1">
                        <label for="food_type">Loại Thực Phẩm</label>
                        <select id="food_type" class="form-select" style="min-width: 200px;" name="food_type" >
                        <option value="null">Tất Cả Loại</option>
                        <option value="1">Đã Chế Biến</option>
                        <option value="2">Chưa Chế Biến</option>
                        <option value="3">Đồ Ăn Nhanh</option>
                        <option value="4">Hải Sản</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary me-2 mt-2">Lọc Thực Phẩm</button>
            <button type="button" class="btn btn-outline-secondary mt-2" data-bs-dismiss="offcanvas">
                Cancel
            </button>
        </div>
    </div>
    </form>
</div>
<div class="card">
    
</div>
@if ($selectedProvince || $selectedDistrict || $selectedWard || $selectedFoodType)
    <div class="results text-warning">
        Thông tin bạn đã chọn:

        @if ($selectedProvince)
            {{ $selectedProvince }}
        @endif

        @if ($selectedDistrict)
            @if ($selectedProvince)
                >>
            @endif
            {{ $selectedDistrict }}
        @endif

        @if ($selectedWard)
            @if ($selectedProvince || $selectedDistrict)
                >>
            @endif
            {{ $selectedWard }}
        @endif

        @if ($selectedFoodType)
            @if ($selectedProvince || $selectedDistrict || $selectedWard)
                >>
            @endif
            {{ $selectedFoodType }}
        @endif
    </div>
@endif
@if ($listFood->isEmpty())
    <div class="alert alert-primary" role="alert">Không có sản phẩm nào phù hợp với thông tin tìm kiếm</div>
@else
<p class="text-success">{{ $listFood->total() }} thực phẩm</p>
<div class="row mb-5" id="food-list" style="min-height:540px;">
    @foreach($listFood as $key => $food)
        <div class="col-md-6 col-lg-3 mb-3">
            <a href="{{ route('show_detail_food',['food_id' => $food->id])}}">
                <div class="card h-100">
                    <img class="card-img-top" src="{{$food->image_url}}" alt="Card image cap"
                        style="width: 100%; height: 300px; object-fit: cover; object-position: center center;" />
                    <div class="card-body">
                        <h5 class="card-title" style="min-height: 2em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{$food->title}}</h5>
                        <p class="card-text  mb-3" style="min-height: 3em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            <i class="bx bx-location-plus"></i>
                            {{$food->location}},
                            {{ $food->ward_name }}, 
                            {{ $food->district_name }}, 
                            {{ $food->province_name }}
                            &nbsp;
                        </p>
                        <p class="card-text text-black">Loại thực phẩm: @if($food->food_type == 1)
                        Đã Chế Biến
                        @elseif($food->food_type == 2)
                        Chưa Chế Biến
                        @elseif($food->food_type == 3)
                        Đồ Ăn Nhanh
                        @elseif($food->food_type == 4)
                        Hải Sản
                        @endif</p>
                        <p class="card-text text-success">
                            Free
                        </p>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
<div class="row">
    <div class="col text-center">
        <small class="text-light fw-semibold">Trang: {{ $listFood->currentPage() }}/{{ $listFood->lastPage() }}</small>
        <div class="demo-inline-spacing">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($listFood->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $listFood->previousPageUrl() }}" aria-label="Previous">
                                <i class="tf-icon bx bx-chevrons-left"></i>
                            </a>
                        </li>
                    @endif
                    @if ($listFood->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $listFood->previousPageUrl() }}" aria-label="Previous">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>
                    @endif
                    @foreach ($listFood->getUrlRange(1, $listFood->lastPage()) as $page => $url)
                        @if ($page == $listFood->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                    @if ($listFood->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $listFood->nextPageUrl() }}" aria-label="Next">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevron-right"></i></span>
                        </li>
                    @endif
                    @if ($listFood->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $listFood->url($listFood->lastPage()) }}" aria-label="Last">
                                <i class="tf-icon bx bx-chevrons-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
@endif
@endsection
@section('js')
<script>
    document.querySelector('select[id="province_id"]').addEventListener('change', function() {
        var selectedIndex = this.selectedIndex;
        var selectedProvince = this.options[selectedIndex].value;
        $.ajax({
            url: "/get-district/" + selectedProvince,
            method: "GET",
            dataType: "json",
            success: function(data) {
                var districtSelect = $('select[id="district_id"]');
                districtSelect.empty();
                districtSelect.append('<option value="">Chọn quận/huyện</option>');
                $.each(data, function(key, value) {
                    districtSelect.append('<option value="' + key + '">' + value + '</option>');
                });
                var wardSelect = $('select[id="ward_id"]');
                wardSelect.empty();
                wardSelect.append('<option value="">Chọn phường/xã</option');
                document.getElementById('district').removeAttribute('hidden');
            }
        });
    });
</Script>
<script>
    document.querySelector('select[id="district_id"]').addEventListener('change', function() {
        var selectedDistrict = this.value;
        $.ajax({
            url: "/get-ward/" + selectedDistrict,
            method: "GET",
            dataType: "json",
            success: function(data) {
                var wardSelect = $('select[id="ward_id"]');
                wardSelect.empty(); 
                wardSelect.append('<option value="">Chọn phường/xã</option>');
                $.each(data, function(key, value) {
                    wardSelect.append('<option value="' + key + '">' + value + '</option>');
                });
                document.getElementById('ward').removeAttribute('hidden');
            }
        });
    });
</script>
<script>
document.getElementById('reload-button').addEventListener('click', function() {
    window.location.href = '/search';
});
document.addEventListener('DOMContentLoaded', function () {
    var toggleButton = document.querySelector('[data-bs-target="#offcanvasTop"]');
    var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasTop'));
    toggleButton.addEventListener('click', function () {
        if (offcanvas.classList.contains('show')) {
            offcanvas.classList.remove('show');
        } else {
            offcanvas.classList.add('show');
        }
    });
});
</script>
@endsection