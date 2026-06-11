<?php
namespace app\controller\admin;

use app\model\AdminArticle;

class Article extends BaseAdmin
{
    // 允许写入的字段
    private $allowedFields = [
        'title', 'summary', 'author', 'source', 'category_id',
        'cover_image', 'content', 'status', 'is_top', 'is_recommend'
    ];

    // 状态映射：前端字符串 → 数据库整数值
    private $statusMap = [
        'draft'      => 0,
        'published'  => 1,
        'unpublished' => 2,
    ];

    /**
     * 文章列表（分页，支持搜索）
     */
    public function index()
    {
        try {
            $params = $this->getParams();
            $query = AdminArticle::with(['category']);

            // 按标题搜索
            if (!empty($params['title'])) {
                $query->where('title', 'like', '%' . $params['title'] . '%');
            }
            // 按分类筛选
            if (!empty($params['category_id'])) {
                $query->where('category_id', intval($params['category_id']));
            }
            // 按状态筛选
            if (isset($params['status']) && $params['status'] !== '') {
                $query->where('status', intval($params['status']));
            }

            $paginator = $query->order('is_top', 'desc')
                ->order('id', 'desc')
                ->paginate([
                    'page'      => intval($params['page'] ?? 1),
                    'list_rows' => intval($params['page_size'] ?? 15),
                ]);

            return success([
                'list'    => $paginator->items(),
                'total'   => $paginator->total(),
            ]);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 文章详情
     */
    public function read($id)
    {
        try {
            $article = AdminArticle::with(['category'])->find(intval($id));
            if (!$article) {
                return error('文章不存在', 404);
            }
            // 阅读量+1
            $article->inc('view_count')->update();

            // 将数字状态转为前端字符串
            $data = $article->toArray();
            $data['status'] = array_search($data['status'], $this->statusMap) ?: 'draft';
            return success($data);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 新增文章
     */
    public function add()
    {
        $data = $this->request->post();
        if (empty($data['title'])) {
            return error('文章标题不能为空');
        }

        try {
            $saveData = $this->filterData($data);
            $saveData['user_id'] = $this->userId;
            $article = AdminArticle::create($saveData);
            return success($article, '创建成功', 201);
        } catch (\Exception $e) {
            return error('创建失败：' . $e->getMessage());
        }
    }

    /**
     * 编辑文章
     */
    public function edit($id)
    {
        $data = $this->request->put();
        try {
            $saveData = $this->filterData($data);
            $article = AdminArticle::update($saveData, ['id' => intval($id)]);
            return success($article, '更新成功');
        } catch (\Exception $e) {
            return error('更新失败：' . $e->getMessage());
        }
    }

    /**
     * 删除文章
     */
    public function delete($id)
    {
        try {
            AdminArticle::destroy(intval($id));
            return success(null, '删除成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 切换发布/下架状态
     */
    public function toggleStatus($id)
    {
        try {
            $article = AdminArticle::find(intval($id));
            if (!$article) {
                return error('文章不存在', 404);
            }
            $article->status = $article->status == 1 ? 2 : 1;
            if ($article->status == 1) {
                $article->published_at = date('Y-m-d H:i:s');
            }
            $article->save();
            return success($article, '状态切换成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 切换置顶
     */
    public function toggleTop($id)
    {
        try {
            $article = AdminArticle::find(intval($id));
            if (!$article) {
                return error('文章不存在', 404);
            }
            $article->is_top = $article->is_top ? 0 : 1;
            $article->save();
            return success($article, '置顶状态切换成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 切换推荐
     */
    public function toggleRecommend($id)
    {
        try {
            $article = AdminArticle::find(intval($id));
            if (!$article) {
                return error('文章不存在', 404);
            }
            $article->is_recommend = $article->is_recommend ? 0 : 1;
            $article->save();
            return success($article, '推荐状态切换成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 过滤并转换数据
     */
    private function filterData($data)
    {
        $result = [];
        foreach ($this->allowedFields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];
                // 状态字段：字符串转整数
                if ($field === 'status') {
                    $value = $this->statusMap[$value] ?? 0;
                }
                // 整型字段确保为整数
                if (in_array($field, ['category_id', 'is_top', 'is_recommend'])) {
                    $value = intval($value);
                }
                // 空字符串保持为空字符串，不转为 null（避免 NOT NULL 约束报错）
                $result[$field] = $value;
            }
        }
        return $result;
    }
}
