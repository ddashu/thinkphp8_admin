<?php
namespace app\controller\admin;

use app\service\RoleService;

class Role extends BaseAdmin
{
    protected $roleService;

    protected function initialize()
    {
        parent::initialize();
        $this->roleService = new RoleService();
    }

    /**
     * 角色列表
     */
    public function index()
    {
        try {
            $params = $this->getParams();
            $result = $this->roleService->getList($params);
            return success($result);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 所有角色（不分页）
     */
    public function all()
    {
        try {
            $result = $this->roleService->getAll();
            return success($result);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 角色详情
     */
    public function read($id)
    {
        try {
            $role = $this->roleService->getInfo(intval($id));
            if (!$role) {
                return error('角色不存在', 404);
            }
            return success($role);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 新增角色
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['name']) || empty($data['code'])) {
            return error('角色名称和编码不能为空');
        }

        try {
            $role = $this->roleService->create($data);
            return success($role, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑角色
     */
    public function edit($id)
    {
        $data = $this->request->put();
        try {
            $role = $this->roleService->update(intval($id), $data);
            return success($role, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 删除角色
     */
    public function delete($id)
    {
        try {
            $this->roleService->delete(intval($id));
            return success(null, '删除成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 分配菜单权限
     */
    public function assignMenus($id)
    {
        $menuIds = $this->request->post('menu_ids', []);
        try {
            $role = $this->roleService->assignMenus(intval($id), $menuIds);
            return success($role, '分配成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 切换状态
     */
    public function toggleStatus($id)
    {
        try {
            $role = $this->roleService->toggleStatus(intval($id));
            return success($role, '操作成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
