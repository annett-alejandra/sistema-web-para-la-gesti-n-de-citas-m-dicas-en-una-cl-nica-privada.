<?php

namespace App\Http\Controllers\Paquetes\P2_PersonalMedico\CU06_GestionarHorariosAtencion;

use App\Http\Controllers\Controller;
use App\Services\Paquetes\P2_PersonalMedico\HorarioService;
use App\Services\Paquetes\P2_PersonalMedico\MedicoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HorariosController extends Controller
{
    private const DIAS = [
        1 => 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo',
    ];

    public function __construct(private HorarioService $service) {}

    public function index(Request $request, MedicoService $medicoService): View
    {
        $gestionaHorarios = $request->user()->can('recepcion');
        $filtro = $request->integer('id_medico');
        $idMedico = $gestionaHorarios ? ($filtro > 0 ? $filtro : null) : $request->user()->id_medico;

        return view('Paquetes.P2_PersonalMedico.CU06_GestionarHorariosAtencion.index', [
            'horarios' => $this->service->listar($idMedico),
            'medicos' => $gestionaHorarios ? $medicoService->listar() : [],
            'idMedico' => $idMedico,
            'dias' => self::DIAS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'id_medico' => 'required|integer|exists:medicos,id_medico',
            'dia_semana' => 'required|integer|min:1|max:7',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'duracion_cita_minutos' => 'required|integer|min:5|max:240',
        ]);

        return $this->procesarFormulario(function () use ($request, $datos) {
            $this->service->crear($datos, $request->user()->getKey());

            return redirect()->route('horarios.index', ['id_medico' => $datos['id_medico']])
                ->with('status', 'Horario de atención configurado.');
        });
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        return $this->procesarFormulario(function () use ($request, $id) {
            $this->service->eliminar($id, $request->user()->getKey());

            return redirect()->route('horarios.index')->with('status', 'Horario eliminado.');
        });
    }
}
