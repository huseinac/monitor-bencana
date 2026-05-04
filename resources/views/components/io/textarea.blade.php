@if($viewtype === 1)
    <div class="row mb-2">
        <label class="col-lg-3 form-label {{ $required == '1' ? 'required' : '' }}">{{ $caption }}</label>
        <div class="col-lg-9">
            <x-textarea
                :type="$type"
                :prefix="$prefix"
                :name="$name"
                class="mb-3 mb-lg-0 {{ $class }}"
                :caption="$placeholder"
                :value="$value"
                :rows="$rows"
                :required="$required"
                {{ $attributes }}
            />
        </div>
    </div>
@endif
@if($viewtype === 2)
    <div class="form-group mb-2 d-flex flex-column align-items-start">
        <label class="form-label py-1 {{ $required == 1 ? 'required' : '' }}">{{ $caption }}</label>
        <x-textarea
            :type="$type"
            :prefix="$prefix"
            :name="$name"
            class="mb-3 mb-lg-0 {{ $class }}"
            :caption="$placeholder"
            :value="$value"
            :rows="$rows"
            :required="$required"
            {{ $attributes }}
        />
    </div>
@endif
