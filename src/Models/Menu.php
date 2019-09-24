<?php

namespace Viaativa\Viaroot\Models;

use TCG\Voyager\Models\Menu as VoyagerMenu;
use TCG\Voyager\Events\MenuDisplay;
use Illuminate\Support\Str;

class Menu extends VoyagerMenu
{
    /**
     * Display menu.
     *
     * @param string      $menuName
     * @param string|null $type
     * @param array       $menuNameRender
     * @param array       $options
     *
     * @return string
     */
    public static function display($menuName, $type = null, array $menuNameRender = ["admin"], array $options = [])
    {
        // GET THE MENU - sort collection in blade
        $menu = \Cache::remember('voyager_menu_'.$menuName, \Carbon\Carbon::now()->addDays(30), function () use ($menuName) {
            return static::where('name', '=', $menuName)
                ->with(['parent_items.children' => function ($q) {
                    $q->orderBy('order');
                }])
                ->first();
        });

        // Check for Menu Existence
        if (!isset($menu)) {
            return false;
        }

        event(new MenuDisplay($menu));

        // Convert options array into object
        $options = (object) $options;
        //return $menu->parent_items;
        $items = $menu->parent_items->sortBy('order');

        if (in_array($menuName, $menuNameRender) && $type == '_json') {
            $items = static::processItems($items);
        }

        if ($type == 'admin') {
            $type = 'viaativa-voyager::menu.'.$type;
        } else {
            if (is_null($type)) {
                $type = 'voyager::menu.default';
            } elseif ($type == 'bootstrap' && !view()->exists($type)) {
                $type = 'voyager::menu.bootstrap';
            }
        }

        if (!isset($options->locale)) {
            $options->locale = app()->getLocale();
        }

        foreach($items as $key => $item)
        {
            if($item->permissions != null)
            {
                $item_permissions = json_decode($item->permissions);
                if(!in_array(\Illuminate\Support\Facades\Auth::user()->role->id,$item_permissions))
                {
                    unset($items[$key]);
                }
            } else {
            }
        }

        if ($type === '_json') {
            return $items;
        }



        return new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\View::make($type, ['items' => $items, 'options' => $options])->render()
        );
    }

    private static function processItems($items)
    {
        $items = $items->transform(function ($item) {
            // Translate title
            $item->title = $item->getTranslatedAttribute('title');
            // Resolve URL/Route
            $item->href = $item->link(true);

            if ($item->href == url()->current() && $item->href != '') {
                // The current URL is exactly the URL of the menu-item
                $item->active = true;
            } elseif (starts_with(url()->current(), Str::finish($item->href, '/'))) {
                // The current URL is "below" the menu-item URL. For example "admin/posts/1/edit" => "admin/posts"
                $item->active = true;
            }
            if (($item->href == url('') || $item->href == route('voyager.dashboard')) && $item->children->count() > 0) {
                // Exclude sub-menus
                $item->active = false;
            } elseif ($item->href == route('voyager.dashboard') && url()->current() != route('voyager.dashboard')) {
                // Exclude dashboard
                $item->active = false;
            }

            if ($item->children->count() > 0) {
                $item->setRelation('children', static::processItems($item->children));

                if (!$item->children->where('active', true)->isEmpty()) {
                    $item->active = true;
                }
            }

            return $item;
        });

        // Filter items by permission
        $items = $items->filter(function ($item) {
            return !$item->children->isEmpty() || app('VoyagerAuth')->user()->can('browse', $item);
        })->filter(function ($item) {
            // Filter out empty menu-items
            if ($item->url == '' && $item->route == '' && $item->children->count() == 0) {
                return false;
            }

            return true;
        });

        return $items->values();
    }
}
