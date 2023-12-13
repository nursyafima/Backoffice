<?php
// function getPembayaran(){
//     if (isset($_SESSION['verified_user_id'])) {
//         $uid = $_SESSION['verified_user_id'];
//         $user = $auth->getUser($uid);
//         $email = $user->email;
//     }
//     $replace = ["@", "."];
//     $email_replaced = str_replace($replace, "", $email);
//     $ref_table = 'daycare/' . $email_replaced . '/booking/';
//     $search_result = null;
//     $fetchdata = null;
//     $fetchdata = $database->getReference($ref_table)->getValue();
//     include 'pembayaran.php';
// }


// if (isset($_POST['search-btn'])) {
//     $search_key = $_POST['search-key'];
//     $search_result = $database->getReference($ref_table)->orderByChild(strtolower('nama'))->startAt(strtolower($search_key))
//         ->endAt(strtolower($search_key .= "\uf8ff"))->getValue();
// } else {
//     $fetchdata = $database->getReference($ref_table)->getValue();
// }
// if ($search_result > 0 && $search_result != null) {
//     $i = 1;
//     foreach ($search_result as $key => $row) {
//     }
// } else if ($fetchdata > 0 && $search_result == null) {
//     $i = 1;
//     foreach ($fetchdata as $key => $row) {
//         $userKey = $row['UID'];
//         $childKey = $row['childID'];
//         $userRef = 'user/' . $userKey;
//         $fetchdataUser = $database->getReference($userRef)->getValue();
//         $fetchdataChild = $database->getReference($userRef . '/child/' . $childKey)->getValue();
//         if ($row['status'] == 'accepted' or $row['status'] == 'finished') {
//         }
//         // print_r($key);
//         // print_r($row['UID']);
//         // print_r($row['childID']);
//     }

// } else {

// }
