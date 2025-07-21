<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\CustomerOrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOrderProductController extends Controller
{
   public function store($id, Request $request)
   {
      $request->merge([
         'customer_order_id' => $id,
      ]);

      $pSale = array_map('parseRupiah', $request->get('price_sale', []));
      $pBuy = array_map('parseRupiah', $request->get('price_buy', []));

      $request->merge([
         'price_sale' => $pSale,
         'price_buy' => $pBuy,
      ]);

      $request->validate([
         'product_id.*' => 'required|string|exists:osano.products,id',
         'store_id.*' => 'nullable|string|exists:osano.stores,id',
         'partners_id.*' => 'nullable|string|exists:osano.partnerss,id',
         'is_from_partner.*' => 'nullable|boolean',
         'desc.*' => 'nullable|string',
         'length.*' => 'nullable|numeric|min:1',
         'qty.*' => 'required|numeric|min:1',
         'price_sale.*' => 'required|numeric|min:1',
         'total_price' => 'required|numeric|min:1',
         'tax' => 'nullable|numeric|min:0',
         'total_price_taxed' => 'nullable|numeric|min:0',
      ]);

      try {
         DB::beginTransaction();
         // Check Price sale Partner tidak boleh kurang dari price buy
         foreach ($request->get('is_from_partner') as $key => $fromPartner){
            $product = \App\Models\Product::findOrFail($request->get('product_id')[$key]);
            $type = $product->type->type;
            $qty = $request->get('qty')[$key];
            $length = $request->get('length')[$key] ?? null;
            $store = $request->get('store_id')[$key];
            $partner = $request->get('partner_id')[$key];
            $priceBuy = $request->get('price_buy')[$key];
            $priceSale = $request->get('price_sale')[$key];

            if (($partner && $store) || (!$partner && !$store)) {
               return redirect()->back()->withInput()
                  ->with('error', 'Silakan pilih salah satu, dari Partner atau Store.')
                  ->with('redirect_hash', 'produk');
            }

            if ($priceSale <= 0){
               return redirect()->back()->withInput()
                  ->with('error', 'Harga jual tidak boleh kurang dari atau sama dengan 0')
                  ->with('redirect_hash', 'produk');
            }

            if($fromPartner == '1'){
               if($priceBuy > $priceSale){
                  return redirect()->back()->withInput()
                     ->with('error', 'Anda merugi karena Harga jual '. formatRupiah($priceSale) . ' lebih kecil dari harga beli ' . formatRupiah($priceBuy))
                     ->with('redirect_hash', 'produk');
               }
            } else {
               // ❗ Pengecekan stok dari STORE
               if ($store){
                  if ($type === 'meteran') {
                     if (!$length) {
                        return redirect()->back()->withInput()
                           ->with('error', 'Panjang harus diisi untuk produk meteran.')
                           ->with('redirect_hash', 'produk');
                     }

                     // Cek hanya stok yang memiliki panjang tersisa cukup
                     $stocks = $product->findMeterStockByLength($length, 'store', $store);
                     $availableQty = $stocks->count();

                     if ($availableQty < (int) $qty) {
                        return redirect()->back()->withInput()
                           ->with('error', 'Qty ' . $qty . ' melebihi stok roll tersisa (' . $availableQty . ') dengan panjang minimal ' . $length . 'cm')
                           ->with('redirect_hash', 'produk');
                     }
                  } else {
                     $remainQty = $product->getAnalyticLocation('store', $store);
                     $remainQty = $remainQty[0]->stock_remaining ?? 0;

                     if($remainQty < (int) $qty){
                        return redirect()->back()->withInput()
                           ->with('error', 'Qty ' . $qty . ' melebihi stok tersisa (' . $remainQty . ')')
                           ->with('redirect_hash', 'produk');
                     }
                  }
               }
            }
         }

         foreach ($request->get('product_id') as $key => $productId) {
            $product = \App\Models\Product::findOrFail($request->get('product_id')[$key]);
            $length = $request->get('length')[$key];
            $type = $product->type->type;

            if( $type == 'meteran' && ($length == null || $length <= 0)){
               return redirect()->back()->withInput()
                  ->with('error', 'Panjang untuk produk ' . $product->name . ' harus diisi.')
                  ->with('redirect_hash', 'produk');
            }
         }

         $cod = CustomerOrder::findOrFail($id);

         foreach ($request->get('product_id') as $key => $productId) {
            $qty = $request->get('qty')[$key];
            $length = $request->get('length')[$key];
            $price_sale = $request->get('price_sale')[$key];
            $price_buy = $request->get('price_buy')[$key];
            $description = $request->get('desc')[$key];
            
            if (preg_match('/_(.*)$/', $productId, $matches)) {
               $productId = $matches[1];
            } else {
               $productId = $productId;
            }

            $storeId = null;
            $partnerId = null;
            if ($request->get('is_from_partner')[$key] == "0") {
               $storeId = $request->get('store_id')[$key];
            }else{
               $partnerId = $request->get('partner_id')[$key];
            }

            $data = [
               'customer_order_id' => $id,
               'product_id' => $productId,
               'store_id' => $storeId,
               'partner_id' => count($request->get('partner_id')) > 0 && $request->get('is_from_partner')[$key] == "1" ? $partnerId : null,
               'is_from_partner' => count($request->get('partner_id')) > 0 && $request->get('is_from_partner')[$key] == "1" ? 1 : 0,
               'length' => $length ?? null,
               'qty' => $qty,
               'description' => $description,
               'price_sale' => $price_sale,
               'price_buy' => $price_buy ?? null,
            ];
            
            if ($cod->products->count() > 0) {
               $cod->products->each(function($product) {
                  $product->delete();
               });
            }
            
            CustomerOrderProduct::create($data);
         }

         $cod->total_price = $request->get('total_price');
         $cod->tax = $request->get('tax');
         $cod->total_price_taxed = $request->get('total_price_taxed');
         $cod->save();

         session()->forget('cod_cart_' . $id);
         DB::commit();

         return redirect()->route('customer-order.setting', ['id' => $id])
            ->with('success', 'Produk Pemrosesan berhasil disimpan.')
            ->with('redirect_hash', 'pembayaran');

      } catch (\Exception $e) {
         DB::rollBack();
         return redirect()->back()->withInput()
            ->with('error', 'Gagal menyimpan produk. Error: ' . $e->getMessage())
            ->with('redirect_hash', 'produk');
      }
   }
}
