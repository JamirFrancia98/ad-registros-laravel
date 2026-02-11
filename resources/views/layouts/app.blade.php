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
    <aside class="sidebar-wrapper fixed top-0 z-30 block h-full w-[308px] bg-gray-400 dark:bg-darkblack-600 sm:hidden xl:block">
      <div class="sidebar-header relative z-30 flex h-[108px] items-center border-b pl-[50px] dark:border-darkblack-400">
        <a href="{{ route('purchases.index') }}">
          <img src="{{ asset('assets/images/logo/logo-color.svg') }}" class="block dark:hidden" />
          <img src="{{ asset('assets/images/logo/logo-white.svg') }}" class="hidden dark:block" />
        </a>
      </div>

      <div class="sidebar-body h-screen overflow-y-scroll pb-[200px] pl-[48px] pt-[14px]">
        <div class="nav-wrapper pr-[50px]">

          <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
            Menu
          </h4>

          {{-- ✅ IMPORTANTE: aquí SI debe ir un <ul> --}}
          <ul class="mt-2.5 space-y-1">

            {{-- =========================
              PRODUCTOS (COLLAPSIBLE)
            ========================== --}}
            <li class="item py-[11px] text-bgray-900 dark:text-white">

              <button type="button"
                class="w-full"
                onclick="toggleSidebarGroup('productos-group','productos-arrow')"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2.5">
                    <span class="item-ico">
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

              <ul id="productos-group"
                  class="mt-2 hidden space-y-1 pl-10 text-bgray-700 dark:text-bgray-50">
                <li>
                  <a href="{{ route('purchases.index') }}" class="block py-2 hover:text-success-300">
                    Lista de Productos
                  </a>
                </li>
                <li>
                  <a href="{{ route('registro-producto.create') }}" class="block py-2 hover:text-success-300">
                    Agregar Producto
                  </a>
                </li>
                <li>
                  <a href="#" class="block py-2 text-bgray-500 hover:text-success-300">
                    Productos Vendidos (luego)
                  </a>
                </li>
              </ul>
            </li>

            {{-- =========================
              VENTAS (COLLAPSIBLE)
            ========================== --}}
            <li class="item py-[11px] text-bgray-900 dark:text-white">

              <button type="button"
                class="w-full"
                onclick="toggleSidebarGroup('ventas-group','ventas-arrow')"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2.5">
                    <span class="item-text text-lg font-medium leading-none">
                      Ventas
                    </span>
                  </div>

                  <span id="ventas-arrow" class="transition-transform duration-200">
                    <svg width="6" height="12" viewBox="0 0 6 12" fill="none" class="fill-current" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                        d="M0.531506 0.414376C0.20806 0.673133 0.155619 1.1451 0.414376 1.46855L4.03956 6.00003L0.414376 10.5315C0.155618 10.855 0.208059 11.3269 0.531506 11.5857C0.854952 11.8444 1.32692 11.792 1.58568 11.4685L5.58568 6.46855C5.80481 6.19464 5.80481 5.80542 5.58568 5.53151L1.58568 0.531506C1.32692 0.20806 0.854953 0.155619 0.531506 0.414376Z"/>
                    </svg>
                  </span>
                </div>
              </button>

              <ul id="ventas-group"
                  class="mt-2 hidden space-y-1 pl-10 text-bgray-700 dark:text-bgray-50">
                <li>
                  <a href="{{ route('sales.index') }}" class="block py-2 hover:text-success-300">
                    Lista de Ventas
                  </a>
                </li>
                <li>
                  <a href="{{ route('sales.create') }}" class="block py-2 hover:text-success-300">
                    Registrar Venta
                  </a>
                </li>
                <li>
                  <a href="#" class="block py-2 text-bgray-500 hover:text-success-300">
                    Análisis (luego)
                  </a>
                </li>
              </ul>
            </li>
<a href="{{ route('analytics.index') }}">Análisis</a>
          </ul>
        </div>
      </div>
    </aside>

    {{-- SIDEBAR COLLAPSED --}}
    <aside class="relative hidden w-[96px] bg-gray-400 dark:bg-black sm:block">
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
        <li>
          <a href="{{ route('sales.index') }}">
            <span class="text-xs font-bold">V</span>
          </a>
        </li>
      </ul>
    </aside>

    {{-- BODY --}}
    <div class="body-wrapper flex-1 overflow-x-hidden dark:bg-darkblack-400">

      {{-- HEADER --}}
      {{-- HEADER (DESKTOP) --}}
    <header class="header-wrapper fixed z-30 hidden w-full md:block">
      <div class="relative flex h-[108px] w-full items-center justify-between bg-gray-400 px-10 dark:bg-darkblack-600 2xl:px-[76px]">
        <!-- LEFT: título -->
        <div class="flex flex-col">
          <h3 class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]">
            @yield('header', 'Dashboard')
          </h3>
          <span class="text-sm font-medium text-bgray-500 dark:text-bgray-200">
            Let's check your update today
          </span>
        </div>

        <!-- CENTER: buscador -->
       

        <!-- RIGHT: acciones -->
        <div class="flex items-center gap-3">
          <!-- Theme toggle -->
          <button id="themeToggleBtn" type="button"
            class="flex h-12 w-12 items-center justify-center rounded-xl border border-bgray-200 bg-gray-400 text-bgray-700 hover:border-success-300 dark:border-darkblack-400 dark:bg-darkblack-600 dark:text-white"
            aria-label="Cambiar tema">
            <span id="themeIconSun" class="hidden">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" stroke="currentColor" stroke-width="2"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </span>
            <span id="themeIconMoon" class="hidden">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </button>

          <!-- Notifications -->
          <div class="relative">
            <button type="button" data-popup-btn="notifPopup"
              class="relative flex h-12 w-12 items-center justify-center rounded-xl border border-bgray-200 bg-gray-400 text-bgray-700 hover:border-success-300 dark:border-darkblack-400 dark:bg-darkblack-600 dark:text-white"
              aria-label="Notificaciones">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
            </button>

            <!-- Dropdown -->
            <div id="notifPopup" class="absolute right-0 mt-3 hidden w-[360px] rounded-2xl border border-bgray-200 bg-gray-400 p-4 shadow-lg dark:border-darkblack-400 dark:bg-darkblack-600">
              <div class="mb-3 flex items-center justify-between">
                <h4 class="text-lg font-bold text-bgray-900 dark:text-white">Notificaciones</h4>
                <button type="button" class="text-bgray-500 hover:text-success-300 dark:text-bgray-200" data-popup-close>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>

              <div class="space-y-3">
                <!-- Placeholder (luego lo llenamos con ventas reales) -->
                <div class="rounded-xl bg-bgray-50 p-4 dark:bg-darkblack-400">
                  <p class="text-sm text-bgray-900 dark:text-white">
                    <span class="font-semibold">Super Admin</span> — Notificaciones en construcción.
                  </p>
                  <p class="mt-2 text-xs text-bgray-500 dark:text-bgray-200">Hace 1 min</p>
                </div>
              </div>
            </div>
          </div>

          <!-- User menu -->
          <div class="relative">
            <button type="button" data-popup-btn="userPopup"
              class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-2xl border border-bgray-200 bg-bgray-100 dark:border-darkblack-400 dark:bg-darkblack-400"
              aria-label="Menú de usuario">
              <!-- Placeholder avatar -->
              <span class="flex h-full w-full items-center justify-center text-xs font-bold text-bgray-700 dark:text-white">52x52</span>
            </button>

            <div id="userPopup" class="absolute right-0 mt-3 hidden w-[260px] rounded-2xl border border-bgray-200 bg-gray-400 p-4 shadow-lg dark:border-darkblack-400 dark:bg-darkblack-600">
              <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-bgray-900 hover:bg-bgray-50 dark:text-white dark:hover:bg-darkblack-400">
                <span class="text-lg">👤</span>
                <span class="font-semibold">My Profile</span>
              </a>
              <a href="#" class="mt-2 flex items-center gap-3 rounded-xl px-3 py-3 text-bgray-900 hover:bg-bgray-50 dark:text-white dark:hover:bg-darkblack-400">
                <span class="text-lg">✉️</span>
                <span class="font-semibold">Inbox</span>
              </a>
              <form method="POST" action="#" class="mt-2">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-success-300 hover:bg-bgray-50 dark:hover:bg-darkblack-400">
                  <span class="text-lg">⎋</span>
                  <span class="font-semibold">Log Out</span>
                </button>
              </form>

              <div class="my-4 h-px bg-bgray-200 dark:bg-darkblack-400"></div>

              <p class="mb-2 text-sm font-semibold text-bgray-500 dark:text-bgray-200">Settings</p>
              <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-bgray-900 hover:bg-bgray-50 dark:text-white dark:hover:bg-darkblack-400">
                <span class="font-semibold">Users</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

      {{-- HEADER (MOBILE) --}}
    <header class="fixed z-30 block w-full md:hidden">
      <div class="flex items-center justify-between bg-gray-400 px-4 py-4 dark:bg-darkblack-600">
        <!-- LEFT -->
        <div class="flex flex-col">
          <h3 class="text-lg font-bold text-bgray-900 dark:text-white">
            @yield('header', 'Dashboard')
          </h3>
          <span class="text-xs font-medium text-bgray-500 dark:text-bgray-200">
            Let's check your update today
          </span>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-2">
          <button id="themeToggleBtnMobile" type="button"
            class="flex h-11 w-11 items-center justify-center rounded-xl border border-bgray-200 bg-gray-400 text-bgray-700 dark:border-darkblack-400 dark:bg-darkblack-600 dark:text-white"
            aria-label="Cambiar tema">
            <span id="themeIconSunMobile" class="hidden">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" stroke="currentColor" stroke-width="2"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </span>
            <span id="themeIconMoonMobile" class="hidden">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </button>

          <div class="relative">
            <button type="button" data-popup-btn="notifPopupMobile"
              class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-bgray-200 bg-gray-400 text-bgray-700 dark:border-darkblack-400 dark:bg-darkblack-600 dark:text-white"
              aria-label="Notificaciones">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
            </button>

            <div id="notifPopupMobile" class="absolute right-0 mt-3 hidden w-[320px] max-w-[90vw] rounded-2xl border border-bgray-200 bg-gray-400 p-4 shadow-lg dark:border-darkblack-400 dark:bg-darkblack-600">
              <div class="mb-3 flex items-center justify-between">
                <h4 class="text-lg font-bold text-bgray-900 dark:text-white">Notificaciones</h4>
                <button type="button" class="text-bgray-500 hover:text-success-300 dark:text-bgray-200" data-popup-close>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
              <div class="space-y-3">
                <div class="rounded-xl bg-bgray-50 p-4 dark:bg-darkblack-400">
                  <p class="text-sm text-bgray-900 dark:text-white">
                    <span class="font-semibold">Super Admin</span> — Notificaciones en construcción.
                  </p>
                  <p class="mt-2 text-xs text-bgray-500 dark:text-bgray-200">Hace 1 min</p>
                </div>
              </div>
            </div>
          </div>

          <div class="relative">
            <button type="button" data-popup-btn="userPopupMobile"
              class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl border border-bgray-200 bg-bgray-100 dark:border-darkblack-400 dark:bg-darkblack-400"
              aria-label="Menú de usuario">
              <span class="flex h-full w-full items-center justify-center text-[10px] font-bold text-bgray-700 dark:text-white">52x52</span>
            </button>

            <div id="userPopupMobile" class="absolute right-0 mt-3 hidden w-[260px] max-w-[90vw] rounded-2xl border border-bgray-200 bg-gray-400 p-4 shadow-lg dark:border-darkblack-400 dark:bg-darkblack-600">
              <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-bgray-900 hover:bg-bgray-50 dark:text-white dark:hover:bg-darkblack-400">
                <span class="text-lg">👤</span>
                <span class="font-semibold">My Profile</span>
              </a>
              <a href="#" class="mt-2 flex items-center gap-3 rounded-xl px-3 py-3 text-bgray-900 hover:bg-bgray-50 dark:text-white dark:hover:bg-darkblack-400">
                <span class="text-lg">✉️</span>
                <span class="font-semibold">Inbox</span>
              </a>
              <form method="POST" action="#" class="mt-2">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-success-300 hover:bg-bgray-50 dark:hover:bg-darkblack-400">
                  <span class="text-lg">⎋</span>
                  <span class="font-semibold">Log Out</span>
                </button>
              </form>

              <div class="my-4 h-px bg-bgray-200 dark:bg-darkblack-400"></div>

              <p class="mb-2 text-sm font-semibold text-bgray-500 dark:text-bgray-200">Settings</p>
              <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-bgray-900 hover:bg-bgray-50 dark:text-white dark:hover:bg-darkblack-400">
                <span class="font-semibold">Users</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

      {{-- MAIN (fix padding-top) --}}
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
  // ============================
  // 1) Tema (día/noche)
  //    - Guardamos preferencia en localStorage
  //    - Tailwind usa 'dark' en <html> para activar estilos dark:
  // ============================
  (function initTheme(){
    const saved = localStorage.getItem('theme'); // 'dark' | 'light' | null
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = saved ? (saved === 'dark') : prefersDark;

    setTheme(isDark);

    const desktopBtn = document.getElementById('themeToggleBtn');
    const mobileBtn  = document.getElementById('themeToggleBtnMobile');

    function onToggle(){
      const nowDark = !document.documentElement.classList.contains('dark');
      setTheme(nowDark);
      localStorage.setItem('theme', nowDark ? 'dark' : 'light');
    }

    if (desktopBtn) desktopBtn.addEventListener('click', onToggle);
    if (mobileBtn)  mobileBtn.addEventListener('click', onToggle);
  })();

  function setTheme(isDark){
    document.documentElement.classList.toggle('dark', isDark);
    syncThemeIcons(isDark);
  }

  function syncThemeIcons(isDark){
    const ids = [
      ['themeIconSun','themeIconMoon'],
      ['themeIconSunMobile','themeIconMoonMobile'],
    ];
    ids.forEach(([sunId, moonId]) => {
      const sun = document.getElementById(sunId);
      const moon = document.getElementById(moonId);
      if (!sun || !moon) return;

      // Si está en dark => mostramos "sol" (porque al hacer click cambiará a día)
      if (isDark) {
        sun.classList.remove('hidden');
        moon.classList.add('hidden');
      } else {
        moon.classList.remove('hidden');
        sun.classList.add('hidden');
      }
    });
  }

  // ============================
  // 2) Popups (usuario / notificaciones)
  //    Evitamos togglePopover() (HTML Popover API) para que funcione en todos los navegadores.
  // ============================
  const POPUP_IDS = ['notifPopup','userPopup','notifPopupMobile','userPopupMobile'];

  function closeAllPopups() {
    POPUP_IDS.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.classList.add('hidden');
    });
  }

  function togglePopup(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const isHidden = el.classList.contains('hidden');
    closeAllPopups();
    if (isHidden) el.classList.remove('hidden');
  }

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-popup-btn]');
    const closeBtn = e.target.closest('[data-popup-close]');

    if (closeBtn) {
      closeAllPopups();
      return;
    }

    if (btn) {
      const targetId = btn.getAttribute('data-popup-btn');
      togglePopup(targetId);
      return;
    }

    // Click fuera => cerrar
    const insidePopup = e.target.closest('#notifPopup, #userPopup, #notifPopupMobile, #userPopupMobile');
    if (!insidePopup) closeAllPopups();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAllPopups();
  });
</script>


<script>
  function toggleSidebarGroup(groupId, arrowId) {
    const group = document.getElementById(groupId);
    const arrow = document.getElementById(arrowId);
    if (!group) return;

    const isHidden = group.classList.contains('hidden');
    group.classList.toggle('hidden');

    if (arrow) {
      arrow.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
    }
  }

  // Auto-expand según ruta
  (function () {
    const p = window.location.pathname;

    const openProductos = p.startsWith('/compras') || p.startsWith('/registro-producto') || p.startsWith('/productos');
    if (openProductos) {
      const g = document.getElementById('productos-group');
      const a = document.getElementById('productos-arrow');
      if (g) g.classList.remove('hidden');
      if (a) a.style.transform = 'rotate(90deg)';
    }

    const openVentas = p.startsWith('/ventas');
    if (openVentas) {
      const g = document.getElementById('ventas-group');
      const a = document.getElementById('ventas-arrow');
      if (g) g.classList.remove('hidden');
      if (a) a.style.transform = 'rotate(90deg)';
    }
  })();
</script>



<script>
  // Tema oscuro/claro: usa la clase "dark" en <html>
  function toggleTheme(){
    const root = document.documentElement;
    const isDark = root.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  }

  // Aplica el tema guardado al cargar
  (function(){
    const saved = localStorage.getItem('theme');
    if(saved === 'dark'){
      document.documentElement.classList.add('dark');
    }
  })();
</script>


@yield('scripts')
</body>
</html> 