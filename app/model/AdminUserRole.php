<?php
namespace app\model;

use think\model\Pivot;

class AdminUserRole extends Pivot
{
    protected $pk = 'user_id';
    protected $name = 'admin_user_role';
    public $timestamps = false;
    protected $autoWriteTimestamp = false;
}
