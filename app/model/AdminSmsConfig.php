<?php
namespace app\model;

use think\Model;

class AdminSmsConfig extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_sms_config';

    protected $type = [
        'status'      => 'integer',
        'is_default'  => 'integer',
        'daily_limit' => 'integer',
        'used_today'  => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
