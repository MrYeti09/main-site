<?php

namespace Viaativa\Viaroot\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController as BaseVoyagerBreadController;
use Viaativa\Viaroot\Models\PageBlock;

class VoyagerBreadController extends BaseVoyagerBreadController
{
    private function prepopulateBreadInfo($table)
    {
        $displayName = Str::singular(implode(' ', explode('_', Str::title($table))));
        $modelNamespace = config('voyager.models.namespace', app()->getNamespace());
        if (empty($modelNamespace)) {
            $modelNamespace = app()->getNamespace();
        }

        return [
            'isModelTranslatable'  => true,
            'table'                => $table,
            'slug'                 => Str::slug($table),
            'display_name'         => $displayName,
            'display_name_plural'  => Str::plural($displayName),
            'model_name'           => $modelNamespace.Str::studly(Str::singular($table)),
            'generate_permissions' => true,
            'server_side'          => false,
        ];
    }

    public function save_block(Request $request)
    {
        $blocks = PageBlock::where('path','like',$request->key."%")->get();
        foreach($blocks as $block)
        {
            $data = $block->data;
            foreach($request->all() as $key => $req)
            {
                if(!in_array($key,['_token','slug','key']))
                {
                    $data->{$key} = $req;
                }
            }
            $block->data = $data;
            $block->save();
        }
        return redirect()->back();
    }

    public function create(Request $request, $table)
    {
        $this->authorize('browse_bread');

        $dataType = Voyager::model('DataType')->whereName($table)->first();

        $data = $this->prepopulateBreadInfo($table);
        $data['fieldOptions'] = SchemaManager::describeTable((isset($dataType) && strlen($dataType->model_name) != 0)
            ? app($dataType->model_name)->getTable()
            : $table
        );

        return Voyager::view('viaativa-voyager::tools.bread.edit-add', $data);
    }
}
