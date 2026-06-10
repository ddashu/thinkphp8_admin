<?php
namespace app\controller\admin;

use app\model\AdminApiKey;

class ApiKey extends BaseAdmin
{
    /**
     * API密钥列表
     */
    public function index()
    {
        $params = $this->getPagination();
        $query  = AdminApiKey::order('id', 'desc');

        $name = $this->request->param('name', '');
        if (!empty($name)) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $status = $this->request->param('status', '');
        if ($status !== '') {
            $query->where('status', intval($status));
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
     * API密钥详情
     */
    public function read($id)
    {
        $apiKey = AdminApiKey::find(intval($id));
        if (!$apiKey) {
            return error('API密钥不存在', 404);
        }
        return success($apiKey);
    }

    /**
     * 新增API密钥
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['name'])) {
            return error('密钥名称不能为空');
        }

        $data['user_id']    = $this->userId;
        $data['api_key']    = 'oc_' . bin2hex(random_bytes(16));
        $data['api_secret'] = bin2hex(random_bytes(32));
        $data['quota_used'] = 0;
        $data['status']     = $data['status'] ?? 1;

        try {
            $apiKey = AdminApiKey::create($data);
            return success($apiKey, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑API密钥
     */
    public function edit($id)
    {
        $apiKey = AdminApiKey::find(intval($id));
        if (!$apiKey) {
            return error('API密钥不存在', 404);
        }

        $data = $this->request->put();
        // 不允许修改api_key和api_secret
        unset($data['api_key'], $data['api_secret'], $data['user_id']);

        try {
            $apiKey->save($data);
            return success($apiKey, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 删除API密钥
     */
    public function delete($id)
    {
        $apiKey = AdminApiKey::find(intval($id));
        if (!$apiKey) {
            return error('API密钥不存在', 404);
        }

        try {
            $apiKey->delete();
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
        $apiKey = AdminApiKey::find(intval($id));
        if (!$apiKey) {
            return error('API密钥不存在', 404);
        }

        $apiKey->status = $apiKey->status === 1 ? 0 : 1;
        $apiKey->save();
        return success($apiKey, '状态切换成功');
    }
}
