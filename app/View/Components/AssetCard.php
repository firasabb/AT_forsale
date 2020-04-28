<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AssetCard extends Component
{

    /**
     * 
     * The object to pass to the component
     * 
     * @var Object
     * 
     */

    public $asset;


    /**
     * Create a new component instance.
     *  
     * @param Object $asset
     * 
     * @return void
     */
    public function __construct($asset)
    {
        $this->asset = $asset;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.asset-card');
    }
}
