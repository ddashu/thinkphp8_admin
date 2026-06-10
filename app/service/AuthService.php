<?php
namespace app\service;

use app\model\AdminUser;
use app\model\AdminLoginLog;

class AuthService
{
    protected $tokenService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    /**
     * 登录
     */
    public function login(string $username, string $password, string $ip = '', string $userAgent = ''): array
    {
        $user = AdminUser::where('username', $username)->find();
        if (!$user) {
            $this->recordLoginLog(0, $username, $ip, $userAgent, 0, '用户不存在');
            throw new \Exception('用户名或密码错误');
        }

        if ($user->status !== 1) {
            $this->recordLoginLog($user->id, $username, $ip, $userAgent, 0, '账号已禁用');
            throw new \Exception('账号已禁用');
        }

        if (!password_verify($password, $user->password)) {
            $this->recordLoginLog($user->id, $username, $ip, $userAgent, 0, '密码错误');
            throw new \Exception('用户名或密码错误');
        }

        // 更新登录信息
        $user->last_login_ip   = $ip;
        $user->last_login_time = date('Y-m-d H:i:s');
        $user->save();

        // 生成令牌
        $accessToken  = $this->tokenService->generateAccessToken($user->id);
        $refreshToken = $this->tokenService->generateRefreshToken($user->id);

        $this->recordLoginLog($user->id, $username, $ip, $userAgent, 1, '登录成功');

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => env('JWT_ACCESS_TTL', 7200),
            'user_info'     => $this->getUserInfo($user->id),
        ];
    }

    /**
     * 登出
     */
    public function logout(int $userId): bool
    {
        return true;
    }

    /**
     * 刷新令牌
     */
    public function refresh(string $refreshToken): array
    {
        $decoded = $this->tokenService->verifyToken($refreshToken, 'refresh');
        if (!$decoded) {
            throw new \Exception('刷新令牌无效或已过期');
        }

        $user = AdminUser::find($decoded->uid);
        if (!$user || $user->status !== 1) {
            throw new \Exception('用户不存在或已禁用');
        }

        $accessToken  = $this->tokenService->generateAccessToken($user->id);
        $newRefreshToken = $this->tokenService->generateRefreshToken($user->id);

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $newRefreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => env('JWT_ACCESS_TTL', 7200),
        ];
    }

    /**
     * 获取用户信息（含角色和权限）
     */
    public function getUserInfo(int $userId): array
    {
        $user = AdminUser::with('roles')->find($userId);
        if (!$user) {
            throw new \Exception('用户不存在');
        }

        $menuService = new MenuService();
        $permissions = $menuService->getPermissionsByUserId($userId);
        $roles = $user->roles->map(function ($role) {
            return [
                'id'   => $role->id,
                'name' => $role->name,
                'code' => $role->code,
            ];
        })->toArray();

        return [
            'id'            => $user->id,
            'username'      => $user->username,
            'nickname'      => $user->nickname,
            'avatar'        => $user->avatar,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'status'        => $user->status,
            'last_login_ip' => $user->last_login_ip,
            'last_login_time' => $user->last_login_time,
            'roles'         => $roles,
            'permissions'   => $permissions,
        ];
    }

    /**
     * 记录登录日志
     */
    protected function recordLoginLog(int $userId, string $username, string $ip, string $userAgent, int $status, string $message): void
    {
        AdminLoginLog::create([
            'user_id'  => $userId,
            'username' => $username,
            'ip'       => $ip,
            'browser'  => $this->parseBrowser($userAgent),
            'os'       => $this->parseOs($userAgent),
            'status'   => $status,
            'message'  => $message,
        ]);
    }

    protected function parseBrowser(string $userAgent): string
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        return 'Unknown';
    }

    protected function parseOs(string $userAgent): string
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'Mac';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) return 'iOS';
        return 'Unknown';
    }
}
