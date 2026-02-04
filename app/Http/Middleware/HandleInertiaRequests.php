<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Permission\Models\Role;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');
        $user = $request->user();
        if ($user) {
            $roles = $user->getAllRoles();
            $userRoles = $user->getAllRolesAlias();
            $permissions = $user->getAllPermissions()->pluck('name');
        } else {
            $roles = null;
            $userRoles = null;
            $permissions = null;
        }
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'roles' => $roles,
            'user_roles' => $userRoles,
            'permissions' => $permissions,
            'list_roles' => Role::query()->pluck('name', 'id'),
            'flash' => fn () => [
                'status' => session('status'),
                'error'  => session('error'),
                'type'  => session('type'),
                'message'  => session('message'),
            ],
        ];
    }
}
