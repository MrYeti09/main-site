<?php

namespace Viaativa\Viaroot\Http\Controllers;

use Viaativa\Viaroot\Models\Page;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Pvtl\VoyagerFrontend\Helpers\BladeCompiler;




class PageController extends \Pvtl\VoyagerPageBlocks\Http\Controllers\PageController
{
    protected $avoidHtmlFromString = [];

    protected function prepareTemplateBlockTypes($block)
    {
        $templateKey = $block->path;
        $templateConfig = Config::get("page-blocks.$templateKey");
        // Ensure every key from config exists in collection
        foreach ((array)$templateConfig['fields'] as $fieldName => $fieldConfig) {
            if (!array_key_exists($fieldName, $block->data)) {
                $block->data->$fieldName = null;
            }
        }
        // Compile each piece of content from the DB, into HTML
        foreach ($block->data as $key => $data) {
            //verify is will avoid the html compiler
            if (!in_array($key, $this->avoidHtmlFromString)) {
                $block->data->$key = BladeCompiler::getHtmlFromString($data);
            }
        }
        // Compile the Blade View to give us HTML output
        if (View::exists($block->template)) {
            $block->html = View::make($block->template, [
                'blockData' => $block->data,
                'mainBlockId' => $block->id
            ])->render();
        }
        return $block;
    }

    public function getPage($slug = 'home', $customData = null)
    {
        $page = Page::where('slug', '=', $slug)->firstOrFail();
        $this->tryUpdateMetaData($page, $customData);

        $blocks = $page->blocks()
            ->where('is_hidden', '=', '0')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($block) {
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
            });
        //added function to create a loop in customData
        if ($customData && is_array($customData)) {
            $blocks->map(function ($block) use ($customData) {
                //verify is $block is empty
                if (!$block->data) {
                    $block->data = new \stdClass();
                }
                foreach ($customData as $key => $data) {
                    $block->data->{$key} = $data;
                }
            });
        }
        // Override standard body content, with page block content

//        $page['body'] = view('voyager-page-blocks::default', [
//            'page' => $page,
//            'blocks' => $this->prepareEachBlock($blocks),
//        ]);
        // Check that the page Layout and its View exists
        if (empty($page->layout)) {
            $page->layout = 'default';
        }
        if (!View::exists("{$this->viewPath}::layouts.{$page->layout}")) {
            $page->layout = 'default';
        }
        // Return the full page
        return view("{$this->viewPath}::modules.pages.default", [
            'page' => $page,
            'layout' => $page->layout
        ]);
    }

    public static function getPageBlockData($slug, $identifier)
    {
        $page = Page::where('slug', '=', $slug)->firstOrFail();
        $data = $page->blocks()
            ->where('path', '=', $identifier)
            ->where('is_hidden', '=', '0')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($block) {
                return $block->cachedData;
            })
            ->first();
        return $data;
    }

    protected function tryUpdateMetaData(&$page, &$customData)
    {
        if (isset($customData['metadata'])) {
            if ($page->meta_description && $customData['metadata']['meta_description']) {
                $page->meta_description = $customData['metadata']['meta_description'];
            }
            if ($page->meta_keywords && $customData['metadata']['meta_keys']) {
                $page->meta_keywords = $customData['metadata']['meta_keys'];
            }
            if ($page->title && $customData['metadata']['seo_title']) {
                $page->title = $customData['metadata']['seo_title'];
            }
        }
    }
}
