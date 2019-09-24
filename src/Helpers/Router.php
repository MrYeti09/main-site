<?php

namespace Viaativa\Viaroot\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;
use Viaativa\Viaroot\Models\Page;
use Viaativa\Viaroot\Models\PageCategory;

class Router
{
    /**
     * Dynamically register pages.
     */

    public static function buildCategorySlugs($category,$arr = []) {
        if(isset($category)) {
            if(!isset($category->slug) and isset($category->parent_id))
            {
                array_push($arr, str_slug($category->name));
            } else {
                array_push($arr, $category->slug);
            }
            if (isset($category->parent_id)) {
                $page = PageCategory::where('id', $category->parent_id)->first();
                if (isset($page)) {
                    return Router::buildCategorySlugs($page, $arr, false);
                }
            } else {
                $string = "";
                foreach (array_reverse($arr) as $key => $item) {
                    $string .= $item;
                    if ($key < sizeof(array_reverse($arr))-1) {
                        $string .= "/";
                    }
                }
                if (sizeof($arr) == 0) {
                    return $string;
                } else {
                    return $string . "/";
                }

            }
        }
    }

    public static function registerPageRoutes()
    {
        // Prevents error before our migration has run
        if (!Schema::hasTable('pages')) {
            return;
        }

        // Which Page Controller shall we use to display the page? Page Blocks or standard page?
        $pageController = '\Pvtl\VoyagerPages\Http\Controllers\PageController';

        if (class_exists('\Pvtl\VoyagerFrontend\Http\Controllers\PageController')) {
            $pageController = '\Pvtl\VoyagerFrontend\Http\Controllers\PageController';
        }

        if (class_exists('\Pvtl\VoyagerPageBlocks\Providers\PageBlocksServiceProvider')) {
            $pageController = '\Viaativa\Viaroot\Http\Controllers\PageViaController';
        }
        $config = config('viaativa-voyager');
        if(isset($config['pageController'])) {
            if (class_exists($config['pageController'])) {
                $pageController = $config['pageController'];
            }
        }
//        dd('asdacerto');
        // Get all page slugs (note it's cached for 5mins)

//        $pages = Cache::remember('page/slugs', 5, function () {
//            return Page::all(['id','slug','page_category_id']);
//        });
//        dump($pages);
        $pages = Page::all(['id','slug','page_category_id']);;

        $slug = Request::path() === '/' ? 'home' : Request::path();
//        dump($slug);
        // When the current URI is known to be a page slug, let it be a route
//        if ($pages->contains('slug', $slug)) {
//        dump($pages);
        foreach($pages as $page) {
            if(isset($page->page_category_id)) {
                $category = $page->category();
                $result = Router::buildCategorySlugs($category);
                Route::get('/' . $result . $page->slug, "$pageController@getPage")
                    ->middleware('web')->defaults('slug', $result . $page->slug)->name('page.' . str_replace('/', '.', $result) . $page->slug);
//                Route::get('/' . $result . $page->slug."/amp", "$pageController@getPage")
//                    ->middleware('web')->defaults('slug', $result . $page->slug)->name('page.' . str_replace('/', '.', $result) . $page->slug.".amp");
            }
        }
//        }
    }
}
