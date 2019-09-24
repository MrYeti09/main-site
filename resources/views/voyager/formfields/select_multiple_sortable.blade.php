{{-- If this is a relationship and the method does not exist, show a warning message --}}
@if(isset($options->relationship) && !method_exists( $dataType->model_name, camel_case($row->field) ) )

    <p class="label label-warning"><i
            class="voyager-warning"></i> {{ __('voyager::form.field_select_dd_relationship', ['method' => camel_case($row->field).'()', 'class' => $dataType->model_name]) }}
    </p>
@endif
@php
    if(property_exists($options,"link"))
    {
    $model = $options->link->model;
    $items = $model::all();

    }
    if(isset($dataTypeContent->{$row->field}))
    {
    if(gettype($dataTypeContent->{$row->field}) == "array")
    {
    $dataTypeContent->{$row->field} = (object)$dataTypeContent->{$row->field};
    }
    else
    {
    $dataTypeContent->{$row->field} = (object)explode(',',$dataTypeContent->{$row->field});
    }
    } else {
    $dataTypeContent->{$row->field} = "";
    }
    $ids = (array)$dataTypeContent->{$row->field};
    $items = $items->sortBy(function($model) use ($ids) {
        $i = array_search($model->id, $ids);
        if(is_bool($i) && !$i){
            return sizeof($ids);
        }
        return $i;
    });
@endphp
<select class="form-control select2-sortable" name="{{ $row->field }}[]" multiple>
    @if(isset($options->relationship))
        {{-- Check that the relationship method exists --}}
        @if( method_exists( $dataType->model_name, camel_case($row->field) ) )
            <?php $selected_values = isset($dataTypeContent) ? $dataTypeContent->{camel_case($row->field)}()->pluck($options->relationship->key)->all() : []; ?>
            <?php
            $relationshipListMethod = camel_case($row->field) . 'List';
            if (isset($dataTypeContent) && method_exists($dataTypeContent, $relationshipListMethod)) {
                $relationshipOptions = $dataTypeContent->$relationshipListMethod();
            } else {
                $relationshipClass = get_class(app($dataType->model_name)->{camel_case($row->field)}()->getRelated());
                $relationshipOptions = $relationshipClass::all();
            }
            ?>
            @foreach($relationshipOptions as $relationshipOption)
                <option
                    value="{{ $relationshipOption->{$options->relationship->key} }}" @if(in_array($relationshipOption->{$options->relationship->key}, $selected_values)){{ 'selected="selected"' }}@endif>{{ $relationshipOption->{$options->relationship->label} }}</option>
            @endforeach
        @endif
    @elseif(isset($options->options))
        @foreach($options->options as $key => $label)
            <?php $selected = ''; ?>
            @if(is_array($dataTypeContent->{$row->field}) && in_array($key, $dataTypeContent->{$row->field}))
                <?php $selected = 'selected="selected"'; ?>
            @elseif(!is_null(old($row->field)) && in_array($key, old($row->field)))
                <?php $selected = 'selected="selected"'; ?>
            @endif
            <option value="{{ $key }}" {!! $selected !!}>
                {{ $label }}
            </option>
        @endforeach
    @elseif(property_exists($options,"link"))
        @php
            if(!property_exists($options->link,"key"))
            {
            $options->link->key = "id";
            }
        if(!property_exists($options->link,"display"))
            {
            $options->link->display = "id";
            }

        @endphp
        @foreach($items as $key => $label)
            <?php $selected = ''; ?>
            @if(in_array($label->{$options->link->key}, $ids))
                <?php $selected = 'selected="selected"'; ?>
            @endif
            <option value="{{ $label->{$options->link->key} }}" {!! $selected !!}>
                {{ $label->{$options->link->display} }}
            </option>
        @endforeach
    @endif
</select>
