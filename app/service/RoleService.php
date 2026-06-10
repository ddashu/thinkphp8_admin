<?php
namespace app\service;

use app\model\AdminRole;
use app\model\AdminRoleMenu;

class RoleService
{
    /**
     * 获取角色列表
     */
    public function getList(array $params = []): array
    {
        $query = AdminRole::with('menus');

        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $query->where('status', $params['status']);
        }

        $page     = intval($params['page'] ?? 1);
        $pageSize = intval($params['page_size'] ?? 15);

        $list = $query->order('sort', 'asc')->order('id', 'asc')->paginate([
            'page'      => $page,
            'list_rows' => $pageSize,
        ]);

        return [
            'list'      => $list->items(),
            'total'     => $list->total(),
            'page'      => $page,
            'page_size' => $pageSize,
        ];
    }

    /**
     * 获取所有角色（不分页）
     */
    public function getAll(): array
    {
        return AdminRole::where('status', 1)->order('sort', 'asc')->select()->toArray();
    }

    /**
     * 获取角色详情
     */
    public function getInfo(int $id): ?AdminRole
    {
        return AdminRole::with('menus')->find($id);
    }

    /**
     * 创建角色
     */
    public function create(array $data): AdminRole
    {
        if (AdminRole::where('code', $data['code'])->find()) {
            throw new \Exception('角色编码已存在');
        }

        $role = AdminRole::create($data);

        // 分配菜单
        if (!empty($data['menu_ids'])) {
            $role->menus()->sync($data['menu_ids']);
        }

        return $role;
    }

    /**
     * 更新角色
     */
    public function update(int $id, array $data): AdminRole
    {
        $role = AdminRole::find($id);
        if (!$role) {
            throw new \Exception('角色不存在');
        }

        if (!empty($data['code']) && $data['code'] !== $role->code) {
            if (AdminRole::where('code', $data['code'])->find()) {
                throw new \Exception('角色编码已存在');
            }
        }

        $role->save($data);

        // 更新菜单
        if (isset($data['menu_ids'])) {
            $role->menus()->sync($data['menu_ids']);
        }

        return $role;
    }

    /**
     * 删除角色
     */
    public function delete(int $id): bool
    {
        $role = AdminRole::find($id);
        if (!$role) {
            throw new \Exception('角色不存在');
        }
        if ($role->code === 'super_admin') {
            throw new \Exception('不能删除超级管理员角色');
        }
        $role->menus()->detach();
        $role->users()->detach();
        return $role->delete();
    }

    /**
     * 分配菜单权限
     */
    public function assignMenus(int $id, array $menuIds): AdminRole
    {
        $role = AdminRole::find($id);
        if (!$role) {
            throw new \Exception('角色不存在');
        }
        $role->menus()->sync($menuIds);
        return $role->load('menus');
    }

    /**
     * 切换状态
     */
    public function toggleStatus(int $id): AdminRole
    {
        $role = AdminRole::find($id);
        if (!$role) {
            throw new \Exception('角色不存在');
        }
        $role->status = $role->status == 1 ? 0 : 1;
        $role->save();
        return $role;
    }
}
