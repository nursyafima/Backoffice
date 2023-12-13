<?php

use Kreait\Firebase\Database\Query\Sorter\OrderByKey;

include('autentication.php');
include('include/header.php');
include('include/navbar.php');
?>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
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
        <!-- End of Topbar -->
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong><?php echo $_SESSION['status'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="overflow: auto;">
                    <h6 class="m-0 font-weight-bold text-primary">Data Aktifitas
                        <!-- <a href="#" onclick="history.back()" role="button" class="btn btn-danger" style="float: end;">Kembali</a> -->
                    </h6>
                    <br>
                    <label for="" style="font-weight: bold;">Cari Tanggal Aktifitas</label>
                    <br>
                    <form class="form-inline mr-auto w-100 navbar-search" action="activityController.php" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control bg-white border-2 small" placeholder="mm/dd/yyyy" onfocus="(this.type='date')" name="searchDate">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" name="searchDate-btn">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Aktifitas</th>
                                    <th>Aktifitas</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                    <th>Jam Aktifitas</th>
                                    <th>Gambar</th>
                                    <th>Video</th>
                                    <th>Sunting</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php
                                function checkStatuss($status)
                                {
                                    if (strcmp($status, "true")) {
                                        return "Belum Dilakukan";
                                    } else {
                                        return "Sudah Dilakukan";
                                    }
                                }
                                function make_slide_indicators($u)
                                {
                                    $output = '';
                                    $count = 0;
                                    $result = explode(",", $u);
                                    foreach ($result as $value) {
                                        if ($count == 0) {
                                            $output .= '<li data-target="#carouselExampleIndicators" data-slide-to="' . $count . '" class="active"></li>';
                                        } else {
                                            $output .= '<li data-target="#carouselExampleIndicators" data-slide-to="' . $count . '"></li>';
                                        }
                                        $count = $count + 1;
                                    }
                                    return $output;
                                }

                                function make_slide_indicators_video($u)
                                {
                                    $output = '';
                                    $count = 0;
                                    $result = explode(",", $u);
                                    foreach ($result as $value) {
                                        if ($count == 0) {
                                            $output .= '<li data-target="#carouselExampleIndicatorsVideo" data-slide-to="' . $count . '" class="active"></li>';
                                        } else {
                                            $output .= '<li data-target="#carouselExampleIndicatorsVideo" data-slide-to="' . $count . '"></li>';
                                        }
                                        $count = $count + 1;
                                    }
                                    return $output;
                                }


                                function make_slides($u)
                                {
                                    $output = '';
                                    $count = 0;
                                    $result = explode(",", $u);
                                    foreach ($result as $value) {
                                        if ($count == 0) {
                                            $output .= '<div class="carousel-item active">';
                                        } else {
                                            $output .= '<div class="carousel-item">';
                                        }
                                        $output .= '<img src="' . $value . '" width="350    "/>
                                                   </div>
                                                   ';
                                        $count = $count + 1;
                                    }
                                    return $output;
                                }
                                function make_slides_video($u)
                                {
                                    $output = '';
                                    $count = 0;
                                    $result = explode(",", $u);
                                    foreach ($result as $value) {
                                        if ($count == 0) {
                                            $output .= '<div class="carousel-item active">';
                                        } else {
                                            $output .= '<div class="carousel-item">';
                                        }
                                        $output .= '<video src="' . $value . '" width="350" controls/>
                                                   </div>
                                                   ';
                                        $count = $count + 1;
                                    }
                                    return $output;
                                }
                                // include('dbconfig.php');
                                // if (isset($_SESSION['verified_user_id'])) {
                                //     $uid = $_SESSION['verified_user_id'];
                                //     $user = $auth->getUser($uid);
                                //     $email = $user->email;
                                // }
                                // $replace = ["@", "."];
                                // $email_replaced = str_replace($replace, "", $email);
                                // $ref_table = 'daycare/' . $email_replaced . '/activity/';
                                // $search_result = null;
                                // $fetchdata = null;
                                // if (isset($_POST['searchDate-btn'])) {
                                //     $search_key = $_POST['searchDate'];
                                //     $search_result = $database->getReference($ref_table)->orderByKey()->equalTo($search_key)->getValue();
                                //     $serach_result_key = $database->getReference($ref_table . $search_key)->getSnapshot()->getReference()->getChildKeys();
                                // } else {
                                //     $fetchdata = $database->getReference($ref_table)->getValue();
                                // }
                                if (isset($_SESSION['searchKey']) != null) {
                                    $search_key = $_SESSION['searchKey'];
                                    $search_result_key = json_decode($_SESSION['searchResult'], true);
                                    $email = $_SESSION['email'];
                                    if ($search_result_key > 0 && $search_result_key != null) {
                                        $i = 1;
                                        foreach ($search_result_key as $key) {
                                            $ref_table = 'daycare/' . $email . '/activity/';
                                            $y = $database->getReference($ref_table . $search_key)->getChild($key)->getValue();
                                            print_r($y);
                                            if ($y['imageUrl'] == "") {
                                ?>
                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>Tidak Ada Gambar</td>
                                                    <td>
                                                        <div id="carouselExampleIndicatorsVideo" class="carousel slide" data-ride="carousel">
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides_video($y['videoUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } else if ($y['videoUrl'] == "") {
                                            ?>
                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>
                                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                <?php echo make_slide_indicators($y['imageUrl']); ?>
                                                            </ol>
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides($y['imageUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>Tidak Ada Video</td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } else if ($y['imageUrl'] == "" && $y['videoUrl'] == "") { ?>
                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>Tidak Ada Gambar</td>
                                                    <td>Tidak Ada Video</td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } else {
                                            ?>

                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>
                                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                <?php echo make_slide_indicators($y['imageUrl']); ?>
                                                            </ol>
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides($y['imageUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev" onclick="$('#carouselExampleIndicators').carousel('prev')">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next" onclick="$('#carouselExampleIndicators').carousel('next')">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="carouselExampleIndicatorsVideo" class="carousel slide" data-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                <?php make_slide_indicators_video($y['videoUrl']); ?>
                                                            </ol>
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides_video($y['videoUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="prev" onclick="$('#carouselExampleIndicatorsVideo').carousel('prev')">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="next" onclick="$('#carouselExampleIndicatorsVideo').carousel('next')">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        // } else if ($fetchdata > 0 && $search_result == null) {
                                        //     $i = 1;
                                        //     foreach ($fetchdata as $key => $row) { 
                                        ?>
                                        <tr style='text-align:center; vertical-align:middle;'>
                                            </td>
                                        </tr>
                                        <?php
                                        //     }
                                        ?>
                                    <?php
                                    } else { ?>
                                        <tr>
                                            <td colspan="10" style='text-align:center'>Tidak Ada Data</td>
                                        </tr>
                                        <?php
                                    }
                                } else if (isset($_SESSION['activityKey'])) {
                                    $search_key = $_SESSION['activityKey'];
                                    $search_result_key = json_decode($_SESSION['activityArray'], true);
                                    $email = $_SESSION['email'];
                                    if ($search_result_key > 0 && $search_result_key != null) {
                                        $i = 1;
                                        foreach ($search_result_key as $key) {
                                            $ref_table = 'daycare/' . $email . '/activity/' . $search_key;
                                            $y = $database->getReference($ref_table)->getChild($key)->getValue();
                                            if ($y['imageUrl'] == "") {
                                        ?>
                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>Tidak Ada Gambar</td>
                                                    <td>
                                                        <div id="carouselExampleIndicatorsVideo" class="carousel slide" data-ride="carousel">
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides_video($y['videoUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } else if ($y['videoUrl'] == "") {
                                            ?>
                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>
                                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                <?php echo make_slide_indicators($y['imageUrl']); ?>
                                                            </ol>
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides($y['imageUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>Tidak Ada Video</td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } else if ($y['imageUrl'] == "" && $y['videoUrl'] == "") { ?>
                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>Tidak Ada Gambar</td>
                                                    <td>Tidak Ada Video</td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } else {
                                            ?>

                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $search_key ?></td>
                                                    <td><?= $y['activity'] ?></td>
                                                    <td><?= $y['noted'] ?></td>
                                                    <td><?= checkStatuss($y['status']) ?></td>
                                                    <td><?= $y['time'] ?></td>
                                                    <td>
                                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                <?php echo make_slide_indicators($y['imageUrl']); ?>
                                                            </ol>
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides($y['imageUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev" onclick="$('#carouselExampleIndicators').carousel('prev')">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next" onclick="$('#carouselExampleIndicators').carousel('next')">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="carouselExampleIndicatorsVideo" class="carousel slide" data-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                <?php make_slide_indicators_video($y['videoUrl']); ?>
                                                            </ol>
                                                            <div class="carousel-inner">
                                                                <?php echo make_slides_video($y['videoUrl']); ?>
                                                            </div>
                                                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="prev" onclick="$('#carouselExampleIndicatorsVideo').carousel('prev')">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicatorsVideo" data-slide="next" onclick="$('#carouselExampleIndicatorsVideo').carousel('next')">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form action="editDataAktifitas.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <button type="submit" name="edit_act" value="<?= (string)$key; ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $search_key; ?>">
                                                            <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                                <button type="submit" name="del_act" value="<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                <?php
                                            }
                                        }
                                    }
                                } else {
                                    $_SESSION['status'] = "Masukkan Tanggal Pencarian";
                                    // header('Location: lihatDataAktifitas.php');
                                    // exit();
                                }
                                unset($_SESSION['searchKey']);
                                ?>
                            </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
    <?php
    include('include/script.php');
    include('include/footer.php');
    ?>