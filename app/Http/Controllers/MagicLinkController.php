<?php

namespace App\Http\Controllers;

use App\Mail\RequesterMagicLinkMail;
use App\Models\MagicLink;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class MagicLinkController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => ' ',
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

            Mail::to($user->email)->send(
                new RequesterMagicLinkMail($token)
            );
            $message = 'Enviamos um link de acesso para o seu e-mail.';
            $type = 'success';
            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            report($t);
            $message = 'Ocorreu algum erro ao enviar seu email de acesso.<br>' . $t->getMessage();
            $type = 'error';
        }

        return back()->with([
            'type' => $type,
            'message' => $message,
        ]);
    }

    public function authenticate(string $token)
    {
        try {
            $magicLink = MagicLink::where('token', $token)->firstOrFail();
            if ($magicLink->isExpired()) {
                $magicLink->delete();

                session()->flash('type', 'warning');
                session()->flash('message', 'Este link expirou. Solicite um novo acesso.');

                return redirect()
                    ->route('start');
            }

            Auth::login($magicLink->user);
            $magicLink->delete();

            return redirect()->route('applicant.dashboard.index');
        } catch ( ModelNotFoundException $e) {
            session()->flash('type', 'warning');
            session()->flash('message', 'Este link expirou. Solicite um novo acesso.');
            return redirect()->route('start');
        }
    }
}
