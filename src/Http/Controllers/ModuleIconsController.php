<?php

namespace Viaativa\Viaroot\Http\Controllers;

use Illuminate\Http\Request;
use Viaativa\Viaroot\Classes\ImportIcons;
use Viaativa\Viaroot\Models\Icon;
use Viaativa\Viaroot\Http\Controllers\Voyager\VoyagerBaseController;

class ModuleIconsController extends VoyagerBaseController
{

    function store(Request $request){

        $this->authorize('add', app(Icon::class));

        if($request->hasFile('zip')){
            $zipFile = $request->file('zip');
            if($zipFile->getMimeType() == "application/zip"){
                $importIcons = new ImportIcons($request->get('name'), $zipFile);
                $importIcons->import();
            }
        }
        return redirect(route('voyager.icons.index'));
    }

    function edit(Request $request, $id)
    {
        return abort(403);
    }

    function destroy(Request $request, $id)
    {

        $this->authorize('delete', app(Icon::class));

        $ids = [];
        if (empty($id)) {
            $ids = explode(',', $request->ids);
        } else {
            $ids[] = $id;
        }
        foreach($ids as $id) {
            $icon = Icon::where('id', $id)->first();
            if($icon) {
                $pubPath = public_path("icons/{$icon->slug}");
                if (is_dir($pubPath)) {
                    $items = array_slice(scandir($pubPath), 2);
                    foreach ($items as $item) {
                        @unlink("{$pubPath}/{$item}");
                    }
                    @rmdir($pubPath);
                }
                Icon::destroy($id);
            }
        }
        return redirect(route('voyager.icons.index'));
    }

}
