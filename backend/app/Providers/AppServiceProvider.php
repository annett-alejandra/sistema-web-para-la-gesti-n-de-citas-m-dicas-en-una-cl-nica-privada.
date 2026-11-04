<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('administracion', fn (User $user) => $user->estado
            && $user->rol?->nombre_rol === 'Administrador');

        Gate::define('recepcion', fn (User $user) => $user->estado
            && in_array($user->rol?->nombre_rol, ['Administrador', 'Recepcionista'], true));

        Gate::define('horarios', fn (User $user) => $user->can('recepcion')
            || ($user->estado && $user->rol?->nombre_rol === 'Médico' && $user->id_medico > 0));

        Gate::define('reservar', fn (User $user) => $user->can('recepcion')
            || ($user->estado && $user->rol?->nombre_rol === 'Paciente' && $user->id_paciente > 0));

        Gate::define('portal', fn (User $user) => $user->can('horarios') || $user->can('reservar'));
    }
}
