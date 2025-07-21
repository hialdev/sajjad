<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $invoice['order_number'] ?? 'ORDER' }}</title>
    <style>
      @page {
         margin: 15px;
      }
      body {
         font-family: monospace;
         font-size: 11px;
         margin: 0;
         padding: 5px;
      }
      .receipt {
         width: 100%;
         max-width: 300px;
         margin: 0 auto;
      }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
        }
        .item-name {
            width: 55%;
        }
        .item-qty {
            width: 15%;
            text-align: right;
        }
        .item-price {
            width: 30%;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="text-center bold">
            {{ $company['name'] ?? 'Sajjad Zamzami' }}<br>
        </div>
        <div class="text-center">
            {{ $company['address'] ?? 'Jl. Mas Mansyur 14' }}<br>
            {{ $company['city'] ?? 'Jakarta Pusat' }}<br>
            {{ $company['phone'] ?? '08123456789' }}
        </div>

        <div class="divider"></div>
        <div class="text-left">
            <strong>Invoice:</strong> {{ $invoice['order_number'] ?? '-' }}<br>
            <strong>Date:</strong> {{ $invoice['date'] ?? now()->format('d M Y H:i') }}
        </div>
        <div class="divider"></div>

        <div class="text-left">
            <strong>Customer:</strong> {{ $customer['name'] ?? '-' }}<br>
            <small>{{ $customer['phone'] ?? '' }} | {{ $customer['email'] ?? '' }}</small>
        </div>

        <div class="divider"></div>

        @foreach($items ?? [] as $item)
            <div class="item">
                <div class="item-name">{{ $item['name'] }}</div>
                <div class="item-qty">{{ $item['quantity'] }}x</div>
                <div class="item-price">Rp{{ number_format($item['price'],0,',','.') }}</div>
            </div>
        @endforeach

        <div class="divider"></div>
        <div class="item">
            <div class="item-name bold">Subtotal</div>
            <div class="item-price bold" style="margin-left:auto;">Rp{{ number_format($totals['subtotal'], 0, ',', '.') }}</div>
        </div>
        <div class="item">
            <div class="item-name">Pajak ({{ $totals['tax_rate'] ?? 0 }}%)</div>
            <div class="item-price" style="margin-left:auto;">Rp{{ number_format($totals['tax_amount'], 0, ',', '.') }}</div>
        </div>
        <div class="item">
            <div class="item-name bold">Total</div>
            <div class="item-price bold" style="margin-left:auto;">Rp{{ number_format($totals['grand_total'], 0, ',', '.') }}</div>
        </div>

        <div class="divider"></div>

        @if(!empty($payment_info['bank_name']))
            <div>
                <strong>Payment via:</strong><br>
                {{ $payment_info['bank_name'] }}<br>
                a.n. {{ $payment_info['account_name'] }}<br>
                {{ $payment_info['account_number'] }}
            </div>
        @endif

        @if(!empty($notes))
            <div class="divider"></div>
            <div>
                <strong>Catatan:</strong><br>
                {{ $notes }}
            </div>
        @endif

        <div class="divider"></div>
        <div class="text-center">
            Terima kasih!<br>
            Dicetak: {{ now()->format('d/m/Y H:i') }}<br>
            Melalui Smart System oleh Pharabius Team - al.developer12@gmail.com
        </div>
    </div>
</body>
</html>
