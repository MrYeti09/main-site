@php
    $blocks = \Viaativa\Viaroot\Models\PageBlock::where('page_id','-1')->get();
    $templates = config('page-blocks');
    $blockData = [];
    $template = null;
    foreach($blocks as $block)
    {
        if(isset($templates[$block->path]) and array_key_exists('type',$templates[$block->path]))
        {
            if($templates[$block->path]['type'] == 'header')
            {

            $template = $templates[$block->path];
                $blockData = $block->data;

            }
        }
    }
@endphp

@if($template != null)
@include($template['template'],['blockData' => $blockData,'template' => $template])
@endif