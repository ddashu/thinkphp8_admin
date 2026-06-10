<?php
namespace app\middleware;

use app\service\TokenService;

class Auth
{
    public function handle($request, \Closure $next)
    {
        $authorization = $request->header('Authorization', '');
        if (empty($authorization)) {
            return error('未提供认证令牌', 401);
        }

        $token = $authorization;
        if (strpos($authorization, 'Bearer ') === 0) {
            $token = substr($authorization, 7);
        }

        if (empty($token)) {
            return error('认证令牌为空', 401);
        }

        $tokenService = new TokenService();
        $decoded = $tokenService->verifyToken($token, 'access');

        if (!$decoded) {
            return error('认证令牌无效或已过期', 401);
        }

        // 将用户信息注入请求
        $request->userId   = $decoded->uid;
        $request->tokenExp = $decoded->exp;

        return $next($request);
    }
}
