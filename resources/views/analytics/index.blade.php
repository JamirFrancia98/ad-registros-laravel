@extends('layouts.app')

@section('title', 'Análisis')
@section('header', 'Análisis')

@section('content')

<div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

  {{-- SUMMARY (línea verde) --}}
  <div class="xl:col-span-8 rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h3 class="text-2xl font-bold text-bgray-900 dark:text-white">Summary</h3>
        <p class="text-sm text-bgray-500">Ventas y unidades (según filtro)</p>
      </div>

      {{-- Filtros --}}
      <div class="flex flex-wrap gap-2">
        <button class="btn-range" data-days="7">7 días</button>
        <button class="btn-range" data-days="30">30 días</button>
        <button class="btn-range" data-days="90">90 días</button>
        <button class="btn-range" data-days="180">6 meses</button>
      </div>
    </div>

    <div class="mt-6 h-[320px] w-full">
      <canvas id="summaryChart" class="h-full w-full"></canvas>
    </div>
  </div>

  {{-- EFFICIENCY (doughnut con colores + números) --}}
  <div class="xl:col-span-4 rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-bgray-900 dark:text-white">Efficiency</h3>
      <span id="effLabel" class="text-sm text-bgray-500">—</span>
    </div>

    <div class="mt-6 h-[220px] w-full">
      <canvas id="effChart" class="h-full w-full"></canvas>
    </div>

    <div class="mt-6 space-y-3">
      <div class="flex items-center justify-between">
        <span class="text-bgray-500">Ganancia</span>
        <span id="gananciaTxt" class="font-bold text-success-300">S/ 0</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-bgray-500">Ventas</span>
        <span id="ventasTxt" class="font-bold text-white">S/ 0</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-bgray-500">Costo</span>
        <span id="costoTxt" class="font-bold text-white">S/ 0</span>
      </div>

      <hr class="border-darkblack-400 opacity-60">

      <div class="flex items-center justify-between">
        <span class="text-bgray-500">Margen</span>
        <span id="margenTxt" class="font-bold text-white">0%</span>
      </div>
    </div>
  </div>


  

</div>
<div class="grid grid-cols-1 gap-6 xl:grid-cols-12 mt-6">

  <!-- TOP MODELOS -->
  <div class="xl:col-span-8 rounded-lg border bg-white p-6 dark:bg-darkblack-600">
    <h3 class="text-2xl font-bold text-white mb-4">Top Modelos</h3>

    <table class="w-full text-left">
      <thead>
        <tr class="text-bgray-400">
          <th>Modelo</th>
          <th>Cantidad</th>
          <th>Total vendido</th>
        </tr>
      </thead>
      <tbody id="topModelsBody"></tbody>
    </table>
  </div>

  <!-- TASK SUMMARY -->
  <div class="xl:col-span-4 rounded-lg border bg-white p-6 dark:bg-darkblack-600">
    <h3 class="text-2xl font-bold text-white mb-6">Resumen</h3>

    <div class="space-y-4">
      <div class="flex justify-between">
        <span>Equipos</span>
        <span id="equiposTxt" class="font-bold"></span>
      </div>

      <div class="flex justify-between">
        <span>Ticket Prom.</span>
        <span id="ticketTxt" class="font-bold"></span>
      </div>

      <div class="flex justify-between">
        <span>Margen</span>
        <span id="margenTaskTxt" class="font-bold text-success-300"></span>
      </div>
    </div>
  </div>

   </div>
@endsection

@section('scripts')
<script>
  // --------- Helpers ---------
  function money(n){
    // Sin decimales (como tu UI): S/ 4,787
    const v = Number(n || 0);
    return 'S/ ' + v.toLocaleString('es-PE', { maximumFractionDigits: 0 });
  }

  function setActiveButton(days){
    document.querySelectorAll('.btn-range').forEach(btn => {
      const isActive = Number(btn.dataset.days) === Number(days);
      // Estilo simple: si quieres igualarlo al template, lo afinamos luego
      btn.className =
        'btn-range rounded-lg border px-4 py-2 text-sm font-bold ' +
        (isActive ? 'bg-success-300 text-white border-success-300' : 'bg-transparent text-bgray-200 border-bgray-400');
    });
  }

  // --------- Charts instances ---------
  let summaryChart = null;
  let effChart = null;

  async function loadSummary(days){
    const res = await fetch(`{{ route('api.analytics.summary') }}?days=${days}`);
    return await res.json();
  }

  async function loadEfficiency(days){
    const res = await fetch(`{{ route('api.analytics.efficiency') }}?days=${days}`);
    return await res.json();
  }

  function renderSummary(data){
    const ctx = document.getElementById('summaryChart');
    if (!ctx) return;

    if (summaryChart) summaryChart.destroy();

    // ✅ Verde en línea y puntos (marcadores)
    summaryChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [
          {
            label: 'Total S/',
            data: data.total,
            borderColor: '#22C55E',        // verde
            backgroundColor: 'rgba(34, 197, 94, 0.12)',
            pointBackgroundColor: '#22C55E',
            pointBorderColor: '#22C55E',
            pointRadius: 3,
            pointHoverRadius: 4,
            tension: 0.35,
            fill: true
          },
          {
            label: 'Unidades',
            data: data.units,
            borderColor: '#34D399',        // verde claro
            backgroundColor: 'rgba(52, 211, 153, 0.10)',
            pointBackgroundColor: '#34D399',
            pointBorderColor: '#34D399',
            pointRadius: 2,
            tension: 0.35,
            fill: false,
            yAxisID: 'y2'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { labels: { color: '#D1D5DB' } }
        },
        scales: {
          x: { ticks: { color: '#9CA3AF' } },
          y: {
            beginAtZero: true,
            ticks: { color: '#9CA3AF' }
          },
          y2: {
            beginAtZero: true,
            position: 'right',
            grid: { drawOnChartArea: false },
            ticks: { color: '#9CA3AF' }
          }
        }
      }
    });
  }

  function renderEfficiency(data){
    const ctx = document.getElementById('effChart');
    if (!ctx) return;

    if (effChart) effChart.destroy();

    // ✅ Doughnut con 3 colores: Ventas / Costo / Ganancia
    // Nota: Ganancia puede ser negativa; para el gráfico, usamos max(ganancia,0) para que no rompa.
    const ventas = Number(data.ventas || 0);
    const costo = Number(data.costo || 0);
    const ganancia = Number(data.ganancia || 0);
    const gananciaForChart = Math.max(ganancia, 0);

    effChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Ventas', 'Costo', 'Ganancia'],
        datasets: [{
          data: [ventas, costo, gananciaForChart],
          backgroundColor: [
            '#22C55E', // Ventas (verde)
            '#F59E0B', // Costo (naranja)
            '#60A5FA'  // Ganancia (azul)
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          legend: { labels: { color: '#D1D5DB' } }
        }
      }
    });

    // ✅ Textos
    document.getElementById('effLabel').innerText = `${data.days} días`;
    document.getElementById('gananciaTxt').innerText = money(ganancia);
    document.getElementById('ventasTxt').innerText = money(ventas);
    document.getElementById('costoTxt').innerText = money(costo);
    document.getElementById('margenTxt').innerText = `${Number(data.margen || 0)}%`;
  }

  async function refresh(days){

  setActiveButton(days);

  const [
    sum,
    eff,
    top,
    task
  ] = await Promise.all([
    loadSummary(days),
    loadEfficiency(days),
    loadTopModels(days),
    loadTaskSummary(days)
  ]);

  renderSummary(sum);
  renderEfficiency(eff);
  renderTopModels(top);
  renderTaskSummary(task);
}

  async function loadTopModels(days){
  const res = await fetch(`/api/analisis/top-modelos?days=${days}`);
  return await res.json();
  }

    async function loadTaskSummary(days){
    const res = await fetch(`/api/analisis/task-summary?days=${days}`);
    return await res.json();
    }

    function renderTopModels(rows){
    const body = document.getElementById('topModelsBody');
    body.innerHTML = '';

    rows.forEach(r => {
        body.innerHTML += `
        <tr class="border-b border-darkblack-400">
            <td class="py-3">${r.model}</td>
            <td>${r.qty}</td>
            <td>S/ ${Number(r.total).toLocaleString('es-PE')}</td>
        </tr>
        `;
    });
    }

    function renderTaskSummary(data){
    document.getElementById('equiposTxt').innerText = data.equipos;
    document.getElementById('ticketTxt').innerText =
        'S/ ' + Number(data.ticket).toLocaleString('es-PE', {maximumFractionDigits:0});
    document.getElementById('margenTaskTxt').innerText = data.margen + '%';
    }

  // Eventos botones
  document.querySelectorAll('.btn-range').forEach(btn => {
    btn.addEventListener('click', () => refresh(btn.dataset.days));
  });

  // Inicial
  refresh(30);
</script>






@endsection