<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\TableResources\RoleResource;
use Humweb\Table\InertiaTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:role view', ['only' => ['index', 'show']]);
        $this->middleware('can:role create', ['only' => ['create', 'store']]);
        $this->middleware('can:role update', ['only' => ['edit', 'update']]);
        $this->middleware('can:role delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Role/Index')->table(function (InertiaTable $table) use ($request) {
            $resource = RoleResource::make($request);
            $resource->toResponse($table);
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $permissions = Permission::all()->pluck("name", "id");

        return Inertia::render('Admin/Role/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRoleRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());

        if (!empty($request->permissions)) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Role  $role
     *
     * @return Response
     */
    public function show(Role $role)
    {
        $permissions        = Permission::all()->groupBy(fn($v) => explode(' ',$v->name)[0]);
        $roleHasPermissions = array_column(json_decode($role->permissions, true), 'id');

        return Inertia::render('Admin/Role/Show', [
            'role'               => $role,
            'permissions'        => $permissions,
            'roleHasPermissions' => $roleHasPermissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role  $role
     *
     * @return Response
     */
    public function edit(Role $role)
    {
        $permissions        = Permission::all()->groupBy(fn($v) => ucfirst(explode(' ',$v->name)[0]));
        $roleHasPermissions = collect($role->permissions)->mapWithKeys(function ($item, $key) {
            return [$item['id'] => true];
        });
        foreach ($permissions as $permissionSet) {
            foreach ($permissionSet as $permission) {
                if (!isset($roleHasPermissions[$permission->id])) {
                    $roleHasPermissions[$permission->id] = false;
                }
            }
        }
        return Inertia::render('Admin/Role/Edit', [
            'role'               => $role,
            'permissions'        => $permissions,
            'roleHasPermissions' => $roleHasPermissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRoleRequest  $request
     * @param  Role               $role
     *
     * @return RedirectResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $permissions = collect($request->permissions ?? [])->filter()->keys()->all();

        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     *
     * @return RedirectResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', __('Role deleted successfully'));
    }
}
