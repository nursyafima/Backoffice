<?php
include('dbconfig.php');
if (isset($_SESSION['verified_user_id'])) {
  $uid = $_SESSION['verified_user_id'];
  $user = $auth->getUser($uid);
  $email = $user->email;
}
$replace = ["@", "."];
$email_replaced = str_replace($replace, "", $email);
$fetchDaycareName = $database->getReference("daycare/" . $email_replaced . "/profile/")->getValue();
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
  <br>
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon rotate-n-15">
    </div>
    <div class="sidebar-brand-text"><?= $fetchDaycareName['daycareName'] ?></div>
  </a>

  <!-- Divider -->
  <br>
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item active">
    <a class="nav-link" href="index.php">
      <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
      <span style="font-size: 15px;">Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->

  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item active">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnak" aria-expanded="true" aria-controls="collapseTwo">
      <span style="font-size: 15px;">Data Anak</span>
    </a>
    <div id="collapseAnak" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <form id="my_form1" action="childController.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form1').submit();">Lihat Data Anak</a>
          <input type="hidden" name="actionChild1" value="auto">
        </form>
        <a class="collapse-item" href="dataAnak.php">Tambah Data Anak</a>
        <form id="my_form2" action="childController.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form2').submit();">Rekap Data Anak</a>
          <input type="hidden" name="actionChild2" value="auto">
        </form>
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item active">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengasuh" aria-expanded="true" aria-controls="collapseTwo">
      <span style="font-size: 15px;">Data Pengasuh</span>
    </a>
    <div id="collapsePengasuh" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <form id="my_form3" action="caretakerController.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form3').submit();">Lihat Data Pengasuh</a>
          <input type="hidden" name="actionPengasuh1" value="auto">
        </form>
        <a class="collapse-item" href="dataPengasuh.php">Tambah Data Pengasuh</a>
        <form id="my_form4" action="caretakerController.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form4').submit();">Rekap Data Pengasuh</a>
          <input type="hidden" name="actionPengasuh2" value="auto">
        </form>
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item active">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAct" aria-expanded="true" aria-controls="collapseTwo">
      <span style="font-size: 15px;">Data Aktifitas</span>
    </a>
    <div id="collapseAct" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
      <form id="my_form8" action="activityController.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form8').submit();">Lihat Data Aktifitas</a>
          <input type="hidden" name="actionActivity" value="auto">
        </form>
        <a class="collapse-item" href="dataAktifitas.php">Tambah Data Aktifitas</a>
      </div>
    </div>
  </li>


  <li class="nav-item active">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePembayaran" aria-expanded="true" aria-controls="collapseUtilities">
      <span style="font-size: 15px;">Pembayaran</span>
    </a>
    <div id="collapsePembayaran" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <form id="my_form5" action="paymentController.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form5').submit();">Pembayaran</a>
          <input type="hidden" name="actionPembayaran" value="auto">
        </form>
        <form id="my_form6" action="invoice.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form6').submit();">List Invoice</a>
          <input type="hidden" name="actionInvoice" value="auto">
        </form>
      </div>
    </div>
  </li>

  <li class="nav-item active">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePemesanan" aria-expanded="true" aria-controls="collapseUtilities">
      <span style="font-size: 15px;">Pendaftaran Siswa Baru</span>
    </a>
    <div id="collapsePemesanan" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <form id="my_form7" action="bookingController.php" method="post">
          <a class="collapse-item" href="#" onclick="document.getElementById('my_form7').submit();">Pendaftaran Siswa Baru</a>
          <input type="hidden" name="actionPemesanan" value="auto">
        </form>
      </div>
    </div>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>


</ul>
<!-- End of Sidebar -->


<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="login.php">Logout</a>
      </div>
    </div>
  </div>
</div>