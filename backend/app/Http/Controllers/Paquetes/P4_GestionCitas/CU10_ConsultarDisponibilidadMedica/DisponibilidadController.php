<?php

namespace App\Http\Controllers\Paquetes\P4_GestionCitas\CU10_ConsultarDisponibilidadMedica;

use App\Http\Controllers\Controller;
use App\Services\Paquetes\P1_SeguridadAdministracion\EspecialidadService;
use App\Services\Paquetes\P2_PersonalMedico\MedicoService;
use App\Services\Paquetes\P4_GestionCitas\DisponibilidadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use InvalidArgumentException;

class DisponibilidadController extends Controller
{
    public function consultar(
        Request $request,
        DisponibilidadService $service,
        EspecialidadService $especialidades,
        MedicoService $medicos,
    ): View {
        $filtros = $request->only('id_especialidad', 'id_medico', 'fecha');
        $disponibilidad = null;
        $erroresConsulta = [];

        if ($filtros !== []) {
            $validacion = Validator::make($filtros, [
                'id_especialidad' => 'bail|required|integer|exists:especialidades,id_especialidad',
                'id_medico' => 'bail|nullable|integer|exists:medicos,id_medico',
                'fecha' => 'required|date_format:Y-m-d',
            ]);

            if ($validacion->fails()) {
                $erroresConsulta = $validacion->errors()->all();
            } else {
                try {
                    $disponibilidad = $service->consultar(
                        (int) $filtros['id_especialidad'],
                        isset($filtros['id_medico']) ? (int) $filtros['id_medico'] : null,
                        $filtros['fecha'],
                    );
                } catch (InvalidArgumentException $exception) {
                    $erroresConsulta = [$exception->getMessage()];
                }
            }
        }

        return view('Paquetes.P4_GestionCitas.CU10_ConsultarDisponibilidadMedica.index', [
            'especialidades' => $especialidades->listar(true),
            'medicos' => $medicos->listar(true),
            'filtros' => $filtros,
            'disponibilidad' => $disponibilidad,
            'erroresConsulta' => $erroresConsulta,
        ]);
    }
}
