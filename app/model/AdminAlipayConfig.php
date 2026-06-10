<?php
namespace app\model;

use think\Model;

class AdminAlipayConfig extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_alipay_config';

    protected $type = [
        'status'      => 'integer',
        'sandbox'     => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
