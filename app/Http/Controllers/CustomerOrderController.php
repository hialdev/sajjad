<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Partner;
use App\Models\PartnerDebt;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
   public function index(Request $request)
   {
      $filter = (object) [
         'q' => $request->get('search', ''),
         'field' => $request->get('field', 'code'),
         'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
      ];

      $customer_orders = CustomerOrder::where('code', 'LIKE', '%' . $filter->q . '%')->orderBy($filter->field, $filter->order)
         ->get();

      return view('customer_orders.index', compact('customer_orders', 'filter'));
   }

   public function add()
   {
      $users = User::orderBy('name', 'ASC')->get();
      $cities = City::orderBy('city_name', 'ASC')->get();
      $customers = Customer::orderBy('name', 'ASC')->get();
      return view('customer_orders.add', compact('customers', 'users', 'cities'));
   }

   public function store(Request $request)
   {
      $request->validate([
         'date' => 'required|date',
         'customer_id' => 'nullable|string|exists:osano.customers,id',
         'user_id' => 'nullable|string|exists:users,id',
         'description' => 'nullable|string|min:4',
      ]);
      try {
         $cod = new CustomerOrder();
         $cod->date = $request->get('date');
         $cod->customer_id = $request->get('customer_id');
         $cod->user_id = $request->get('user_id');
         $cod->description = $request->get('description');
         $cod->save();

         return redirect()->route('customer-order.setting', ['id' => $cod->id])->with('success', 'Penjualan Instant ke Pelanggan ' . $cod->code . ' berhasil diperbarui, sekarang tentukan produknya.')->with('redirect_hash', 'produk');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Penjualan Instant ke Pelanggan, Error: ' . $e->getMessage())->with('redirect_hash', 'produk');
      }
   }

   public function setting($id, Request $request)
   {
      $cod = CustomerOrder::find($id);
      $users = User::orderBy('name', 'ASC')->get();
      $cities = City::orderBy('city_name', 'ASC')->get();
      $stores = Store::orderBy('name', 'ASC')->get();
      $partners = Partner::orderBy('name', 'ASC')->get();
      $customers = Customer::orderBy('name', 'ASC')->get();
      $payments = Payment::where('is_active', 1)->orderBy('bank_name', 'ASC')->get();

      $filter = (object) [
         'q' => $request->get('search') ?? '',
      ];
      $products = Product::where('name', 'LIKE', "%{$filter->q}%")->orderBy('name', 'ASC')->paginate(9);
      if (count(session()->get('cod_cart_' . $id, [])) == 0) {
         $this->refetch($id, new Request());
      }

      if ($request->filled('hashProduct')) {
         session(['redirect_hash' => 'produk']);
      }

      return view('customer_orders.setting', compact('customers', 'cities', 'users', 'filter', 'products', 'cod', 'stores', 'partners', 'payments'));
   }

   public function update($id, Request $request)
   {
      $request->validate([
         'date' => 'required|date',
         'customer_id' => 'nullable|string|exists:osano.customers,id',
         'user_id' => 'nullable|string|exists:users,id',
         'description' => 'nullable|string|min:4',
      ]);
      try {
         $cod = CustomerOrder::find($id);
         $cod->date = $request->get('date');
         $cod->customer_id = $request->get('customer_id');
         $cod->user_id = $request->get('user_id');
         $cod->description = $request->get('description');
         $cod->save();

         return redirect()->route('customer-order.setting', ['id' => $cod->id])->with('success', 'Penjualan Instant ke Pelanggan ' . $cod->code . ' berhasil ditambahkan, sekarang tentukan produknya.')->with('redirect_hash', 'produk');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Penjualan Instant ke Pelanggan, Error: ' . $e->getMessage())->with('redirect_hash', 'produk');
      }
   }

   public function addCart($id, Request $request)
   {
      $cod = CustomerOrder::find($id);
      if ($cod->is_finished != '0') return redirect()->back()->with('error', 'Pembelian telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

      $request->validate([
         'product_id' => 'required|exists:osano.products,id',
      ]);

      $cart = session()->get("cod_cart_" . $id, []);

      if (isset($cart[$request->get('product_id')])) {
         // Jika produk sudah ada di cart, tambahkan jumlahnya
         $product = Product::find($request->product_id);
         if ($product->type->type == 'satuan') {
            $cart[$request->get('product_id')]['qty'] += $request->get('qty', 1);
         }else{
            $cart[\Illuminate\Support\Str::uuid().'_'.$request->get('product_id')] = [
               'id' => $request->get('product_id'),
               'qty' => 1,
               'length' => 1,
               'store_id' => '',
               'partner_id' => '',
               'is_from_partner' => 0,
               'price_buy' => 0,
               'price_sale' => 0,
               'description' => '',
            ];
         }
      } else {
         // Jika produk belum ada di cart, tambahkan dengan qty default 1
         $cart[$request->get('product_id')] = [
            'id' => $request->get('product_id'),
            'qty' => 1,
            'length' => 1,
            'store_id' => '',
            'partner_id' => '',
            'is_from_partner' => 0,
            'price_buy' => 0,
            'price_sale' => 0,
            'description' => '',
         ];
      }

      session()->put('cod_cart_' . $id, $cart);

      return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang')->with('redirect_hash', 'produk');
   }

   public function removeCart($id, Request $request)
   {
      $cod = CustomerOrder::find($id);
      if ($cod->is_finished != '0') return redirect()->back()->with('error', 'Pembelian telah selesai, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

      $request->validate([
         'product_id' => 'required|exists:osano.products,id',
      ]);

      $cart = session()->get("cod_cart_" . $id, []);

      if (isset($cart[$request->get('product_id')])) {
         unset($cart[$request->get('product_id')]);
         session()->put("cod_cart_" . $id, $cart);
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
      $cod = CustomerOrder::find($id);
      session()->forget('cod_cart_' . $id);
      $cart = [];
      if ($cod->products) {
         foreach ($cod->products as $codproduct) {
            $cart[\Illuminate\Support\Str::uuid().'_'.$codproduct->product_id] = [
               'id' => $codproduct->product_id,
               'qty' => $codproduct->qty,
               'length' => $codproduct->length,
               'is_from_partner' => $codproduct->is_from_partner,
               'partner_id' => $codproduct->partner_id,
               'store_id' => $codproduct->store_id,
               'description' => $codproduct->description,
               'price_buy' => $codproduct->price_buy,
               'price_sale' => $codproduct->price_sale,
            ];
         }
      }
      session()->put('cod_cart_' . $id, $cart);

      if ($request->ajax()) {
         return response()->json([
            'success' => true,
            'message' => 'Mereset Ulang dengan data Produk Penjualan Instant ke Pelanggan',
         ], 200);
      }
   }

   public function confirmPayment($id, Request $request)
   {
      $cod = CustomerOrder::find($id);
      if ($cod->is_finished != '0') return redirect()->back()->with('error', 'Pembelian telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

      $request->validate([
         'payment_id' => 'required|exists:osano.payments,id',
         'image' => 'required|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
      ]);
      try {
         if ($request->hasFile('image')) {
            $cod->payment_receipt = $request->file('image')->store('payments', 'public');
         }
         $cod->payment_id = $request->get('payment_id');
         $cod->is_finished = '1';
         $cod->save();

         $this->updateStockForCOD($cod);

         if($cod){
            $codProducts = $cod->products()->where('is_from_partner', 1)->get();
            foreach ($codProducts as $cprod) {
               $pdebt = new PartnerDebt();
               $pdebt->customer_order_id = $cod->id;
               $pdebt->customer_order_product_id = $cprod->id;
               $pdebt->total_debt = $cprod->price_buy * $cprod->qty;
               $pdebt->save();
            }
         }

         return redirect()->route('customer-order.setting', ['id' => $cod->id])->with('success', 'Pembayaran untuk Penjualan Instant ke Pelanggan ' . $cod->code . ' berhasil diperbarui.')->with('redirect_hash', 'partner');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pembayaran Penjualan Instant ke Pelanggan, Error: ' . $e->getMessage())->with('redirect_hash', 'produk');
      }
   }

   private function updateStockForCOD(CustomerOrder $cod)
   {
      foreach ($cod->products as $product) {
         if ($product->is_from_partner != 0) {
            // Barang dari partner tidak mengurangi stok
            continue;
         }

         if ($product->product->type->type === 'satuan') {
            $stock = new \App\Models\Stock();
            $stock->product_id = $product->product_id;
            $stock->nowin_type = 'store';
            $stock->nowin_id = $product->store_id;
            $stock->qty = $product->qty;
            $stock->trx_type = 'out';
            $stock->save();
         } else {
            // Meteran
            $stocks = \App\Models\StockMeter::where([
                  ['product_id', '=', $product->product_id],
                  ['nowin_type', '=', 'store'],
                  ['nowin_id', '=', $product->store_id],
                  ['length', '>=', $product->length],
                  ['sold_length', '<', $product->length],
                  ['is_onway', '=', 0]
               ])
               ->limit($product->qty)
               ->get();

            if ($stocks->count() < $product->qty) continue;

            foreach ($stocks as $stock) {
               $stock->sold_length = $product->length;
               $stock->save();
            }
         }
      }
   }

   public function destroy($id)
   {
      try {
         $cod = CustomerOrder::find($id);
         if ($cod->is_finished != '0') {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Penjualan Instant ke Pelanggan, Error: Status tidak diizinkan untuk dihapus');
         }
         $cod->delete();
         session()->forget('cod_cart_' . $id);

         return redirect()->route('customer-order.index')->with('success', 'Penjualan Instant ke Pelanggan ' . $cod->code . ' berhasil dihapus.');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal menghapus Penjualan Instant ke Pelanggan, Error: ' . $e->getMessage());
      }
   }

   // -------------------------
   // Partner Debt
   // -------------------------

   public function pdebtIndex(Request $request)
   {
      $filter = (object) [
         'q' => $request->get('search', ''),
         'field' => $request->get('field', 'id'), // Use a valid column in partner_debts
         'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
      ];

      $partner_debts = PartnerDebt::whereHas('customerOrderProduct.customerOrder', function ($query) use ($filter) {
         $query->where('code', 'LIKE', '%' . $filter->q . '%');
      })->orderBy($filter->field, $filter->order)->get();
      
      return view('customer_orders.partner_debts.index', compact('partner_debts', 'filter'));
   }

   public function pdebtPay($id, $debt_id, Request $request){
      $pdebt = PartnerDebt::find($debt_id);
      if (!$pdebt) {
         return redirect()->back()->with('error', 'Data Hutang Partner tidak ditemukan');
      }

      $request->validate([
         'partner-image' => 'required|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
         'partner-description' => 'nullable|string',
      ]);

      try {
         if ($request->hasFile('partner-image')) {
            $pdebt->image = $request->file('partner-image')->store('partner_debts', 'public');
         }
         $pdebt->description = $request->get('partner-description');
         $pdebt->is_paid = 1;
         $pdebt->save();

         return redirect()->route('customer-order.setting', $id)->with('success', 'Pembayaran Hutang Partner '.$pdebt->partner->name.' berhasil disimpan.')->with('redirect_hash', 'partner');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Pembayaran Hutang Partner, Error: ' . $e->getMessage());
      }
   }
}
