<?php
session_start();
include('dbconfig.php');

if (isset($_POST['simpan_pengasuh'])) {
    $email           = $_POST['email'];
    $nama_pengasuh   = $_POST['nama_pengasuh'];
    $jk_pengasuh     = $_POST['jenis_kelamin_pengasuh'];
    $alamat_pengasuh = $_POST['alamat_pengasuh'];
    $phone           = $_POST['phone'];
    $tanggal_masuk   = $_POST['tanggal_masuk'];
    $kerja           = getAge($tanggal_masuk);

    if (empty($nama_pengasuh)) {
        $_SESSION['status'] = "Nama Pengasuh Kosong";
        header('Location: dataPengasuh.php');
        exit();
    } else if (empty($jk_pengasuh)) {
        $_SESSION['status'] = "Jenis Kelamin Pengasuh Kosong";
        header('Location: dataPengasuh.php');
        exit();
    } else if (empty($alamat_pengasuh)) {
        $_SESSION['status'] = "Alamat Pengasuh Kosong";
        header('Location: dataPengasuh.php');
        exit();
    } else if (empty($phone)) {
        $_SESSION['status'] = "No Handphone Pengasuh Kosong";
        header('Location: dataPengasuh.php');
        exit();
    } else if (empty($tanggal_masuk)) {
        $_SESSION['status'] = "Tanggal Bekerja Pengasuh Kosong";
        header('Location: dataPengasuh.php');
        exit();
    } else {
        $postData = [
            'name' => $nama_pengasuh,
            'gender' => $jk_pengasuh,
            'address' => $alamat_pengasuh,
            'phoneNumber' => $phone,
            'entryDate' => $tanggal_masuk,
            'startedWork' => $kerja,
        ];
        $replace = ["@", "."];
        $email_replaced = str_replace($replace, "", $email);
        $ref_table = 'daycare/' . $email_replaced . '/caretaker/';
        $postRef_result = $database->getReference($ref_table)->push($postData);
        $fetchdata = $database->getReference($ref_table)->getValue();
        $array = (array) $fetchdata;
        $json = json_encode($array);
        if ($postRef_result) {
            $ref_table = 'daycare/' . $email_replaced . '/caretaker/';
            $fetchdata = $database->getReference($ref_table)->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pengasuh1'] = $json;
            $_SESSION['status'] = "Data berhasil ditambahkan";
            header('Location: lihatDataPengasuh.php');
            exit();
            // header('Location: lihatDataPengasuh.php');
        } else {
            $_SESSION['status'] = "Data gagal ditambahkan";
            header('Location: lihatDataPengasuh.php');
        }
    }
}

if (isset($_POST['edit_pengasuh'])) {
    $email          = $_POST['email'];
    $key            = $_POST['key_pengasuh'];
    $nama_pengasuh  = $_POST['nama_pengasuh'];
    $jk_pengasuh    = $_POST['jenis_kelamin_pengasuh'];
    $alamat_pengasuh = $_POST['alamat_pengasuh'];
    $phone          = $_POST['phone'];
    $tanggal_masuk  = $_POST['tanggal_masuk'];
    $kerja          = getAge($tanggal_masuk);

    if (empty($nama_pengasuh)) {
        $_SESSION['status'] = "Nama Pengasuh Kosong";
        header('Location: editDataPengasuh.php');
        exit();
    } else if (empty($jk_pengasuh)) {
        $_SESSION['status'] = "Jenis Kelamin Pengasuh Kosong";
        header('Location: editDataPengasuh.php');
        exit();
    } else if (empty($alamat_pengasuh)) {
        $_SESSION['status'] = "Alamat Pengasuh Kosong";
        header('Location: editDataPengasuh.php');
        exit();
    } else if (empty($phone)) {
        $_SESSION['status'] = "No Handphone Pengasuh Kosong";
        header('Location: editDataPengasuh.php');
        exit();
    } else if (empty($tanggal_masuk)) {
        $_SESSION['status'] = "Tanggal Bekerja Pengasuh Kosong";
        header('Location: editDataPengasuh.php');
        exit();
    } else {
        $edit = [
            'name' => $nama_pengasuh,
            'gender' => $jk_pengasuh,
            'address' => $alamat_pengasuh,
            'phoneNumber' => $phone,
            'entryDate' => $tanggal_masuk,
            'startedWork' => $kerja,
        ];
        $replace = ["@", "."];
        $email_replaced = str_replace($replace, "", $email);
        $ref_table = 'daycare/' . $email_replaced . '/caretaker/';
        $postRef_result = $database->getReference($ref_table . $key)->update($edit);
        $fetchdata = $database->getReference($ref_table)->getValue();
        $array = (array) $fetchdata;
        $json = json_encode($array);
        if ($postRef_result) {
            $ref_table = 'daycare/' . $email_replaced . '/caretaker/';
            $fetchdata = $database->getReference($ref_table)->getValue();
            $array = (array) $fetchdata;
            $json = json_encode($array);
            $_SESSION['pengasuh1'] = $json;
            $_SESSION['status'] = "Data berhasil diubah";
            header('Location: lihatDataPengasuh.php');
            exit();
            // header('Location: lihatDataPengasuh.php');
        } else {
            $_SESSION['status'] = "Data gagal diubah";
            header('Location: lihatDataPengasuh.php');
        }
    }
}

if (isset($_POST['hapus_pengasuh'])) {
    $del_id = $_POST['hapus_pengasuh'];
    $email  = $_POST['email'];
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/caretaker/';
    $ref_pengasuh = 'daycare/' . $email_replaced;
    $fetchDataMovePengasuh = $database->getReference($ref_table . $del_id)->getValue();
    $move_child = [
        'name' => $fetchDataMovePengasuh['name'],
        'gender' => $fetchDataMovePengasuh['gender'],
        'address' => $fetchDataMovePengasuh['address'],
        'entryDate' => $fetchDataMovePengasuh['entryDate'],
        'phoneNumber' => $fetchDataMovePengasuh['phoneNumber'],
        'startedWork' => $fetchDataMovePengasuh['startedWork'],
    ];
    $move_child_result = $database->getReference($ref_pengasuh . '/allCaretaker/' . $del_id)->set($move_child);
    $delete_result = $database->getReference($ref_table . $del_id)->remove();
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    if ($delete_result) {
        $ref_table = 'daycare/' . $email_replaced . '/caretaker/';
        $fetchdata = $database->getReference($ref_table)->getValue();
        $array = (array) $fetchdata;
        $json = json_encode($array);
        $_SESSION['pengasuh1'] = $json;
        $_SESSION['status'] = "Data berhasil dihapus";
        header('Location: lihatDataPengasuh.php');
        exit();
        // header('Location: lihatDataPengasuh.php');
    } else {
        $_SESSION['status'] = "Data gagal dihapus";
        header('Location: lihatDataPengasuh.php');
    }
}

if (isset($_POST['actionPengasuh1'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/caretaker/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['pengasuh1'] = $json;
    header('Location: lihatDataPengasuh.php');
    exit();
}

if (isset($_POST['actionPengasuh2'])) {
    if (isset($_SESSION['verified_user_id'])) {
        $uid = $_SESSION['verified_user_id'];
        $user = $auth->getUser($uid);
        $email = $user->email;
    }
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/allCaretaker/';
    $fetchdata = $database->getReference($ref_table)->getValue();
    $array = (array) $fetchdata;
    $json = json_encode($array);
    $_SESSION['pengasuh2'] = $json;
    header('Location: lihatSemuaPengasuh.php');
    exit();
}

function getAge($lahir)
{
    $bday = new DateTime($lahir);
    $today = new DateTime(date('m.d.y'));
    $diff = $today->diff($bday);
    return $diff->y . ' Tahun, ' . $diff->m . ' Bulan';
    $payload = file_get_contents('https://firebasestorage.googleapis.com/v0/b/example.appspot.com/o/Photos%2Fpic.jpeg');
    $data = json_decode($payload);
    echo $data->downloadTokens;
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
