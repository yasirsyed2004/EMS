<?php

use Modules\Employee\Entities\Employee;

function getModelUserId($Model, $id)
{
    switch ($Model) {
        case 'employee':
            $modelClass = Employee::class;
            break;
        default:
            return null;
    }
    $record = $modelClass::find($id);
    if ($record) {
        return $record->user_id;
    }
}

