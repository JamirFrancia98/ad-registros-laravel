<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta
    name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
  />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <title>@yield('title', 'AD Registros')</title>

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/output.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>

<body>
<div class="layout-wrapper active w-full">
  <div class="relative flex w-full">

    {{-- SIDEBAR FULL --}}
    <aside class="sidebar-wrapper fixed top-0 z-30 block h-full w-[308px] bg-white dark:bg-darkblack-600 sm:hidden xl:block">
      <div class="sidebar-header relative z-30 flex h-[108px] items-center border-b pl-[50px] dark:border-darkblack-400">
        <a href="{{ route('purchases.index') }}">
          <img src="{{ asset('assets/images/logo/logo-color.svg') }}" class="block dark:hidden" />
          <img src="{{ asset('assets/images/logo/logo-white.svg') }}" class="hidden dark:block" />
        </a>
      </div>

      <div class="sidebar-body h-screen overflow-y-scroll pb-[200px] pl-[48px] pt-[14px]">
        <div class="nav-wrapper pr-[50px]">

          <h4 class="border-b text-sm font-medium text-bgray-700 dark:text-bgray-50">
            Menu
          </h4>

          {{-- PRODUCTOS (COLLAPSIBLE) --}}
<li class="item py-[11px] text-bgray-900 dark:text-white">

  <button type="button"
    class="w-full"
    onclick="toggleSidebarGroup('productos-group','productos-arrow')"
  >
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2.5">
        <span class="item-ico">
          {{-- icono (puedes cambiarlo luego) --}}
          <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 16V6C18 3.79086 16.2091 2 14 2H4C1.79086 2 0 3.79086 0 6V16C0 18.2091 1.79086 20 4 20H14C16.2091 20 18 18.2091 18 16Z" fill="#1A202C" class="path-1"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 8C4.25 7.58579 4.58579 7.25 5 7.25H13C13.4142 7.25 13.75 7.58579 13.75 8C13.75 8.41421 13.4142 8.75 13 8.75H5C4.58579 8.75 4.25 8.41421 4.25 8Z" fill="#22C55E" class="path-2"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.25 12C4.25 11.5858 4.58579 11.25 5 11.25H13C13.4142 11.25 13.75 11.5858 13.75 12C13.75 12.4142 13.4142 12.75 13 12.75H5C4.58579 12.75 4.25 12.4142 4.25 12Z" fill="#22C55E" class="path-2"/>
          </svg>
        </span>

        <span class="item-text text-lg font-medium leading-none">
          Productos
        </span>
      </div>

      <span id="productos-arrow" class="transition-transform duration-200">
        <svg width="6" height="12" viewBox="0 0 6 12" fill="none" class="fill-current" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
            d="M0.531506 0.414376C0.20806 0.673133 0.155619 1.1451 0.414376 1.46855L4.03956 6.00003L0.414376 10.5315C0.155618 10.855 0.208059 11.3269 0.531506 11.5857C0.854952 11.8444 1.32692 11.792 1.58568 11.4685L5.58568 6.46855C5.80481 6.19464 5.80481 5.80542 5.58568 5.53151L1.58568 0.531506C1.32692 0.20806 0.854953 0.155619 0.531506 0.414376Z"/>
        </svg>
      </span>
    </div>
  </button>

  {{-- SUBMENU --}}
  <ul id="productos-group"
      class="mt-2 hidden space-y-1 pl-10 text-bgray-700 dark:text-bgray-50">

    <li>
      <a href="{{ route('purchases.index') }}"
         class="block py-2 hover:text-success-300">
        Lista de Productos
      </a>
    </li>

    <li>
      <a href="{{ route('registro-producto.create') }}"
         class="block py-2 hover:text-success-300">
        Agregar Producto
      </a>
    </li>

    <li>
      <a href="{{ route('products.sold') }}"
         class="block py-2 text-bgray-500 hover:text-success-300">
        Productos Vendidos
      </a>
    </li>
  </ul>
</li>

        </div>
      </div>
    </aside>

    {{-- SIDEBAR COLLAPSED --}}
    <aside class="relative hidden w-[96px] bg-white dark:bg-black sm:block">
      <div class="sidebar-header flex h-[108px] items-center justify-center border-b dark:border-darkblack-500">
        <a href="{{ route('purchases.index') }}">
          <img src="{{ asset('assets/images/logo/logo-short.svg') }}" class="block dark:hidden" />
          <img src="{{ asset('assets/images/logo/logo-short-white.svg') }}" class="hidden dark:block" />
        </a>
      </div>

      <ul class="mt-6 flex flex-col items-center gap-6">
        <li>
          <a href="{{ route('purchases.index') }}">
            <img src="{{ asset('assets/images/icons/list.svg') }}" alt="Lista" />
          </a>
        </li>
        <li>
          <a href="{{ route('registro-producto.create') }}">
            <img src="{{ asset('assets/images/icons/plus.svg') }}" alt="Agregar" />
          </a>
        </li>
      </ul>
    </aside>

    {{-- BODY --}}
    <div class="body-wrapper flex-1 overflow-x-hidden dark:bg-darkblack-500">

      {{-- HEADER --}}
      <header class="header-wrapper fixed z-30 hidden w-full md:block">
        <div class="flex h-[108px] items-center bg-white px-10 dark:bg-darkblack-600">
          <h3 class="text-xl font-bold dark:text-white">
            @yield('header', 'Dashboard')
          </h3>
        </div>
      </header>

      {{-- MAIN --}}
      <main class="w-full px-6 pb-6 pt-[100px] sm:pt-[156px] xl:px-12 xl:pb-12">
        @if(session('ok'))
          <div class="mb-4 rounded-lg bg-success-50 p-4 text-success-700">
            {{ session('ok') }}
          </div>
        @endif

        @yield('content')
      </main>

    </div>
  </div>
</div>

{{-- JS --}}
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/chart.js') }}"></script>
<script>AOS.init();</script>



<script>
  function toggleSidebarGroup(groupId, arrowId) {
    const group = document.getElementById(groupId);
    const arrow = document.getElementById(arrowId);
    if (!group) return;

    const isHidden = group.classList.contains('hidden');
    if (isHidden) {
      group.classList.remove('hidden');
      if (arrow) arrow.style.transform = 'rotate(90deg)';
    } else {
      group.classList.add('hidden');
      if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
  }

  // Auto-expand si estás en /compras o /registro-producto o /productos-vendidos
  (function () {
    const p = window.location.pathname;
    const shouldOpen =
      p.startsWith('/compras') ||
      p.startsWith('/registro-producto') ||
      p.startsWith('/productos-vendidos');

    if (shouldOpen) {
      const group = document.getElementById('productos-group');
      const arrow = document.getElementById('productos-arrow');
      if (group) group.classList.remove('hidden');
      if (arrow) arrow.style.transform = 'rotate(90deg)';
    }
  })();
</script>

@yield('scripts')
</body>
</html>