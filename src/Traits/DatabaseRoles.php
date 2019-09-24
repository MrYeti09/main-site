<?php

namespace Viaativa\Viaroot\Traits;

use TCG\Voyager\Models\Role;

trait DatabaseRoles
{
    protected function addRole($name, $displayName){
        $role = Role::firstOrNew([
            'name' => $name
        ]);

        if (!$role->exists) {
            $role->fill([
                'display_name' => $displayName
            ])->save();
        }
    }
}
