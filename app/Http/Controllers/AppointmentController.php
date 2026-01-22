<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentDayLimit;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('appointments.index');
    }

    /**
     * FullCalendar consume esto
     * title solo nombre
     * envía attended en extendedProps
     */
    public function events(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

        $appointments = Appointment::query()
            ->when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end,   fn ($q) => $q->whereDate('date', '<=', $end))
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $events = $appointments->map(function ($a) {
            return [
                'id' => $a->id,
                'title' => $a->name, // solo nombre
                'start' => $a->date->format('Y-m-d') . 'T' . $a->time,
                'allDay' => false,
               'extendedProps' => [
  'phone' => $a->phone,
  'observations' => $a->observations,
  'date_iso' => $a->date->format('Y-m-d'),
  'date_display' => $a->date->format('d/m/Y'),
  'time' => $a->time,
  'attended' => (bool) $a->attended,
],

            ];
        });

        return response()->json($events);
    }

    /**
     * Guardar cita
     * Permite duplicar hora y día
     * Respeta límite por día SOLO si existe en appointment_day_limits
     * max_per_day = 0 bloquea el día
     */
    public function store(Request $request)
{
    $request->validate([
        'date' => ['required', 'date'],
        'time' => ['required', 'date_format:H:i'],
        'name' => ['required', 'string', 'max:150'],
        'phone' => ['required', 'string', 'max:30'],
        'observations' => ['nullable', 'string'],
    ]);

    $fechaBonita = Carbon::parse($request->date)->format('d/m/Y');

    // Límite específico del día (si existe). Si no existe => sin límite.
    $dayLimit = AppointmentDayLimit::whereDate('date', $request->date)->value('max_per_day');

    // Si existe override y es 0 => día bloqueado
    if ($dayLimit !== null && (int)$dayLimit === 0) {
        return response()->json([
            'message' => "Día bloqueado {$fechaBonita}. No se pueden agendar citas."
        ], 422);
    }

    // Si existe override > 0 => validar cupo
    if ($dayLimit !== null) {
        $count = Appointment::whereDate('date', $request->date)->count();

        if ($count >= (int)$dayLimit) {
            return response()->json([
                'message' => "Cupo lleno para {$fechaBonita}: máximo {$dayLimit} citas."
            ], 422);
        }
    }

    $appointment = Appointment::create([
        'date' => $request->date,
        'time' => $request->time, // H:i
        'name' => $request->name,
        'phone' => $request->phone,
        'observations' => $request->observations,
        // attended queda por default false en BD
    ]);

    return response()->json([
        'message' => 'Cita agendada correctamente.',
        'appointment' => $appointment,
    ]);
}

    /**
     * Update (editar cita)
     * Respeta límite por día SOLO si existe
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'observations' => ['nullable', 'string'],
        ]);

        // respeta límite por día SOLO si existe
        $dayLimit = AppointmentDayLimit::whereDate('date', $request->date)->value('max_per_day');

        if ($dayLimit !== null) {
            $count = Appointment::whereDate('date', $request->date)
                ->where('id', '!=', $appointment->id)
                ->count();

            if ($count >= (int) $dayLimit) {
                $fecha = Carbon::parse($request->date)->format('d/m/Y');

                return response()->json([
                    'message' => "Límite alcanzado para {$fecha}: máximo {$dayLimit} citas."
                ], 422);
            }
        }

        $appointment->update([
            'date' => $request->date,
            'time' => $request->time,
            'name' => $request->name,
            'phone' => $request->phone,
            'observations' => $request->observations,
        ]);

        return response()->json([
            'message' => 'Cita actualizada correctamente.',
            'appointment' => $appointment,
        ]);
    }

    /**
     * Toggle attended
     */
    public function toggleAttended(Appointment $appointment)
    {
        $appointment->attended = !$appointment->attended;
        $appointment->save();

        return response()->json([
            'message' => $appointment->attended ? 'Marcado como: Ya vino.' : 'Marcado como: Pendiente.',
            'attended' => (bool) $appointment->attended
        ]);
    }

    /**
     * Estado de días (solo días con límite configurado)
     */
    public function daysStatus(Request $request)
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end'   => ['required', 'date'],
        ]);

        $start = $request->query('start');
        $end   = $request->query('end');

        // límites configurados en el rango
        $limits = AppointmentDayLimit::whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->get()
            ->keyBy(fn ($x) => $x->date->format('Y-m-d'));

        // conteo de citas por día en el rango
        $counts = Appointment::selectRaw('date, COUNT(*) as total')
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->groupBy('date')
            ->get()
            ->keyBy(fn ($x) => Carbon::parse($x->date)->format('Y-m-d'));

        $result = [];

        foreach ($limits as $date => $limitRow) {
            $count = (int) ($counts[$date]->total ?? 0);
            $limit = (int) $limitRow->max_per_day;

            $result[$date] = [
                'limit' => $limit,
                'count' => $count,
                'is_blocked' => ($limit === 0),
                'is_full' => ($limit > 0 && $count >= $limit),
            ];
        }

        return response()->json($result);
    }

    /**
     * Eliminar cita
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Cita eliminada.',
        ]);
    }
}
