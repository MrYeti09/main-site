<?php
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use Viaativa\Viaroot\Models\MenuItem;
use Viaativa\Viaroot\Traits\DatabaseDataType;
use Viaativa\Viaroot\Traits\DatabaseDataRow;
use Viaativa\Viaroot\Traits\DatabaseDataRowRelationship;
use Viaativa\Viaroot\Traits\DatabaseMenus;
use Viaativa\Viaroot\Traits\DatabasePermissions;

class DataTypeSeeder extends Seeder
{
    use DatabaseDataType;

    public function run()
    {
        $this->iconSeeder();
    }

    private function iconSeeder(){
        $this->addDataType(
            'icons',
            'icons',
            'Ícone',
            'Ícones',
            'Viaativa\\Viaroot\\Models\\Icon',
            null,
            'Viaativa\\Viaroot\\Http\\Controllers\\ModuleIconsController'
        );
    }


}
