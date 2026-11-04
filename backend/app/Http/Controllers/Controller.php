<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Throwable;

abstract class Controller
{
    protected function procesarFormulario(Closure $operacion): RedirectResponse
    {
        try {
            return $operacion();
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages(['operacion' => $exception->getMessage()]);
        } catch (ModelNotFoundException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'operacion' => 'La operación no pudo completarse. Revise el estado antes de volver a intentarlo.',
            ]);
        }
    }
}
