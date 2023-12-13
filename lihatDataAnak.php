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
            <?php
            if (isset($_SESSION['status'])) :
            ?>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong><?php echo $_SESSION['status'] ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            endif
            ?>
            <!-- DataTales Example -->
            <div class="card shadow mb-5">
                <div class="card-header py-3" style="overflow: auto;">
                    <h6 class="m-0 font-weight-bold text-primary">Data Anak <?= (int) $getdata['quota'] - (int) $getdata['currentQuota'] . "/" . (int) $getdata['quota'] ?>
                        <!-- <h6 class="m-0 font-weight-bold text-primary"></h6> -->
                        <a href="#" onclick="window.location.reload(true)" role="button"><img src="img/refresh.png" alt="" style="width:28px;height:28px;"></a>
                    </h6>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Anak</th>
                                    <th>Jenis kelamin</th>
                                    <th>Tempat Tanggal Lahir</th>
                                    <th>Umur</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php
                                function getAge($lahir)
                                {
                                    $bday = new DateTime($lahir);
                                    $today = new DateTime(date('m.d.y'));
                                    $diff = $today->diff($bday);
                                    return $diff->y . ' Tahun, ' . $diff->m . ' Bulan';
                                    $payload = file_get_contents('https://firebasestorage.googleapis.com/v0/b/example.appspot.com/o/Photos%2Fpic.jpeg');
                                    $data = json_decode($payload);
                                    echo $data->downloadTokens;
                                }
                                function console_log($output, $with_script_tags = true)
                                {
                                    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
                                        ');';
                                    if ($with_script_tags) {
                                        $js_code = '<script>' . $js_code . '</script>';
                                    }
                                    echo $js_code;
                                }
                                ?>
                                <?php
                                if (isset($_SESSION['child1'])) {
                                    $fetchdata = json_decode($_SESSION['child1'], true);
                                    if ($fetchdata > 0) {
                                        $i = 0;
                                        $num = 1;
                                        foreach ($fetchdata as $key => $row) {
                                            $i++;
                                            $age = getAge($row['birthDate']);
                                ?>
                                            <tr style='text-align:center; vertical-align:middle;'>
                                                <td><?= $num++; ?></td>
                                                <td><?= $row['firstName'] . " " . $row['lastName'] ?></td>
                                                <td><?= $row['gender'] ?></td>
                                                <td><?= $row['birthDate'] ?></td>
                                                <td><?= $age ?></td>
                                                <td><?= $row['parent'] ?></td>
                                                <td><?= $row['status'] ?></td>
                                                <td>
                                                    <form action="editDataAnak.php" method="POST">
                                                        <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                        <button type="submit" name="edit_anak" value="<?= $key ?>" class="btn btn-primary btn-sm">Sunting</button></a>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="childController.php" method="POST">
                                                        <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                                        <a href="deletelink" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')">
                                                            <button type="submit" name="hapus_anak" value="<?= $key ?>" class="btn btn-danger btn-sm">Hapus</button></a>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        if (isset($_POST['hapus_anak'])) {
                                            $currentQuota = (int) $getdata['currentQuota']++;
                                            $currQuota = [
                                                'currentQuota' => $currentQuota,
                                            ];
                                            $quota = 'daycare/' . $email_replaced . '/profile/';
                                            $postRef_result = $database->getReference($quota)->update($currQuota);
                                        } else {
                                            $currentQuota = $getdata['quota'] -= $i;
                                            $currQuota = [
                                                'currentQuota' => $currentQuota,
                                            ];
                                            $quota = 'daycare/' . $email_replaced . '/profile/';
                                            $postRef_result = $database->getReference($quota)->update($currQuota);
                                        }
                                        ?>
                                    <?php
                                    } else { ?>
                                        <tr>
                                            <td colspan="10" style='text-align:center'>Tidak Ada Data</td>
                                        </tr>
                                <?php
                                    }
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