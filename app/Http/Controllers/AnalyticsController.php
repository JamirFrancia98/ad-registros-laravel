<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Solo muestra la vista. Los datos se cargan por AJAX (fetch) desde el JS.
        return view('analytics.index');
    }

    public function summary(Request $request)
    {
        // Filtro permitido: 7 / 30 / 90 / 180 (6 meses)
        $days = (int) $request->query('days', 30);
        $days = in_array($days, [7, 30, 90, 180], true) ? $days : 30;

        $from = now()->subDays($days)->toDateString();

        // SUMMARY = ventas por día:
        // - total: suma de grand_total (venta + accesorios)
        // - units: cantidad de ventas (unidades vendidas)
        $rows = DB::table('sales')
            ->selectRaw('sold_at as d, COUNT(*) as units, SUM(grand_total) as total')
            ->where('sold_at', '>=', $from)
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        return response()->json([
            'days' => $days,
            'labels' => $rows->pluck('d'),
            'total' => $rows->pluck('total')->map(fn($v) => (float) $v),
            'units' => $rows->pluck('units')->map(fn($v) => (int) $v),
        ]);
    }

    public function efficiency(Request $request)
    {
        // Mismo filtro para que Summary y Efficiency siempre estén alineados
        $days = (int) $request->query('days', 30);
        $days = in_array($days, [7, 30, 90, 180], true) ? $days : 30;

        $from = now()->subDays($days)->toDateString();

        // Ventas totales (grand_total incluye accesorios)
        $ventas = (float) DB::table('sales')
            ->where('sold_at', '>=', $from)
            ->sum('grand_total');

        // Costo total: suma purchase_price de los iPhones vendidos en ese rango
        // (Join sales -> purchases para obtener el costo)
        $costo = (float) DB::table('sales as s')
            ->join('purchases as p', 'p.id', '=', 's.purchase_id')
            ->where('s.sold_at', '>=', $from)
            ->sum('p.purchase_price');

        // Ganancia = ventas - costo
        $ganancia = $ventas - $costo;

        // Margen % = ganancia / ventas
        $margen = ($ventas > 0) ? round(($ganancia / $ventas) * 100, 1) : 0;

        return response()->json([
            'days' => $days,
            'ventas' => $ventas,
            'costo' => $costo,
            'ganancia' => $ganancia,
            'margen' => $margen,
        ]);
    }

    public function topModels(Request $request)
    {
        $days = (int) $request->query('days', 30);
        $days = in_array($days, [7, 30, 90, 180], true) ? $days : 30;

        $from = now()->subDays($days)->toDateString();

        // Top modelos vendidos en el rango
        $rows = DB::table('sales as s')
            ->join('purchases as p', 'p.id', '=', 's.purchase_id')
            ->join('iphone_models as m', 'm.id', '=', 'p.iphone_model_id')
            ->selectRaw('m.name as model, COUNT(*) as qty, SUM(s.grand_total) as total')
            ->where('s.sold_at', '>=', $from)
            ->groupBy('m.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        return response()->json($rows);
    }

    public function taskSummary(Request $request)
    {
        $days = (int) $request->query('days', 30);
        $days = in_array($days, [7, 30, 90, 180], true) ? $days : 30;

        $from = now()->subDays($days)->toDateString();

        $equipos = DB::table('sales')
            ->where('sold_at', '>=', $from)
            ->count();

        $ventas = (float) DB::table('sales')
            ->where('sold_at', '>=', $from)
            ->sum('grand_total');

        $costo = (float) DB::table('sales as s')
            ->join('purchases as p', 'p.id', '=', 's.purchase_id')
            ->where('s.sold_at', '>=', $from)
            ->sum('p.purchase_price');

        $ganancia = $ventas - $costo;

        $ticketProm = $equipos > 0 ? $ventas / $equipos : 0;
        $margen = $ventas > 0 ? ($ganancia / $ventas) * 100 : 0;

        return response()->json([
            'equipos' => $equipos,
            'ticket' => $ticketProm,
            'margen' => round($margen, 1)
        ]);
    }
}