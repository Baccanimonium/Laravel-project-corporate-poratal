<?php

namespace Corp\Repositories;

use Corp\Permissions;

class PermissionsRepository extends Repository{

    protected $rol_rep;


    public function __construct(Permissions $permission, RolesRepository $rol_rep)
    {
        $this->rol_rep = $rol_rep;

        $this->model = $permission;
    }


    public function changePermissions($request){

        $data = $request->except('_token');

        $roles = $this->rol_rep->get();


        foreach ($roles as $value) {
            if (isset($data[$value->id])) {
                $value->savePermissions($data[$value->id]);
            }
            else {
                $value->savePermissions([]);
            }
        }

        return ['status'=>'Права обновленны'];
    }
}