<?php

namespace Viaativa\Viaroot\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Spatie\ImageOptimizer\Optimizers\Cwebp;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Setting;
use Viaativa\Viaroot\Models\Notification;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Viaativa\Viaroot\Models\PageBlock;
use Viaativa\Viaroot\Models\User;
use WebPConvert\WebPConvert;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function view_optimize() {
        return view('voyager::optimize');
    }

    public function toggle_widget(Request $request) {
        if($request->ajax())
        {
            $userId = Auth::user()->id;
            $user = User::where('id',$userId)->first();
            if($user->widgets == null)
            {
                $user->widgets = json_encode([]);
            }
            $widgets = json_decode($user->widgets);
            $index = array_search($request->input('name'),$widgets);
            if($index !== FALSE){
                unset($widgets[$index]);
            } else
            {
                array_push($widgets,$request->input('name'));
            }
            $user->widgets = json_encode($widgets);
            $user->save();
            return $user->widgets;
        }
    }

    public function optimize() {

        $dataTypes = DataType::all();

        foreach($dataTypes as $dataType)
        {
            $rows = $dataType->rows()->get();

            $model = app($dataType->model_name);

            foreach($model->all() as $dataTable) {

                foreach ($rows as $row) {
                    if(!in_array($row->type,['media_picker','image','icon']))
                    {
                        continue;
                    }
                    $data = $dataTable->{$row->field};
                    $explode = explode('.',$data);
                    $extension = end($explode);
                    if(in_array($extension,['png','jpg','jpeg']))
                    {
                        if(!file_exists(storage_path('app\public\optimized\\')))
                        {
                            mkdir(storage_path('app\public\optimized\\'));
                        }
                        if(file_exists(storage_path('app\public\\').$data) and !file_exists(storage_path('app\public\optimized\\') . str_replace(['.png', '.jpeg', '.jpg'], '.webp', $data))) {
                            WebPConvert::convert(
                                storage_path('app\public\\') . $data,
                                storage_path('app\public\optimized\\') . str_replace(['.png', '.jpeg', '.jpg'], '.webp', $data));
                        }
//                        array_push($images,str_replace(['.png','.jpeg','.jpg'],'.webp',$data));
                        //$optimizerChain->addOptimizer(new Cwebp())->optimize(,storage_path('app\public\optimized\\').$data.".webp");

                    }
                }
            }


        }

        $optimizerChain = OptimizerChainFactory::create();
        $blocks = PageBlock::all();
        $images = [];
        foreach($blocks as $block)
        {
            $blockData = $block->data;
            foreach($blockData as $data)
            {
                $explode = explode('.',$data);
                $extension = end($explode);
                if(in_array($extension,['png','jpg','jpeg']))
                {
                    if(!file_exists(storage_path('app\public\optimized\\')))
                    {
                        mkdir(storage_path('app\public\optimized\\'));
                    }
                    WebPConvert::convert(
                        storage_path('app\public\\').$data,
                        storage_path('app\public\optimized\\').str_replace(['.png','.jpeg','.jpg'],'.webp',$data));
                    array_push($images,str_replace(['.png','.jpeg','.jpg'],'.webp',$data));
                    //$optimizerChain->addOptimizer(new Cwebp())->optimize(,storage_path('app\public\optimized\\').$data.".webp");

                }
            }
        }
        return redirect()->back();
    }

    public function verify() {
        return view('viaativa-voyager::verify');
    }

    public function docs() {
        return view('viaativa-voyager::developer.index');
    }



    public function mark_notification(Request $request) {
        $notification = Notification::where('id',$request->id)->first();
        $seen = json_decode($notification->seen);
        if($seen == null)
        {
            $seen = [];
        }
        array_push($seen,Auth::user()->id);
        $notification->seen = json_encode($seen);
        $notification->save();
    }

    public function setup_voyager(Request $request)
    {
        $files = $request->allFiles();
        $inputs = $request->all();
        $welcome = Setting::where('key','admin.description')->first();
        $welcome->value = $inputs['welcome'];
        $welcome->save();

        if($request->hasFile('login_logo')) {
            $image = $request->file('login_logo');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('app/public/admin');
            $image->move($destinationPath, $name);

            $icon = Setting::where('key', 'site.logo')->first();
            $icon->value = 'admin\\' . $name;
            $icon->save();
        }

        if($request->hasFile('login_bg')) {
            $image = $request->file('login_bg');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('app/public/admin');
            $image->move($destinationPath, $name);

            $bg = Setting::where('key', 'admin.bg_image')->first();
            $bg->value = 'admin\\' . $name;
            $bg->save();
        }

        if($request->hasFile('dash_icon')) {
            $image = $request->file('dash_icon');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('app/public/admin');
            $image->move($destinationPath, $name);

            $bg = Setting::where('key', 'admin.icon_image')->first();
            $bg->value = 'admin\\' . $name;
            $bg->save();
        }

        if($request->hasFile('dash_load')) {
            $image = $request->file('dash_load');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('app/public/admin');
            $image->move($destinationPath, $name);

            $bg = Setting::where('key', 'admin.loader')->first();
            $bg->value = 'admin\\' . $name;
            $bg->save();
        }


            $icon = Setting::where('key', 'admin.dash_pos')->first();
            if($icon == null)
            {
                $icon = new Setting();
                $icon->key = 'admin.dash_pos';
                $icon->display_name = 'Posição do painel';
                $icon->details = null;
                $icon->type = 'checkbox';
                $icon->order = '8';
                $icon->group = 'Admin';
            }
            if($request->has('dash_pos')) {
                $icon->value = json_encode([$request->dash_pos]);
            } else {
                $icon->value = json_encode([1]);
            }
            $icon->save();


            $layout = Setting::where('key', 'admin.dash_layout')->first();
            if($layout == null)
            {
                $layout = new Setting();
                $layout->key = 'admin.dash_layout';
                $layout->display_name = 'Layout do painel';
                $layout->details = null;
                $layout->type = 'checkbox';
                $layout->order = '9';
                $layout->group = 'Admin';
            }
            if($request->has('dash_layout')) {
                $layout->value = json_encode([$request->dash_layout]);
            } else
            {
                $layout->value = json_encode([0]);
            }
            $layout->save();

        return redirect()->back();
    }

    public function write_log(Request $request)
    {
        $text = "";
        foreach($request->info as $res)
        {
            if(!isset($res['msg']))
            {
                $text .= "[" . $res['name'] . "] : Undefined \r\n";
            } else {
                $text .= "[" . $res['name'] . "] : " . $res['msg'] . "\r\n";
            }
        }
        $results = print_r((array)$request->info, true);
        file_put_contents(base_path().'/logs/logs.txt', $text);
        return $results;
    }

}
