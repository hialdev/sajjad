<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class InvoiceController extends Controller
{
    /**
     * Display the invoice preview
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function preview(Request $request)
    {
        try {
            $data = $this->getInvoiceData($request);
            
            return view('invoice.preview', compact('data'));
        } catch (Exception $e) {
            Log::error('Invoice preview error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menampilkan preview invoice');
        }
    }

    /**
     * Generate and download PDF invoice
     *
     * @param Request $request
     * @return Response
     */
    public function generatePdf(Request $request)
    {
        try {
            $data = $this->getInvoiceData($request);
            
            // Generate PDF using DomPDF
            $pdf = Pdf::loadView('invoices.cod', $data);
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Set options for better rendering
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
            ]);
            
            $filename = 'invoice_' . ($data['invoice']['order_number'] ?? 'ORDER_' . date('YmdHis')) . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Stream PDF invoice to browser
     *
     * @param Request $request
     * @return Response
     */
    public function streamPdf($id, Request $request)
    {
        try {
            $request->merge(['id' => $id]);
            $data = $this->getInvoiceData($request);
            
            // $pdf = Pdf::loadView('invoices.cod', $data);
            // $pdf->setPaper('A4', 'portrait');

            $pdf = Pdf::loadView('invoices.receipt', $data);
            $pdf->setPaper([0, 0, 226.77, 800], 'portrait');
            
            $filename = 'invoice_' . ($data['invoice']['order_number'] ?? 'ORDER_' . date('YmdHis')) . '.pdf';
            
            return $pdf->stream($filename);
            
        } catch (Exception $e) {
            Log::error('PDF stream error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menampilkan PDF: ' . $e->getMessage());
        }
    }

    /**
     * Save PDF to storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePdf(Request $request)
    {
        try {
            $data = $this->getInvoiceData($request);
            
            // $pdf = Pdf::loadView('invoices.cod', $data);
            // $pdf->setPaper('A4', 'portrait');

            $pdf = Pdf::loadView('invoices.receipt', $data);
            $pdf->setPaper([0, 0, 226.77, 800], 'portrait');
            
            $filename = 'invoices/invoice_' . ($data['invoice']['order_number'] ?? 'ORDER_' . date('YmdHis')) . '.pdf';
            
            // Save to storage
            Storage::put($filename, $pdf->output());
            
            return response()->json([
                'success' => true,
                'message' => 'PDF berhasil disimpan',
                'filename' => $filename,
                'url' => Storage::url($filename)
            ]);
            
        } catch (Exception $e) {
            Log::error('PDF save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send invoice via email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000'
        ]);

        try {
            $data = $this->getInvoiceData($request);
            
            // Generate PDF
            // $pdf = Pdf::loadView('invoices.cod', $data);
            // $pdf->setPaper('A4', 'portrait');

            $pdf = Pdf::loadView('invoices.receipt', $data);
            $pdf->setPaper([0, 0, 226.77, 800], 'portrait');

            $filename = 'invoice_' . ($data['invoice']['order_number'] ?? 'ORDER_' . date('YmdHis')) . '.pdf';
            
            // Email data
            $emailData = [
                'invoice_data' => $data,
                'subject' => $request->subject ?? 'Invoice #' . ($data['invoice']['order_number'] ?? 'ORDER'),
                'custom_message' => $request->message ?? ''
            ];
            
            // Send email with PDF attachment
            Mail::send('emails.invoice', $emailData, function($message) use ($request, $emailData, $pdf, $filename) {
                $message->to($request->email)
                       ->subject($emailData['subject'])
                       ->attachData($pdf->output(), $filename, [
                           'mime' => 'application/pdf',
                       ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil dikirim ke ' . $request->email
            ]);
            
        } catch (Exception $e) {
            Log::error('Email send error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoice data from request or database
     *
     * @param Request $request
     * @return array
     */
    private function getInvoiceData(Request $request)
      {
         $orderId = $request->input('id'); // dari URL atau form
         $order = CustomerOrder::with(['products.product', 'customer'])->findOrFail($orderId);

         // Ambil data produk
         $items = $order->products->map(function ($item) {
            return [
                  'name' => $item->product->name ?? '-',
                  'length' => $item->length ?? '-',
                  'quantity' => $item->qty,
                  'price' => $item->price_sale,
                  'subtotal' => $item->price_sale * $item->qty,
            ];
         })->toArray();

         $totals = $this->calculateTotals($items, $order->tax ?? 11);

         return [
            'invoice' => [
                  'order_number' => $order->code,
                  'date' => $order->created_at->format('d M Y H:i') . ' WIB',
                  'due_date' => $order->created_at->addDays(30)->format('d M Y'),
            ],
            'company' => [
                  'name' => setting('company.legal-name') ?? config('app.company_name', 'Sajjad Zamzami Carpet'),
                  'address' => setting('company.address') ?? config('app.company_address', 'Jl. K.H. Mas Mansyur No.14'),
                  'city' => setting('company.city') ?? config('app.company_city', 'Jakarta Pusat'),
                  'phone' => setting('company.phone') ?? config('app.company_phone', '6285156546132'),
                  'email' => setting('company.cp-email') ?? config('app.company_email', 'sajjadzamzami@gmail.com'),
                  'contact_person' => setting('company.cp-name') ?? 'Sajjad Zamzami',
            ],
            'customer' => [
                  'name' => $order->customer->name ?? '-',
                  'address' => $order->customer->address ?? '-',
                  'phone' => $order->customer->phone ?? '',
                  'email' => $order->customer->email ?? '',
            ],
            'items' => $items,
            'totals' => $totals,
            'notes' => $order->description ?? '',
            'payment_info' => [
                  'bank_name' => $order->payment->bank_name ?? '',
                  'account_number' => $order->payment->account_number ?? '',
                  'account_name' => $order->payment->account_name ?? '',
                  'status' => $order->is_finished ? 'Paid' : 'Waiting',
                  'proof_image' => $order->payment_receipt ? '/storage/'.$order->payment_receipt : null,
            ]
         ];
      }

      private function getInvoiceDataRO(Request $request)
      {
         $orderId = $request->input('id'); // dari URL atau form
         $order = CustomerOrder::with(['products.product', 'customer'])->findOrFail($orderId);

         // Ambil data produk
         $items = $order->products->map(function ($item) {
            return [
                  'name' => $item->product->name ?? '-',
                  'length' => $item->length ?? '-',
                  'quantity' => $item->qty,
                  'price' => $item->price_sale,
                  'subtotal' => $item->price_sale * $item->qty,
            ];
         })->toArray();

         $totals = $this->calculateTotals($items, $order->tax ?? 11);

         return [
            'invoice' => [
                  'order_number' => $order->code,
                  'date' => $order->created_at->format('d M Y H:i') . ' WIB',
                  'due_date' => $order->created_at->addDays(30)->format('d M Y'),
            ],
            'company' => [
                  'name' => setting('company.legal-name') ?? config('app.company_name', 'Sajjad Zamzami Carpet'),
                  'address' => setting('company.address') ?? config('app.company_address', 'Jl. K.H. Mas Mansyur No.14'),
                  'phone' => setting('company.phone') ?? config('app.company_phone', '6285156546132'),
                  'email' => setting('company.cp-email') ?? config('app.company_email', 'sajjadzamzami@gmail.com'),
                  'contact_person' => setting('company.cp-name') ?? 'Sajjad Zamzami',
            ],
            'customer' => [
                  'name' => $order->customer->name ?? '-',
                  'address' => $order->customer->address ?? '-',
                  'phone' => $order->customer->phone ?? '',
                  'email' => $order->customer->email ?? '',
            ],
            'items' => $items,
            'totals' => $totals,
            'notes' => $order->description ?? '',
            'payment_info' => [
                  'bank_name' => $order->payment->bank_name ?? '',
                  'account_number' => $order->payment->account_number ?? '',
                  'account_name' => $order->payment->account_name ?? '',
                  'status' => $order->is_finished ? 'Paid' : 'Waiting',
                  'proof_image' => $order->payment_receipt ? '/storage/'.$order->payment_receipt : null,
            ]
         ];
      }

      private function calculateTotals(array $items, float $taxRate = 11)
      {
         $subtotal = 0;
         
         foreach ($items as $item) {
               $subtotal += $item['subtotal'] ?? 0;
         }
         
         $taxAmount = $subtotal * ($taxRate / 100);
         $grandTotal = $subtotal + $taxAmount;
         
         return [
               'subtotal' => $subtotal,
               'tax_rate' => $taxRate,
               'tax_amount' => $taxAmount,
               'grand_total' => $grandTotal
         ];
      }

}