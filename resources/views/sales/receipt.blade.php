<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $sale->invoice_number }}</title>
<style>
    /* --- PENGATURAN DASAR --- */
    body {
        font-family: 'Courier New', Courier, monospace; /* Font khas kasir */
        font-size: 13px;
        line-height: 1.3;
        color: #000;
        background-color: #f4f4f4; /* Latar belakang abu di layar agar struk terlihat kontras */
        margin: 0;
        padding: 20px 0;
    }

    /* --- CONTAINER STRUK (DIPAKSA TETAP 80MM) --- */
    .receipt-box {
        width: 80mm; /* Standar lebar kertas printer thermal */
        min-height: 100mm;
        margin: 0 auto;
        padding: 6mm;
        background-color: #fff;
        box-sizing: border-box;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
    
    .divider {
        border-top: 1px dashed #000;
        margin: 6px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table td {
        vertical-align: top;
        padding: 2px 0;
    }

    /* --- PERBAIKAN UTAMA UNTUK MESIN PRINT STRUK THERMAL --- */
    @media print {
        @page {
            /* Memaksa kertas cetak berukuran lebar 80mm dengan tinggi otomatis mengikuti konten */
            size: 80mm auto; 
            margin: 0; /* Menghilangkan margin browser & link URL */
        }

        body {
            background-color: #fff;
            padding: 0;
            margin: 0;
        }

        .receipt-box {
            width: 80mm !important; /* Kunci lebar agar tidak melar ke ukuran A4 */
            box-shadow: none;
            padding: 4mm; /* Sesuaikan padding tipis agar fit di kertas thermal */
            margin: 0;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
</head>
<body>

    <div class="receipt-box">
        <div class="text-center">
            <h3 style="margin: 0 0 5px 0; font-size: 16px;" class="font-bold">TOKO KASIR KITA</h3>
            <p style="margin: 0; font-size: 12px;">Batam, Indonesia</p>
        </div>

        <div class="divider"></div>

        <table style="font-size: 12px;">
            <tr>
                <td>No: {{ $sale->invoice_number }}</td>
                <td class="text-right">{{ $sale->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir: {{ $sale->user->name ?? '-' }}</td>
                <td class="text-right">Metode: {{ $sale->payment_method }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table>
            @foreach($sale->saleItems as $item)
                <tr>
                    <td colspan="2" class="font-bold">{{ $item->product->name }}</td>
                </tr>
                <tr>
                    <td>{{ $item->quantity }} x Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <table>
            <tr>
                <td>Subtotal Item:</td>
                <td class="text-right">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($sale->discount > 0)
            <tr>
                <td>Potongan Diskon:</td>
                <td class="text-right">-Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td>PPN (11%):</td>
                <td class="text-right">Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
            </tr>
            <tr class="font-bold" style="font-size: 15px;">
                <td>Total Tagihan:</td>
                <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="divider"></tr>
            <tr>
                <td>Uang Bayar:</td>
                <td class="text-right">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian:</td>
                <td class="text-right">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 15px; font-size: 12px;">
            <p style="margin: 0;" class="font-bold">Terima Kasih Atas Kunjungan Anda!</p>
            <p style="margin: 5px 0 0 0; font-size: 10px; color: #555;">Sistem POS Kasir Kita</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // Opsional: Tutup tab otomatis setelah dialog print selesai ditutup kasir
            // window.onafterprint = function() { window.close(); }
        }
    </script>
</body>
</html>