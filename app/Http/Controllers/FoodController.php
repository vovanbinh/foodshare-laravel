<?php

namespace App\Http\Controllers;

use App\Models\district;
use App\Models\Food;
use App\Models\ImagesFood;
use App\Models\Location;
use App\Models\province;
use App\Models\Users;
use App\Models\ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\rate;
use Illuminate\Support\Facades\DB;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->forget('searchContent');
        $currentDateTime = Carbon::now();
        $query = Food::join('province', 'food.province_id', '=', 'province.id')
            ->select('food.*', 'province.name as province_name')
            ->leftJoin('district', 'food.district_id', '=', 'district.id')
            ->select('food.*', 'province.name as province_name', 'district.name as district_name')
            ->leftJoin('ward', 'food.ward_id', '=', 'ward.id')
            ->select('food.*', 'province.name as province_name', 'district.name as district_name', 'ward.name as ward_name')
            ->where('food.quantity', '>', 0)
            ->where('food.expiry_date', '>', $currentDateTime)
            ->whereNotIn('food.status', [2, 4]);
    
        if ($request->has('searchContent')) {
            $searchContent = $request->input('searchContent');
            session(['searchContent' => $searchContent]);
            $query->where(function ($q) use ($searchContent) {
                $q->where('food.title', 'like', '%' . $searchContent . '%')
                    ->orWhere('food.description', 'like', '%' . $searchContent . '%');
            });
        } 
        $selectedProvince = null;
        $selectedDistrict = null;
        $selectedWard = null;
        $selectedFoodType = null;
        if ($request->filled('food_type')) {
            $food_type = $request->input('food_type');
            if ($food_type !== 'null') { 
                $query->where('food.food_type', $food_type);
                if($food_type==1){
                    $food_type = "Đã Chế Biến";
                }else if($food_type==2) {
                    $food_type = "Chưa Chế Biến";
                }else if($food_type==3){
                    $food_type = "Đồ Ăn Nhanh";
                }
                else if($food_type==4){
                    $food_type = "Hải Sản";
                }
                $selectedFoodType = $food_type;
            }
        }
    
        if ($request->filled('province')) {
            $provinceId = $request->input('province');
            if ($provinceId != 'null') {
                $query->where('food.province_id', $provinceId);
                $province = Province::where('id', $provinceId)->first();
                if ($province) {
                    $selectedProvince = $province->name; 
                }
            }
        }
    
        if ($request->filled('district')) {
            $districtId = $request->input('district');
            if ($districtId != 'null') {
                $query->where('food.district_id', $districtId);
                $district = district::where('id', $districtId)->first();
                if ($district) {
                    $selectedDistrict = $district->name; 
                }
            }
        }
    
        if ($request->filled('ward')) {
            $wardId = $request->input('ward');
            if ($wardId != 'null') {
                $query->where('food.ward_id', $wardId);
                $ward = ward::where('id', $wardId)->first();
                if ($ward) {
                    $selectedWard = $ward->name; 
                }
            }
        }
       
        $listFood = $query->paginate(6);
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('user/search', compact('listFood', 'provinces', 'selectedProvince', 'selectedDistrict', 'selectedWard', 'selectedFoodType'));
    }
    public function food_share_home()
    {
        $topUsers = Users::select('users.username', DB::raw('SUM(food.quantity) as total_quantity'))
            ->leftJoin('food', 'users.id', '=', 'food.user_id')
            ->groupBy('users.username')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('user/index', ['topUsers' => $topUsers]);
    }

    public function get_district($province_id)
    {
        $districts = District::where('province_id', $province_id)->pluck('name', 'id');
        return response()->json($districts);
    }
    public function
        get_ward(
        $ward_id
    ) {
        $ward = ward::where('district_id', $ward_id)->pluck('name', 'id');
        return response()->json($ward);
    }
    public function show_add_donated_food()
    {
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('user/add_donated_food', compact('provinces'));
    }
    public function show_update_donate_food($food_donate_id)
    {
        $food = Food::find($food_donate_id);
        $imageUrls = ImagesFood::where('food_id', $food_donate_id)->pluck('image_url')->toArray();
        if (empty($food)) {
            return redirect('/search');
        } else {
            $province_old = $food->province;
            $district_old = $food->district;
            $ward_old = $food->ward;
            $provinces = province::all();
            $districts = district::where('province_id', $province_old->id)->get();
            $wards = ward::whereIn('district_id', $districts->pluck('id')->toArray())->get();
            return View('user/update_donate_food', compact('food', 'province_old', 'district_old', 'ward_old', 'provinces', 'districts', 'wards', 'imageUrls'));
        }
    }
    public function show_detail_food($food_id)
    {
        $food = Food::find($food_id);
        if (empty($food)) {
            return View('error404');
        } else if ($food->status == 2 || $food->status==4) {
            return View('error404');
        } else {
            $imageUrls = ImagesFood::where('food_id', $food_id)->pluck('image_url')->toArray();
            $foodData = $food->toArray();
            $user = Users::find($foodData['user_id']);
            $province = province::find($foodData['province_id']);
            $district = district::find($foodData['district_id']);
            $ward = ward::find($foodData['district_id']);
            $combinedData = array_merge($foodData, ['imageUrls' => $imageUrls]);
            $ratings = [];
            $userratings = [];
            if ($food->foodTransactions) {
                foreach ($food->foodTransactions as $transaction) {
                    $transactions[] = $transaction;
                    $transactionRatings = rate::where('food_transaction_id', $transaction->id)->first();
                    if (isset($transactionRatings)) {
                        $ratings[$transaction->id] = $transactionRatings;
                    } else {
                        $ratings[$transaction->id] = null;
                    }
                }
            }
            return view('user/detail_food', compact('foodData', 'imageUrls', 'user', 'province', 'ward', 'district', 'ratings', 'userratings')); // Corrected variable name
        }
    }

    public function show_donate_list()
    {
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if ($user) {
            $donatedFoods = Food::where('user_id', $user->id)->get();
            return view('user/manage_list_donate', compact('donatedFoods'));
        }
        return redirect()->back()->with('error', 'Không tìm thấy tài khoản.');
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_donated_food(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'food_type' => 'required|in:1,2,3,4',
            'description' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date',
            'confirm_time' => 'required|in:30,60,90,120,150,180',
            'province_id' => 'required|exists:province,id',
            'district_id' => 'required|exists:district,id',
            'ward_id' => 'exists:ward,id',
            'location' => 'required|string|max:255',
            'contact_information' => 'required|string|max:255',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images_food' => 'array',
            'images_food.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'food_type.required' => 'Vui lòng chọn loại thực phẩm.',
            'food_type.in' => 'Loại thực phẩm không hợp lệ.',
            'description.required' => 'Vui lòng nhập mô tả.',
            'description.max' => 'Vui lòng nhập mô tả ngắn hơn.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            'expiry_date.required' => 'Vui lòng nhập thời gian hết hạn.',
            'expiry_date.date' => 'Thời gian hết hạn không hợp lệ.',
            'confirm_time.required' => 'Vui lòng nhập thời gian chấp nhận.',
            'confirm_time.in' => 'Thời gian chấp nhận không hợp lệ.',
            'province_id.required' => 'Vui lòng chọn Tỉnh/Thành Phố hợp lệ.',
            'province_id.in' => 'Tỉnh/Thành Phố không hợp lệ.',

            'district_id.required' => 'Vui lòng chọn Quận/Huyện.',
            'district_id.in' => 'Quận/Huyện không hợp lệ.',
            'ward_id.in' => 'Phường/Xã không hợp lệ.',

            'location.required' => 'Vui lòng nhập địa chỉ cụ thể.',
            'location.max' => 'Địa chỉ cụ thể không được vượt quá 255 ký tự.',
            'contact_information.required' => 'Vui lòng nhập thông tin liên hệ.',
            'contact_information.max' => 'Thông tin liên hệ không được vượt quá 255 ký tự.',
            'image_url.required' => 'Vui lòng chọn ảnh thực phẩm.',
            'image_url.image' => 'Ảnh thực phẩm phải là hình ảnh.',
            'image_url.mimes' => 'Ảnh thực phẩm phải có định dạng jpeg, png, jpg hoặc gif.',
            'image_url.max' => 'Ảnh thực phẩm không được vượt quá 2MB.',
            'images_food.*.image' => 'Tất cả các ảnh mô tả phải là hình ảnh.',
            'images_food.*.mimes' => 'Tất cả các ảnh mô tả phải có định dạng jpeg, png, jpg hoặc gif.',
            'images_food.*.max' => 'Tất cả các ảnh mô tả không được vượt quá 2MB.',
        ]);
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if ($user) {
            $userId = $user->id;
        } else {
            $userId = null;
        }
        if ($validator->passes()) {
            $food = new Food();
            $expiryDate = Carbon::parse($request->input('expiry_date'));
            $currentTime = Carbon::now();
            if ($expiryDate->greaterThan($currentTime)) {
                $food->user_id = $userId;
                $food->category_id = 1;
                $food->title = $request->input('title');
                $food->food_type = $request->input('food_type');
                $food->description = $request->input('description');
                $food->quantity = $request->input('quantity');
                $food->expiry_date = $request->input('expiry_date');
                $food->remaining_time_to_accept = $request->input('confirm_time');
                $food->province_id = $request->input('province_id');
                $food->district_id = $request->input('district_id');
                $food->ward_id = $request->input('ward_id');
                $food->location = $request->input('location');
                $food->contact_information = $request->input('contact_information');

                if ($request->hasFile('image_url')) {
                    $image = $request->file('image_url');
                    $file_name = Str::slug($request->input('title')) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/food_images'), $file_name);
                    $food->image_url = '../../uploads/food_images/' . $file_name;
                }

                $food->save();

                if ($request->hasFile('images_food')) {
                    $imageUrls = []; 

                    foreach ($request->file('images_food') as $image) {
                        $randomTitle = random_int(100000, 999999); 
                        $file_name = Str::slug($request->input('title')) . '_' . Str::slug($randomTitle) . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/food_images'), $file_name);
                        $imageUrls[] = '../../uploads/food_images/' . $file_name;
                    }
                    foreach ($imageUrls as $imageUrl) {
                        ImagesFood::create([
                            'food_id' => $food->id,
                            'image_url' => $imageUrl,
                        ]);
                    }
                }
                return response()->json(['message' => 'Food added successfully'], 200);
            } else {
                return response()->json(['error' => 'Ngày hết hạn phải sau thời gian hiện tại'], 200);
            }
        }

        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function update_donated_food(request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date',
            'confirm_time' => 'required|in:30,60,90,120,150,180',
            'province_id' => 'required|exists:province,id',
            'district_id' => 'required|exists:district,id',
            'ward_id' => 'exists:ward,id',
            'location' => 'required|string|max:255',
            'contact_information' => 'required|string|max:255',
            'image_url' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images_food' => 'array',
            'images_food.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'description.required' => 'Vui lòng nhập mô tả.',
            'description.max' => 'Vui lòng nhập mô tả ngắn hơn.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            'expiry_date.required' => 'Vui lòng nhập thời gian hết hạn.',
            'expiry_date.date' => 'Thời gian hết hạn không hợp lệ.',
            'confirm_time.required' => 'Vui lòng nhập thời gian chấp nhận.',
            'confirm_time.in' => 'Thời gian chấp nhận không hợp lệ.',
            'province_id.required' => 'Vui lòng chọn Tỉnh/Thành Phố hợp lệ.',
            'province_id.in' => 'Tỉnh/Thành Phố không hợp lệ.',
            'district_id.required' => 'Vui lòng chọn Quận/Huyện.',
            'district_id.in' => 'Quận/Huyện không hợp lệ.',
            'ward_id.in' => 'Phường/Xã không hợp lệ.',
            'location.required' => 'Vui lòng nhập địa chỉ cụ thể.',
            'location.max' => 'Địa chỉ cụ thể không được vượt quá 255 ký tự.',
            'contact_information.required' => 'Vui lòng nhập thông tin liên hệ.',
            'contact_information.max' => 'Thông tin liên hệ không được vượt quá 255 ký tự.',
            'image_url.image' => 'Ảnh thực phẩm phải là hình ảnh.',
            'image_url.mimes' => 'Ảnh thực phẩm phải có định dạng jpeg, png, jpg hoặc gif.',
            'image_url.max' => 'Ảnh thực phẩm không được vượt quá 2MB.',
            'images_food.*.image' => 'Tất cả các ảnh mô tả phải là hình ảnh.',
            'images_food.*.mimes' => 'Tất cả các ảnh mô tả phải có định dạng jpeg, png, jpg hoặc gif.',
            'images_food.*.max' => 'Tất cả các ảnh mô tả không được vượt quá 2MB.',
        ]);
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if ($user) {
            $userId = $user->id;
        } else {
            $userId = null;
        }
        if ($validator->passes()) {
            $food = Food::find($request->id);

            if (!$food) {
                return response()->json(['error' => 'Không tìm thấy thực phẩm để cập nhật'], 404);
            }

            $expiryDate = Carbon::parse($request->input('expiry_date'));
            $currentTime = Carbon::now();

            if ($expiryDate->greaterThan($currentTime)) {
                // Xóa ảnh cũ trước khi thêm ảnh mới
                if ($request->hasFile('image_url')) {
                    $oldImagePath = $food->image_url;
                    $parts = explode('/', $oldImagePath);
                    $filename = end($parts);
                    $oldImagePath = public_path('uploads/food_images/' . $filename);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                // Cập nhật đường dẫn của ảnh mới
                if ($request->hasFile('image_url')) {
                    $image = $request->file('image_url');
                    $file_name = Str::slug($food->title) . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/food_images'), $file_name);
                    $food->image_url = '../../uploads/food_images/' . $file_name;
                }

                $food->description = $request->input('description');
                $food->quantity = $request->input('quantity');
                $food->expiry_date = $request->input('expiry_date');
                $food->remaining_time_to_accept = $request->input('confirm_time');
                $food->province_id = $request->input('province_id');
                $food->location = $request->input('location');
                $food->contact_information = $request->input('contact_information');
                $food->save();

                // Xóa ảnh mô tả cũ trước khi thêm ảnh mới
                if ($request->hasFile('images_food')) {
                    ImagesFood::where('food_id', $request->id)->delete();
                }

                if ($request->hasFile('images_food')) {
                    // Lấy tất cả các hình ảnh mô tả cũ
                    $oldImages = ImagesFood::where('food_id', $food->id)->get();

                    // Xóa tất cả các hình ảnh mô tả cũ
                    // foreach ($oldImages as $oldImage) {
                    //     $oldImagePath = $oldImage->image_url;
                    //     $parts = explode('/', $oldImagePath);
                    //     $filename = end($parts);
                    //     $oldImagePath = public_path('uploads/food_images/' . $filename);
                    //     if (file_exists($oldImagePath)) {
                    //         unlink($oldImagePath);
                    //     }
                    //     $oldImage->delete(); // Xóa bản ghi trong cơ sở dữ liệu
                    // }
                    $imageUrls = []; // Initialize an array to store image URLs
                    foreach ($request->file('images_food') as $image) {
                        $randomTitle = random_int(100000, 999999); // Change 10 to the desired length of the random title
                        $file_name = Str::slug($food->title) . '_' . Str::slug($randomTitle) . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/food_images'), $file_name);
                        $imageUrls[] = '../../uploads/food_images/' . $file_name;
                    }
                    // Thêm ảnh mô tả mới vào cơ sở dữ liệu
                    foreach ($imageUrls as $imageUrl) {
                        ImagesFood::create([
                            'food_id' => $food->id,
                            'image_url' => $imageUrl,
                        ]);
                    }
                }
                return response()->json(['message' => 'Cập nhật thực phẩm thành công'], 200);
            } else {
                return response()->json(['error' => 'Ngày hết hạn phải sau thời gian hiện tại'], 200);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }

    public function update_donated_food_cancel($food_donate_id)
    {
        $food = Food::find($food_donate_id);
        if (!$food) {
            return response()->json(['errors' => 'Không tìm thấy thực phẩm.'], 404);
        }
        $food->status = 2; //trạng thái hủy tặng thực phẩm
        $food->save();
        // Trả về phản hồi JSON thành công
        return response()->json(['message' => 'Đã dừng tặng thực phẩm thành công.']);
    }
    public function update_donated_food_continues($food_donate_id)
    {
        $food = Food::find($food_donate_id);
        if (!$food) {
            return response()->json(['errors' => 'Không tìm thấy thực phẩm.'], 404);
        }
        $food->status = 0;
        $food->save();
        return response()->json(['message' => 'Tiếp tục tặng thực phẩm thành công.']);
    }

}