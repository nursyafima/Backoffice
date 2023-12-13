<?php
session_start();
include('dbconfig.php');
// include('autentication.php');


if (isset($_POST['register-btn'])) {
    $nama_admin            = $_POST['daycareName'];
    $email                 = $_POST['email'];
    $desc                  = $_POST['description'];
    $alamat                = $_POST['address'];
    $no_hp                 = $_POST['phoneNumber'];
    $fasilitas             = $_POST['facilities'];
    $harga                 = number_format($_POST['price'], 0, ",", ".");
    $accountNumber         = $_POST['accountNumber'];
    $password              = $_POST['password'];
    $lat                   = $_POST['latitude'];
    $long                  = $_POST['longitude'];
    $kuota                 = $_POST['quota'];
    $currentQuota          = $_POST['quota'];
    $profilePic            = $_POST['profilePic'];
    $profileImageUrl       = "null";

    if (isset($_POST['facilities'])) {
        foreach ($fasilitas as $facilities) {
            $a = $a . $facilities . ",";
        }
        $a = substr($a, 0, strlen($a) - 1);
    } else {
        $a = "-";
    }

    if (empty($nama_admin)) {
        $_SESSION['status'] = "Nama Daycare Kosong";
        header('Location: register.php');
        exit();
    } else if (empty($email)) {
        $_SESSION['status'] = "Email Anda Kosong";
        header('Location: register.php');
        exit();
    } else if (empty($password)) {
        $_SESSION['status'] = "Password Anda Kosong";
        header('Location: register.php');
        exit();
    } else {
        $userProperties = [
            'email' => $email,
            'daycareName' => $nama_admin,
            'password' => $password,
        ];
        $profile = [
            'email' => $email,
            'daycareName' => $nama_admin,
            'description' => $desc,
            'address' => $alamat,
            'phoneNumber' => $no_hp,
            'facilities' => $a,
            'price' => $harga,
            'accountNumber' => $accountNumber,
            'longitude' => (float) $long,
            'latitude' => (float) $lat,
            'quota' => (int) $kuota,
            'currentQuota' => (int) $currentQuota,
            'profilePic' => $profilePic,
            'mediaUrl' => $profileImageUrl,
        ];
        $activity = [
            'activity' => 'first',
            'time' => '07.00" . "-" . "08.00',
            'noted' => 'activity',
            'status' => 'true',
            'imageUrl' => 'null',
            'videoUrl' => 'null',
        ];
        $invoice = [
            'invoiceID' => "1000",
        ];
        $replace = ["@", "."];
        $createdUser = $auth->createUser($userProperties);
        $email_replaced = str_replace($replace, "", $email);
        $database->getReference('daycare/' . $email_replaced . '/profile')->set($profile);
        $database->getReference('daycare/' . $email_replaced . '/activity/2022-06-01/activity0')->set($activity);
        $database->getReference('daycare/' . $email_replaced . '/invoice/1000')->set($invoice);
        if ($createdUser) {
            $_SESSION['status'] = "Berhasil Melakukan Pendaftaran";
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['status'] = "Pendaftaran Gagal";
            header('Location: register.php');
            exit();
        }
    }
}

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
            $_SESSION['status'] = "Data berhasil diubah";
            header('Location: lihatDataAnak.php');
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
                $_SESSION['status'] = "Data berhasil ditambahkan";
                header('Location: lihatDataAnak.php');
            } else {
                $_SESSION['status'] = "Data gagal ditambahkan";
                header('Location: lihatDataAnak.php');
            }
        }
    }
}

// function checkQuota($a, $b)
// {
// }

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
            $_SESSION['status'] = "Data berhasil ditambahkan";
            header('Location: lihatDataPengasuh.php');
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
            $_SESSION['status'] = "Data berhasil diubah";
            header('Location: lihatDataPengasuh.php');
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
        $_SESSION['status'] = "Data berhasil dihapus";
        header('Location: lihatDataPengasuh.php');
    } else {
        $_SESSION['status'] = "Data gagal dihapus";
        header('Location: lihatDataPengasuh.php');
    }
}

if (isset($_POST['simpan_act'])) {
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
        if (checkData($dataRef, "2022-06-01")) {
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
        } else {
            if (checkData($dataRef, $dateAct)) {
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
                $postRef_result = $database->getReference($ref_tables . '/activity' . '1')->set($postData);
            }
        }
        if ($postRef_result) {
            $_SESSION['status'] = "Data berhasil ditambahkan";
            header('Location: lihatDataAktifitas.php?fetchdata=' . null . '&& search_key=' . null . '&& email=' . null . '&& search_result_key=' . null);
        } else {
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
    $delete_result = $database->getReference($ref_table)->remove();

    if ($delete_result) {
        $_SESSION['status'] = "Data berhasil dihapus";
        header('Location: lihatDataAktifitas.php');
    } else {
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
        $postRef_result = $database->getReference($ref_table)->update($edit);
        if ($postRef_result) {
            $_SESSION['status'] = "Data berhasil diubah";
            header('Location: lihatDataAktifitas.php');
        } else {
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
    return str_contains($activityRef, $str2);
}

function checkStatus($stats)
{
    if (isset($stats)) {
        return $stats = "true";
    } else {
        return $stats = "false";
    }
}

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
                $_SESSION['status'] = "Data berhasil diproses";
                header('Location: pemesanan.php');
            } else {
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
                $_SESSION['status'] = "Data berhasil diproses";
                header('Location: pemesanan.php');
            } else {
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
                $_SESSION['status'] = "Data berhasil diproses";
                header('Location: pemesanan.php');
            } else {
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
            $_SESSION['status'] = "Data berhasil ditolak";
            header('Location: pemesanan.php');
        } else {
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
            $_SESSION['status'] = "Data berhasil diproses";
            header('Location: pemesanan.php');
        } else {
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
            $_SESSION['status'] = "Data berhasil diproses";
            header('Location: pemesanan.php');
        } else {
            $_SESSION['status'] = "Data gagal diproses";
            header('Location: pemesanan.php');
        }
    }
}

if (isset($_POST['edit-profile'])) {
    $nama_admin            = $_POST['daycareName'];
    $email                 = $_POST['email'];
    $desc                  = $_POST['description'];
    $alamat                = $_POST['address'];
    $no_hp                 = $_POST['phoneNumber'];
    $fasilitas             = $_POST['facilities'];
    $harga                 = $_POST['price'];
    $accountNumber         = $_POST['accountNumber'];
    $long                  = $_POST['longitude'];
    $lat                   = $_POST['latitude'];
    $kuota                 = $_POST['quota'];
    $currentQuota          = $_POST['quota'];
    $profilePic            = $_POST['profilePic'];

    if (isset($_POST['facilities'])) {
        foreach ($fasilitas as $facilities) {
            $a = $a . $facilities . ",";
        }
    } else {
        $a = "-";
    }

    if (empty($nama_admin)) {
        $_SESSION['status'] = "Nama Daycare Kosong";
        header('Location: profile.php');
        exit();
    } else {
        $updateProfile = [
            'email' => $email,
            'daycareName' => $nama_admin,
            'description' => $desc,
            'address' => $alamat,
            'phoneNumber' => $no_hp,
            'facilities' => $a,
            'price' => $harga,
            'longitude' => (float) $long,
            'latitude' => (float) $lat,
            'quota' => (int) $kuota,
            'currentQuota' => (int) $currentQuota,
            'profilePic' => $profilePic,
        ];
        $replace = ["@", "."];
        $email_replaced = str_replace($replace, "", $email);
        $editProfile = $database->getReference('daycare/' . $email_replaced . '/profile/')->update($updateProfile);

        if ($editProfile) {
            $_SESSION['status'] = "Berhasil Melakukan Update Profil";
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['status'] = "Gagal Melakukan Update Profil";
            header('Location: profile.php');
            exit();
        }
    }
}

if (isset($_POST['addPhoto'])) {
    $email = $_POST['email'];
    $mediaUrl = $_POST['imageUrl'];
    $replace = ["@", "."];
    $email_replaced = str_replace($replace, "", $email);
    $ref_table = 'daycare/' . $email_replaced . '/profile/';
    $fetchDataDaycare = $database->getReference($ref_table)->getValue();
    $updateMedia = [
        'mediaUrl' => $mediaUrl,
    ];
    $upload_media_result = $database->getReference($ref_table)->update($updateMedia);
    if ($upload_media_result) {
        $_SESSION['status'] = "Berhasil Melakukan Upload Foto Daycare";
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['status'] = "Gagal Melakukan Upload Foto Daycare";
        header('Location: index.php');
        exit();
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
            header('Location: lihatDataAktifitas.php');
            exit();
        }
    }
}

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

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
