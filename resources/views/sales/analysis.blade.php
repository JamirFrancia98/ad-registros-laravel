@extends('layouts.app')

@section('title','Análisis de Ventas')
@section('header','Análisis de Ventas')

@section('content')

{{-- Wrapper similar al template --}}
<div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

  {{-- SUMMARY (Line chart) --}}
  <div class="xl:col-span-8">
    <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h3 class="text-2xl font-bold text-bgray-900 dark:text-white">Summary</h3>
          <p class="text-sm text-bgray-500">Ventas y unidades (últimos {{ $days }} días)</p>
        </div>

        {{-- Selector rango (7/30/90) estilo template --}}
        <div class="flex gap-2">
          <a href="{{ route('sales.analysis', ['days'=>7]) }}"
             class="rounded-lg border border-bgray-300 px-4 py-2 text-sm font-bold text-bgray-700 dark:border-darkblack-400 dark:text-white">
            7 días
          </a>
          <a href="{{ route('sales.analysis', ['days'=>30]) }}"
             class="rounded-lg border border-bgray-300 px-4 py-2 text-sm font-bold text-bgray-700 dark:border-darkblack-400 dark:text-white">
            30 días
          </a>
          <a href="{{ route('sales.analysis', ['days'=>90]) }}"
             class="rounded-lg border border-bgray-300 px-4 py-2 text-sm font-bold text-bgray-700 dark:border-darkblack-400 dark:text-white">
            90 días
          </a>
        </div>
      </div>

      <div class="mt-6 h-[320px] w-full">
        <canvas id="salesSummaryChart" class="h-full w-full"></canvas>
      </div>
    </div>
  </div>

  {{-- EFFICIENCY (Donut) --}}
  <div class="xl:col-span-4">
    <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <div class="flex items-center justify-between">
        <h3 class="text-2xl font-bold text-bgray-900 dark:text-white">Efficiency</h3>
        <span class="text-sm text-bgray-500">Monthly</span>
      </div>

      {{-- Donut + resumen --}}
      <div class="mt-6 flex flex-col items-center">
        <div class="h-[220px] w-[220px]">
          <canvas id="efficiencyDonut" class="h-full w-full"></canvas>
        </div>

        <div class="mt-6 w-full space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-bgray-600 dark:text-bgray-50">Ganancia</span>
            <span class="text-sm font-bold {{ $profit >= 0 ? 'text-success-300' : 'text-red-500' }}">
              S/ {{ number_format($profit, 0) }}
            </span>
          </div>

          <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-bgray-600 dark:text-bgray-50">Ventas</span>
            <span class="text-sm font-bold text-bgray-900 dark:text-white">
              S/ {{ number_format($totalSales, 0) }}
            </span>
          </div>

          <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-bgray-600 dark:text-bgray-50">Costo</span>
            <span class="text-sm font-bold text-bgray-900 dark:text-white">
              S/ {{ number_format($totalCost, 0) }}
            </span>
          </div>
        </div>

        <div class="mt-6 w-full border-t border-bgray-200 pt-4 dark:border-darkblack-400">
          <div class="flex items-center justify-between">
            <span class="text-sm text-bgray-500">Margen</span>
            @php
              $margin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;
            @endphp
            <span class="text-sm font-bold text-bgray-900 dark:text-white">
              {{ number_format($margin, 1) }}%
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MOST LOCATIONS (lo convertimos en Top Modelos) --}}
  <div class="xl:col-span-8">
    <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-2xl font-bold text-bgray-900 dark:text-white">Top Modelos</h3>
          <p class="text-sm text-bgray-500">Los más vendidos en el rango</p>
        </div>
        <span class="text-sm text-bgray-500">This Year</span>
      </div>

      <div class="mt-6 overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-bgray-300 dark:border-darkblack-400">
              <th class="py-3 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Modelo</th>
              <th class="py-3 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Cantidad</th>
              <th class="py-3 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Total vendido</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topModels as $t)
              <tr class="border-b border-bgray-200 dark:border-darkblack-400">
                <td class="py-3 text-bgray-900 dark:text-white font-bold">{{ $t->model_name }}</td>
                <td class="py-3 text-bgray-900 dark:text-white">{{ (int)$t->qty }}</td>
                <td class="py-3 text-bgray-900 dark:text-white">S/ {{ number_format((float)$t->total, 0) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="py-6 text-center text-bgray-500">Aún no hay ventas en este rango.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- TASK SUMMARY (KPIs tipo cards) --}}
  <div class="xl:col-span-4">
    <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <div class="flex items-center justify-between">
        <h3 class="text-2xl font-bold text-bgray-900 dark:text-white">Task Summary</h3>
        <span class="text-bgray-500">•••</span>
      </div>

      {{-- KPIs como cards --}}
      <div class="mt-6 grid grid-cols-2 gap-4">
        <div class="rounded-lg bg-success-300/20 p-4">
          <p class="text-sm text-bgray-600 dark:text-bgray-50">Equipos</p>
          <p class="mt-2 text-2xl font-bold text-bgray-900 dark:text-white">{{ $unitsSold }}</p>
        </div>

        <div class="rounded-lg bg-success-300/20 p-4">
          <p class="text-sm text-bgray-600 dark:text-bgray-50">Ticket Prom.</p>
          <p class="mt-2 text-2xl font-bold text-bgray-900 dark:text-white">
            S/ {{ number_format($avgTicket, 0) }}
          </p>
        </div>

        <div class="col-span-2 rounded-lg bg-darkblack-500 p-5">
          <p class="text-sm text-bgray-200">Eficiencia de margen</p>
          <p class="mt-2 text-4xl font-bold text-white">{{ number_format($margin, 1) }}%</p>
          <p class="mt-1 text-sm text-bgray-200">
            (Ganancia / Ventas)
          </p>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  // Datos vienen del controlador
  const labels = @json($labels);
  const totals = @json($dailyTotals);
  const qtys   = @json($dailyQty);

  // 1) Summary (line)
  const ctx1 = document.getElementById('salesSummaryChart');
  if (ctx1) {
    if (window.__salesSummaryChart) window.__salesSummaryChart.destroy();

    window.__salesSummaryChart = new Chart(ctx1, {
      type: 'line',
      data: {
        labels,
        datasets: [
          { label: 'Total S/', data: totals, tension: 0.35 },
          { label: 'Unidades', data: qtys, tension: 0.35 }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
      }
    });
  }

  // 2) Efficiency (donut): usamos 3 partes (Ventas, Costo, Ganancia)
  const donut = document.getElementById('efficiencyDonut');
  if (donut) {
    if (window.__effDonut) window.__effDonut.destroy();

    const totalSales = {{ (float)$totalSales }};
    const totalCost  = {{ (float)$totalCost }};
    const profit     = {{ (float)$profit }};

    // Si profit es negativo, lo mostramos como 0 en el donut para evitar valores raros
    const profitSafe = profit > 0 ? profit : 0;

    window.__effDonut = new Chart(donut, {
      type: 'doughnut',
      data: {
        labels: ['Ventas', 'Costo', 'Ganancia'],
        datasets: [{
          data: [totalSales, totalCost, profitSafe],
          borderWidth: 0,
          cutout: '68%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
      }
    });
  }
</script>
@endsection