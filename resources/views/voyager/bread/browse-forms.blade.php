@foreach($block->fields as $key => $field)
    @php
    $pageBlock = \Viaativa\Viaroot\Models\PageBlock::where('path','like',$data->key.'%')->first();
    $field = (object)$field;
    $options = null;
    if(isset($field->options))
    {
    $options = $field->options;
    }
    @endphp
    @if($field->partial != 'break')
    @if($field->partial != "voyager::formfields.hidden")
    <div class="col-md-3">
        <div class="form-group">
            <label>{{$field->display_name}}</label>
            @if($pageBlock != null)
    @include($field->partial,['row' => $field,'dataTypeContent' => $pageBlock->data, 'options' => $options])
                @else
                @include($field->partial,['row' => $field,'dataTypeContent' => $pageBlock, 'options' => $options])
            @endif
        </div>
    </div>
        @else
    @endif
    @if(isset($field->child))
    @foreach($field->child as $field_c)
        @php
        $field_c = (object)$field_c;
            $options_c = null;
            if(isset($field_c->options))
            {
            $options_c = $field_c;
            }
        @endphp
        @if($field_c->partial != 'voyager::formfields.profile')
        <div class="col-md-3">
            <div class="form-group">
                <label>{{$field_c->display_name}}</label>

                @if($pageBlock != null)
            @include($field_c->partial,['row' => $field_c,'dataTypeContent' => $pageBlock->data, 'options' => $options_c])
                    @else
                    @include($field_c->partial,['row' => $field_c,'dataTypeContent' => $pageBlock, 'options' => $options_c])
                @endif
            </div>
        </div>
        @endif
        @endforeach
        @endif
    @else
        <div class="col-md-12" style="width:100%;height:1px;background:rgba(0,0,0,0.09);"></div>
        @endif
@endforeach