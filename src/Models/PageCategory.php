<?php

namespace Viaativa\Viaroot\Models;

use Illuminate\Database\Eloquent\Model;

class PageCategory extends Model
{
    //
    public function getBlocksAttribute()
    {
        return PageBlock::where('category_id',$this->id)->get();
    }

    public function pages() {
        return Page::where('page_category_id',$this->id);
    }

    public function parent() {
        return PageCategory::where('id',$this->parent_id);
    }


    public function breadcrumbs($mode = 'url',$category = null, $arr = []) {
        if($category == null)
        {
            $category = $this;
        }
        if (isset($category)) {
            array_push($arr, $category);
            if (isset($category->parent_id)) {
                $page = \Viaativa\Viaroot\Models\PageCategory::where('id', $category->parent_id)->first();
                if (isset($page)) {
                    return $this->breadcrumbs($mode,$page, $arr);
                }
            } else {
                $string = "";
                foreach (array_reverse($arr) as $key => $item) {
                    $string .= $item;
                    if ($key < sizeof(array_reverse($arr)) - 1) {
                        $string .= "/";
                    }
                }
                if (sizeof($arr) == 0) {
                    return $string;
                } else {
                    switch($mode)
                    {
                        case 'url':
                            return $string . "/";
                            break;
                        case 'route':
                            return str_replace('/','.',$string);
                            break;
                        case 'array':
                            return array_reverse($arr);
                            break;
                    }

                }

            }
        }
    }
}
