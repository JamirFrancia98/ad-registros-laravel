@extends('layouts.app')

@section('title', 'Editar Compra')
@section('header', 'Editar Compra')

@section('content')
  @if ($errors->any())
    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @php
    // Progreso (para 100% contamos campos + fotos)
    $checks = [
      'Fecha compra' => !empty($purchase->purchase_date),
      'Proveedor' => !empty($purchase->supplier_id),
      'Modelo iPhone' => !empty($purchase->iphone_model_id),
      'GB' => !empty($purchase->storage_option_id),
      'Color' => !empty($purchase->color_id),
      'IMEI 1' => !empty($purchase->imei1),
      'Precio compra' => !empty($purchase->purchase_price),
      'Precio venta' => !empty($purchase->sale_price),
      'Foto IMEI' => !empty($purchase->imei_photo_path),
      'Foto Teléfono' => !empty($purchase->phone_photo_path),
    ];

    $total = count($checks);
    $done = collect($checks)->filter(fn($v) => $v)->count();
    $percent = $total > 0 ? (int) round(($done / $total) * 100) : 0;
    $missing = collect($checks)->filter(fn($v) => !$v)->keys()->values();
  @endphp

  <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

    {{-- LEFT: Form (Plantilla: Personal Information's) --}}
    <div class="xl:col-span-8">
      <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">

        <div class="mb-6 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold text-bgray-900 dark:text-white">Personal Information's</h2>
            <p class="text-sm text-bgray-500">Edita la compra #{{ $purchase->id }}</p>
          </div>
          <a href="{{ route('purchases.index') }}" class="rounded-lg border px-4 py-2 text-sm font-bold">
            Volver
          </a>
        </div>

        <form method="POST"
              action="{{ route('purchases.update', $purchase->id) }}"
              enctype="multipart/form-data"
              class="space-y-8">
          @csrf
          @method('PUT')

          {{-- Datos de compra --}}
          <div>
            <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Datos de compra</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Fecha de compra</label>
                <input type="date" name="purchase_date"
                       value="{{ old('purchase_date', $purchase->purchase_date) }}"
                       class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                       required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Proveedor</label>
                <select name="supplier_id"
                        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                        required>
                  @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" @selected(old('supplier_id', $purchase->supplier_id) == $s->id)>
                      {{ $s->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Modelo iPhone</label>
                <select id="model_id" name="iphone_model_id"
                        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                        required>
                  @foreach($models as $m)
                    <option value="{{ $m->id }}" @selected(old('iphone_model_id', $purchase->iphone_model_id) == $m->id)>
                      {{ $m->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">GB</label>
                <select name="storage_option_id"
                        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                        required>
                  @foreach($storages as $st)
                    <option value="{{ $st->id }}" @selected(old('storage_option_id', $purchase->storage_option_id) == $st->id)>
                      {{ $st->label }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Color</label>
                <select id="color_id" name="color_id"
                        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                        required>
                  @foreach($colors as $c)
                    <option value="{{ $c->id }}" @selected(old('color_id', $purchase->color_id) == $c->id)>
                      {{ $c->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          {{-- Identificadores --}}
          <div>
            <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Identificadores</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">IMEI 1 (bloqueado)</label>
                <input type="text" value="{{ $purchase->imei1 }}"
                       class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none opacity-80 dark:bg-darkblack-500 dark:text-white"
                       disabled>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">IMEI 2 (opcional)</label>
                <input type="text" name="imei2" value="{{ old('imei2', $purchase->imei2) }}"
                       class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Serie (opcional)</label>
                <input type="text" name="serial" value="{{ old('serial', $purchase->serial) }}"
                       class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
              </div>
            </div>
          </div>

          {{-- Precios --}}
          <div>
            <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Precios</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Precio compra</label>
                <input id="purchase_price" type="number" step="0.01" name="purchase_price"
                       value="{{ old('purchase_price', $purchase->purchase_price) }}"
                       class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                       required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Margen</label>
                <select id="markup" name="markup"
                        class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white">
                  <option value="">Selecciona</option>
                  @foreach([150,200,250,300] as $m)
                    <option value="{{ $m }}" @selected(old('markup', $purchase->markup) == $m)>+{{ $m }}</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Precio venta</label>
                <input id="sale_price" type="number" step="0.01" name="sale_price"
                       value="{{ old('sale_price', $purchase->sale_price) }}"
                       class="h-[56px] w-full rounded-lg bg-bgray-100 px-4 text-base text-bgray-700 outline-none dark:bg-darkblack-500 dark:text-white"
                       required>
              </div>
            </div>
          </div>

          {{-- Fotos --}}
          <div>
            <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Fotos</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Actualizar Foto IMEI</label>
                <input type="file" name="imei_photo" accept="image/*"
                       class="w-full rounded-lg bg-bgray-100 p-3 text-sm text-bgray-700 dark:bg-darkblack-500 dark:text-white">
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-bgray-700 dark:text-bgray-50">Actualizar Foto Teléfono</label>
                <input type="file" name="phone_photo" accept="image/*"
                       class="w-full rounded-lg bg-bgray-100 p-3 text-sm text-bgray-700 dark:bg-darkblack-500 dark:text-white">
              </div>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
            <a href="{{ route('purchases.index') }}" class="h-[56px] inline-flex items-center justify-center rounded-lg border px-6 text-sm font-bold">
              Cancelar
            </a>
            <button class="h-[56px] rounded-lg bg-success-300 px-8 text-sm font-bold text-white hover:bg-success-400 transition-all">
              Guardar cambios
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- RIGHT: Progreso + Previews --}}
    <div class="xl:col-span-4">
      <div class="rounded-lg border border-bgray-300 bg-white p-6 dark:border-darkblack-400 dark:bg-darkblack-600">

        <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Complete purchase</h3>
        <p class="text-sm text-bgray-500">Progreso de completitud del registro.</p>

        <div class="mt-5 flex items-center gap-4">
          <div
            class="h-[70px] w-[70px] rounded-full flex items-center justify-center"
            style="background: conic-gradient(#22c55e {{ $percent }}%, rgba(0,0,0,0.10) 0);"
          >
            <div class="h-[56px] w-[56px] rounded-full bg-white dark:bg-darkblack-600 flex items-center justify-center">
              <span class="text-sm font-bold text-bgray-900 dark:text-white">{{ $percent }}%</span>
            </div>
          </div>

          <div>
            <p class="text-sm font-semibold text-bgray-900 dark:text-white">Progreso</p>
            <p class="text-xs text-bgray-500">{{ $done }} / {{ $total }} campos</p>
          </div>
        </div>

        @if($missing->count() > 0)
          <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            <p class="font-bold text-sm mb-2">Falta completar:</p>
            <ul class="list-disc pl-5 text-sm">
              @foreach($missing as $m)
                <li>{{ $m }}</li>
              @endforeach
            </ul>
          </div>
        @else
          <div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            <p class="font-bold text-sm">✅ Registro completo al 100%</p>
          </div>
        @endif

        <div class="mt-6 space-y-5">
          {{-- Preview IMEI --}}
          <div class="rounded-lg border border-bgray-200 p-4 dark:border-darkblack-400">
            <div class="flex items-center justify-between mb-3">
              <p class="text-sm font-bold text-bgray-900 dark:text-white">Preview IMEI</p>
            </div>

            @if(!empty($purchase->imei_photo_path))
              <img
                src="{{ asset('storage/' . $purchase->imei_photo_path) }}"
                alt="IMEI"
                class="w-full rounded-lg object-cover cursor-pointer h-[180px] sm:h-[220px] xl:h-[180px]"
                onclick="openImg(this.src)"
              />
            @else
              <div class="h-[180px] sm:h-[220px] xl:h-[180px] rounded-lg bg-bgray-100 dark:bg-darkblack-500 flex items-center justify-center text-bgray-500">
                Sin imagen
              </div>
            @endif
          </div>

          {{-- Preview Teléfono --}}
          <div class="rounded-lg border border-bgray-200 p-4 dark:border-darkblack-400">
            <div class="flex items-center justify-between mb-3">
              <p class="text-sm font-bold text-bgray-900 dark:text-white">Preview Teléfono</p>
            </div>

            @if(!empty($purchase->phone_photo_path))
              <img
                src="{{ asset('storage/' . $purchase->phone_photo_path) }}"
                alt="Teléfono"
                class="w-full rounded-lg object-cover cursor-pointer h-[180px] sm:h-[220px] xl:h-[180px]"
                onclick="openImg(this.src)"
              />
            @else
              <div class="h-[180px] sm:h-[220px] xl:h-[180px] rounded-lg bg-bgray-100 dark:bg-darkblack-500 flex items-center justify-center text-bgray-500">
                Sin imagen
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>

  </div>
@endsection

@section('scripts')
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

    // Auto-calcular venta por margen
    const purchasePrice = document.getElementById('purchase_price');
    const markup = document.getElementById('markup');
    const salePrice = document.getElementById('sale_price');

    function calcSale(){
      const p = parseFloat(purchasePrice?.value || '0');
      const m = parseFloat(markup?.value || '0');
      if(p > 0 && m > 0){
        salePrice.value = (p + m).toFixed(2);
      }
    }
    purchasePrice?.addEventListener('input', calcSale);
    markup?.addEventListener('change', calcSale);
  </script>
@endsection