<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\TableResources\PermissionResource;
use Humweb\Table\InertiaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:permission view', ['only' => ['index', 'show']]);
        $this->middleware('can:permission create', ['only' => ['create', 'store']]);
        $this->middleware('can:permission update', ['only' => ['edit', 'update']]);
        $this->middleware('can:permission delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Permission/Index')->table(function (InertiaTable $table) use ($request) {
            $resource = PermissionResource::make($request);
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
        return Inertia::render('Admin/Permission/Create');
    }

    public function store(StorePermissionRequest $request)
    {
        Permission::create($request->all());

        return redirect()->route('admin.permissions.index')
                        ->with('success', __('Permission created successfully.'));
    }

    public function show(Permission $permission)
    {
        return Inertia::render('Admin/Permission/Show', [
            'permission' => $permission,
        ]);
    }

    public function edit(Permission $permission)
    {
        return Inertia::render('Admin/Permission/Edit', [
            'permission' => $permission,
        ]);
    }


    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->all());

        return redirect()->route('admin.permissions.index')
                        ->with('success', __('Permission updated successfully.'));
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')
                        ->with('success', __('Permission deleted successfully'));
    }
}
