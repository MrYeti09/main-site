<?php

namespace Viaativa\Viaroot\Traits;

trait DatabaseDataRowRelationship
{

    use DatabaseDataRow;

    /**
     * @param $currentTable : Current table name
     * @param $table : Table name of relationship
     * @param $relationshipType : Type of relationship, availables "hasOne, hasMany, belongsTo, belongsToMany"
     * @param $displayName : Display name in B
     * @param $b : b of bread
     * @param $r : r of bread
     * @param $e : e of bread
     * @param $a : a of bread
     * @param $d : d of bread
     * @param $model : model path of relationship table
     * @param $column : column of relationship
     * @param $key : key of relationship
     * @param $label : table name of relationship that i want to show
     * @param null $pivotTable : if is belongstomany, this need the name of pivot table
     * @param int $taggable : if taggable is true, user can add item on enter event
     */
    function addRelationship(
        $currentTable,
        $table,
        $relationshipType,
        $displayName,
        $b,
        $r,
        $e,
        $a,
        $d,
        $model,
        $column,
        $key,
        $label,
        $pivotTable = null,
        $taggable = 0)
    {
        $relationshipTypeLowerCase = mb_strtolower($relationshipType);
        $slug = "{$currentTable}_{$relationshipTypeLowerCase}_{$table}";
        $details = [
            'model' => $model,
            'table' => $table,
            'type' => $relationshipType,
            'column' => $column,
            'key' => $key,
            'label' => $label,
            'pivot_table' => $pivotTable,
            'pivot' => $pivotTable ? 1 : 0,
            'taggable' => $taggable
        ];

        $this->addDataRow($slug,
            'relationship',
            $displayName,
            0,
            $b,
            $r,
            $e,
            $a,
            $d,
            $details);
    }

}
