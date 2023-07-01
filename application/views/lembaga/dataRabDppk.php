<div class="table-responsive">

    <table class="table mb-0 table-hover">
        <thead>
            <tr>
                <th scope="col">
                    <div class="form-check form-check-success">
                        <input class="form-check-input" type="checkbox" value="" id="checkboxControl">
                    </div>
                </th>
                <th scope="col">No</th>
                <!-- <th scope="col">Kode</th> -->
                <th scope="col" style="max-width: 300px; overflow-wrap: break-word;">Nama Item/Kegiatan</th>
                <th scope="col">Harga</th>
                <th scope="col">QTY</th>
                <th scope="col">Ajukan Sisa</th>
                <th scope="col">Total</th>
                <!-- <th scope="col">#</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rab as $rab) :
                $pakaiSM = isset($realSm[$rab->kode]) ? $realSm[$rab->kode] : 0;
                $pakai = isset($real[$rab->kode]) ? $real[$rab->kode] : 0;
                $sisa = $rab->qty - ($pakaiSM + $pakai);
            ?>
                <tr>
                    <th scope="row">
                        <div class="form-check form-check-success">
                            <input class="form-check-input checkboxTarget" name="kodeRab[<?= $no ?>]" type="checkbox" value="<?= $rab->kode ?>" id="">
                        </div>
                    </th>
                    <td><?= $no ?></td>
                    <!-- <td><?= $rab->kode ?></td> -->
                    <td style="max-width: 300px; overflow-wrap: break-word;">
                        <b><?= $rab->nama ?></b><br>
                        <?= $rab->kegiatan ?>
                    </td>
                    <td class="price"><?= rupiah($rab->harga_satuan) ?></td>
                    <td><?= $rab->qty  ?></td>
                    <td>
                        <input type="number" style="width: 100%;" class="form-control form-control-sm qtyInput" name="qty[<?= $no ?>]" value="<?= $sisa ?>" min="0" max="<?= $sisa ?>">
                        <input type="hidden" name="sisa[<?= $no ?>]" value="<?= $sisa ?>">
                        <input type="hidden" name="kode_pjn[<?= $no ?>]" value="<?= $sisa ?>">
                    </td>
                    <td class="total"><?= rupiah($rab->harga_satuan * $sisa) ?></td>
                    <!-- <td></td> -->
                </tr>
            <?php
                $no++;
            endforeach; ?>
        </tbody>
    </table>
    <br>
    <button type="submit" class="btn btn-success btn-sm pull-right"><i class="bx bx-cart"></i> Add to Cart</button>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.checkboxTarget').change(function() {
            var allChecked = true;
            $('.checkboxTarget').each(function() {
                if (!this.checked) {
                    allChecked = false;
                    return false; // Hentikan iterasi jika ada checkbox target yang tidak tercentang
                }
            });
            $('#checkboxControl').prop('checked', allChecked);
        });

        // Mengubah status checkbox target ketika checkbox kontrol berubah
        $('#checkboxControl').change(function() {
            var isChecked = this.checked;
            $('.checkboxTarget').prop('checked', isChecked);
        });
    });


    var qtyInputs = document.querySelectorAll(".qtyInput");

    // Tambahkan event listener pada setiap elemen input qty
    qtyInputs.forEach(function(qtyInput) {
        qtyInput.addEventListener("change", updateTotal);
    });

    function updateTotal(event) {
        var input = event.target;
        var row = input.closest("tr");
        var qty = parseInt(input.value);

        // Validasi batasan qty
        var minQty = parseInt(input.getAttribute("min"));
        var maxQty = parseInt(input.getAttribute("max"));

        if (qty < minQty) {
            input.value = minQty; // Set qty menjadi nilai minimal
        } else if (qty > maxQty) {
            input.value = maxQty; // Set qty menjadi nilai maksimal
        }

        var priceElement = row.querySelector(".price");
        var priceText = priceElement.textContent;
        var price = parseInt(priceText.replace("Rp. ", "").replace(".", "").trim());
        var totalElement = row.querySelector(".total");
        var total = price * qty;
        totalElement.textContent = "Rp. " + formatNumber(total);
    }

    function formatNumber(number) {
        return number.toLocaleString("id-ID");
    }
</script>