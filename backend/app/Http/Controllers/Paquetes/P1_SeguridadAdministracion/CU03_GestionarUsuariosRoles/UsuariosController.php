<?php

namespace App\Http\Controllers\Paquetes\P1_SeguridadAdministracion\CU03_GestionarUsuariosRoles;

use App\Http\Controllers\Controller;
use App\Models\Paquetes\P2_PersonalMedico\Medico;
use App\Models\Paquetes\P3_GestionPacientes\Paciente;
use App\Services\Paquetes\P1_SeguridadAdministracion\UsuarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuariosController extends Controller
{
    public function __construct(private UsuarioService $service) {}

    public function index(Request $request): View
    {
        $usuarios = $this->service->listar();
        $idEditar = $request->integer('editar');

        return view('Paquetes.P1_SeguridadAdministracion.CU03_GestionarUsuariosRoles.index', [
            'usuarios' => $usuarios,
            'edicion' => $idEditar > 0 ? $usuarios->findOrFail($idEditar) : null,
            'roles' => $this->service->listarRoles(),
            'pacientes' => Paciente::orderBy('nombre_completo')->get(['id_paciente', 'nombre_completo', 'ci']),
            'medicos' => Medico::orderBy('nombre_completo')->get(['id_medico', 'nombre_completo']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas(false));

        return $this->procesarFormulario(function () use ($request, $datos) {
            $this->service->crear($datos, $request->user()->getKey());

            return redirect()->route('usuarios.index')->with('status', 'Usuario creado.');
        });
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $datos = $request->validate($this->reglas(true));

        return $this->procesarFormulario(function () use ($request, $id, $datos) {
            $this->service->actualizar($id, $datos, $request->user()->getKey());

            return redirect()->route('usuarios.index')->with('status', 'Usuario actualizado.');
        });
    }

    public function toggleEstado(Request $request, int $id): RedirectResponse
    {
        return $this->procesarFormulario(function () use ($request, $id) {
            $this->service->toggleEstado($id, $request->user()->getKey());

            return redirect()->route('usuarios.index')->with('status', 'Estado actualizado.');
        });
    }

    private function reglas(bool $edicion): array
    {
        $reglas = [
            'nombre_usuario' => 'required|string|max:100',
            'correo' => 'required|email|max:255',
            'password' => $edicion ? 'nullable|string|min:6|max:72' : 'required|string|min:6|max:72',
            'id_rol' => 'required|integer|exists:roles,id_rol',
            'estado' => 'sometimes|boolean',
        ];

        return $edicion ? $reglas : $reglas + [
            'id_paciente' => 'nullable|integer|exists:pacientes,id_paciente|prohibits:id_medico',
            'id_medico' => 'nullable|integer|exists:medicos,id_medico',
        ];
    }
}
