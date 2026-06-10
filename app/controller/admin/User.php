<?php
namespace app\controller\admin;

use app\service\UserService;

class User extends BaseAdmin
{
    protected $userService;

    protected function initialize()
    {
        parent::initialize();
        $this->userService = new UserService();
    }

    /**
     * 用户列表
     */
    public function index()
    {
        try {
            $params = $this->getParams();
            $result = $this->userService->getList($params);
            return success($result);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 用户详情
     */
    public function read($id)
    {
        try {
            $user = $this->userService->getInfo(intval($id));
            if (!$user) {
                return error('用户不存在', 404);
            }
            return success($user);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 新增用户
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['username']) || empty($data['password'])) {
            return error('用户名和密码不能为空');
        }

        try {
            $user = $this->userService->create($data);
            return success($user, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑用户
     */
    public function edit($id)
    {
        $data = $this->request->put();
        try {
            $user = $this->userService->update(intval($id), $data);
            return success($user, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 删除用户
     */
    public function delete($id)
    {
        try {
            $this->userService->delete(intval($id));
            return success(null, '删除成功');
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
            $user = $this->userService->toggleStatus(intval($id));
            return success($user, '状态切换成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 重置密码
     */
    public function resetPassword($id)
    {
        $password = $this->request->post('password', '');
        if (empty($password)) {
            return error('新密码不能为空');
        }

        try {
            $this->userService->resetPassword(intval($id), $password);
            return success(null, '密码重置成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
