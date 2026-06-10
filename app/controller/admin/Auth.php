<?php
namespace app\controller\admin;

use app\service\AuthService;

class Auth extends BaseAdmin
{
    protected $authService;

    protected function initialize()
    {
        parent::initialize();
        $this->authService = new AuthService();
    }

    /**
     * 登录
     */
    public function login()
    {
        $username = $this->request->post('username', '');
        $password = $this->request->post('password', '');

        if (empty($username) || empty($password)) {
            return error('用户名和密码不能为空');
        }

        try {
            $ip        = $this->request->ip();
            $userAgent = $this->request->header('user-agent', '');
            $result    = $this->authService->login($username, $password, $ip, $userAgent);
            return success($result, '登录成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        try {
            $this->authService->logout($this->userId);
            return success(null, '登出成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 刷新令牌
     */
    public function refresh()
    {
        $refreshToken = $this->request->post('refresh_token', '');
        if (empty($refreshToken)) {
            return error('刷新令牌不能为空');
        }

        try {
            $result = $this->authService->refresh($refreshToken);
            return success($result, '刷新成功');
        } catch (\Exception $e) {
            return error($e->getMessage(), 401);
        }
    }

    /**
     * 获取当前用户信息
     */
    public function info()
    {
        try {
            $result = $this->authService->getUserInfo($this->userId);
            return success($result);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
