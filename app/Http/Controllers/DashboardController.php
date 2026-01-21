<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $kpis = [
            'usuarios'   => 0,
            'productos'  => 0,
            'ventas_mes' => 0,
            'ordenes'    => 0,
        ];

        $ventasLabels = ['Semana 1','Semana 2','Semana 3','Semana 4'];
        $ventasData   = [5200, 6100, 4300, 9250];

        $topProductosLabels = ['Aceite 4T', 'Bujías', 'Cadena', 'Pastillas', 'Filtro'];
        $topProductosData   = [120, 95, 70, 65, 50];

        return view('dashboard', compact(
            'kpis',
            'ventasLabels','ventasData',
            'topProductosLabels','topProductosData'
        ));
    }
}
