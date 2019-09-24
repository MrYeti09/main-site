<?php

namespace Viaativa\Viaroot\Traits;

use TCG\Voyager\Models\DataType;

trait DatabaseDataType
{
    protected function addDataType($name, $slug, $singularName, $pluralName, $modelName, $policyName = null, $controller = null, $icon = 'voyager-list', $generate_permissions = 1, $server_side = 0, $description = '')
    {
        $dataType = $this->dataType('slug', $slug);
        if ($dataType->exists == false) {
            $dataType->fill([
                'name' => $name,
                'display_name_singular' => $singularName,
                'display_name_plural' => $pluralName,
                'icon' => $icon,
                'model_name' => $modelName,
                'policy_name' => $policyName,
                'controller' => $controller,
                'generate_permissions' => $generate_permissions,
                'server_side' => $server_side,
                'description' => $description
            ])->save();
        }
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for  [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}