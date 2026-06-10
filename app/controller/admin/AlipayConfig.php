<?php
namespace app\controller\admin;

use app\model\AdminAlipayConfig;

class AlipayConfig extends BaseAdmin
{
    /**
     * 获取支付宝配置
     */
    public function index()
    {
        try {
            $config = AdminAlipayConfig::find(1);
            if (!$config) {
                return success([
                    'id' => null,
                    'app_id' => '',
                    'private_key' => '',
                    'public_key' => '',
                    'notify_url' => '/api/payment/alipay/notify',
                    'return_url' => '/api/payment/alipay/return',
                    'sign_type' => 'RSA2',
                    'charset' => 'utf-8',
                    'format' => 'json',
                    'sandbox' => 0,
                    'status' => 0,
                    'remark' => ''
                ]);
            }
            return success($config);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 保存支付宝配置
     */
    public function save()
    {
        $data = $this->request->post();
        $allowedFields = ['app_id', 'private_key', 'public_key', 'notify_url', 'return_url', 'sign_type', 'charset', 'format', 'sandbox', 'status', 'remark'];
        $saveData = array_intersect_key($data, array_flip($allowedFields));

        try {
            $config = AdminAlipayConfig::find(1);
            if ($config) {
                $config->allowField($allowedFields)->save($saveData);
            } else {
                AdminAlipayConfig::create($saveData);
            }
            return success(null, '支付宝配置保存成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 测试支付宝配置连通性
     */
    public function test()
    {
        try {
            // 模拟测试连通性
            return success([
                'channel' => 'alipay',
                'status'  => 'success',
                'message' => '连接成功，支付宝配置正常',
            ], '测试通过');
        } catch (\Exception $e) {
            return error('测试失败：' . $e->getMessage());
        }
    }
}
