<?php
session_start();
include('dbconfig.php');

if (isset($_POST['simpan_act'])) {
    $email           = $_POST['email'];
    $dateAct         = $_POST['dateAct'];
    $activity        = $_POST['activity'];
    $time1           = date("H:i", strtotime($_POST['time1']));
    $time2           = date("H:i", strtotime($_POST['time2']));
    $noted           = $_POST['noted'];
    $status          = checkStatus($_POST['status']);
    $image_url       = $_POST['imageUrl'];
    $video_url       = $_POST['videoUrl'];

    if (empty($dateAct)) {
        $_SESSION['status'] = "Tanggal Aktifitas Kosong";
        header('Location: dataAktifitas.php');
        exit();
    } else if (empty($activity)) {
        $_SESSION['status'] = "Aktifitas Kosong";
        header('Location: dataAktifitas.php');
        exit();
    } else if (empty($time1)) {
        $_SESSION['status'] = "Waktu Aktifitas Awal Kosong";
        header('Location: dataAktifitas.php');
        exit();
    } else if (empty($time2)) {
        $_SESSION['status'] = "Waktu Aktifitas Akhir Kosong";
        header('Location: dataAktifitas.php');
        exit();
    } else if (empty($noted)) {
        $_SESSION['status'] = "Catatan Kosong";
        header('Location: dataAktifitas.php');
        exit();
    } else if (empty($status)) {
        $_SESSION['status'] = "Status Kosong";
        header('Location: dataAktifitas.php');
        exit();
    } else {
        $replace = ["@", "."];
        $email_replaced = str_replace($replace, "", $email);
        $ref_table = 'daycare/' . $email_replaced . '/activity/';
        $ref_tables = 'daycare/' . $email_replaced . '/activity/' . $dateAct;
        $dataRef = $database->getReference($ref_table)->getChildKeys();
        if (in_array("2022-06-01", $dataRef)) {
            $database->getReference($ref_table)->removeChildren(["2022-06-01"]);
            $postData = [
                'activity' => $activity,
                'time' => $time1 . "-" . $time2,
                'noted' => $noted,
                'status' => $status,
                'imageUrl' => $image_url,
                'videoUrl' => $video_url,
            ];
            $postRef_result = $database->getReference($ref_tables . '/activity1')->set($postData);
        } else if (in_array($dateAct, $dataRef)) {
            $activityKey = $database->getReference($ref_tables)->getChildKeys();
            $i = sizeof($activityKey);
            $newKeyAct = $i + 1;
            $postData = [
                'activity' => $activity,
                'time' => $time1 . "-" . $time2,
                'noted' => $noted,
                'status' => $status,
                'imageUrl' => $image_url,
                'videoUrl' => $video_url,
            ];
            $postRef_result = $database->getReference($ref_tables . '/activity' . $newKeyAct)->set($postData);
        } else {
            $postData = [
                'activity' => $activity,
                'time' => $time1 . "-" . $time2,
                'noted' => $noted,
                'status' => $status,
                'imageUrl' => $image_url,
                'videoUrl' => $video_url,
            ];
            $postRef_result = $database->getReference($ref_tables . '/activity1')->set($postData);
        }
        if ($postRef_result) {
            $fetchdata = $database->getReference($ref_table)->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['activity'] = $json;
            $_SESSION['status'] = "Data berhasil ditambahkan";
            header('Location: lihatDataAktifitas.php?fetchdata=' . null . '&& search_key=' . null . '&& email=' . null . '&& search_result_key=' . null);
        } else {
            $fetchdata = $database->getReference($ref_table)->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['activity'] = $json;
            $_SESSION['status'] = "Data gagal ditambahkan";
            header('Location: lihatDataAktifitas.php?fetchdata=' . null . '&& search_key=' . null . '&& email=' . null . '&& search_result_key=' . null);
        }
    }
}

if (isset($_POST['del_act'])) {
    $key = $_POST['key'];
    $del_id = $_POST['del_act'];
    $email  = $_POST['email'];
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/activity/' . $key . "/" . $del_id;
    $ref_tables = 'daycare/' . $email_replaced . '/activity/';
    $delete_result = $database->getReference($ref_table)->remove();

    if ($delete_result) {
        $fetchdata = $database->getReference($ref_tables)->getValue();
        $array = (array) $fetchdata;
        $json = json_encode($array);
        $_SESSION['activity'] = $json;
        $_SESSION['status'] = "Data berhasil dihapus";
        header('Location: lihatDataAktifitas.php');
    } else {
        $fetchdata = $database->getReference($ref_tables)->getValue();
        $array = (array) $fetchdata;
        $json = json_encode($array);
        $_SESSION['activity'] = $json;
        $_SESSION['status'] = "Data gagal dihapus";
        header('Location: lihatDataAktifitas.php');
    }
}

if (isset($_POST['edit_act'])) {
    $key             = $_POST['key'];
    $key_child       = $_POST['key_child'];
    $email           = $_POST['email'];
    $dateAct         = $_POST['dateAct'];
    $activity        = $_POST['activity'];
    $time1            = date("H:i", strtotime($_POST['time1']));
    $time2            = date("H:i", strtotime($_POST['time2']));
    $noted           = $_POST['noted'];
    $status          = checkStatus($_POST['status']);
    $image_url       = $_POST['imageUrl'];
    $video_url       = $_POST['videoUrl'];

    if (empty($dateAct)) {
        $_SESSION['status'] = "Tanggal Aktifitas Kosong";
        header('Location: editDataAktifitas.php');
        exit();
    } else if (empty($activity)) {
        $_SESSION['status'] = "Aktifitas Kosong";
        header('Location: dataAktifitas.php');
        exit();
    } else if (empty($time1)) {
        $_SESSION['status'] = "Waktu Aktifitas Dimulai Kosong";
        header('Location: editDataAktifitas.php');
        exit();
    } else if (empty($time2)) {
        $_SESSION['status'] = "Waktu Aktifitas Berakhir Kosong";
        header('Location: editDataAktifitas.php');
        exit();
    } else if (empty($noted)) {
        $_SESSION['status'] = "Catatan Kosong";
        header('Location: editDataAktifitas.php');
        exit();
    } else if (empty($status)) {
        $_SESSION['status'] = "Status Kosong";
        header('Location: editDataAktifitas.php');
        exit();
    } else {
        $edit = [
            'activity' => $activity,
            'time' => $time1 . "-" . $time2,
            'noted' => $noted,
            'status' => $status,
            'imageUrl' => $image_url,
            'videoUrl' => $video_url,
        ];
        $replace = ["@", "."];
        $email_replaced = str_replace($replace, "", $email);
        $ref_table = 'daycare/' . $email_replaced . '/activity/' . $key . '/' . $key_child;
        $ref_tables = 'daycare/' . $email_replaced . '/activity/';
        $postRef_result = $database->getReference($ref_table)->update($edit);
        if ($postRef_result) {
            $fetchdata = $database->getReference($ref_tables)->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['activity'] = $json;
            $_SESSION['status'] = "Data berhasil diubah";
            header('Location: lihatDataAktifitas.php');
        } else {
            $fetchdata = $database->getReference($ref_tables)->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['activity'] = $json;
            $_SESSION['status'] = "Data gagal diubah";
            header('Location: lihatDataAktifitas.php');
        }
    }
}


function checkData($str1, $str2)
{
    $b = "";
    foreach ($str1 as $activityRef) {
        $b = $b . "," . $activityRef;
    }
    return str_contains($b, $str2);
}

function checkStatus($stats)
{
    if (isset($stats)) {
        return $stats = "true";
    } else {
        return $stats = "false";
    }
}

if (isset($_POST['searchDate-btn'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    if (empty($_POST['searchDate'])) {
        $_SESSION['status'] = "Masukkan Tanggal Pencarian";
        header('Location: lihatDataAktifitas.php');
        exit();
    } else {
        $replace = ["@", "."];
        $email_replaced = str_replace($replace, "", $email);
        $ref_table = 'daycare/' . $email_replaced . '/activity/';
        $search_result = null;
        $fetchdata = null;
        $search_key = $_POST['searchDate'];
        $search_result = $database->getReference($ref_table)->orderByKey()->equalTo($search_key)->getValue();
        $search_result_key = $database->getReference($ref_table . $search_key)->getSnapshot()->getReference()->getChildKeys();
        $array1 = (array) $search_result;
        $json1 = json_encode($array1);
        $array2 = (array) $search_result_key;
        $json2 = json_encode($array2);
        if ($json2 != null) {
            $_SESSION['searchKey'] = $search_key;
            $_SESSION['searchResult'] = $json2;
            $_SESSION['email'] = $email_replaced;
            header('Location: lihatDataTanggal.php');
            exit();
        }
    }
}

if (isset($_POST['actionActivity'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/activity/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['activity'] = $json;
    header('Location: lihatDataAktifitas.php');
    exit();
}

if (isset($_POST['activityClicked'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $key = $_POST['activityClicked'];
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/activity/';
    $activityKey = $database->getReference($ref_table . $key)->getSnapshot()->getReference()->getChildKeys();
    // $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $activityKey;
    $json = json_encode($array);
    $_SESSION['activityKey'] = $key;
    $_SESSION['activityArray'] = $json;
    $_SESSION['email'] = $email_replaced;
    header('Location: lihatDataTanggal.php');
    exit();
}

if (isset($_POST['saveActivity'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $key = $_POST['saveActivity'];
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/activity/';
    $activityKey = $database->getReference($ref_table . $key)->getSnapshot()->getReference()->getChildKeys();
    // $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $activityKey;
    $json = json_encode($array);
    $_SESSION['saveActivity'] = $key;
    $_SESSION['email'] = $email_replaced;
    header('Location: dataAktifitas.php');
    exit();
}
