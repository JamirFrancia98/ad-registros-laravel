<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Notification;


class SaleController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $sales = Sale::query()
            ->with([
                'customer',
                'purchase.supplier',
                'purchase.iphoneModel',
                'purchase.storageOption'
            ])
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->whereHas('purchase', function ($p) use ($q) {
                        $p->where('imei1', 'like', "%{$q}%")
                          ->orWhere('imei2', 'like', "%{$q}%")
                          ->orWhere('serial', 'like', "%{$q}%");
                    })
                    ->orWhereHas('customer', function ($c) use ($q) {
                        $c->where('first_name', 'like', "%{$q}%")
                          ->orWhere('last_name', 'like', "%{$q}%")
                          ->orWhere('dni', 'like', "%{$q}%")
                          ->orWhere('phone', 'like', "%{$q}%");
                    });
                });
            })
            ->orderByDesc('sold_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('sales.index', compact('sales', 'q'));
    }

    public function create()
    {
        // Solo iPhones NO vendidos
        $products = Purchase::with(['supplier', 'iphoneModel', 'storageOption'])
            ->whereDoesntHave('sale')
            ->orderByDesc('purchase_date')
            ->paginate(15);

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Cliente
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'dni'        => ['required', 'digits:8'],
            'email'      => ['nullable', 'email', 'max:120'],
            'phone'      => ['required', 'string', 'max:30'],
            'operator'   => ['nullable', 'string', 'max:30'],

            // Venta
            'purchase_id' => ['required', 'exists:purchases,id', 'unique:sales,purchase_id'],
            'sold_at'     => ['required', 'date'],
            'sold_price'  => ['required', 'numeric', 'min:0'],

            // Accesorios (opcionales)
            'items'         => ['nullable', 'array'],
            'items.*.name'  => ['nullable', 'string', 'max:120'],
            'items.*.qty'   => ['nullable', 'integer', 'min:1', 'max:50'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($data) {

            // 1) Cliente: se crea o se reutiliza por DNI (dni es UNIQUE)
            $customer = Customer::where('dni', $data['dni'])->first();
            if (!$customer) {
                $customer = new Customer();
                $customer->dni = $data['dni']; // obligatorio
            }

            // Actualizamos datos “suaves” (si el cliente ya existía, se refresca info)
            $customer->first_name = $data['first_name'];
            $customer->last_name  = $data['last_name'];
            $customer->email      = $data['email'] ?? $customer->email;
            $customer->phone      = $data['phone'];
            $customer->operator   = $data['operator'] ?? $customer->operator;
            $customer->save();

            // 2) Venta: se crea con el customer_id (sin mass assignment)
            $sale = new Sale();
            $sale->purchase_id = $data['purchase_id'];
            $sale->customer_id = $customer->id;
            $sale->sold_at     = $data['sold_at'];
            $sale->sold_price  = $data['sold_price'];
            $sale->total_items = 0;
            $sale->grand_total = 0;
            $sale->save();

            // 3) Accesorios (si vienen)
            $totalItems = 0;

            if (!empty($data['items'])) {
                foreach ($data['items'] as $it) {
                    $name  = trim((string)($it['name'] ?? ''));
                    $qty   = (int)($it['qty'] ?? 1);
                    $price = (float)($it['price'] ?? 0);

                    // Ignorar filas vacías o sin precio
                    if ($name === '' || $price <= 0) continue;

                    $item = new SaleItem();
                    $item->sale_id = $sale->id;
                    $item->name = $name;
                    $item->qty = $qty;
                    $item->price = $price;
                    $item->save();

                    $totalItems += ($qty * $price);
                }
            }

            // 4) Totales finales
            $sale->total_items = $totalItems;
            $sale->grand_total = ((float)$sale->sold_price) + $totalItems;
            $sale->save();

            // 5) ✅ Notificación (para el SUPER ADMIN en el header)
            //    - Mensaje: "Vendedor X vendió ..." lo veremos luego cuando haya login/roles.
            $purchase = Purchase::with(['iphoneModel','storageOption'])->find($sale->purchase_id);

            $modelName = $purchase?->iphoneModel?->name ?? 'iPhone';
            $storage   = $purchase?->storageOption?->label ?? '';
            $fullName  = trim($customer->first_name . ' ' . $customer->last_name);

            Notification::create([
                'title'   => 'Nueva venta',
                'message' => "{$fullName} compró {$modelName} {$storage} por S/ " . number_format((float)$sale->sold_price, 0),
                'data'    => [
                    'sale_id'     => $sale->id,
                    'purchase_id' => $sale->purchase_id,
                    'customer_id' => $customer->id,
                ],
                'read_at' => null,
            ]);

            return redirect()->route('sales.index')->with('ok', 'Venta registrada');
        });
    }

    public function edit($id)
    {
        $sale = Sale::with([
                'customer',
                'items',
                'purchase.supplier',
                'purchase.iphoneModel',
                'purchase.storageOption',
                'purchase.color',
            ])
            ->find($id);

        if (!$sale) {
            return redirect()->route('sales.index')->with('ok', 'Venta no encontrada');
        }

        // Accesorios ya existentes
        $items = $sale->items ?? collect();

        // Para mostrar resumen del iPhone vendido (no editable por ahora)
        $purchase = $sale->purchase;

        return view('sales.edit', compact('sale', 'items', 'purchase'));
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::with(['customer', 'items'])->find($id);

        if (!$sale) {
            return redirect()->route('sales.index')->with('ok', 'Venta no encontrada');
        }

        $data = $request->validate([
            // Cliente
            'first_name' => ['required','string','max:80'],
            'last_name'  => ['required','string','max:80'],
            'dni'        => ['required','digits:8'],
            'email'      => ['nullable','email','max:120'],
            'phone'      => ['required','string','max:30'],
            'operator'   => ['nullable','string','max:30'],

            // Venta
            'sold_at'     => ['required','date'],
            'sold_price'  => ['required','numeric','min:0'],

            // Accesorios (arrays)
            'items' => ['nullable','array'],
            'items.*.name'  => ['nullable','string','max:120'],
            'items.*.qty'   => ['nullable','integer','min:1','max:50'],
            'items.*.price' => ['nullable','numeric','min:0'],
        ]);

        return DB::transaction(function () use ($sale, $data) {

            // 1) Actualizar cliente (en este momento lo dejamos “dentro de la venta”)
            //    Si luego quieres “cliente único por DNI global”, lo hacemos después.
            $customer = $sale->customer;
            if (!$customer) {
                $customer = new Customer();
            }

            $customer->dni        = $data['dni'];
            $customer->first_name = $data['first_name'];
            $customer->last_name  = $data['last_name'];
            $customer->email      = $data['email'] ?? null;
            $customer->phone      = $data['phone'];
            $customer->operator   = $data['operator'] ?? null;
            $customer->save();

            // Garantiza relación
            $sale->customer_id = $customer->id;

            // 2) Actualizar venta
            $sale->sold_at    = $data['sold_at'];
            $sale->sold_price = $data['sold_price'];

            // 3) Recalcular accesorios: estrategia simple y segura => borrar y recrear
            SaleItem::where('sale_id', $sale->id)->delete();

            $totalItems = 0;

            if (!empty($data['items'])) {
                foreach ($data['items'] as $it) {
                    $name  = trim((string)($it['name'] ?? ''));
                    $qty   = (int)($it['qty'] ?? 1);
                    $price = (float)($it['price'] ?? 0);

                    // Ignorar filas vacías
                    if ($name === '' || $price <= 0) continue;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'name' => $name,
                        'qty' => $qty,
                        'price' => $price,
                    ]);

                    $totalItems += ($qty * $price);
                }
            }

            $sale->total_items = $totalItems;
            $sale->grand_total = ((float)$sale->sold_price) + $totalItems;
            $sale->save();

            return redirect()->route('sales.index')->with('ok', 'Venta actualizada');
        });
    }

    public function analysis(Request $request)
    {
        $days = (int) $request->query('days', 30);
        if (!in_array($days, [7, 30, 90])) $days = 30;

        $from = now()->subDays($days)->toDateString();

        // Totales (ventas)
        $totalSales = (float) Sale::whereDate('sold_at', '>=', $from)->sum('sold_price');
        $unitsSold  = (int)   Sale::whereDate('sold_at', '>=', $from)->count();

        // Costo total (sumando purchase_price de los purchases vendidos en ese rango)
        $totalCost = (float) Sale::whereDate('sold_at', '>=', $from)
            ->whereHas('purchase')
            ->with('purchase:id,purchase_price')
            ->get()
            ->sum(fn($s) => (float)($s->purchase->purchase_price ?? 0));

        $profit = $totalSales - $totalCost;
        $avgTicket = $unitsSold > 0 ? ($totalSales / $unitsSold) : 0;

        // Top modelos vendidos
        $topModels = Sale::query()
            ->whereDate('sold_at', '>=', $from)
            ->join('purchases as p', 'p.id', '=', 'sales.purchase_id')
            ->join('iphone_models as m', 'm.id', '=', 'p.iphone_model_id')
            ->selectRaw('m.name as model_name, COUNT(*) as qty, SUM(sales.sold_price) as total')
            ->groupBy('m.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // Ventas por día (para gráfico)
        $daily = Sale::query()
            ->whereDate('sold_at', '>=', $from)
            ->selectRaw('DATE(sold_at) as d, COUNT(*) as qty, SUM(sold_price) as total')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $labels = $daily->pluck('d');
        $dailyTotals = $daily->pluck('total')->map(fn($v) => (float)$v);
        $dailyQty = $daily->pluck('qty')->map(fn($v) => (int)$v);

        return view('sales.analysis', compact(
            'days', 'from',
            'totalSales', 'totalCost', 'profit', 'unitsSold', 'avgTicket',
            'topModels',
            'labels', 'dailyTotals', 'dailyQty'
        ));
    }

    // BORRAR (real)
    public function destroy($id)
    {
        \App\Models\Sale::where('id', $id)->delete();

        return redirect()->route('sales.index')
            ->with('ok', 'Venta eliminada');
    }



}