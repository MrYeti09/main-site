<?php

namespace Viaativa\Viaroot\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class FontAwesomePicker extends AbstractHandler
{
    public $codename = 'icon';


    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.fontawesome', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}