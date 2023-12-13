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
                    <h6 class="m-0 font-weight-bold text-primary">Data Pemesanan
                        <!-- <a href="#" onclick="history.back()" role="button" class="btn btn-danger" style="float: end;">Kembali</a> -->
                    </h6>
                    <br>
                    <!-- <form class="form-inline mr-auto w-100 navbar-search" action="lihatDataAktifitas.php" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control bg-white border-2 small" placeholder="Cari Tanggal Aktifitas" onfocus="(this.type='date')" name="searchDate">
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
                                    <th>Tanggal Pemesanan</th>
                                    <th>Nama Anak</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Nomor Handphone</th>
                                    <th>Status</th>
                                    <th>Terima</th>
                                    <th>Tolak</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>

                                <?php
                                if (isset($_SESSION['pemesanan'])) {
                                    $fetchdata = json_decode($_SESSION['pemesanan'], true);
                                    $i = 1;
                                    foreach ($fetchdata as $key => $row) {
                                        $userKey = $row['UID'];
                                        $childKey = $row['childID'];
                                        $userRef = 'user/' . $userKey;
                                        $fetchdataUser = $database->getReference($userRef)->getValue();
                                        $fetchdataChild = $database->getReference($userRef . '/child/' . $childKey)->getValue();
                                ?>
                                        <tr style='text-align:center; vertical-align:middle;'>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['date'] ?></td>
                                            <td><?= $row['firstName'] . " " . $row['lastName'] ?></td>
                                            <td><?= $fetchdataUser['firstName'] . " " . $fetchdataUser['lastName'] ?></td>
                                            <td><?= $fetchdataUser['phoneNumber'] ?></td>
                                            <td><?= $row['status'] ?></td>
                                            <td>
                                                <form action="bookingController.php" method="post">
                                                    <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="price" value="<?= $getdata['price']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="accountNumber" value="<?= $getdata['accountNumber']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="UID" value="<?= $row['UID']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childID" value="<?= $row['childID']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="date" value="<?= $row['date']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childFirstName" value="<?= $row['firstName']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childLastName" value="<?= $row['lastName']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childBirthDate" value="<?= $row['birthDate']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childGender" value="<?= $row['gender']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="status" value="<?= $row['status']; ?>">
                                                    <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menerima Pesanan Ini?')">
                                                        <button type="submit" name="accept_order" value="<?= $key ?>" <?php
                                                    if ($row['status'] == "rejected" or $row['status'] == "cancelled" or $row['status'] == "accepted" or $row['status'] == "finished" or $row['status'] == "uploaded") { ?> disabled <?php } ?> class="btn btn-primary btn-sm">Terima</button></a>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="bookingController.php" method="POST">
                                                    <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="UID" value="<?= $row['UID']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childID" value="<?= $row['childID']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="date" value="<?= $row['date']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childFirstName" value="<?= $row['firstName']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childLastName" value="<?= $row['lastName']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childBirthDate" value="<?= $row['birthDate']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="childGender" value="<?= $row['gender']; ?>">
                                                    <input type="hidden" class="form-control form-control-user" name="status" value="<?= $row['status']; ?>">
                                                    <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menolak Pesanan Ini?')">
                                                        <button type="submit" name="reject_order" value="<?= $key ?>" <?php
                                                    if ($row['status'] == "accepted" or $row['status'] == "cancelled" or $row['status'] == "finished" or $row['status'] == "rejected" or $row['status'] == "uploaded") { ?> disabled <?php } ?> class="btn btn-danger btn-sm">Tolak</button></a>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                        // print_r($key);
                                        // print_r($row['UID']);
                                        // print_r($row['childID']);
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