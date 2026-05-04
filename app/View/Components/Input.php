<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $class, $prefix, $name, $caption, $type, $value, $alert, $required;
    public function __construct(
        $class = null,
        $prefix = null,
        $name = null,
        $caption = null,
        $type = 'text',
        $value = '',
        $alert = '1',
        $required = ''
    )
    {
        $this->class = $class;
        $this->prefix = $prefix;
        $this->name = $name;
        $this->caption = $caption;
        $this->type = $type;
        $this->value = $value;
        $this->alert = $alert;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.input');
    }
}
