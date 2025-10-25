<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // Validate input
    $request->validate([
        'email_or_phone' => 'required|string',
        'password' => 'required|string',
    ]);

    $loginInput = $request->input('email_or_phone');
    $password = $request->input('password');

    // Detect if input is email or phone
    $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

    // Attempt login
    if (Auth::attempt([$loginField => $loginInput, 'password' => $password])) {
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    return back()->withErrors([
        'email_or_phone' => 'The provided credentials do not match our records.',
    ]);
}

}
