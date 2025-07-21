<?php

namespace App\Http\Controllers;

use App\Models\RequestOrderInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestOrderInvoiceController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'code',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $roInvoices = RequestOrderInvoice::query()
            ->where('code', 'LIKE', '%' . $filter->q . '%');
        $roInvoices->orderBy($filter->field, $filter->order);
        $roInvoices = $roInvoices->get();

        return view('request_orders.invoice.index', compact('filter', 'roInvoices'));
    }

    public function show($id){
        $roInvoice = RequestOrderInvoice::findOrFail($id);
        
        return view('request_orders.invoice.show', compact('roInvoice'));
    }

    public function destroy($id){
        try{
            $roInvoice = RequestOrderInvoice::findOrFail($id);
            if($roInvoice->trx)
                return redirect()->back()->with('error', 'Tidak dapat menghapus Invoice, Error: Invoice sudah di catat ke Jurnal Akutansi.');
            if($roInvoice->payments->count() > 0)
                return redirect()->back()->with('error', 'Tidak dapat menghapus Invoice, Error: Terdapat Pembayaran yang dilakukan didalamnya.');

            $roInvoice->delete();
        }catch (\Exception $e){
            return redirect()->back()->with('error', 'Gagal menghapus Invoice, Error: '.$e->getMessage());
        }
    }

    public function streamPdf($id)
    {
        try {
            // Ambil data invoice beserta relasi yang dibutuhkan
            $invoice = RequestOrderInvoice::with([
                'requestOrder.client',
                'requestOrder.pic',
                'requestOrder.products.product'
            ])->findOrFail($id);

            $requestOrder = $invoice->requestOrder;

            // Persiapkan data produk
            $items = $requestOrder->products->map(function ($item) {
                return [
                    'name' => $item->product->name ?? '-',
                    'length' => $item->length,
                    'quantity' => $item->qty,
                    'price' => $item->price_sale,
                    'subtotal' => $item->price_sale * $item->qty,
                ];
            })->toArray();

            // Hitung total
            $subtotal = collect($items)->sum('subtotal');
            $taxRate = $requestOrder->tax ?? 11;
            $taxAmount = $subtotal * ($taxRate / 100);
            $grandTotal = $subtotal + $taxAmount;

            $data = [
                'invoice' => [
                    'code' => $invoice->code,
                    'payment_status' => $invoice->payment_status,
                    'order_number' => $requestOrder->code,
                    'date' => Carbon::parse($requestOrder->date)->format('d M Y'),
                    'description' => $requestOrder->description,
                ],
                'client' => [
                    'name' => $requestOrder->client->name ?? '-',
                    'address' => $requestOrder->client->address ?? '-',
                    'phone' => $requestOrder->client->phone ?? '',
                    'email' => $requestOrder->client->email ?? '',
                ],
                'items' => $items,
                'totals' => [
                    'subtotal' => $subtotal,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'grand_total' => $grandTotal,
                ]
            ];

            // Render PDF
            $pdf = Pdf::loadView('invoices.ro', $data);
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('invoice_' . $invoice->code . '.pdf');

        } catch (Exception $e) {
            Log::error('Gagal stream PDF RequestOrderInvoice: ' . $e->getMessage());
            return back()->with('error', 'Gagal menampilkan PDF: ' . $e->getMessage());
        }
    }
}
