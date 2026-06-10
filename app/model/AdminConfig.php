<?php
namespace app\model;

use think\Model;

class AdminConfig extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_config';

    protected $type = [
        'sort'        => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
