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

<?php

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
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $getdata['daycareName']; ?></span>
                        <img class="img-profile rounded-circle" src="<?= $getdata['profilePic']; ?>">
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
                                            <h1 class="h4 text-gray-900 mb-4">Tambah Data Anak</h1>
                                        </div>
                                        <?php
                                        if (isset($_SESSION['verified_user_id'])) {
                                            $uid = $_SESSION['verified_user_id'];
                                            $user = $auth->getUser($uid);
                                            $email = $user->email;
                                            $replace = ["@", "."];
                                            $email_replaced = str_replace($replace, "", $email);
                                            $ref_table = 'daycare/' . $email_replaced . '/profile';
                                            $getdata = $database->getReference($ref_table)->getValue();
                                        }
                                        ?>
                                        <form class="user" action="childController.php" method="post">
                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                            <input type="hidden" class="form-control form-control-user" name="quota" value="<?= $getdata['quota']; ?>">
                                            <input type="hidden" class="form-control form-control-user" name="currQuota" value="<?= $getdata['currentQuota']; ?>">
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Nama Depan Anak :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="firstName" placeholder="Nama Depan Anak">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Nama Belakang Anak :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="lastName" placeholder="Nama Belakang Anak">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Jenis Kelamin Anak :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <select class="form-control form-control-select" name="gender">
                                                        <option value="Laki-laki">Laki-laki</option>
                                                        <option value="Perempuan">Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" style="color: black;">Tanggal Lahir Anak :</label>
                                                <input type="text" class="form-control form-control-user" name="birthDate" onfocus="(this.type='date')" placeholder="Tanggal Lahir">
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Nama Orang Tua Anak :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="parent" placeholder="Orang Tua">
                                                </div>
                                            </div>
                                            <!-- <label for="checkbox">Status Anak (Terdaftar atau Tidak Terdaftar):</label>
                                            <label class="switch">
                                                <input type="checkbox" name="status">
                                                <span class="slider round"></span>
                                            </label> -->
                                            <button type="submit" name="simpan_anak" class="btn btn-primary btn-user btn-block" onclick="window.location.href='lihatDataAnak.php'">Simpan Data</button>
                                        </form>
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