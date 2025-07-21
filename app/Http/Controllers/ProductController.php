<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $products = Product::where('name', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('products.index', compact('products', 'filter'));
    }

    public function add(){
        $product_types = ProductType::orderBy('name', 'ASC')->get();
        $units = Unit::orderBy('name', 'ASC')->get();
        return view('products.add', compact('product_types', 'units'));
    }

    public function store(Request $request){
        $request->merge(['price_per_unit' => parseRupiah($request->get('price_per_unit'))]);
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.products,name',
            'width' => 'nullable|numeric|min:1',
            'height' => 'nullable|numeric|min:1',
            'price_per_unit' => 'nullable|numeric|min:1',
            'product_type_id' => 'required|string|exists:osano.product_types,id',
            'unit_id' => 'required|string|exists:osano.units,id',
            'description' => 'nullable|string',
        ]);
        if((!$request->get('width') && $request->get('height')) || ($request->get('width') && !$request->get('height')))
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Product, Error: Jika memiliki ukuran maka harus menentukan Tinggi dan Lebarnya');

        try {
            $product = new Product();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }
            $product->product_type_id = $request->get('product_type_id');
            $product->unit_id = $request->get('unit_id');
            $product->name = $request->get('name');
            $product->width = $request->get('width');
            $product->height = $request->get('height');
            $product->price_per_unit = $request->get('price_per_unit');
            $product->description = $request->get('description');
            $product->save();

            return redirect()->route('product.index')->with('success', 'Product '.$product->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Product, Error: '.$e->getMessage());
        }
    }

    public function edit($id){
        $product = Product::find($id);
        $product_types = ProductType::orderBy('name', 'ASC')->get();
        $units = Unit::orderBy('name', 'ASC')->get();
        return view('products.edit', compact('product', 'product_types', 'units'));
    }

    public function stock($id){
        $product = Product::find($id);
        // dd($product->warehouses());
        return view('products.stock', compact('product'));
    }

    public function update($id, Request $request){
        $request->merge(['price_per_unit' => parseRupiah($request->get('price_per_unit'))]);
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.products,name,'.$id,
            'width' => 'nullable|numeric|min:1',
            'height' => 'nullable|numeric|min:1',
            'price_per_unit' => 'nullable|numeric|min:1',
            'product_type_id' => 'required|string|exists:osano.product_types,id',
            'unit_id' => 'required|string|exists:osano.units,id',
            'description' => 'nullable|string',
        ]);
         if((!$request->get('width') && $request->get('height')) || ($request->get('width') && !$request->get('height')))
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Product, Error: Jika memiliki ukuran maka harus menentukan Tinggi dan Lebarnya');

        try {
            $product = Product::find($id);
            if ($request->hasFile('image')) {
                if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                    unlink(storage_path('app/public/' . $product->image));
                }
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }
            $product->product_type_id = $request->get('product_type_id');
            $product->unit_id = $request->get('unit_id');
            $product->name = $request->get('name');
            $product->width = $request->get('width');
            $product->height = $request->get('height');
            $product->price_per_unit = $request->get('price_per_unit');
            $product->description = $request->get('description');
            $product->save();

            return redirect()->route('product.index')->with('success', 'Product '.$product->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Product, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $product = Product::find($id);
            $product->delete();

            return redirect()->route('product.index')->with('success', 'Product '.$product->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Product, Error: '.$e->getMessage());
        }
    }

   public function qtyStockByLength(Request $request)
   {
      $request->validate([
         'product_id' => 'required|uuid|exists:osano.products,id',
         'length' => 'nullable|numeric|min:1',
         'store_id' => 'nullable|uuid|exists:osano.stores,id', // jika tidak pakai partner
      ]);

      $storeId = $request->get('store_id');
      $product = Product::with('type')->findOrFail($request->product_id);

      // Jika produk bertipe "meteran"
      if ($product->type->type === 'meteran') {
         if (!$request->filled('length')) {
               return response()->json([
                  'success' => false,
                  'message' => 'Panjang (length) harus diisi untuk produk meteran'
               ], 422);
         }

         $stocks = null;
         if($request->filled('store_id')){
            $stocks = $product->findMeterStockByLength($request->length, 'store', $request->get('store_id'));
         }else{
            $stocks = $product->findMeterStockByLength($request->length);
         }

         $totalQty = $stocks->count();
         $totalLength = $stocks->sum(fn($s) => $s->length - $s->sold_length);

         return response()->json([
               'success' => true,
               'qty' => $totalQty,
               'length_available' => $totalLength,
         ]);
      }

      // Jika produk bukan meteran, maka ambil dari stok biasa
      if (!$request->filled('store_id')) {
         return response()->json([
               'success' => false,
               'message' => 'Store belum dipilih untuk produk non-meteran'
         ], 422);
      }

      $stockResult = \App\Models\Stock::analyticProductInLocation('store', $storeId, $product->id)->first();

      if (!$stockResult) {
         return response()->json([
               'success' => true,
               'qty' => 0,
         ]);
      }

      return response()->json([
         'success' => true,
         'qty' => $stockResult->stock_remaining,
      ]);
   }

}
