<?php
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use Viaativa\Viaroot\Models\MenuItem;
use Viaativa\Viaroot\Traits\DatabaseDataType;
use Viaativa\Viaroot\Traits\DatabaseDataRow;
use Viaativa\Viaroot\Traits\DatabaseDataRowRelationship;
use Viaativa\Viaroot\Traits\DatabaseMenus;
use Viaativa\Viaroot\Traits\DatabasePermissions;

class MenuSeeder extends Seeder
{
    use DatabaseDataType;
    use DatabaseDataRow, DatabaseDataRowRelationship;
    use DatabaseMenus;
    use DatabasePermissions;

    public function run()
    {
        $modulesMenu = MenuItem::where('title','Modulos')->first()->id;
        MenuItem::where('parent_id',$modulesMenu)->delete();
        $modulesMenu = MenuItem::where('title','Modulos')->delete();
        $items = DataType::whereNotIn('name',["","","page_categories","enquiries","inputs","forms","settings_formfields",'users','menus','roles','categories','posts','pages','blog_posts','page_blocks','fonts'])->get();
        $this->useAdminAsParentMenu();
        $this->addMenuItem('Modulos', 'voyager-browser', 'no-route', '', '#2bb7d6',5, json_encode([1]));
        foreach($items as $item)
        {
            if(isset($item->icon) and strlen($item->icon))
            {
                $icon = $item->icon ;
            }
            else {
                $icon = 'voyager-browser';
            }
            $this->addChildMenuItem($item->display_name_plural, 'voyager-list',"voyager.".str_replace("_","-",$item->slug).".index");
        }


    }

}