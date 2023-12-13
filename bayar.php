<?php
if (isset($_SESSION['verified_user_id'])) {
    $uid = $_SESSION['verified_user_id'];
    $user = $auth->getUser($uid);
    $email = $user->email;
}
include('autentication.php');
include('dbconfig.php');
include('include/header.php');
include('include/navbar.php');
?>
<!-- <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-storage.js"></script>
<script src="js/init.js" type="text/javascript"></script> -->
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
                                            <h1 class="h4 text-gray-900 mb-4">Data Pembayaran</h1>
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
                                        if (isset($_POST['confirmPayment'])) {
                                            $key = $_POST['confirmPayment'];
                                            $userKey = $_POST['UID'];
                                            $childKey = $_POST['childID'];
                                            $email  = $_POST['email'];
                                            $date = $_POST['date'];
                                            $status = "finished";
                                            $replace = ["@", "."];
                                            $email_replaced = str_replace($replace, "", $email);
                                            $daycareRef = 'daycare/' . $email_replaced;
                                            $userRef = 'user/' . $userKey;
                                            $ref_table_booking = 'daycare/' . $email_replaced . '/booking/' . $key;
                                            $ref_table_child = 'daycare/' . $email_replaced . '/child/';
                                            $fetchdataUser = $database->getReference($userRef)->getValue();
                                            $fetchdataChild = $database->getReference($userRef . '/child/' . $childKey)->getValue();
                                            $fetchdataDaycare = $database->getReference($daycareRef . '/profile/')->getValue();
                                            $fetchdataBooking = $database->getReference($ref_table_booking)->getValue();
                                        }
                                        ?>
                                        <form class="user" action="invoice.php" method="post">
                                            <input type="hidden" class="form-control form-control-user" name="key" value="<?= $key; ?>">
                                            <input type="hidden" class="form-control form-control-user" name="email" value="<?= $email; ?>">
                                            <input type="hidden" class="form-control form-control-user" name="UID" value="<?= $userKey; ?>">
                                            <input type="hidden" class="form-control form-control-user" name="childKey" value="<?= $childKey; ?>">
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Tanggal Pembayaran :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="date" id="date" onfocus="(this.type='date')" placeholder="Tanggal Pembayaran">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Nama Orang Tua :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="parentName" readonly="true" value="<?= $fetchdataUser['firstName'] . " " . $fetchdataUser['lastName']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Nama Anak :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="childName" readonly="true" value="<?= $fetchdataBooking['firstName'] . " " . $fetchdataBooking['lastName']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Nomor Handphone Orang Tua :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="parentPhone" readonly="true" value="<?= $fetchdataUser['phoneNumber']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Email Orang Tua :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="parentEmail" readonly="true" value="<?= $fetchdataUser['email']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Tujuan Pembayaran :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="desc" placeholder="(contoh: Pembayaran Uang Bulanan)">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" style="color: black;">Harga :</label>
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" name="price" readonly="true" value="<?= $fetchdataDaycare['price']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label for="">Setelah mengisi form diatas, silahkan klik tombol dibawah untuk mendownload file.</label>
                                                    <button type="submit" name="pdf" class="btn btn-primary btn-user btn-block">Download PDF</button>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label for="">Setelah file didownload, silahkan upload file tersebut pada form dibawah ini</label>
                                                    <input type="file" id="file" accept="application/pdf,application/vnd.ms-excel">
                                                    <progress id="progress_bars" value="0" max="100"></progress>
                                                    <script src="js/uploadInvoice.js" type="text/javascript"></script>
                                                    <input type="hidden" class="form-control form-control-user" name="invoiceUrl" id="invoiceUrl" value="">
                                                </div>
                                            </div>
                                            <button type="submit" name="save_payment" class="btn btn-primary btn-user btn-block" onclick="window.location.href='pembayaran.php'">Simpan Data</button>
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