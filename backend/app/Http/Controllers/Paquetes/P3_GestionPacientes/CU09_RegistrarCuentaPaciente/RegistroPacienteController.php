<?php

namespace App\Http\Controllers\Paquetes\P3_GestionPacientes\CU09_RegistrarCuentaPaciente;

use App\Http\Controllers\Controller;
use App\Services\Paquetes\P3_GestionPacientes\RegistroPacienteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistroPacienteController extends Controller
{
    public function register(Request $request, RegistroPacienteService $service): RedirectResponse
    {
        $datos = $request->validate([
            'ci' => 'required|string|max:50',
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|string|max:50',
            'correo' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:72|confirmed',
            'nombre_usuario' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:50',
            'direccion_domicilio' => 'nullable|string|max:255',
            'terminos' => 'accepted',
        ]);

        return $this->procesarFormulario(function () use ($datos, $service) {
            $service->registrar($datos);

            return redirect()->route('login')->with('status', 'Registro completado. El acceso a la cuenta se habilitará con CU01.');
        });
    }
}
