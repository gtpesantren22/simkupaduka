<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="<?= base_url('vertical/'); ?>assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="<?= base_url('vertical/'); ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/plugins/notifications/css/lobibox.min.css" />
    <!-- loader-->
    <link href="<?= base_url('vertical/'); ?>assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?= base_url('vertical/'); ?>assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('vertical/'); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/app2.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('vertical/'); ?>assets/css/icons.css" rel="stylesheet">
    <title>SIMKUPADUKA - Login Page</title>
</head>

<body class="bg-login">
    <!--wrapper-->

    <div class="wrapper">
        <div class="section-authentication-cover">
            <div class="">
                <div class="row g-0">

                    <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                            <div class="card-body">
                                <img src="https://codervent.com/rocker/demo/vertical/assets/images/login-images/login-cover.svg" class="img-fluid auth-img-cover-login" width="650" alt="" />
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                        <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                            <div class="card-body p-sm-5">
                                <div class="">
                                    <div class="mb-3 text-center">
                                        <img src="<?= base_url('vertical/'); ?>assets/images/logo.png" width="180" alt="" />
                                    </div>
                                    <div class="text-center mb-4">
                                        <p class="mb-0">Please log in to your account</p>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" method="post" action="<?= base_url('login/masuk'); ?>">
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Usename</label>
                                                <input type="text" name="username" class="form-control" id="inputEmailAddress" placeholder="User Account">
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter
                                                    Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password">
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Tahun
                                                    Pelajaran</label>
                                                <select name="tahun" class="form-control" id="" required>
                                                    <?php
                                                    foreach ($tahun as $a) { ?>
                                                        <option value="<?= $a->nama_tahun ?>"><?= $a->nama_tahun ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end"> <a href="authentication-forgot-password.html">Daftar Akun</a>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="login-separater text-center mb-5"> <span>OR SIGN IN WITH</span>
                                        <hr>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="<?= base_url('vertical/'); ?>assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->
    <!--notification js -->
    <script src="<?= base_url('vertical/'); ?>assets/plugins/notifications/js/lobibox.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/plugins/notifications/js/notifications.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/sw/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/plugins/notifications/js/my-notif.js"></script>
    <script src="<?= base_url('vertical/'); ?>assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>

    <!-- <script src="<?= base_url('vertical/assets/js/OneSignalSDKWorker.js') ?>" async=""></script> -->

    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "89772c00-acb3-44c0-b189-cee68f98acce",
                safari_web_id: "web.onesignal.auto.1afb9025-a2b0-4a54-8c00-23b218b2b39b",
                notifyButton: {
                    enable: true,
                },
            });
        });
    </script>
</body>

</html>