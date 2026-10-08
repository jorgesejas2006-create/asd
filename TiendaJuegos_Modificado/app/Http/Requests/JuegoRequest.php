<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JuegoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->rol === 'admin';
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => is_string($this->nombre) ? trim($this->nombre) : $this->nombre,
            'descripcion' => is_string($this->descripcion) ? trim($this->descripcion) : $this->descripcion,
            'imagen' => is_string($this->imagen) ? trim($this->imagen) : $this->imagen,
            'codigo_acceso' => is_string($this->codigo_acceso)
                ? strtoupper(trim($this->codigo_acceso))
                : $this->codigo_acceso,
        ]);
    }

    public function rules(): array
    {
        $juego = $this->route('juego');

        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'not_regex:/[<>]/',
            ],
            'descripcion' => [
                'required',
                'string',
                'min:10',
                'max:1500',
                'not_regex:/<\s*script\b/i',
            ],
            'anio_lanzamiento' => [
                'required',
                'integer',
                'min:1980',
                'max:' . (date('Y') + 1),
            ],
            'categoria' => ['required', Rule::in(['Acción', 'Fantasía', 'FPS', 'Survival'])],
            'consola' => ['required', Rule::in(['PC', 'Play', 'XBOX'])],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'precio' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'imagen' => ['nullable', 'url', 'max:2048'],
            'codigo_acceso' => [
                'required',
                'string',
                'min:4',
                'max:80',
                'regex:/^[A-Z0-9][A-Z0-9_-]*$/',
                Rule::unique('juegos', 'codigo_acceso')->ignore($juego?->id),
            ],
            'en_venta' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.not_regex' => 'El nombre del juego no puede contener los caracteres < o >.',
            'descripcion.not_regex' => 'La descripción contiene contenido no permitido.',
            'categoria.in' => 'Selecciona una categoría válida.',
            'consola.in' => 'Selecciona una consola válida.',
            'codigo_acceso.regex' => 'El código de acceso solo puede contener letras mayúsculas, números, guiones y guiones bajos.',
            'codigo_acceso.unique' => 'Ese código de acceso ya está registrado en otro juego.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del juego',
            'descripcion' => 'descripción',
            'anio_lanzamiento' => 'año de lanzamiento',
            'categoria' => 'categoría',
            'consola' => 'consola',
            'stock' => 'stock',
            'precio' => 'precio',
            'imagen' => 'URL de la imagen',
            'codigo_acceso' => 'código de acceso',
            'en_venta' => 'estado de venta',
        ];
    }
}
