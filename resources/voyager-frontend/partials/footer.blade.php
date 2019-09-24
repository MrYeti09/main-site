@php
    $blocks = \Viaativa\Viaroot\Models\PageBlock::where('page_id','-1')->get();
    $template = null;
    $templates = config('page-blocks');
    $blockData = [];
    foreach($blocks as $block)
    {

        if(isset($templates[$block->path]) and array_key_exists('type',$templates[$block->path]))
        {
            if($templates[$block->path]['type'] == 'footer')
            {

                $template = $templates[$block->path];
                $blockData = $block->data;
            }
        }
    }
@endphp
@if($template != null)
@include($template['template'],['blockData' => $blockData,'template' => $template])
@endif

<script src="{{ url('/') }}/js/app.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="https://unpkg.com/packery@2/dist/packery.pkgd.js"></script>
<script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js" integrity="sha256-PtzTX1ftmEmj8YUiAX0wTIQ+ddTAGVt2MiLMsGsAMxM=" crossorigin="anonymous"></script>
<!-- Finished JS -->
<script src="https://static.zenvia.com/embed/js/zenvia-chat.min.js"></script>
<script>
    // var chat = new ZenviaChat('d9e31191e7cb10799dfa4a8a5f33a14c')
    //     .embedded('button').build();
    //
    //
    // $('a').each(function() {
    //     var $this = $(this);
    //     var data = $this.attr('href')
    //     if(data == "$open-chat")
    //     {
    //         $this.removeAttr('href').click(function() {
    //             chat.open();
    //         })
    //     }
    // })

</script>
<script type="text/javascript" src="{{ asset('js/viaativa-blocks.js') }}"></script>
</body>
</html>
