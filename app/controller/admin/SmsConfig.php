<?php
namespace app\controller\admin;

use app\model\AdminSmsConfig;
use think\facade\Db;

class SmsConfig extends BaseAdmin
{
    /**
     * 短信渠道列表（分页）
     */
    public function index()
    {
        try {
            $params = $this->getParams();
            $query = AdminSmsConfig::order('id', 'asc');

            if (!empty($params['name'])) {
                $query->where('name', 'like', '%' . $params['name'] . '%');
            }
            if (isset($params['status']) && $params['status'] !== '') {
                $query->where('status', intval($params['status']));
            }

            $list = $query->paginate([
                'page'      => intval($params['page'] ?? 1),
                'list_rows' => intval($params['page_size'] ?? 15),
            ]);

            return success($list);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 短信渠道详情
     */
    public function read($id)
    {
        try {
            $config = AdminSmsConfig::find(intval($id));
            if (!$config) {
                return error('短信渠道不存在', 404);
            }
            return success($config);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 新增短信渠道
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['name']) || empty($data['code'])) {
            return error('渠道名称和标识不能为空');
        }

        try {
            $config = AdminSmsConfig::create($data);
            return success($config, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑短信渠道
     */
    public function edit($id)
    {
        $data = $this->request->put();
        try {
            $config = AdminSmsConfig::update($data, ['id' => intval($id)]);
            return success($config, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 删除短信渠道
     */
    public function delete($id)
    {
        try {
            AdminSmsConfig::destroy(intval($id));
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
            $config = AdminSmsConfig::find(intval($id));
            if (!$config) {
                return error('短信渠道不存在', 404);
            }
            $config->status = $config->status ? 0 : 1;
            $config->save();
            return success($config, '状态切换成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 设为默认
     */
    public function setDefault($id)
    {
        try {
            Db::startTrans();
            try {
                AdminSmsConfig::where('is_default', 1)->update(['is_default' => 0]);

                $config = AdminSmsConfig::find(intval($id));
                if (!$config) {
                    Db::rollback();
                    return error('短信渠道不存在', 404);
                }
                $config->is_default = 1;
                $config->save();

                Db::commit();
                return success($config, '设置默认成功');
            } catch (\Exception $e) {
                Db::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 发送测试短信
     */
    public function sendTest()
    {
        $phone = $this->request->post('phone', '');
        $id = $this->request->post('id', 0);

        if (empty($phone)) {
            return error('手机号不能为空');
        }

        try {
            $config = AdminSmsConfig::find(intval($id));
            if (!$config) {
                return error('短信渠道不存在', 404);
            }

            // 模拟发送测试短信
            return success([
                'phone'   => $phone,
                'channel' => $config->name,
                'status'  => 'success',
            ], '测试短信发送成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
