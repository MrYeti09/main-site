<input @if($row->required == 1) required @endif type="text" class="form-control tagify" name="{{ $row->field }}"
       placeholder="{{ old($row->field, $options->placeholder ?? $row->display_name) }}"
       value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
