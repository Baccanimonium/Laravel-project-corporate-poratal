<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.12.2017
 * Time: 17:33
 */

namespace Corp\Repositories;


use Corp\Role;

class RolesRepository extends Repository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }
}