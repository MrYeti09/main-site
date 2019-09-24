<?php

namespace Viaativa\Viaroot\Http\Controllers\Voyager;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Events\MediaFileAdded;
use TCG\Voyager\Http\Controllers\VoyagerMediaController as BaseVoyagerMediaController;

class VoyagerMediaController extends BaseVoyagerMediaController
{

    /** @var string */
    private $filesystem;

    /** @var string */
    private $directory = '/public';

    public function __construct()
    {
        parent::__construct();
    }


}
