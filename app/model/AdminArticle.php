<?php
namespace app\model;

use think\Model;

class AdminArticle extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_article';

    protected $type = [
        'category_id'   => 'integer',
        'view_count'    => 'integer',
        'like_count'    => 'integer',
        'comment_count' => 'integer',
        'is_top'        => 'integer',
        'is_recommend'  => 'integer',
        'status'        => 'integer',
        'user_id'       => 'integer',
        'published_at'  => 'datetime',
        'create_time'   => 'datetime',
        'update_time'   => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(AdminArticleCategory::class, 'category_id', 'id');
    }
}
