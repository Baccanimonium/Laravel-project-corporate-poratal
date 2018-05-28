<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany('Corp\User', 'user_role');
    }

    public function permission()
    {
        return $this->belongsToMany('Corp\Permissions', 'permissions_role');

    }
    public function hasPermission($name, $require = FALSE) {

        if(is_array($name)) {
            foreach($name as $permName) {
                $permName = $this->hasPermission($permName);
                if($permName && !$require) {
                    return TRUE;
                }
                else if(!$permName  && $require) {
                    return FALSE;
                }
            }

            return  $require;
        }
        else {
            foreach($this->permission()->get() as $permission) {
                if ($permission->name == $name) {
                    return true;
                }
            }
        }
        return false;
    }

    public function savePermissions($inputPermissions)
    {
        if (!empty($inputPermissions)) {
            $this->permission()->sync($inputPermissions);
        } else {
            $this->permission()->detach();
        }
        return true;
    }
}
