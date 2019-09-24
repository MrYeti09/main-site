@php $action = new $action($dataType, $data); @endphp

@if ($action->shouldActionDisplayOnDataType())
    @if ($data)
        @can ($action->getPolicy(), $data)
            <a href="{{ $action->getRoute($dataType->name) }}" style="background:#0f447a;padding: 4px 12px;" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}" style="color:white;font-size:16px;"></i> <span class="hidden-xs hidden-sm"></span>
            </a>
        @endcan
    @elseif (method_exists($action, 'massAction'))
        <form method="post" action="{{ route('voyager.'.$dataType->slug.'.action') }}" style="display:inline">
            {{ csrf_field() }}
            <button type="submit" {!! $action->convertAttributesToHtml() !!}><i class="{{ $action->getIcon() }}"></i>  {{ $action->getTitle() }}</button>
            <input type="hidden" name="action" value="{{ get_class($action) }}">
            <input type="hidden" name="ids" value="" class="selected_ids">
        </form>
    @endif
@endif
