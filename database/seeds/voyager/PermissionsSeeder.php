<?php

use Illuminate\Database\Seeder;
use Viaativa\Viaroot\Traits\DatabasePermissions;

class PermissionsSeeder extends Seeder
{
    use DatabasePermissions;

    public function run()
    {
        $this->iconSeeder();
    }

    private function iconSeeder()
    {
        $this->createPermissions([
            'icons'
        ]);
    }

}
