<?php

namespace Corp\Http\Controllers\Admin;

use Corp\Permissions;
use Corp\Repositories\PermissionsRepository;
use Corp\Role;
use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;

class PermissionsController extends AdminController
{
    protected $per_rep;
    protected $rol_rep;
    public function __construct(PermissionsRepository $per_rep, Role $rol_rep)
    {
        $this->middleware('can:ADMIN_USERS');
        parent::__construct();
        $this->per_rep = $per_rep;
        $this->rol_rep = $rol_rep;
        $this->template = env('THEME').'.admin.permissions';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = "Менеджер прав пользователей";
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();
        $this->content = view(env('THEME').'.admin.permissions_content')->with(['roles'=>$roles,'permission'=>$permissions])->render();
        return $this->renderOutPut();
    }

    public function getRoles()
    {
        $roles = $this->rol_rep->get();
        return $roles;
    }

    public function getPermissions()
    {
        $permissions = $this->per_rep->get();
        return $permissions;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->per_rep->changePermissions($request);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return back()->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Corp\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function show(Permissions $permissions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Corp\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function edit(Permissions $permissions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Corp\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permissions $permissions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Corp\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permissions $permissions)
    {
        //
    }
}
