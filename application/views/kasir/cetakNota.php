<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Convert HTML To Image</title>
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script> -->

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .invoice {
            max-width: 100%;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .invoice header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice header h2 {
            margin: 0;
        }

        .invoice section {
            margin-bottom: 20px;
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice table th,
        .invoice table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .invoice table tfoot td {
            font-weight: bold;
            border-top: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }
    </style>

</head>

<!-- <body onload="autoClick();"> -->

<body>

    <div id="htmlContent">

        <div class="invoice">
            <!-- Header -->
            <header>
                <!-- Informasi Perusahaan -->
                <div class="company-info">
                    <h2>BENDAHARA</h2>
                    <p>Pesantren Darul Lughah Wal Karomah</p>
                    <p><u><b>Kode Inv : <?= $kode_pj ?></b></u></p>
                </div>

                <!-- Informasi Pelanggan -->
                <div class="customer-info">
                    <h2><?= $mitra->nama ?></h2>
                    <p><?= $mitra->pj ?></p>
                    <p><?= $mitra->hp ?></p>
                </div>
            </header>

            <!-- Detail Invoice -->
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Item</th>
                            <th>QTY</th>
                            <th>Satuan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($order_mitra->result() as $dtm) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $dtm->nama ?></td>
                                <td><?= $dtm->vol ?></td>
                                <td><?= $dtm->satuan ?></td>
                                <td><?= $dtm->tgl_order ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Tambahkan baris tambahan sesuai dengan item yang diperlukan -->
                    </tbody>
                    <!-- <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">Subtotal:</td>
                            <td>$20.00</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right">Pajak:</td>
                            <td>$2.00</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right">Total:</td>
                            <td>$22.00</td>
                        </tr>
                    </tfoot> -->
                </table>
            </section>
        </div>

    </div>
    <button id="exportButton" onclick="saveImage()">
        <b style="color: white;">Simpan</b>
    </button>

    <script>
        function saveImage() {
            var element = document.getElementById("htmlContent");

            html2canvas(element).then(function(canvas) {
                canvas.toBlob(function(blob) {
                    // Create a FormData object to send the blob data
                    var formData = new FormData();
                    formData.append('image', blob, 'image.jpg');

                    const baseUrl = '<?= base_url(''); ?>';

                    // console.log(baseUrl + 'lembaga/save_img')
                    // Send the blob data to the server using AJAX

                    fetch('save_image', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            console.log('Gambar berhasil disimpan di server.');
                        })
                        .catch(error => {
                            console.log('Terjadi kesalahan saat menyimpan gambar: ' + error);
                        });
                }, 'image/jpeg');
            });
        }
    </script>

    <script>
        window.print()
    </script>
</body>

</html>