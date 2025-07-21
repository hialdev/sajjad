<?php

namespace App\Http\Controllers;

use App\Models\RequestOrderProduct;
use App\Models\RequestProcess;
use App\Models\RequestProcessProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestProcessProductController extends Controller
{
   public function store($id, Request $request)
   {
      $request->merge([
         'request_process_id' => $id,
      ]);

      $request->validate([
         'product_id.*' => 'required|string|exists:osano.products,id',
         'process_qty.*' => 'required|numeric|min:1',
         'from_id.*' => [
            'required',
            'uuid',
            function ($attribute, $value, $fail) {
               $isValid = DB::connection('osano')->table('stores')->where('id', $value)->exists()
                  || DB::connection('osano')->table('warehouses')->where('id', $value)->exists();
               if (!$isValid) {
                  $fail('The selected ' . $attribute . ' is invalid.');
               }
            },
         ],
      ]);

      try {
         DB::beginTransaction();

         $process = RequestProcess::findOrFail($id);
         $prodanalytics = collect($process->requestOrder->getProcessingAnalytics());

         foreach ($request->get('product_id') as $key => $productId) {
            $processQty = $request->get('process_qty')[$key];
            $analytic = $prodanalytics->get($productId);

            if (!$analytic || $analytic['remaining_qty'] < $processQty) {
               return redirect()->back()->withInput()
                  ->with('error', 'Qty ' . $processQty . ' melebihi stock belum diproses (' . ($analytic['remaining_qty'] ?? 0) . ')')
                  ->with('redirect_hash', 'produk');
            }
         }

         foreach ($request->get('product_id') as $key => $productId) {
            $processQty = $request->get('process_qty')[$key];
            $fromId = $request->get('from_id')[$key];

            $warehouse = \App\Models\Warehouse::find($fromId);
            $fromType = $warehouse ? 'warehouse' : 'store';
            $model = $warehouse ?? \App\Models\Store::find($fromId);

            $availableStock = $model->getProductRemaining($productId);

            if ($processQty > $availableStock) {
               return redirect()->back()->withInput()
                  ->with('error', 'Qty ' . $processQty . ' melebihi stok tersedia (' . $availableStock . ') di lokasi asal.')
                  ->with('redirect_hash', 'produk');
            }

            $requestOrder = $process->requestOrder;
            $reqProduct = $requestOrder->products()->where('product_id', $productId)->first();

            $existing = RequestProcessProduct::where([
               ['product_id', '=', $productId],
               ['request_process_id', '=', $process->id],
            ])->first();

            $data = [
               'request_process_id' => $id,
               'from_type' => $fromType,
               'from_id' => $fromId,
               'product_id' => $productId,
               'length' => $reqProduct->length ?? null,
               'qty' => $processQty,
            ];

            if ($existing) {
               if ($existing->remaining_qty < $processQty) {
                  return redirect()->back()->withInput()
                     ->with('error', 'Qty ' . $processQty . ' melebihi stok belum diproses (' . $existing->remaining_qty . ')')
                     ->with('redirect_hash', 'produk');
               }
               $existing->update($data);
            } else {
               RequestProcessProduct::create($data);
            }
         }

         session()->forget('cart_' . $id);
         DB::commit();

         return redirect()->route('request-process.setting', ['id' => $id])
            ->with('success', 'Produk Pemrosesan berhasil disimpan.')
            ->with('redirect_hash', 'produk');
      } catch (\Exception $e) {
         DB::rollBack();
         return redirect()->back()->withInput()
            ->with('error', 'Gagal menyimpan produk. Error: ' . $e->getMessage())
            ->with('redirect_hash', 'produk');
      }
   }
}
