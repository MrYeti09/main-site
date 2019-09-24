<?php

namespace Viaativa\Viaroot\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class Icon extends AbstractHandler
{
    public $codename = 'font-icon';


    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('viaativa-voyager::formfields.font-icon', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
