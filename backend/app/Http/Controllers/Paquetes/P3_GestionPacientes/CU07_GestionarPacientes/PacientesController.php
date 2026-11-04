<?php

namespace App\Http\Controllers\Paquetes\P3_GestionPacientes\CU07_GestionarPacientes;

use App\Http\Controllers\Controller;
use App\Services\Paquetes\P3_GestionPacientes\PacienteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PacientesController extends Controller
{
    public function __construct(private PacienteService $service) {}

    public function index(Request $request): View
    {
        $filtros = $request->validate(['q' => 'nullable|string|max:255']);
        $pacientes = $this->service->listar($filtros['q'] ?? null, $request->boolean('solo_activos'));
        $idEditar = $request->integer('editar');

        return view('Paquetes.P3_GestionPacientes.CU07_GestionarPacientes.index', [
            'pacientes' => $pacientes,
            'edicion' => $idEditar > 0 ? $pacientes->findOrFail($idEditar) : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        return $this->procesarFormulario(function () use ($request, $datos) {
            $this->service->crear($datos, $request->user()->getKey());

            return redirect()->route('pacientes.index')->with('status', 'Ficha de paciente registrada.');
        });
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        return $this->procesarFormulario(function () use ($request, $id, $datos) {
            $this->service->actualizar($id, $datos, $request->user()->getKey());

            return redirect()->route('pacientes.index')->with('status', 'Ficha de paciente actualizada.');
        });
    }

    public function toggleEstado(Request $request, int $id): RedirectResponse
    {
        return $this->procesarFormulario(function () use ($request, $id) {
            $this->service->toggleEstado($id, $request->user()->getKey());

            return redirect()->route('pacientes.index')->with('status', 'Estado actualizado.');
        });
    }

    private function reglas(): array
    {
        return [
            'ci' => 'required|string|max:50',
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|string|max:50',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'direccion_domicilio' => 'nullable|string|max:255',
            'contacto_emergencia' => 'nullable|string|max:255',
            'telefono_emergencia' => 'nullable|string|max:50',
            'tipo_paciente' => 'nullable|string|max:100',
            'alergias' => 'nullable|string',
            'informacion_medica_basica' => 'nullable|string',
            'estado' => 'sometimes|boolean',
        ];
    }
}
