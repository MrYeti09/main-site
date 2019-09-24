<?php

namespace Viaativa\Viaroot\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class Packery extends AbstractHandler
{
    public $codename = 'packery';


    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.packery', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}