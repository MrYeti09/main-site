<?php

use TCG\Voyager\Traits\Seedable;
use Illuminate\Database\Seeder;

class VoyagerSeeder extends Seeder
{
    use Seedable;
    protected $seedersPath = __DIR__.'/voyager/';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('DefaultData');
        $this->seed('DataTypeSeeder');
        $this->seed('DataRowsSeeder');
        $this->seed('MenuItemsSeeder');
        $this->seed('PermissionsSeeder');
        $this->seed('SettingsFormfieldsSeeder');
    }
}
