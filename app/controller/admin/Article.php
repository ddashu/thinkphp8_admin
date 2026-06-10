<?php
namespace app\controller\admin;

use app\model\AdminArticle;

class Article extends BaseAdmin
{
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

            $list = $query->order('is_top', 'desc')
                ->order('id', 'desc')
                ->paginate([
                    'page'      => intval($params['page'] ?? 1),
                    'list_rows' => intval($params['page_size'] ?? 15),
                ]);

            return success($list);
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
            return success($article);
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
            $data['user_id'] = $this->userId;
            $article = AdminArticle::create($data);
            return success($article, '创建成功', 201);
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * 编辑文章
     */
    public function edit($id)
    {
        $data = $this->request->put();
        try {
            $article = AdminArticle::update($data, ['id' => intval($id)]);
            return success($article, '更新成功');
        } catch (\Exception $e) {
            return error($e->getMessage());
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
}
