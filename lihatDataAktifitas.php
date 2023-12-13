<?php
include('autentication.php');
include('include/header.php');
include('include/navbar.php');
?>
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
                    <!-- <form class="form-inline mr-auto w-100 navbar-search" action="pembayaran.php" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control bg-white border-2 small" placeholder="Cari Tanggal Pembayaran" onfocus="(this.type='date')" name="searchDate">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" name="searchDate-btn">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Aktifitas</th>
                                    <th>Jumlah Aktifitas</th>
                                    <th>Lihat Aktifitas</th>
                                    <th>Tambah Aktifitas</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php
                                if (isset($_SESSION['activity'])) {
                                    $fetchdata = json_decode($_SESSION['activity'], true);
                                    if ($fetchdata > 0) {
                                        $i = 1;
                                        foreach ($fetchdata as $key => $row) {
                                            $activityKey = $database->getReference('daycare/'. $email_replaced. '/activity/' . $key)->getChildKeys();
                                            $count = sizeof($activityKey)
                                ?>
                                                <tr style='text-align:center; vertical-align:middle;'>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $key ?></td>
                                                    <td><?= $count ?></td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <button type="submit" name="activityClicked" value="<?= $key ?>" class="btn btn-info btn-sm">Lihat Aktifitas</button></a>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="activityController.php" method="POST">
                                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                            <button type="submit" name="saveActivity" value="<?= $key ?>" class="btn btn-info btn-sm">Tambah Aktifitas</button></a>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    <?php
                                    } else { ?>
                                        <tr>
                                            <td colspan="10" style='text-align:center'>Tidak Ada Data</td>
                                        </tr>
                                <?php
                                    }
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
    <!-- <?php
            if ($row['status'] == "request cancel" or $row['status'] == "cancelled" or $row['status'] == "accepted") { ?> disabled <?php } ?>  -->