<?php

namespace Viaativa\Viaroot\Models;

use Pvtl\VoyagerFrontend\Helpers\ClassEvents;

class Page extends \Pvtl\VoyagerFrontend\Page
{
    // Add relation to page blocks
    public function blocks()
    {
        return $this->hasMany('Viaativa\Viaroot\Models\PageBlock');
    }

    public function category() {
        return PageCategory::where('id',$this->page_category_id)->first();
    }

    /**
     * Get the indexed data array for the model.
     *
     * @return array
     */

    public function categories_path($mode = 'url',$category = null, $arr = []) {
        if($category == null)
        {
            $category = $this->category();
        }
        if (isset($category)) {

            if(!isset($category->slug) and isset($category->parent_id))
            {
                array_push($arr, str_slug($category->name));
            } else {
                array_push($arr, $category->slug);
            }
            if (isset($category->parent_id)) {
                $page = \Viaativa\Viaroot\Models\PageCategory::where('id', $category->parent_id)->first();
                if (isset($page)) {
                    return $this->categories_path($mode,$page, $arr);
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

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Include page block data to be "Searchable"
        $pageBlocks = $this->blocks()->get()->map(function ($block) {
            // If it's an included file, return the HTML of this block to be searched
            if ($block->type === 'include') {
                return trim(preg_replace(
                    '/\s+/',
                    ' ',
                    strip_tags(ClassEvents::executeClass($block->path)->render())
                ));
            }

            $blockContent = [];

            foreach ($block->data as $datum) {
                    if(gettype($datum) == "array")
                    {
                        $datum = implode(",",$datum);
                    }

                $blockContent[] = strip_tags($datum);
            }

            return $blockContent;
        });

        $array['page_blocks'] = implode(' ', array_flatten($pageBlocks));

        return $array;
    }
}
