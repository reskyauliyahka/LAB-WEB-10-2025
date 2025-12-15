<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public $judul;
    public $gambar;
    public $deskripsi;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($judul, $gambar, $deskripsi)
    {
        $this->judul = $judul;
        $this->gambar = $gambar;
        $this->deskripsi = $deskripsi;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}