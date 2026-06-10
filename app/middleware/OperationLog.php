<?php
namespace app\middleware;

use app\model\AdminLog;
use app\model\AdminUser;

class OperationLog
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        // 只记录写操作
        $method = $request->method();
        if (!in_array($method, ['POST', 'PUT', 'DELETE'])) {
            return $response;
        }

        $userId = $request->userId ?? 0;
        if (!$userId) {
            return $response;
        }

        try {
            $user = AdminUser::find($userId);
            $username = $user ? $user->username : '';

            $controller = $request->controller();
            $action     = $request->action();

            $params = $request->param();
            // 移除敏感字段
            unset($params['password'], $params['old_password'], $params['new_password']);

            AdminLog::create([
                'user_id'    => $userId,
                'username'   => $username,
                'module'     => $controller,
                'action'     => $action,
                'method'     => $method,
                'url'        => $request->url(),
                'params'     => $params,
                'ip'         => $request->ip(),
                'user_agent' => $request->header('user-agent', ''),
            ]);
        } catch (\Exception $e) {
            // 日志记录失败不影响业务
        }

        return $response;
    }
}
