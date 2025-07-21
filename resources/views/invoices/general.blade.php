<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #8B4513;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .footer {
            background-color: #8B4513;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
        }
        .invoice-summary {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .invoice-summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-summary td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .invoice-summary .label {
            font-weight: bold;
            width: 30%;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #8B4513;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .custom-message {
            background-color: #e8f4f8;
            padding: 15px;
            border-left: 4px solid #8B4513;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔥 {{ $invoice_data['company']['name'] }}</h1>
        <p>Invoice Notification</p>
    </div>

    <div class="content">
        <h2>Halo {{ $invoice_data['customer']['name'] }},</h2>
        
        <p>Terima kasih atas kepercayaan Anda menggunakan layanan kami. Berikut adalah invoice untuk pesanan Anda:</p>

        @if($custom_message)
        <div class="custom-message">
            <strong>Pesan khusus:</strong><br>
            {{ $custom_message }}
        </div>
        @endif

        <div class="invoice-summary">
            <h3>Detail Invoice</h3>
            <table>
                <tr>
                    <td class="label">Nomor Invoice:</td>
                    <td>{{ $invoice_data['invoice']['order_number'] }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal:</td>
                    <td>{{ $invoice_data['invoice']['date'] }}</td>
                </tr>
                <tr>
                    <td class="label">Customer:</td>
                    <td>{{ $invoice_data['customer']['name'] }}</td>
                </tr>
                <tr>
                    <td class="label">Total Items:</td>
                    <td>{{ count($invoice_data['items']) }} item(s)</td>
                </tr>
                <tr>
                    <td class="label">Subtotal:</td>
                    <td>Rp {{ number_format($invoice_data['totals']['subtotal'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Pajak ({{ $invoice_data['totals']['tax_rate'] }}%):</td>
                    <td>Rp {{ number_format($invoice_data['totals']['tax_amount'], 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold; font-size: 16px; background-color: #f0f0f0;">
                    <td class="label">Grand Total:</td>
                    <td>Rp {{ number_format($invoice_data['totals']['grand_total'], 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <p><strong>Invoice PDF telah dilampirkan dalam email ini.</strong></p>

        <p>Silakan lakukan pembayaran sesuai dengan detail yang tertera dalam invoice. Jika ada pertanyaan, jangan ragu untuk menghubungi kami.</p>

        @if($invoice_data['payment_info']['bank_name'])
        <div class="invoice-summary">
            <h3>Informasi Pembayaran</h3>
            <table>
                <tr>
                    <td class="label">Bank:</td>
                    <td>{{ $invoice_data['payment_info']['bank_name'] }}</td>
                </tr>
                <tr>
                    <td class="label">No. Rekening:</td>
                    <td>{{ $invoice_data['payment_info']['account_number'] }}</td>
                </tr>
                <tr>
                    <td class="label">Atas Nama:</td>
                    <td>{{ $invoice_data['payment_info']['account_name'] }}</td>
                </tr>
            </table>
        </div>
        @endif

        <p>Terima kasih atas kerja sama Anda!</p>

        <p>
            Salam,<br>
            <strong>{{ $invoice_data['company']['contact_person'] }}</strong><br>
            {{ $invoice_data['company']['name'] }}
        </p>
    </div>

    <div class="footer">
        <p>{{ $invoice_data['company']['name'] }} | {{ $invoice_data['company']['address'] }}</p>
        <p>Phone: {{ $invoice_data['company']['phone'] }} | Email: {{ $invoice_data['company']['email'] }}</p>
        <p style="font-size: 11px; margin-top: 10px;">
            Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
        </p>
    </div>
</body>
</html>