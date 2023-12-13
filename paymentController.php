<?php
session_start();
include('dbconfig.php');


if (isset($_POST['actionPembayaran'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/booking/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['pembayaran'] = $json;
    header('Location: pembayaran.php');
    exit();
}
