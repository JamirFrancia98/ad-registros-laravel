<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Para manejo de archivos

class ProductRegisterController extends Controller
{
    public function create()
    {
        $suppliers = Supplier::query()->orderBy('name')->get();
        $models = DB::table('iphone_models')->orderBy('name')->get();
        $storages = DB::table('storage_options')->orderBy('gb')->get();

        return view('registro-producto', compact('suppliers','models','storages'));
    }

    public function colors($model)
    {
        $colors = DB::table('colors')
            ->where('iphone_model_id', $model)
            ->orderBy('name')
            ->get(['id','name']);

        return response()->json($colors);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purchase_date' => ['required','date'],
            'supplier_id' => ['required','exists:suppliers,id'],
            'iphone_model_id' => ['required','exists:iphone_models,id'],
            'storage_option_id' => ['required','exists:storage_options,id'],
            'color_id' => ['required','exists:colors,id'],

            'imei1' => ['required','string','max:20','unique:purchases,imei1'],
            'imei2' => ['nullable','string','max:20','unique:purchases,imei2'],
            'serial' => ['nullable','string','max:50','unique:purchases,serial'],

            'purchase_price' => ['required','numeric','min:0'],
            'sale_price' => ['required','numeric','min:0'],
            'markup' => ['nullable','integer','in:150,200,250,300'],

            // Fotos (opcionales)
            'imei_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],  // 5MB
            'phone_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $imeiPhotoPath = null;
        $phonePhotoPath = null;

        // Guardado de fotos en storage/app/public/purchases
        if ($request->hasFile('imei_photo')) {
            $imeiPhotoPath = $request->file('imei_photo')->store('purchases/imei', 'public');
        }

        if ($request->hasFile('phone_photo')) {
            $phonePhotoPath = $request->file('phone_photo')->store('purchases/phone', 'public');
        }

        DB::table('purchases')->insert([
            'purchase_date' => $data['purchase_date'],
            'supplier_id' => $data['supplier_id'],
            'iphone_model_id' => $data['iphone_model_id'],
            'storage_option_id' => $data['storage_option_id'],
            'color_id' => $data['color_id'],

            'imei1' => $data['imei1'],
            'imei2' => $data['imei2'] ?? null,
            'serial' => $data['serial'] ?? null,

            'imei_photo_path' => $imeiPhotoPath,
            'phone_photo_path' => $phonePhotoPath,

            'purchase_price' => $data['purchase_price'],
            'sale_price' => $data['sale_price'],
            'markup' => $data['markup'] ?? null,

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('registro-producto.create')->with('ok', 'Registro guardado');
    }

    public function index(Request $request)
    {
        $q          = trim((string) $request->query('q', ''));              // IMEI / Serie
        $supplierId = $request->query('supplier_id');
        $modelId    = $request->query('iphone_model_id');
        $dateFrom   = $request->query('date_from');
        $dateTo     = $request->query('date_to');
        $sort       = $request->query('sort', 'date_desc');

        $query = DB::table('purchases as p')
            ->join('suppliers as s', 's.id', '=', 'p.supplier_id')
            ->join('iphone_models as m', 'm.id', '=', 'p.iphone_model_id')
            ->join('storage_options as st', 'st.id', '=', 'p.storage_option_id')
            ->select([
                'p.id',
                'p.purchase_date',
                'p.purchase_price',
                'p.sale_price',
                'p.imei1',
                'p.serial',
                'p.imei_photo_path',
                's.name as supplier_name',
                'm.name as model_name',
                'st.label as storage_label',
            ]);

        // filtros
        $query->when($q !== '', function ($qq) use ($q) {
            $qq->where(function ($w) use ($q) {
                $w->where('p.imei1', 'like', "%{$q}%")
                ->orWhere('p.imei2', 'like', "%{$q}%")
                ->orWhere('p.serial', 'like', "%{$q}%");
            });
        });

        $query->when($supplierId, fn($qq) => $qq->where('p.supplier_id', $supplierId));
        $query->when($modelId, fn($qq) => $qq->where('p.iphone_model_id', $modelId));

        $query->when($dateFrom, fn($qq) => $qq->whereDate('p.purchase_date', '>=', $dateFrom));
        $query->when($dateTo, fn($qq) => $qq->whereDate('p.purchase_date', '<=', $dateTo));

        // orden
        switch ($sort) {
            case 'cost_desc':
                $query->orderByDesc('p.purchase_price')->orderByDesc('p.id');
                break;
            case 'cost_asc':
                $query->orderBy('p.purchase_price')->orderByDesc('p.id');
                break;
            case 'date_asc':
                $query->orderBy('p.purchase_date')->orderByDesc('p.id');
                break;
            default: // date_desc
                $query->orderByDesc('p.purchase_date')->orderByDesc('p.id');
        }

        $rows = $query->paginate(20)->withQueryString();

        // Totales para cards
        $totalCost  = (float) DB::table('purchases')->sum('purchase_price');
        $totalSales = (float) DB::table('purchases')->sum('sale_price');
        $profit     = $totalSales - $totalCost;

        // listas para filtros
        $suppliers = DB::table('suppliers')->orderBy('name')->get();
        $models    = DB::table('iphone_models')->orderBy('name')->get();

        return view('purchases-index', compact(
            'rows', 'totalCost', 'totalSales', 'profit', 'suppliers', 'models'
        ));
    }   


    public function chart()
    {
        // últimos 30 días (puedes cambiar a 7/90)
        $rows = DB::table('purchases')
            ->selectRaw("purchase_date as d, SUM(purchase_price) as cost, SUM(sale_price) as sales")
            ->where('purchase_date', '>=', now()->subDays(30)->toDateString())
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $labels = $rows->pluck('d');
        $cost = $rows->pluck('cost')->map(fn($v) => (float)$v);
        $sales = $rows->pluck('sales')->map(fn($v) => (float)$v);

        return response()->json([
            'labels' => $labels,
            'cost' => $cost,
            'sales' => $sales,
        ]);
    }



        // VER (temporal)
    public function show($id)
    {
        return redirect()->route('purchases.index')
            ->with('ok', 'Vista detalle en construcción');
    }

    // EDITAR (temporal)
    public function edit($id)
    {
        $purchase = DB::table('purchases')->where('id', $id)->first();
        if (!$purchase) {
            return redirect()->route('purchases.index')->with('ok', 'Registro no encontrado');
        }

        $suppliers = DB::table('suppliers')->orderBy('name')->get();
        $models = DB::table('iphone_models')->orderBy('name')->get();
        $storages = DB::table('storage_options')->orderBy('gb')->get();

        // Colores del modelo actual para precargar dropdown
        $colors = DB::table('colors')
            ->where('iphone_model_id', $purchase->iphone_model_id)
            ->orderBy('name')
            ->get();

        return view('purchases-edit', compact('purchase', 'suppliers', 'models', 'storages', 'colors'));
    }
    public function update(Request $request, $id)
    {
        $purchase = DB::table('purchases')->where('id', $id)->first();
        if (!$purchase) {
            return redirect()->route('purchases.index')->with('ok', 'Registro no encontrado');
        }

        $data = $request->validate([
            'purchase_date' => ['required','date'],
            'supplier_id' => ['required','exists:suppliers,id'],
            'iphone_model_id' => ['required','exists:iphone_models,id'],
            'storage_option_id' => ['required','exists:storage_options,id'],
            'color_id' => ['required','exists:colors,id'],

            // IMEI NO se edita (bloqueado) => no lo validamos como input
            'imei2' => ['nullable','string','max:20', 'unique:purchases,imei2,' . $id],
            'serial' => ['nullable','string','max:50', 'unique:purchases,serial,' . $id],

            'purchase_price' => ['required','numeric','min:0'],
            'sale_price' => ['required','numeric','min:0'],
            'markup' => ['nullable','integer','in:150,200,250,300'],
            'phone_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'imei_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $newPhonePhotoPath = $purchase->phone_photo_path;

        if ($request->hasFile('phone_photo')) {
            $newPhonePhotoPath = $request->file('phone_photo')->store('purchases/phone', 'public');

            if (!empty($purchase->phone_photo_path)) {
                Storage::disk('public')->delete($purchase->phone_photo_path);
            }
        }


        $newImeiPhotoPath = $purchase->imei_photo_path;

        // Si sube una nueva foto IMEI, guardamos y borramos la anterior
        if ($request->hasFile('imei_photo')) {
            $newImeiPhotoPath = $request->file('imei_photo')->store('purchases/imei', 'public');

            if (!empty($purchase->imei_photo_path)) {
                Storage::disk('public')->delete($purchase->imei_photo_path);
            }
        }

        DB::table('purchases')->where('id', $id)->update([
            'purchase_date' => $data['purchase_date'],
            'supplier_id' => $data['supplier_id'],
            'iphone_model_id' => $data['iphone_model_id'],
            'storage_option_id' => $data['storage_option_id'],
            'color_id' => $data['color_id'],

            'imei2' => $data['imei2'] ?? null,
            'serial' => $data['serial'] ?? null,

            'phone_photo_path' => $newPhonePhotoPath,
            'imei_photo_path' => $newImeiPhotoPath,

            'purchase_price' => $data['purchase_price'],
            'sale_price' => $data['sale_price'],
            'markup' => $data['markup'] ?? null,

            'updated_at' => now(),
        ]);

        return redirect()->route('purchases.index')->with('ok', 'Registro actualizado');
    }


    // BORRAR (REAL, ya funciona)
    public function destroy($id)
    {
        $purchase = DB::table('purchases')->where('id', $id)->first();
        if ($purchase) {
            if (!empty($purchase->imei_photo_path)) {
                Storage::disk('public')->delete($purchase->imei_photo_path);
            }
            if (!empty($purchase->phone_photo_path)) {
                Storage::disk('public')->delete($purchase->phone_photo_path);
            }
            DB::table('purchases')->where('id', $id)->delete();
        }

        return redirect()->route('purchases.index')
            ->with('ok', 'Registro eliminado');
    }


}