<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => is_string($request->name) ? trim($request->name) : $request->name,
            'email' => is_string($request->email) ? strtolower(trim($request->email)) : $request->email,
        ]);

        $datos = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:80', "regex:/^[\\pL\\s'.-]+$/u"],
            'email' => ['required', 'string', 'lowercase', 'email:rfc', 'max:120', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.regex' => 'El nombre solo puede contener letras, espacios, apóstrofes, puntos y guiones.',
            'email.unique' => 'Ya existe una cuenta registrada con ese correo electrónico.',
        ], [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ]);

        $user = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
