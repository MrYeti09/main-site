<?php

namespace Viaativa\Viaroot\Traits;

use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

trait DatabaseMenus
{
    protected $menuOrder = 1;
    protected $parentMenu;
    protected $parentItem;

    protected function useAdminAsParentMenu(){
        $this->defineParentMenu('admin');
    }

    protected function defineParentMenu($name){
        $this->parentMenu = $this->getMenu($name);
    }

    protected function getMenu($name)
    {
        Menu::firstOrCreate([
            'name' => $name,
        ]);

        return Menu::where('name', $name)->firstOrFail();
    }

    protected function addMenuItem($title, $icon = "voyager-list", $route = null, $url = '',$color = null, $order = 5, $permissions = null)
    {
        $parentItem = MenuItem::firstOrNew([
            'menu_id' => $this->parentMenu->id,
            'title' => $title,
            'url' => $url,
            'route' => $route,
            'permissions' => $permissions
        ]);

        if (!$parentItem->exists) {
            $parentItem->fill([
                'target' => '_self',
                'icon_class' => $icon,
                'color' => $color,
                'parent_id' => null,
                'order' => $order,
                'permissions' => $permissions
            ])->save();
        }

        $this->parentItem = $parentItem;
        return $parentItem;
    }

    protected function addChildMenuItem($title,  $icon = "voyager-list", $route,$color = null, $routeDefault = false, $url = '', $parentItem = null)
    {
        $parentItem = $parentItem ? $parentItem : $this->parentItem;
        if ($parentItem) {
            MenuItem::firstOrNew([
                'menu_id' => $this->parentMenu->id,
                'title' => $title,
                'url' => $url,
                'route' => $routeDefault ? "voyager.{$route}.index" : $route,
                'target' => '_self',
                'icon_class' => $icon,
                'color' => $color,
                'parent_id' => $this->parentItem->id,
                'order' => $this->menuOrder,
            ])->save();
            $this->menuOrder++;
        }
    }
}
