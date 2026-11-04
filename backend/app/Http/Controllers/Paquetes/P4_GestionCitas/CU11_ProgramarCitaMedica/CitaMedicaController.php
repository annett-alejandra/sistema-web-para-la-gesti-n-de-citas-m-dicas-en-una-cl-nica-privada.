<?php

namespace App\Http\Controllers\Paquetes\P4_GestionCitas\CU11_ProgramarCitaMedica;

use App\Http\Controllers\Controller;
use App\Models\Paquetes\P3_GestionPacientes\Paciente;
use App\Services\Paquetes\P1_SeguridadAdministracion\EspecialidadService;
use App\Services\Paquetes\P2_PersonalMedico\MedicoService;
use App\Services\Paquetes\P4_GestionCitas\CitaMedicaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CitaMedicaController extends Controller
{
    public function __construct(private CitaMedicaService $service) {}

    public function index(Request $request, EspecialidadService $especialidades, MedicoService $medicos): View
    {
        $usuario = $request->user();
        $recepcion = $usuario->can('recepcion');
        $reserva = $usuario->can('reservar');
        $esPaciente = $usuario->rol->nombre_rol === 'Paciente';
        $filtros = $request->validate([
            'paciente' => 'nullable|integer|min:1',
            'medico' => 'nullable|integer|min:1',
            'fecha' => 'nullable|date_format:Y-m-d',
        ]);

        $idPaciente = $esPaciente ? $usuario->id_paciente : ($recepcion ? ($filtros['paciente'] ?? null) : null);
        $idMedico = $recepcion ? ($filtros['medico'] ?? null) : ($esPaciente ? null : $usuario->id_medico);

        return view('Paquetes.P4_GestionCitas.CU11_ProgramarCitaMedica.index', [
            'citas' => $this->service->listar($idPaciente, $idMedico, $filtros['fecha'] ?? null),
            'pacientes' => $recepcion ? Paciente::where('estado', true)->orderBy('nombre_completo')->get(['id_paciente', 'nombre_completo', 'ci']) : [],
            'especialidades' => $reserva ? $especialidades->listar(true) : [],
            'medicos' => $reserva ? $medicos->listar(true) : [],
            'filtros' => $filtros,
        ]);
    }

    public function show(Request $request, string $codigo): View
    {
        $cita = $this->service->buscarPorCodigo($codigo);
        $usuario = $request->user();

        if (! $usuario->can('recepcion')) {
            $propia = match ($usuario->rol->nombre_rol) {
                'Paciente' => $cita->id_paciente === $usuario->id_paciente,
                'Médico' => $cita->id_medico === $usuario->id_medico,
                default => false,
            };

            abort_unless($propia, 404);
        }

        return view('Paquetes.P4_GestionCitas.CU11_ProgramarCitaMedica.show', ['cita' => $cita]);
    }

    public function store(Request $request): RedirectResponse
    {
        $usuario = $request->user();
        if ($usuario->rol->nombre_rol === 'Paciente') {
            $request->merge(['id_paciente' => $usuario->id_paciente]);
        }

        $datos = $request->validate([
            'id_paciente' => 'bail|required|integer|exists:pacientes,id_paciente',
            'id_medico' => 'bail|required|integer|exists:medicos,id_medico',
            'id_especialidad' => 'bail|required|integer|exists:especialidades,id_especialidad',
            'fecha_cita' => 'required|date_format:Y-m-d',
            'hora_cita' => 'required|date_format:H:i',
            'duracion_minutos' => 'nullable|integer|min:5|max:180',
            'motivo' => 'nullable|string|max:500',
        ]);

        return $this->procesarFormulario(function () use ($usuario, $datos) {
            $cita = $this->service->programar($datos, $usuario->getKey());

            return redirect()->route('citas.show', $cita->codigo_unico)
                ->with('status', 'Cita médica programada.');
        });
    }
}
