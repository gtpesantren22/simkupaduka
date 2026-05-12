<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cek COA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-2xl bg-white p-6 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold mb-4 text-gray-700">
            🔍 Test Validasi COA
        </h2>

        <!-- Parent -->
        <div class="mb-3">
            <label class="text-sm font-semibold">Parent COA</label>
            <select id="parent" class="w-full border p-2 rounded-lg">
                <option value="">Loading...</option>
            </select>
        </div>

        <!-- Child -->
        <div class="mb-3">
            <label class="text-sm font-semibold">Child COA</label>
            <select id="child" class="w-full border p-2 rounded-lg">
                <option value="">-- Pilih COA --</option>
            </select>
        </div>

        <!-- Kebutuhan -->
        <div class="mb-3">
            <label class="text-sm font-semibold">Kebutuhan</label>
            <textarea id="kebutuhan" class="w-full border p-2 rounded-lg"
                placeholder="Contoh: Konsumsi rapat guru"></textarea>
        </div>

        <button id="cekBtn"
            class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">
            🚀 Cek
        </button>

        <div id="result" class="mt-4 hidden p-3 rounded-lg text-sm"></div>

    </div>

    <script>
        // ==========================
        // LOAD PARENT COA
        // ==========================
        $(document).ready(function() {
            $.get("<?= base_url('testcoa/get_parent_coa') ?>", function(data) {

                let html = '<option value="">-- Pilih Parent --</option>';

                data.forEach(row => {
                    html += `<option value="${row.kode}">${row.kode} : ${row.nama}</option>`;
                });

                $("#parent").html(html);
            });
        });

        // ==========================
        // LOAD CHILD
        // ==========================
        $("#parent").change(function() {

            let parent = $(this).val();

            $("#child").html('<option>Loading...</option>');

            $.get("<?= base_url('testcoa/get_child_coa') ?>", {
                parent: parent
            }, function(data) {

                let html = '<option value="">-- Pilih COA --</option>';

                data.forEach(row => {
                    html += `<option value="${row.kode}">${row.kode} : ${row.nama}</option>`;
                });

                $("#child").html(html);
            });

        });

        // ==========================
        // CEK COA (AJAX)
        // ==========================
        $("#cekBtn").click(function() {
            $(this).prop("disabled", true).html("🚀 Thinking...");

            let kode = $("#child").val();
            let kebutuhan = $("#kebutuhan").val();

            if (!kode || !kebutuhan) {
                alert("Lengkapi dulu!");
                return;
            }

            $.post("<?= base_url('testcoa/simpan') ?>", {
                kode: kode,
                kebutuhan: kebutuhan
            }, function(res) {

                let box = $("#result");
                box.removeClass("hidden");

                if (res.status === "Tidak Sesuai") {
                    box.attr("class", "mt-4 p-3 rounded-lg bg-red-100 text-red-700");
                    box.html(`<b>Peringatan:</b> ${res.alasan}<br><br><b>Saran:</b> ${res.saran_coa}`);
                } else {
                    box.attr("class", "mt-4 p-3 rounded-lg bg-green-100 text-green-700");
                    box.html("✅ Sudah sesuai!");
                }
                $("#cekBtn").prop("disabled", false).html("🚀 Cek");

            }, "json");

        });
    </script>

</body>

</html>