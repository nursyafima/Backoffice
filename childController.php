<?php
session_start();
include('dbconfig.php');

if (isset($_POST['hapus_anak'])) {
    $del_id = $_POST['hapus_anak'];
    $email  = $_POST['email'];
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $quota = 'daycare/' . $email_replaced . '/profile/';
    $getQuota = $database->getReference($quota)->getValue();
    $ref_table = 'daycare/' . $email_replaced . '/child/' . $del_id;
    $all_child = 'daycare/' . $email_replaced;
    $fetchDataMovedChild = $database->getReference($ref_table)->getValue();
    $move_child = [
        'firstName' => $fetchDataMovedChild['firstName'],
        'lastName' => $fetchDataMovedChild['lastName'],
        'gender' => $fetchDataMovedChild['gender'],
        'birthDate' => $fetchDataMovedChild['birthDate'],
        'age' => $fetchDataMovedChild['age'],
        'parent' => $fetchDataMovedChild['parent'],
        'status' => "Tidak Terdaftar",
    ];
    $move_child_result = $database->getReference($all_child . '/allChild/' . $del_id)->set($move_child);
    $delete_result = $database->getReference($ref_table)->remove();
    $currentQuota = (int) $getQuota['currentQuota'] += 1;
    $updateQuota = [
        'currentQuota' => $currentQuota,
    ];
    $update_result = $database->getReference($quota)->update($updateQuota);
    $fetchdata = $database->getReference($all_child . '/child/')->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    if ($update_result) {
        $fetchdata = $database->getReference('daycare/'. $email_replaced . '/child/')->getValue();
        $array = (array) $fetchdata;
        $json = json_encode($array);
        $_SESSION['child1'] = $json;
        $_SESSION['status'] = "Berhasil mengeluarkan anak dari daycare";
        header('Location: lihatDataAnak.php');
    } else {
        $_SESSION['status'] = "Gagal mengeluarkan anak dari daycare";
        header('Location: lihatDataAnak.php');
    }
}

if (isset($_POST['edit_anak'])) {
    $email          = $_POST['email'];
    $key            = $_POST['key_anak'];
    $firstName      = $_POST['firstName'];
    $lastName       = $_POST['lastName'];
    $gender         = $_POST['gender'];
    $birthDate      = $_POST['birthDate'];
    $parent         = $_POST['parent'];
    $age            = getAge($birthDate);

    if (empty($firstName)) {
        $_SESSION['status'] = "Nama Depan Anak Kosong";
        header('Location: editDataAnak.php');
        exit();
    } else if (empty($lastName)) {
        $_SESSION['status'] = "Nama Belakang Anak Kosong";
        header('Location: editDataAnak.php');
        exit();
    } else if (empty($gender)) {
        $_SESSION['status'] = "Jenis Kelamin Anak Kosong";
        header('Location: editDataAnak.php');
        exit();
    } else if (empty($birthDate)) {
        $_SESSION['status'] = "Tanggal Lahir Anak Kosong";
        header('Location: editDataAnak.php');
        exit();
    } else if (empty($parent)) {
        $_SESSION['status'] = "Orang Tua Anak Kosong";
        header('Location: editDataAnak.php');
        exit();
    } else {
        $editData = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'gender' => $gender,
            'birthDate' => $birthDate,
            'age' => $age,
            'parent' => $parent,
        ];
        $replace = ["@", "."];
        $email_replaced = str_replace($replace, "", $email);
        $ref_table = 'daycare/' . $email_replaced;
        $edit_result = $database->getReference($ref_table . '/child/' . $key)->update($editData);
        $fetchdata = $database->getReference($ref_table . '/child/')->getValue();
        $array = (array) $fetchdata;
        $json = json_encode($array);
        if ($edit_result) {
            $fetchdata = $database->getReference($ref_table . '/child/')->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['child1'] = $json;
            $_SESSION['status'] = "Data berhasil diubah";
            header('Location: lihatDataAnak.php');
            exit();
            // header('Location: lihatDataAnak.php');
        } else {
            $_SESSION['status'] = "Data gagal diubah";
            header('Location: lihatDataAnak.php');
        }
    }
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

function dateFormat($date)
{
    $result = explode("-", $date);
    $date = $result[2];
    $month = $result[1];
    $year = $result[0];
    $newDate = $date . '-' . $month . '-' . $year;

    return $newDate;
}

if (isset($_POST['simpan_anak'])) {
    $email          = $_POST['email'];
    $firstName      = $_POST['firstName'];
    $lastName       = $_POST['lastName'];
    $gender         = $_POST['gender'];
    $birthDate      = $_POST['birthDate'];
    $parent         = $_POST['parent'];
    $age            = getAge($birthDate);
    $currQuota      = $_POST['currQuota'];
    $quota          = $_POST['quota'];

    if (empty($firstName)) {
        $_SESSION['status'] = "Nama Depan Anak Kosong";
        header('Location: dataAnak.php');
        exit();
    } else if (empty($lastName)) {
        $_SESSION['status'] = "Nama Belakang Anak Kosong";
        header('Location: dataAnak.php');
        exit();
    } else if (empty($gender)) {
        $_SESSION['status'] = "Jenis Kelamin Anak Kosong";
        header('Location: dataAnak.php');
        exit();
    } else if (empty($birthDate)) {
        $_SESSION['status'] = "Tanggal Lahir Anak Kosong";
        header('Location: dataAnak.php');
        exit();
    } else if (empty($parent)) {
        $_SESSION['status'] = "Orang Tua Anak Kosong";
        header('Location: dataAnak.php');
        exit();
    } else {
        // $all_child = 'daycare/' . $email_replaced;
        // $fetchdata = $database->getReference($all_child . '/child/')->getValue();
        // $array = (array) $fetchdata;
        // $json = json_encode($array);
        if ((int)$currQuota == 0) {
            $_SESSION['status'] = "Data gagal ditambahkan, Kuota Penuh";
            header('Location: lihatDataAnak.php');
        } else {
            $postData = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'gender' => $gender,
                'birthDate' => $birthDate,
                'age' => $age,
                'parent' => $parent,
                'status' => "Terdaftar",
            ];
            $replace = ["@", "."];
            $email_replaced = str_replace($replace, "", $email);
            $ref_table = 'daycare/' . $email_replaced . '/child/';
            $postRef_result = $database->getReference($ref_table)->push($postData);
            $updateQuota = [
                'currentQuota' => $currQuota -= 1,
            ];
            $quota = 'daycare/' . $email_replaced . '/profile/';
            $quota_update = $database->getReference($quota)->update($updateQuota);
            if ($postRef_result) {
                $fetchdata = $database->getReference($ref_table)->getValue();
                $array = (array) $fetchdata;
                $json = json_encode($array);
                $_SESSION['child1'] = $json;
                $_SESSION['status'] = "Data berhasil ditambahkan";
                header('Location: lihatDataAnak.php');
                exit();
                // header('Location: lihatDataAnak.php');
            } else {
                $_SESSION['status'] = "Data gagal ditambahkan";
                header('Location: lihatDataAnak.php');
            }
        }
    }
}

if (isset($_POST['actionChild1'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/child/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['child1'] = $json;
    header('Location: lihatDataAnak.php');
    exit();
}
if (isset($_POST['actionChild2'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/allChild/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['child2'] = $json;
    header('Location: lihatSemuaAnak.php');
    exit();
}
