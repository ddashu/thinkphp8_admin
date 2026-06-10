<?php
namespace app\model;

use think\model\Pivot;

class AdminRoleMenu extends Pivot
{
    protected $pk = 'role_id';
    protected $name = 'admin_role_menu';
    public $timestamps = false;
    protected $autoWriteTimestamp = false;
}
