@if($viewtype === 1)
    <div class="row mb-2">
        <label class="col-lg-3 form-label {{ $required == 1 ? 'required' : '' }}">{{ $caption }}</label>
        <div class="col-lg-9">
            <x-select :name="$name" :class="$class" :options="$options" :value="$value" :caption="$placeholder" data-control="select2" {{ $attributes }} />
        </div>
    </div>
@endif
@if($viewtype === 2)
    <div class="form-group mb-2 d-flex flex-column align-items-start">
        <label class="form-label py-1 {{ $required == 1 ? 'required' : '' }}">{{ $caption }}</label>
        <x-select :name="$name" :class="$class" :options="$options" :value="$value" :caption="$placeholder" data-control="select2" {{ $attributes }} />
    </div>
@endif
