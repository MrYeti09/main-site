<?php

namespace Viaativa\Viaroot\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as BaseVoyagerMenuController;
use Viaativa\Viaroot\Models\MenuItem;

class VoyagerMenuController extends BaseVoyagerMenuController
{
    public function builder($id)
    {
        $menu = Voyager::model('Menu')->findOrFail($id);

        $this->authorize('edit', $menu);

        $isModelTranslatable = is_bread_translatable(Voyager::model('MenuItem'));

        return Voyager::view('viaativa-voyager::menus.builder', compact('menu', 'isModelTranslatable'));
    }

    public function delete_menu($menu, $id)
    {
        $item = Voyager::model('MenuItem')->findOrFail($id);

        $this->authorize('delete', $item);

        $item->deleteAttributeTranslation('title');

        $item->destroy($id);

        return redirect()
            ->back()
            ->with([
                'message'    => __('voyager::menu_builder.successfully_deleted'),
                'alert-type' => 'success',
            ]);
    }


    public function update_item(Request $request)
    {

        $id = $request->input('id');
        $data = $this->prepareParameters(
            $request->except(['id'])
        );

        if(isset($request->description))
        {
            $data['description'] = $request->description;
        }

        if ($request->hasFile('img')) {
            $image      = $request->file('img');
            $image->storePublicly("public/menu-builder/");
            $data['img'] = $image->hashName();
        }

        if ($request->hasFile('img_hover')) {
            $image      = $request->file('img_hover');
            $image->storePublicly("public/menu-builder/");
            $data['img_hover'] = $image->hashName();
        }


        $menuItem = Voyager::model('MenuItem')->findOrFail($id);


        $this->authorize('edit', $menuItem->menu);

        if(isset($request->permissions))
        {
            $data['permissions'] = json_encode($request->permissions);
        } else {
            $data['permissions'] = null;
        }

        if (is_bread_translatable($menuItem)) {
            $trans = $this->prepareMenuTranslations($data);

            // Save menu translations
            $menuItem->setAttributeTranslations('title', $trans, true);
        }
        $menuItem->update($data);

        return redirect()
            ->back()
            ->with([
                'message'    => __('voyager::menu_builder.successfully_updated'),
                'alert-type' => 'success',
            ]);
    }

    public function order_item(Request $request)
    {
        $menuItemOrder = json_decode($request->input('order'));

        $this->orderMenu($menuItemOrder, null);
    }

    public function order(Request $request)
    {
        $menuItemOrder = json_decode($request->input('order'));
        \Artisan::call('cache:clear');
        return $this->orderMenu($menuItemOrder, null);
    }

    private function orderMenu(array $menuItems, $parentId)
    {
        foreach ($menuItems as $index => $menuItem) {
//            $item = Voyager::model('MenuItem')->findOrFail($menuItem->id);
            Voyager::model('MenuItem')->where('id',$menuItem->id)->update(['order' => $index + 1,'parent_id' => $parentId]);
//            $item->order = $index + 1;
//            $item->parent_id = $parentId;
//            $item->save();

            if (isset($menuItem->children)) {
                $this->orderMenu($menuItem->children, $menuItem->id);
            }
        }
        //return $menuItems;
    }

    public function add_item(Request $request)
    {
        $menu = Voyager::model('Menu');

        $this->authorize('add', $menu);

        $data = $this->prepareParameters(
            $request->all()
        );

        if(isset($request->description))
        {
            $data['description'] = $request->description;
        }

        if ($request->hasFile('img')) {
            $image      = $request->file('img');
            $image->storePublicly("public/menu-builder/");
            $data['img'] = $image->hashName();
        }

        if ($request->hasFile('img_hover')) {
            $image      = $request->file('img_hover');
            $image->storePublicly("public/menu-builder/");
            $data['img_hover'] = $image->hashName();
        }

        unset($data['id']);
        $data['order'] = Voyager::model('MenuItem')->highestOrderMenuItem();

        // Check if is translatable
        $_isTranslatable = is_bread_translatable(Voyager::model('MenuItem'));
        if ($_isTranslatable) {
            // Prepare data before saving the menu
            $trans = $this->prepareMenuTranslations($data);
        }

        $menuItem = Voyager::model('MenuItem')->create($data);

        // Save menu translations
        if ($_isTranslatable) {
            $menuItem->setAttributeTranslations('title', $trans, true);
        }

        return redirect()
            ->back()
            ->with([
                'message'    => __('voyager::menu_builder.successfully_created'),
                'alert-type' => 'success',
            ]);
    }

    public function update_item_advanced(Request $request)
    {




        $id = $request->input('id');
        $data = $this->prepareParameters(
            $request->except(['id'])
        );

        if(isset($request->description))
        {
            $data['description'] = $request->description;
        }

        if ($request->hasFile('img')) {
            $image      = $request->file('img');
            $image->storePublicly("public/menu-builder/");
            $data['img'] = $image->hashName();
        }

        if ($request->hasFile('img_hover')) {
            $image      = $request->file('img_hover');
            $image->storePublicly("public/menu-builder/");
            $data['img_hover'] = $image->hashName();
        }


        $menuItem = Voyager::model('MenuItem')->findOrFail($id);


        $this->authorize('edit', $menuItem->menu);

        if(isset($request->permissions))
        {
            $data['permissions'] = json_encode($request->permissions);
        } else {
            $data['permissions'] = null;
        }

        if (is_bread_translatable($menuItem)) {
            $trans = $this->prepareMenuTranslations($data);

            // Save menu translations
            $menuItem->setAttributeTranslations('title', $trans, true);
        }
        $menuItem->update($data);

        return redirect()
            ->back()
            ->with([
                'message'    => __('voyager::menu_builder.successfully_updated'),
                'alert-type' => 'success',
            ]);
    }


    protected function prepareParameters($parameters)
    {
        switch (Arr::get($parameters, 'type')) {
            case 'route':
                $parameters['url'] = null;
                break;
            default:
                $parameters['route'] = null;
                $parameters['parameters'] = '';
                break;
        }

        if (isset($parameters['type'])) {
            unset($parameters['type']);
        }

        return $parameters;
    }

    /**
     * Prepare menu translations.
     *
     * @param array $data menu data
     *
     * @return JSON translated item
     */
    protected function prepareMenuTranslations(&$data)
    {
        $trans = json_decode($data['title_i18n'], true);

        // Set field value with the default locale
        $data['title'] = $trans[config('voyager.multilingual.default', 'en')];

        unset($data['title_i18n']);     // Remove hidden input holding translations
        unset($data['i18n_selector']);  // Remove language selector input radio

        return $trans;
    }
}
