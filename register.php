<?php
include('include/header.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkPIS-kPEurrL0ciFQ_a_7x7iz2lojCMY"></script>
<script type="text/javascript">
    // variabel global marker
    var marker;

    function taruhMarker(peta, posisiTitik) {

        if (marker) {
            // pindahkan marker
            marker.setPosition(posisiTitik);
        } else {
            // buat marker baru
            marker = new google.maps.Marker({
                position: posisiTitik,
                map: peta,
            });
        }

        // animasi sekali
        marker.setAnimation(google.maps.Animation.BOUNCE);
        setTimeout(function() {
            marker.setAnimation(null);
        }, 750);

        // kirim nilai koordinat ke input
        $("input[name=longitude]").val(posisiTitik.lng());
        $("input[name=latitude]").val(posisiTitik.lat());

        console.log($("input[name=longitude]").val() + "," + $("input[name=latitude]").val());
    }

    function initialize() {
        var propertiPeta = {
            center: new google.maps.LatLng(-7.9767, 112.6168),
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var peta = new google.maps.Map(document.getElementById("google-maps"), propertiPeta);

        // even listner ketika peta diklik
        google.maps.event.addListener(peta, 'click', function(event) {
            taruhMarker(this, event.latLng);
        });

        // marker.setMap(peta);
    }
    // event jendela di-load
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pendaftaran</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-5 col-lg-6 col-md-1">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <?php
                        if (isset($_SESSION['status'])) :
                        ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><?php echo $_SESSION['status'] ?></strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php
                        endif
                        ?>
                        <!-- Nested Row within Card Body -->
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Daftar</h1>
                                </div>
                                <form class="user" action="code.php" method="post">
                                    <label for="" style="color: black;">Nama Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" name="daycareName" placeholder="Nama Daycare">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Deskripsi Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" name="description" placeholder="Deskripsi Daycare">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Alamat Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" name="address" placeholder="Alamat Daycare">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Pilih titik Daycare pada Peta :</label>
                                    <div id="google-maps" style="height: 400px; width:100%"></div>
                                    <!-- input untuk menampung koordinat -->
                                    <input type="hidden" name="longitude" value="" />
                                    <input type="hidden" name="latitude" value="" />
                                    <br>
                                    <label for="" style="color: black;">Nomor Handphone Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="number" class="form-control form-control-user" name="phoneNumber" placeholder="Nomor Handphone Daycare">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Fasilitas Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            Ruang Belajar<input type="checkbox" name="facilities[]" id="facilities" value="Ruang Belajar"><br>
                                            Taman Bermain<input type="checkbox" name="facilities[]" id="facilities" value="Taman Bermain"><br>
                                            Makan<input type="checkbox" name="facilities[]" id="facilities" value="Makan"><br>
                                            Tempat Tidur<input type="checkbox" name="facilities[]" id="facilities" value="Tempat Tidur"><br>
                                            Mandi<input type="checkbox" name="facilities[]" id="facilities" value="Mandi"><br>
                                            <input type="text" class="form-control form-control-user" name="facilities[]" placeholder="Lainnya...">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Tarif Daycare Per Bulan :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="number" class="form-control form-control-user" name="price" placeholder="Harga Per Bulan">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Nomor Rekening (Untuk Pembayaran) :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" name="accountNumber" placeholder="Harga Per Bulan">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Kuota anak yang disediakan Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="number" class="form-control form-control-user" name="quota" placeholder="Kuota yang disediakan">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Email Daycare :</label>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email" placeholder="Email">
                                    </div>
                                    <label for="" style="color: black;">Password Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password (minimal 6)">
                                        </div>
                                    </div>
                                    <label for="" style="color: black;">Foto Profil Daycare :</label>
                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-3 mb-sm-0">
                                            <input type="file" id="file">
                                            <progress id="progress_bars" value="0" max="100"></progress>
                                            <img src="" alt="" id="image" style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
                                            <script src="js/uploadFile.js" type="text/javascript"></script>
                                            <input type="hidden" name="profilePic" id="profilePic" value="">
                                        </div>
                                    </div>
                                    <button type="submit" name="register-btn" class="btn btn-primary btn-user btn-block">Daftar</button>
                                </form>
                                <hr>
                                <!-- <div class="text-center">
                                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                                </div> -->
                                <div class="text-center">
                                    <a class="small" href="login.php">Sudah punya akun ? Masuk disini</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('select').material_select();
            });
        </script>


</body>

</html>