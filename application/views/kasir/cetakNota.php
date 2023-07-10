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

    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/plugins/notifications/css/lobibox.min.css" />

    <link href="<?= base_url('vertical/assets/invoice/') ?>print_style.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('vertical/assets/invoice/') ?>paper.css" />
    <link rel="stylesheet" href="<?= base_url('vertical/assets/invoice/') ?>normalize.css" />


    <style>
        @page {
            size: A4 portrait;
            margin-top: 8px;
            margin-bottom: 10px;
            margin-left: 20px;
        }
    </style>
    <style>
        /* Three image containers (use 25% for four, and 50% for two, etc) */
        .column {
            float: left;
            padding: 5px;
        }

        /* Clear floats after image containers */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table tbody tr td {
            padding: 2px !important;
            line-height: 1.35 !important;
        }

        @media print {
            .box-body {
                margin-top: 10px !important;
                margin-bottom: 10px;
            }
        }
    </style>


    <style>
        .center-me {
            font-size: 15px;
            margin: auto;
            height: 10px;
            max-width: 500px;
            margin: 75px auto 40px auto;
            display: flex;
        }
    </style>

    <style>
        /* Gaya untuk indikator loading */
        #loading-indicator {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 4px solid #fff;
            border-top-color: #007bff;
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
    </style>

</head>

<!-- <body onload="autoClick();"> -->

<body>
    <div id="loading-indicator">
        <div class="spinner"></div>
    </div>

    <div id="htmlContent">

        <section class="sheet" id="content-print">
            <style>
                table {
                    /*border-collapse: unset !important;*/
                }
            </style>

            <div class="box-body" id="box_data" style="display: flex; padding: 5px 10px 0 10px; margin-bottom: -21px">
                <div style="width: 100%; padding-right: 10px" class="col-md-12">
                    <div class="row">
                        <div class="col-lg-4" style="width: 70%; padding-left: 20px">
                            <h4>INVOICE</h4>
                        </div>
                        <div class="col-lg-8" style="width: 30%">
                            <h5 style="font-size: 20px; margin-bottom: 15px; margin-top: 25px">
                                BENDAHARA PESANTREN
                            </h5>

                            <p style="font-size: 12px; margin: 0; padding: 0">
                                PonPes Darul Lughah Wal Karomah
                            </p>

                            <p style="font-size: 12px; margin: 0; padding-top: 3px">
                                Sidomukti - Kraksaan - Probolinggo
                            </p>

                            <p style="font-size: 12px; margin: 0; padding-top: 5px">
                                Phone: -
                            </p>
                            <br />
                        </div>
                    </div>
                    <div class="" style="display: flex; margin-top: -62px">
                        <table border="1" style="width: 30%">
                            <tr class="" style="background: rgba(217, 225, 242, 1)">
                                <td style="font-size: 14px" class="db text-center" width="100px">
                                    Tanggal
                                </td>
                                <td style="border-left: none; font-size: 14px">INVOICE NO</td>
                                <td style="border-left: none; font-size: 14px">LEMBAGA</td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px"><?= date('d-m-Y H:i') ?></td>
                                <td class="text-center" style="font-weight: bold; color: red;"><?= $kode_pj ?></td>
                                <td><?= $lembaga->row('nama') ?></td>
                            </tr>
                        </table>
                    </div>
                    <br />

                    <table width="100%" border="1">
                        <tr style="background: rgba(217, 225, 242, 1)">
                            <th class="text-center" colspan="2" style="width: 25%">
                                Mitra Tujuan
                            </th>
                        </tr>
                        <tr>
                            <th style="
									width: 5%;
									font-size: 12px;
									background: rgba(217, 225, 242, 1);
								">
                                ID Mitra
                            </th>
                            <th><?= substr($mitra->id_mitra, 0, 8)  ?>****************************</th>
                        </tr>
                        <tr>
                            <th style="
									width: 5%;
									font-size: 12px;
									background: rgba(217, 225, 242, 1);
								">
                                Nama Mitra
                            </th>
                            <th><?= $mitra->nama ?></th>
                        </tr>
                        <tr>
                            <th style="
									width: 5%;
									font-size: 12px;
									background: rgba(217, 225, 242, 1);
								">
                                PJ Mitra
                            </th>
                            <th><?= $mitra->pj ?></th>
                        </tr>
                        <tr>
                            <th style="
									width: 5%;
									font-size: 12px;
									background: rgba(217, 225, 242, 1);
								">
                                No. HP
                            </th>
                            <th><?= $mitra->hp ?></th>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="1px">
                        <tr style="background: rgba(217, 225, 242, 1)">
                            <th class="text-center">#</th>
                            <th class="text-center" colspan="3">Nama Item</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Tanggal</th>
                        </tr>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($order_mitra->result() as $dtm) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td colspan="3"><?= $dtm->nama ?></td>
                                    <td class="text-center"><?= $dtm->vol ?></td>
                                    <td><?= $dtm->satuan ?></td>
                                    <td><?= $dtm->tgl_order ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <!-- <tfoot>
                            <tr style="background: rgba(217, 225, 242, 1)">
                                <td>Tax</td>
                                <td>7%</td>
                                <td>Discount</td>
                                <td>5%</td>
                                <td colspan="2">Total Amount</td>
                                <td colspan="1">5741</td>
                            </tr>
                        </tfoot> -->
                    </table>
                    <br />
                    <table width="94%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="0%" rowspan="2" valign="top">
                                <strong class="asd"> &nbsp;<br /></strong>
                            </td>
                            <td width="75%" align="center" valign="top">
                                <h6>
                                    *<i>Nota ini sebagai bukti pengambilan barang kepada Mitra Pesantren untuk pengajuan pencairan berupa barang</i>
                                </h6>
                                <!-- <h6>Name, Email, Phone</h6> -->
                            </td>
                            <td width="25%" valign="top">
                                <h6 style="margin-bottom: 0">
                                    <span style="
											text-decoration: dashed;
											padding-left: 100%;
											color: #000;
											border-bottom: 1px solid black;
										"></span>
                                </h6>
                                <h6 class="text-center" style="margin-top: 10px">
                                    <?= $kasir ?>
                                </h6>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>

    </div>
    <button id="exportButton" onclick="saveImage()" data-id_mitra="<?= $mitra->id_mitra ?>" data-kode_pengajuan="<?= $kode_pj ?>" data-hp="<?= $mitra->hp ?>">
        <b style="color: white;">Kirim Nota</b>
    </button>
    <!-- <button onclick="closeWindow()">
        <b style=" color: white;">Tutup</b>
    </button> -->

    <script src="<?= base_url('vertical/'); ?>assets/plugins/notifications/js/lobibox.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/plugins/notifications/js/notifications.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/plugins/notifications/js/my-notif.js"></script>

    <script>
        function showLoadingIndicator() {
            document.getElementById('loading-indicator').style.display = 'block';
        }

        // Menyembunyikan indikator loading setelah proses Ajax selesai
        function hideLoadingIndicator() {
            document.getElementById('loading-indicator').style.display = 'none';
        }

        function round_success_noti() {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: false,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: 'Invoice berhasil terkirim.'
            });
        }

        function errorNotif() {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: false,
                // icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: 'Invoice gagal terkirim.'
            });
        }

        function closeWindow() {
            window.close()
        }

        function saveImage() {
            showLoadingIndicator();

            var element = document.getElementById("htmlContent");
            var button = document.getElementById('exportButton');

            var id_mitra = button.getAttribute('data-id_mitra');
            var kode_pengajuan = button.getAttribute('data-kode_pengajuan');
            var hp = button.getAttribute('data-hp');


            html2canvas(element).then(function(canvas) {
                canvas.toBlob(function(blob) {
                    // Create a FormData object to send the blob data
                    var formData = new FormData();
                    formData.append('image', blob, 'image.jpg');

                    const baseUrl = '<?= base_url(''); ?>';

                    // console.log(baseUrl + 'lembaga/save_img')
                    // Send the blob data to the server using AJAX

                    // fetch('https://simkupaduka.ppdwk.com/save_image.php?id_mitra=' + id_mitra + '&kode_pengajuan=' + kode_pengajuan, {
                    fetch(baseUrl + 'save_image.php?id_mitra=' + id_mitra + '&kode_pengajuan=' + kode_pengajuan, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            console.log('Gambar berhasil disimpan di server.');
                            // console.log(hp)

                            $.ajax({
                                url: baseUrl + "kirim.php",
                                type: "POST",
                                dataType: 'json',
                                // contentType: 'application/x-www-form-urlencoded',
                                data: {
                                    apiKey: "f4064efa9d05f66f9be6151ec91ad846",
                                    phone: hp,
                                    // url_file: baseUrl + "vertical/aseests/nota/" + kode_pengajuan + "_" + id_mitra + ".jpg",
                                    url_file: "https://cdns.klimg.com/bola.net/library/upload/21/2023/03/645x430/motogp_1c91939.jpg",
                                    as_document: 0,
                                    caption: "Informasi Order Baru dari Bendahara pesanten",
                                },
                                success: function(response) {
                                    // console.log(response);
                                    if (response.code == 200) {
                                        // console.log('Pengiriman berhasil');
                                        hideLoadingIndicator();
                                        round_success_noti();
                                        setTimeout(function() {
                                            window.close();
                                        }, 2500);
                                    } else {
                                        hideLoadingIndicator();
                                        errorNotif()
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(error);
                                }
                            })
                        })
                        .catch(error => {
                            console.log('Terjadi kesalahan saat menyimpan gambar: ' + error);
                        })
                }, 'image/jpeg');
            });
        }
    </script>

    <script>
        window.print()
    </script>
</body>

</html>