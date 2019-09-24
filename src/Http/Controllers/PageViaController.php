<?php

namespace Viaativa\Viaroot\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Events\BreadDataUpdated;
use Viaativa\Viaroot\Models\Municipio;
use Viaativa\Viaroot\Models\PageBlock;
use Viaativa\Viaroot\Models\Page;
use Viaativa\Viaroot\Models\PageCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\View;
use Viaativa\Viaroot\Traits\Blocks;
use Illuminate\Support\Facades\Config;
use Pvtl\VoyagerFrontend\Helpers\BladeCompiler;
use Illuminate\Http\Request;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Facades\Voyager;
use Viaativa\Viaroot\Http\Controllers\Voyager\VoyagerBaseController as BaseController;


class PageViaController extends BaseController
{
    use Blocks;


    protected $avoidHtmlFromString = [];

    public function index(Request $request)
    {
        return redirect(route('voyager.page-categories.index'));
    }

    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        //dd($dataType);
        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;
        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
//        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $category_id = $request->get('page_category_id');
        $categories = PageCategory::all();
        foreach ($categories as $category) {
            if (Page::where('id', '<>', $id)->where('page_category_id', $category_id)->where('slug', $request->slug)->exists()) {
                return redirect()->back()->withErrors(['message' => 'Slug não pode ser duplicado!'])->withInput();
            }
        }
        $this->insertUpdateDataAdvanced($request, $slug, $dataType->editRows, $data);

        if($request->has('page_category_id')) {
            $data->update(['page_category_id' => $request->input('page_category_id')]);
        }
        event(new BreadDataUpdated($dataType, $data));

        if (Auth::user()->can('browse_' . $dataType->slug)) {
            return redirect()
                ->back()
                ->with([
                    'message' => __('voyager::generic.successfully_updated') . " {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        } else {
            return redirect()->back()->with([
                'message' => __('voyager::generic.successfully_updated') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
        }
    }


    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
//        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();

//        dd($request->all());

        $category_id = $request->get('page_category_id');
        $categories = PageCategory::all();
        foreach ($categories as $category) {
            if (Page::where('page_category_id', $category_id)->where('slug', $request->slug)->exists()) {
                return redirect()->back()->withErrors(['message' => 'Slug não pode ser duplicado!'])->withInput();
            }
        }
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
        if($request->has('page_category_id')) {
            $data->update(['page_category_id' => $request->input('page_category_id')]);
        }

        $item = event(new BreadDataAdded($dataType, $data));

        if (isset($data->page_category_id)) {
            if (PageCategory::where('id', $data->page_category_id)->first() != null) {
                return redirect(route("voyager.page-blocks.edit", ["page_block" => $data->id]))
                    ->with([
                        'message' => "Página criada com sucesso!",
                        'alert-type' => 'success',
                    ]);
            } else {
                return redirect()
                    ->route("voyager.page-blocks.edit", ["page_block" => $data->id])
                    ->with([
                        'message' => "Página criada com sucesso!",
                        'alert-type' => 'success',
                    ]);
            }
        } else {
            return redirect()
                ->route("voyager.page-blocks.edit", ["page_block" => $data->id])
                ->with([
                    'message' => "Página criada com sucesso!",
                    'alert-type' => 'success',
                ]);
        }
    }

    static function getPageInfo($slug = 'home')
    {

        $page = Page::where('slug', '=', $slug)->firstOrFail();


        $blocks = $page->blocks()
            ->where('is_hidden', '=', '0')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($block) {
                if ($block->template() != null) {
                    return (object)[
                        'id' => $block->id,
                        'page_id' => $block->page_id,
                        'updated_at' => $block->updated_at,
                        'cache_ttl' => $block->cache_ttl,
                        'template' => $block->template()->template,
                        'data' => $block->cachedData,
                        'path' => $block->path,
                        'type' => $block->type,
                        'template_raw' => $block->template()
                    ];
                }
            });

        //dd($block->template());
        // Override standard body content, with page block content

        // Return the full page
        return $blocks;
    }

    static function printBlogView($block, $extra = [])
    {
        $templateKey = $block->path;
        $templateConfig = Config::get("page-blocks.$templateKey");

        foreach ((array)$templateConfig['fields'] as $fieldName => $fieldConfig) {
            if (!array_key_exists($fieldName, $block->data)) {
                $block->data->$fieldName = null;
            }
        }
        foreach ($block->data as $key => $data) {
            //verify is will avoid the html compiler
            if (gettype($data) == "array") {
                $data = implode(",", $data);
            }
            $block->data->$key = BladeCompiler::getHtmlFromString($data);
        }
        // Compile the Blade View to give us HTML output
        $addition_info = [
            'blockData' => $block->data,
            'blockId' => $block->id
        ];
        $addition_info = array_merge($addition_info, $extra);

        $blockModel = PageBlock::where('id', $block->id)->first();
        if (View::exists($blockModel->template()->template)) {

            $block->html = View::make($blockModel->template()->template, $addition_info)->render();
        }
        //return $block->html;

        $extra = (array)json_decode($blockModel->extra);
        if (!isset($extra['small'])) {
            $extra['small'] = 12;
        }
        if (!isset($extra['medium'])) {
            $extra['medium'] = 12;
        }
        if (!isset($extra['large'])) {
            $extra['large'] = 12;
        }
        if (!isset($extra['fluid'])) {
            $extra['fluid'] = false;
        }

        $block->html = "<div data-block='$block->path' class='cell block-{$block->id} small-{$extra['small'] } medium-{$extra['medium']} large-{$extra['large']}'>" . $block->html . "</div>";

        return $block->html;

    }

    static function printView($block, $extra = [])
    {
        $templateKey = $block->path;
        $templateConfig = Config::get("page-blocks.$templateKey");

        foreach ((array)$templateConfig['fields'] as $fieldName => $fieldConfig) {
            if (!array_key_exists($fieldName, $block->data)) {
                $block->data->$fieldName = null;
            }
        }
        foreach ($block->data as $key => $data) {
            //verify is will avoid the html compiler
            if (gettype($data) == "array") {
                $data = implode(",", $data);
            }
            $block->data->$key = BladeCompiler::getHtmlFromString($data);
        }
        // Compile the Blade View to give us HTML output
        $addition_info = [
            'blockData' => $block->data,
            'blockId' => $block->id
        ];
        $addition_info = array_merge($addition_info, $extra);
        if (View::exists($block->template()->template)) {

            $block->html = View::make($block->template()->template, $addition_info)->render();
        }
        //return $block->html;

        $extra = (array)json_decode(PageBlock::where('id', $block->id)->first()->extra);
        if (!isset($extra['small'])) {
            $extra['small'] = 12;
        }
        if (!isset($extra['medium'])) {
            $extra['medium'] = 12;
        }
        if (!isset($extra['large'])) {
            $extra['large'] = 12;
        }
        if (!isset($extra['fluid'])) {
            $extra['fluid'] = false;
        }

        if (!isset($extra['mobile'])) {
            $extra['mobile'] = false;
        }

        $block->html = "<div data-block='$block->path' class='cell block-{$block->id} small-{$extra['small'] } medium-{$extra['medium']} large-{$extra['large']}'>" . $block->html . "</div>";

        return $block->html;

    }

    public function get_city(Request $request)
    {
        return Municipio::where('Uf', $request->uf)->get();
    }


    public function duplicate(Request $request)
    {
        $page = Page::find($request->page_id);
        $maxId = Page::orderBy('id', 'desc')->first()->id;
        $newPage = $page->replicate();
        $newPage->slug = $maxId + 1;
        $newPage->save();
        $blocks = PageBlock::where('page_id', $request->page_id)->get();
        foreach ($blocks as $key => $block) {
            $newBlock = $block->replicate();
            $newBlock->page_id = $newPage->id;
            $newBlock->save();
        }
        return redirect()->back();
    }


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

        $res = $data->destroy($ids);
        $data = $res
            ? [
                'message' => __('voyager::generic.successfully_deleted') . " {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message' => __('voyager::generic.error_deleting') . " {$displayName}",
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataDeleted($dataType, $data));
        }

        return redirect()->back()->with($data);
    }


    public function getPage($slug = null, $customData = null, Request $request = null)
    {

        if ($request == null) {
            $request = new \Illuminate\Http\Request();
        }
        $defaultSlug = config('website.default_slug');

        $slugs = explode('/', $slug);
        $category = null;
        if (sizeof($slugs) > 1) {
            $category = PageCategory::where('slug', $slugs[sizeof($slugs) - 2])->first();
        }


        if ($defaultSlug !== null && strlen($defaultSlug) && $slug == null) {
            $slugs = [$defaultSlug];
        }
        if($slug == "amp")
        {
            $slugs = ["home"];
        }
        if ($slug == null) {
            $slugs = ["home"];
        }
        if (isset($category)) {
            //dd($category,$slugs[sizeof($slugs) - 1]);
            $page = Page::where('slug', '=', $slugs[sizeof($slugs) - 1])->where('page_category_id', $category->id)->firstOrFail();
        } else {
            $pages = Page::where('slug', '=', $slugs[sizeof($slugs) - 1])->get();
            $allPages = [];
            foreach ($pages as $pageItem) {
                if (isset($pageItem->page_category_id) and PageCategory::where('id', $pageItem->page_category_id)->first()->slug == null) {
                    $page = $pageItem;
                    array_push($allPages, $page);
                } else if (!isset($pageItem->page_category_id)) {
                    $page = $pageItem;
                    array_push($allPages, $page);
                }
            }

            if (!isset($page)) {
                abort(404);
            }
        }
        if ($page->category() != null) {
            $blocksCategory = PageBlock::where('category_id', $page->category()->id)->get();
        } else {
            $blocksCategory = $page->blocks;
        }

        $blocksSorted = $blocksCategory->sortBy('order');
        $arrItems = $blocksSorted->toArray();
        $allOrders = collect([]);
        $quantity = sizeof($blocksSorted) + 1;
        if($page->category() == null)
        {
            $quantity = sizeof($blocksSorted);
        }
        for ($i = 1; $i <= $quantity; $i++) {
            $block = $blocksSorted->filter(function ($item) use ($i) {
                return $item->order == $i;
            })->first();
            if (!$block == null) {
                $desired_object = (object)[
                    'id' => $block->id,
                    'page_id' => $block->page_id,
                    'updated_at' => $block->updated_at,
                    'cache_ttl' => $block->cache_ttl,
                    'template' => $block->template()->template,
                    'data' => $block->cachedData,
                    'path' => $block->path,
                    'type' => $block->type,
                ];

                $allOrders->push($desired_object);
            } else {

                $blockinho = $page->blocks()
                    ->where('is_hidden', '=', '0')
                    ->orderBy('order', 'asc')
                    ->get()
                    ->map(function ($block) {
                        if ($block->template() != null) {
                            return (object)[
                                'id' => $block->id,
                                'page_id' => $block->page_id,
                                'updated_at' => $block->updated_at,
                                'cache_ttl' => $block->cache_ttl,
                                'template' => $block->template()->template,
                                'data' => $block->cachedData,
                                'path' => $block->path,
                                'type' => $block->type,
                            ];
                        } else {
                            return (object)[
                                'id' => $block->id,
                                'page_id' => $block->page_id,
                                'path' => $block->path,
                            ];
                        }
                    });
                foreach ($blockinho as $minibloco) {
                    $allOrders->push($minibloco);
                }

            }
        }
        $blocks = $allOrders;

        $page['body'] = view('voyager-page-blocks::default', [
            'page' => $page,
            'blocks' => $this->prepareEachBlock($blocks),
        ]);


        foreach ($page['body']['blocks'] as $key => $block) {
            if (isset($page['body']['blocks'][$key - 1]) and $key > 0) {
                $lastBlock = $page['body']['blocks'][$key - 1];
                if (($lastBlock->fluid != $block->fluid or !isset($lastBlock->fluid)) or ($lastBlock->padding != $block->padding)) {

                    if ($block->fluid == "on") {
                        $block->html = '</div></div><div class="grid-container full"><div class="grid-x">' . $block->html;
                    } else {
                        if ($block->padding == "on") {
                            $block->html = '</div></div><div class="grid-container" style="padding:0;"><div class="grid-x">' . $block->html;
                        } else {
                            $block->html = '</div></div><div class="grid-container"><div class="grid-x">' . $block->html;
                        }
                    }
                }
            } elseif ($key == 0) {

                if ($block->fluid == "on") {
                    $block->html = '</div></div><div class="grid-container full"><div class="grid-x">' . $block->html;
                } else {
                    if ($block->padding == "on") {
                        $block->html = '</div></div><div class="grid-container" style="padding:0;"><div class="grid-x">' . $block->html;
                    } else {
                        $block->html = '</div></div><div class="grid-container"><div class="grid-x">' . $block->html;
                    }
                }
            }
        }


        // Check that the page Layout and its View exists
        if (empty($page->layout) or $page->layout == null) {
            $page->layout = 'default';
        } else
            if (!View::exists("{$this->viewPath}::layouts.{$page->layout}")) {
                $page->layout = 'default';
            }


        // Return the full page
        if (isset($this->viewPath)) {
            return view("{$this->viewPath}::modules.pages.default", [
                'page' => $page,
                'layout' => $page->layout,
            ]);
        } else {

            return view("voyager-frontend::modules.pages.default", [
                'page' => $page,
                'layout' => $page->layout,
            ]);
        }
    }

    protected function prepareTemplateBlockTypes($block, $extras = [])
    {
        $templateKey = $block->path;
        $templateConfig = Config::get("page-blocks.$templateKey");
        // Ensure every key from config exists in collection
        foreach ((array)$templateConfig['fields'] as $fieldName => $fieldConfig) {
            if (!array_key_exists($fieldName, $block->data)) {
                if (!is_object($block->data)) {
                    $block->data = (object)$block->data;
                }

                $block->data->$fieldName = null;
            }
        }
        // Compile each piece of content from the DB, into HTML
        foreach ($block->data as $key => $data) {
            //verify is will avoid the html compiler
            if (!in_array($key, $this->avoidHtmlFromString)) {
                if (gettype($data) == "array") {
                    $data = implode(",", $data);
                }

                $block->data->$key = BladeCompiler::getHtmlFromString($data);
            }
        }
        // Compile the Blade View to give us HTML output
        $vars = [
            'blockData' => $block->data,
            'blockId' => $block->id
        ];
        $vars = array_merge($vars, $extras);
        if (View::exists($block->template)) {
            $block->html = View::make($block->template, $vars)->render();
        }
        if ((is_array($block->data) and sizeof($block->data)) or (is_object($block->data))) {
            if (property_exists($block->data, 'custom_id')) {
                if (array_key_exists('type', $templateConfig)) {
                    if ($templateConfig['type'] != 'modal') {
                        $block->html = "<div id='{$block->data->custom_id}'>" . $block->html . "</div>";
                    }
                } else {
                    $block->html = "<div id='{$block->data->custom_id}'>" . $block->html . "</div>";
                }
            }

            $extra = (array)json_decode(PageBlock::where('id', $block->id)->first()->extra);
            if (!isset($extra['small'])) {
                $extra['small'] = 12;
            }
            if (!isset($extra['medium'])) {
                $extra['medium'] = 12;
            }
            if (!isset($extra['large'])) {
                $extra['large'] = 12;
            }
            if (!isset($extra['fluid'])) {
                $extra['fluid'] = "on";
            } elseif ($extra['fluid'] == "off") {
            }

            if (!isset($extra['padding'])) {
                $extra['padding'] = "on";
            } elseif ($extra['padding'] == "off") {
            }

            if (!isset($extra['mobile'])) {
                $extra['mobile'] = "on";
            } elseif ($extra['mobile'] == "off") {
            }

            $block->small = $extra['small'];
            $block->medium = $extra['medium'];
            $block->large = $extra['large'];
            $block->fluid = $extra['fluid'];
            $block->mobile = $extra['mobile'];
            $block->padding = $extra['padding'];
            $block->html = "<div data-block='$block->path' class='cell block-{$block->id} small-{$extra['small'] } medium-{$extra['medium']} large-{$extra['large']}'>" . $block->html . "</div>";
            $useragent=$_SERVER['HTTP_USER_AGENT'];
//            dump($block->mobile,$useragent);
            if($block->mobile == "off" and preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            {
                $block->html = "<div data-block='$block->path'></div>";
            }
        }
        return $block;
    }
}
