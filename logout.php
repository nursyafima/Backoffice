<?php
session_start();

unset($_SESSION['verified_user_id']);
unset($_SESSION['idTokenString']);

$_SESSION ['status']= "Berhasil Keluar";
    header('Location: login.php');
    exit();
?>