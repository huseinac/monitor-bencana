<input
    type="{{ $type }}"
    class="form-control {{ $class }}"
    name="{{ $name }}"
    id="{{ $prefix.str_replace(['[', ']'], '', $name) }}"
    placeholder="{{ $caption }}"
    value="{{ $value }}"
    autocomplete="off"
    {{ $required != '' ? 'required' : '' }}
    {{ $attributes }}
/>
@if($type === 'file' && $value !== '')
    @php($ext = last(explode('.', $value)))
    @if ($ext === 'pdf')
        <embed src="{{ asset('storage/' . $value) }}" type="application/pdf" class="w-100 h-600px" />
    @else
        <img src="{{ asset('storage/' . $value) }}" alt="" class="w-100 img-fluid">
    @endif
@endif
@if($alert === '1')
    <div class="alert alert-danger d-flex align-items-center p-5 mt-5 d-none w-100" @error($name) style="display: block!important;" @enderror id="{{ $prefix.$name }}_error">
        <div class="d-flex flex-column align-items-start" id="{{ $prefix.$name }}_error_content">
            @error($name) <span>{{ $message }}</span> @enderror
        </div>
    </div>
@endif
