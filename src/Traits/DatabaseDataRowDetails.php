<?php

namespace Viaativa\Viaroot\Traits;

trait DatabaseDataRowDetails
{

    function detailDropDown($options = [], $default = null){
        $dropdown = [];
        if(count($options)){
            $dropdown['default'] = $default ? $default : '';
            foreach ($options as $name => $value){
                $dropdown['options'][$name] = $value;
            }
        }
        return $dropdown;
    }

    function detailValidation($rule, $messages = [], $edit = '', $add = '')
    {
        $validation = ["rule" => $rule];
        if(count($messages)){
            $validation['messages'] = $messages;
        }
        if(strlen($edit)){
            $validation['edit']['rule'] = $edit;
        }
        if(strlen($add)){
            $validation['add']['rule'] = $add;
        }
        return ["validation" => $validation];
    }


    /**
     * @param $dateForm : %d/%m/%Y
     * @return array
     */

    function detailDate($dateForm = "%d/%m/%Y")
    {
        return ["format" => $dateForm];
    }

    function detailSlugify($origin, $forceUpdate = true)
    {
        return [
            "slugify" => [
                "origin" => $origin,
                "forceUpdate" => $forceUpdate
            ]
        ];
    }

    function detailCheckbox($onText = "Ligado", $offText = "Desligado", $checked = false){
        return [
            "on" => $onText,
            "off" => $offText,
            "checked" => $checked
        ];
    }

    /**
     * @param array|false $resize [Width, Height]: The Width and Height of Resize or false to disable. Default: ['1000', null]
     * @param string|false $quality : Percentage of quality of image or false to disable. Default: 70%
     * @param boolean $upsize : Enable upsize if resize is true
     * @param array $thumbnails | false: Array of thumbnails
     *
     * $thumbnails['scales']: Array with ['scale_name' => 'percent']
     * $thumbnails['crops']: Array with ['crop_name' => ['width','height']]
     *
     * Thumbnail Example:
     *
     * $thumbnails = [
     *      'scales' => [
     *          'medium' => '50%',
     *          'small' => '25%'
     *      ],
     *      'crops' => [
     *          'cropped' => ['300', '250']
     *      ]
     * ]
     *
     * @return array $detail
     */
    function detailImage(
        $resize = ['1000', null],
        $quality = '70%',
        $upsize = true,
        $thumbnails = [
            'scales' => [
                'medium' => '50%',
                'small' => '25%'
            ],
            'crops' => [
                'cropped' => ['300', '250']
            ]
        ]
    )
    {

        $detail = [];

        if ($resize) {
            $detail['resize'] = ['width' => $resize[0], 'height' => $resize[1]];
        }

        if ($quality) {
            $detail['quality'] = $quality;
        }

        if ($upsize) {
            $detail['upsize'] = $upsize;
        }

        if ($thumbnails) {

            if (isset($thumbnails['scales'])) {
                foreach ($thumbnails['scales'] as $name => $scale) {
                    $detail['thumbnails'][] = [
                        'name' => $name,
                        'scale' => $scale
                    ];
                }
            }

            if (isset($thumbnails['crops'])) {
                foreach ($thumbnails['crops'] as $name => $cropSize) {
                    $detail['thumbnails'][] = [
                        'name' => $name,
                        'crop' => [
                            'width' => $cropSize[0],
                            'height' => $cropSize[1],
                        ]
                    ];
                }
            }

        }

        return $detail;
    }
}
