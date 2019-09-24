@foreach($blocks as $key => $block)

    @if (!empty($block->html))
        @php echo (string)$block->html @endphp
    @else
        <div class="page-block">
            <div class="callout alert" style="border-top: 1px dashed rgba(0,0,0,0.43);border-bottom: 1px dashed rgba(0,0,0,0.43);margin-bottom:5px;">
                <div class="grid-container column text-center">
                    <h3><< Missing: <strong> {{$block->path}} </strong>>></h3>
                </div>
            </div>
        </div>
    @endif
@endforeach
