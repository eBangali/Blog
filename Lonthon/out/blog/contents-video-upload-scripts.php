<?php
include_once(dirname(dirname(dirname(__FILE__))).'/initialize.php');
include_once(ebblog.'/blog.php');
include_once(ebbd.'/connection-inc.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['type'] == 'video/mp4') {
    $filename = $_FILES['file']['name'];
    $username = $_SESSION['ebusername'] ?? 'anonymous';
    $memberlevel = $_SESSION['memberlevel'] ?? 0;
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $contents_id = strval($_POST['contents_id']);

    $store_path = eb."/ebcontents/uploads/$username/blog-video/$year/$month/$day";

    if (!is_dir($store_path)) {
        mkdir($store_path, 0755, true);
    }

    if (!is_writable($store_path)) {
        echo "Upload path is not writable.";
        exit;
    }

    $upload_path = $store_path.'/'.uniqid(mt_rand()).'.mp4';
    $videoPathName = $upload_path;
    $contents_video_link = str_replace(docRoot, "", $videoPathName);

    if ($memberlevel >= 8) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $videoPathName)) {
            $query = "UPDATE blog_contents SET contents_approved='NO', contents_video_link='$contents_video_link' WHERE contents_id='$contents_id'";
            if ($connectdb->query($query) === TRUE) {
                echo 'Video uploaded successfully!';
            } else {
                echo 'Database error: ' . $connectdb->error;
            }
        } else {
            echo 'There was an error moving the uploaded video file.';
        }
    } else {
        echo 'Permission denied. Your membership level is not high enough.';
    }
} else {
    echo 'Invalid upload. Make sure to select an MP4 file.';
}
?>
