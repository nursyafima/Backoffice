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
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
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
                <li class="nav-item dropdown no-arrow">
                    <?php
                    if (isset($_SESSION['verified_user_id'])) {
                        $uid = $_SESSION['verified_user_id'];
                        $user = $auth->getUser($uid);
                        $email = $user->email;
                        $replace = ["@", "."];
                        $email_replaced = str_replace($replace, "", $email);
                        $ref_table = 'daycare/' . $email_replaced;
                        $getdata = $database->getReference($ref_table . '/profile/')->getValue();
                        $checkBooking = $database->getReference($ref_table . '/booking/')->getSnapshot()->hasChildren();
                        $checkInvoice = $database->getReference($ref_table . '/invoice/')->getSnapshot()->hasChildren();
                        $checkInvoices = $database->getReference($ref_table . '/invoice/')->getChildKeys();
                        $checkChild = $database->getReference($ref_table . '/child/')->getSnapshot()->hasChildren();
                        $checkPengasuh = $database->getReference($ref_table . '/caretaker/')->getSnapshot()->hasChildren();
                    }
                    ?>
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
                        <a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong><?php echo $_SESSION['status'] ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Selamat Datang, <?= $getdata['daycareName'] ?></h1>
            </div>


            <!-- Content Row -->
            <div class="row">
                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Jumlah Anak</div>
                                    <?php
                                    if ($checkChild == false) {
                                        $total = 0;
                                    } else {
                                        $getChild = $database->getReference($ref_table . '/child/')->getChildKeys();
                                        for ($i = 0; $i <= sizeOf($getChild); $i++) {
                                            $total = $i;
                                        }
                                    }
                                    ?>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Jumlah Pengasuh</div>
                                    <?php
                                    if ($checkPengasuh == false) {
                                        $total = 0;
                                    } else {
                                        $getPengasuh = $database->getReference($ref_table . '/caretaker/')->getChildKeys();
                                        for ($i = 0; $i <= sizeOf($getPengasuh); $i++) {
                                            $total = $i;
                                        }
                                    }
                                    ?>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Jumlah Pemesanan</div>
                                    <?php
                                    if ($checkBooking == false) {
                                        $total = 0;
                                    } else {
                                        $getBooking = $database->getReference($ref_table . '/booking/')->getChildKeys();
                                        for ($i = 0; $i <= sizeOf($getBooking); $i++) {
                                            $total = $i;
                                        }
                                    }
                                    ?>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Jumlah Invoice</div>
                                    <?php
                                    if (in_array('1000', $checkInvoices)) {
                                        $total = 0;
                                    } else {
                                        $getInvoice = $database->getReference($ref_table . '/invoice/')->getChildKeys();
                                        for ($i = 0; $i <= sizeOf($getInvoice); $i++) {
                                            $total = $i;
                                        }
                                    }
                                    ?>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total ?></div>
                                </div>
                            </div>
                        </div>
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

</div>
<!-- End of Page Wrapper -->
<?php
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