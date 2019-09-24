<input type="hidden" class="viatable" name="{{ $row->field }}" value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
