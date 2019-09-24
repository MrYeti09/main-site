<input type="number"
       class="form-control"
       name="{{ $row->field }}"
       type="number"
       @if($row->required == 1) required @endif
       @if(isset($options->min)) min="{{ $options->min }}" @endif
       @if(isset($options->max)) max="{{ $options->max }}" @endif
       step="{{ $options->step ?? 'any' }}"
       placeholder="@if(property_exists($row,'display_name')) {{ old($row->field, $options->placeholder ?? $row->display_name) }} @endif"
       value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">