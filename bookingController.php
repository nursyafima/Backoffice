<?php
session_start();
include('dbconfig.php');

if (isset($_POST['accept_order'])) {
    $key = $_POST['accept_order'];
    $userKey = $_POST['UID'];
    $childKey = $_POST['childID'];
    $email  = $_POST['email'];
    $price = $_POST['price'];
    $accountNumber = $_POST['accountNumber'];
    $date = $_POST['date'];
    $childFirstName = $_POST['childFirstName'];
    $childLastName = $_POST['childLastName'];
    $childBirthDate = $_POST['childBirthDate'];
    $childGender = $_POST['childGender'];
    $status = $_POST['status'];
    $status_changed = "accepted";
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $userRef = 'user/' . $userKey;
    $ref_quota = $database->getReference('daycare/' . $email_replaced . '/profile/')->getValue();
    $currentQuota = $ref_quota['currentQuota'];
    $ref_table = 'daycare/' . $email_replaced . '/child/' . $childFirstName . $childLastName;
    $all_child = 'daycare/' . $email_replaced;
    $ref_table_booking = 'daycare/' . $email_replaced . '/booking/' . $key;
    $ref_table_child = 'daycare/' . $email_replaced . '/child/';
    $quota = 'daycare/' . $email_replaced . '/profile/';
    $getQuota = $database->getReference($quota)->getValue();
    $fetchdataUser = $database->getReference($userRef)->getValue();
    $fetchDataMovedChild = $database->getReference($ref_table)->getValue();
    // $fetchdataChild = $database->getReference($userRef . '/child/' . $childKey)->getValue();
    if ((int)$currentQuota = 0 && $status == "waiting") {
        $_SESSION['status'] = "Data gagal ditambahkan, Kuota Penuh";
        header('Location: pemesanan.php');
    } else {
        if ($status == "waiting") {
            $updateBooking = [
                // 'UID' => $userKey,
                // 'childID' => $childKey,
                // 'date' => $date,
                'price' => $price,
                'accountNumber' => $accountNumber,
                'status' => $status_changed,
            ];
            $accept_result_booking = $database->getReference('daycare/' . $email_replaced . '/booking/' . $key)->update($updateBooking);
            $updateChild = [
                // 'firstName' => $fetchdataChild['firstName'],
                // 'lastName' => $fetchdataChild['lastName'],
                // 'birthDate' => $fetchdataChild['birthDate'],
                // 'gender' => $fetchdataChild['gender'],
                // 'daycareID' => $fetchdataChild['daycareID'],
                'status' => "waiting",
            ];
            $accept_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
            // $transaction = [

            // ];
            // $accept_add_transaction = $database->getReference('daycare/' . $email_replaced . '/transaction/' . $key)->update($transaction);
            $addChild = [
                'firstName' => $childFirstName,
                'lastName' => $childLastName,
                'gender' => $childGender,
                'birthDate' => $childBirthDate,
                'age' => getAge($childBirthDate),
                'parent' => $fetchdataUser['firstName'] . " " . $fetchdataUser['lastName'],
                'status' => "Terdaftar",
            ];
            $add_child_result = $database->getReference('daycare/' . $email_replaced . '/child/' . $childKey)->set($addChild);
            $updateQuota = [
                'currentQuota' => $currentQuota -= 1,
            ];
            $quota = 'daycare/' . $email_replaced . '/profile/';
            $quota_update = $database->getReference($quota)->update($updateQuota);
            if ($accept_result_booking) {
                $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
                $array = (array) $fetchdata;
                $json = json_encode($array);
                $_SESSION['pemesanan'] = $json;
                $_SESSION['status'] = "Data berhasil diproses";
                header('Location: pemesanan.php');
            } else {
                $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
                $array = (array) $fetchdata;
                $json = json_encode($array);
                $_SESSION['pemesanan'] = $json;
                $_SESSION['status'] = "Data gagal diproses";
                header('Location: pemesanan.php');
            }
        } else if ($status == "request cancel") {
            $updateBooking = [
                // 'UID' => $userKey,
                // 'childID' => $childKey,
                // 'date' => $date,
                'status' => "cancelled",
            ];
            $cancel_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
            $updateChild = [
                // 'firstName' => $childFirstName,
                // 'lastName' => $childLastName,
                // 'birthDate' => $childBirthDate,
                // 'gender' => $childGender,
                'daycareID' => "null",
                'status' => "not registered",
            ];
            $cancel_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
            if ($cancel_result_booking) {
                $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
                $array = (array) $fetchdata;
                $json = json_encode($array);
                $_SESSION['pemesanan'] = $json;
                $_SESSION['status'] = "Data berhasil diproses";
                header('Location: pemesanan.php');
            } else {
                $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
                $array = (array) $fetchdata;
                $json = json_encode($array);
                $_SESSION['pemesanan'] = $json;
                $_SESSION['status'] = "Data gagal diproses";
                header('Location: pemesanan.php');
            }
        } else if ($status == "request quit") {
            $updateBooking = [
                // 'UID' => $userKey,
                // 'childID' => $childKey,
                // 'date' => $date,
                'status' => "cancelled",
            ];
            $cancel_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
            $updateChild = [
                // 'firstName' => $childFirstName,
                // 'lastName' => $childLastName,
                // 'birthDate' => $childBirthDate,
                // 'gender' => $childGender,
                'daycareID' => "null",
                'status' => "not registered",
            ];
            $cancel_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
            $move_child = [
                'firstName' => $fetchDataMovedChild['firstName'],
                'lastName' => $fetchDataMovedChild['lastName'],
                'gender' => $fetchDataMovedChild['gender'],
                'birthDate' => $fetchDataMovedChild['birthDate'],
                'age' => $fetchDataMovedChild['age'],
                'parent' => $fetchDataMovedChild['parent'],
                'status' => "Tidak Terdaftar",
            ];
            $move_child_result = $database->getReference($all_child . '/allChild/' . $childKey)->set($move_child);
            $delete_result = $database->getReference($ref_table)->remove();
            $currentQuota = (int) $getQuota['currentQuota'] += 1;
            $updateQuota = [
                'currentQuota' => $currentQuota,
            ];
            $update_result = $database->getReference($quota)->update($updateQuota);
            if ($cancel_result_booking) {
                $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
                $array = (array) $fetchdata;
                $json = json_encode($array);
                $_SESSION['pemesanan'] = $json;
                $_SESSION['status'] = "Data berhasil diproses";
                header('Location: pemesanan.php');
            } else {
                $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
                $array = (array) $fetchdata;
                $json = json_encode($array);
                $_SESSION['pemesanan'] = $json;
                $_SESSION['status'] = "Data gagal diproses";
                header('Location: pemesanan.php');
            }
        }
    }
}


if (isset($_POST['reject_order'])) {
    $key = $_POST['reject_order'];
    $userKey = $_POST['UID'];
    $childKey = $_POST['childID'];
    $email  = $_POST['email'];
    $date = $_POST['date'];
    $childFirstName = $_POST['childFirstName'];
    $childLastName = $_POST['childLastName'];
    $childBirthDate = $_POST['childBirthDate'];
    $childGender = $_POST['childGender'];
    $status = $_POST['status'];
    $status_changed = "rejected";
    $status_cancel = "waiting";
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $userRef = 'user/' . $userKey;
    $ref_table_booking = 'daycare/' . $email_replaced . '/booking/' . $key;
    $ref_table_child = 'daycare/' . $email_replaced . '/child/';
    $fetchdataUser = $database->getReference($userRef)->getValue();
    $fetchdataChild = $database->getReference($userRef . '/child/' . $childKey)->getValue();
    if ($status == "waiting") {
        $updateBooking = [
            // 'UID' => $userKey,
            // 'childID' => $childKey,
            // 'date' => $date,
            'status' => $status_changed,
        ];
        $reject_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
        $updateChild = [
            // 'firstName' => $fetchdataChild['firstName'],
            // 'lastName' => $fetchdataChild['lastName'],
            // 'birthDate' => $fetchdataChild['birthDate'],
            // 'gender' => $fetchdataChild['gender'],
            'daycareID' => "null",
            'status' => "not registered",
        ];
        $reject_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
        if ($reject_result_child) {
            $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pemesanan'] = $json;
            $_SESSION['status'] = "Data berhasil ditolak";
            header('Location: pemesanan.php');
        } else {
            $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pemesanan'] = $json;
            $_SESSION['status'] = "Data gagal ditolak";
            header('Location: pemesanan.php');
        }
    }
    if ($status == "request cancel") {
        $updateBooking = [
            // 'UID' => $userKey,
            // 'childID' => $childKey,
            // 'date' => $date,
            'status' => "waiting",
        ];
        $cancel_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
        $updateChild = [
            // 'firstName' => $fetchdataChild['firstName'],
            // 'lastName' => $fetchdataChild['lastName'],
            // 'birthDate' => $fetchdataChild['birthDate'],
            // 'gender' => $fetchdataChild['gender'],
            // 'daycareID' => $fetchdataChild['daycareID'],
            'status' => "waiting",
        ];
        $cancel_result_child = $database->getReference($userRef . '/child/' . $childKey)->update($updateChild);
        if ($cancel_result_child) {
            $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pemesanan'] = $json;
            $_SESSION['status'] = "Data berhasil diproses";
            header('Location: pemesanan.php');
        } else {
            $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pemesanan'] = $json;
            $_SESSION['status'] = "Data gagal diproses";
            header('Location: pemesanan.php');
        }
    } else if ($status == "request quit") {
        $updateBooking = [
            // 'UID' => $userKey,
            // 'childID' => $childKey,
            // 'date' => $date,
            'status' => "finished",
        ];
        $cancel_result_booking = $database->getReference($ref_table_booking)->update($updateBooking);
        if ($cancel_result_booking) {
            $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pemesanan'] = $json;
            $_SESSION['status'] = "Data berhasil diproses";
            header('Location: pemesanan.php');
        } else {
            $fetchdata = $database->getReference('daycare/' . $email_replaced  . '/booking/')->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pemesanan'] = $json;
            $_SESSION['status'] = "Data gagal diproses";
            header('Location: pemesanan.php');
        }
    }
}

if (isset($_POST['actionPemesanan'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced;
    $fetchdata = $database->getReference($ref_table  . '/booking/')->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['pemesanan'] = $json;
    header('Location: pemesanan.php');
    exit();
}

function getAge($lahir)
{
    $bday = new DateTime($lahir);
    $today = new DateTime(date('m.d.y'));
    $diff = $today->diff($bday);
    return $diff->y . ' Tahun, ' . $diff->m . ' Bulan';
    // $payload = file_get_contents('https://firebasestorage.googleapis.com/v0/b/example.appspot.com/o/Photos%2Fpic.jpeg');
    // $data = json_decode($payload);
    // echo $data->downloadTokens;
}
