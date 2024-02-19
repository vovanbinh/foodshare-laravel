<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Jobs\SendEmailJob;
use App\Jobs\SendForgotPasswordEmail;
use App\Models\food_transactions;
use App\Models\Notification;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function show_forgot_password()
    {
        return View('user/forgotPassword');
    }
    public function show_profice()
    {
        $username = session('username');
        $user = Users::where('username', $username)->first();
        return View('user/UserProfice', compact('user'));
    }
    public function show_confirm_code_forgot()
    {
        return View('user/confirm_code_forgot');
    }
    public function show_new_password()
    {
        return View('new_password');
    }
    public function newavatar(request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'image.image' => 'Ảnh thực phẩm phải là hình ảnh.',
            'image.mimes' => 'Ảnh thực phẩm phải có định dạng jpeg, png, jpg hoặc gif.',
            'image.max' => 'Ảnh thực phẩm không được vượt quá 2MB.',
        ]);
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if ($validator->passes()) {
            // Xóa ảnh cũ trước khi thêm ảnh mới
            if ($request->hasFile('image')) {
                if ($user->image != null) {
                    $oldImagePath = $user->image;
                    $parts = explode('/', $oldImagePath);
                    $filename = end($parts);
                    $oldImagePath = public_path('uploads/user/' . $filename);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                // Cập nhật đường dẫn của ảnh mới
                $image = $request->file('image');
                $file_name = Str::slug($user->username) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/user'), $file_name);
                $user->image = '../../uploads/user/' . $file_name;
                $user->save();
                return response()->json(['message' => 'success'], 200);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function updateprofice(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|min:4',
            'phoneNumber' => 'required|regex:/^\d{10}$/u',
            'address' => 'required|max:200|min:10',
            'date' => 'required|date',
        ], [
            'name.string' => 'Họ và Tên phải là một chuỗi.',
            'name.max' => 'Họ và Tên không được vượt quá 50 ký tự.',
            'name.min' => 'Họ và Tên phải có ít nhất 4 ký tự.',
            'name.required' => 'Vui lòng nhập Họ và tên.',
            'phoneNumber.integer' => 'Số điện thoại phải là một số nguyên.',
            'phoneNumber.regex' => 'Số điện thoại không hợp lệ. Hãy nhập 10 chữ số.',
            'phoneNumber.required' => 'Vui lòng nhập số điện thoại.',
            'address.max' => 'Địa chỉ không được vượt quá 200 ký tự.',
            'address.min' => 'Địa chỉ phải có ít nhất 10 ký tự.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'date.date' => 'Ngày không hợp lệ.',
            'date.required' => 'Vui lòng nhập ngày sinh.',
        ]);
        $expiryDate = Carbon::parse($request->input('date'));
        $currentTime = Carbon::now();

        if ($validator->passes()) {
            if ($expiryDate < $currentTime) {
                $username = session('username');
                $user = Users::where('username', $username)->first();
                $user->full_name = $request->input('name');
                $user->phone_number = $request->input('phoneNumber');
                $user->address = $request->input('address');
                $user->birthdate = $request->input('date');
                $user->save();
                return response()->json(['message' => 'Cập nhật thành công'], 200);
            } else {
                return response()->json(['error' => 'Ngày sinh phải trước ngày hiện tại'], 200);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
        ]);

        if ($validator->passes()) {
            $email = $request->input('email');
            Session::put('email', $email);
            $user = Users::where('email', $email)->first();
            if ($user) {
                $forgot_password_code = random_int(100000, 999999);
                $user->forgot_password_code = $forgot_password_code;
                $user->save();
                $username = $user->username;
                dispatch(new SendForgotPasswordEmail($email, $username, $forgot_password_code));
                return response()->json(['message' => 'Đăng ký thành công'], 200);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function confirm_code_forgot(request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:users,forgot_password_code',
        ], [
            'code.required' => 'Vui lòng nhập mã ',
            'code.exists' => 'Mã không đúng',
        ]);

        if ($validator->passes()) {
            $code = $request->code;
            $user = User::where('forgot_password_code', $code)->first();
            $user->forgot_password_code = null;
            $user->save();
            return response()->json(['message' => 'Xác thực thành công'], 200);
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function new_password_forgot(request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:30',
            'rpassword' => 'required|same:password',
        ], [
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 30 ký tự.',
            'rpassword.required' => 'Xác nhận mật khẩu là bắt buộc.',
            'rpassword.same' => 'Xác nhận mật khẩu không khớp với mật khẩu.',
        ]);
        if ($validator->passes()) {
            $email = session('email');
            $password = $request->input('password');
            $hashedPassword = Hash::make($password);
            $user = Users::where('email', $email)->first();
            if ($user) {
                $user->password = $hashedPassword;
            }
            if ($user->save()) {
                $username = $request->input('username');
                return response()->json(['message' => 'Thay đổi mật khẩu thành công'], 200);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function update_password(request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required',
            'password' => 'required|min:8|max:30',
            'rpassword' => 'required|same:password',
        ], [
            'oldpassword.required' => 'Mật khẩu cũ là bắt buộc.',
            'password.required' => 'Mật khẩu mới là bắt buộc.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 30 ký tự.',
            'rpassword.required' => 'Xác nhận mật khẩu là bắt buộc.',
            'rpassword.same' => 'Xác nhận mật khẩu không khớp với mật khẩu.',
        ]);
        if ($validator->passes()) {
            $username = session('username');
            $oldpassword = $request->input('oldpassword');
            $user = Users::where('username', $username)->first();
            if ($user && Hash::check($oldpassword, $user->password)) {
                $password = $request->input('password');
                if (Hash::check($password, $user->password)) {
                    return response()->json(['error' => 'Mật Khẩu Mới Không Được Giống Mật Khẩu Cũ'], 200);
                }
                $hashedPassword = Hash::make($password);
                if ($user) {
                    $user->password = $hashedPassword;
                }
                if ($user->save()) {
                    return response()->json(['message' => 'Thay đổi mật khẩu thành công'], 200);
                }
            } else {
                return response()->json(['errors' => ['Mật Khẩu Cũ Không Đúng']], 200);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function show_detail_notification($notification_id)
    {
        $notification = Notification::where('id', $notification_id)->first();
        $transaction = food_transactions::where('id', $notification->transaction_id)->first();
        if ($notification) {
            $notification->is_read = 1;
            $notification->save();
        }
        $username = session('username');
        $receiver = $transaction->receiver;

        if ($transaction->status == 2) {
            $home = "home";
            $donor = "null";
            $donor = "null";
        } else {
            if ($receiver->username != $username) {
                $donor = "donor";
            } else {
                $donor = "receiver";
            }
            $home = "error";
        }
        return view('user/detail_notification', compact('notification', 'transaction', 'donor', 'home'));
    }


    public function getNotificationCount()
    {
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if ($user) {
            $notificationCount = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc') // Sắp xếp theo thời gian tạo (tuỳ chọn)
                ->get();

            return response()->json([
                'notificationCount' => $notificationCount,
                'notifications' => $notifications
            ]);
        }
        return response()->json(['notificationCount' => 0]);
    }
    public function getInfomation()
    {
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if ($user) {
            return response()->json(['user' => $user]); // Trả về thông tin người dùng
        }
        return response()->json(['user' => null]); // Trường hợp không tìm thấy người dùng

    }


    public function logout()
    {
        session()->forget('username');
        return redirect()->route('index');
    }
    public function showverification()
    {
        return View('user/verification');
    }
    public function verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verifi_code' => 'required|exists:users,verification_code',
        ], [
            'verifi_code.required' => 'Vui lòng nhập mã xác thực.',
            'verifi_code.exists' => 'Mã xác thực không đúng',
        ]);

        if ($validator->passes()) {
            $email = session('email');
            $user = Users::where('email', $email)->first();
            if (!$user) {
                return response()->json(['errors' => ['User not found']], 400);
            }
            $user->is_verified = 1;
            if ($user->save()) {
                Session::put('success', 'Xác thực thành công');
                return response()->json(['message' => 'Xác thực thành công'], 200);
            } else {
                return response()->json(['errors' => ['Failed to update user']], 500);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
    public function login(Request $request)
    {
        // Sử dụng Laravel Validator để kiểm tra và xác thực dữ liệu đầu vào 
        // để bảo vệ ứng dụng khỏi các cuộc tấn công thông qua dữ liệu không hợp lệ.
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);
        if ($validator->passes()) {
            //tiến hành kiểm tra thông tin đăng nhập
            $username = $request->input('username');
            $password = $request->input('password');
            $user = Users::where('username', $username)->first();
            if ($user && Hash::check($password, $user->password)) {
                if ($user->is_verified == 0) {
                    //trường hợp chưa xác thực
                    $verificationCode = random_int(100000, 999999);
                    $email = $user->email;
                    Session::put('email', $email);
                    $user->verification_code = $verificationCode;
                    if ($user->save()) {
                        dispatch(new SendEmailJob($email, $username, $verificationCode));
                        return response()->json(['notverification' => ['Chưa Xác Thực']], 200);
                    }
                }
                Session::put('username', $username);
                if ($user->role == 1) {
                    //trường hợp là admin
                    return response()->json(['admin' => 'Đăng nhập thành công'], 200);
                }
                if ($id_product = session('id_product')) {
                    //trường hợp nhấn vào nhận thực phẩm khi chưa đăng nhập
                    session()->forget('id_product');
                    return response()->json(['id_product' => $id_product], 200);
                }
                return response()->json(['message' => 'Đăng nhập thành công'], 200);
            } else {
                //trường hợp tên đăng nhập hoặc mật khẩu không đúng
                return response()->json(['errors' => ['Tên đăng nhập hoặc mật khẩu không đúng']], 200);
            }
        }
        //trường hợp lỗi validator
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:40|regex:/^[A-Za-z0-9]+$/',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8|max:30',
            'confirm_password' => 'required|same:password',
        ], [
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'username.max' => 'Tên đăng nhập không được vượt quá 40 ký tự.',
            'username.regex' => 'Tên đăng nhập không được chứa dấu hoặc khoảng trắng.',
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email đã tồn tại.',
            'email.max' => 'Địa chỉ email không được vượt quá 100 ký tự.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 30 ký tự.',
            'confirm_password.required' => 'Xác nhận mật khẩu là bắt buộc.',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu.',
        ]);

        if ($validator->passes()) {
            $username = $request->input('username');
            $email = $request->input('email');
            $password = $request->input('password');
            $hashedPassword = Hash::make($password);
            $verificationCode = random_int(100000, 999999);
            $user = new Users([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'verification_code' => $verificationCode,
            ]);
            if ($user->save()) {
                Session::put('email', $email);
                Session::put('success', 'Đăng ký thành công');
                dispatch(new SendEmailJob($email, $username, $verificationCode));
                return response()->json(['message' => 'Đăng ký thành công'], 200);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 200);
    }
}