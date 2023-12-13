<?php
include('autentication.php');
include('dbconfig.php');
?>
<!-- <script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-storage.js"></script>
<script src="js/init.js" type="text/javascript"></script> -->

<?php
if (isset($_POST['pdf'])) {
  $key = $_POST['key'];
  $invoiceID = 0;
  $invoiceUrl = "null";
  $userKey = $_POST['UID'];
  $childKey = $_POST['childKey'];
  $email  = $_POST['email'];
  $date = $_POST['date'];
  $parentName = $_POST['parentName'];
  $childName = $_POST['childName'];
  $parentPhone = $_POST['parentPhone'];
  $parentEmail = $_POST['parentEmail'];
  $desc = $_POST['desc'];
  $price = $_POST['price'];
  $status = "finished";
  $replace = ["@", "."];
  $email_replaced = str_replace($replace, "", $email);
  $userRef = 'user/' . $userKey;
  $ref_table_payment = 'daycare/' . $email_replaced;
  $ref_table_child = 'daycare/' . $email_replaced . '/child/';
  $ref_table_booking = 'daycare/' . $email_replaced . '/booking/' . $key;
  $fetchDataDaycare = $database->getReference($ref_table_payment)->getChild('profile')->getValue();
  // $fetchDataInvoice = $database->getReference($ref_table_payment)->getChild('invoice/')->getValue();
  $fetchdataUser = $database->getReference($userRef)->getValue();
  $fetchdataChild = $database->getReference($userRef . '/child/' . $childKey)->getValue();
  $fetchDataInvoice = $database->getReference($ref_table_payment . "/invoice/")->getChildKeys();
  // Require composer autoload
  require_once __DIR__ . '/vendor/autoload.php';
  // Create an instance of the class:
  if (checkData($fetchDataInvoice, 1000)) {
    $replaces = ["-"];
    $daycareName = $fetchDataDaycare['daycareName'];
    $daycareNameReplaced = substr(str_replace(' ', '', $daycareName), 0, 3);
    $childNameReplaced = substr(str_replace(' ', '', $childName), 0, 3);
    console_log($childNameReplaced);
    (int)$invoiceID += 1;
    $newID = "INV_" . str_replace($replaces, "", $date) . "_" . $daycareNameReplaced . "_" . $childNameReplaced . "_" . $invoiceID;
    $mpdf = new \Mpdf\Mpdf();
    $html = '<!DOCTYPE html>
         <html lang="en">
           <head>
             <meta charset="utf-8">
             <title>Example 1</title>
             <link rel="stylesheet" href="css/invoiceStyle.css" media="all" />
           </head>
           <body>
             <header class="clearfix">
               <h1>INVOICE</h1>
               <div id="daycare" class="clearfix">
               <h2>' . $newID . '</h2>
               <h3>' . $date . '</h3>
               <br>
               <div>Dari :</div>
                 <div>' . $fetchDataDaycare['daycareName'] . '</div>
                 <div>' . $fetchDataDaycare['address'] . '</div>
                 <div>' . $fetchDataDaycare['phoneNumber'] . '</div>
                 <div>' . $fetchDataDaycare['email'] . '</div>
               </div>
               <br>
               <br>
               <div>Kepada :</div>
               <div id="parent">
                 <div>' . $parentName . '</div>
                 <div>' . $childName . '</div>
                 <div>' . $parentPhone . '</div>
                 <div>' . $parentEmail . '</div>
               </div>
             </header>
             <main>
               <table>
                 <thead>
                   <tr>
                     <th class="desc" colspan="2">Deskripsi</th>
                     <th>Total</th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr>
                     <td class="desc" colspan="2">' . $desc . '</td>
                     <td class="total">' . $price . '</td>
                   </tr>
                   <tr>
                     <td colspan="2" class="Total">TOTAL</td>
                     <td class="grand total">' . $price . '</td>
                   </tr>
                 </tbody>
               </table>
             </main>
             <footer>
               Invoice was created on a computer and is valid without the signature and seal.
             </footer>
           </body>
         </html>';

    // Write some HTML code:
    $mpdf->WriteHTML($html);
    $filepath = $mpdf->Output($newID . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);
  } else {
    if (checkData($fetchDataInvoice, $userKey . $childKey)) {
      $fetchAllInvoice = $database->getReference($ref_table_payment . "/invoice/" . $userKey . $childKey . "/")->getChildKeys();
      $i = sizeof($fetchAllInvoice);
      $newKey = $i += 1;
      $replaces = ["-"];
      $daycareName = $fetchDataDaycare['daycareName'];
      $daycareNameReplaced = substr(str_replace(' ', '', $daycareName), 0, 3);
      $childNameReplaced = substr(str_replace(' ', '', $childName), 0, 3);
      console_log($childNameReplaced);
      (int)$invoiceID += 1;
      $newID = "INV_" . str_replace($replaces, "", $date) . "_" . $daycareNameReplaced . "_" . $childNameReplaced . "_" . $newKey;
      $mpdf = new \Mpdf\Mpdf();
      $html = '<!DOCTYPE html>
           <html lang="en">
             <head>
               <meta charset="utf-8">
               <title>Example 1</title>
               <link rel="stylesheet" href="css/invoiceStyle.css" media="all" />
             </head>
             <body>
               <header class="clearfix">
                 <h1>INVOICE</h1>
                 <div id="daycare" class="clearfix">
                 <h2>' . $newID . '</h2>
                 <h3>' . $date . '</h3>
                 <br>
                 <div>Dari :</div>
                   <div>' . $fetchDataDaycare['daycareName'] . '</div>
                   <div>' . $fetchDataDaycare['address'] . '</div>
                   <div>' . $fetchDataDaycare['phoneNumber'] . '</div>
                   <div>' . $fetchDataDaycare['email'] . '</div>
                 </div>
                 <br>
                 <br>
                 <div>Kepada :</div>
                 <div id="parent">
                   <div>' . $parentName . '</div>
                   <div>' . $childName . '</div>
                   <div>' . $parentPhone . '</div>
                   <div>' . $parentEmail . '</div>
                 </div>
               </header>
               <main>
                 <table>
                   <thead>
                     <tr>
                       <th class="desc" colspan="2">Deskripsi</th>
                       <th>Total</th>
                     </tr>
                   </thead>
                   <tbody>
                     <tr>
                       <td class="desc" colspan="2">' . $desc . '</td>
                       <td class="total">' . $price . '</td>
                     </tr>
                     <tr>
                       <td colspan="2" class="Total">TOTAL</td>
                       <td class="grand total">' . $price . '</td>
                     </tr>
                   </tbody>
                 </table>
               </main>
               <footer>
                 Invoice was created on a computer and is valid without the signature and seal.
               </footer>
             </body>
           </html>';

      // Write some HTML code:
      $mpdf->WriteHTML($html);
      $filepath = $mpdf->Output($newID . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);
    } else {
      $replaces = ["-"];
      $daycareName = $fetchDataDaycare['daycareName'];
      $daycareNameReplaced = substr(str_replace(' ', '', $daycareName), 0, 3);
      $childNameReplaced = substr(str_replace(' ', '', $childName), 0, 3);
      console_log($childNameReplaced);
      (int)$invoiceID += 1;
      $newID = "INV_" . str_replace($replaces, "", $date) . "_" . $daycareNameReplaced . "_" . $childNameReplaced . "_" . $invoiceID;
      $mpdf = new \Mpdf\Mpdf();

      $html = '<!DOCTYPE html>
         <html lang="en">
           <head>
             <meta charset="utf-8">
             <title>Example 1</title>
             <link rel="stylesheet" href="css/invoiceStyle.css" media="all" />
           </head>
           <body>
             <header class="clearfix">
               <h1>INVOICE</h1>
               <div id="daycare" class="clearfix">
               <h2>' . $newID . '</h2>
               <h3>' . $date . '</h3>
               <br>
               <div>Dari :</div>
                 <div>' . $fetchDataDaycare['daycareName'] . '</div>
                 <div>' . $fetchDataDaycare['address'] . '</div>
                 <div>' . $fetchDataDaycare['phoneNumber'] . '</div>
                 <div>' . $fetchDataDaycare['email'] . '</div>
               </div>
               <br>
               <br>
               <div>Kepada :</div>
               <div id="parent">
                 <div>' . $parentName . '</div>
                 <div>' . $childName . '</div>
                 <div>' . $parentPhone . '</div>
                 <div>' . $parentEmail . '</div>
               </div>
             </header>
             <main>
               <table>
                 <thead>
                   <tr>
                     <th class="desc" colspan="2">Deskripsi</th>
                     <th>Total</th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr>
                     <td class="desc" colspan="2">' . $desc . '</td>
                     <td class="total">' . $price . '</td>
                   </tr>
                   <tr>
                     <td colspan="2" class="Total">TOTAL</td>
                     <td class="grand total">' . $price . '</td>
                   </tr>
                 </tbody>
               </table>
             </main>
             <footer>
               Invoice was created on a computer and is valid without the signature and seal.
             </footer>
           </body>
         </html>';

      // Write some HTML code:
      $mpdf->WriteHTML($html);
      $filepath = $mpdf->Output($newID . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);
    }
  }
}

if (isset($_POST['save_payment'])) {
  $key = $_POST['key'];
  $invoiceID = 0;
  $invoiceUrl = $_POST['invoiceUrl'];
  $userKey = $_POST['UID'];
  $childKey = $_POST['childKey'];
  $email  = $_POST['email'];
  $date = $_POST['date'];
  $parentName = $_POST['parentName'];
  $childName = $_POST['childName'];
  $parentPhone = $_POST['parentPhone'];
  $parentEmail = $_POST['parentEmail'];
  $desc = $_POST['desc'];
  $price = $_POST['price'];
  $status = "finished";
  $replace = ["@", "."];
  $email_replaced = str_replace($replace, "", $email);
  $userRef = 'user/' . $userKey;
  $ref_table_payment = 'daycare/' . $email_replaced;
  $ref_table_child = 'daycare/' . $email_replaced . '/child/';
  $ref_table_booking = 'daycare/' . $email_replaced . '/booking/' . $key;
  $fetchDataDaycare = $database->getReference($ref_table_payment)->getChild('profile')->getValue();
  $fetchdataUser = $database->getReference($userRef)->getValue();
  $fetchdataChild = $database->getReference($userRef . '/child/' . $childKey)->getValue();
  $fetchDataInvoice = $database->getReference($ref_table_payment . "/invoice/")->getChildKeys();
  if (empty($date)) {
    $_SESSION['status'] = "Tanggal Pembayaran Kosong";
    header('Location: bayar.php');
    exit();
  } else if(empty($desc)){
    $_SESSION['status'] = "Deskripsi Pembayaran Kosong";
    header('Location: bayar.php');
    exit();
  }
  // Require composer autoload
  require_once __DIR__ . '/vendor/autoload.php';
  // Create an instance of the class:
  if (checkData($fetchDataInvoice, 1000)) {
    $database->getReference($ref_table_payment . "/invoice/")->removeChildren(["1000"]);
    $replaces = ["-"];
    $daycareName = $fetchDataDaycare['daycareName'];
    $daycareNameReplaced = substr(str_replace(' ', '', $daycareName), 0, 3);
    $childNameReplaced = substr(str_replace(' ', '', $childName), 0, 3);
    (int)$invoiceID += 1;
    $newID = "INV_" . str_replace($replaces, "", $date) . "_" . $daycareNameReplaced . "_" . $childNameReplaced . "_" . $invoiceID;
    $replace = ["_"];
    $id_replaced = str_replace($replace, "", $newID);
    $updateChild = [
      // 'firstName' => $fetchdataChild['firstName'],
      // 'lastName' => $fetchdataChild['lastName'],
      // 'birthDate' => $fetchdataChild['birthDate'],
      // 'gender' => $fetchdataChild['gender'],
      // 'daycareID' => $fetchdataChild['daycareID'],
      'status' => "registered",
    ];
    $accept_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
    $updateBooking = [
      // 'UID' => $userKey,
      // 'childID' => $childKey,
      // 'childName' => $childName,
      // 'date' => $date,
      'lastPayment' => $date,
      'status' => $status,
    ];
    $setInvoice = [
      'invoiceID' => $id_replaced,
      'date' => $date,
      'invoiceUrl' => $invoiceUrl,
      'UID' => $userKey,
      'childID' => $childKey,
      'status' => $status,
      'childName' => $childName,
      'desc' => $desc,
      'price' => $price,
    ];
    $setInvoiceUser = [
      'invoiceID' => $id_replaced,
      'date' => $date,
      'invoiceUrl' => $invoiceUrl,
      'UID' => $userKey,
      'childID' => $childKey,
      'daycareName' => $daycareName,
      'childName' => $childName,
      'desc' => $desc,
    ];
    $payment_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
    $payment_result_invoice = $database->getReference($ref_table_payment . "/invoice/" . $userKey . $childKey . "/" . $id_replaced)->set($setInvoice);
    $payment_result_invoice_user = $database->getReference($userRef . "/invoice/" . $id_replaced)->set($setInvoiceUser);
  } else {
    if (checkData($fetchDataInvoice, $userKey . $childKey)) {
      $fetchAllInvoice = $database->getReference($ref_table_payment . "/invoice/" . $userKey . $childKey . "/")->getChildKeys();
      $i =  sizeof($fetchAllInvoice);
      $newKey = $i + 1;
      $replaces = ["-"];
      $daycareName = $fetchDataDaycare['daycareName'];
      $daycareNameReplaced = substr(str_replace(' ', '', $daycareName), 0, 3);
      $childNameReplaced = substr(str_replace(' ', '', $childName), 0, 3);
      $newID = "INV_" . str_replace($replaces, "", $date) . "_" . $daycareNameReplaced . "_" . $childNameReplaced . "_" . $newKey;
      $replace = ["_"];
      $id_replaced = str_replace($replace, "", $newID);
      $updateChild = [
        // 'firstName' => $fetchdataChild['firstName'],
        // 'lastName' => $fetchdataChild['lastName'],
        // 'birthDate' => $fetchdataChild['birthDate'],
        // 'gender' => $fetchdataChild['gender'],
        // 'daycareID' => $fetchdataChild['daycareID'],
        'status' => "registered",
      ];
      $accept_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
      $updateBooking = [
        // 'UID' => $userKey,
        // 'childID' => $childKey,
        // 'childName' => $childName,
        // 'date' => $date,
        'lastPayment' => $date,
        'status' => $status,
      ];
      $setInvoice = [
        'invoiceID' => $id_replaced,
        'date' => $date,
        'invoiceUrl' => $invoiceUrl,
        'UID' => $userKey,
        'childID' => $childKey,
        'status' => $status,
        'childName' => $childName,
        'desc' => $desc,
        'price' => $price,
      ];
      $setInvoiceUser = [
        'invoiceID' => $id_replaced,
        'date' => $date,
        'invoiceUrl' => $invoiceUrl,
        'UID' => $userKey,
        'childID' => $childKey,
        'daycareName' => $daycareName,
        'childName' => $childName,
        'desc' => $desc,
      ];
      $payment_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
      $payment_result_invoice = $database->getReference($ref_table_payment . "/invoice/" . $userKey . $childKey . "/" . $id_replaced)->set($setInvoice);
      $payment_result_invoice_user = $database->getReference($userRef . "/invoice/" . $id_replaced)->set($setInvoiceUser);
    } else {
      $replaces = ["-"];
      $daycareName = $fetchDataDaycare['daycareName'];
      $daycareNameReplaced = substr(str_replace(' ', '', $daycareName), 0, 3);
      $childNameReplaced = substr(str_replace(' ', '', $childName), 0, 3);
      $newID = "INV_" . str_replace($replaces, "", $date) . "_" . $daycareNameReplaced . "_" . $childNameReplaced . "_" . (int)$invoiceID += 1;
      $replace = ["_"];
      $id_replaced = str_replace($replace, "", $newID);
      $updateChild = [
        // 'firstName' => $fetchdataChild['firstName'],
        // 'lastName' => $fetchdataChild['lastName'],
        // 'birthDate' => $fetchdataChild['birthDate'],
        // 'gender' => $fetchdataChild['gender'],
        // 'daycareID' => $fetchdataChild['daycareID'],
        'status' => "registered",
      ];
      $accept_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
      $updateBooking = [
        // 'UID' => $userKey,
        // 'childID' => $childKey,
        // 'childName' => $childName,
        // 'date' => $date,
        'lastPayment' => $date,
        'status' => $status,
      ];
      $setInvoice = [
        'invoiceID' => $id_replaced,
        'date' => $date,
        'invoiceUrl' => $invoiceUrl,
        'UID' => $userKey,
        'childID' => $childKey,
        'status' => $status,
        'childName' => $childName,
        'desc' => $desc,
        'price' => $price,
      ];
      $setInvoiceUser = [
        'invoiceID' => $id_replaced,
        'date' => $date,
        'invoiceUrl' => $invoiceUrl,
        'UID' => $userKey,
        'childID' => $childKey,
        'daycareName' => $daycareName,
        'childName' => $childName,
        'desc' => $desc,
      ];
      $payment_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
      $payment_result_invoice = $database->getReference($ref_table_payment . "/invoice/" . $userKey . $childKey . "/" . $id_replaced)->set($setInvoice);
      $payment_result_invoice_user = $database->getReference($userRef . "/invoice/" . $id_replaced)->set($setInvoiceUser);
    }
  }
  if ($payment_result_invoice) {
    $ref_table = 'daycare/' . $email_replaced . '/booking/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['pembayaran'] = $json;
    $_SESSION['status'] = "Berhasil Melakukan Pembayaran";
    header('Location: pembayaran.php');
  } else {
    $ref_table = 'daycare/' . $email_replaced . '/booking/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['pembayaran'] = $json;
    $_SESSION['status'] = "Gagal Melakukan Pembayaran, Periksa kembali data yang anda masukkan";
    header('Location: pembayaran.php');
  }
}


if (isset($_POST['invoiceDownload'])) {
  console_log($key);
  $uid = $_POST['UID'];
  $childID = $_POST['childID'];
  $keyInvoice = $uid . $childID;
  $key = $_POST['invoiceDownload'];
  $email = $_POST['email'];
  $replace = ["@", "."];
  $email_replaced = str_replace($replace, "", $email);
  $daycareInfo = 'daycare/' . $email_replaced . '/profile/';
  $daycareRef = 'daycare/' . $email_replaced . '/invoice/' . $keyInvoice . "/" . $key;
  $daycareRefs = 'daycare/' . $email_replaced . '/invoice/';
  $userRef = 'user/' . $uid;
  $fetchdataUser = $database->getReference($userRef)->getValue();
  $fetchDataDaycare = $database->getReference($daycareInfo)->getValue();
  $fetchInvoiceKey = $database->getReference($daycareRefs)->getChildKeys();
  $fetchDataInvoice = $database->getReference($daycareRef)->getValue();
  $id = $fetchDataInvoice['invoiceID'];
  $replaces = ["-"];
  $daycareName = $fetchDataDaycare['daycareName'];
  $childName = $_POST['childName'];
  $daycareNameReplaced = substr(str_replace(' ', '', $daycareName), 0, 3);
  $childNameReplaced = substr(str_replace(' ', '', $childName), 0, 3);
  $newID = "INV_" . str_replace($replaces, "", $fetchDataInvoice['date']) . "_" . $daycareNameReplaced . "_" . $childNameReplaced . "_" . substr($id, -1);
  // $newID = "INV_" . str_replace($replaces, "", $fetchDataInvoice['date']) . "_" . substr($id, -1);
  require_once __DIR__ . '/vendor/autoload.php';
  $filename = $fetchDataInvoice['invoiceID'] . ".pdf";
  $mpdf = new \Mpdf\Mpdf();
  $html = '<!DOCTYPE html>
             <html lang="en">
               <head>
                 <meta charset="utf-8">
                 <title>Example 1</title>
                 <link rel="stylesheet" href="css/invoiceStyle.css" media="all" />
               </head>
               <body>
                 <header class="clearfix">
                   <h1>INVOICE</h1>
                   <div id="daycare" class="clearfix">
                   <h2>' . $newID . '</h2>
                   <h3>' . $fetchDataInvoice['date'] . '</h3>
                   <br>
                   <div>Dari :</div>
                     <div>' . $fetchDataDaycare['daycareName'] . '</div>
                     <div>' . $fetchDataDaycare['address'] . '</div>
                     <div>' . $fetchDataDaycare['phoneNumber'] . '</div>
                     <div>' . $fetchDataDaycare['email'] . '</div>
                   </div>
                   <br>
                   <br>
                   <div>Kepada :</div>
                   <div id="parent">
                     <div>' . $fetchdataUser['firstName'] . " " . $fetchdataUser['lastName'] . '</div>
                     <div>' . $fetchDataInvoice['childName'] . '</div>
                     <div>' . $fetchdataUser['phoneNumber'] . '</div>
                     <div>' . $fetchdataUser['email'] . '</div>
                   </div>
                 </header>
                 <main>
                   <table>
                     <thead>
                       <tr>
                         <th class="desc" colspan="2">Deskripsi</th>
                         <th>Total</th>
                       </tr>
                     </thead>
                     <tbody>
                       <tr>
                         <td class="desc" colspan="2">' . $fetchDataInvoice['desc'] . '</td>
                         <td class="total">' . $fetchDataInvoice['price'] . '</td>
                       </tr>
                       <tr>
                         <td colspan="2" class="Total">TOTAL</td>
                         <td class="grand total">' . $fetchDataInvoice['price'] . '</td>
                       </tr>
                     </tbody>
                   </table>
                 </main>
                 <footer>
                   Invoice was created on a computer and is valid without the signature and seal.
                 </footer>
               </body>
             </html>';

  $mpdf->WriteHTML($html);
  $filepath = $mpdf->Output($newID . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);

  if ($filepath) {
    $_SESSION['status'] = "Berhasil mengunduh Pembayaran";
    header('Location: pembayaran.php');
  } else {
    $_SESSION['status'] = "Berhasil Mengunduh Pembayaran";
    header('Location: pembayaran.php');
  }
}

if (isset($_POST['actionInvoice'])) {
  if (isset($_SESSION['verified_user_id'])) {
      $uid = $_SESSION['verified_user_id'];
      $user = $auth->getUser($uid);
      $email = $user->email;
  }
  $replace = ["@", "."];
  $email_replaced = str_replace($replace, "", $email);
  $ref_table = 'daycare/' . $email_replaced . '/invoice/';
  $fetchdata = $database->getReference($ref_table)->getValue();
  $array = (array) $fetchdata;
  $json = json_encode($array);
  $_SESSION['invoiceList'] = $json;
  header('Location: invoiceList.php');
  exit();
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

function checkData($str1, $str2)
{
  $b = "";
  foreach ($str1 as $activityRef) {
    $b = $b . "," . $activityRef;
  }
  return str_contains($activityRef, $str2);
}

// <input type="hidden" id="urlInvoice" value="<?= $fetchDataInvoice['invoiceUrl'] 
?>
<!-- // <script src="js/download.js" type="module"></script> -->