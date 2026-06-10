<?php
namespace app\model;

use think\Model;

class AdminLoginLog extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_login_log';

    protected $type = [
        'user_id'     => 'integer',
        'status'      => 'integer',
        'create_time' => 'datetime',
    ];

    protected $updateTime = false;
}
