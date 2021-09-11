<?php

namespace App\Http\Livewire\Settings;

use App\Jobs\AddPropertiesToMailjet;
use App\Models\Niche;
use App\Models\NicheUser;
use Livewire\Component;

class NichePreferences extends Component
{
    public $niches;

    public $selectedNiches = [];

    public function mount()
    {
        $this->niches = Niche::where('is_enabled', true)
            ->orderBy('name', 'ASC')
            ->get();

        $userNiches = NicheUser::where('user_id', auth()->id())
            ->get()
            ->pluck('niche_id');

        foreach ($this->niches as $niche) {
            $this->selectedNiches[$niche->id] = false;
        }

        foreach ($userNiches as $niche) {
            $this->selectedNiches[$niche] = true;
        }
    }

    public function updatedSelectedNiches($selected, $nicheId)
    {
        $exists = NicheUser::where([
            'niche_id' => $nicheId,
            'user_id' => auth()->id(),
        ])->first();
        if ($selected) {
            if (!$exists) {
                $niche = new NicheUser;
                $niche->niche_id = $nicheId;
                $niche->user_id = auth()->id();
                $niche->save();
            }
        } else if ($exists) {
            $exists->delete();
        }

        dispatch(new AddPropertiesToMailjet(auth()->user()));
    }

    public function render()
    {
        return view('livewire.settings.niche-preferences');
    }
}
