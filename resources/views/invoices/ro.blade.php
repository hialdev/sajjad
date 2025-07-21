<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice['code'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>INVOICE</h2>
    <p><strong>Kode Invoice:</strong> {{ $invoice['code'] }}<br>
       <strong>Order:</strong> {{ $invoice['order_number'] }}<br>
       <strong>Tanggal:</strong> {{ $invoice['date'] }}<br>
       <strong>Status:</strong> {{ ['Menunggu', 'Diproses', 'Selesai'][$invoice['payment_status']] ?? 'Unknown' }}</p>

    <hr>

    <h4>Client</h4>
    <p>
        {{ $client['name'] }}<br>
        {{ $client['address'] }}<br>
        {{ $client['phone'] }} | {{ $client['email'] }}
    </p>

    <h4>Produk</h4>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Length (cm)</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="text-right">{{ $item['length'] }}</td>
                <td class="text-right">{{ $item['quantity'] }}</td>
                <td class="text-right">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Total</h4>
    <table>
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp{{ number_format($totals['subtotal'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>PPN ({{ $totals['tax_rate'] }}%)</td>
            <td class="text-right">Rp{{ number_format($totals['tax_amount'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Grand Total</strong></td>
            <td class="text-right"><strong>Rp{{ number_format($totals['grand_total'], 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div style="padding: 20 0;">
      <img src="{{ filePath(setting('site.logo')) }}" alt="Logo Sajjad Zamzami" style="max-height: 3em">
      <p><strong>{{ setting('company.legal-name') }}</strong><br>
            {{ setting('company.address') }}<br>
            {{ setting('company.city') }}<br>
            {{ setting('company.postal-code') }}<br>
            Phone: {{ setting('company.phone') }} | Email: {{ setting('company.cp-email') }}<br><br><br><br>
            Printed with smart system by <strong>Pharabius Team - <a href="https://instagram.com/hiamalif">
                  @hiamalif</a></strong>
      </p>
   </div>
</body>
</html>
