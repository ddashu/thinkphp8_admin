<?php
namespace app\model;

use think\Model;

class AdminArticleCategory extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_article_category';

    protected $type = [
        'parent_id'   => 'integer',
        'sort'        => 'integer',
        'status'      => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    public function children()
    {
        return $this->hasMany(AdminArticleCategory::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(AdminArticleCategory::class, 'parent_id', 'id');
    }
}
