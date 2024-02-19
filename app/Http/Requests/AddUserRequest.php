<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Đặt thành true nếu bạn muốn cho phép tất cả người dùng truy cập route này.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users|max:40', // Ví dụ về một quy tắc kiểm tra: bắt buộc, duy nhất và tối đa 255 ký tự
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8|max:30',
            'confirm_password' => 'required|same:password',
            // Thêm các quy tắc kiểm tra khác cho các trường dữ liệu khác ở đây
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'username.max' => 'Tên đăng nhập không được vượt quá 40 ký tự.',
            'email.required' => 'Địa chỉ email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email đã tồn tại.',
            'email.max' => 'Địa chỉ email không được vượt quá 100 ký tự.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 30 ký tự.',
            'confirm_password.required' => 'Xác nhận mật khẩu là bắt buộc.',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp với mật khẩu.',
            // Thêm các thông báo lỗi cho các quy tắc kiểm tra khác ở đây
        ];
    }
}
