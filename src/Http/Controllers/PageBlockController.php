<?php

namespace Viaativa\Viaroot\Http\Controllers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use ScssPhp\ScssPhp\Compiler;
use Viaativa\Viaroot\Models\Layout;
use Viaativa\Viaroot\Models\Page;
use Viaativa\Viaroot\Models\PageBlock;
use Pvtl\VoyagerPageBlocks\Traits\Blocks;
use Pvtl\VoyagerPageBlocks\Validators\BlockValidators;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Viaativa\Viaroot\Models\Template;
use WebPConvert\WebPConvert;

class PageBlockController extends VoyagerBaseController
{

    use Blocks;

    public function load_block_form(Request $request)
    {
//        return $request->all();
        $block = PageBlock::where('id',$request->block)->first();
        $template = $block->template();
        $blockData = $block->data;
        return view('viaativa-voyager::page-blocks.partials.page-block-form',['template' => $template,'block' => $block, 'blockData' => $blockData,'dataTypeContent' => $blockData]);
    }

    public function SaveSass()
    {
        $stringResult = "";
        foreach(PageBlock::all() as $block) {
            $stringResult .= PageBlockController::renderSassKwatch($block);
        }
        $scss = new Compiler();
        $scss->setFormatter('ScssPhp\ScssPhp\Formatter\Compressed');
        $resultScss = $scss->compile($stringResult);
        file_put_contents(base_path().'/public/css/blocks.css', $resultScss);
    }


    static function renderSassKwatch($block = null)
    {
        \View::addExtension('sasskwatch','blade');
        \View::addExtension('swss','blade');
        \View::addExtension('skwt','blade');
        if($block != null) {
            $blockData = $block->data;
            $template = $block->template();
            if(isset($template) and property_exists($template,'sass')) {
                $path = $template->sass;
                if (\View::exists($path)) {
                    //$blockData = BlockTypesData::filter_template(config('page-blocks')[explode('::',$block->template()->path)[0]],$blockData);
                    $render = view($path , ['blockData' => $blockData]);
                    return ".block-{$block->id} { " . $render . "}\n\r";
                }
            }
        }
    }


    public function index(Request $request)
    {

        return redirect('/admin/pages');
    }

    /**
     * POST B(R)EAD - Read data.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return View
     */
    public function edit(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        return view('viaativa-voyager::page-blocks.edit-add', [
            'page' => $page,
            'pageBlocks' => $page->blocks->sortBy('order'),
        ]);
    }


    public function remove(Request $request)
    {
        $block = PageBlock::findOrFail($request->blockid);
        $temp = $block->data;
        $temp->{$request->id} = '';
        $block->data = $temp;
        $block->save();
        return redirect()
            ->to(URL::previous() . "#block-id-" . $request->blockid)
            ->with([
                'message' => __('voyager::generic.successfully_updated'),
                'alert-type' => 'success',
            ]);
    }


    public function add_main(Request $request)
    {

        $block = new PageBlock();
        $block->page_id = "-1";
        $block->type = "template";
        $block->path = $request->id;
        $block->data = [];
        $block->save();
        return redirect()->back();
    }

    /**
     * POST BR(E)AD - Edit data.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    static function check_base64_image($base64) {
        try {
            $img = @imagecreatefromstring(base64_decode($base64));
        } catch(Exception $e) {
            return false;
        }

        if (!$img) {
            return false;
        }

        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');

        unlink('tmp.png');

        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }

        return false;
    }

    public function update(Request $request, $id)
    {
        if ($id == "main") {
            $id = -1;
        }

        $block = PageBlock::findOrFail($id);


        $template = $block->template();
        $dataType = Voyager::model('DataType')->where('slug', '=', 'page-blocks')->first();
//        if($id == 9)

        $data_custom = $request->all();

        $has_slug = false;
        {
            foreach ($block->data as $key => $item) {
                if ($key == "custom_id") {
                    $has_slug = true;
                }
                if (gettype($item) == "array") {
                    $item = implode(',', $item);
                    $test = (array)$block->data;
                    $test[$key] = $item;
                    $block->data = (object)$test;
                }


            }
        }
        // Get all block data & validate
        $data = [];
        if(is_object($block->data)) {
            $data = (array)$block->data;
        }
        foreach ($template->fields as $row) {

            if (property_exists($row, 'child')) {
                foreach ($row->child as $key => $child) {
                    $data[$child->field] = $request->{$child->field};
                }
            }
            $existingData = $block->data;
            if ($row->partial === 'voyager::formfields.croppable_image' || $row->partial === 'voyager::formfields.image' || $row->partial === 'voyager::formfields.multiple_images' || $row->partial === 'voyager::formfields.icon') {

                if (is_string($request->{$row->field})) {
                    //dd($request->all());
                    $image = base64_decode($request->{$row->field});  // your base64 encoded

                    $image = str_replace('data:image/png;base64,', '', $image);
                    if(PageBlockController::check_base64_image($image)) {

                    $image = str_replace(' ', '+', $image);
                    $imageName = str_random(20) . '.' . 'png';
                    if(PageBlockController::check_base64_image($image)) {
                        \File::put(storage_path() . '/app/public/blocks/' . $imageName, base64_decode($image));
                    } else {
                    }

                    $data[$row->field] = 'blocks/' . $imageName;
                    }
                } else {


                    if (is_null($request->file($row->field))) {

                        if (isset($existingData->{$row->field})) {
                            $data[$row->field] = $existingData->{$row->field};
                        }

                        continue;
                    } else {

                        $data[$row->field] = $request->file($row->field);
                    }
                }

            } else {
                $data[$row->field] = $request->input($row->field);
            }
            if($row->partial === 'voyager::formfields.file')
            {
                if($request->{$row->field} == null) {
                    $data[$row->field] = $existingData->{$row->field};
                }
            }
        }

        foreach($request->all() as $key => $item)
        {
            if(substr($key,0,6) == "cloned") {
                $data[$key] = $item;
            }
        }

        // Just.Do.It! (Nike, TM)
        $validator = BlockValidators::validateBlock($request, $block);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'message' => __('voyager::json.validation_errors'),
                    'alert-type' => 'error',
                ]);
        }
        foreach ($data as $key => $req) {
            if (is_array($req)) {
                $data[$key] = implode(',', $req);
            }
        }
        //dd($data);
        $data = $this->uploadImages($request, $data);
        if ($has_slug) {
            $data['custom_id'] = $block->data->custom_id;
        }
//        PageBlock::where('id',$id)->update(["data" => json_encode($data)]);
        $block->data = $data;

        //dd($block);

        $block->path = $block->type === 'include' ? $request->input('path') : $block->path;
        $block->is_hidden = $request->has('is_hidden');
        $block->is_delete_denied = $request->has('is_delete_denied');
        $block->cache_ttl = $request->input('cache_ttl');
        $block->save();

        $this->saveSass();

        return redirect()
            ->to(URL::previous() . "#block-id-" . $id)
            ->with([
                'message' => __('voyager::generic.successfully_updated') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }


    public function add_tab($id,Request $request)
    {
        $block = PageBlock::where('id',$id)->first();
        $clones = [];
        if(strlen($block->clones)) {
            $clones = json_decode($block->clones);
            array_push($clones,max($clones)+1);
        } else {
            array_push($clones,0);
        }
        $block->clones = json_encode($clones);
        $block->save();
        return redirect()->back()->with([
            'message' => __('voyager::generic.successfully_updated'),
            'alert-type' => 'success',
        ]);
    }

    public function remove_tab($id,$tab_id,Request $request)
    {
        $block = PageBlock::where('id',$id)->firstOrFail();
        $clones = [];
        if(strlen($block->clones)) {
            $clones = (array)json_decode($block->clones);
        }
        unset($clones[array_search($tab_id,$clones)]);
        $block->clones = json_encode($clones);
        $block->save();
        return redirect()->back()->with([
            'message' => __('voyager::generic.successfully_updated'),
            'alert-type' => 'success',
        ]);

    }


    /**
     * POST - Order data.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function sort(Request $request)
    {
        $blockIds = json_decode($request->input('ids'));
        foreach ($blockIds as $index => $blockId){
            PageBlock::where('id', $blockId)->update(['order' => ($index+1)]);
        }
    }

    public function sort_page(Request $request)
    {

        $order = json_decode($request->input('order'));
        foreach ($order as $index => $item) {
            $page = Page::findOrFail($item->pageid);
            $page->order = $item->order;
            $page->save();
        }
        return $order;
    }



    public function load_block(Request $request)
    {
        return view('page-block-form')->render();
    }

    /**
     * POST - Minimize Block
     *
     * @param \Illuminate\Http\Request $request
     */
    public function minimize(Request $request)
    {
        $block = PageBlock::findOrFail((int)$request->id);
        $block->is_minimized = (int)$request->is_minimized;
        $block->save();
    }

    /**
     * POST - Change Page Layout
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id - the page id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLayout(Request $request, $id)
    {
        $page = Page::findOrFail((int)$id);
        $page->layout = $request->layout;
        $page->save();

        return redirect()
            ->back()
            ->with([
                'message' => __('voyager::generic.successfully_updated') . " Page Layout",
                'alert-type' => 'success',
            ]);
    }

    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
//            return $request->all();

        $page = Page::findOrFail($request->input('page_id'));

        $dataType = Voyager::model('DataType')->where('slug', '=', 'page-blocks')->first();
        if($request->input('type') != null) {
            if ($request->input('type') === 'include') {
                $type = $request->input('type');
                $path = '\Pvtl\VoyagerFrontend\Http\Controllers\PostController::recentBlogPosts()';
            } else {
                list($type, $path) = explode('|', $request->input('type'));
            }


            $order = PageBlock::where('page_id',$request->input('page_id'))->orderByDesc('order')->first();
            if($order != null)
            {
                $order = $order->order+1;
            } else
            {
                $order = 0;
            }


            $block = new PageBlock();
            $block->page_id = $request->input('page_id');
            $block->type = $type;
            $block->path = $path;
            $block->data = $type === 'include' ? '' : $this->generatePlaceholders($request);
            $block->order = $order;
            $block->save();

            $this->saveSass();


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

    /**
     * DELETE BREA(D) - Delete data.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $block = PageBlock::findOrFail($id);
        $dataType = Voyager::model('DataType')->where('slug', '=', 'page-blocks')->first();

        try {
            $block->delete();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with([
                    'message' => "Unable to delete {$dataType->display_name_singular}",
                    'alert-type' => 'error',
                ]);
        }

        return redirect()
            ->back()
            ->with([
                'message' => __('voyager::generic.successfully_deleted') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }

    public function duplicate(Request $request)
    {
        (PageBlock::where('id', $request->input('id'))->first())->replicate()->save();
        return redirect()->back();
    }

    public function duplicate_to(Request $request)
    {
        if($request->has('id') and is_array(json_decode($request->input('id'))))
        {
            $blocks = PageBlock::whereIn('id', json_decode($request->input('id')))->get();
            $page = PageBlock::where('page_id',$request->target_page)->orderByDesc('order')->first();
            if($page != null)
            {
                $maxOrder = $page->order;
            } else
            {
                $maxOrder = 0;
            }
            foreach($blocks as $key => $block)
            {
                $pg = $block->replicate();
                $pg->order = $maxOrder+$pg->order;
                $pg->page_id = $request->target_page;
                $pg->save();
            }


        } else {
            $pg = (PageBlock::where('id', $request->input('id'))->first())->replicate();

            $pg->page_id = $request->target_page;

            $pg->save();
        }
        return redirect()->back();
    }

    public function duplicate_item_to(Request $request)
    {
        $pg = PageBlock::where('id', $request->input('id'))->first();
        $custom_data = $pg->data;
        foreach ($pg->data as $key => $dt) {
            if (substr($key, 0, 4) == "item") {
                if (substr($key, 5, 1) == $request->from_item) {
                    $var = substr($key, 7);
                    $custom_data->{'item_' . $request->target_item . '_' . $var} = $dt;
                }
            }
        }
        $pg->data = $custom_data;
        $pg->save();
        return redirect()->back()->with([
            'message' => __('voyager::generic.successfully_updated') . "",
            'alert-type' => 'success',
        ]);
    }

    public function mainsettings()
    {

        $blocks = PageBlock::where('page_id', -1)->get();
        return view('viaativa-voyager::page-blocks.edit-add-main', ["pageBlocks" => $blocks]);
    }

    public function add_template($template = null,$page,Request $request)
    {
        if($template != null)
        {
            $template = Template::where('id',$template)->first();
        }
        if($page != null)
        {
            $targetPage = Page::where('id',$page)->first();
        }
        $lastItem = PageBlock::where('page_id',$page)->orderByDesc('order')->first();
        if($lastItem != null)
        {
            $maxOrder = $lastItem->order;
        } else {
            $maxOrder = 0;
        }
        if(is_array($template->data) and sizeof($template->data))
        {
            foreach($template->data as $key => $dataBlock)
            {
                $temporaryData = $dataBlock;
                $temporaryData->order = ($temporaryData->order) + $maxOrder;
                $block = new PageBlock();
                foreach ($temporaryData as $key => $data) {
                    $block->{$key} = $data;
                }
                $block->page_id = $page;
                $block->save();
            }
        } else {
            $temporaryData = $template->data;
            $temporaryData->order = 1 + $maxOrder;
            $block = new PageBlock();
            foreach ($temporaryData as $key => $data) {
                $block->{$key} = $data;
            }
            $block->page_id = $page;
            $block->save();
        }
        return redirect()->back();
    }


    public function delete_blocks(Request $request) {
        if($request->has('blocks'))
        {
            PageBlock::whereIn('id',$request->blocks)->delete();
        }
    }

    public function edit_template_name(Request $request) {
        $template = Template::where('id',$request->id)->update(['name' => $request->name]);
        return "oi";
    }

    public function remove_template(Request $request) {
        if($request->has('id'))
        {
            Template::where('id',$request->id)->delete();
            return "Success";
        } else {
            return "Error";
        }
    }

    public function create_layout(Request $request)
    {
        if($request->has('page_id') and strlen($request->page_id))
        {
            $page = Page::where('id',$request->page_id)->first();
            $blocks = $page->blocks()->get();
            foreach($blocks as $block)
            {
                unset($block->id);
                unset($block->updated_at);
                unset($block->created_at);
            }
            $template = new Template();
            $template->name = $request->layout_name;
            $template->data = json_encode($blocks);
            $template->save();
        } elseif($request->has('block_id') and strlen($request->block_id)) {
            if(is_array(json_decode($request->block_id)))
            {
                $blocks = PageBlock::whereIn('id', json_decode($request->block_id))->get();
                foreach($blocks as $block) {
                    unset($block->id);
                    unset($block->updated_at);
                    unset($block->created_at);
                }
                $template = new Template();
                $template->name = $request->layout_name;
                $template->data = json_encode($blocks);
                $template->save();
            } else {
                $block = PageBlock::where('id', $request->block_id)->first();
                unset($block->id);
                unset($block->updated_at);
                unset($block->created_at);
                $template = new Template();
                $template->name = $request->layout_name;
                $template->data = json_encode($block);
                $template->save();
            }
        }
        return redirect()->back();
    }

    public function custom(Request $request)
    {
        $block = PageBlock::where('id', $request->block_id)->first();
        $customData = $block->data;
        $extra = (object)[];
        if ($request->id != null) {
            $customData->custom_id = $request->id;
        }
        if ($request->custom_name != null) {
            $extra->name = $request->custom_name;
        }

        if ($request->custom_color != null) {
            if ($request->custom_color != "#000000") {
                $extra->color = $request->custom_color;
            }
        }
        if ($request->large != null) {
                $extra->large = $request->large;
        }
        if ($request->medium != null) {
            $extra->medium = $request->medium;
        }
        if ($request->small != null) {
            $extra->small = $request->small;
        }

        if ($request->fluid != null) {
            $extra->fluid = $request->fluid;
        } else {
            $extra->fluid = "off";
        }

        if ($request->padding != null) {
            $extra->padding = $request->padding;
        } else {
            $extra->padding = "off";
        }

        if ($request->mobile != null) {
            $extra->mobile = $request->mobile;
        } else {
            $extra->mobile = "off";
        }

        $block->extra = json_encode($extra);
        $block->data = $customData;
        $block->save();
        return redirect()->back()->with([
            'message' => __('voyager::generic.successfully_updated') . " Custom Slug",
            'alert-type' => 'success',
        ]);
    }

    public function editmodal($id,Request $request)
    {
        $block = PageBlock::where('id', $id)->first();
        $template = $block->template();
        //return $request->all();
        return view('viaativa-voyager::page-blocks.modal', ['block' => $block, 'template' => $template])->render();
    }


    public function settings_modal(Request $request)
    {
        //$block = PageBlock::where('id',$request->blockid)->firstOrFail();
        //$template = $block->template();
        //return $request->all();
        $key = $request->key;
        return view('viaativa-voyager::page-blocks.settings-modal', ['row' => $key])->render();
    }
}
