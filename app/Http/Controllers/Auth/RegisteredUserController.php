<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Niche;
use App\Models\NicheUser;
use App\Models\User;
use App\Notifications\NewUserRegistration;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $niches = Niche::where('is_enabled', true)
            ->orderBy('name', 'ASC')
            ->get();
        return view('auth.register', [
            'niches' => $niches,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'niche.*' => 'required|exists:niches,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $niches = array_keys($request->get('niche'));
        if (!empty($niches)) {
            foreach ($niches as $nicheId) {
                $record = new NicheUser;
                $record->niche_id = $nicheId;
                $record->user_id = $user->id;
                $record->save();
            }
        }

        event(new Registered($user));

        Auth::login($user);

        Notification::route('slack', config('services.slack.notification'))
            ->notify(new NewUserRegistration($user));

        return redirect(RouteServiceProvider::HOME);
    }
}
