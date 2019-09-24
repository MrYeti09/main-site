<?php // ?>
<form id="{{ $form->title }}" action="{{ route('voyager.enquiries.submit', ['id' => $form->id]) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    @if (session('success'))
        <div data-success-email-send></div>
    @endif

    @if (session('error'))
        <div data-error-email-send></div>
    @endif

    @foreach ($form->inputs as $input)

            @if (in_array($input->type, ['text', 'number', 'email']))
                <label for="{{ $input->label }}">
                    <input name="{{ $input->label }}" type="{{ $input->type }}" @if ($input->required) required @endif placeholder="{{ $input->label }}@if ($input->required) * @endif">
                </label>
            @endif

            @if ($input->type === 'text_area')
                <label for="{{ $input->label }}">
                    {{ $input->label }}
                    <textarea name="{{ $input->label }}" @if ($input->required) required @endif></textarea>
                </label>
            @endif

            @if ($input->type === 'select')
                <label for="{{ $input->label }}">
                    {{ $input->label }}
                    <select name="{{ $input->label }}" @if ($input->required) required @endif>
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


    @endforeach

    @if (setting('admin.google_recaptcha_site_key'))
        <button

                class="button g-recaptcha"
                data-badge="inline"
                data-sitekey="{{ setting('admin.google_recaptcha_site_key') }}"
                data-callback="onSubmit" onclick="setFormId('{{ $form->title }}')"
        >
            {{$blockData->form_button_text}}
        </button>
    @else
        <button style="font-size: {{$blockData->button_size}}px;font-family: {{$blockData->button_font}};width:80%;font-weight: {{$blockData->button_weight}};border:none;border-radius:12px;height:{{$blockData->form_button_size}}px;background-color: {{$blockData->form_button_color}};color: {{$blockData->button_color}};margin-bottom:{{$blockData->form_info_distance}}px"--}}
                       data-hover-out='{"background":"{{$blockData->form_button_color_out}}"}'
                       data-hover-in='{"background":"{{$blockData->form_button_color_in}}"}' class="hover-me" id="submit" type="submit" value="submit">{{$blockData->form_button_text}}</button>
    @endif
</form>
