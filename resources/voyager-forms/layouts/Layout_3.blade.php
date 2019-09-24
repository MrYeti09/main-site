<?php
$block = (new BlockTypesData($blockData, "modal-form-1"))
?>
<form id="{{ $form->title }}" action="{{ route('voyager.enquiries.submit', ['id' => $form->id]) }}" method="POST" enctype="multipart/form-data" style="width:100%;" class="grid-container">
    <div class="grid-x grid-padding-x">
        {{ csrf_field() }}

        @if (session('success'))
            <div data-success-email-send></div>
        @endif

        @if (session('error'))
            <div data-error-email-send></div>
        @endif

        @foreach ($form->inputs as $input)
            <div class="cell large-6 medium-6 small-6">
                @if (in_array($input->type, ['text', 'number', 'email']))

                    <label for="{{ $input->label }}">
                        <input style="border: {{$blockData->form_border_width}}px solid {{$blockData->form_border_color}}" name="{{ $input->label }}" type="{{ $input->type }}" @if ($input->required) required @endif placeholder="{{ $input->label }}@if ($input->required) * @endif">
                    </label>
                @endif

                @if ($input->type === 'text_area')
                    <label for="{{ $input->label }}">
                        <textarea style="height:95px;border: {{$blockData->form_border_width}}px solid {{$blockData->form_border_color}}" name="{{ $input->label }}" @if ($input->required) required @endif></textarea>
                    </label>
                @endif

                @if ($input->type === 'select')
                    <label for="{{ $input->label }}">
                        {{ $input->label }}
                        <select style="border: {{$blockData->form_border_width}}px solid {{$blockData->form_border_color}}" name="{{ $input->label }}" @if ($input->required) required @endif>
                            <option value="">-- Select --</option>

                            @foreach (explode(', ', $input->options) as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </label>
                @endif

                @if (in_array($input->type, ['radio', 'checkbox']))
                    <fieldset class="fieldset medium-12 cell">
                        <legend>{{ $input->label }}</legend>
                        @foreach (explode(', ', $input->options) as $option)
                            <input
                                    id="{{ $option }}-{{ $input->type }}"
                                    name="{{ $input->label }}"
                                    type="{{ $input->type }}"
                                    value="{{ $option }}"
                            >
                            <label for="{{ $option }}-{{ $input->type }}">{{ ucwords($option) }}</label>
                        @endforeach
                    </fieldset>
                @endif

                @if ($input->type === 'file')
                    <label for="{{ $input->label }}">
                        {{ $input->label }}
                        <input type="file" name="{{ $input->label }}" accept="{{ $input->options }}" @if ($input->required) required @endif>
                    </label>
                @endif

            </div>
        @endforeach
        <div class="cell large-12 medium-12 small-12" style="justify-content: center;
align-items: center;
display: flex;">
            @if (setting('admin.google_recaptcha_site_key'))
                <button
                        data-close
                        class="button g-recaptcha"
                        data-badge="inline"
                        data-sitekey="{{ setting('admin.google_recaptcha_site_key') }}"
                        data-callback="onSubmit" onclick="setFormId('{{ $form->title }}')"
                >
                    {{$blockData->form_button_text}}
                </button>
            @else
                <button data-close
                        style="font-size: {{$blockData->button_size}}px;font-family: {{$blockData->button_font}};width:{{$blockData->button_width}}%;font-weight: {{$blockData->button_weight}};border:none;border-radius:12px;height:{{$blockData->form_button_size}}px;background-color: {{$blockData->form_button_color}};color: {{$blockData->button_color}};margin-bottom:{{$blockData->form_info_distance}}px"
                        data-hover-out='{"background":"{{$blockData->form_button_color}}","color":"{{$blockData->button_color}}"}'
                        data-hover-in='{"background":"{{$blockData->form_button_color_in}}","color":"{{$blockData->button_text_hover}}"}' class="hover-me" id="submit" type="submit" value="submit">{{$blockData->button_text}}</button>
            @endif
        </div>
    </div>
</form>
