<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // this can be done as separate statements
        $adminRole         = Role::create(['name' => 'admin']);
        $permissionsByRole = [
            'admin' => [
                'user view', 'user create', 'user update', 'user delete',
                'role view', 'role create', 'role update', 'role delete',
                'permission view', 'permission create', 'permission update', 'permission delete',
            ],
        ];

        $insertPermissions = fn($role) => collect($permissionsByRole[$role])
            ->map(fn($name) => DB::table('permissions')->insertGetId([
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]))
            ->toArray();

        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin')
        ];

        foreach ($permissionIdsByRole as $role => $permissionIds) {
            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn($id) => [
                        'role_id'       => $adminRole->id,
                        'permission_id' => $id
                    ])->toArray()
                );
        }

        if ($user = User::find(1)) {
            $user->assignRole('admin');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->delete();
        DB::table('roles')->delete();
        DB::table('role_has_permissions')->delete();
    }
};
