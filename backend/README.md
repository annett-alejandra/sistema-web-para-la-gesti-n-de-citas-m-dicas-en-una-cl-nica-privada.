# Backend de Clínica Vida Salud

Laravel 13 genera las vistas Blade con PHP 8.3. Las vistas cargan los archivos CSS y JavaScript convencionales con `asset()`. El servidor de Laravel es el único proceso de aplicación para desarrollo.

## Ejecución local

Desde esta carpeta, con PostgreSQL 16 y la configuración local preparada:

```sh
composer install
php artisan serve
```

Abra la dirección mostrada por Laravel. En Windows, si PHP informa problemas al escribir en `bootstrap/cache`, compruebe los permisos o utilice una ruta local corta compuesta por caracteres ASCII.

## Comprobaciones

```sh
php artisan route:list -vv --except-vendor
php artisan view:cache
php vendor/phpunit/phpunit/phpunit --do-not-cache-result
```

La suite utiliza un entorno de pruebas con sesiones en memoria. Las pruebas HTTP de Blade sustituyen servicios o verificaciones de existencia cuando es necesario. Para validar el comportamiento SQL, use una instancia PostgreSQL desechable con el esquema del proyecto. El registro y las citas necesitan los catálogos correspondientes en esa instancia.

Consulte el [inventario de casos de uso y límites de acceso](docs/arquitectura-mvc-blade.md) y el [esquema PostgreSQL](docs/esquema-postgresql.md).
