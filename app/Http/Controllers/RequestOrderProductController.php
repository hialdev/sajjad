<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RequestOrder;
use App\Models\RequestOrderProduct;
use Illuminate\Http\Request;

class RequestOrderProductController extends Controller
{
    public function store($id, Request $request){
        $request->merge([
            'request_order_id' => $id,
            'price_sale' => array_map(fn($value) => $value !== null ? parseRupiah($value) : 0, $request->get('price_sale', [])),
        ]);
        $request->validate([
            'product_id.*' => 'required|string|exists:osano.products,id',
            'qty.*' => 'required|numeric|min:1',
            'length.*' => 'nullable|numeric|min:1',
            'price_sale.*' => 'required|min:1',
            'total_price' => 'required|min:1',
            'tax' => 'required|min:1',
            'total_price_taxed' => 'required|min:1',
        ]);
        try {
            $reqorder = RequestOrder::find($id);
            if($reqorder->status != 0){
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Produk Permintaan Client, Error: Status tidak diizinkan untuk diperbarui');
            }

            foreach ($request->get('product_id') as $key => $productId){
               $product = Product::find($productId);
               $qty = $request->get('qty')[$key];
               $length = $request->get('length')[$key] ?? null;

               if ($product->type->type === 'meteran') {
                  $available = \App\Models\StockMeter::where('product_id', $product->id)
                     ->where('is_onway', 0)
                     ->whereRaw('(length - COALESCE(sold_length, 0)) >= ?', [$length])
                     ->count();

                  if ($available < $qty) {
                     return redirect()->back()->withInput()->with('error', "Stok tidak cukup untuk produk '{$product->name}', hanya tersedia $available stok dengan panjang minimal $length cm.");
                  }
               } else {
                  if ($product->stock_count < $qty) {
                     return redirect()->back()->withInput()->with('error', "Qty {$qty} melebihi stok tersedia ({$product->stock_count}) untuk produk '{$product->name}'.");
                  }
               }
            }

            foreach ($request->get('product_id') as $key => $product_id) {
                RequestOrderProduct::create([
                    'request_order_id' => $request->get('request_order_id'),
                    'product_id' => $product_id,
                    'length' => $request->get('length')[$key] ?? null,
                    'price_sale' => $request->get('price_sale')[$key],
                    'qty' => $request->get('qty')[$key],
                ]);
            }

            $reqorder->total_price = $request->get('total_price');
            $reqorder->tax = $request->get('tax');
            $reqorder->total_price_taxed = $request->get('total_price_taxed');
            $reqorder->save();

            session()->forget('cart_'.$id);
            return redirect()->route('request-order.setting', ['id' => $id])->with('success', 'Product Permintaan Client (Request Order) berhasil disimpan, sekarang kelola Lampirannya.')->with('redirect_hash', 'lampiran');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Product Permintaan Client (Request Order), Error: '.$e->getMessage())->with('redirect_hash', 'lampiran');
        }
    }
}
