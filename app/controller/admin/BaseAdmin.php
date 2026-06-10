<?php
namespace app\controller\admin;

use think\App;
use think\facade\Request;

class BaseAdmin
{
    protected $app;
    protected $request;
    protected $userId;
    protected $username;

    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $app->request;
        $this->initialize();
    }

    protected function initialize()
    {
        $this->userId = $this->request->userId ?? 0;
    }

    /**
     * 获取分页参数
     */
    protected function getPagination(): array
    {
        return [
            'page'      => intval($this->request->param('page', 1)),
            'page_size' => intval($this->request->param('page_size', 15)),
        ];
    }

    /**
     * 获取请求参数（排除分页和空值）
     */
    protected function getParams(): array
    {
        return $this->request->param();
    }
}
