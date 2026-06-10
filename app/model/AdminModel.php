<?php
namespace app\model;

use think\Model;

class AdminModel extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_model';

    protected $type = [
        'status'      => 'integer',
        'sort'        => 'integer',
        'config'      => 'json',
        'pricing'     => 'json',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
