<?php

namespace TCG\Voyager\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class FontHandler extends AbstractHandler
{
    protected $codename = 'font';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.font', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
