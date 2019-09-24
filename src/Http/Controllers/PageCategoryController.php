<?php

namespace Viaativa\Viaroot\Http\Controllers;

use TCG\Voyager\Events\BreadDataDeleted;
use Viaativa\Viaroot\Models\PageCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Viaativa\Viaroot\Models\Page;
use Viaativa\Viaroot\Models\PageBlock;
use Pvtl\VoyagerPageBlocks\Traits\Blocks;
use Pvtl\VoyagerPageBlocks\Validators\BlockValidators;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use Viaativa\Viaroot\Http\Controllers\Voyager\VoyagerBaseController as BaseController;
use Viaativa\Viaroot\Models\Template;

class PageCategoryController extends BaseController
{

    use Blocks;


    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();


        // Check permission
        $this->authorize('delete', app($dataType->model_name));

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

            $model = app($dataType->model_name);
            if (!($model && in_array(SoftDeletes::class, class_uses($model)))) {
                $this->cleanup($dataType, $data);
            }
        }



        $displayName = count($ids) > 1 ? $dataType->display_name_plural : $dataType->display_name_singular;
        $data->pages()->update(['page_category_id' => null]);
        PageCategory::where('parent_id',$data->id)->update(['parent_id' => null]);
        foreach($data->pages()->get() as $page)
        {
            $related = Page::where('page_category_id',null)->where('slug',$page->slug)->first();
            if($related->exists() && $related != $page)
            {
                $page->update(['slug' => $page->slug."-".time()]);
            }
        }


        $res = $data->destroy($ids);
        $data = $res
            ? [
                'message'    => __('voyager::generic.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('voyager::generic.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataDeleted($dataType, $data));
        }

        return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
    }


    public function add_template($template = null,$page,Request $request)
    {
        if($template != null)
        {
            $template = Template::where('id',$template)->first();
        }
        $lastItem = PageBlock::where('category_id',$page)->orderByDesc('order')->first();
        if($lastItem != null)
        {
            $maxOrder = $lastItem->order+1;
        } else {
            $maxOrder = 1;
        }
        if(is_array($template->data) and sizeof($template->data))
        {
$count = 0;
            foreach($template->data as $key => $dataBlock)
            {

                $temporaryData = $dataBlock;
                $temporaryData->order = $maxOrder+$count;
                $block = new PageBlock();
                foreach ($temporaryData as $key => $data) {
                    $block->{$key} = $data;
                }
                $block->category_id = $page;
                $block->page_id = null;
                $block->save();
                $count += 1;
            }
        } else {
            $temporaryData = $template->data;
            $temporaryData->order = $maxOrder;
            $block = new PageBlock();
            foreach ($temporaryData as $key => $data) {
                $block->{$key} = $data;
            }
            $block->category_id = $page;
            $block->page_id = null;
            $block->save();
        }
        return redirect()->back();
    }

    public function store_block($id,Request $request) {

//            return $request->all();

        $page = PageCategory::findOrFail($request->input('page_id'));

        $dataType = Voyager::model('DataType')->where('slug', '=', 'page-blocks')->first();
        if($request->input('type') != null) {
            if ($request->input('type') === 'include') {
                $type = $request->input('type');
                $path = '\Pvtl\VoyagerFrontend\Http\Controllers\PostController::recentBlogPosts()';
            } else {
                list($type, $path) = explode('|', $request->input('type'));
            }

           // dd($page->blocks->orderByDesc('order')->first()+1);
            $orderBlock = $page->blocks->sortByDesc('order')->first();
            if($orderBlock == null)
            {
                $order = 1;
            } else
            {
                $order = $orderBlock->order+1;
            }

            $block = new PageBlock();
            $block->page_id = null;
            $block->type = $type;
            $block->path = $path;
            $block->category_id = $request->input('page_id');
            $block->data = $type === 'include' ? '' : $this->generatePlaceholders($request);
            $block->order = $order;
            $block->save();

            return view('viaativa-voyager::page-blocks.partials.page-blocks-sorting',['block' => $block,"template" => $block->template(),'page' => $page])->render();
            return redirect()
                ->route('voyager.page-blocks.edit', array($page->id, '#block-id-' . $block->id))
                ->with([
                    'message' => __('voyager::generic.successfully_added_new') . " {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        } else {

            return redirect()
                ->back()
                ->with([
                    'message' => "Erro ao adicionar bloco.",
                    'alert-type' => 'error',
                ]);
        }
    }


    public function edit_blocks($id = null,Request $request)
    {
        $page = PageCategory::where('id',$id)->first();
        return view('viaativa-voyager::page-categories.blocks.edit-add', [
            'page' => $page,
            'pageBlocks' => $page->blocks->sortBy('order'),
        ]);
    }

    public function show(Request $request, $id = null)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = "pages";

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
        $searchable = $dataType->server_side ? array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()) : '';
        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', null);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + 1;
            $orderColumn = [[$index, 'desc']];
            if (!$sortOrder && isset($dataType->order_direction)) {
                $sortOrder = $dataType->order_direction;
                $orderColumn = [[$index, $dataType->order_direction]];
            } else {
                $orderColumn = [[$index, 'desc']];
            }
        }

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $model->{$dataType->scope}();
            } else {
                $query = $model::select('*');
            }
            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses($model)) && app('VoyagerAuth')->user()->can('delete', app($dataType->model_name))) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
                $query->where($search->key, $search_filter, $search_value);
            }

            if ($orderBy && in_array($orderBy, $dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $dataTypeContent = call_user_func([
                    $query->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        if (($isModelTranslatable = is_bread_translatable($model))) {
            $dataTypeContent->load('translations');
        }

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        $view = 'voyager::bread.browse';

        if (view()->exists("viaativa-voyager::$slug.browse")) {
            if($this->getSlug($request) == "page-categories")
            {
                $view = "viaativa-voyager::$slug.browse-internal";
            } else {
                $view = "viaativa-voyager::$slug.browse";
            }

            $dataTypeContent = $model->where('page_category_id',$id)->get();
        }

        return Voyager::view($view, compact(
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortOrder',
            'searchable',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted'
        ));
    }

    /**
     * POST B(R)EAD - Read data.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return View
     */

    /**
     * POST - Order data.
     *
     * @param \Illuminate\Http\Request $request
     */

    public function delete($slug,Request $request)
    {
        $pages = Page::where('page_category_id',$slug)->update(['page_category_id' => null]);
        PageCategory::where('parent_id',$slug)->update(['parent_id' => null]);
        PageCategory::where('id',$slug)->delete();
        return redirect(route('voyager.page-categories.index'));
    }


    public function sort(Request $request)
    {

        $blockOrder = json_decode($request->input('order'));

        foreach ($blockOrder as $index => $item) {
            $block = PageBlock::findOrFail($item->id);
            $block->order = $index + 1;
            $block->save();
        }
    }

    public function sort_page(Request $request)
    {
        $order = json_decode($request->input('order'));
        foreach ($order as $index => $item) {
            $page = PageCategory::findOrFail($item->pageid);
            $page->order = $item->order;
            $page->save();
        }
    }
}
