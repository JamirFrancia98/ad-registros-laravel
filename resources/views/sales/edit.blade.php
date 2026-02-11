@extends('layouts.app')

@section('title','Editar Venta')
@section('header','Editar Venta')

@section('content')

@php
  // Campos “clave” para progreso
  $missing = [];

  $c = $sale->customer;
  $fn = old('first_name', $c->first_name ?? '');
  $ln = old('last_name',  $c->last_name ?? '');
  $dni = old('dni', $c->dni ?? '');
  $ph = old('phone', $c->phone ?? '');
  $sa = old('sold_at', $sale->sold_at ?? '');
  $sp = old('sold_price', $sale->sold_price ?? '');

  if(trim($fn)==='') $missing[] = 'Nombres';
  if(trim($ln)==='') $missing[] = 'Apellidos';
  if(trim($dni)==='') $missing[] = 'DNI';
  if(trim($ph)==='') $missing[] = 'Teléfono';
  if(trim((string)$sa)==='') $missing[] = 'Fecha de venta';
  if(trim((string)$sp)==='') $missing[] = 'Precio de venta';

  $totalRequired = 6;
  $completed = $totalRequired - count($missing);
  $percent = (int) round(($completed / $totalRequired) * 100);
@endphp

<form method="POST" action="{{ route('sales.update', $sale->id) }}" class="grid grid-cols-1 gap-6 xl:grid-cols-12">
  @csrf
  @method('PUT')

  {{-- IZQUIERDA: FORM --}}
  <div class="xl:col-span-8">

    {{-- PROGRESO --}}
    <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Progreso de la venta</h3>
          <p class="text-sm text-bgray-500">Completo: {{ $percent }}%</p>
        </div>
        <div class="text-sm font-bold text-success-300">{{ $completed }}/{{ $totalRequired }}</div>
      </div>

      <div class="mt-4 h-3 w-full rounded-full bg-bgray-100 dark:bg-darkblack-500 overflow-hidden">
        <div class="h-full bg-success-300" style="width: {{ $percent }}%"></div>
      </div>

      @if(count($missing))
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
          <div class="font-bold mb-1">Falta completar:</div>
          <ul class="list-disc pl-5">
            @foreach($missing as $m)
              <li>{{ $m }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>

    {{-- PERSONAL INFORMATION --}}
    <div class="mt-6 rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Personal Information's</h3>
      <p class="text-sm text-bgray-500">Datos del cliente</p>

      <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Nombres</label>
          <input name="first_name" value="{{ old('first_name', $c->first_name ?? '') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white" />
          @error('first_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Apellidos</label>
          <input name="last_name" value="{{ old('last_name', $c->last_name ?? '') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white" />
          @error('last_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">DNI (único)</label>
          <input name="dni" value="{{ old('dni', $c->dni ?? '') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white" />
          @error('dni')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Teléfono</label>
          <input name="phone" value="{{ old('phone', $c->phone ?? '') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white" />
          @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Correo (opcional)</label>
          <input name="email" value="{{ old('email', $c->email ?? '') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white" />
          @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Operador (opcional)</label>
          <input name="operator" value="{{ old('operator', $c->operator ?? '') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white" />
          @error('operator')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
      </div>
    </div>

    {{-- INFO IPHONE (solo lectura, más visible) --}}
<div class="mt-6 rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
  <div class="flex items-center justify-between">
    <div>
      <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Información del iPhone</h3>
      <p class="text-sm text-bgray-500">Estos datos vienen del producto y no se editan desde Ventas</p>
    </div>

    <span class="rounded-lg bg-bgray-100 px-3 py-2 text-xs font-bold text-bgray-700 dark:bg-darkblack-500 dark:text-white">
      ID Producto: {{ $purchase->id ?? '—' }}
    </span>
  </div>

  <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">

    <div class="md:col-span-2">
      <div class="rounded-lg bg-success-50 p-4 dark:bg-darkblack-500">
        <div class="text-base font-bold text-bgray-900 dark:text-white">
          {{ $purchase?->iphoneModel?->name ?? '—' }}
          @if($purchase?->storageOption?->label)
            <span class="text-bgray-600 dark:text-bgray-50 font-medium">({{ $purchase->storageOption->label }})</span>
          @endif
        </div>

        <div class="mt-1 text-sm text-bgray-600 dark:text-bgray-50">
        
          Costo: <span class="font-bold">S/ {{ number_format((float)($purchase?->purchase_price ?? 0), 0) }}</span>
        </div>
      </div>
    </div>

    {{-- IMEI 1 (completo, deshabilitado) --}}
    <div>
      <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">IMEI 1</label>
      <input
        value="{{ $purchase?->imei1 ?? '' }}"
        disabled
        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none opacity-80 dark:bg-darkblack-500 dark:text-white"
      />
    </div>

    {{-- IMEI 2 (completo, deshabilitado) --}}
    <div>
      <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">IMEI 2</label>
      <input
        value="{{ $purchase?->imei2 ?? '' }}"
        disabled
        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none opacity-80 dark:bg-darkblack-500 dark:text-white"
      />
      <p class="mt-1 text-xs text-bgray-500">Si no aplica, quedará vacío.</p>
    </div>

    {{-- Serie (deshabilitado) --}}
    <div>
      <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Serie</label>
      <input
        value="{{ $purchase?->serial ?? '' }}"
        disabled
        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none opacity-80 dark:bg-darkblack-500 dark:text-white"
      />
    </div>

    {{-- Color (solo lectura) --}}
    <div>
      <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Color</label>
      <input
        value="{{ $purchase?->color?->name ?? '—' }}"
        disabled
        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-bgray-700 outline-none opacity-80 dark:bg-darkblack-500 dark:text-white"
      />
    </div>

  </div>
</div>

    {{-- ACCESORIOS --}}
    <div class="mt-6 rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Accesorios</h3>
          <p class="text-sm text-bgray-500">Opcional (se suma al total)</p>
        </div>
        <button type="button" onclick="addItemRow()"
          class="rounded-lg bg-bgray-900 px-4 py-2 text-sm font-bold text-white hover:opacity-90">
          + Agregar
        </button>
      </div>

      <div id="itemsWrap" class="mt-4 space-y-3">
        @php
          $oldItems = old('items');
          $renderItems = is_array($oldItems) ? $oldItems : ($items->toArray() ?? []);
        @endphp

        @forelse($renderItems as $k => $it)
          <div class="itemRow grid grid-cols-1 gap-2 md:grid-cols-12">
            <input name="items[{{ $k }}][name]" value="{{ $it['name'] ?? '' }}"
              placeholder="Ej: Case / Cable / Audífonos"
              class="md:col-span-6 h-[56px] rounded-lg bg-bgray-100 px-4 outline-none dark:bg-darkblack-500 dark:text-white" />

            <input name="items[{{ $k }}][qty]" type="number" min="1" value="{{ $it['qty'] ?? 1 }}"
              class="md:col-span-2 h-[56px] rounded-lg bg-bgray-100 px-4 outline-none dark:bg-darkblack-500 dark:text-white" />

            <input name="items[{{ $k }}][price]" type="number" step="0.01" min="0" value="{{ $it['price'] ?? 0 }}"
              placeholder="Precio"
              class="md:col-span-3 h-[56px] rounded-lg bg-bgray-100 px-4 outline-none dark:bg-darkblack-500 dark:text-white" />

            <button type="button" onclick="removeItemRow(this)"
              class="md:col-span-1 h-[56px] rounded-lg bg-red-500 px-4 text-white">
              X
            </button>
          </div>
        @empty
          {{-- Sin items iniciales --}}
        @endforelse
      </div>

      <div class="mt-5 text-sm text-bgray-600 dark:text-bgray-50">
        <span class="font-bold">Total actual:</span> S/ {{ number_format((float)$sale->grand_total, 0) }}
      </div>
    </div>

    {{-- BOTONES --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
      <button class="rounded-lg bg-success-300 px-6 py-3 text-sm font-bold text-white hover:bg-success-400">
        Guardar cambios
      </button>

      <a href="{{ route('sales.index') }}"
        class="rounded-lg border border-bgray-200 px-6 py-3 text-sm font-bold text-center dark:border-darkblack-400">
        Cancelar
      </a>
    </div>

  </div>

  {{-- DERECHA: PREVIEWS (imei + phone si existe) --}}
  <div class="xl:col-span-4">
    <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Previsualización</h3>
      <p class="text-sm text-bgray-500">Fotos del equipo</p>

      <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-1">
        {{-- IMEI photo --}}
        <div class="rounded-lg border border-bgray-200 p-3 dark:border-darkblack-400">
          <div class="text-sm font-bold text-bgray-900 dark:text-white mb-2">Foto IMEI</div>
          @php $imeiPath = $purchase?->imei_photo_path ?? null; @endphp
          @if($imeiPath)
            <img src="{{ asset('storage/'.$imeiPath) }}" class="w-full rounded-lg object-cover max-h-[220px] sm:max-h-[180px]" />
          @else
            <div class="text-sm text-bgray-500">— Sin foto</div>
          @endif
        </div>

        {{-- Phone photo --}}
        <div class="rounded-lg border border-bgray-200 p-3 dark:border-darkblack-400">
          <div class="text-sm font-bold text-bgray-900 dark:text-white mb-2">Foto iPhone</div>
          @php $phonePath = $purchase?->phone_photo_path ?? null; @endphp
          @if($phonePath)
            <img src="{{ asset('storage/'.$phonePath) }}" class="w-full rounded-lg object-cover max-h-[220px] sm:max-h-[180px]" />
          @else
            <div class="text-sm text-bgray-500">— Sin foto</div>
          @endif
        </div>
      </div>
    </div>
  </div>

</form>
@endsection

@section('scripts')
<script>
  let itemIndex = document.querySelectorAll('#itemsWrap .itemRow').length || 0;

  function addItemRow(){
    const wrap = document.getElementById('itemsWrap');
    const row = document.createElement('div');
    row.className = 'itemRow grid grid-cols-1 gap-2 md:grid-cols-12';

    row.innerHTML = `
      <input name="items[${itemIndex}][name]" placeholder="Ej: Case / Cable / Audífonos"
        class="md:col-span-6 h-[56px] rounded-lg bg-bgray-100 px-4 outline-none dark:bg-darkblack-500 dark:text-white" />

      <input name="items[${itemIndex}][qty]" type="number" min="1" value="1"
        class="md:col-span-2 h-[56px] rounded-lg bg-bgray-100 px-4 outline-none dark:bg-darkblack-500 dark:text-white" />

      <input name="items[${itemIndex}][price]" type="number" step="0.01" min="0" value="0"
        placeholder="Precio"
        class="md:col-span-3 h-[56px] rounded-lg bg-bgray-100 px-4 outline-none dark:bg-darkblack-500 dark:text-white" />

      <button type="button" onclick="removeItemRow(this)"
        class="md:col-span-1 h-[56px] rounded-lg bg-red-500 px-4 text-white">X</button>
    `;

    wrap.appendChild(row);
    itemIndex++;
  }

  function removeItemRow(btn){
    const row = btn.closest('.itemRow');
    if(row) row.remove();
  }
</script>
@endsection