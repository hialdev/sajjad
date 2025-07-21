<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderProduct;
use App\Models\PurchaseReceive;
use App\Models\PurchaseReceiveProduct;
use Illuminate\Http\Request;

class PurchaseReceiveProductController extends Controller
{
    public function add($id, Request $request){
        $receive = PurchaseReceive::findOrFail($id);
        if($receive->is_lock) return redirect()->back()->with('error', 'Gagal menyimpan produk, Status Penerimaan telah Dikunci!')->with('redirect_hash', 'produk');
        
        if ($receive->purchase->isFullyReceived()){
            $receive->delete();
            return redirect()->route('receive.index')->with('error', 'Semua produk dari Pembelian '.$receive->purchase->code.' telah diterima! Menghapus Penerimaan '.$receive->code);
        }
        
        $request->validate([
            'is_lock' => 'nullable|boolean',
            'purchase_product_id' => 'array|required',
            'purchase_product_id.*' => 'required|exists:osano.purchase_order_products,id',
            'receive_qty' => 'array|required',
            'receive_qty.*' => 'numeric|required|min:1',
            'description.*' => 'string|nullable',
        ]);

        try {
            if ($this->hitMaxQty($request->get('purchase_product_id'), $request->get('receive_qty'))){
                return redirect()->back()->with('error', 'Gagal menyimpan produk, Qty yang diterima melebihi qty yang dipesan, perbaiki dan teliti!')->with('redirect_hash', 'produk');
            }

            foreach ($request->get('purchase_product_id') as $i => $val) {
                $purchaseProduct = PurchaseOrderProduct::find($val);
                
                if (!isset($request->get('receive_qty')[$i]) || $request->get('receive_qty')[$i] === null) {
                    continue; // Skip baris yang qty-nya tidak dikirim
                }

                $receiveQty = $request->get('receive_qty')[$i];
                $remaining = $purchaseProduct->remainingReceive;

                // Verifikasi apakah masih ada sisa yang bisa diterima
                if ($receiveQty > $remaining) {
                    return redirect()->back()->with('error',"Terdapat kelebihan qty yang diterima (dimasukan {$receiveQty} dari tersisa {$remaining}).")->with('redirect_hash', 'produk');
                }

                $prod = new PurchaseReceiveProduct();
                $check = PurchaseReceiveProduct::where('purchase_receive_id', $id)->where('purchase_product_id', $val)->first();
                if($check) $prod = $check;

                $prod->purchase_receive_id = $id;
                $prod->purchase_product_id = $val;
                $prod->receive_qty = $remaining <= 0 ? 0 : $receiveQty;
                $prod->description = $request->get('description')[$i];
                $prod->save();
            }

            $receive->is_lock = $request->get('is_lock');
            $receive->save();

            return redirect()->back()->with('success', 'Berhasil menyimpan data Penerimaan Produk, untuk Penerimaan '.$receive->code.' dan Pemesananan Barang '.$receive->purchase->code)->with('redirect_hash', 'produk');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Oops ada kesalahan, Error : '.$e->getMessage())->with('redirect_hash', 'produk');
        }
    }

    private function hitMaxQty($purchase_product_ids, $receive_qtys){
        foreach ($purchase_product_ids as $i => $val){
            $purch_prod = PurchaseOrderProduct::find($val);
            $maxQty = $purch_prod->qty;
            $receiveQty = $receive_qtys[$i] ?? 0;
            if($receiveQty > $maxQty) return true;
        }
        return false;
    }
}
