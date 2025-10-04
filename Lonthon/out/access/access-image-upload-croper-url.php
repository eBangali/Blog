<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php
include_once(ebbd.'/connection-inc.php');
if (isset($_POST['image'])) {
    $data = $_POST['image'];
    $image_array_1 = explode(';', $data);
    $image_array_2 = explode(",", $image_array_1[1]);
    $data = base64_decode($image_array_2[1]);

    $username = $_SESSION['ebusername'];
    $year = date('Y');
    $month = date('m');
    $day = date('d');

    $store_path_a = eb . "/ebcontents";
    if (!is_dir($store_path_a)) { 
        mkdir($store_path_a, 0755);
    }
    $store_path_b = $store_path_a . "/" . "uploads";
    if (!is_dir($store_path_b)) { 
        mkdir($store_path_b, 0755);
    }
    $store_path_1 = $store_path_b . "/" . $username;
    if (!is_dir($store_path_1)) { 
        mkdir($store_path_1, 0755);
    }
    $store_path_app = $store_path_1 . "/" . "userpicture";
    if (!is_dir($store_path_app)) { 
        mkdir($store_path_app, 0755);
    }
    $store_path_2 = $store_path_app . "/" . $year;
    if (!is_dir($store_path_2)) { 
        mkdir($store_path_2, 0755);
    }
    $store_path_3 = $store_path_2 . "/" . $month;
    if (!is_dir($store_path_3)) { 
        mkdir($store_path_3, 0755);
    }
    $store_path_4 = $store_path_3 . "/" . $day;
    if (!is_dir($store_path_4)) { 
        mkdir($store_path_4, 0755);
    }

    $upload_path = $store_path_4;
    $file_path = $upload_path . '/' . uniqid(mt_rand()) . '.png';
    $imageName = $file_path;
    $profile_picture_link = str_replace(docRoot, "", $imageName);
    file_put_contents($imageName, $data);

    echo $imageName;

    $ebusername = $_SESSION['ebusername'];

    if (isset($_SESSION['memberlevel'])) {
        if ($_SESSION['memberlevel'] >= 1) {
            $stmt = $connectdb->prepare("
                UPDATE excessusers 
                SET profile_picture_link = ? 
                WHERE ebusername = ?
            ");
            $stmt->bind_param("ss", $profile_picture_link, $ebusername);
            $stmt->execute();
            $stmt->close();
        }
    }
}

?>