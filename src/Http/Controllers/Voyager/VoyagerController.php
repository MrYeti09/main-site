<?php

namespace Viaativa\Viaroot\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use TCG\Voyager\Http\Controllers\VoyagerController as BaseVoyagerController;

class VoyagerController extends BaseVoyagerController
{
    public function assets(Request $request)
    {
        $path = str_start(str_replace(['../', './'], '', urldecode($request->path)), '/');
        $path = base_path('vendor/tcg/voyager/publishable/assets'.$path);
        if (File::exists($path)) {
            $mime = '';
            if (ends_with($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (ends_with($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            if(file_exists($path)) {
                try {
                    $response = response(File::get($path), 200, ['Content-Type' => $mime]);
                    $response->setSharedMaxAge(31536000);
                    $response->setMaxAge(31536000);
                    $response->setExpires(new \DateTime('+1 year'));
                } catch(\Exception $err)
                {
                    return response('',404);
                }

                return $response;
            } else {
                return response('',404);
            }
        }

        return response('', 404);
    }
}
