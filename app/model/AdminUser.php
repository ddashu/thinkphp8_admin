<?php
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

class AdminUser extends Model
{
    use SoftDelete;

    protected $pk = 'id';
    protected $name = 'admin_user';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = null;

    protected $type = [
        'status'          => 'integer',
        'last_login_time' => 'datetime',
        'create_time'     => 'datetime',
        'update_time'     => 'datetime',
        'delete_time'     => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, AdminUserRole::class, 'role_id', 'user_id');
    }
}
