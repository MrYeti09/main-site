<?php


use Illuminate\Support\Facades\Storage;

if (!function_exists('matching_ends')) {
    function matching_ends($s1, $s2)
    {
        return substr($s1, -strlen($s2)) == $s2 ? "true" : "false";
    }
}

function css($style = "",$vars = "")
{
    return $style.":".$vars.";";
}

function webImage($file = "",$default = "")
{
    if (!empty($file)) {
            return str_replace('\\', '/', Storage::disk(config('voyager.storage.disk'))->url($file));
    }

    return $default;
}

if (!function_exists('forms_info')) {
function forms_info($key, $info = [], $default = null)
{
$forms = new \Viaativa\Viaroot\Models\Forms();
return $forms->forms($key, $info, $default);
}
}

if (!function_exists('adminMenu')) {
    function adminMenu($menuName, $type = null, array $options = [])
    {
        return \Viaativa\Viaroot\Models\Menu::display($menuName, $type, ["admin"], $options);
    }
}




if (!function_exists('check_permission')) {
    function check_permission($permission,$key,$abort = true)
    {
        if($abort == false) {
            if (sizeof(Illuminate\Support\Facades\Auth::user()->role->permissions->where('key', $permission . "_" . $key)) <= 0) {
                return false;
            } else {
                return true;
            }
        } else {
            if(sizeof(Illuminate\Support\Facades\Auth::user()->role->permissions->where('key',$permission."_".$key)) <= 0) {
                abort(403);
            }
        }
    }
}


if (!function_exists('create_notification')) {
    function create_notification($text,$url = null,$for = [],$extra = null)
    {
        if(strlen($text)) {
            $not = new \Viaativa\Viaroot\Models\Notification();
            $not->text = $text;
            $not->url = $url;
            $not->for = json_encode($for);
            $not->extra = json_encode($extra);
            $not->save();
        }
    }
}