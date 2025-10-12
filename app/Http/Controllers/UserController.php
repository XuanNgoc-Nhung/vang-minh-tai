<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;

class UserController extends Controller
{
    public function login()
    {
        Log::info('User accessing login page');
        return view('user.login');
    }
    
    public function register()
    {
        Log::info('User accessing register page');
        return view('user.register');
    }
    
    public function postRegister(Request $request)
    {
        Log::info('Registration attempt started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $request->except(['password', 'password_confirmation'])
        ]);
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(0|\+84)[0-9]{9,10}$/', 'unique:users,phone'],
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|accepted'
        ], [
            'name.required' => 'Họ và tên là bắt buộc',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'terms.required' => 'Bạn phải đồng ý với điều khoản sử dụng',
            'terms.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng'
        ]);

        if ($validator->fails()) {
            Log::warning('Registration validation failed', [
                'errors' => $validator->errors()->toArray(),
                'ip' => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Creating new user account', [
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name
            ]);
            
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 0, // 0 = user, 1 = admin
                'status' => 1 // 1 = active, 0 = inactive
            ]);

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Create profile for user
            Profile::create([
                'user_id' => $user->id
            ]);

            Log::info('User profile created successfully', [
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công!',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            Log::error('Registration failed with exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'email' => $request->email ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.'
            ], 500);
        }
    }

    public function dashboard()
    {
        return view('user.home');
    }
    
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Log::info('User logout', [
            'user_id' => $user ? $user->id : null,
            'ip' => $request->ip()
        ]);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Đăng xuất thành công!');
    }
    public function postLogin(Request $request)
    {
        Log::info('Login attempt started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $request->except(['password'])
        ]);
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string|min:6'
        ], [
            'login.required' => 'Email hoặc số điện thoại là bắt buộc',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự'
        ]);

        if ($validator->fails()) {
            Log::warning('Login validation failed', [
                'errors' => $validator->errors()->toArray(),
                'ip' => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $login = $request->input('login');
            $password = $request->input('password');
            
            // Determine if login is email or phone
            $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
            $field = $isEmail ? 'email' : 'phone';
            
            Log::info('Attempting authentication', [
                'field' => $field,
                'login' => $login,
                'ip' => $request->ip()
            ]);
            
            // Find user by email or phone
            $user = User::where($field, $login)->first();
            
            if (!$user) {
                Log::warning('Login failed - user not found', [
                    'field' => $field,
                    'login' => $login,
                    'ip' => $request->ip()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Thông tin đăng nhập không chính xác'
                ], 401);
            }
            
            // Check if user is active
            if ($user->status != 1) {
                Log::warning('Login failed - user inactive', [
                    'user_id' => $user->id,
                    'status' => $user->status,
                    'ip' => $request->ip()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                ], 403);
            }
            
            // Verify password
            if (!Hash::check($password, $user->password)) {
                Log::warning('Login failed - incorrect password', [
                    'user_id' => $user->id,
                    'ip' => $request->ip()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Thông tin đăng nhập không chính xác'
                ], 401);
            }
            
            // Login successful
            Auth::login($user, $request->has('remember'));
            
            Log::info('Login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'ip' => $request->ip()
            ]);
            
            // Determine redirect URL based on user role
            $redirectUrl = $user->role == 1 ? '/admin' : '/dashboard';
            
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'redirect' => $redirectUrl,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Login failed with exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'login' => $request->input('login') ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.'
            ], 500);
        }
    }
}
