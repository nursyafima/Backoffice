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

<!-- <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-storage.js"></script>
<script src="backoffice/js/init.js" type="text/javascript"></script> -->

<?php

?>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
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
                        <br>
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
        <!-- End of Topbar -->
        <!-- End of Topbar -->


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
                                            <h1 class="h4 text-gray-900 mb-4">Tambah Data Aktifitas</h1>
                                        </div>
                                        <?php
                                        if (isset($_SESSION['verified_user_id'])) {
                                            $uid = $_SESSION['verified_user_id'];
                                            $user = $auth->getUser($uid);
                                            $email = $user->email;
                                        }
                                        if (isset($_SESSION['saveActivity'])) {
                                        ?>
                                            <form class="user" action="activityController.php" method="post">
                                                <input type="hidden" class="form-control form-control-user" name="email" id="email" value="<?= $email; ?>">
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Tanggal Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="dateAct" id="dateAct" readonly="true" value="<?= $_SESSION['saveActivity'] ?>" placeholder="Tanggal Aktifitas">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Nama Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="activity" placeholder="Nama Aktifitas">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Jam Aktifitas Dimulai :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="time1" onfocus="(this.type='time')" placeholder="Jam Aktifitas Awal">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Jam Aktifitas Berakhir :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="time2" onfocus="(this.type='time')" placeholder="Jam Aktifitas Akhir">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Catatan Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="noted" placeholder="Catatan">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Upload Gambar atau Video Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="file" id="files" multiple>
                                                        <progress id="progress_bar" value="0" max="100"></progress>
                                                        <img src="" alt="" id="image">
                                                        <script src="js/uploadAct.js" type="text/javascript"></script>
                                                        <input type="hidden" name="imageUrl" id="imageUrl" value="">
                                                        <input type="hidden" name="videoUrl" id="videoUrl" value="">
                                                    </div>
                                                </div>
                                                <label for="checkbox">Status Aktifitas<br>(Sudah atau Belum Dilakukan):</label>
                                                <br>
                                                <label class="switch">
                                                    <input type="checkbox" name="status" style="align-items:center">
                                                    <span class="slider round"></span>
                                                </label>
                                                <button type="submit" name="simpan_act" id="save" class="btn btn-primary btn-user btn-block">Simpan Data</button>
                                            </form>
                                        <?php
                                            unset($_SESSION['saveActivity']);
                                        } else {
                                            unset($_SESSION['saveActivity']);
                                        ?>
                                            <form class="user" action="activityController.php" method="post">
                                                <input type="hidden" class="form-control form-control-user" name="email" id="email" value="<?= $email; ?>">
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Tanggal Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="dateAct" id="dateAct" onfocus="(this.type='date')" placeholder="Tanggal Aktifitas">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Nama Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="activity" placeholder="Nama Aktifitas">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Jam Aktifitas Dimulai :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="time1" onfocus="(this.type='time')" placeholder="Jam Aktifitas Awal">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Jam Aktifitas Berakhir :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="time2" onfocus="(this.type='time')" placeholder="Jam Aktifitas Akhir">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Catatan Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" name="noted" placeholder="Catatan">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" style="color: black;">Upload Gambar atau Video Aktifitas :</label>
                                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                                        <input type="file" id="files" multiple>
                                                        <progress id="progress_bar" value="0" max="100"></progress>
                                                        <img src="" alt="" id="image">
                                                        <script src="js/uploadAct.js" type="text/javascript"></script>
                                                        <input type="hidden" name="imageUrl" id="imageUrl" value="">
                                                        <input type="hidden" name="videoUrl" id="videoUrl" value="">
                                                    </div>
                                                </div>
                                                <label for="checkbox">Status Aktifitas<br>(Sudah atau Belum Dilakukan):</label>
                                                <br>
                                                <label class="switch">
                                                    <input type="checkbox" name="status" style="align-items:center">
                                                    <span class="slider round"></span>
                                                </label>
                                                <button type="submit" name="simpan_act" id="save" class="btn btn-primary btn-user btn-block">Simpan Data</button>
                                            </form>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <?php
                include('include/script.php');
                include('include/footer.php');
                ?>