<?php
namespace app\model;

use think\Model;

class AdminPaymentConfig extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_payment_config';

    protected $type = [
        'status'      => 'integer',
        'sandbox'     => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
