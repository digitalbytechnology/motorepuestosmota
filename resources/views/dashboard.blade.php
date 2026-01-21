@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $kpis['usuarios'] }}</h3>
                    <p>Usuarios</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('usuarios.index') }}" class="small-box-footer">
                    Ver usuarios <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $kpis['productos'] }}</h3>
                    <p>Productos</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="#" class="small-box-footer">
                    Ver productos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Q {{ number_format($kpis['ventas_mes'], 2) }}</h3>
                    <p>Ventas del mes</p>
                </div>
                <div class="icon"><i class="fas fa-cash-register"></i></div>
                <a href="#" class="small-box-footer">
                    Ver ventas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $kpis['ordenes'] }}</h3>
                    <p>Órdenes pendientes</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <a href="#" class="small-box-footer">
                    Ver órdenes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ventas por semana</h3>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top productos</h3>
                </div>
                <div class="card-body">
                    <canvas id="topProductosChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Pasa datos del backend al JS (Vite ya tiene cargado dashboard.js)
    document.addEventListener('DOMContentLoaded', () => {
        if (window.renderDashboardCharts) {
            window.renderDashboardCharts({
                ventasLabels: @json($ventasLabels),
                ventasData: @json($ventasData),
                topLabels: @json($topProductosLabels),
                topData: @json($topProductosData),
            });
        }
    });
</script>
@endpush
