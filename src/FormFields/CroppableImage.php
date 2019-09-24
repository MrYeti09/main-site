<?php

namespace Viaativa\Viaroot\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class CroppableImage extends AbstractHandler
{
    public $codename = 'croppable_image';


    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.croppable_image', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}