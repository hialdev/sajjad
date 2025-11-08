<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RequestOrder;
use App\Models\RequestProcess;
use App\Models\Stock;
use App\Models\StockMeter;
use App\Models\Store;
use App\Models\Transport;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class RequestProcessController extends Controller
{
   public function index(Request $request)
   {
      $filter = (object) [
         'q' => $request->get('search', ''),
         'field' => $request->get('field', 'code'),
         'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
      ];

      $processes = RequestProcess::where('code', 'LIKE', '%' . $filter->q . '%')->orWhere('description', 'LIKE', '%' . $filter->q . '%')->orderBy($filter->field, $filter->order)->with('products')
         ->get();

      return view('request_process.index', compact('processes', 'filter'));
   }

   public function add()
   {
      $reqorders = RequestOrder::orderBy('code', 'ASC')->get();
      $transports = Transport::whereDoesntHave('requestProcess')
         ->whereDoesntHave('distribution')
         ->whereDoesntHave('purchaseOrder')
         ->orderBy('code', 'ASC')
         ->get();
      $users = User::orderBy('name', 'ASC')->get();

      return view('request_process.add', compact('transports', 'reqorders', 'users'));
   }

   public function store(Request $request)
   {
      $request->validate([
         'date' => 'required|date',
         'request_order_id' => 'nullable|string|exists:osano.request_orders,id',
         'user_id' => 'nullable|string|exists:users,id',
         'is_handle_logistic' => 'nullable|boolean',
         'transport_id' => 'nullable|string|exists:osano.transports,id',
         'description' => 'nullable|string|min:4',
      ]);
      try {
         $process = new RequestProcess();
         $process->date = $request->get('date');
         $process->request_order_id = $request->get('request_order_id');
         $process->user_id = $request->get('user_id');
         $process->is_handle_logistic = $request->get('transport_id') && $request->get('is_handle_logistic') ? $request->get('is_handle_logistic') : '0';
         $process->transport_id = $request->get('transport_id') && $request->get('is_handle_logistic') ? $request->get('transport_id') : null;
         $process->description = $request->get('description');
         $process->save();

         return redirect()->route('request-process.setting', ['id' => $process->id])->with('success', 'Pemrosesan Permintaan Client ' . $process->code . ' berhasil ditambahkan, sekarang tentukan produknya.')->with('redirect_hash', 'produk');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Pemrosesan Permintaan Client, Error: ' . $e->getMessage())->with('redirect_hash', 'produk');
      }
   }

   public function setting($id, Request $request)
   {
      $process = RequestProcess::find($id);
      $reqorders = RequestOrder::orderBy('code', 'ASC')->get();
      $transports = Transport::whereDoesntHave('requestProcess')
         ->whereDoesntHave('distribution')
         ->whereDoesntHave('purchaseOrder')
         ->orderBy('code', 'ASC')
         ->get();
      if ($process && $process->transport) {
         $transports->push($process->transport);
      }

      $users = User::orderBy('name', 'ASC')->get();
      $warehouses = Warehouse::orderBy('name', 'ASC')->get();
      $stores = Store::orderBy('name', 'ASC')->get();
      $filter = (object) [
         'q' => $request->get('search') ?? '',
      ];
      $products = $process->requestOrder->products()->where('name', 'LIKE', "%{$filter->q}%")->get();
      if (count(session()->get('cart_' . $id, [])) == 0) {
         $this->refetch($id, new Request());
      }

      if ($request->filled('hashProduct')) {
         session(['redirect_hash' => 'produk']);
      }

      //dd(!$process->clientInvoice && $process->requestOrder && !$process->requestOrder->invoice->purchase_order_id == $process->id);
      return view('request_process.setting', compact('reqorders', 'transports', 'users', 'filter', 'products', 'process', 'warehouses', 'stores'));
   }

   public function update($id, Request $request)
   {
      $process = RequestProcess::find($id);
      $request->validate([
         'date' => 'required|date',
         'principal_id' => 'required|string|exists:osano.principals,id',
         'principal_pic_id' => 'required|string|exists:osano.principal_pics,id',
         'warehouse_id' => 'nullable|string|exists:osano.warehouses,id',
         'transport_id' => 'nullable|string|exists:osano.transports,id|unique:osano.request_process,transport_id',
         'pickup_address_id' => 'nullable|string|exists:osano.principal_addresses,id',
         'is_handle_logistic' => 'nullable|boolean',
         'description' => 'nullable|string|min:4',
      ]);
      try {
         if ($process->status != '0') {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pemrosesan Permintaan Client, Error: Status tidak diizinkan untuk diperbarui');
         }
         $process->date = $request->get('date');
         $process->principal_id = $request->get('principal_id');
         $process->principal_pic_id = $request->get('principal_pic_id');
         $process->transport_id = $request->get('transport_id');
         $process->pickup_address_id = $request->get('pickup_address_id');
         $process->warehouse_id = $request->get('warehouse_id');
         $process->is_handle_logistic = $request->get('is_handle_logistic') ?? '0';
         $process->description = $request->get('description');
         $process->save();

         return redirect()->route('request-process.setting', ['id' => $process->id])->with('success', 'Pemrosesan Permintaan Client ' . $process->name . ' berhasil diperbarui.');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pemrosesan Permintaan Client, Error: ' . $e->getMessage());
      }
   }

   public function addCart($id, Request $request)
   {
      $process = RequestProcess::find($id);
      if ($process->status != '0') return redirect()->back()->with('error', 'Pembelian telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');
      $analytic = $process->requestOrder->getProcessingAnalytics()[$request->product_id];
      if ($analytic['remaining_qty'] == 0) return redirect()->back()->with('error', 'Tidak dapat ditambahkan, produk ini sudah di proses semua!')->with('redirect_hash', 'produk');

      $request->validate([
         'product_id' => 'required|exists:osano.products,id',
      ]);

      $cart = session()->get("cart_" . $id, []);

      if (isset($cart[$request->get('product_id')])) {
         // Jika produk sudah ada di cart, tambahkan jumlahnya
         $cart[$request->get('product_id')]['qty'] += $request->get('qty', 1);
      } else {
         // Jika produk belum ada di cart, tambahkan dengan qty default 1
         $cart[$request->get('product_id')] = [
            'id' => $request->get('product_id'),
            'qty' => 1,
            'price_buy' => 0,
            'pack_id' => '',
         ];
      }

      session()->put('cart_' . $id, $cart);

      return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang')->with('redirect_hash', 'produk');
   }

   public function removeCart($id, Request $request)
   {
      $process = RequestProcess::find($id);
      if ($process->status != '0') return redirect()->back()->with('error', 'Pembelian telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

      $request->validate([
         'product_id' => 'required|exists:osano.products,id',
      ]);

      $cart = session()->get("cart_" . $id, []);

      if (isset($cart[$request->get('product_id')])) {
         unset($cart[$request->get('product_id')]);
         session()->put("cart_" . $id, $cart);
      }

      if ($request->ajax()) {
         return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang',
         ], 200);
      }

      return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang')->with('redirect_hash', 'produk');
   }

   // Refill Cart Session by Request Order Product
   public function refetch($id, Request $request)
   {
      $process = RequestProcess::find($id);
      session()->forget('cart_' . $id);
      $cart = [];
      if ($process->products) {
         foreach ($process->products as $processproduct) {
            $cart[$processproduct->product_id] = [
               'id' => $processproduct->product_id,
               'qty' => $processproduct->qty,
               'price_buy' => $processproduct->price_buy,
               'pack_id' => $processproduct->pack_id,
            ];
         }
      }
      session()->put('cart_' . $id, $cart);

      if ($request->ajax()) {
         return response()->json([
            'success' => true,
            'message' => 'Mereset Ulang dengan data Produk Pemrosesan Permintaan Client',
         ], 200);
      }
   }

   public function process($id, Request $request)
   {
      try {
         $process = RequestProcess::find($id);
         if ($process->status == '0' && $process->requestOrder && !$process->requestOrder->isStillRemain())
            return redirect()->back()->with('error', 'Gagal memproses Pembelian, Sesuaikan qty produk yang di proses dengan sisa yang belum diproses!.');
         if ($process->products->count() == 0)
            return redirect()->back()->with('error', 'Gagal memproses Pembelian, Tidak ada produk yang diproses!.');

         if ($process->status == 0) {
            $process->status = (string) 1;
            $this->updateStockForProcess($process, 1);
         } else if ($process->status == 1) {
            $process->status = (string) 2;
            $this->updateStockForProcess($process, 2);
         } else {
            return redirect()->back()->with('error', 'Tidak ada proses selanjutnya.');
         }
         $process->save();

         if ($process->is_handle_logistic) {
            $transport = $process->transport;
            $transport->status = $process->status;
            $transport->save();
         }

         if ($process->requestOrder) {
            $reqOrder = $process->requestOrder;
            $reqOrder->status = $reqOrder->isFinished() ? '2' : '1';
            $reqOrder->save();
         }

         return redirect()->back()->with('success', 'Berhasil ' . ($process->status == 1 ? 'Selesaikan' : 'Proses') . ' Pemrosesan Permintaan Client dengan Kode ' . $process->code . '.');
      } catch (\Exception $e) {
         return redirect()->back()->with('error', 'Gagal memproses / selesaikan Pemrosesan Permintaan Client, Error: ' . $e->getMessage());
      }
   }

   private function updateStockForProcess($process, $status) {
      if($status == 1){
         foreach ($process->products as $pprod) {
            if($pprod->product->type->type == 'satuan'){
               $stock = new Stock();
               $stock->product_id = $pprod->product_id;
               $stock->nowin_type = $pprod->from_type;
               $stock->nowin_id = $pprod->from_id;
               $stock->qty = $pprod->qty;
               $stock->trx_type = 'onway';
               $stock->save();
            }else{
               $stocks = StockMeter::where([
                           ['product_id', '=', $pprod->product_id], 
                           ['nowin_type', '=', $pprod->from_type], 
                           ['nowin_id', '=', $pprod->from_id], 
                           ['length', '>=', $pprod->length], 
                        ])->limit($pprod->qty)->get();
               if($stocks->count() == 0) continue;
               foreach ($stocks as $stock) { 
                  $stock->is_onway = 1;
                  $stock->sold_length = $pprod->length;
                  $stock->save();
               }
            }
         }
      }else{
         foreach ($process->products as $pprod) {
            if($pprod->product->type->type == 'satuan'){
               $stock = Stock::where([
                           ['product_id', '=', $pprod->product_id], 
                           ['nowin_type', '=', $pprod->from_type], 
                           ['nowin_id', '=', $pprod->from_id], 
                           ['qty', '=', $pprod->qty], 
                           ['trx_type', '=', 'onway']
                        ])->first();
               if(!$stock) continue;
               $stock->trx_type = 'out';
               $stock->save();
            }else{
               $stocks = StockMeter::where([
                           ['product_id', '=', $pprod->product_id], 
                           ['nowin_type', '=', $pprod->from_type], 
                           ['nowin_id', '=', $pprod->from_id], 
                           ['sold_length', '>=', $pprod->length], 
                           ['is_onway', '=', 1]
                        ])->limit($pprod->qty)->get();
               if($stocks->count() == 0) continue;
               foreach ($stocks as $stock) { 
                  $stock->is_onway = 0;
                  $stock->save();
               }
            }
         }
      }
   }

   public function invoice($id)
   {
      $process = RequestProcess::findOrFail($id);

      if ($process->status != '2' || $process->invoice)
         return redirect()->back()->with('error', 'Gagal Generate Invoice, Status tidak valid atau Invoice telah dibuat!.');

      try {
         $invoicePurchase = new RequestProcessInvoice();
         $invoicePurchase->purchase_order_id = $process->id;
         $invoicePurchase->payment_status = '0';
         $invoicePurchase->save();

         if ($process->transport) {
            $invoiceTransport = new TransportInvoice();
            $invoiceTransport->transport_id = $process->transport->id;
            $invoiceTransport->payment_status = '0';
            $invoiceTransport->save();

            $process->transport->generate_invoice = 1;
            $process->transport->save();
         }

         $process->generate_invoice = 1;
         $process->save();

         return redirect()->route('request-process.setting', $id)->with('success', 'Generate Invoice untuk Pembelian Principal kode ' . $process->code . ' berhasil dilakukan.')
            ->with('redirect_hash', 'invoices');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal Generate Invoice, Error: ' . $e->getMessage())->with('redirect_hash', 'invoices');
      }
   }

   public function invoiceClientPartial($id)
   {
      $process = RequestProcess::findOrFail($id);

      if ($process->status != '2' || !$process->invoice)
         return redirect()->back()->with('error', 'Gagal Generate Invoice, Status tidak valid atau Invoice Pemrosesan Permintaan Client belum dibuat!.')->with('redirect_hash', 'invoices');

      if ($process->requestOrder->invoice && !$process->requestOrder->invoice->RequestProcess)
         return redirect()->back()->with('error', 'Gagal Generate Invoice Partial, Sudah ada Invoice Secara Keseluruhan!.')->with('redirect_hash', 'invoices');

      try {
         $invoiceClient = new RequestOrderInvoice();
         $invoiceClient->request_order_id = $process->requestOrder->id;
         $invoiceClient->purchase_order_id = $process->id;
         $invoiceClient->payment_status = '0';
         $invoiceClient->save();

         return redirect()->route('request-process.setting', $id)->with('success', 'Generate Partial Invoice Client untuk Pembelian Principal kode ' . $process->code . ' berhasil dilakukan.')
            ->with('redirect_hash', 'invoices');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal Generate Partial Invoice, Error: ' . $e->getMessage())->with('redirect_hash', 'invoices');
      }
   }

   public function destroy($id)
   {
      try {
         $process = RequestProcess::find($id);
         if ($process->status != '0') {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pemrosesan Permintaan Client, Error: Status tidak diizinkan untuk dihapus');
         }
         $process->delete();

         return redirect()->route('request-process.index')->with('success', 'Pemrosesan Permintaan Client ' . $process->code . ' berhasil dihapus.');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pemrosesan Permintaan Client, Error: ' . $e->getMessage());
      }
   }
}
