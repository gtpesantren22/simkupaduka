<?php date_default_timezone_set('Asia/Jakarta'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Nota Mitra - <?= $kode_pj ?></title>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?= base_url('vertical/assets/invoice/') ?>normalize.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/assets/invoice/') ?>paper.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 0.75rem;
        }

        /* @page rules are dynamically defined in #paper-size-style */

        /* Invoice Container */
        .invoice-box {
            max-width: 800px;
            margin: 0 auto;
            padding: 5px;
            background-color: #fff;
        }

        /* Top Bar with action buttons - hidden on print */
        .no-print-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            padding: 8px 15px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .no-print-bar h5 {
            margin: 0;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5c636a;
            border-color: #565e64;
        }

        /* Header Info Layout */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-left h2 {
            margin: 0 0 3px 0;
            font-weight: 700;
            color: #212529;
            letter-spacing: -0.5px;
            font-size: 1.2rem;
        }

        .header-left p {
            margin: 0;
            font-size: 0.75rem;
            color: #6c757d;
            line-height: 1.3;
        }

        .header-right {
            text-align: right;
        }

        .header-right h3 {
            margin: 0 0 5px 0;
            color: #0d6efd;
            font-weight: 700;
            font-size: 1rem;
        }

        .header-right p {
            margin: 1px 0;
            font-size: 0.75rem;
            color: #495057;
        }

        /* Meta Table Style */
        .meta-info-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 15px;
        }

        .meta-card {
            flex: 1;
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 8px 12px;
            border: 1px solid #e9ecef;
        }

        .meta-card h4 {
            margin: 0 0 8px 0;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #868e96;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 3px;
        }

        .meta-card table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-card table td {
            padding: 2px 0;
            font-size: 0.75rem;
            color: #495057;
        }

        .meta-card table td.label {
            font-weight: 600;
            color: #212529;
            width: 30%;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 6px 8px;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
        }

        .items-table th.text-center {
            text-align: center;
        }

        .items-table th.text-end {
            text-align: right;
        }

        .items-table td {
            padding: 6px 8px;
            font-size: 0.75rem;
            border-bottom: 1px solid #dee2e6;
            color: #212529;
        }

        .items-table td.text-center {
            text-align: center;
        }

        .items-table td.text-end {
            text-align: right;
        }

        .items-table tr.total-row td {
            font-weight: 700;
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.8rem;
        }

        .items-table tr.total-row td.total-val {
            color: #dc3545;
        }

        /* Note and Signature */
        .invoice-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .footer-note {
            width: 65%;
            font-size: 0.7rem;
            color: #6c757d;
            font-style: italic;
            line-height: 1.4;
        }

        .footer-signature {
            width: 25%;
            text-align: center;
        }

        .signature-line {
            margin-top: 45px;
            border-bottom: 1px solid #495057;
            font-weight: 600;
            color: #212529;
            padding-bottom: 3px;
            font-size: 0.8rem;
        }

        .signature-title {
            font-size: 0.7rem;
            color: #6c757d;
            margin-top: 3px;
        }

        /* Print Override */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #fff;
                padding: 0;
            }
            .invoice-box {
                padding: 0;
            }
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    </style>
    <style id="paper-size-style">
        @page {
            size: A4 portrait;
            margin: 8mm 8mm 8mm 12mm;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <!-- Top bar action buttons (visible on screen only) -->
        <div class="no-print-bar no-print">
            <h5>Pratinjau Cetak Nota</h5>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <label for="paperSize" style="font-size: 0.75rem; font-weight: 600; color: #495057; margin: 0;">Ukuran Kertas:</label>
                    <select id="paperSize" onchange="changePaperSize(this.value)" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; border: 1px solid #ced4da; border-radius: 4px; background: white; cursor: pointer;">
                        <option value="a4" selected>A4 (210 x 297 mm)</option>
                        <option value="f4">F4 / Folio (215 x 330 mm)</option>
                    </select>
                </div>
                <button onclick="window.print()" class="btn btn-primary">Cetak Sekarang</button>
                <button onclick="window.close()" class="btn btn-secondary">Tutup Halaman</button>
            </div>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="header-left">
                <h2>INVOICE</h2>
                <p>BENDAHARA PESANTREN</p>
                <p>PonPes Darul Lughah Wal Karomah</p>
                <p>Sidomukti - Kraksaan - Probolinggo</p>
            </div>
            <div class="header-right">
                <h3>KPA LEMBAGA</h3>
                <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i') ?></p>
                <p><strong>No Invoice:</strong> <span style="font-weight: bold; color: #dc3545;"><?= $kode_pj ?></span></p>
            </div>
        </div>

        <!-- Meta Info Cards (Submissions & Partner Details) -->
        <div class="meta-info-box">
            <!-- Left Meta Card: Submission Info -->
            <div class="meta-card">
                <h4>Data Pengajuan</h4>
                <table>
                    <tr>
                        <td class="label">Lembaga</td>
                        <td>: <?= $lembaga->row('nama') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Kode</td>
                        <td>: <?= $kode_pj ?></td>
                    </tr>
                </table>
            </div>

            <!-- Right Meta Card: Partner Info -->
            <div class="meta-card">
                <h4>Mitra Tujuan</h4>
                <table>
                    <tr>
                        <td class="label">Nama</td>
                        <td>: <?= $mitra->nama ?></td>
                    </tr>
                    <tr>
                        <td class="label">PJ</td>
                        <td>: <?= $mitra->pj ?></td>
                    </tr>
                    <tr>
                        <td class="label">No. HP</td>
                        <td>: <?= $mitra->hp ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th>Nama Item</th>
                    <th class="text-center" width="8%">QTY</th>
                    <th class="text-center" width="12%">Satuan</th>
                    <th class="text-end" width="18%">Harga</th>
                    <th class="text-end" width="18%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($order_mitra->result() as $dtm) :
                    $ket = $dtm->ket;
                    preg_match('/^(.*?)\s*-\s*@/u', $ket, $namaMatch);
                    $nama = isset($namaMatch[1]) ? trim($namaMatch[1]) : null;

                    preg_match('/\d+\s+([^\s]+)\s+x/u', $ket, $satuanMatch);
                    $satuan = isset($satuanMatch[1]) ? trim($satuanMatch[1]) : null;

                    $display_nama = $nama ? $nama : $ket;
                    $display_satuan = $satuan ? $satuan : '-';
                    
                    $harga = isset($dtm->harga) && $dtm->harga > 0 ? $dtm->harga : ($dtm->vol > 0 ? $dtm->nominal / $dtm->vol : 0);
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $display_nama ?></td>
                        <td class="text-center"><?= $dtm->vol ?></td>
                        <td class="text-center"><?= $display_satuan ?></td>
                        <td class="text-end"><?= rupiah($harga) ?></td>
                        <td class="text-end fw-bold"><?= rupiah($dtm->vol * $harga) ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="5" class="text-end">TOTAL</td>
                    <td class="text-end total-val"><?= rupiah($order_mitraTotal->total) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Signature & Note -->
        <div class="invoice-footer">
            <div class="footer-note">
                <p>* Nota ini berfungsi sebagai bukti sah pengambilan barang kepada Mitra Pesantren untuk pengajuan pencairan non-tunai.</p>
            </div>
            <div class="footer-signature">
                <div class="signature-line"><?= $kasir ?></div>
                <div class="signature-title">KPA Lembaga</div>
            </div>
        </div>
    </div>

    <script>
        function changePaperSize(size) {
            const styleTag = document.getElementById('paper-size-style');
            if (size === 'f4') {
                styleTag.innerHTML = '@page { size: 215mm 330mm; margin: 8mm 8mm 8mm 12mm; }';
            } else {
                styleTag.innerHTML = '@page { size: A4 portrait; margin: 8mm 8mm 8mm 12mm; }';
            }
        }

        // Auto trigger browser print dialog upon page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>
