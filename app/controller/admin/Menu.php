<?php
namespace app\controller\admin;

use app\service\MenuService;

class Menu extends BaseAdmin
{
    protected $menuService;

    protected function initialize()
    {
        parent::initialize();
        $this->menuService = new MenuService();
    }

    /**
     * 菜单树
     */
    public function tree()
    {
        try {
            $tree = $this->menuService->getTree();
            return success($tree);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 用户菜单树
     */
    public function userMenuTree()
    {
        try {
            $tree = $this->menuService->getUserMenuTree($this->userId);
            return success($tree);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 菜单列表
     */
    public function index()
    {
        try {
            $params = $this->getParams();
            $result = $this->menuService->getList($params);
            return success($result);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 菜单详情
     */
    public function read($id)
    {
        try {
            $menu = $this->menuService->getInfo(intval($id));
            if (!$menu) {
                return error('菜单不存在', 404);
            }
            return success($menu);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 新增菜单
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['name'])) {
            return error('菜单名称不能为空');
        }

        try {
            $menu = $this->menuService->create($data);
            return success($menu, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑菜单
     */
    public function edit($id)
    {
        $data = $this->request->put();
        try {
            $menu = $this->menuService->update(intval($id), $data);
            return success($menu, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 删除菜单
     */
    public function delete($id)
    {
        try {
            $this->menuService->delete(intval($id));
            return success(null, '删除成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
