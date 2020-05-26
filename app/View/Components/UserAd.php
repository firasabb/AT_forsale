<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserAd extends Component
{

    public $user;
    public $userAd;
    public $showAsModal;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, $userAd, $showAsModal = true)
    {
        $this->user = $user;
        $this->userAd = $userAd;
        $this->showAsModal = $showAsModal;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.user-ad');
    }
}
