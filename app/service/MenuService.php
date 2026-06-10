<?php
namespace app\service;

use app\model\AdminMenu;
use app\model\AdminUser;
use app\model\AdminRoleMenu;
use app\model\AdminUserRole;

class MenuService
{
    /**
     * 获取菜单树
     */
    public function getTree(int $status = -1): array
    {
        $query = AdminMenu::order('sort', 'asc')->order('id', 'asc');
        if ($status >= 0) {
            $query->where('status', $status);
        }
        $list = $query->select()->toArray();
        return $this->buildTree($list, 0);
    }

    /**
     * 构建树形结构
     */
    protected function buildTree(array $list, int $parentId = 0): array
    {
        $tree = [];
        foreach ($list as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($list, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * 获取用户菜单树
     */
    public function getUserMenuTree(int $userId): array
    {
        $user = AdminUser::with('roles.menus')->find($userId);
        if (!$user) {
            return [];
        }

        // 超级管理员获取所有菜单
        $isSuperAdmin = $user->roles->where('code', 'super_admin')->count() > 0;
        if ($isSuperAdmin) {
            return $this->getTree(1);
        }

        // 获取用户所有角色的菜单ID
        $menuIds = [];
        foreach ($user->roles as $role) {
            foreach ($role->menus as $menu) {
                $menuIds[] = $menu->id;
            }
        }
        $menuIds = array_unique($menuIds);
        if (empty($menuIds)) {
            return [];
        }

        $list = AdminMenu::where('status', 1)
            ->whereIn('id', $menuIds)
            ->order('sort', 'asc')
            ->order('id', 'asc')
            ->select()
            ->toArray();

        return $this->buildTree($list, 0);
    }

    /**
     * 获取用户权限标识列表
     */
    public function getPermissionsByUserId(int $userId): array
    {
        $user = AdminUser::with('roles.menus')->find($userId);
        if (!$user) {
            return [];
        }

        // 超级管理员拥有所有权限
        $isSuperAdmin = $user->roles->where('code', 'super_admin')->count() > 0;
        if ($isSuperAdmin) {
            $menus = AdminMenu::where('status', 1)->where('permission', '<>', '')->select();
            return $menus->column('permission');
        }

        $permissions = [];
        foreach ($user->roles as $role) {
            if ($role->status !== 1) continue;
            foreach ($role->menus as $menu) {
                if ($menu->status === 1 && !empty($menu->permission)) {
                    $permissions[] = $menu->permission;
                }
            }
        }
        return array_unique($permissions);
    }

    /**
     * 获取菜单列表（平铺）
     */
    public function getList(array $params = []): array
    {
        $query = AdminMenu::order('sort', 'asc')->order('id', 'asc');

        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $query->where('status', $params['status']);
        }
        if (!empty($params['type'])) {
            $query->where('type', $params['type']);
        }

        return $query->select()->toArray();
    }

    /**
     * 获取菜单详情
     */
    public function getInfo(int $id): ?AdminMenu
    {
        return AdminMenu::find($id);
    }

    /**
     * 创建菜单
     */
    public function create(array $data): AdminMenu
    {
        return AdminMenu::create($data);
    }

    /**
     * 更新菜单
     */
    public function update(int $id, array $data): AdminMenu
    {
        $menu = AdminMenu::find($id);
        if (!$menu) {
            throw new \Exception('菜单不存在');
        }
        $menu->save($data);
        return $menu;
    }

    /**
     * 删除菜单
     */
    public function delete(int $id): bool
    {
        $menu = AdminMenu::find($id);
        if (!$menu) {
            throw new \Exception('菜单不存在');
        }
        // 检查是否有子菜单
        $children = AdminMenu::where('parent_id', $id)->count();
        if ($children > 0) {
            throw new \Exception('存在子菜单，不能删除');
        }
        // 删除角色关联
        AdminRoleMenu::where('menu_id', $id)->delete();
        return $menu->delete();
    }
}
