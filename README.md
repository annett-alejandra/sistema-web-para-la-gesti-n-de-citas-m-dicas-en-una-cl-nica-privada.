# Clínica Vida Salud

Sistema de gestión de citas médicas en PHP 8.3, Laravel 13, Blade, HTML, CSS, JavaScript convencional y PostgreSQL 16. Laravel genera el HTML y sirve los recursos estáticos desde `backend/public/`.

## Información académica

| Campo | Información |
| --- | --- |
| Universidad | Universidad Autónoma Gabriel René Moreno (UAGRM) |
| Facultad | Ingeniería en Ciencias de la Computación y Telecomunicaciones |
| Materia | Ingeniería de Requisitos y Modelado |
| Docente | MSc. Ing. Angélica Garzón Cuéllar |
| Grupo | 22 - SC |
| Gestión | 2/2026 |

## Ejecutar el proyecto

Requiere PHP 8.3 con `pdo_pgsql`, Composer y PostgreSQL 16. En una instalación nueva, configure un `.env` a partir de `backend/.env.example` y genere una clave de aplicación propia. En una instalación existente, use su configuración y esquema actuales.

```sh
cd backend
composer install
php artisan serve
```

La portada, el acceso preparado y el registro de cuentas nuevas se sirven desde Laravel. El panel y los módulos privados requieren una sesión autenticada. El inicio de sesión web está pendiente de CU01. El registro requiere que exista el rol `Paciente` en la instalación.

## Estructura

| Capa | Ubicación |
| --- | --- |
| Controladores | `backend/app/Http/Controllers/Paquetes/` |
| Modelos | `backend/app/Models/` |
| Servicios | `backend/app/Services/Paquetes/` |
| Vistas Blade | `backend/resources/views/Paquetes/` |
| Rutas web | `backend/routes/web.php` |
| CSS y JavaScript | `backend/public/css/` y `backend/public/js/` |

La aplicación conserva siete paquetes funcionales y 19 casos de uso. La [arquitectura MVC](backend/docs/arquitectura-mvc-blade.md) registra las interfaces migradas, los flujos pendientes y los permisos. Consulte también el [esquema PostgreSQL](backend/docs/esquema-postgresql.md) y la [guía de comprobaciones](backend/README.md).
