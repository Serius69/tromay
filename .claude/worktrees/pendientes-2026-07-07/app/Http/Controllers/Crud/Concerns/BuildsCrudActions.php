<?php

namespace App\Http\Controllers\Crud\Concerns;

/**
 * Columna "action" estándar de los DataTables del admin (editar + eliminar).
 * Los botones extra (p.ej. convertir cotización) se pasan como HTML ya escapado.
 */
trait BuildsCrudActions
{
    protected function crudActions(
        int|string $id,
        string $extraItems = '',
        bool $editable = true,
        bool $deletable = true,
    ): string {
        $edit = $editable
            ? '<li class="list-inline-item edit">
                    <a href="#showModal" data-bs-toggle="modal" data-id="' . e($id) . '"
                       class="text-primary d-inline-block edit-item-btn">
                        <i class="ri-pencil-fill fs-16"></i>
                    </a>
                </li>'
            : '';

        $delete = $deletable
            ? '<li class="list-inline-item">
                    <a class="text-danger d-inline-block remove-item-btn" data-id="' . e($id) . '"
                       data-bs-toggle="modal" href="#deleteRecordModal">
                        <i class="ri-delete-bin-5-fill fs-16"></i>
                    </a>
                </li>'
            : '';

        return '
            <ul class="list-inline hstack gap-2 mb-0">
                ' . $extraItems . $edit . $delete . '
            </ul>';
    }
}
