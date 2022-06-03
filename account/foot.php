<!-- footer content -->
<footer>
    <div class="pull-right">
        App Sentralisasi Keuangan - PP DWK &copy;2021 v.1.0 | by <a href="">Admin DWK_21</a>
    </div>
    <div class="clearfix"></div>
</footer>
<!-- /footer content -->
</div>
</div>

<!-- jQuery -->
<script src="../main/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../main/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- FastClick -->
<script src="../main/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="../main/vendors/nprogress/nprogress.js"></script>
<!-- Chart.js -->
<script src="../main/vendors/Chart.js/dist/Chart.min.js"></script>
<!-- gauge.js -->
<script src="../main/vendors/gauge.js/dist/gauge.min.js"></script>
<!-- bootstrap-progressbar -->
<script src="../main/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="../main/vendors/iCheck/icheck.min.js"></script>
<!-- Skycons -->
<script src="../main/vendors/skycons/skycons.js"></script>

<!-- Flot -->
<script src="../main/vendors/Flot/jquery.flot.js"></script>
<script src="../main/vendors/Flot/jquery.flot.pie.js"></script>
<script src="../main/vendors/Flot/jquery.flot.time.js"></script>
<script src="../main/vendors/Flot/jquery.flot.stack.js"></script>
<script src="../main/vendors/Flot/jquery.flot.resize.js"></script>
<!-- Flot plugins -->
<script src="../main/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
<script src="../main/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="../main/vendors/flot.curvedlines/curvedLines.js"></script>
<!-- DateJS -->
<script src="../main/vendors/DateJS/build/date.js"></script>
<!-- JQVMap -->
<script src="../main/vendors/jqvmap/dist/jquery.vmap.js"></script>
<script src="../main/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="../main/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../main/vendors/moment/min/moment.min.js"></script>
<script src="../main/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- Custom Theme Scripts -->
<script src="../main/build/js/custom.min.js"></script>

<script src="../main/dist/sweetalert2.all.min.js"></script>

<script type="text/javascript">
    var rupiah = document.getElementById('uang');
    var rupiah2 = document.getElementById('uang_2');

    rupiah.addEventListener('keyup', function(e) {
        rupiah.value = formatRupiah(this.value);
    });
    rupiah2.addEventListener('keyup', function(e) {
        rupiah2.value = formatRupiah(this.value);
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
    }
</script>
</body>

</html>