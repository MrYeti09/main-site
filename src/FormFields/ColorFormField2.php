<?php

namespace Viaativa\Viaroot\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class ColorFormField2 extends AbstractHandler
{
    public $codename = 'colorpicker';


    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.colorpicker', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}