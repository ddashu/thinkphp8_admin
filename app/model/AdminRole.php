<?php
namespace app\model;

use think\Model;

class AdminRole extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_role';

    protected $type = [
        'sort'        => 'integer',
        'status'      => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    public function menus()
    {
        return $this->belongsToMany(AdminMenu::class, AdminRoleMenu::class, 'menu_id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(AdminUser::class, AdminUserRole::class, 'user_id', 'role_id');
    }
}
