@extends('layouts.app')

@section('title','Crear Venta')
@section('header','Crear Venta')

@section('content')

<div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

  {{-- LEFT: FORM --}}
  <div class="xl:col-span-8">
    <div class="rounded-lg border border-bgray-300 bg-white dark:border-darkblack-400 dark:bg-darkblack-600">
      <div class="border-b border-bgray-200 px-6 py-5 dark:border-darkblack-400">
        <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Registrar Venta</h3>
        <p class="mt-1 text-sm text-bgray-500">Cliente + iPhone vendido + precio final. Accesorios opcionales.</p>
      </div>

      <div class="p-6">
        <form method="POST" action="{{ route('sales.store') }}" class="space-y-6">
          @csrf

          {{-- Cliente --}}
          <div>
            <h4 class="text-lg font-bold text-bgray-900 dark:text-white">Datos del cliente</h4>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Nombres</label>
                <input name="first_name" value="{{ old('first_name') }}" required
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Apellidos</label>
                <input name="last_name" value="{{ old('last_name') }}" required
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">DNI (8 dígitos)</label>
                <input name="dni" value="{{ old('dni') }}" required
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                  placeholder="Ej: 71234567">
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Teléfono</label>
                <input name="phone" value="{{ old('phone') }}" required
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                  placeholder="Ej: 999 999 999">
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Operador (opcional)</label>
                <select name="operator"
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
                  <option value="">—</option>
                  <option value="Claro" @selected(old('operator')==='Claro')>Claro</option>
                  <option value="Movistar" @selected(old('operator')==='Movistar')>Movistar</option>
                  <option value="Entel" @selected(old('operator')==='Entel')>Entel</option>
                  <option value="Bitel" @selected(old('operator')==='Bitel')>Bitel</option>
                  <option value="Otro" @selected(old('operator')==='Otro')>Otro</option>
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Correo (opcional)</label>
                <input type="email" name="email" value="{{ old('email') }}"
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                  placeholder="cliente@email.com">
              </div>
            </div>
          </div>

          <hr class="border-bgray-200 dark:border-darkblack-400">

          {{-- Venta --}}
          <div>
            <h4 class="text-lg font-bold text-bgray-900 dark:text-white">Datos de la venta</h4>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">

              {{-- Selector de iPhone (NO vendido) --}}
              <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">iPhone (stock disponible)</label>

                <select id="purchaseSelect" name="purchase_id" required
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
                  <option value="">Selecciona un equipo...</option>

                  @foreach($products as $p)
                    @php
                      $label = trim(($p->iphoneModel->name ?? 'iPhone') . ' ' . ($p->storageOption->label ?? ''));
                      $imei5 = substr((string)$p->imei1, -5);
                    @endphp
                    <option
                      value="{{ $p->id }}"
                      data-imei5="{{ $imei5 }}"
                      data-model="{{ $p->iphoneModel->name ?? '' }}"
                      data-storage="{{ $p->storageOption->label ?? '' }}"
                      data-cost="{{ (float)$p->purchase_price }}"
                      data-serial="{{ $p->serial ?? '' }}"
                    >
                      #{{ $p->id }} — {{ $label }} — IMEI: {{ $imei5 }} — Costo: S/ {{ number_format((float)$p->purchase_price, 0) }}
                    </option>
                  @endforeach
                </select>

                <p class="mt-2 text-xs text-bgray-500">
                  Si no aparece, es porque ya fue vendido o no está registrado.
                </p>

                {{-- Paginación del stock (si hay muchos) --}}
                <div class="mt-3">
                  {{ $products->links() }}
                </div>
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Fecha de venta</label>
                <input type="date" name="sold_at" value="{{ old('sold_at') ?? now()->toDateString() }}" required
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Precio venta final</label>
                <input id="soldPrice" type="number" step="0.01" name="sold_price" value="{{ old('sold_price') }}" required
                  class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                  placeholder="Ej: 3200">
                <p class="mt-1 text-xs text-bgray-500">Puede ser mayor o menor al costo.</p>
              </div>
            </div>
          </div>

          {{-- Accesorios (colapsable) --}}
          <div>
            <button type="button" id="toggleItems"
              class="flex w-full items-center justify-between rounded-lg border border-bgray-200 bg-white px-4 py-4 text-sm font-bold text-bgray-900 dark:border-darkblack-400 dark:bg-darkblack-600 dark:text-white">
              <span>+ Agregar accesorios (opcional)</span>
              <span id="toggleItemsIcon">▼</span>
            </button>

            <div id="itemsBox" class="mt-3 hidden rounded-lg bg-bgray-100 p-4 dark:bg-darkblack-500">
              <div id="itemsList" class="space-y-3"></div>

              <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                <button type="button" id="addItem"
                  class="rounded-lg bg-bgray-900 px-4 py-2 text-sm font-bold text-white dark:bg-white dark:text-black">
                  + Añadir ítem
                </button>

                <button type="button" id="clearItems"
                  class="rounded-lg border border-bgray-200 px-4 py-2 text-sm font-bold dark:border-darkblack-400">
                  Limpiar ítems
                </button>
              </div>

              <p class="mt-2 text-xs text-bgray-500">Si dejas nombre o precio vacío, no se guarda.</p>
            </div>
          </div>

          {{-- Errores --}}
          @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
              <ul class="list-disc pl-5">
                @foreach($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Submit --}}
          <button class="h-[56px] w-full rounded-lg bg-success-300 text-sm font-bold text-white hover:bg-success-400">
            Guardar venta
          </button>

        </form>
      </div>
    </div>
  </div>

  {{-- RIGHT: RESUMEN / PREVIEW --}}
  <div class="xl:col-span-4">
    <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
      <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Resumen</h3>
      <p class="mt-1 text-sm text-bgray-500">Se actualiza al elegir un iPhone.</p>

      <div class="mt-5 space-y-3 text-sm">
        <div class="flex items-center justify-between">
          <span class="text-bgray-600 dark:text-bgray-50">Modelo</span>
          <span id="pvModel" class="font-bold text-bgray-900 dark:text-white">—</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-bgray-600 dark:text-bgray-50">Almacenamiento</span>
          <span id="pvStorage" class="font-bold text-bgray-900 dark:text-white">—</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-bgray-600 dark:text-bgray-50">IMEI (últ. 5)</span>
          <span id="pvImei" class="font-bold text-bgray-900 dark:text-white">—</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-bgray-600 dark:text-bgray-50">Costo</span>
          <span id="pvCost" class="font-bold text-bgray-900 dark:text-white">—</span>
        </div>
      </div>

      <div class="mt-6 rounded-lg bg-bgray-100 p-4 dark:bg-darkblack-500">
        <div class="flex items-center justify-between">
          <span class="text-sm font-bold text-bgray-900 dark:text-white">Tip</span>
          <span class="text-xs text-bgray-500">rápido</span>
        </div>
        <p class="mt-2 text-sm text-bgray-600 dark:text-bgray-50">
          Si el cliente trae accesorios, agrégalos para que el total final quede completo.
        </p>
      </div>
    </div>
  </div>

</div>

@endsection

@section('scripts')
<script>
  const purchaseSelect = document.getElementById('purchaseSelect');
  const pvModel = document.getElementById('pvModel');
  const pvStorage = document.getElementById('pvStorage');
  const pvImei = document.getElementById('pvImei');
  const pvCost = document.getElementById('pvCost');
  const soldPrice = document.getElementById('soldPrice');

  function money(v){
    try { return 'S/ ' + Math.round(Number(v || 0)).toLocaleString('es-PE'); }
    catch(e){ return 'S/ ' + v; }
  }

  purchaseSelect?.addEventListener('change', () => {
    const opt = purchaseSelect.options[purchaseSelect.selectedIndex];
    if (!opt || !opt.value) return;

    const model = opt.dataset.model || '—';
    const storage = opt.dataset.storage || '—';
    const imei5 = opt.dataset.imei5 || '—';
    const cost = opt.dataset.cost || 0;

    pvModel.textContent = model;
    pvStorage.textContent = storage;
    pvImei.textContent = imei5;
    pvCost.textContent = money(cost);

    // Sugerencia rápida: si el precio está vacío, propone costo + 200
    if (soldPrice && String(soldPrice.value || '').trim() === '') {
      const suggested = Number(cost || 0) + 200;
      soldPrice.value = suggested.toFixed(2);
    }
  });

  // Accesorios (colapsable)
  const toggleBtn = document.getElementById('toggleItems');
  const itemsBox = document.getElementById('itemsBox');
  const itemsList = document.getElementById('itemsList');
  const addItemBtn = document.getElementById('addItem');
  const clearItemsBtn = document.getElementById('clearItems');
  const toggleIcon = document.getElementById('toggleItemsIcon');

  toggleBtn?.addEventListener('click', () => {
    const isHidden = itemsBox.classList.contains('hidden');
    itemsBox.classList.toggle('hidden');
    if (toggleIcon) toggleIcon.textContent = isHidden ? '▲' : '▼';
  });

  function itemRow(idx){
    const wrap = document.createElement('div');
    wrap.className = "grid grid-cols-1 gap-2 sm:grid-cols-4";

    wrap.innerHTML = `
      <input name="items[${idx}][name]" placeholder="Nombre (case, cable, etc.)"
        class="h-[48px] w-full rounded-lg bg-white px-3 text-sm outline-none dark:bg-darkblack-600 dark:text-white sm:col-span-2" />

      <input name="items[${idx}][qty]" type="number" min="1" value="1"
        class="h-[48px] w-full rounded-lg bg-white px-3 text-sm outline-none dark:bg-darkblack-600 dark:text-white" />

      <input name="items[${idx}][price]" type="number" step="0.01" min="0" placeholder="Precio"
        class="h-[48px] w-full rounded-lg bg-white px-3 text-sm outline-none dark:bg-darkblack-600 dark:text-white" />
    `;
    return wrap;
  }

  let itemIndex = 0;

  addItemBtn?.addEventListener('click', () => {
    itemsList.appendChild(itemRow(itemIndex++));
  });

  clearItemsBtn?.addEventListener('click', () => {
    itemsList.innerHTML = '';
    itemIndex = 0;
  });
</script>
@endsection