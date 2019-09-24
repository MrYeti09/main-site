<div class="icons-wrapper icons-wrapper-{{ $row->field }}" style="margin-right:18px;">
    <div class="icon-selected">
        <input type="hidden" name="{{ $row->field }}" @if($row->required == 1) required @endif @if(isset($dataTypeContent->{$row->field}) and  is_object($dataTypeContent)) value="{{ $dataTypeContent->{$row->field} }}" @endif>
        <i class="icon-preview @if(isset($dataTypeContent->{$row->field}) and is_object($dataTypeContent)) {{$dataTypeContent->{$row->field} }} @endif"  style="font-size: 80px;"></i>
    </div>
    <button type="button" class="btn btn-select-icon" style="background: #0f447a;color:#fff;"
            data-form-name="{{ $row->field }}">Selecionar Ícone
    </button>
    <div class="icon-selector icon-selector-{{ $row->field }} hidden">
        <div class="icons-wrapper-arrow"></div>
        <select class="filter-icons" data-form-name="{{ $row->field }}">
            <option value="">Todos</option>
            @foreach(\Viaativa\Viaroot\Models\Icon::all() as $icon)
                <option value="{{$icon->slug}}">{{$icon->name}}</option>
            @endforeach
        </select>
        <div class="icons-list">
            @foreach(\Viaativa\Viaroot\Models\Icon::all() as $icon)
                @foreach($icon->icons as $iconClass)
                    <div class="icon-wrapper {{$icon->slug}}" data-form-name="{{ $row->field }}"
                         data-form-value="{{$iconClass}}">
                        <i class="{{$iconClass}}"></i>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>