<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class CreateButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $url;

    public $label;

    public function __construct($url, $label = 'Create')
    {
        $this->url = $url;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.create-button');
    }
}
