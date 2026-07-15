<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class BackButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $url;

    public $label;

    public function __construct($url, $label = 'Back')
    {
        $this->url = $url;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.back-button');
    }
}
