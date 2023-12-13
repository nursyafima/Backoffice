<?php

use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

session_start();
include('dbconfig.php');

if (isset($_POST['login-btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        if (empty($email)) {
            $_SESSION['status'] = "Email Anda Kosong";
            header('Location: login.php');
            exit();
        } else {
            $user = $auth->getUserByEmail("$email");
        }
        try {

            $signInResult = $auth->signInWithEmailAndPassword($email, $password);
            $idTokenString = $signInResult->idToken();

            try {
                $verifiedIdToken = $auth->verifyIdToken($idTokenString);
                $uid = $verifiedIdToken->claims()->get('sub');

                $_SESSION['verified_user_id'] = $uid;
                $_SESSION['idTokenString'] = $idTokenString;

                $_SESSION['status'] = "Berhasil Masuk, Jika belum mengupload foto daycare 
                silahkan upload melalui menu upload foto daycare pada nama dipojok kanan atas";
                header('Location: index.php');
                exit();
            } catch (FailedToVerifyToken $e) {
                echo 'The token is invalid: ' . $e->getMessage();
            }

            $uid = $verifiedIdToken->claims()->get('sub');

            $user = $auth->getUser($uid);
        } catch (Exception $e) {
            if (empty($password)) {
                $_SESSION['status'] = "Password Anda Kosong";
                header('Location: login.php');
                exit();
            } else {
                $_SESSION['status'] = "Email atau Password Salah";
                header('Location: login.php');
                exit();
            }
        }
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        $_SESSION['status'] = "Email atau Password Salah";
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION = "Not Allowed";
    header('Location: login.php');
    exit();
}
?>