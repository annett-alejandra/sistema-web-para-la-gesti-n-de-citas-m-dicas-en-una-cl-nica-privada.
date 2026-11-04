<?php

namespace Tests\Feature;

use App\Models\Paquetes\P1_SeguridadAdministracion\Especialidad;
use App\Models\Paquetes\P1_SeguridadAdministracion\Rol;
use App\Models\Paquetes\P2_PersonalMedico\Medico;
use App\Models\Paquetes\P4_GestionCitas\CitaMedica;
use App\Models\User;
use App\Services\Paquetes\P1_SeguridadAdministracion\AuthService;
use App\Services\Paquetes\P1_SeguridadAdministracion\EspecialidadService;
use App\Services\Paquetes\P2_PersonalMedico\HorarioService;
use App\Services\Paquetes\P2_PersonalMedico\MedicoService;
use App\Services\Paquetes\P3_GestionPacientes\RegistroPacienteService;
use App\Services\Paquetes\P4_GestionCitas\CitaMedicaService;
use App\Services\Paquetes\P4_GestionCitas\DisponibilidadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\PresenceVerifierInterface;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PortalClinicoTest extends TestCase
{
    private const CODIGO = '3c352c78-974a-40c9-8bb9-022a48f638fe';

    protected function setUp(): void
    {
        parent::setUp();

        // Las pruebas HTTP sustituyen los servicios o la verificación de FK. Cualquier SQL accidental falla.
        config(['database.default' => 'blocked', 'database.connections.blocked' => ['driver' => 'blocked']]);
        DB::extend('blocked', static fn () => throw new \LogicException('SQL fuera del alcance de la prueba HTTP.'));
    }

    public static function rutasPrivadas(): array
    {
        return [
            ['GET', '/panel'],
            ['GET', '/usuarios'],
            ['POST', '/usuarios'],
            ['PUT', '/usuarios/1'],
            ['PATCH', '/usuarios/1/estado'],
            ['GET', '/especialidades'],
            ['POST', '/especialidades'],
            ['PUT', '/especialidades/1'],
            ['PATCH', '/especialidades/1/estado'],
            ['GET', '/medicos'],
            ['POST', '/medicos'],
            ['PUT', '/medicos/1'],
            ['PATCH', '/medicos/1/estado'],
            ['GET', '/horarios'],
            ['POST', '/horarios'],
            ['DELETE', '/horarios/1'],
            ['GET', '/pacientes'],
            ['POST', '/pacientes'],
            ['PUT', '/pacientes/1'],
            ['PATCH', '/pacientes/1/estado'],
            ['GET', '/disponibilidad'],
            ['GET', '/citas'],
            ['GET', '/citas/'.self::CODIGO],
            ['POST', '/citas'],
            ['POST', '/cerrar-sesion'],
        ];
    }

    #[DataProvider('rutasPrivadas')]
    public function test_visitantes_no_acceden_a_las_rutas_privadas(string $method, string $uri): void
    {
        $this->call($method, $uri)->assertRedirectToRoute('login');
    }

    public function test_react_api_no_publica_los_controladores_y_cu01_solo_presenta_su_vista(): void
    {
        $this->get('/api/usuarios')->assertNotFound();
        $this->get('/api/pacientes')->assertNotFound();
        $this->get('/api/citas/'.self::CODIGO)->assertNotFound();
        $this->post('/api/auth/login')->assertNotFound();
        $this->post('/api/auth/logout')->assertNotFound();
        $this->post('/api/registro-paciente')->assertNotFound();
        $this->post('/iniciar-sesion')->assertMethodNotAllowed();

        $this->get(route('login'))->assertOk()
            ->assertSee('Iniciar sesión')
            ->assertSee('aria-describedby="acceso-pendiente" disabled', false);
    }

    public function test_paciente_y_cuenta_inactiva_no_pueden_usar_acciones_administrativas(): void
    {
        $this->actingAs($this->usuario('Paciente', idPaciente: 27));

        $this->get(route('panel'))->assertOk()->assertDontSee(route('usuarios.index'));
        $this->get(route('usuarios.index'))->assertForbidden();
        $this->post(route('especialidades.store'))->assertForbidden();
        $this->patch(route('pacientes.estado', 1))->assertForbidden();

        $cuentaInactiva = $this->usuario('Administrador');
        $cuentaInactiva->estado = false;
        $this->actingAs($cuentaInactiva)->get(route('usuarios.index'))->assertForbidden();
    }

    public function test_medico_solo_ve_su_horario_y_no_puede_gestionar_turnos_ni_reservar(): void
    {
        $service = $this->mock(HorarioService::class);
        $service->shouldReceive('listar')->once()->with(9)->andReturn(new Collection);

        $this->actingAs($this->usuario('Médico', idMedico: 9))
            ->get(route('horarios.index', ['id_medico' => 8]))
            ->assertOk()
            ->assertSee('Horarios y turnos de atención')
            ->assertDontSee('Guardar turno');

        $this->delete(route('horarios.destroy', 1))->assertForbidden();
        $this->post(route('citas.store'))->assertForbidden();
    }

    public function test_admin_renderiza_especialidad_y_su_identidad_prevalece_en_la_auditoria(): void
    {
        $especialidad = new Especialidad(['nombre_especialidad' => '<Cirugía>', 'estado' => true]);
        $especialidad->id_especialidad = 3;
        $service = $this->mock(EspecialidadService::class);
        $service->shouldReceive('listar')->once()->with(false)->andReturn(new Collection([$especialidad]));
        $service->shouldReceive('actualizar')->once()->with(3, ['nombre_especialidad' => 'Cirugía'], 18)->andReturn($especialidad);

        $this->actingAs($this->usuario('Administrador'))
            ->get(route('especialidades.index', ['editar' => 3]))
            ->assertOk()
            ->assertSee('Modificar especialidad')
            ->assertSee('&lt;Cirugía&gt;', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="_method" value="PUT"', false);

        $this->put(route('especialidades.update', 3), [
            'nombre_especialidad' => 'Cirugía',
            'operador_id' => 666,
        ])->assertRedirectToRoute('especialidades.index')->assertSessionHas('status');
    }

    public function test_cu09_valida_el_consentimiento_y_el_registro_no_abre_sesion(): void
    {
        $this->get(route('registro.index'))->assertOk()->assertSee('Registro de cuenta de paciente')
            ->assertSee('name="_token"', false);

        $datos = [
            'ci' => '99999999',
            'nombre_completo' => 'Paciente de prueba',
            'fecha_nacimiento' => '1990-01-01',
            'genero' => 'Otro',
            'correo' => 'paciente@example.test',
            'password' => 'prueba-segura',
            'password_confirmation' => 'prueba-segura',
        ];

        $this->fromRoute('registro.index')->post(route('registro.store'), $datos)
            ->assertRedirectToRoute('registro.index')
            ->assertSessionHasErrors('terminos')
            ->assertSessionMissing('_old_input.password')
            ->assertSessionMissing('_old_input.password_confirmation');

        $this->mock(RegistroPacienteService::class)->shouldReceive('registrar')->once()
            ->with(Mockery::on(fn ($data) => $data['ci'] === $datos['ci'] && $data['terminos'] === '1'))
            ->andReturn(new User);

        $this->post(route('registro.store'), $datos + ['terminos' => '1'])
            ->assertRedirectToRoute('login')->assertSessionHas('status');
        $this->assertGuest();
    }

    public function test_cu10_presenta_cupos_y_prefija_el_formulario_cu11(): void
    {
        $this->permitirConsultasDeExistencia();
        $this->prepararCatalogos();
        $fecha = now()->addDay()->toDateString();
        $this->mock(DisponibilidadService::class)->shouldReceive('consultar')->once()->with(3, null, $fecha)->andReturn([
            [
                'medico' => ['id_medico' => 7, 'nombre_completo' => 'Médico de prueba'],
                'especialidad' => ['id_especialidad' => 3, 'nombre_especialidad' => 'Pediatría'],
                'fecha' => $fecha,
                'total_cupos' => 1,
                'cupos' => [['hora_inicio' => '08:00', 'hora_fin' => '08:30', 'duracion_minutos' => 30]],
            ],
        ]);

        $seleccion = [
            'id_medico' => 7, 'id_especialidad' => 3, 'fecha_cita' => $fecha,
            'hora_cita' => '08:00', 'duracion_minutos' => 30,
        ];

        $this->actingAs($this->usuario('Paciente', idPaciente: 27))
            ->get(route('disponibilidad.index', ['id_especialidad' => 3, 'fecha' => $fecha]))
            ->assertOk()->assertSee('1 turnos libres')->assertSee(route('citas.index', $seleccion));

        $this->mock(CitaMedicaService::class)->shouldReceive('listar')->once()->with(27, null, null)->andReturn(new Collection);
        $this->get(route('citas.index', $seleccion + ['paciente' => 999, 'medico' => 999]))
            ->assertOk()
            ->assertSee('value="7" selected', false)
            ->assertSee('value="08:00"', false)
            ->assertDontSee('name="id_paciente"', false);
    }

    public function test_cu11_bloquea_detalle_ajeno_y_reemplaza_paciente_y_operador_del_payload(): void
    {
        $this->permitirConsultasDeExistencia();
        $cita = new CitaMedica([
            'codigo_unico' => self::CODIGO,
            'id_paciente' => 27,
            'id_medico' => 7,
            'fecha_cita' => now()->addDay(),
            'hora_cita' => '08:00:00',
            'duracion_minutos' => 30,
            'motivo' => '<motivo reservado>',
        ]);
        foreach (['paciente', 'medico', 'especialidad', 'estadoCita'] as $relacion) {
            $cita->setRelation($relacion, null);
        }

        $service = $this->mock(CitaMedicaService::class);
        $service->shouldReceive('buscarPorCodigo')->twice()->with(self::CODIGO)->andReturn($cita);
        $service->shouldReceive('programar')->once()->with(
            Mockery::on(fn ($data) => $data['id_paciente'] === 27 && ! isset($data['operador_id'])),
            18,
        )->andReturn($cita);

        $this->actingAs($this->usuario('Paciente', idPaciente: 81))->get(route('citas.show', self::CODIGO))
            ->assertNotFound()->assertDontSee('motivo reservado');

        $this->actingAs($this->usuario('Paciente', idPaciente: 27))
            ->post(route('citas.store'), [
                'id_paciente' => 81, 'operador_id' => 666, 'id_medico' => 7, 'id_especialidad' => 3,
                'fecha_cita' => $cita->fecha_cita->toDateString(), 'hora_cita' => '08:00',
            ])->assertRedirectToRoute('citas.show', self::CODIGO)->assertSessionHas('status');

        $this->get(route('citas.show', self::CODIGO))->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertSee(self::CODIGO)->assertSee('&lt;motivo reservado&gt;', false);
    }

    public function test_error_de_negocio_retorna_al_formulario_con_datos_y_mensaje(): void
    {
        $this->mock(EspecialidadService::class)->shouldReceive('crear')->once()
            ->with(['nombre_especialidad' => 'Pediatría'], 18)
            ->andThrow(new InvalidArgumentException('La especialidad ya existe.'));

        $this->actingAs($this->usuario('Administrador'))->fromRoute('especialidades.index')
            ->post(route('especialidades.store'), ['nombre_especialidad' => 'Pediatría'])
            ->assertRedirectToRoute('especialidades.index')
            ->assertSessionHasErrors(['operacion' => 'La especialidad ya existe.'])
            ->assertSessionHasInput('nombre_especialidad', 'Pediatría');
    }

    public function test_cu02_invalida_la_sesion_y_audita_al_usuario_autenticado(): void
    {
        $this->mock(AuthService::class)->shouldReceive('logout')->once()->with(18);
        $token = str_repeat('a', 40);

        $this->actingAs($this->usuario('Administrador'))
            ->withSession(['_token' => $token, 'dato' => 'privado'])
            ->post(route('logout'), ['id_usuario' => 666])
            ->assertRedirectToRoute('login')
            ->assertSessionMissing('dato')
            ->assertSessionHas('_token', fn ($nuevoToken) => $nuevoToken !== $token);

        $this->assertGuest();
    }

    private function usuario(string $rol, ?int $idPaciente = null, ?int $idMedico = null): User
    {
        $user = new User([
            'nombre_usuario' => 'usuario_prueba', 'estado' => true,
            'id_paciente' => $idPaciente, 'id_medico' => $idMedico,
        ]);
        $user->id_usuario = 18;
        $user->setRelation('rol', new Rol(['nombre_rol' => $rol]));

        return $user;
    }

    private function prepararCatalogos(): void
    {
        $especialidad = new Especialidad(['nombre_especialidad' => 'Pediatría']);
        $especialidad->id_especialidad = 3;
        $medico = new Medico(['nombre_completo' => 'Médico de prueba', 'registro_profesional' => 'TEST']);
        $medico->id_medico = 7;

        $this->mock(EspecialidadService::class)->shouldReceive('listar')->with(true)->andReturn(new Collection([$especialidad]));
        $this->mock(MedicoService::class)->shouldReceive('listar')->with(true)->andReturn(new Collection([$medico]));
    }

    private function permitirConsultasDeExistencia(): void
    {
        $verifier = Mockery::mock(PresenceVerifierInterface::class);
        $verifier->shouldReceive('getCount')->andReturn(1);
        $this->app['validator']->setPresenceVerifier($verifier);
    }
}
