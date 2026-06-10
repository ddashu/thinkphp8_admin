<?php
namespace app\model;

use think\Model;

class AdminMenu extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_menu';

    protected $type = [
        'parent_id'   => 'integer',
        'type'        => 'integer',
        'sort'        => 'integer',
        'visible'     => 'integer',
        'status'      => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    public function children()
    {
        return $this->hasMany(AdminMenu::class, 'parent_id', 'id')->order('sort', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(AdminMenu::class, 'parent_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, AdminRoleMenu::class, 'role_id', 'menu_id');
    }
}
