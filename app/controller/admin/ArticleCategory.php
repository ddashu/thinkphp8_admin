<?php
namespace app\controller\admin;

use app\model\AdminArticleCategory;

class ArticleCategory extends BaseAdmin
{
    /**
     * 分类树形列表
     */
    public function index()
    {
        try {
            $list = AdminArticleCategory::order('sort', 'asc')->order('id', 'asc')
                ->select()->toArray();

            $tree = $this->buildTree($list);
            return success($tree);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 全部分类列表（平铺，用于下拉选择）
     */
    public function all()
    {
        try {
            $list = AdminArticleCategory::where('status', 1)
                ->order('sort', 'asc')->order('id', 'asc')
                ->select()->toArray();
            return success($list);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 新增分类
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['name'])) {
            return error('分类名称不能为空');
        }

        try {
            $category = AdminArticleCategory::create($data);
            return success($category, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑分类
     */
    public function edit($id)
    {
        $data = $this->request->put();
        try {
            $category = AdminArticleCategory::update($data, ['id' => intval($id)]);
            return success($category, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 删除分类
     */
    public function delete($id)
    {
        try {
            // 检查是否有子分类
            $children = AdminArticleCategory::where('parent_id', intval($id))->count();
            if ($children > 0) {
                return error('该分类下存在子分类，无法删除');
            }

            AdminArticleCategory::destroy(intval($id));
            return success(null, '删除成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 构建树形结构
     */
    private function buildTree(array $items, int $parentId = 0): array
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($items, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }
}
