<?php
namespace app\controller\admin;

use app\model\AdminConfig;

class Config extends BaseAdmin
{
    /**
     * 配置列表
     */
    public function index()
    {
        $group = $this->request->param('group', '');

        $query = AdminConfig::order('sort', 'asc')->order('id', 'asc');
        if (!empty($group)) {
            $query->where('group', $group);
        }

        $list = $query->select()->toArray();

        // 按分组组织
        $grouped = [];
        foreach ($list as $item) {
            $g = $item['group'];
            if (!isset($grouped[$g])) {
                $grouped[$g] = [];
            }
            $grouped[$g][] = $item;
        }

        return success([
            'list'    => $list,
            'grouped' => $grouped,
        ]);
    }

    /**
     * 批量更新配置
     */
    public function batchUpdate()
    {
        $items = $this->request->post('items', []);
        if (empty($items)) {
            return error('配置数据不能为空');
        }

        try {
            foreach ($items as $item) {
                if (!empty($item['code'])) {
                    AdminConfig::where('code', $item['code'])->update([
                        'value' => $item['value'] ?? '',
                    ]);
                }
            }
            return success(null, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
}
