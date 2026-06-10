<?php
namespace app\model;

use think\Model;

class AdminApiKey extends Model
{
    protected $pk = 'id';
    protected $name = 'admin_api_key';

    protected $type = [
        'user_id'      => 'integer',
        'quota_limit'  => 'integer',
        'quota_used'   => 'integer',
        'status'       => 'integer',
        'expires_at'   => 'datetime',
        'last_used_at' => 'datetime',
        'create_time'  => 'datetime',
        'update_time'  => 'datetime',
    ];
}
