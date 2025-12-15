<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class FoodCard extends Component
{
    public $img;
    public $title;
    public $desc;

    public function __construct($img, $title, $desc)
    {
        $this->img = $img;
        $this->title = $title;
        $this->desc = $desc;
    }

    public function render(): View|Closure|string
    {
        return view('components.food-card');
    }
}
