<?php
namespace app\service;

use app\model\AdminUser;
use app\model\AdminUserRole;

class UserService
{
    /**
     * 获取用户列表
     */
    public function getList(array $params = []): array
    {
        $query = AdminUser::with('roles');

        if (!empty($params['username'])) {
            $query->where('username', 'like', '%' . $params['username'] . '%');
        }
        if (!empty($params['nickname'])) {
            $query->where('nickname', 'like', '%' . $params['nickname'] . '%');
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $query->where('status', $params['status']);
        }

        $page     = intval($params['page'] ?? 1);
        $pageSize = intval($params['page_size'] ?? 15);

        $list = $query->order('id', 'desc')->paginate([
            'page'     => $page,
            'list_rows' => $pageSize,
        ]);

        $items = $list->items();
        foreach ($items as &$item) {
            $item->hidden(['password']);
        }

        return [
            'list'  => $items,
            'total' => $list->total(),
            'page'  => $page,
            'page_size' => $pageSize,
        ];
    }

    /**
     * 获取用户详情
     */
    public function getInfo(int $id): ?AdminUser
    {
        $user = AdminUser::with('roles')->find($id);
        if ($user) {
            $user->hidden(['password']);
        }
        return $user;
    }

    /**
     * 创建用户
     */
    public function create(array $data): AdminUser
    {
        if (AdminUser::where('username', $data['username'])->find()) {
            throw new \Exception('用户名已存在');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $user = AdminUser::create($data);

        // 分配角色
        if (!empty($data['role_ids'])) {
            $user->roles()->sync($data['role_ids']);
        }

        return $user;
    }

    /**
     * 更新用户
     */
    public function update(int $id, array $data): AdminUser
    {
        $user = AdminUser::find($id);
        if (!$user) {
            throw new \Exception('用户不存在');
        }

        if (!empty($data['username']) && $data['username'] !== $user->username) {
            if (AdminUser::where('username', $data['username'])->find()) {
                throw new \Exception('用户名已存在');
            }
        }

        // 不允许通过此方法修改密码
        unset($data['password']);

        $user->save($data);

        // 更新角色
        if (isset($data['role_ids'])) {
            $user->roles()->sync($data['role_ids']);
        }

        return $user;
    }

    /**
     * 删除用户
     */
    public function delete(int $id): bool
    {
        $user = AdminUser::find($id);
        if (!$user) {
            throw new \Exception('用户不存在');
        }
        if ($user->username === 'admin') {
            throw new \Exception('不能删除超级管理员');
        }
        $user->roles()->detach();
        return $user->delete();
    }

    /**
     * 切换用户状态
     */
    public function toggleStatus(int $id): AdminUser
    {
        $user = AdminUser::find($id);
        if (!$user) {
            throw new \Exception('用户不存在');
        }
        if ($user->username === 'admin') {
            throw new \Exception('不能禁用超级管理员');
        }
        $user->status = $user->status === 1 ? 0 : 1;
        $user->save();
        return $user;
    }

    /**
     * 重置密码
     */
    public function resetPassword(int $id, string $password): bool
    {
        $user = AdminUser::find($id);
        if (!$user) {
            throw new \Exception('用户不存在');
        }
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        return $user->save();
    }
}
