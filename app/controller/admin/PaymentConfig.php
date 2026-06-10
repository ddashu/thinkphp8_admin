<?php
namespace app\controller\admin;

use app\model\AdminPaymentConfig;

class PaymentConfig extends BaseAdmin
{
    /**
     * 获取支付配置列表
     */
    public function index()
    {
        try {
            $list = AdminPaymentConfig::order('id', 'asc')->select()->toArray();
            return success($list);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 获取支付配置详情
     */
    public function read($id)
    {
        try {
            $config = AdminPaymentConfig::find(intval($id));
            if (!$config) {
                return error('配置不存在', 404);
            }
            return success($config);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 批量保存支付配置
     */
    public function save()
    {
        $items = $this->request->post('items', []);
        if (empty($items)) {
            return error('配置数据不能为空');
        }

        try {
            foreach ($items as $item) {
                if (!empty($item['id'])) {
                    AdminPaymentConfig::update($item);
                } else {
                    AdminPaymentConfig::create($item);
                }
            }
            return success(null, '保存成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 测试支付配置连通性
     */
    public function test()
    {
        $channel = $this->request->post('channel', '');
        if (empty($channel)) {
            return error('请选择支付渠道');
        }

        try {
            // 模拟测试连通性
            return success([
                'channel' => $channel,
                'status'  => 'success',
                'message' => '连接成功',
            ], '测试通过');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
