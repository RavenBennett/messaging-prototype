<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear cached permissions to ensure changes take effect
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = 'admin';
        $roles= [$admin, 'user'];

        $permissions = ['view admin dashboard', 'approve message'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role) {
            $roleInstance = Role::findOrCreate($role);

            if($role === $admin) {
                $roleInstance->givePermissionTo($permissions);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear cached permissions to ensure changes take effect
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles= ['admin', 'user'];
        $permissions = ['view admin dashboard', 'approve message'];

        foreach($roles as $role) {
            Role::findByName('name', $role)->delete();
        }

        foreach($permissions as $permission) {
            Permission::findByName($permission)->delete();
        }


    }
};
