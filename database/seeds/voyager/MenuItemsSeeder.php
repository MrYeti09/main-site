<?php

use Illuminate\Database\Seeder;
use Viaativa\Viaroot\Traits\DatabaseMenus;
use Viaativa\Viaroot\Models\MenuItem;

class MenuItemsSeeder extends Seeder
{
    use DatabaseMenus;

    public function run()
    {
        $this->iconSeeder();
    }


    private function iconSeeder(){
        $this->useAdminAsParentMenu();
        $this->parentItem = MenuItem::where('title', 'Configurações')->first();
        if($this->parentItem) {
            $this->addChildMenuItem('Ícones', 'voyager-wand', 'voyager.icons.index');
        }
    }
}
