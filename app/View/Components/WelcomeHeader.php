<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WelcomeHeader extends Component
{
    public $user;

    public function __construct()
    {
        
    }

    public function render()
    {
        return view('components.welcome-header');
    }
}
