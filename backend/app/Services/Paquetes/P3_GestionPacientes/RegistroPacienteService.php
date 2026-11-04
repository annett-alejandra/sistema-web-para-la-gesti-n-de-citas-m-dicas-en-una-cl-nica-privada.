<?php

namespace App\Services\Paquetes\P3_GestionPacientes;

use App\Models\Paquetes\P1_SeguridadAdministracion\Rol;
use App\Models\Paquetes\P3_GestionPacientes\Paciente;
use App\Models\User;
use App\Services\Paquetes\P1_SeguridadAdministracion\AuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;

class RegistroPacienteService
{
    public function registrar(array $datos): User
    {
        $ci = trim($datos['ci']);
        $correo = trim($datos['correo']);
        $nombreUsuario = trim($datos['nombre_usuario'] ?? explode('@', $correo)[0]);

        return DB::transaction(function () use ($datos, $ci, $correo, $nombreUsuario) {
            // Una ficha existente requiere verificación de identidad antes de vincular una cuenta.
            if (User::where('correo', $correo)->exists() || Paciente::where('ci', $ci)->exists()) {
                throw new InvalidArgumentException('No se pudo completar el registro. Contacte con admisiones para verificar sus datos.');
            }

            $rolPaciente = Rol::where('nombre_rol', 'Paciente')->first();
            if (! $rolPaciente) {
                throw new InvalidArgumentException('El registro de pacientes no está disponible temporalmente.');
            }

            if (User::where('nombre_usuario', $nombreUsuario)->exists()) {
                $nombreUsuario = Str::substr($nombreUsuario, 0, 95).'_'.Str::random(4);
            }

            $paciente = Paciente::create([
                'ci' => $ci,
                'nombre_completo' => trim($datos['nombre_completo']),
                'fecha_nacimiento' => $datos['fecha_nacimiento'],
                'genero' => $datos['genero'],
                'correo' => $correo,
                'telefono' => $datos['telefono'] ?? null,
                'direccion_domicilio' => $datos['direccion_domicilio'] ?? null,
                'tipo_paciente' => 'General',
                'estado' => true,
            ]);

            $usuario = User::create([
                'nombre_usuario' => $nombreUsuario,
                'correo' => $correo,
                'password_hash' => Hash::make($datos['password']),
                'estado' => true,
                'id_rol' => $rolPaciente->id_rol,
                'id_paciente' => $paciente->id_paciente,
            ]);

            AuditService::registrar(
                $usuario->id_usuario,
                'AUTOREGISTRO_PACIENTE',
                'usuarios',
                $usuario->id_usuario,
                ['id_paciente' => $paciente->id_paciente]
            );

            return $usuario;
        });
    }
}
