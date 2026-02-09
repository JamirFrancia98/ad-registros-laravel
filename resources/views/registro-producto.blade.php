@extends('layouts.app')

@section('title', 'Registro Producto')
@section('header', 'REGISTRO-PRODUCTO')

@section('content')
  <div class="max-w-3xl bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-bold mb-6">Crear COMPRA TELÉFONOS</h2>
@if ($errors->any())
  <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
    <ul class="list-disc pl-5">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
    <form method="POST" action="{{ route('registro-producto.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf

      <div>
        <label class="block text-sm font-medium">Fecha de compra</label>
        <input type="date" name="purchase_date" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">Proveedor</label>
        <select name="supplier_id" class="w-full border rounded-lg p-2" required>
          <option value="">Selecciona</option>
          @foreach($suppliers as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Modelo</label>
        <select id="model_id" name="iphone_model_id" class="w-full border rounded-lg p-2" required>
          <option value="">Selecciona</option>
          @foreach($models as $m)
            <option value="{{ $m->id }}">{{ $m->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">GB</label>
        <select name="storage_option_id" class="w-full border rounded-lg p-2" required>
          <option value="">Selecciona</option>
          @foreach($storages as $st)
            <option value="{{ $st->id }}">{{ $st->label }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Color</label>
        <select id="color_id" name="color_id" class="w-full border rounded-lg p-2" required>
          <option value="">Selecciona un modelo primero</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">IMEI 1</label>
        <input type="text" name="imei1" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">IMEI 2 (opcional)</label>
        <input type="text" name="imei2" class="w-full border rounded-lg p-2">
      </div>

      <div>
        <label class="block text-sm font-medium">Serie (opcional)</label>
        <input type="text" name="serial" class="w-full border rounded-lg p-2">
      </div>

      <div>
        <label class="block text-sm font-medium">Precio compra</label>
        <input id="purchase_price" type="number" step="0.01" name="purchase_price" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">Margen</label>
        <select id="markup" name="markup" class="w-full border rounded-lg p-2">
          <option value="">Selecciona</option>
          <option value="150">+150</option>
          <option value="200">+200</option>
          <option value="250">+250</option>
          <option value="300">+300</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Precio venta</label>
        <input id="sale_price" type="number" step="0.01" name="sale_price" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">Foto IMEI</label>
        <input type="file" name="imei_photo" accept="image/*" class="w-full border rounded-lg p-2">
      </div>

      <div>
        <label class="block text-sm font-medium">Foto iPhone</label>
        <input type="file" name="phone_photo" accept="image/*" class="w-full border rounded-lg p-2">
      </div>

      <div class="md:col-span-2 flex gap-3">
        <button class="bg-black text-white px-4 py-2 rounded-lg">Guardar</button>
        <a href="{{ route('purchases.index') }}" class="px-4 py-2 rounded-lg border">Ver listado</a>
      </div>
    </form>
  </div>
@endsection

@section('scripts')
<script>
  const modelSelect = document.getElementById('model_id');
  const colorSelect = document.getElementById('color_id');
  const purchasePrice = document.getElementById('purchase_price');
  const markup = document.getElementById('markup');
  const salePrice = document.getElementById('sale_price');

  async function loadColors(modelId){
    colorSelect.innerHTML = '<option value="">Cargando...</option>';
    const res = await fetch(`/api/modelos/${modelId}/colores`);
    const data = await res.json();
    colorSelect.innerHTML = '<option value="">Selecciona</option>';
    data.forEach(c => {
      const opt = document.createElement('option');
      opt.value = c.id;
      opt.textContent = c.name;
      colorSelect.appendChild(opt);
    });
  }

  modelSelect.addEventListener('change', (e) => {
    const id = e.target.value;
    if(!id){
      colorSelect.innerHTML = '<option value="">Selecciona un modelo primero</option>';
      return;
    }
    loadColors(id);
  });

  function calcSale(){
    const p = parseFloat(purchasePrice.value || '0');
    const m = parseFloat(markup.value || '0');
    if(p > 0 && m > 0){
      salePrice.value = (p + m).toFixed(2);
    }
  }

  purchasePrice.addEventListener('input', calcSale);
  markup.addEventListener('change', calcSale);
</script>
@endsection