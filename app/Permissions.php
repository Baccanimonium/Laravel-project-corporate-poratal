<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = 'permissions';
    public function roles()
    {
        return $this->belongsToMany('Corp\Role', 'permissions_role');
    }
}
