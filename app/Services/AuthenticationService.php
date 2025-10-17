<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticationService
{
    /**
     * Authenticate user with identifier (username, email, or phone)
     *
     * @param string $identifier
     * @param string $password
     * @return array
     */
    public function authenticate(string $identifier, string $password): array
    {
        // Rate limiting: max 5 attempts per minute per IP
        $key = 'login_attempts:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            Log::warning('Login rate limit exceeded', [
                'ip' => request()->ip(),
                'retry_after' => $seconds
            ]);

            return [
                'success' => false,
                'message' => "Quá nhiều lần thử đăng nhập. Vui lòng thử lại sau {$seconds} giây."
            ];
        }

        // Find user by identifier with caching
        $user = $this->findUserByIdentifier($identifier);

        if (!$user) {
            RateLimiter::hit($key, 60); // Block for 1 minute
            Log::warning('Login failed: User not found', ['identifier' => $identifier]);

            return [
                'success' => false,
                'message' => 'Tài khoản không tồn tại trong hệ thống!'
            ];
        }

        // Verify password (only hashed passwords allowed)
        if (!Hash::check($password, $user->password)) {
            RateLimiter::hit($key, 60);

            Log::warning('Login failed: Invalid password', [
                'user_id' => $user->id,
                'identifier' => $identifier
            ]);

            return [
                'success' => false,
                'message' => 'Mật khẩu không chính xác!'
            ];
        }

        // Clear rate limiter on successful login
        RateLimiter::clear($key);

        // Login user
        Auth::login($user);

        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'ip' => request()->ip()
        ]);

        return [
            'success' => true,
            'user' => $user,
            'redirect_route' => $this->getRedirectRoute($user->role)
        ];
    }

    /**
     * Find user by username, email, or phone with caching
     *
     * @param string $identifier
     * @return User|null
     */
    private function findUserByIdentifier(string $identifier): ?User
    {
        $cacheKey = 'user_lookup:' . md5($identifier);

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($identifier) {
            return User::where('username', $identifier)
                ->orWhere('email', $identifier)
                ->orWhere('phone', $identifier)
                ->first();
        });
    }

    /**
     * Get redirect route based on user role
     *
     * @param string $role
     * @return string
     */
    private function getRedirectRoute(string $role): string
    {
        return match (strtolower($role)) {
            'admin' => 'admin.dashboard',
            'staff' => 'staff.dashboard',
            'bus_owner' => 'bus-owner.dashboard',
            default => 'home'
        };
    }

    /**
     * Logout user and clean up sessions
     *
     * @return void
     */
    public function logout(): void
    {
        $userId = Auth::id();

        Auth::logout();

        // Clear any cached data for this user
        $this->clearUserCache($userId);

        Log::info('User logged out', ['user_id' => $userId]);
    }

    /**
     * Clear cached data for user
     *
     * @param int|null $userId
     * @return void
     */
    private function clearUserCache(?int $userId): void
    {
        if (!$userId) return;

        // Clear user lookup cache
        Cache::forget('user:' . $userId);

        // Clear any other user-related cache keys
        $patterns = ['user_lookup:*'];
        foreach ($patterns as $pattern) {
            // Note: Laravel doesn't have a built-in way to clear by pattern
            // This would need Redis or a more sophisticated caching strategy
        }
    }
}
