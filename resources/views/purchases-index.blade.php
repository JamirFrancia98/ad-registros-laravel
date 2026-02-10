@extends('layouts.app')

@section('title', 'Listado Compras')
@section('header', 'Listado Compras')

@section('content')

  {{-- LISTADO + FILTROS (Transaction style) --}}
  <div class="rounded-lg border border-bgray-300 bg-white dark:border-darkblack-400 dark:bg-darkblack-600">
    <div class="flex flex-col gap-4 px-6 py-5 xl:flex-row xl:items-center xl:justify-between">
      <div>
        <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Listado Compras</h3> 
      </div>

      <a href="{{ route('registro-producto.create') }}"
         class="rounded-lg bg-success-300 px-5 py-3 text-sm font-bold text-white hover:bg-success-400 transition-all">
        + Nuevo registro
      </a>
    </div>

    {{-- FILTROS --}}
    <div class="px-6 pb-5">
      <form method="GET" action="{{ route('purchases.index') }}" class="flex flex-col gap-4 xl:flex-row xl:items-end">


        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5 flex-1">
          {{-- Buscar --}}
          <div class="w-full">
            <p class="mb-2 text-base font-bold leading-[24px] text-bgray-900 dark:text-white">Buscar</p>
            <div class="relative h-[56px] w-full">
              <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="IMEI / Serie"
                class="h-full w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
              />
            </div>
          </div>

          {{-- Proveedor --}}
          <div class="w-full">
            <p class="mb-2 text-base font-bold leading-[24px] text-bgray-900 dark:text-white">Proveedor</p>
            <div class="relative h-[56px] w-full">
              <select
                name="supplier_id"
                class="h-full w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
              >
                <option value="">Todos</option>
                @foreach($suppliers as $s)
                  <option value="{{ $s->id }}" @selected(request('supplier_id') == $s->id)>{{ $s->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Modelo --}}
          <div class="w-full">
            <p class="mb-2 text-base font-bold leading-[24px] text-bgray-900 dark:text-white">Modelo</p>
            <div class="relative h-[56px] w-full">
              <select
                name="iphone_model_id"
                class="h-full w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
              >
                <option value="">Todos</option>
                @foreach($models as $m)
                  <option value="{{ $m->id }}" @selected(request('iphone_model_id') == $m->id)>{{ $m->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Desde --}}
          <div class="w-full">
            <p class="mb-2 text-base font-bold leading-[24px] text-bgray-900 dark:text-white">Desde</p>
            <div class="relative h-[56px] w-full">
              <input
                type="date"
                name="date_from"
                value="{{ request('date_from') }}"
                class="h-full w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
              />
            </div>
          </div>

          {{-- Hasta   --}}
          <div class="w-full">
            <p class="mb-2 text-base font-bold leading-[24px] text-bgray-900 dark:text-white">Hasta</p>

            <div class="flex gap-2">
              <input
                type="date"
                name="date_to"
                value="{{ request('date_to') }}"
                class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
              />

            
            </div>

           
          </div>
            <div class="mt-3 flex gap-2">
              <button
                class="rounded-lg bg-success-300 px-4 py-2 text-sm font-bold text-white hover:bg-success-400"
              >
                Filtrar
              </button>

              <a href="{{ route('purchases.index') }}"
                class="rounded-lg border px-4 py-2 text-sm font-bold">
                Limpiar
              </a>
            </div>
        </div>
      </form>
    </div>

    {{-- TABLA --}}
    <div class="table-content w-full overflow-x-auto px-6 pb-6">
      <table class="w-full">
       <thead>
  <tr class="border-b border-bgray-300 dark:border-darkblack-400">

    <!-- Fecha (solo desktop) -->
    <th class="hidden md:table-cell py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">
      Fecha Compra
    </th>

    <!-- Proveedor (solo desktop) -->
    <th class="hidden md:table-cell py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">
      Proveedor
    </th>

    <!-- iPhone (siempre) -->
    <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">
      iPhone
    </th>

    <!-- IMEI (siempre) -->
    <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">
      IMEI (últ. 5)
    </th>

    <!-- Foto IMEI (solo desktop) -->
    <th class="hidden md:table-cell py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">
      Foto IMEI
    </th>

    <!-- Precio costo (siempre) -->
    <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">
      Precio Costo
    </th>

    <!-- Acciones (siempre) -->
    <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">
      Acciones
    </th>
  </tr>
</thead>

        <tbody>
          @forelse($rows as $r)
            <tr class="border-b border-bgray-200 dark:border-darkblack-400">

  <!-- Fecha (solo desktop) -->
  <td class="hidden md:table-cell py-4 text-bgray-900 dark:text-white">
    {{ $r->purchase_date }}
  </td>

  <!-- Proveedor (solo desktop) -->
  <td class="hidden md:table-cell py-4 text-bgray-900 dark:text-white">
    {{ $r->supplier_name }}
  </td>

  <!-- iPhone (siempre) -->
<td class="py-4 text-bgray-900 dark:text-white min-w-[160px]">
  <div class="font-medium">{{ $r->model_name }}</div>
  @if(!empty($r->storage_label))
    <div class="text-xs text-bgray-500">{{ $r->storage_label }}</div>
  @endif
</td>

  <!-- IMEI (siempre) -->
<td class="py-4 text-bgray-900 dark:text-white min-w-[90px] whitespace-nowrap">
      {{ substr($r->imei1, -5) }}
  </td>

  <!-- Foto IMEI (solo desktop) -->
  <td class="hidden md:table-cell py-4">
    @if($r->imei_photo_path)
      <img
        src="{{ asset('storage/' . $r->imei_photo_path) }}"
        class="h-12 w-12 rounded-lg object-cover cursor-pointer border"
        alt="Foto IMEI"
        onclick="openImg(this.src)"
      />
    @else
      <span class="text-bgray-500 text-sm">—</span>
    @endif
  </td>

  <!-- Precio costo (siempre) -->
<td class="py-4 text-bgray-900 dark:text-white min-w-[90px] whitespace-nowrap">
    S/ {{ number_format((float)$r->purchase_price, 0) }}
  </td>

  <!-- Acciones (siempre) -->
  <td class="py-4">
<div class="flex flex-col gap-2 md:flex-row md:items-center">
        <a href="{{ route('purchases.show', $r->id) }}"
        class="rounded bg-bgray-100 px-3 py-1 text-sm hover:bg-bgray-200">
        Ver
      </a>

      <a href="{{ route('purchases.edit', $r->id) }}"
        class="rounded bg-success-300 px-3 py-1 text-sm text-white hover:bg-success-400">
        Editar
      </a>

      <form action="{{ route('purchases.destroy', $r->id) }}"
            method="POST"
            onsubmit="return confirm('¿Seguro que deseas borrar este registro?')">
        @csrf
        @method('DELETE')
        <button class="rounded bg-red-500 px-3 py-1 text-sm text-white hover:bg-red-600">
          Borrar
        </button>
      </form>
    </div>
  </td>
</tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-bgray-500">
                No hay registros con estos filtros.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-6">
        {{ $rows->links() }}
      </div>
    </div>
  </div>

  {{-- TOP: Total Balance + Overall Balance (debajo del listado) --}}
  <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-12">

    {{-- Total Balance --}}
    <div class="xl:col-span-4">
      <div class="rounded-lg border border-bgray-300 p-8 pb-12 dark:border-darkblack-400 dark:bg-darkblack-600">
        <h3 class="text-2xl font-semibold text-bgray-900 dark:text-white">
          Total Balance
        </h3>

        <h2 class="mb-2 font-poppins text-4xl font-bold text-bgray-900 dark:text-white">
          S/ {{ number_format($totalCost, 2) }}
          <span class="text-base font-medium uppercase text-bgray-500">PEN</span>
        </h2>

        <div class="flex gap-4">
          <span class="text-base font-medium text-bgray-500 dark:text-darkblack-300">
            {{ now()->format('d M Y') }}
          </span>
        </div>
      </div>

      <div class="-mt-6 flex justify-center">
        <div class="rounded-lg bg-white px-6 py-3 text-sm font-medium text-bgray-600 dark:bg-darkblack-600 dark:text-white">
          Ganancia aprox: <span class="font-bold">S/ {{ number_format($profit, 2) }}</span>
        </div>
      </div>
    </div>

    {{-- Overall Balance --}}
    <div class="xl:col-span-8">
      <div class="rounded-lg border border-bgray-300 p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
        <div class="mb-2 flex items-center justify-between pb-2">
          <div>
            <span class="text-sm font-medium text-bgray-600 dark:text-white">Overall Balance</span>
            <div class="flex items-center space-x-2">
              <h3 class="text-2xl font-bold leading-[36px] text-bgray-900 dark:text-white">
                S/ {{ number_format($totalSales, 2) }}
              </h3>
              <span class="text-sm font-medium text-success-300 dark:text-white">
                ventas
              </span>
            </div>
          </div>
        </div>

        <div class="h-[260px] w-full rounded-lg bg-bgray-100 dark:bg-darkblack-500 flex items-center justify-center">
          <canvas id="overallChart" class="w-full h-full"></canvas>
        </div>
      </div>
    </div>

  </div>

@endsection

@section('scripts')
  {{-- Modal para imagen IMEI --}}
  <div id="imgModal" class="fixed inset-0 hidden items-center justify-center bg-black/70 z-[9999]" onclick="closeImg()">
    <img id="imgModalSrc" src="" class="max-h-[90vh] max-w-[90vw] rounded-xl border bg-white" alt="preview">
  </div>

  <script>
    function openImg(src){
      const modal = document.getElementById('imgModal');
      const img = document.getElementById('imgModalSrc');
      img.src = src;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
    function closeImg(){
      const modal = document.getElementById('imgModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      document.getElementById('imgModalSrc').src = '';
    }
    document.addEventListener('keydown', (e) => {
      if(e.key === 'Escape') closeImg();
    });
  </script>

  {{-- Chart.js (si ya lo tienes en layout, no hace falta importar) --}}
  <script>
    async function loadChart() {
      const res = await fetch("{{ route('api.purchases.chart') }}");
      const data = await res.json();

      const ctx = document.getElementById('overallChart');
      if (!ctx) return;

      if (window.__overallChart) window.__overallChart.destroy();

      window.__overallChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [
            { label: 'Costo', data: data.cost, tension: 0.35 },
            { label: 'Venta', data: data.sales, tension: 0.35 }
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
    loadChart();
  </script>
@endsection