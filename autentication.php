<?php
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
session_start();
include('dbconfig.php');

if(isset($_SESSION['verified_user_id'])){
    $verifiedIdToken = $_SESSION['verified_user_id'];
    $uid = $_SESSION['idTokenString'];
    try {
        $verifiedIdToken = $auth->verifyIdToken($uid);
    } catch (FailedToVerifyToken $e) {
    $_SESSION['status'] = "Anda Sudah Masuk";
    header('Location: index.php');
    exit();
    }

}else{
    $_SESSION['gagal'] = "Masuk Terlebih Dahulu untuk Mengakses Fitur";
    header('Location: login.php');
    exit();
}
?>