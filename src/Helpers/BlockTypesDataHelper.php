<?php



class BlockTypesData
{

    private $blockData;
    private $inputValidation;
    private $avoidVerification;


    static function filter_template(&$template, &$blockData)
    {
        $template = (object)$template;
        foreach ($template->fields as $key => $field) {

            $field = (object)$field;

            if (!property_exists($blockData, $key)) {
                $blockData->{$key} = "";
            } else {
                $blockData->{$key} = html_entity_decode($blockData->{$key});

            }
            if(property_exists($field,'child'))
            {

                foreach($field->child as $c_key => $children)
                {

                    if(property_exists($blockData,$c_key))
                    {
                        $blockData->{$c_key} = html_entity_decode($blockData->{$c_key});
                    } else {
                        $blockData->{$c_key} = "";
                    }
                }
            }
        }

        return $blockData;
    }

    static function font_fields(&$blockData, $varname = null)
    {
        $res = "";
        if ($varname != null) {
            if (isset($blockData->{$varname . '_font'}) and strlen($blockData->{$varname . '_font'})) {
                $res .= "font-family: {$blockData->{$varname.'_font'}};";
            }
            if (isset($blockData->{$varname . '_size'}) and strlen($blockData->{$varname . '_size'})) {
                $res .= "font-size: {$blockData->{$varname.'_size'}}px;";
            }
            if (isset($blockData->{$varname . '_color'}) and strlen($blockData->{$varname . '_color'})) {
                $res .= "color: {$blockData->{$varname.'_color'}};";
            }
            if (isset($blockData->{$varname . '_weight'}) and strlen($blockData->{$varname . '_weight'})) {
                $res .= "font-weight: {$blockData->{$varname.'_weight'}};";
            }
            if (isset($blockData->{$varname . '_height'}) and strlen($blockData->{$varname . '_height'})) {
                $res .= "line-height: {$blockData->{$varname.'_height'}};";
            }
            if (isset($blockData->{$varname . '_space'}) and strlen($blockData->{$varname . '_space'})) {
                $res .= "letter-spacing: {$blockData->{$varname.'_space'}}px;";
            }
            return $res;
        }
    }


    static function multiple_fields(&$blocks)
    {

        foreach ($blocks as $key => $block) {
            $blockName = $key;
            //dd($blockName);
            foreach ($blocks[$blockName]['fields'] as $key => $item) {
                if (property_exists((object)$item, "quantity")) {
                    $name = $item['field'];
                    $tab = $item['tab'];
                    for ($i = 0; $i < $item['quantity']; $i++) {
                        $index = array_search($item['field'], array_keys($blocks[$blockName]['fields']));
                        $temp = $blocks[$blockName]['fields'][$name . '_' . $i] = [
                            'field' => $name . '_' . $i,
                            'display_name' => '',
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => $item['type'],
                            'width' => 'col-md-1'
                        ];
                        $blocks[$blockName]['fields'] = array_merge(array_slice($blocks[$blockName]['fields'], 0, $index + 1),
                            array($temp['field'] => $temp),
                            array_slice($blocks[$blockName]['fields'], 0, count($blocks[$blockName]['fields'])));
                    }
                }
            }
        }
        //dd($blocks);
        return $blocks;
    }

    static function generate_font_fields(&$blocks)
    {
        foreach ($blocks as $blockName => $block) {
            foreach ($blocks[$blockName]['fields'] as $fieldName => $field) {
                if (in_array($field['partial'], [
                    "voyager::formfields.hidden",
                    "voyager::formfields.text",
                    "voyager::formfields.text_area",
                    "voyager::formfields.rich_text_box",
                    "voyager::formfields.number"
                ])) {
                    $target_field = isset($field['font_fields'])
                        ? 'font_fields'
                        : (isset($field['extra_fields'])
                            ? 'extra_fields'
                            :(isset($field['complex'])
                                ? 'complex'
                                : null
                            )
                        );
                    if (!is_null($target_field)) {

                        $tab = isset($field['tab']) ? $field['tab'] : "1";

                        $name = isset($field[$target_field]['varname']) ?
                            "{$field[$target_field]['varname']}_" :
                            "{$field['field']}_";

                        $display_var = (is_string($field[$target_field]) && strlen($field[$target_field]))
                            ? $field[$target_field]
                            : ((is_array($field[$target_field] && isset($field[$target_field]['display_var']))
                                ? $field[$target_field]['display_var']
                                : "texto"
                            ));

                        $child = isset($blocks[$blockName]['fields'][$fieldName]['child']) ? $blocks[$blockName]['fields'][$fieldName]['child'] : [];

                        $blocks[$blockName]['fields'][$fieldName]['child'] = [];
                        $blocks[$blockName]['fields'][$fieldName]['child'][$name . "profile"] = [
                            'field' => $name . "profile",
                            'display_name' => 'Perfil do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.profile'
                        ];

                        $blocks[$blockName]['fields'][$fieldName]['child'][$name . "font"] = [
                            'field' => $name . "font",
                            'display_name' => 'Fonte do ' . $display_var,
                            'group' => $name,
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.font'
                        ];


                        $blocks[$blockName]['fields'][$fieldName]['child'][$name . "color"] = [
                            'field' => $name . "color",
                            'group' => $name,
                            'display_name' => 'Cor do ' . $display_var,
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.colorpicker',
                            'default' => '#3c3f41'
                        ];

                        $blocks[$blockName]['fields'][$fieldName]['child'][$name . "size"] = [
                            'field' => $name . "size",
                            'display_name' => 'Tamanho da fonte do ' . $display_var . ' (px)',
                            'group' => $name,
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number'
                        ];

                        $blocks[$blockName]['fields'][$fieldName]['child'][$name . "weight"] = [
                            'field' => $name . "weight",
                            'display_name' => 'Peso da fonte do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.select_dropdown',
                            'options' => [
                                "inherit" => 'Selecione',
                                "100" => '100',
                                "200" => '200',
                                "300" => '300',
                                "400" => '400',
                                "500" => '500',
                                "600" => '600',
                                "700" => '700',
                                "800" => '800',
                                "900" => '900'
                            ]
                        ];

                        $blocks[$blockName]['fields'][$fieldName]['child'][$name . "height"] = [
                            'field' => $name . "height",
                            'display_name' => 'Altura da linha do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number'
                        ];

                        $blocks[$blockName]['fields'][$fieldName]['child'][$name . "space"] = [
                            'field' => $name . "space",
                            'display_name' => 'Espaçamento do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number'
                        ];

                        foreach ($child as $childKey => $childItem) {
                            $blocks[$blockName]['fields'][$fieldName]['child'][$childKey] = $childItem;
                        }
                    }
                }
            }
        }
        return $blocks;
    }

    static function item_complex(&$blocks)
    {
        foreach ($blocks as $key => $block) {
            $blockName = $key;
            foreach ($blocks[$blockName]['fields'] as $key => $item) {
                if ($item['partial'] == "voyager::formfields.hidden" or $item['partial'] == "voyager::formfields.text" or $item['partial'] == "voyager::formfields.text_area" or $item['partial'] == "voyager::formfields.rich_text_box" or $item['partial'] == "voyager::formfields.number") {
                    $target_field = null;
                    if(property_exists((object)$item, 'complex'))
                    {
                        $target_field = "complex";
                    } elseif(property_exists((object)$item, 'extra_fields')) {
                        $target_field = "extra_fields";
                    }

                    if (!is_null($target_field)) {

                        $tab = "1";
                        if (property_exists((object)$item, "tab")) {
                            $tab = $item['tab'];
                        }
                        $name = $item[$target_field]['varname'] . "_";

                        $blockds = $blocks[$blockName]['fields'][$key];
                        $blockds['group'] = $name;
                        $blockds['primary'] = true;
                        $blocks[$blockName]['fields'][$key] = $blockds;
                        $end = false;
                        $display_var = $item[$target_field]['display_var'];
                        //dd($item['complex']['varname']);
                        $required = 0;
                        if (property_exists((object)$item[$target_field], 'item')) {
                            if ($item[$target_field]['item'] == true) {
                                $num = explode("_", $item['field'])[1];
                                $name = "item_" . $num . "_" . $item[$target_field]['varname'];
                            }
                        }
                        $index = array_search($item['field'], array_keys($blocks[$blockName]['fields']));
                        if (property_exists((object)$item[$target_field], 'before')) {
                            if ($item[$target_field]['before'] == true) {
                                $index -= 1;
                            }
                        }
                        if (property_exists((object)$item[$target_field], 'end')) {
                            if ($item[$target_field]['end'] == true) {
                                $end = true;
                            }
                        }
                        $temp = null;


                        if (!isset($item[$target_field]['color'])) {
                            $temp = $blocks[$blockName]['fields'][$key]['child'][$name . "color"] = [
                                'field' => $name . "color",
                                'group' => $name,
                                'display_name' => 'Cor do ' . $display_var,
                                'required' => 0,
                                'tab' => $tab,
                                'partial' => 'voyager::formfields.colorpicker',
                                'width' => 'col-md-1',
                                'default' => '#3c3f41'
                            ];
                        }

                        if (!$end and $temp != null) {
                            $blocks[$blockName]['fields'][$key]['child'] = array_merge(array_slice($blocks[$blockName]['fields'][$key]['child'], 0, $index + 1),
                                array($temp['field'] => $temp),
                                array_slice($blocks[$blockName]['fields'][$key]['child'], 0, count($blocks[$blockName]['fields'][$key]['child'])));
                        }

                        $temp = $blocks[$blockName]['fields'][$key]['child'][$name . "weight"] = [
                            'field' => $name . "weight",
                            'display_name' => 'Peso da fonte do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.select_dropdown',
                            'options' => [
                                "inherit" => 'Selecione',
                                "100" => '100',
                                "200" => '200',
                                "300" => '300',
                                "400" => '400',
                                "500" => '500',
                                "600" => '600',
                                "700" => '700',
                                "800" => '800',
                                "900" => '900'
                            ],
                            'width' => 'col-md-2'
                        ];

                        if (!$end) {
                            $blocks[$blockName]['fields'][$key]['child'] = array_merge(array_slice($blocks[$blockName]['fields'][$key]['child'], 0, $index + 1),
                                array($temp['field'] => $temp),
                                array_slice($blocks[$blockName]['fields'][$key]['child'], 0, count($blocks[$blockName]['fields'][$key]['child'])));
                        }
                        //if($name == "title_") { dd($temp);}
                        $temp = $blocks[$blockName]['fields'][$key]['child'][$name . "font"] = [
                            'field' => $name . "font",
                            'display_name' => 'Fonte do ' . $display_var,
                            'group' => $name,
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.font',
                            'width' => 'col-md-2'
                        ];
                        if (!$end) {
                            $blocks[$blockName]['fields'][$key]['child'] = array_merge(array_slice($blocks[$blockName]['fields'][$key]['child'], 0, $index + 1),
                                array($temp['field'] => $temp),
                                array_slice($blocks[$blockName]['fields'][$key]['child'], 0, count($blocks[$blockName]['fields'][$key]['child'])));
                        }
                        $temp = $blocks[$blockName]['fields'][$key]['child'][$name . "size"] = [
                            'field' => $name . "size",
                            'display_name' => 'Tamanho da fonte do ' . $display_var . ' (px)',
                            'group' => $name,
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number',
                            'width' => 'col-md-2'
                        ];
                        if (!$end) {
                            $blocks[$blockName]['fields'][$key]['child'] = array_merge(array_slice($blocks[$blockName]['fields'][$key]['child'], 0, $index + 1),
                                array($temp['field'] => $temp),
                                array_slice($blocks[$blockName]['fields'][$key]['child'], 0, count($blocks[$blockName]['fields'][$key]['child'])));
                        }


                        $temp = $blocks[$blockName]['fields'][$key]['child'][$name . "height"] = [
                            'field' => $name . "height",
                            'display_name' => 'Altura da linha do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number',
                            'width' => 'col-md-2'
                        ];


                        if (!$end) {
                            $blocks[$blockName]['fields'][$key]['child'] = array_merge(array_slice($blocks[$blockName]['fields'][$key]['child'], 0, $index + 1),
                                array($temp['field'] => $temp),
                                array_slice($blocks[$blockName]['fields'][$key]['child'], 0, count($blocks[$blockName]['fields'][$key]['child'])));
                        }

                        $temp = $blocks[$blockName]['fields'][$key]['child'][$name . "space"] = [
                            'field' => $name . "space",
                            'display_name' => 'Espaçamento do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number',
                            'width' => 'col-md-2'
                        ];


                        if (!$end) {
                            $blocks[$blockName]['fields'][$key]['child'] = array_merge(array_slice($blocks[$blockName]['fields'][$key]['child'], 0, $index + 1),
                                array($temp['field'] => $temp),
                                array_slice($blocks[$blockName]['fields'][$key]['child'], 0, count($blocks[$blockName]['fields'][$key]['child'])));
                        }




//                        $blocks[$blockName]['fields'] = array_merge(array_slice($blocks[$blockName]['fields'], 0, $index+1),
//                            array($temp['field']=>$temp),
//                            array_slice($blocks[$blockName]['fields'], 0, count($blocks[$blockName]['fields'])));


                    }
                }
            }
        }
        return $blocks;
    }

    public function __construct(&$blockData = null, $blockId = null, $avoidVerification = [])
    {
        if ($blockData != null) {
            $this->blockData = $blockData;
            $this->inputValidation = config('inputValidation');
            $this->avoidVerification = $avoidVerification;
            $this->getInputValidationConfig($blockId);
        }
    }

    function getInputValidationConfig($blockId)
    {

//        dd($this->blockData);
//        dd();

        $pageBlock = config('page-blocks');
        if (isset($pageBlock[$blockId])) {
            $fields = $pageBlock[$blockId]['fields'];
            $this->getValidationType($fields);
        }
        return;

    }

    private function getValidationType($fields)
    {
        foreach ($fields as $blockID => $field) {
            $partial = $field['partial'];
            if (isset($this->inputValidation[$partial])) {
                $this->validate($blockID, $this->inputValidation[$partial], $this->avoidVerification);
            }
        }
    }


    function create_items($blockData)
    {
        $items = [];
        foreach ($blockData as $key => $val) {
            if (substr($key, 0, 4) == "item") {
                $res = explode("_", $key);
                if (sizeof($res) == 3) {
                    if (!in_array($res[0] . $res[1], $items)) {
                        array_push($items, $res[0] . $res[1]);
                    }
                    if (!property_exists($blockData, $res[0] . ucfirst($res[2]))) {
                        $blockData->{$res[0] . ucfirst($res[2])} = [];
                    }
                    array_push($blockData->{$res[0] . ucfirst($res[2])}, $val);// = (object)intval($res[1]) = 0;;
                }
            }
        }
        return $items;
    }


    static function settings(&$blocks)
    {
        $blockId = array_keys($blocks)[0];
        $lastTab = sizeof($blocks[$blockId]['tabs']) + 1;
        $blocks[$blockId]['tabs'][$lastTab] = ['name' => "config-main"];
    }


    private function validate($blockID, $validateTypes, $avoidVerification)
    {
        foreach ($validateTypes as $validateType) {
            $value = $this->blockData->{$blockID};
            if (!in_array($blockID, $avoidVerification)) {

                switch ($validateType) {
                    case "string":
                        if (strlen($value) < 0) {
                            $this->blockData->{$blockID} = $blockID . " not found.";
                        }
                        break;
                    case "numeric":
                        if (strlen($value) < 0) {
                            $this->blockData->{$blockID} = "0";
                        }
                        break;
                    case "hex":
                        if (strlen($value) < 0) {
                            $this->blockData->{$blockID} = "#ffffff";
                        }
                        break;
                    case "image":
                        if (strlen($value) > 0 and $value != null) {
//                        if (file_Exists(storage_path() . "\app\public\\" . $value)) {
////                            if (!exif_imagetype(storage_path() . "\app\public\\" . $value)) {
////                                $this->blockData->{$blockID} = "https://via.placeholder.com/128x128php";
////                            } else {
////                                $this->blockData->{$blockID} = (string)Voyager::image($value);
////                            }
//                            $this->blockData->{$blockID} = (string)Voyager::image($value);
//                        } else {
//                            $this->blockData->{$blockID} = "https://via.placeholder.com/128x128php";
//                            //dd("File not found");
//                        }
                            $this->blockData->{$blockID} = (string)Voyager::image($value);
                        } else {
                            $this->blockData->{$blockID} = "https://via.placeholder.com/128x128php";
                        }
                        break;
                    case "null":
                        $this->blockData->{$blockID} = "Null";
                        break;
                }
            } else {
                if ($validateType === "image") {
                    $this->blockData->{$blockID} = (string)Voyager::image($value);
                }
            }
        }
    }

    static function clonable_fields(&$blocks)
    {
        foreach ($blocks as $key => $block) {
            $blockName = $key;
            foreach ($blocks[$blockName]['clonable']['fields'] as $key => $item) {
                if ($item['partial'] == "voyager::formfields.hidden" or $item['partial'] == "voyager::formfields.text" or $item['partial'] == "voyager::formfields.text_area" or $item['partial'] == "voyager::formfields.rich_text_box" or $item['partial'] == "voyager::formfields.number") {
                    $target_field = null;
                    if (property_exists((object)$item, 'complex')) {
                        $target_field = "complex";
                    } elseif (property_exists((object)$item, 'extra_fields')) {
                        $target_field = "extra_fields";
                    }

                    if (!is_null($target_field)) {

                        $tab = "1";
                        if (property_exists((object)$item, "tab")) {
                            $tab = $item['tab'];
                        }
                        $name = $item[$target_field]['varname'] . "_";

                        $blockds = $blocks[$blockName]['clonable']['fields'][$key];
                        $blockds['group'] = $name;
                        $blockds['primary'] = true;
                        $blocks[$blockName]['clonable']['fields'][$key] = $blockds;
                        $end = false;
                        $display_var = $item[$target_field]['display_var'];
                        //dd($item['complex']['varname']);
                        $required = 0;
                        if (property_exists((object)$item[$target_field], 'item')) {
                            if ($item[$target_field]['item'] == true) {
                                $num = explode("_", $item['field'])[1];
                                $name = "item_" . $num . "_" . $item[$target_field]['varname'];
                            }
                        }
                        $index = array_search($item['field'], array_keys($blocks[$blockName]['fields']));
                        if (property_exists((object)$item[$target_field], 'before')) {
                            if ($item[$target_field]['before'] == true) {
                                $index -= 1;
                            }
                        }
                        $temp = null;

                        $temp = $blocks[$blockName]['clonable']['fields'][$key]['child'][$name . "profile"] = [
                            'field' => $name . "profile",
                            'display_name' => 'Perfil do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.profile',
                            'width' => 'col-md-2'
                        ];

                        if (!isset($item[$target_field]['color'])) {
                            $temp = $blocks[$blockName]['clonable']['fields'][$key]['child'][$name . "color"] = [
                                'field' => $name . "color",
                                'group' => $name,
                                'display_name' => 'Cor do ' . $display_var,
                                'required' => 0,
                                'tab' => $tab,
                                'partial' => 'voyager::formfields.colorpicker',
                                'width' => 'col-md-1',
                                'default' => '#3c3f41'
                            ];
                        }


                        $temp = $blocks[$blockName]['clonable']['fields'][$key]['child'][$name . "weight"] = [
                            'field' => $name . "weight",
                            'display_name' => 'Peso da fonte do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.select_dropdown',
                            'options' => [
                                "inherit" => 'Selecione',
                                "100" => '100',
                                "200" => '200',
                                "300" => '300',
                                "400" => '400',
                                "500" => '500',
                                "600" => '600',
                                "700" => '700',
                                "800" => '800',
                                "900" => '900'
                            ],
                            'width' => 'col-md-2'
                        ];

                        //if($name == "title_") { dd($temp);}
                        $temp = $blocks[$blockName]['clonable']['fields'][$key]['child'][$name . "font"] = [
                            'field' => $name . "font",
                            'display_name' => 'Fonte do ' . $display_var,
                            'group' => $name,
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.font',
                            'width' => 'col-md-2'
                        ];
                        $temp = $blocks[$blockName]['clonable']['fields'][$key]['child'][$name . "size"] = [
                            'field' => $name . "size",
                            'display_name' => 'Tamanho da fonte do ' . $display_var . ' (px)',
                            'group' => $name,
                            'required' => 0,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number',
                            'width' => 'col-md-2'
                        ];
                        $temp = $blocks[$blockName]['clonable']['fields'][$key]['child'][$name . "height"] = [
                            'field' => $name . "height",
                            'display_name' => 'Altura da linha do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number',
                            'width' => 'col-md-2'
                        ];

                        $temp = $blocks[$blockName]['clonable']['fields'][$key]['child'][$name . "space"] = [
                            'field' => $name . "space",
                            'display_name' => 'Espaçamento do ' . $display_var,
                            'required' => 0,
                            'group' => $name,
                            'tab' => $tab,
                            'partial' => 'voyager::formfields.number',
                            'width' => 'col-md-2'
                        ];


//                        $blocks[$blockName]['fields'] = array_merge(array_slice($blocks[$blockName]['fields'], 0, $index+1),
//                            array($temp['field']=>$temp),
//                            array_slice($blocks[$blockName]['fields'], 0, count($blocks[$blockName]['fields'])));


                    }
                }
            }
        }
        return $blocks;
    }

}
