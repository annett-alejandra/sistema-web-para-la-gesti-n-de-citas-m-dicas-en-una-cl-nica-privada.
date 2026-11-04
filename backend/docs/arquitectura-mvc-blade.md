# Arquitectura MVC con Laravel y Blade

Clínica Vida Salud concentra la aplicación en `backend/`. Las rutas del grupo `web` reciben las peticiones, aplican sesión, CSRF y autorización, y envían los formularios a los controladores del paquete correspondiente. Los controladores validan con Laravel, invocan los servicios de negocio y devuelven HTML o redirecciones con mensajes temporales. Los servicios usan los modelos Eloquent y las restricciones de PostgreSQL 16.

| Capa | Ubicación |
| --- | --- |
| Controladores | `app/Http/Controllers/Paquetes/` |
| Servicios | `app/Services/Paquetes/` |
| Diez modelos Eloquent | `app/Models/` |
| Vistas por caso de uso | `resources/views/Paquetes/` |
| Layout y componentes | `resources/views/layouts/` y `resources/views/components/` |
| Rutas con sesión | `routes/web.php` |
| CSS y JavaScript estáticos | `public/css/` y `public/js/` |

## Inventario de los 19 casos de uso

La rama de partida tenía diez pantallas React. `App.jsx` mantenía el módulo activo, la cuenta y el turno seleccionado en el cliente. Los hooks de los paquetes llamaban a `apiClient.js` para consumir las rutas JSON de Laravel. Blade ya aportaba la portada y dos vistas parciales para CU03 y CU11. La integración mantiene las diez interfaces y sus flujos de datos con las salvedades siguientes.

| Paquete | CU | Pantalla de origen y resultado en Blade |
| --- | --- | --- |
| P1 Seguridad y administración | CU01 Iniciar sesión | `UI_Autenticacion` → `/iniciar-sesion`. Vista preparada. Falta autenticar con el guard web y crear la sesión. |
| P1 | CU02 Cerrar sesión | `UI_MenuPrincipal` → `/panel` y navegación compartida. El POST de salida invalida una sesión real y reutiliza la auditoría existente. |
| P1 | CU03 Gestionar usuarios y roles | `UI_Usuario` y vista Blade previa → `/usuarios`. Tabla, creación, modificación, asignación de rol y cambio de estado. |
| P1 | CU04 Gestionar especialidades médicas | `UI_Especialidad` → `/especialidades`. Catálogo con descripción, formulario y cambio de estado. |
| P2 Personal médico | CU05 Gestionar médicos | `UI_Medico` → `/medicos`. Listado, contacto, matrícula, selección de especialidades y cambio de estado. |
| P2 | CU06 Gestionar horarios de atención | `UI_HorarioAtencion` → `/horarios`. Filtro, alta y eliminación de turnos para recepción y administración. Los médicos consultan su horario. |
| P3 Gestión de pacientes | CU07 Gestionar pacientes | `UI_Paciente` → `/pacientes`. Directorio, búsqueda por nombre o CI, alta, edición y cambio de estado. |
| P3 | CU08 Gestionar perfil de paciente | Carpeta reservada. Pendiente de implementación. |
| P3 | CU09 Registrar cuenta de paciente | `UI_RegistroPaciente` → `/registro-paciente`. Formulario y alta de cuenta con ficha nueva. Vincular una ficha preexistente requiere verificar la identidad. El acceso posterior depende de CU01. |
| P4 Gestión de citas | CU10 Consultar disponibilidad médica | `UI_Disponibilidad` → `/disponibilidad`. Filtra por especialidad, médico y fecha. Los roles que pueden reservar abren CU11 con el turno preseleccionado. |
| P4 | CU11 Programar cita médica | `UI_CitaMedica` y vista Blade previa → `/citas` y detalle por código. Formulario, listado por alcance y confirmación con UUID. |
| P4 | CU12 Reprogramar cita médica | Carpeta reservada. Pendiente de implementación. |
| P4 | CU13 Cancelar cita médica | Carpeta reservada. Pendiente de implementación. |
| P5 Control de atención | CU14 Registrar confirmación, asistencia o inasistencia | Carpeta reservada. Pendiente de implementación. |
| P5 | CU15 Registrar cita atendida | Carpeta reservada. Pendiente de implementación. |
| P6 Consultas e historiales | CU16 Consultar agenda médica | Carpeta reservada. El alcance por médico de CU11 protege el listado existente. Falta el flujo de agenda. |
| P6 | CU17 Consultar historial de citas del paciente | Carpeta reservada. Pendiente de implementación. |
| P6 | CU18 Consultar mis citas | Carpeta reservada. El alcance por paciente de CU11 protege el listado existente. Falta el flujo independiente de CU18. |
| P7 Reportes y estadísticas | CU19 Generar reportes administrativos | Carpeta reservada. Pendiente de implementación. |

## Sesión y autorización

- La portada y las pantallas de acceso y registro se sirven sin sesión. El POST de registro usa CSRF y límite de cinco solicitudes por minuto por IP para visitantes.
- `auth` y las capacidades definidas en `AppServiceProvider` protegen las rutas privadas. Las vistas usan las mismas capacidades para mostrar enlaces y formularios. El rol proviene de la relación Eloquent del usuario autenticado y se verifica el estado activo de la cuenta.
- Administración accede a CU03 y CU04. Administración y recepción gestionan CU05, CU06 y CU07, pueden reservar para pacientes y consultar citas. Un médico con ficha vinculada consulta su horario y sus citas. Un paciente con ficha vinculada reserva y consulta sus propias citas.
- El operador de auditoría se obtiene de la sesión. El servidor fija la ficha para las reservas del paciente y limita el acceso por UUID al ámbito del usuario. El UUID no se usa como credencial.
- CU02 utiliza el guard web, invalida la sesión y rota el token CSRF. Las rutas `/api/*` del cliente anterior fueron retiradas. Se conserva el código de autenticación legado de CU01 sin exponer sus acciones.
- La portada conserva las fotografías institucionales referenciadas por URL y sus cuatro capítulos. El carrusel, las pestañas, el filtro, la galería y el control de movimiento usan Blade y JavaScript convencional. Los recursos de plantilla del cliente React no se referenciaban desde las vistas nuevas.

## Cambios necesarios en la integración

- CU09 crea la ficha y la cuenta en la transacción existente. Exige el rol `Paciente` por nombre y devuelve un mensaje genérico ante conflictos de CI o correo. Se suspendió la asociación por CI a fichas existentes y se retiró el token sin respaldo que se devolvía después del alta. Laravel valida la confirmación de contraseña y el consentimiento que el formulario anterior pedía en el navegador.
- El formulario de cita toma los datos preseleccionados del enlace de CU10 y Laravel vuelve a validarlos al guardar. Se conservan los límites del backend. CU06 admite turnos de hasta 240 minutos por cupo y CU11 reserva hasta 180. CU10 indica esta diferencia en los cupos que exceden el límite de reserva.
- Los formularios usan `x-form` para CSRF y verbos PUT, PATCH y DELETE. El servidor valida las horas como `HH:mm` y las ediciones envían formularios completos. Los servicios conservan las reglas de unicidad, turnos, reservas y estados. Las excepciones de negocio regresan al formulario con datos y mensajes. Las contraseñas se excluyen de los valores anteriores.

## Antes de implementar CU01

1. Conectar la verificación del hash y de la cuenta activa con el guard `web`. Regenerar el ID de sesión al entrar y redirigir al panel o al destino solicitado. Reutilizar la bitácora con la identidad real.
2. Retirar del controlador y servicio legado la emisión de tokens sin almacenamiento ni verificación, el cierre que aceptaba un ID del cliente y las respuestas que concatenaban mensajes de excepciones.
3. Aplicar límite de intentos y una respuesta uniforme para credenciales inválidas o cuentas inactivas. Definir cómo resolver colisiones entre nombres de usuario y correos. Verificar la configuración de cookies de sesión en el despliegue.
4. Acordar cómo se verifica la identidad y el contacto antes de activar accesos o vincular una cuenta a una ficha anterior. Revisar la asignación de roles y vínculos de paciente/médico para las cuentas existentes.
5. Probar inicio y cierre de sesión, roles, propiedad de citas, invalidación de sesión y cuentas desactivadas con datos de prueba de PostgreSQL. Si se despliega fuera del entorno local, confirmar que el modo de depuración esté desactivado.

## Archivos de esta integración

- Las vistas nuevas están en CU01, CU02, CU04, CU05, CU06, CU07, CU09 y CU10. CU11 añade la vista de detalle. Se integraron las vistas existentes de CU03, CU11 y la portada.
- Los componentes nuevos son `clinica-heading`, `clinica-input`, `clinica-select`, `clinica-row-actions` y `portal-nav`. Se actualizaron `layouts/app.blade.php`, `editorial-slider`, `gallery-modal`, `public/css/app.css` y `public/js/app.js`. Los recursos `portada.css` y `portada.js` contienen los estilos y controles de la portada.
- Se actualizaron los controladores de CU02, CU03, CU04, CU05, CU06, CU07, CU09, CU10 y CU11, además del controlador base y el servicio de CU09. El registro de rutas, las capacidades y la redirección de invitados autenticados están en `routes/`, `app/Providers/AppServiceProvider.php` y `bootstrap/app.php`.
- Se retiraron `frontend/`, los manifiestos y configuraciones de Vite y Tailwind del backend y sus entradas en `resources/css/` y `resources/js/`. `composer.json` usa comandos de PHP para ejecutar el servidor.
- `tests/Feature/PortalClinicoTest.php` comprueba los accesos, formularios, alcance de citas y salida. `tests/TestCase.php` genera una clave exclusiva del proceso de prueba.
