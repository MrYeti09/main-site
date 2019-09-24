<?php

namespace Viaativa\Viaroot\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class Tags extends AbstractHandler
{
    protected $codename = 'tags';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('viaativa-voyager::formfields.tags', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
