<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    /**
     * Register a new user
     *
     * @param array $userData
     * @return array
     * @throws ValidationException
     */
    public function register(array $userData): array
    {
        // Validate registration data
        $validated = $this->validateRegistrationData($userData);

        try {
            DB::beginTransaction();

            // Create user
            $user = $this->createUser($validated);

            // Clear any cached lookups that might conflict
            $this->clearUserLookupCache($validated);

            DB::commit();

            // Log successful registration
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'ip' => request()->ip()
            ]);

            // Auto-login after registration
            Auth::login($user);

            return [
                'success' => true,
                'user' => $user,
                'message' => 'Đăng ký thành công! Chào mừng bạn đến với hệ thống.'
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration error: ' . $e->getMessage(), [
                'data' => Arr::except($userData, ['password', 'password_confirmation']),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip()
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại sau.'
            ];
        }
    }

    /**
     * Validate registration data
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    private function validateRegistrationData(array $data): array
    {
        $rules = [
            'username' => 'required|string|max:50|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'fullname' => 'required|string|max:100|min:3',
            'email' => 'required|string|email|max:100|unique:users',
            'phone' => 'required|string|max:15|unique:users|regex:/^[0-9]{10,11}$/',
            'password' => 'required|string|min:8|confirmed'
        ];

        $messages = [
            // Username messages
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.max' => 'Tên đăng nhập không được quá 50 ký tự',
            'username.unique' => 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới',

            // Fullname messages
            'fullname.required' => 'Vui lòng nhập họ và tên',
            'fullname.max' => 'Họ và tên không được quá 100 ký tự',
            'fullname.min' => 'Họ và tên phải có ít nhất 3 ký tự',

            // Email messages
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không đúng định dạng',
            'email.max' => 'Email không được quá 100 ký tự',
            'email.unique' => 'Email đã được sử dụng, vui lòng dùng email khác',

            // Phone messages
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.max' => 'Số điện thoại không được quá 15 ký tự',
            'phone.unique' => 'Số điện thoại đã được đăng ký, vui lòng dùng số khác',
            'phone.regex' => 'Số điện thoại phải là 10-11 chữ số',

            // Password messages
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp'
        ];

        return validator($data, $rules, $messages)->validate();
    }

    /**
     * Create a new user
     *
     * @param array $validatedData
     * @return User
     */
    private function createUser(array $validatedData): User
    {
        return User::create([
            'username' => $validatedData['username'],
            'fullname' => $validatedData['fullname'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'user'
        ]);
    }

    /**
     * Clear user lookup cache for provided identifiers
     *
     * @param array $data
     * @return void
     */
    private function clearUserLookupCache(array $data): void
    {
        $identifiers = [
            $data['username'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null
        ];

        foreach ($identifiers as $identifier) {
            if ($identifier) {
                $cacheKey = 'user_lookup:' . md5($identifier);
                Cache::forget($cacheKey);
            }
        }
    }

    /**
     * Check if username, email, or phone already exists
     *
     * @param string $field
     * @param string $value
     * @return bool
     */
    public function isFieldTaken(string $field, string $value): bool
    {
        $cacheKey = "field_check:{$field}:" . md5($value);

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($field, $value) {
            return User::where($field, $value)->exists();
        });
    }
}
