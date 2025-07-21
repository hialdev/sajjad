<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice['order_number'] ?? 'ORDER/212/VII/25' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            width: 100%;
            max-width: 750px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            background-color: #212121;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .info-table,
        .items-table,
        .total-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .items-table th,
        .items-table td,
        .total-table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .items-table th {
            background-color: #9c7f44;
            color: #fff;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <h1>INVOICE</h1>

        <table class="info-table">
            <tr>
                <td><strong>Order:</strong> {{ $invoice['order_number'] }}</td>
                <td><strong>Date:</strong> {{ $invoice['date'] }}</td>
            </tr>
            <tr>
                <td><strong>Customer:</strong> {{ $customer['name'] }}</td>
                <td><strong>Due Date:</strong> {{ $invoice['due_date'] }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Customer Address:</strong> {{ $customer['address'] }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Customer Contact:</strong> {{ $customer['phone'] . ' - ' . $customer['email'] }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Length (cm)</th>
                    <th class="text-right">Qty x Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td class="text-center">{{ $item['length'] }}</td>
                        <td class="text-right">{{ $item['quantity'] }} x
                            {{ formatRupiah($item['price'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ formatRupiah($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="total-table">
            <tr>
                <td class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right">{{ formatRupiah($totals['subtotal'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Tax ({{ $totals['tax_rate'] }}%):</strong></td>
                <td class="text-right">{{ formatRupiah($totals['tax_amount'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-right"><strong>Grand Total:</strong></td>
                <td class="text-right">{{ formatRupiah($totals['grand_total'], 0, ',', '.') }}</td>
            </tr>
        </table>

        @if (!empty($notes) || !empty($payment_info['bank_name']))
            <div style="margin-top: 20px; font-size: 12px;">
                @if (!empty($notes))
                    <h3 style="margin-bottom: 5px;">Notes:</h3>
                    <p style="margin-top: 0;">{{ $notes }}</p>
                @endif

                @if (!empty($payment_info['bank_name']))
                    <h3 style="margin-top: 15px; margin-bottom: 5px; padding:5; background:#f7f7f7; color:#212121">Payment Info</h3>
                    <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                        <tr>
                            <td style="width: 30%; padding: 4px;"><strong>Bank Name</strong></td>
                            <td style="padding: 4px;">{{ $payment_info['bank_name'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px;"><strong>Account Number</strong></td>
                            <td style="padding: 4px;">{{ $payment_info['account_number'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px;"><strong>Account Name</strong></td>
                            <td style="padding: 4px;">{{ $payment_info['account_name'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px;"><strong>Payment Status</strong></td>
                            <td style="padding: 4px;">{{ $payment_info['status'] }}</td>
                        </tr>
                        @if($payment_info['proof_image'])
                           <tr>
                              <td style="padding: 4px;"><strong>Payment Proof</strong></td>
                              <td style="padding: 4px;"><a href="{{ $payment_info['proof_image'] }}" target="_blank">See Image Proof</a></td>
                           </tr>
                        @endif
                    </table>
                @endif
            </div>
        @endif

        <div class="footer">
            <img src="{{ filePath(setting('site.logo')) }}" alt="Logo Sajjad Zamzami" style="max-height: 2em">
            <p><strong>{{ $company['name'] }}</strong><br>
                {{ $company['address'] }}<br>
                Phone: {{ $company['phone'] }} | Email: {{ $company['email'] }}<br><br><br><br>
                Printed with smart system by <strong>Pharabius Team - <a href="https://instagram.com/hiamalif">
                        @hiamalif</a></strong>
            </p>
        </div>
    </div>
</body>

</html>
