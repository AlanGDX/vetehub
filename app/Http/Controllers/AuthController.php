<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->withCookie($this->jwtService->makeAuthCookie($user, $request->boolean('remember')));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'clinic_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'clinic_name' => $validated['clinic_name'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->regenerate();

        return redirect('/dashboard')
            ->withCookie($this->jwtService->makeAuthCookie($user, true));
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $user = $this->jwtService->userFromRequest($request);

        if ($user) {
            $user->increment('token_version');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->withCookie($this->jwtService->expireAuthCookie());
    }

    /**
     * Show the profile edit form.
     */
    public function editProfile()
    {
        return view('auth.profile');
    }

    /**
     * Update the user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'clinic_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Verificar contraseña actual si se está cambiando la contraseña
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->name = $validated['name'];
        $user->clinic_name = $validated['clinic_name'] ?? null;
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Perfil actualizado exitosamente.');
    }
}
