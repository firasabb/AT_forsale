<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ArtCard extends Component
{

    /**
     * 
     * The object to pass to the component
     * 
     * @var Object
     * 
     */

    public $art;


    /**
     * Create a new component instance.
     *  
     * @param Object $art
     * 
     * @return void
     */
    public function __construct($art)
    {
        $this->art = $art;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.art-card');
    }
}
