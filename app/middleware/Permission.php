<?php
namespace app\middleware;

use app\service\MenuService;
use app\model\AdminUser;

class Permission
{
    public function handle($request, \Closure $next)
    {
        $userId = $request->userId ?? 0;
        if (!$userId) {
            return error('未认证', 401);
        }

        // 超级管理员跳过权限检查
        $user = AdminUser::with('roles')->find($userId);
        if (!$user) {
            return error('用户不存在', 401);
        }

        $isSuperAdmin = $user->roles->where('code', 'super_admin')->count() > 0;
        if ($isSuperAdmin) {
            return $next($request);
        }

        // 获取当前路由的权限标识
        $controller = $request->controller();
        $action     = $request->action();

        // 不需要权限验证的操作
        $noPermissionActions = ['index', 'list', 'read', 'info', 'tree', 'stats', 'trend', 'api_stats', 'model_distribution'];
        if (in_array($action, $noPermissionActions)) {
            return $next($request);
        }

        // 获取用户权限
        $menuService = new MenuService();
        $permissions = $menuService->getPermissionsByUserId($userId);

        // 构建权限标识映射
        $permissionMap = $this->buildPermissionMap();
        $key = strtolower($controller) . ':' . $action;

        if (isset($permissionMap[$key])) {
            $requiredPermission = $permissionMap[$key];
            if (!in_array($requiredPermission, $permissions)) {
                return error('没有操作权限', 403);
            }
        }

        return $next($request);
    }

    /**
     * 构建控制器动作到权限标识的映射
     */
    protected function buildPermissionMap(): array
    {
        return [
            'user:add'          => 'system:user:add',
            'user:edit'         => 'system:user:edit',
            'user:delete'       => 'system:user:delete',
            'role:add'          => 'system:role:add',
            'role:edit'         => 'system:role:edit',
            'role:delete'       => 'system:role:delete',
            'menu:add'          => 'system:menu:add',
            'menu:edit'         => 'system:menu:edit',
            'menu:delete'       => 'system:menu:delete',
            'apikey:add'        => 'ai:apikey:add',
            'apikey:edit'       => 'ai:apikey:edit',
            'apikey:delete'     => 'ai:apikey:delete',
            'model:add'         => 'ai:model:add',
            'model:edit'        => 'ai:model:edit',
            'model:delete'      => 'ai:model:delete',
        ];
    }
}
