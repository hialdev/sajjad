<?php

namespace App\Http\Controllers;

use App\Models\Principal;
use App\Models\PrincipalPic;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInvoice;
use App\Models\PurchaseReceive;
use App\Models\RequestOrder;
use App\Models\RequestOrderInvoice;
use App\Models\Stock;
use App\Models\StockMeter;
use App\Models\Transport;
use App\Models\TransportInvoice;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\WarehouseStockMeter;
use Illuminate\Http\Request;

class PurchaseReceiveController extends Controller
{
    public function index(Request $request) {
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'code'),
            'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
        ];

        $receives = PurchaseReceive::where('code', 'LIKE', '%'.$filter->q.'%')->orWhere('description', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)
                                ->get();

        return view('purchase_orders.receive.index', compact('receives', 'filter'));
    }

    public function products($id){
        $purchaseReceive = PurchaseReceive::whereHas('purchase.products.product.type', function ($q) {
                $q->where('type', 'satuan');
            })
            ->with(['purchase.products.product.type'])
            ->where('id', $id)
            ->firstOrFail();
        return response()->json($purchaseReceive->purchase->products->map(function ($product) use ($purchaseReceive) {
            return [
                'id' => $product->id,
                'product_id' => $product->product->id,
                'image' => $product->product->image ? asset('/storage/'.$product->product->image) : '/assets/images/profile/user-1.jpg',
                'type' => $product->product->type->type,
                'name' => $product->product->name,
                'height' => $product->product->height,
                'width' => $product->product->width,
                'qty' => $product->availableQtyBale($purchaseReceive->id),
                'price_buy' => $product->price_buy,
            ];
        }));
    }

    public function add(){
        $warehouses = Warehouse::orderBy('name', 'ASC')->get();
        $purchases = PurchaseOrder::orderBy('code', 'ASC')->get();
        $users = User::role('employee')->get();
        return view('purchase_orders.receive.add', compact('purchases', 'users', 'warehouses'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'date' => 'required|date',
            'warehouse_id' => 'required|string|exists:osano.warehouses,id',
            'purchase_order_id' => 'required|string|exists:osano.purchase_orders,id',
            'user_id' => 'required|string|exists:users,id',
            'description' => 'nullable|string',
        ]);
        
        try {
            $receive = new PurchaseReceive();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('purchase_receives', 'public');
                $receive->image = $imagePath;
            }
            $receive->date = $request->get('date');
            $receive->warehouse_id = $request->get('warehouse_id');
            $receive->purchase_order_id = $request->get('purchase_order_id');
            $receive->user_id = $request->get('user_id');
            $receive->description = $request->get('description');
            $receive->save();

            return redirect()->route('receive.setting', $receive->id)->with('success', 'Penerimaan Barang dengan Kode '.$receive->code.' berhasil ditambahkan. Selanjutnya Kelola Barang yang diterima.')->with('redirect_hash', 'produk');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Penerimaan Barang, Error: '.$e->getMessage());
        }
    }

    public function setting($id, Request $request){
        $receive = PurchaseReceive::find($id);
        $purchase = $receive->purchase;
        $warehouses = Warehouse::orderBy('name', 'ASC')->get();
        $purchases = PurchaseOrder::orderBy('code', 'ASC')->get();
        $users = User::role('employee')->get();

        $filter = (object) [
            'q' => $request->get('search') ?? '',
        ];
        $products = Product::where('name', 'LIKE', "%{$filter->q}%")->orderBy('name')->paginate((int) setting('site.product-limit') ?? 6);

        if ($request->filled('hashProduct')) {
            session(['redirect_hash' => 'produk']);
        }
        
        //dd(!$purchase->clientInvoice && $purchase->requestOrder && !$purchase->requestOrder->invoice->purchase_order_id == $purchase->id);
        return view('purchase_orders.receive.setting', compact('receive', 'purchases', 'purchase', 'filter', 'products', 'users', 'warehouses'));
    }

    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'date' => 'required|date',
            'warehouse_id' => 'required|string|exists:osano.warehouses,id',
            'purchase_order_id' => 'required|string|exists:osano.purchase_orders,id',
            'user_id' => 'required|string|exists:users,id',
            'description' => 'nullable|string',
        ]);
        try {
            $receive = PurchaseReceive::findOrFail($id);
            if($receive->is_lock) return redirect()->back()->with('error', 'Gagal memperbarui, Data Penerimaan sudah dikunci!');

            if ($request->hasFile('image')) {
                if ($receive->image && file_exists(storage_path('app/public/' . $receive->image))) {
                    unlink(storage_path('app/public/' . $receive->image));
                }
                $imagePath = $request->file('image')->store('purchase_receives', 'public');
                $receive->image = $imagePath;
            }
            $receive->date = $request->get('date');
            $receive->warehouse_id = $request->get('warehouse_id');
            $receive->purchase_order_id = $request->get('purchase_order_id');
            $receive->user_id = $request->get('user_id');
            $receive->description = $request->get('description');
            $receive->save();

            return redirect()->route('receive.setting', $receive->id)->with('success', 'Penerimaan Barang dengan Kode '.$receive->code.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Penerimaan barang, Error: '.$e->getMessage());
        }
    }

    public function process($id, Request $request){
        try {
            $receive = PurchaseReceive::find($id);
            if($receive->products->count() < 1) return redirect()->back()->with('error', 'Gagal memproses karena Tidak ada data produk yang diterima!');
            if($receive->is_stocked) return redirect()->back()->with('error', 'Gagal memproses karena Penerimaan ini telah dimasukan kedalam Stock!');

            $this->addToStock($id);
            
            if (!$receive->is_lock) {
                $receive->is_lock = 1;
            }
            $receive->is_stocked = 1;
            $receive->save();

            $purchase = $receive->purchase;
            if ($purchase->status != '2' && $purchase->isFullyReceived()){
                $purchase->status = '2';
                $purchase->save();
            }

            return redirect()->back()->with('success', 'Berhasil menyelesaikan Pemesanan dan Penerimaan. Stock juga telah ditambahkan ke Gudang '.$receive->purchase->warehouse->name);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyelesaikan Pemesanan dan Penerimaan, Error: '.$e->getMessage());
        }
    }

    public function invoice($id){
        $purchase = PurchaseOrder::findOrFail($id);
        
        if ($purchase->status != '2' || $purchase->invoice) 
            return redirect()->back()->with('error', 'Gagal Generate Invoice, Status tidak valid atau Invoice telah dibuat!.');
        
        try {
            $invoicePurchase = new PurchaseOrderInvoice();
            $invoicePurchase->purchase_order_id = $purchase->id;
            $invoicePurchase->payment_status = '0';
            $invoicePurchase->save();

            if($purchase->transport){
                $invoiceTransport = new TransportInvoice();
                $invoiceTransport->transport_id = $purchase->transport->id;
                $invoiceTransport->payment_status = '0';
                $invoiceTransport->save();

                $purchase->transport->generate_invoice = 1;
                $purchase->transport->save();
            }

            $purchase->generate_invoice = 1;
            $purchase->save();

            return redirect()->route('purchase-order.setting', $id)->with('success', 'Generate Invoice untuk Pembelian Principal kode '.$purchase->code.' berhasil dilakukan.')
                            ->with('redirect_hash', 'invoice');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Generate Invoice, Error: '.$e->getMessage())->with('redirect_hash', 'invoice');
        }
        
    }    

    public function invoiceClientPartial($id){
        $purchase = PurchaseOrder::findOrFail($id);
        
        if ($purchase->status != '2' || !$purchase->invoice) 
            return redirect()->back()->with('error', 'Gagal Generate Invoice, Status tidak valid atau Invoice Pembelian ke Principal belum dibuat!.')->with('redirect_hash', 'invoice');

        if ($purchase->requestOrder->invoice && !$purchase->requestOrder->invoice->purchaseOrder) 
            return redirect()->back()->with('error', 'Gagal Generate Invoice Partial, Sudah ada Invoice Secara Keseluruhan!.')->with('redirect_hash', 'invoice');

        try {
            $invoiceClient = new RequestOrderInvoice();
            $invoiceClient->request_order_id = $purchase->requestOrder->id;
            $invoiceClient->purchase_order_id = $purchase->id;
            $invoiceClient->payment_status = '0';
            $invoiceClient->save();

            return redirect()->route('purchase-order.setting', $id)->with('success', 'Generate Partial Invoice Client untuk Pembelian Principal kode '.$purchase->code.' berhasil dilakukan.')
                            ->with('redirect_hash', 'invoice');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Generate Partial Invoice, Error: '.$e->getMessage())->with('redirect_hash', 'invoice');
        }
        
    }    

    public function destroy($id){
        try {
            $purchase = PurchaseOrder::find($id);
            if($purchase->status != '0'){
                return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pembelian ke Principal, Error: Status tidak diizinkan untuk dihapus');
            }
            $purchase->delete();

            return redirect()->route('purchase-order.index')->with('success', 'Pembelian ke Principal '.$purchase->code.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pembelian ke Principal, Error: '.$e->getMessage());
        }
    }

    // ------------------------------
    private function addToStock($receiveId){ 
        $receive = PurchaseReceive::find($receiveId);
        if (!$receive) return;

        $products = $receive->products;
        foreach ($products as $prd) {
            $product = $prd->purchaseProduct->product;
            $type = $product->type->type;

            if($type == 'satuan') {
                $this->stockSatuan($receive, $product, $prd->receive_qty);
            }else if($type == 'meteran') {
                $this->stockMeteran($receive, $product, $prd->receive_qty);
            }else { return false; }
        }
    }

    private function stockSatuan($receive, $product, $qty){
        $stock = new Stock();
        $stock->nowin_type = 'warehouse';
        $stock->nowin_id = $receive->warehouse_id;
        $stock->product_id = $product->id;
        $stock->qty = $qty;
        $stock->trx_type = 'in'; //in, onway, out
        $stock->save();
    }

    private function stockMeteran($receive, $product, $qty){
        for ($i=0; $i < $qty; $i++) { 
            $stockMeter = new StockMeter();
            $stockMeter->product_id = $product->id;
            $stockMeter->nowin_type = 'warehouse';
            $stockMeter->nowin_id = $receive->warehouse_id;
            $stockMeter->length = $product->width;
            $stockMeter->sold_length = 0;
            $stockMeter->save();
        }
    }
}
