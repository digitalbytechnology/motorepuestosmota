<?php

namespace App\Http\Controllers;

use App\Models\AppointmentDayLimit;
use Illuminate\Http\Request;

class AppointmentDayLimitController extends Controller
{
  
    public function show(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $specific = AppointmentDayLimit::whereDate('date', $request->date)->first();

        return response()->json([
            'date'        => $request->date,
            'specific'    => $specific?->max_per_day,
            'current'     => $specific?->max_per_day, // null si no existe
            'is_override' => (bool) $specific,
        ]);
    }

    /**
     * Crear/actualizar límite para un día.
     * max_per_day = 0 => bloquea el día
     */
    public function upsert(Request $request)
    {
        $request->validate([
            'date'        => ['required', 'date'],
            'max_per_day' => ['required', 'integer', 'min:0', 'max:500'],
        ]);

        $limit = AppointmentDayLimit::updateOrCreate(
            ['date' => $request->date],
            ['max_per_day' => (int) $request->max_per_day]
        );

        return response()->json([
            'message' => 'Límite del día actualizado correctamente.',
            'limit'   => $limit,
        ]);
    }

    /**
     * Elimina el límite específico del día.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        AppointmentDayLimit::whereDate('date', $request->date)->delete();

        return response()->json([
            'message' => 'Límite del día eliminado.',
        ]);
    }
}
