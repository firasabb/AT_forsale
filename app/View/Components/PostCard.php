<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PostCard extends Component
{

    /**
     * 
     * The object to pass to the component
     * 
     * @var Object
     * 
     */

    public $post;


    /**
     * Create a new component instance.
     *  
     * @param Object $post
     * 
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.post-card');
    }
}
