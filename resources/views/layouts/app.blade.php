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

    {{-- CSS del template --}}
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/output.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
  </head>

  <body>
    <!-- layout start -->
    <div class="layout-wrapper active w-full">
      <div class="relative flex w-full">

        {{-- SIDEBAR (FULL) --}}
        <aside
          class="sidebar-wrapper fixed top-0 z-30 block h-full w-[308px] bg-white dark:bg-darkblack-600 sm:hidden xl:block"
        >
          <div
            class="sidebar-header relative z-30 flex h-[108px] w-full items-center border-b border-r border-b-[#F7F7F7] border-r-[#F7F7F7] pl-[50px] dark:border-darkblack-400"
          >
            <a href="{{ route('purchases.index') }}">
              <img
                src="{{ asset('assets/images/logo/logo-color.svg') }}"
                class="block dark:hidden"
                alt="logo"
              />
              <img
                src="{{ asset('assets/images/logo/logo-white.svg') }}"
                class="hidden dark:block"
                alt="logo"
              />
            </a>

            <button
              type="button"
              class="drawer-btn absolute right-0 top-auto"
              title="Ctrl+b"
            >
              <span>
                <svg
                  width="16"
                  height="40"
                  viewBox="0 0 16 40"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M0 10C0 4.47715 4.47715 0 10 0H16V40H10C4.47715 40 0 35.5228 0 30V10Z"
                    fill="#22C55E"
                  />
                  <path
                    d="M10 15L6 20.0049L10 25.0098"
                    stroke="#ffffff"
                    stroke-width="1.2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </span>
            </button>
          </div>

          <div
            class="sidebar-body overflow-style-none relative z-30 h-screen w-full overflow-y-scroll pb-[200px] pl-[48px] pt-[14px]"
          >
            <div class="nav-wrapper mb-[36px] pr-[50px]">

              <div class="item-wrapper mb-5">
                <h4
                  class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50"
                >
                  Menu
                </h4>

                <ul class="mt-2.5">
                  {{-- REGISTRO PRODUCTO --}}
                  <li class="item py-[11px] text-bgray-900 dark:text-white">
                    <a href="{{ route('registro-producto.create') }}">
                      <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2.5">
                          <span class="item-ico">
                            <svg width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path class="path-1" d="M0 8.84719C0 7.99027 0.366443 7.17426 1.00691 6.60496L6.34255 1.86217C7.85809 0.515019 10.1419 0.515019 11.6575 1.86217L16.9931 6.60496C17.6336 7.17426 18 7.99027 18 8.84719V17C18 19.2091 16.2091 21 14 21H4C1.79086 21 0 19.2091 0 17V8.84719Z" fill="#1A202C"/>
                              <path class="path-2" d="M5 17C5 14.7909 6.79086 13 9 13C11.2091 13 13 14.7909 13 17V21H5V17Z" fill="#22C55E"/>
                            </svg>
                          </span>

                          <span class="item-text text-lg font-medium leading-none">
                            Registro Producto
                          </span>
                        </div>

                        <span>
                          <svg width="6" height="12" viewBox="0 0 6 12" fill="none" class="fill-current" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                              d="M0.531506 0.414376C0.20806 0.673133 0.155619 1.1451 0.414376 1.46855L4.03956 6.00003L0.414376 10.5315C0.155618 10.855 0.208059 11.3269 0.531506 11.5857C0.854952 11.8444 1.32692 11.792 1.58568 11.4685L5.58568 6.46855C5.80481 6.19464 5.80481 5.80542 5.58568 5.53151L1.58568 0.531506C1.32692 0.20806 0.854953 0.155619 0.531506 0.414376Z"/>
                          </svg>
                        </span>
                      </div>
                    </a>
                  </li>

                  {{-- LISTADO COMPRAS --}}
                  <li class="item py-[11px] text-bgray-900 dark:text-white">
                    <a href="{{ route('purchases.index') }}">
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
                            Listado Compras
                          </span>
                        </div>

                        <span>
                          <svg width="6" height="12" viewBox="0 0 6 12" fill="none" class="fill-current" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                              d="M0.531506 0.414376C0.20806 0.673133 0.155619 1.1451 0.414376 1.46855L4.03956 6.00003L0.414376 10.5315C0.155618 10.855 0.208059 11.3269 0.531506 11.5857C0.854952 11.8444 1.32692 11.792 1.58568 11.4685L5.58568 6.46855C5.80481 6.19464 5.80481 5.80542 5.58568 5.53151L1.58568 0.531506C1.32692 0.20806 0.854953 0.155619 0.531506 0.414376Z"/>
                          </svg>
                        </span>
                      </div>
                    </a>
                  </li>
                </ul>
              </div>

            </div>
          </div>
        </aside>

        {{-- SIDEBAR (COLLAPSED) --}}
        <aside class="relative hidden w-[96px] bg-white dark:bg-black sm:block">
          <div class="sidebar-wrapper-collapse relative top-0 z-30 w-full">
            <div
              class="sidebar-header sticky top-0 z-20 flex h-[108px] w-full items-center justify-center border-b border-r border-b-[#F7F7F7] border-r-[#F7F7F7] bg-white dark:border-darkblack-500 dark:bg-darkblack-600"
            >
              <a href="{{ route('purchases.index') }}">
                <img src="{{ asset('assets/images/logo/logo-short.svg') }}" class="block dark:hidden" alt="logo" />
                <img src="{{ asset('assets/images/logo/logo-short-white.svg') }}" class="hidden dark:block" alt="logo" />
              </a>
            </div>

            <div class="sidebar-body w-full pt-[14px]">
              <div class="flex flex-col items-center">
                <div class="nav-wrapper mb-[36px]">
                  <ul class="mt-2.5 flex flex-col items-center justify-center">
                    <li class="item px-[43px] py-[11px]">
                      <a href="{{ route('registro-producto.create') }}">
                        <span class="item-ico">
                          <img src="{{ asset('assets/images/icons/plus.svg') }}" alt="+" onerror="this.style.display='none';" />
                        </span>
                      </a>
                    </li>
                    <li class="item px-[43px] py-[11px]">
                      <a href="{{ route('purchases.index') }}">
                        <span class="item-ico">
                          <img src="{{ asset('assets/images/icons/list.svg') }}" alt="list" onerror="this.style.display='none';" />
                        </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </aside>

        {{-- BODY --}}
        <div class="body-wrapper flex-1 overflow-x-hidden dark:bg-darkblack-500">

          {{-- HEADER --}}
          <header class="header-wrapper fixed z-30 hidden w-full md:block">
            <div
              class="relative flex h-[108px] w-full items-center justify-between bg-white px-10 dark:bg-darkblack-600 2xl:px-[76px]"
            >
              <button
                title="Ctrl+b"
                type="button"
                class="drawer-btn absolute left-0 top-auto rotate-180 transform"
              >
                <span>
                  <svg width="16" height="40" viewBox="0 0 16 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 10C0 4.47715 4.47715 0 10 0H16V40H10C4.47715 40 0 35.5228 0 30V10Z" fill="#22C55E"/>
                    <path d="M10 15L6 20.0049L10 25.0098" stroke="#ffffff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
              </button>

              <div>
                <h3 class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]">
                  @yield('header', 'Dashboard')
                </h3>
              </div>
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
    <!-- layout end -->

    {{-- scripts del template --}}
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script> AOS.init(); </script>
    <script src="{{ asset('assets/js/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>

    @yield('scripts')
  </body>
</html>