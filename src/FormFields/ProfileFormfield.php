<?php

namespace Viaativa\Viaroot\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class ProfileFormfield extends AbstractHandler
{
    protected $codename = 'profile';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.profile', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
