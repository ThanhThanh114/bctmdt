<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    /**
     * Update user profile
     *
     * @param array $profileData
     * @return array
     */
    public function updateProfile(array $profileData): array
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Người dùng không được xác thực.'
            ];
        }

        try {
            $validated = $this->validateProfileData($profileData, $user->id);

            DB::beginTransaction();

            // Update user attributes
            foreach ($validated as $key => $value) {
                $user->$key = $value;
            }
            $user->save();

            // Clear user cache
            $this->clearUserCache($user->id);

            DB::commit();

            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($validated)
            ]);

            return [
                'success' => true,
                'message' => 'Thông tin tài khoản đã được cập nhật thành công!',
                'user' => $user
            ];

        } catch (ValidationException $e) {
            return [
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors()
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin. Vui lòng thử lại sau.'
            ];
        }
    }

    /**
     * Change user password
     *
     * @param array $passwordData
     * @return array
     */
    public function changePassword(array $passwordData): array
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Người dùng không được xác thực.'
            ];
        }

        try {
            $validated = $this->validatePasswordData($passwordData);

            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return [
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không đúng!'
                ];
            }

            DB::beginTransaction();

            $user->password = Hash::make($validated['password']);
            $user->save();

            // Clear user cache
            $this->clearUserCache($user->id);

            DB::commit();

            Log::info('Password changed successfully', ['user_id' => $user->id]);

            return [
                'success' => true,
                'message' => 'Mật khẩu đã được cập nhật thành công!'
            ];

        } catch (ValidationException $e) {
            return [
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors()
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Password change error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại sau.'
            ];
        }
    }

    /**
     * Get user profile data with caching
     *
     * @param int $userId
     * @return User
     */
    public function getUserProfile(int $userId): User
    {
        $cacheKey = 'user_profile:' . $userId;

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($userId) {
            return User::findOrFail($userId);
        });
    }

    /**
     * Validate profile update data
     *
     * @param array $data
     * @param int $userId
     * @return array
     * @throws ValidationException
     */
    private function validateProfileData(array $data, int $userId): array
    {
        $rules = [
            'fullname' => 'required|string|max:100',
            'phone' => 'required|string|max:15|unique:users,phone,' . $userId,
            'email' => 'required|string|email|max:100|unique:users,email,' . $userId,
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Nam,Nữ,Khác'
        ];

        $messages = [
            'fullname.required' => 'Vui lòng nhập họ và tên',
            'fullname.max' => 'Họ và tên không được quá 100 ký tự',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.max' => 'Số điện thoại không được quá 15 ký tự',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không đúng định dạng',
            'email.max' => 'Email không được quá 100 ký tự',
            'email.unique' => 'Email đã được sử dụng',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
            'date_of_birth.date' => 'Ngày sinh không đúng định dạng',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hiện tại',
            'gender.in' => 'Giới tính không hợp lệ'
        ];

        return validator($data, $rules, $messages)->validate();
    }

    /**
     * Validate password change data
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    private function validatePasswordData(array $data): array
    {
        $rules = [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed|different:current_password'
        ];

        $messages = [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại'
        ];

        return validator($data, $rules, $messages)->validate();
    }

    /**
     * Clear user-related cache
     *
     * @param int $userId
     * @return void
     */
    private function clearUserCache(int $userId): void
    {
        $cacheKeys = [
            'user_profile:' . $userId,
            'user:' . $userId
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear user lookup cache for identifiers
        $user = User::find($userId);
        if ($user) {
            $identifiers = [$user->username, $user->email, $user->phone];
            foreach ($identifiers as $identifier) {
                if ($identifier) {
                    $lookupKey = 'user_lookup:' . md5($identifier);
                    Cache::forget($lookupKey);
                }
            }
        }
    }
}
