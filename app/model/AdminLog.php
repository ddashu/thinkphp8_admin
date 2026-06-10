<?php
namespace app\model;

use think\Model;

class AdminLog extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_log';

    protected $type = [
        'user_id'     => 'integer',
        'params'      => 'json',
        'create_time' => 'datetime',
    ];

    protected $updateTime = false;
}
