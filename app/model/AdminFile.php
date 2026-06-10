<?php
namespace app\model;

use think\Model;

class AdminFile extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_file';

    protected $type = [
        'user_id'     => 'integer',
        'size'        => 'integer',
        'create_time' => 'datetime',
    ];

    protected $updateTime = false;
}
