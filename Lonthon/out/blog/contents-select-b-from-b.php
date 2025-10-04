<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php
include_once(ebbd.'/connection-inc.php');
include_once (ebblog.'/blog.php');
$obj = new ebapps\blog\blog();

if (isset($_POST['pic_name']) && $_POST['pic_name'] != '') {
    $pic_name = $_POST['pic_name'];
    
    // Prepare the SQL statement
    $stmt = $connectdb->prepare("SELECT contents_sub_category FROM blog_sub_category WHERE contents_category_in_blog_sub_category = ?");
    $stmt->bind_param("s", $pic_name);
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['contents_sub_category'], ENT_QUOTES, 'UTF-8') . "'>" 
                . $obj->visulString($row['contents_sub_category']) . "</option>";
        }
        $result->free();
    }
    
    $stmt->close();
}
?>

