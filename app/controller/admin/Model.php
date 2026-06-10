<?php
namespace app\controller\admin;

use app\model\AdminModel;

class Model extends BaseAdmin
{
    /**
     * 模型列表
     */
    public function index()
    {
        $params = $this->getPagination();
        $query  = AdminModel::order('sort', 'asc')->order('id', 'desc');

        $name = $this->request->param('name', '');
        if (!empty($name)) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $category = $this->request->param('category', '');
        if (!empty($category)) {
            $query->where('category', $category);
        }

        $provider = $this->request->param('provider', '');
        if (!empty($provider)) {
            $query->where('provider', $provider);
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
     * 模型详情
     */
    public function read($id)
    {
        $model = AdminModel::find(intval($id));
        if (!$model) {
            return error('模型不存在', 404);
        }
        return success($model);
    }

    /**
     * 新增模型
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['name']) || empty($data['code'])) {
            return error('模型名称和编码不能为空');
        }

        if (AdminModel::where('code', $data['code'])->find()) {
            return error('模型编码已存在');
        }

        try {
            $model = AdminModel::create($data);
            return success($model, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑模型
     */
    public function edit($id)
    {
        $model = AdminModel::find(intval($id));
        if (!$model) {
            return error('模型不存在', 404);
        }

        $data = $this->request->put();

        if (!empty($data['code']) && $data['code'] !== $model->code) {
            if (AdminModel::where('code', $data['code'])->find()) {
                return error('模型编码已存在');
            }
        }

        try {
            $model->save($data);
            return success($model, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 删除模型
     */
    public function delete($id)
    {
        $model = AdminModel::find(intval($id));
        if (!$model) {
            return error('模型不存在', 404);
        }

        try {
            $model->delete();
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
        $model = AdminModel::find(intval($id));
        if (!$model) {
            return error('模型不存在', 404);
        }
        try {
            $model->status = $model->status == 1 ? 0 : 1;
            $model->save();
            return success($model, '操作成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
