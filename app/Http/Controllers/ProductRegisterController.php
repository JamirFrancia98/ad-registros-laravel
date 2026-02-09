<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRegisterController extends Controller
{
    public function create()
    {
        $suppliers = DB::table('suppliers')->orderBy('name')->get();
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
            'phone_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'], // 5MB
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

    public function index()
    {
        $rows = DB::table('purchases as p')
            ->join('suppliers as s', 's.id', '=', 'p.supplier_id')
            ->join('iphone_models as m', 'm.id', '=', 'p.iphone_model_id')
            ->select([
                'p.id',
                'p.purchase_date',
                'p.purchase_price',
                'p.imei1',
                'p.imei_photo_path', 
                's.name as supplier_name',
                'm.name as model_name',
            ])
            ->orderByDesc('p.purchase_date')
            ->orderByDesc('p.id')
            ->paginate(20);

        // Totales para las cards
        $totalCost = (float) DB::table('purchases')->sum('purchase_price');
        $totalSales = (float) DB::table('purchases')->sum('sale_price');
        $profit = $totalSales - $totalCost;

        return view('purchases-index', compact('rows', 'totalCost', 'totalSales', 'profit'));
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
    return redirect()->route('purchases.index')
        ->with('ok', 'Edición en construcción');
}

// BORRAR (REAL, ya funciona)
public function destroy($id)
{
    DB::table('purchases')->where('id', $id)->delete();

    return redirect()->route('purchases.index')
        ->with('ok', 'Registro eliminado');
}


}