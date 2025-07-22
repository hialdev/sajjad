<?php

namespace App\Http\Controllers;

use App\Models\Bal;
use App\Models\BalProduct;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReceive;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class BalController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $bals = Bal::where('name', 'LIKE', '%'.$filter->q.'%')->orWhere('code', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('bals.index', compact('bals', 'filter'));
    }

    public function add(){
        $product_types = ProductType::where('type', 'satuan')->orderBy('name', 'ASC')->get();
        $purchase_orders = PurchaseOrder::orderBy('code', 'ASC')->get();
        $products = Product::orderBy('name', 'ASC')->get();
        $warehouses = Warehouse::orderBy('name', 'ASC')->get();
        $stores = Store::orderBy('name', 'ASC')->get();
        $receives = PurchaseReceive::orderBy('code','asc')->get();
        return view('bals.add', compact('product_types','products', 'purchase_orders','receives', 'stores', 'warehouses'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.bals,name',
            'purchase_receive_id' => 'nullable|string|exists:osano.purchase_receives,id',
            'description' => 'nullable|string',
            'product_id' => 'array|required',
            'product_id.*' => 'exists:osano.products,id',
            'qty' => 'array',
            'qty.*' => 'numeric|required'
        ]);
        // dd($request->all());
        try {
            $bal = new Bal();
            $receive = null;
            $qty = $request->get('qty');

            if($request->purchase_receive_id != null && $request->purchase_receive_id != ''){
               // Jika ada purchase_receive_id, ambil data PurchaseReceive
               $receive = PurchaseReceive::find($request->get('purchase_receive_id'));
               // Verifikasi Qty
               foreach ($request->get('product_id') as $i => $productId) {
                  if($receive->purchase->products->count() > 0){
                     $pcproduct = $receive->purchase->products()->where('product_id', $productId)->first();
                     if($qty[$i] > $pcproduct->availableQtyBale($receive->id)){
                           return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Bal, Error: Kuantiti Produk pada Bal melebihi Ketersediaan dari Kuantitas Produk di Pembelian');
                     }
                  }
               }
            }
            
            $nowin = null;
            $nowin_type = null;
            if ($request->get('nowin_id') != null && $request->get('nowin_id') != ''){
               $nowin = Warehouse::find($request->get('nowin_id'));
               $nowin_type = 'warehouse';
               if (!$nowin) {
                  $nowin = Store::find($request->get('nowin_id'));
                  $nowin_type = 'store';
               };

               if (!$nowin) return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Bal, Error: Lokasi tidak ditemukan');
            }

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('bals', 'public');
                $bal->image = $imagePath;
            }

            $bal->name = $request->get('name');
            $bal->nowin_type = $receive ? 'warehouse' : $nowin_type;
            $bal->nowin_id = $receive ? $receive->purchase->warehouse_id : $request->get('nowin_id');
            $bal->purchase_receive_id = $request->get('purchase_receive_id') ?? null;
            $bal->description = $request->get('description');
            $bal->save();

            if($bal){
                foreach ($request->get('product_id') as $i => $productId) {
                    if($qty[$i] != 0){
                        $balProduct = new BalProduct();
                        $balProduct->bal_id = $bal->id;
                        $balProduct->product_id = $productId;
                        $balProduct->qty = $qty[$i];
                        $balProduct->save();
                    }
                }
            }

            return redirect()->route('bal.index')->with('success', 'Bal '.$bal->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Bal, Error: '.$e->getMessage());
        }
    }

    public function edit($id){
        $bal = Bal::find($id);
        if($bal->is_unpack) return redirect()->back()->with('error', 'Bal : '.$bal->name.' telah dibongkar, Edit tidak diizinkan.');
        $product_types = ProductType::orderBy('name', 'ASC')->get();
        $receives = PurchaseReceive::orderBy('code', 'ASC')->get();
        $products = Product::orderBy('name', 'ASC')->get();
        $warehouses = Warehouse::orderBy('name', 'ASC')->get();
        $stores = Store::orderBy('name', 'ASC')->get();
        return view('bals.edit', compact('product_types', 'receives', 'bal', 'products', 'warehouses', 'stores'));
    }

    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.bals,name,'.$id,
            'purchase_receive_id' => 'nullable|string|exists:osano.purchase_receives,id',
            'description' => 'nullable|string',
            'product_id' => 'array|required',
            'product_id.*' => 'exists:osano.products,id',
            'qty' => 'array',
            'qty.*' => 'numeric|required'
        ]);
        // dd($request->all());
        try {
            $bal = Bal::find($id);
            $receive = null;
            $qty = $request->get('qty');

            if($request->purchase_receive_id != null && $request->purchase_receive_id != ''){
               // Jika ada purchase_receive_id, ambil data PurchaseReceive
               dd($request->all());
               $receive = PurchaseReceive::find($request->get('purchase_receive_id'));
               // Verifikasi Qty
               foreach ($request->get('product_id') as $i => $productId) {
                  if($receive->purchase->products->count() > 0){
                     $pcproduct = $receive->purchase->products()->where('product_id', $productId)->first();
                     if($qty[$i] > ($pcproduct->availableQtyBale($receive->id) + $bal->products()->where('product_id', $productId)->first()->qty)) {
                        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Bal, Error: Kuantiti Produk pada Bal melebihi Ketersediaan dari Kuantitas Produk di Pembelian');
                     }
                  }
               }
            }
            
            $nowin = null;
            $nowin_type = null;
            if ($request->get('nowin_id') != null && $request->get('nowin_id') != ''){
               $nowin = Warehouse::find($request->get('nowin_id'));
               $nowin_type = 'warehouse';
               if (!$nowin) {
                  $nowin = Store::find($request->get('nowin_id'));
                  $nowin_type = 'store';
               };

               if (!$nowin) return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Bal, Error: Lokasi tidak ditemukan');
            }

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('bals', 'public');
                $bal->image = $imagePath;
            }

            $bal->name = $request->get('name');
            $bal->nowin_type = $receive ? 'warehouse' : $nowin_type;
            $bal->nowin_id = $receive ? $receive->purchase->warehouse_id : $request->get('nowin_id');
            $bal->purchase_receive_id = $request->get('purchase_receive_id') ?? null;
            $bal->description = $request->get('description');
            $bal->save();

            if($bal){
                $bal->products()->delete(); // Hapus produk lama sebelum menambahkan yang baru
                foreach ($request->get('product_id') as $i => $productId) {
                    if($qty[$i] != 0){
                        $balProduct = new BalProduct();
                        $balProduct->bal_id = $bal->id;
                        $balProduct->product_id = $productId;
                        $balProduct->qty = $qty[$i];
                        $balProduct->save();
                    }
                }
            }

            return redirect()->route('bal.index')->with('success', 'Bal '.$bal->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Bal, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $bal = Bal::find($id);
            if($bal->is_unpack) return redirect()->back()->withInput()->with('error', 'Gagal menghapus Bal, Error: Bal Sudah dibongkar!');
            $bal->delete();

            return redirect()->route('bal.index')->with('success', 'Bal '.$bal->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Bal, Error: '.$e->getMessage());
        }
    }
}
