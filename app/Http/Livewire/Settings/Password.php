<?php

namespace App\Http\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Password extends Component
{
    public $password = '';
    public $confirmPassword = '';
    public $state = null;

    public function render()
    {
        return view('livewire.settings.password');
    }

    public function save()
    {
        if ($this->password = $this->confirmPassword && strlen($this->password) >= 6) {
            $user = Auth::user();
            $user->password = Hash::make($this->password);
            $user->save();
            $this->state = 'saved';
            $this->password = '';
            $this->confirmPassword = '';
        }
    }

    public function resetState()
    {
        $this->state = null;
    }
}
