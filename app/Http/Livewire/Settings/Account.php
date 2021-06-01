<?php

namespace App\Http\Livewire\Settings;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Str;

class Account extends Component
{
    use WithFileUploads;

    public $user;
    public $notifyNewProducts;
    public $notifyNewTools;
    public $state = false;
    public $photo = null;
    public $key = null;
    public $confirmDeletion = false;
    public $deletionKey = 'delete';
    public $deletionInput = '';

    protected $rules = [
        'user.name' => 'required|string',
        'user.about' => 'sometimes|string',
        'user.company' => 'sometimes|string|max:255',
        'deletionInput' => 'sometimes|string',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->notifyNewProducts = $this->user->notify_new_products;
        $this->notifyNewTools = $this->user->notify_new_tools;
        $this->key = $this->user->photo_s3_key;
    }

    public function render()
    {
        return view('livewire.settings.account');
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:4096', // 4MB Max
        ]);

        $name = Str::uuid();
        $this->photo->storePubliclyAs('photos', $name . '.' . $this->photo->extension(), 's3');
        $this->key = 'photos/' . $name . '.' . $this->photo->extension();
    }

    public function save()
    {
        $this->validate();
        $this->user->photo_s3_key = $this->key;
        $this->user->notify_new_products = $this->notifyNewProducts;
        $this->user->notify_new_tools = $this->notifyNewTools;
        $this->user->save();
        $this->state = 'saved';
    }

    public function resetState()
    {
        $this->state = '';
    }

    public function getPhotoUrlProperty()
    {
        return $this->user->photo_url;
    }

    public function toggle($key)
    {
        switch ($key) {
            case 'notify_new_products':
                $this->notifyNewProducts = !$this->notifyNewProducts;
                break;
            case 'notify_new_tools':
                $this->notifyNewTools = !$this->notifyNewTools;
                break;
            default:
                break;
        }
    }

    public function askForDeletion()
    {
        $this->confirmDeletion = true;
        $this->deletionInput = '';
    }

    public function cancelDeletion()
    {
        $this->confirmDeletion = false;
    }

    public function deleteUser()
    {
        if ($this->deletionKey === $this->deletionInput) {
            $this->user->delete();
            return redirect(route('login'));
        }
    }
}
