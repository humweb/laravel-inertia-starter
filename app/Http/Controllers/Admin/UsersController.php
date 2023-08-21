<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\CreateUser;
use App\Actions\User\UpdateUser;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\TableResources\UserResource;
use Humweb\Table\InertiaTable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;


class UsersController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request)
    {
        return Inertia::render('Admin/Users')->table(function (InertiaTable $table) use ($request) {
            $resource = UserResource::make($request);
            $resource->toResponse($table);
        });
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::get(['name', 'id'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUser $create, StoreUserRequest $request)
    {
        $create->handle($request->all());
        return redirect()->route('admin.users.index')->with('success', 'Created user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Edit user
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update user data
     *
     * @param  Request  $request
     * @param  User     $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, UpdateUser $update, User $user)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
        ]);

        if ($request->password) {
            $request['password'] = bcrypt($request->password);
            $update->handle($request->all(), $user);
        } else {
            $update->handle($request->except('password'), $user);
        }

        return redirect()->route('admin.users.index')->with('success', 'Updated user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.users.index')->with('success', 'Deleted user');
    }
}
