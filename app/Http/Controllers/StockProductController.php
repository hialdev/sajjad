<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMeter;
use App\Models\StockMovement;
use App\Models\StockMovementProduct;
use Illuminate\Http\Request;

class StockProductController extends Controller
{
    public function store($id, Request $request){
        $request->merge([
            'movement_id' => $id,
            'price_buy' => array_map(fn($value) => $value !== null ? parseRupiah($value) : 0, $request->get('price_buy', [])),
        ]);

        $request->validate([
            'product_id'   => 'required|array',
            'product_id.*' => 'required|string|exists:osano.products,id',
            'qty'          => 'required|array',
            'qty.*'        => 'required|numeric|min:1',
            'desc.*'       => 'nullable|string|min:3'
        ]);

        $move = StockMovement::findOrFail($id);

        try {
            if($move->payment_status != 0 && $move->requestOrder->status > 1){
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Produk Pembelian Ke Principal, Error: Status Pembelian Ke Principal tidak diizinkan untuk perubahan / hapus.');
            }
            if($move->products){
                $move->products()->delete();
            }

            foreach ($request->get('product_id') as $key => $product_id) {
                if (!$this->checkBelowRemaining($move, $product_id, $request->get('qty')[$key])) {
                    return redirect()->back()->withInput()->with('error', $request->get('qty')[$key].' qty distribusi pada salah satu produk melebihi kuantitas yang tersedia.')->with('redirect_hash', 'produk');
                }
            }

            foreach ($request->get('product_id') as $key => $product_id) {
                StockMovementProduct::create([
                    'movement_id' => $request->get('movement_id'),
                    'product_id' => $product_id,
                    'qty' => $request->get('qty')[$key],
                    'description' => $request->get('desc')[$key] ?? '',
                ]);
            }

            session()->forget('distribution_cart_'.$id);
            return redirect()->route('stock.setting', ['id' => $id])->with('success', 'Product Distribusi berhasil disimpan.')->with('redirect_hash', 'produk');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Product Distribusi, Error: '.$e->getMessage())->with('redirect_hash', 'produk');
        }
    }

    private function checkBelowRemaining($move, $productId, $qty){
        $prod = Product::find($productId);
        if ($prod->type->type == 'meteran'){
            $remaining = StockMeter::analyticProductInLocation($move->from_type, $move->from_id, $productId);
            if ((int) $qty > (int) $remaining[0]->qty_remaining) return false;
        }else{
            $remaining = Stock::analyticProductInLocation($move->from_type, $move->from_id, $productId);
            if ((int) $qty > (int) $remaining[0]->stock_remaining) return false;
        }

        return true;
    }
}
