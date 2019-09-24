<?php

namespace Viaativa\Viaroot\Traits;

use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\DataRow;

trait DatabaseDataRow
{
    use DatabaseDataRowDetails;

    private $dataType = null;
    private $order = 0;

    protected function defineDataType($slug)
    {
        $this->dataType = DataType::where('slug', $slug)->firstOrFail();
        $this->order = 0;
    }

    protected function addDataRow($slug, $type, $display_name, $required = 1, $b = 1, $r = 1, $e = 1, $a = 1, $d = 1, $details = '',$options = '')
    {
        $dataRow = $this->dataRow($this->dataType, $slug);
        if (!$dataRow->exists) {

            if (is_array($details) && $type !== 'relationship') {
                $details = $this->defineDetails($details);
            }

            $dataRow->fill([
                'type' => $type,
                'display_name' => $display_name,
                'required' => $required,
                'browse' => $b,
                'read' => $r,
                'edit' => $e,
                'add' => $a,
                'delete' => $d,
                'details' => $details,
                'order' => $this->order,
                'options' => $options
            ])->save();
            $this->order++;
        }
    }

    protected function defineDetails($details)
    {
        $detailsReturn = [];
        foreach ($details as $detailType => $detailParams) {
            $detailsReturn = array_merge($detailsReturn, $this->doTheDetail($detailType, $detailParams));
        }
        return $detailsReturn;
    }

    protected function doTheDetail($detailType, $detailParams)
    {
        switch ($detailType) {
            case 'slugify':
                return $this->detailSlugify($detailParams);
            case 'image':
                $resize = isset($detailParams['resize']) ? $detailParams['resize'] : ['1000', null];
                $quality = isset($detailParams['quality']) ? $detailParams['quality'] : '70%';
                $upsize = isset($detailParams['upsize']) ? $detailParams['upsize'] : true;
                $thumbnails = isset($detailParams['thumbnails']) ? $detailParams['thumbnails'] : [
                    'scales' => [
                        'medium' => '50%',
                        'small' => '25%'
                    ],
                    'crops' => [
                        'cropped' => ['300', '250']
                    ]
                ];
                return $this->detailImage($resize, $quality, $upsize, $thumbnails);
            case 'date':
                return $this->detailDate($detailParams);
            case 'validation':
                $rule = isset($detailParams['rule']) ? $detailParams['rule'] : '';
                $messages = isset($detailParams['messages']) ? $detailParams['messages'] : [];
                $edit = isset($detailParams['edit']) ? $detailParams['edit'] : '';
                $add = isset($detailParams['add']) ? $detailParams['add'] : '';
                return $this->detailValidation($rule, $messages, $edit, $add);
            case 'dropdown':
                $default = isset($detailParams['default']) ? $detailParams['default'] : null;
                $options = isset($detailParams['options']) ? $detailParams['options'] : [];
                return $this->detailDropDown($options, $default);
            case 'checkbox':
                $onText = isset($detailParams['onText'] ) ? $detailParams['onText'] : 'Ligado';
                $offText = isset($detailParams['offText'] ) ? $detailParams['offText'] : 'Desligado';
                $checked = isset($detailParams['checked'] ) ? $detailParams['checked'] : false;
                return $this->detailCheckbox($onText, $offText, $checked);
        }
        return [];
    }

    /**
     * [dataRow description].
     *
     * @param [type] $type  [description]
     * @param [type] $field [description]
     *
     * @return [type] [description]
     */
    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew([
            'data_type_id' => $type->id,
            'field' => $field,
        ]);
    }
}
