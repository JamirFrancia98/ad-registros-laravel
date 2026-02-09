@extends('layouts.app')

@section('title', 'Listado Compras')
@section('header', 'Listado Compras')

@section('content')



  {{-- LISTA INFERIOR (tabla) --}}
  <div class="mt-6 rounded-lg border border-bgray-300 bg-white dark:border-darkblack-400 dark:bg-darkblack-600">
    <div class="flex items-center justify-between px-6 py-5">
      <div>
        <h3 class="text-xl font-bold text-bgray-900 dark:text-white">Listado Compras</h3>
        <p class="text-sm text-bgray-500">Proveedor / iPhone / IMEI / Costo / Fecha</p>
      </div>

      <a href="{{ route('registro-producto.create') }}"
         class="rounded-lg bg-success-300 px-5 py-3 text-sm font-bold text-white hover:bg-success-400 transition-all">
        + Nuevo registro
      </a>
    </div>

    <div class="table-content w-full overflow-x-auto px-6 pb-6">
      <table class="w-full">
        <thead>
          <tr class="border-b border-bgray-300 dark:border-darkblack-400">
            <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Proveedor</th>
            <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">iPhone</th>
            <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">IMEI (últ. 5)</th> 
            <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Foto IMEI</th>
            <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Precio Costo</th>
            <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Fecha Compra</th>
            <th class="py-4 text-left text-sm font-medium text-bgray-600 dark:text-bgray-50">Acciones</th>
          </tr>
        </thead>

        <tbody>
          @forelse($rows as $r)
            <tr class="border-b border-bgray-200 dark:border-darkblack-400">
              <td class="py-4 text-bgray-900 dark:text-white">{{ $r->supplier_name }}</td>
              <td class="py-4 text-bgray-900 dark:text-white">{{ $r->model_name }}</td>
              <td class="py-4 text-bgray-900 dark:text-white">{{ substr($r->imei1, -5) }}</td>
              <td class="py-4">
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
              <td class="py-4 text-bgray-900 dark:text-white">S/ {{ number_format((float)$r->purchase_price, 2) }}</td>
              <td class="py-4 text-bgray-900 dark:text-white">{{ $r->purchase_date }}</td>
              
              <td class="py-4">
              <div class="flex items-center gap-2">

                {{-- VER --}}
                <a href="{{ route('purchases.show', $r->id) }}"
                  class="rounded bg-bgray-100 px-3 py-1 text-sm hover:bg-bgray-200">
                  Ver
                </a>

                {{-- EDITAR --}}
                <a href="{{ route('purchases.edit', $r->id) }}"
                  class="rounded bg-success-300 px-3 py-1 text-sm text-white hover:bg-success-400">
                  Editar
                </a>

                {{-- BORRAR --}}
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
              <td colspan="5" class="py-8 text-center text-bgray-500">
                Aún no hay compras registradas.
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


  {{-- TOP: Total Balance + Overall Balance (solo estas 2 secciones) --}}
  <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

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

      {{-- (Opcional) Si quieres mostrar ganancia --}}
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

        {{-- Placeholder del gráfico (luego lo conectamos a Chart.js con tus datos reales) --}}
        <div class="h-[260px] w-full rounded-lg bg-bgray-100 dark:bg-darkblack-500 flex items-center justify-center">
            <canvas id="overallChart" class="w-full h-full"></canvas> 
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
  </script>
@endsection