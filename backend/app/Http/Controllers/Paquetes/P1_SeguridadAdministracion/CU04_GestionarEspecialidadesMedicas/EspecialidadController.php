<?php

namespace App\Http\Controllers\Paquetes\P1_SeguridadAdministracion\CU04_GestionarEspecialidadesMedicas;

use App\Http\Controllers\Controller;
use App\Services\Paquetes\P1_SeguridadAdministracion\EspecialidadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EspecialidadController extends Controller
{
    public function __construct(private EspecialidadService $service) {}

    public function index(Request $request): View
    {
        $especialidades = $this->service->listar($request->boolean('solo_activas'));
        $idEditar = $request->integer('editar');

        return view('Paquetes.P1_SeguridadAdministracion.CU04_GestionarEspecialidadesMedicas.index', [
            'especialidades' => $especialidades,
            'edicion' => $idEditar > 0 ? $especialidades->findOrFail($idEditar) : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        return $this->procesarFormulario(function () use ($request, $datos) {
            $this->service->crear($datos, $request->user()->getKey());

            return redirect()->route('especialidades.index')->with('status', 'Especialidad registrada.');
        });
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        return $this->procesarFormulario(function () use ($request, $id, $datos) {
            $this->service->actualizar($id, $datos, $request->user()->getKey());

            return redirect()->route('especialidades.index')->with('status', 'Especialidad actualizada.');
        });
    }

    public function toggleEstado(Request $request, int $id): RedirectResponse
    {
        return $this->procesarFormulario(function () use ($request, $id) {
            $this->service->toggleEstado($id, $request->user()->getKey());

            return redirect()->route('especialidades.index')->with('status', 'Estado actualizado.');
        });
    }

    private function reglas(): array
    {
        return [
            'nombre_especialidad' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|boolean',
        ];
    }
}
