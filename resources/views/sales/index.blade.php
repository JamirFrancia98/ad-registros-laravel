@extends('layouts.app')

@section('title','Ventas')
@section('header','Ventas')

@section('content')

<div class="rounded-lg border border-bgray-300 bg-white dark:border-darkblack-400 dark:bg-darkblack-600">

  <div class="flex flex-col gap-4 px-6 py-5 xl:flex-row xl:items-center xl:justify-between">
    <div>
      <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Listado de Ventas</h3>
      <p class="text-sm text-bgray-500">Cliente / iPhone / Total</p>
    </div>

    <a href="{{ route('sales.create') }}"
      class="rounded-lg bg-success-300 px-5 py-3 text-sm font-bold text-white hover:bg-success-400 transition-all">
      + Nueva venta
    </a>
  </div>

  {{-- BUSCADOR --}}
  <div class="px-6 pb-5">
    <form method="GET" action="{{ route('sales.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
      <div class="flex-1">
        <p class="mb-2 text-base font-bold text-bgray-900 dark:text-white">Buscar</p>
        <input
          type="text"
          name="q"
          value="{{ request('q') }}"
          placeholder="IMEI, serie, DNI, nombre o teléfono"
          class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
        />
      </div>

      <div class="flex gap-2 sm:justify-end">
        <button class="h-[56px] rounded-lg bg-success-300 px-5 text-sm font-bold text-white hover:bg-success-400">
          Filtrar
        </button>

        <a href="{{ route('sales.index') }}"
          class="h-[56px] rounded-lg border border-bgray-200 px-5 text-sm font-bold flex items-center justify-center dark:border-darkblack-400">
          Limpiar
        </a>
      </div>
    </form>
  </div>

  {{-- TABLA --}}
  <div class="table-content w-full overflow-x-auto px-6 pb-6">
    <table class="w-full">
      <thead>
        <tr class="border-b border-bgray-300 dark:border-darkblack-400">
          {{-- Desktop: FECHA --}}
          <th class="hidden md:table-cell py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Fecha</th>

          {{-- Cliente --}}
          <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Cliente</th>

          {{-- iPhone --}}
          <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">iPhone</th>

          {{-- Total --}}
          <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Total</th>

          {{-- Acciones --}}
          <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($sales as $s)
          @php
            $p = $s->purchase;
            $model = $p?->iphoneModel?->name ?? 'iPhone';
            $storage = $p?->storageOption?->label ?? '';
            $customer = $s->customer;

            $cliente = trim(($customer?->first_name ?? '').' '.($customer?->last_name ?? ''));
            $dni = $customer?->dni ?? null;

            $fecha = $s->sold_at ? \Illuminate\Support\Carbon::parse($s->sold_at)->format('Y-m-d') : '—';
          @endphp

          <tr class="border-b border-bgray-200 dark:border-darkblack-400">
            {{-- Fecha (desktop) --}}
            <td class="hidden md:table-cell py-4 text-bgray-900 dark:text-white">
              {{ $fecha }}
            </td>

            {{-- Cliente (mobile + desktop) --}}
            <td class="py-4 text-bgray-900 dark:text-white">
              <div class="font-bold">{{ $cliente ?: '—' }}</div>
              @if($dni)
                <div class="text-xs text-bgray-500">DNI: {{ $dni }}</div>
              @endif
            </td>

            {{-- iPhone --}}
            <td class="py-4 text-bgray-900 dark:text-white">
              <div class="font-bold">{{ $model }}</div>
              @if($storage)
                <div class="text-xs text-bgray-500">{{ $storage }}</div>
              @endif
            </td>

            {{-- Total --}}
            <td class="py-4 text-bgray-900 dark:text-white font-bold">
              S/ {{ number_format((float)$s->grand_total, 0) }}
            </td>

            {{-- Acciones: en mobile uno debajo del otro --}}
            <td class="py-4">
              <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <a href="{{ route('sales.edit', $s->id) }}"
                  class="rounded bg-success-300 px-3 py-2 text-sm text-white text-center hover:bg-success-400">
                  Editar
                </a>

                <form action="{{ route('sales.destroy', $s->id) }}" method="POST"
                  onsubmit="return confirm('¿Seguro que deseas borrar esta venta?')">
                  @csrf
                  @method('DELETE')
                  <button class="w-full rounded bg-red-500 px-3 py-2 text-sm text-white hover:bg-red-600">
                    Borrar
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="py-8 text-center text-bgray-500">
              Aún no hay ventas registradas.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="mt-6">
      {{ $sales->links() }}
    </div>
  </div>

</div>

@endsection