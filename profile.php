<?php
if (isset($_SESSION['verified_user_id'])) {
    $uid = $_SESSION['verified_user_id'];
    $user = $auth->getUser($uid);
    $email = $user->email;
}
include('autentication.php');
include('include/header.php');
include('include/navbar.php');
?>

<script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-storage.js"></script>
<script src="js/init.js" type="text/javascript"></script>
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
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <form class="form-inline">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
            </form>

            <!-- Topbar Search
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form> -->

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <?php
                if (isset($_SESSION['verified_user_id'])) {
                    $uid = $_SESSION['verified_user_id'];
                    $user = $auth->getUser($uid);
                    $email = $user->email;
                    $replace = ["@", "."];
                    $email_replaced = str_replace($replace, "", $email);
                    $ref_table = 'daycare/' . $email_replaced . '/profile/';
                    $getdata = $database->getReference($ref_table)->getValue();
                }
                ?>
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $getdata['daycareName'] ?></span>
                        <img class="img-profile rounded-circle" src="<?= $getdata['profilePic'] ?>">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="profile.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="uploadDaycarePhoto.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Upload Foto Daycare
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>

        <body class="bg-gradient-primary">

            <div class="container">
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong><?php echo $_SESSION['status'] ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Outer Row -->
                <div class="row justify-content-center">

                    <div class="col-xl-5 col-lg-6 col-md-1">

                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-0">
                                <!-- Nested Row within Card Body -->
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Edit Profil</h1>
                                        </div>
                                        <form class="user" action="code.php" method="post">
                                            <div class="form-group row">
                                                <label for="">Nama Daycare :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="daycareName" value="<?= $getdata['daycareName'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="">Deskripsi Daycare :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="description" value="<?= $getdata['description'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="">Alamat Daycare :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="address" value="<?= $getdata['address'] ?>">
                                                </div>
                                            </div>
                                            <label for="">Pilih Lokasi Daycare :</label>
                                            <div id="google-maps" style="height: 400px; width:100%"></div>
                                            <!-- input untuk menampung koordinat -->
                                            <input type="hidden" name="longitude" value="<?= $getdata['longitude'] ?>" />
                                            <input type="hidden" name="latitude" value="<?= $getdata['latitude'] ?>" />
                                            <div class="form-group row">
                                                <label for="">Nomor Telepon Daycare :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="number" class="form-control form-control-user" name="phoneNumber" value="<?= $getdata['phoneNumber'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="">Fasilitas Daycare :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    Ruang Belajar<input type="checkbox" name="facilities[]" id="facilities" value="Ruang Belajar"><br>
                                                    Taman Bermain<input type="checkbox" name="facilities[]" id="facilities" value="Taman Bermain"><br>
                                                    Makan<input type="checkbox" name="facilities[]" id="facilities" value="Makan"><br>
                                                    Tempat Tidur<input type="checkbox" name="facilities[]" id="facilities" value="Tempat Tidur"><br>
                                                    Mandi<input type="checkbox" name="facilities[]" id="facilities" value="Mandi"><br>
                                                    <input type="text" class="form-control form-control-user" name="facilities[]" placeholder="Lainnya...">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="">Harga Daycare per Bulan:</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="number" class="form-control form-control-user" name="price" value="<?= $getdata['price'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="">Nomor Rekening Pembayaran Daycare :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="number" class="form-control form-control-user" name="accountNumber" value="<?= $getdata['accountNumber'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="">Kuota yang disediakan Daycare :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="number" class="form-control form-control-user" name="quota" value="<?= $getdata['quota'] ?>">
                                                </div>
                                            </div>
                                            <!-- <input type="hidden" name="profilePic" value=""> -->
                                            <input type="hidden" name="email" value="<?= $getdata['email'] ?>">
                                            <!-- <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label for="">Daycare Picture</label>
                                                    <input type="file" id="files" multiple>
                                                    <progress id="progress_bar" value="0" max="100"></progress>
                                                    <img src="" alt="" id="image">
                                                    <script src="js/uploadFiles.js" type="text/javascript"></script>
                                                    <input type="hidden" name="imageUrl" id="imageUrl" value="">
                                                </div>
                                            </div> -->
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label for="">Edit Foto Profil</label>
                                                    <input type="file" id="file">
                                                    <progress id="progress_bars" value="0" max="100"></progress>
                                                    <img src="<?= $getdata['profilePic'] ?>" alt="" id="image" style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
                                                    <script src="js/uploadFile.js" type="text/javascript"></script>
                                                    <input type="hidden" name="profilePic" id="profilePic" value="<?= $getdata['profilePic'] ?>">
                                                </div>
                                            </div>
                                            <button type="submit" name="edit-profile" class="btn btn-primary btn-user btn-block">Ubah Profil</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </body>

        <?php
        include('include/script.php');
        include('include/footer.php');
        ?>