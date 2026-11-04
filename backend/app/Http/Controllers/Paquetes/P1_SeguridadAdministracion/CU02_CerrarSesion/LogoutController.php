<?php

namespace App\Http\Controllers\Paquetes\P1_SeguridadAdministracion\CU02_CerrarSesion;

use App\Http\Controllers\Controller;
use App\Services\Paquetes\P1_SeguridadAdministracion\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request, AuthService $authService): RedirectResponse
    {
        $idUsuario = $request->user()->getKey();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $authService->logout($idUsuario);

        return redirect()->route('login')->with('status', 'Sesión cerrada correctamente.');
    }
}
