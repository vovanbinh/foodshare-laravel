<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Exception;
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
use Illuminate\Support\Facades\DB; // Thêm dòng này
use Tymon\JWTAuth\Contracts\JWTSubject;
class AuthController extends Controller 
{
    public function register(Request $request)
    {
        try {
            // Kiểm tra các trường bắt buộc
            $fullName = $request->input('fullName');
            $email = $request->input('email');
            $password = $request->input('password');

            if (empty($fullName) || empty($email) || empty($password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng điền đầy đủ thông tin.',
                ], 400);
            }

            // Kiểm tra email đã tồn tại
            $existingUser = DB::table("users")->where('email', $email)->first();
            if ($existingUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email đã tồn tại trong hệ thống.',
                ], 400);
            }
            $existingusername = DB::table("users")->where('username', $fullName)->first();
            if ($existingusername) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Full name đã tồn tại trong hệ thống.',
                ], 400);
            }
            // Hash password trước khi lưu vào cơ sở dữ liệu
            $hashedPassword = bcrypt($password);

            // Lưu thông tin người dùng
            $data = DB::table("users")->insert([
                'username' => $fullName,
                'email' => $email,
                'password' => $hashedPassword,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký thành công.',
            ], 200);
        } catch (Exception $e) {
            // Xử lý ngoại lệ nếu có
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);

        if ($validator->passes()) {
            $username = $request->input('username');
            $password = $request->input('password');
            $user = User::where('username', $username)->first();
            if ($user && Hash::check($password, $user->password)) {
                return response()->json(['success' => ['Đăng nhập thành công']], 200);
            } else {
                return response()->json(['errors' => ['Tên đăng nhập hoặc mật khẩu không đúng']], 200);
            }
        } else {
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors], 200);
        }
    }

}
