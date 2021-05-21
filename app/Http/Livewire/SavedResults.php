<?php

namespace App\Http\Livewire;

use App\Models\SavedResult;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SavedResults extends Component
{
    public $query = '';
    public $results = [];

    public function render()
    {
        if (empty($this->query)) {
            $this->results = SavedResult::where('user_id', Auth::id())
                ->orderByDesc('created_at')
                ->take(100)
                ->get();
        } else {
            $this->results = SavedResult::search($this->query)
                ->where('user_id', Auth::id())
                ->take(100)
                ->get();
        }

        return view('livewire.saved-results');
    }

    public function removeResult ($id)
    {
        SavedResult::where([
            'id' => $id,
            'user_id' => Auth::id(),
        ])->delete();
    }
}
