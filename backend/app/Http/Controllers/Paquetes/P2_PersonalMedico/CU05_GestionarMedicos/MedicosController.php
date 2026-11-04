<?php

namespace App\Http\Controllers\Paquetes\P2_PersonalMedico\CU05_GestionarMedicos;

use App\Http\Controllers\Controller;
use App\Services\Paquetes\P1_SeguridadAdministracion\EspecialidadService;
use App\Services\Paquetes\P2_PersonalMedico\MedicoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicosController extends Controller
{
    public function __construct(private MedicoService $service) {}

    public function index(Request $request, EspecialidadService $especialidadService): View
    {
        $medicos = $this->service->listar($request->boolean('solo_activos'));
        $idEditar = $request->integer('editar');

        return view('Paquetes.P2_PersonalMedico.CU05_GestionarMedicos.index', [
            'medicos' => $medicos,
            'edicion' => $idEditar > 0 ? $medicos->findOrFail($idEditar) : null,
            'especialidades' => $especialidadService->listar(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());
        // Al desmarcar todas las casillas, el navegador omite el campo.
        $datos['especialidades'] = $datos['especialidades'] ?? [];

        return $this->procesarFormulario(function () use ($request, $datos) {
            $this->service->crear($datos, $request->user()->getKey());

            return redirect()->route('medicos.index')->with('status', 'Médico registrado.');
        });
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $datos = $request->validate($this->reglas());
        // Al desmarcar todas las casillas, el navegador omite el campo.
        $datos['especialidades'] = $datos['especialidades'] ?? [];

        return $this->procesarFormulario(function () use ($request, $id, $datos) {
            $this->service->actualizar($id, $datos, $request->user()->getKey());

            return redirect()->route('medicos.index')->with('status', 'Médico actualizado.');
        });
    }

    public function toggleEstado(Request $request, int $id): RedirectResponse
    {
        return $this->procesarFormulario(function () use ($request, $id) {
            $this->service->toggleEstado($id, $request->user()->getKey());

            return redirect()->route('medicos.index')->with('status', 'Estado actualizado.');
        });
    }

    private function reglas(): array
    {
        return [
            'nombre_completo' => 'required|string|max:255',
            'registro_profesional' => 'required|string|max:100',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'especialidades' => 'nullable|array',
            'especialidades.*' => 'integer|exists:especialidades,id_especialidad',
            'estado' => 'sometimes|boolean',
        ];
    }
}
