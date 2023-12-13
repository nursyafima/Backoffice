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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkPIS-kPEurrL0ciFQ_a_7x7iz2lojCMY"></script>
<!-- <script type="text/javascript">
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
        $("input[name=longitude]").val(posisiTitik.lat());
        $("input[name=latitude]").val(posisiTitik.lng());

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
</script> -->
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

                <!-- Outer Row -->
                <div class="row justify-content-center">

                    <div class="col-xl-5 col-lg-6 col-md-1">

                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-0">
                                <!-- Nested Row within Card Body -->
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Tambah Foto</h1>
                                        </div>
                                        <form class="user" action="code.php" method="post">
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <b style="font-size: 20px; color:black; ">Silahkan tambahkan foto Daycare pada form dibawah ini untuk
                                                        ditampilkan pada pencarian Daycare</b>
                                                    <label for="">(note: Dapat mengupload beberapa foto)</label>
                                                    <br>
                                                    <br>
                                                    <input type="hidden" name="email" value="<?= $email ?>">
                                                    <input type="file" id="files" multiple>
                                                    <progress id="progress_bar" value="0" max="100"></progress>
                                                    <img src="" alt="" id="image">
                                                    <script src="js/uploadFiles.js" type="text/javascript"></script>
                                                    <input type="hidden" name="imageUrl" id="imageUrl" value="">
                                                </div>
                                            </div>
                                            <button type="submit" name="addPhoto" class="btn btn-primary btn-user btn-block">Tambah Foto</button>
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