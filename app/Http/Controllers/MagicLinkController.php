<?php

namespace App\Http\Controllers;

use App\Mail\RequesterMagicLinkMail;
use App\Models\MagicLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MagicLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->email,
                'password' => bcrypt($request->email),
            ]
        );

        if (!$user->hasRole('applicant')) {
            $user->assignRole('applicant');
        }

        // Invalida tokens anteriores
        MagicLink::where('user_id', $user->id)->delete();

        // Cria token
        $token = Str::uuid()->toString();

        MagicLink::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(30),
        ]);

        // Envia e-mail
        Mail::to($user->email)->send(
            new RequesterMagicLinkMail($token)
        );

        return back()->with(
            'status',
            'Enviamos um link de acesso para o seu e-mail.'
        );
    }

    public function authenticate(string $token)
    {
        $magicLink = MagicLink::where('token', $token)->firstOrFail();

        if ($magicLink->isExpired()) {
            $magicLink->delete();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Este link expirou. Solicite um novo acesso.',
                ]);
        }

        Auth::login($magicLink->user);
        $magicLink->delete();

        return redirect()->route('applicant.dashboard');
    }
}
