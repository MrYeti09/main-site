<?php
namespace Viaativa\Viaroot\Traits;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\Permission;
trait DatabasePermissions
{
    protected function createPermissions($tablesNames, $syncWith = "admin")
    {
        $this->generateFor($tablesNames);
        $this->syncPermissionsWith($syncWith);
    }
    protected function generateFor($tablesNames = [])
    {
        foreach ($tablesNames as $tableName) {
            Permission::generateFor($tableName);
        }
    }
    protected function syncPermissionsWith($syncWith)
    {
        $role = Role::where('name', $syncWith)->firstOrFail();
        $permissions = Permission::all();
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );
    }
    protected function defineAllPermissions($role, $tableName)
    {
        $arr_permissions = [
            "browse_{$tableName}",
            "read_{$tableName}",
            "edit_{$tableName}",
            "add_{$tableName}",
            "delete_{$tableName}",
        ];
        $this->definePermissions($role, $arr_permissions);
    }

    protected function definePermissions($role, $arr_permissions = [])
    {
        $role = Role::where('name', $role)->firstOrFail();
        foreach ($arr_permissions as $arr_permission) {
            $permission = Permission::where('key', $arr_permission)->first();
            if (!$role->permissions->contains($permission->id)) {
                $role->permissions()->attach($permission);
            }
        }
    }
}
