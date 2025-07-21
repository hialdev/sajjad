<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMeter;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\Transport;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{
    public function analytic(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];
        
        $warehouses = Warehouse::orderBy('name', 'ASC')->get();
        $stores = Store::orderBy('name', 'ASC')->get();
        $totalStock = Stock::sum('qty');
        $totalStockMeter = StockMeter::all()->count();
        $stockTotal = (object)[
            'satuan' => (int) $totalStock,
            'meteran' => (int) $totalStockMeter,
            'total' => (int) $totalStock + $totalStockMeter,
        ];
        
        return view('stocks.index', compact('stores', 'warehouses', 'stockTotal', 'filter'));
    }

    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'code'),
            'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
        ];
        $distributions = StockMovement::where('code', 'LIKE', '%'.$filter->q.'%')->orWhere('description', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)
                                ->get();
        return view('stocks.distribution', compact('distributions', 'filter'));
    }

    public function move(){
        $users = User::withoutRole('developer')->get();
        $transports = Transport::whereDoesntHave('distribution')
                                ->whereDoesntHave('purchaseOrder')
                                ->whereDoesntHave('requestProcess')
                                ->orderBy('code', 'ASC')
                                ->get();
        $warehouses = Warehouse::orderBy('name', 'ASC')->get();
        $stores = Store::orderBy('name', 'ASC')->get();
        
        return view('stocks.move', compact('users', 'transports', 'warehouses', 'stores'));
    }

    private function checkIdWS($id){
        $warehouse = Warehouse::find($id);
        $store = Store::find($id);
        if( $warehouse || $store ) return true;
        return false;
    }

    public function moved(Request $request){
        $request->validate([
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'is_handle_logistic' => 'nullable|boolean',
            'transport_id' => 'nullable|exists:osano.transports,id',
            'from_id' => 'required|uuid',
            'to_id' => 'required|uuid',
            'description' => 'nullable|string',
        ]);
        $isTransport = $request->is_handle_logistic && $request->transport_id;
        if (!$this->checkIdWS($request->from_id)) return redirect()->back()->withInput()->with('error', 'Data Warehouse / Toko dengan id '.$request->from_id.' tidak ditemukan (Tempat Asal).');
        if (!$this->checkIdWS($request->to_id)) return redirect()->back()->withInput()->with('error', 'Data Warehouse / Toko dengan id '.$request->to_id.' tidak ditemukan (Tempat Tujuan).');

        try{
            $move = new StockMovement();
            $move->date = $request->date;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('stock_movements', 'public');
                $move->image = $imagePath;
            }
            $move->user_id = $request->user_id;
            $move->from_type = $this->getNowinType($request->get('from_id'));
            $move->from_id = $request->from_id;
            $move->to_type = $this->getNowinType($request->get('to_id'));
            $move->to_id = $request->to_id;
            $move->description = $request->description;
            if($isTransport) $move->transport_id = $request->transport_id;
            $move->save();

            return redirect()->route('stock.setting', $move->id)->with('success', 'Berhasil mencatat Pendistribusian barang, sekarang tentukan barangnya')->with('redirect_hash', 'produk');
        }catch(\Exception $e){
            return redirect()->back()->withInput()->with('error', 'Terdapat kesalahan : '.$e->getMessage());
        }
    }

    public function getNowinType($id){
        $check = Warehouse::find($id);
        if($check){
            return 'warehouse';
        }
        return 'store';
    }

    public function setting($id, Request $request){
        $move = StockMovement::findOrFail($id);
        $users = User::withoutRole('developer')->get();
        $transports = Transport::whereDoesntHave('distribution')
                                ->whereDoesntHave('purchaseOrder')
                                ->orderBy('code', 'ASC')
                                ->get();
        $warehouses = Warehouse::orderBy('name', 'ASC')->get();
        $stores = Store::orderBy('name', 'ASC')->get();
        $filter = (object) [
            'q' => $request->get('search') ?? '',
        ];
        // Ambil produk dari Stock
        $stockProducts = Stock::where('nowin_type', $move->from_type)
            ->where('nowin_id', $move->from_id)
            ->whereHas('product', function($q) use ($filter) {
            $q->where('name', 'LIKE', "%{$filter->q}%");
            })
            ->with('product')
            ->get()
            ->pluck('product')
            ->unique('id')
            ->values();

        // Ambil produk dari StockMeter
        $stockMeterProducts = StockMeter::where('nowin_type', $move->from_type)
            ->where('nowin_id', $move->from_id)
            ->whereHas('product', function($q) use ($filter) {
            $q->where('name', 'LIKE', "%{$filter->q}%");
            })
            ->with('product')
            ->get()
            ->pluck('product')
            ->unique('id')
            ->values();

        // Gabungkan dan hilangkan duplikat berdasarkan id produk
        $products = $stockProducts->merge($stockMeterProducts)->unique('id')->values();

        // Tambahkan analytic stock untuk setiap produk
        $products = $products->map(function($product) use ($move) {
            if (isset($product->type) && isset($product->type->type) && $product->type->type === 'meteran') {
                $analytic = StockMeter::analyticProductInLocation($move->from_type, $move->from_id, $product->id)->first();
                $product->analytic = $analytic ?: (object)[
                    'qty' => 0,
                    'length_total' => 0,
                    'qty_onway' => 0,
                    'qty_remaining' => 0,
                ];
            } else {
                $analytic = Stock::analyticProductInLocation($move->from_type, $move->from_id, $product->id)->first();
                $product->analytic = $analytic ?: (object)[
                    'stock_in' => 0,
                    'stock_out' => 0,
                    'stock_onway' => 0,
                    'stock_remaining' => 0,
                ];
            }
            return $product;
        });

        if ($request->filled('hashProduct')) {
            session(['redirect_hash' => 'produk']);
        }
        return view('stocks.setting', compact('move','users', 'transports', 'stores', 'warehouses', 'products', 'filter'));
    }

    public function moveUpdate($id, Request $request){
        $request->validate([
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'is_handle_logistic' => 'nullable|boolean',
            'transport_id' => 'nullable|exists:osano.transports,id',
            'from_id' => 'required|uuid',
            'to_id' => 'required|uuid',
            'description' => 'nullable|string',
        ]);
        $isTransport = $request->is_handle_logistic && $request->transport_id;
        if (!$this->checkIdWS($request->from_id)) return redirect()->back()->withInput()->with('error', 'Data Warehouse / Toko dengan id '.$request->from_id.' tidak ditemukan (Tempat Asal).');
        if (!$this->checkIdWS($request->to_id)) return redirect()->back()->withInput()->with('error', 'Data Warehouse / Toko dengan id '.$request->to_id.' tidak ditemukan (Tempat Tujuan).');

        try{
            $move = StockMovement::findOrFail($id);
            $move->date = $request->date;
            if ($request->hasFile('image')) {
                if ($move->image && Storage::disk('public')->exists($move->image)) {
                    Storage::disk('public')->delete($move->image);
                }
                $imagePath = $request->file('image')->store('stock_movements', 'public');
                $move->image = $imagePath;
            }
            $move->user_id = $request->user_id;
            $move->from_type = $this->getNowinType($request->get('from_id'));
            $move->from_id = $request->from_id;
            $move->to_type = $this->getNowinType($request->get('to_id'));
            $move->to_id = $request->to_id;
            $move->description = $request->description;
            if($isTransport) $move->transport_id = $request->transport_id;
            $move->save();

            return redirect()->route('stock.setting', $move->id)->with('success', 'Berhasil mencatat Pendistribusian barang')->with('redirect_hash', 'produk');
        }catch(\Exception $e){
            return redirect()->back()->withInput()->with('error', 'Terdapat kesalahan : '.$e->getMessage());
        }
    }

    public function process($id){
        try {
            $move = StockMovement::find($id);
            if($move->products->count() < 1) return redirect()->back()->with('error', 'Gagal memproses karena Tidak ada data produk yang diterima!');
            if($move->is_stocked) return redirect()->back()->with('error', 'Gagal memproses karena Penerimaan ini telah dimasukan kedalam Stock!');

            $move->status = $move->status < 1 ? '1' : '2';
            $move->save();

            if ($move->status == '1'){
                $this->onwayStock($move);
            }

            if ($move->status == '2'){
                $this->moveStock($move);
            }

            return redirect()->back()->with('success', 'Berhasil '.($move->status != 2 ? 'Memproses' : 'Menyelesaikan').' Distribusi Stock / Barang.  ke '.$move->to->name);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal '.($move->status != 2 ? 'Memproses' : 'Menyelesaikan').' Distribusi Stock / Barang, Error: '.$e->getMessage());
        }
    }

    public function moveDestroy($id)
    {
        try {
            $move = StockMovement::findOrFail($id);
            if($move->status != 0) 
                return redirect()->back()->with('error', 'Tidak dapat menghapus karena sudah di proses / selesai');

            if ($move->image && Storage::disk('public')->exists($move->image)) {
                Storage::disk('public')->delete($move->image);
            }
            $move->delete();

            return redirect()->route('stock.distribution')->with('success', 'Data distribusi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terdapat kesalahan : ' . $e->getMessage());
        }
    }

    public function addCart($id, Request $request)
    {
        $move = StockMovement::find($id);
        if($move->status != '0') return redirect()->back()->with('error', 'Distribusi telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

        $request->validate([
            'product_id' => 'required|exists:osano.products,id',
        ]);

        $cart = session()->get("distribution_cart_".$id, []);

        if (isset($cart[$request->get('product_id')])) {
            // Jika produk sudah ada di cart, tambahkan jumlahnya
            $cart[$request->get('product_id')]['qty'] += $request->get('qty', 1);
        } else {
            // Jika produk belum ada di cart, tambahkan dengan qty default 1
            $cart[$request->get('product_id')] = [
                'id' => $request->get('product_id'),
                'qty' => 1,
                'description' => '',
            ];
        }

        session()->put('distribution_cart_'.$id, $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke Keranjang Distribusi')->with('redirect_hash', 'produk');
    }

    public function removeCart($id, Request $request)
    {
        $move = StockMovement::find($id);
        if($move->status != '0') return redirect()->back()->with('error', 'Distribusi telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

        $request->validate([
            'product_id' => 'required|exists:osano.products,id',
        ]);

        $cart = session()->get("distribution_cart_".$id, []);

        if (isset($cart[$request->get('product_id')])) {
            unset($cart[$request->get('product_id')]);
            session()->put("distribution_cart_".$id, $cart);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang distribusi',
            ], 200);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang distribusi')->with('redirect_hash', 'produk');
    }

    //-----------------------
    private function onwayStock($move){
        $products = $move->products;
        foreach ($products as $mp) {
            $type = $mp->product->type->type;
            if($type == 'meteran'){
                $stocks = StockMeter::where('product_id', $mp->product_id)->where('nowin_id', $move->from_id)->where('is_onway', 0)->where('sold_length', 0)->limit($mp->qty)->get();
                StockMeter::whereIn('id', $stocks->pluck('id'))->update(['is_onway' => 1]);
            }else{
                $stock = new Stock();
                $stock->product_id = $mp->product_id;
                $stock->nowin_type = $move->from_type;
                $stock->nowin_id = $move->from_id;
                $stock->qty = $mp->qty;
                $stock->trx_type = 'onway';
                $stock->save();
            }
        }
    }

    private function moveStock($move){
        $products = $move->products;
        foreach ($products as $mp) {
            $type = $mp->product->type->type;
            if($type == 'meteran'){
                $stocks = StockMeter::where('product_id', $mp->product_id)->where('nowin_id', $move->from_id)->where('is_onway', 1)->where('sold_length', 0)->limit($mp->qty)->get();
                StockMeter::whereIn('id', $stocks->pluck('id'))->update(['is_onway' => 0, 'nowin_type' => $move->to_type, 'nowin_id' => $move->to_id]);
            }else{
                $updateOnwayStock = Stock::where('product_id', $mp->product_id)
                    ->where('nowin_type', $move->from_type)
                    ->where('nowin_id', $move->from_id)
                    ->where('qty', $mp->qty)
                    ->where('trx_type', 'onway')
                    ->first();

                if ($updateOnwayStock) {
                    $updateOnwayStock->trx_type = 'out';
                    $updateOnwayStock->save();
                }
                
                if ($updateOnwayStock) {
                    // Create in stock
                    $stock = new Stock();
                    $stock->product_id = $mp->product_id;
                    $stock->nowin_type = $move->to_type;
                    $stock->nowin_id = $move->to_id;
                    $stock->qty = $mp->qty;
                    $stock->trx_type = 'in';
                    $stock->save();
                }
            }
        }
    }
}
