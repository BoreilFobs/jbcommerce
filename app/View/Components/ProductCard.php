<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public $offer;
    public $showActions;
    public $isAdmin;

    /**
     * Create a new component instance.
     */
    public function __construct($offer, $showActions = false, $isAdmin = false)
    {
        $this->offer = $offer;
        $this->showActions = $showActions;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-card');
    }
}
