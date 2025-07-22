<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectFilter extends Component
{
    /**
     * Create a new component instance.
     */

     public $name;
    public $label;
    public $options;
    public $valueKey;
    public $labelKey;
    public $enabled;
    public $placeholder;

    public function __construct($name,
        $options,
        $label = null,
        $valueKey = 'id',
        $labelKey = 'name',
        $enabled = true,
        $placeholder = null
    ) {
        $this->name = $name;
        $this->options = $options;
        $this->label = $label;
        $this->valueKey = $valueKey;
        $this->labelKey = $labelKey;
        $this->enabled = $enabled;
        $this->placeholder = $placeholder;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-filter');
    }
}
