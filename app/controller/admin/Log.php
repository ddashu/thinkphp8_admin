<?php
namespace app\controller\admin;

use app\model\AdminLog;

class Log extends BaseAdmin
{
    /**
     * 操作日志列表
     */
    public function index()
    {
        $params = $this->getPagination();
        $query  = AdminLog::order('id', 'desc');

        $username = $this->request->param('username', '');
        if (!empty($username)) {
            $query->where('username', 'like', '%' . $username . '%');
        }

        $module = $this->request->param('module', '');
        if (!empty($module)) {
            $query->where('module', $module);
        }

        $method = $this->request->param('method', '');
        if (!empty($method)) {
            $query->where('method', $method);
        }

        $startDate = $this->request->param('start_date', '');
        $endDate   = $this->request->param('end_date', '');
        if (!empty($startDate)) {
            $query->where('create_time', '>=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $query->where('create_time', '<=', $endDate . ' 23:59:59');
        }

        $list = $query->paginate([
            'page'      => $params['page'],
            'list_rows' => $params['page_size'],
        ]);

        return success([
            'list'      => $list->items(),
            'total'     => $list->total(),
            'page'      => $params['page'],
            'page_size' => $params['page_size'],
        ]);
    }

    /**
     * 日志详情
     */
    public function read($id)
    {
        try {
            $log = AdminLog::find(intval($id));
            if (!$log) {
                return error('日志不存在', 404);
            }
            return success($log);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
