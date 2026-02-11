@extends('layouts.app')

@section('title','Crear Venta')
@section('header','Crear Venta')

@section('content')

<div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

  {{-- LISTA de productos NO vendidos --}}
  <div class="xl:col-span-7 rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Selecciona iPhone (no vendido)</h2>
      <a href="{{ route('sales.index') }}" class="text-sm font-bold text-bgray-600 hover:underline">Volver</a>
    </div>

    <p class="mt-2 text-sm text-bgray-500">Elige uno y copia su ID (por ahora). Luego lo hacemos selector/buscador.</p>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-bgray-300 dark:border-darkblack-400">
            <th class="py-3 text-left text-sm text-bgray-600 dark:text-bgray-50">ID</th>
            <th class="py-3 text-left text-sm text-bgray-600 dark:text-bgray-50">iPhone</th>
            <th class="py-3 text-left text-sm text-bgray-600 dark:text-bgray-50">IMEI (últ. 5)</th>
            <th class="hidden md:table-cell py-3 text-left text-sm text-bgray-600 dark:text-bgray-50">Costo</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $p)
            <tr class="border-b border-bgray-200 dark:border-darkblack-400">
              <td class="py-3 font-bold text-bgray-900 dark:text-white">{{ $p->id }}</td>
              <td class="py-3 text-bgray-900 dark:text-white">
                {{ $p->iphoneModel->name ?? '—' }}
                @if($p->storageOption?->label)
                  <span class="text-bgray-500">({{ $p->storageOption->label }})</span>
                @endif
              </td>
              <td class="py-3 text-bgray-900 dark:text-white">{{ substr((string)$p->imei1, -5) }}</td>
              <td class="hidden md:table-cell py-3 text-bgray-900 dark:text-white">S/ {{ number_format((float)$p->purchase_price, 0) }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="py-8 text-center text-bgray-500">No hay productos por vender.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      {{ $products->links() }}
    </div>
  </div>

  {{-- FORM Registrar venta --}}
  <div class="xl:col-span-5 rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">
    <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Registrar Venta</h2>

    <form method="POST" action="{{ route('sales.store') }}" class="mt-5 space-y-4">
      @csrf

      {{-- Cliente --}}
      <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Nombres</label>
          <input name="first_name" value="{{ old('first_name') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Apellidos</label>
          <input name="last_name" value="{{ old('last_name') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Tipo doc</label>
          <select name="document_type"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
            <option value="DNI" @selected(old('document_type')==='DNI')>DNI</option>
            <option value="CE" @selected(old('document_type')==='CE')>CE</option>
          </select>
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">N° documento</label>
          <input name="document_number" value="{{ old('document_number') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Teléfono</label>
          <input name="phone" value="{{ old('phone') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Operador</label>
          <select name="operator"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white">
            <option value="">—</option>
            <option value="Claro" @selected(old('operator')==='Claro')>Claro</option>
            <option value="Movistar" @selected(old('operator')==='Movistar')>Movistar</option>
            <option value="Entel" @selected(old('operator')==='Entel')>Entel</option>
            <option value="Bitel" @selected(old('operator')==='Bitel')>Bitel</option>
            <option value="Otro" @selected(old('operator')==='Otro')>Otro</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Correo (opcional)</label>
          <input type="email" name="email" value="{{ old('email') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white">
        </div>
      </div>

      <hr class="my-3 border-bgray-200 dark:border-darkblack-400">

      {{-- Venta --}}
      <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Purchase ID</label>
          <input name="purchase_id" value="{{ old('purchase_id') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
        </div>

        <div>
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Fecha venta</label>
          <input type="date" name="sold_at" value="{{ old('sold_at') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
        </div>

        <div class="md:col-span-2">
          <label class="mb-2 block text-sm font-bold text-bgray-900 dark:text-white">Precio venta final</label>
          <input type="number" step="0.01" name="sold_price" value="{{ old('sold_price') }}"
            class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 dark:bg-darkblack-500 dark:text-white" required>
        </div>
      </div>

      {{-- Accesorios (desplegable) --}}
      <div class="mt-2">
        <button type="button" id="toggleItems"
          class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-left text-sm font-bold dark:border-darkblack-400">
          + Agregar accesorios (opcional)
        </button>

        <div id="itemsBox" class="mt-3 hidden rounded-lg bg-bgray-100 p-4 dark:bg-darkblack-500">
          <div id="itemsList" class="space-y-3"></div>

          <button type="button" id="addItem"
            class="mt-3 rounded-lg bg-bgray-900 px-4 py-2 text-sm font-bold text-white dark:bg-white dark:text-black">
            + Añadir ítem
          </button>

          <p class="mt-2 text-xs text-bgray-500">Si dejas nombre o precio vacío, no se guarda.</p>
        </div>
      </div>

      <button class="mt-4 h-[56px] w-full rounded-lg bg-success-300 text-sm font-bold text-white hover:bg-success-400">
        Guardar venta
      </button>

      @if($errors->any())
        <div class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-700">
          <ul class="list-disc pl-5">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

    </form>
  </div>

</div>

@endsection

@section('scripts')
<script>
  const toggleBtn = document.getElementById('toggleItems');
  const itemsBox = document.getElementById('itemsBox');
  const itemsList = document.getElementById('itemsList');
  const addItemBtn = document.getElementById('addItem');

  toggleBtn?.addEventListener('click', () => {
    itemsBox.classList.toggle('hidden');
  });

  function itemRow(idx){
    const wrap = document.createElement('div');
    wrap.className = "grid grid-cols-1 gap-2 md:grid-cols-4";

    wrap.innerHTML = `
      <input name="items[${idx}][name]" placeholder="Nombre (case, cable, etc.)"
        class="h-[48px] w-full rounded-lg bg-white px-3 text-sm dark:bg-darkblack-600 dark:text-white md:col-span-2" />

      <input name="items[${idx}][qty]" type="number" min="1" value="1"
        class="h-[48px] w-full rounded-lg bg-white px-3 text-sm dark:bg-darkblack-600 dark:text-white" />

      <input name="items[${idx}][price]" type="number" step="0.01" min="0" placeholder="Precio"
        class="h-[48px] w-full rounded-lg bg-white px-3 text-sm dark:bg-darkblack-600 dark:text-white" />
    `;
    return wrap;
  }

  let itemIndex = 0;
  addItemBtn?.addEventListener('click', () => {
    itemsList.appendChild(itemRow(itemIndex++));
  });
</script>
@endsection