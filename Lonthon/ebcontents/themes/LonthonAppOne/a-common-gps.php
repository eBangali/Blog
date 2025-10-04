<?php include_once (dirname(dirname(dirname(dirname(__FILE__)))).'/initialize.php'); ?>
<?php include_once (ebSys.'/sys.php'); ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['latitudeFromByBrowser']) && isset($_POST['longitudeFromByBrowser'])) {
        $latitudeVisitor = $_POST['latitudeFromByBrowser'];
        $longitudeVisitor = $_POST['longitudeFromByBrowser'];

        // Save latitude and longitude in the PHP session
        $_SESSION['latitudeVisitor'] = $latitudeVisitor;
        $_SESSION['longitudeVisitor'] = $longitudeVisitor;
       
        // Send the response as JSON
        $response = [
            'status' => 'success',
            'latitude' => $latitudeVisitor,
            'longitude' => $longitudeVisitor
        ];
        echo json_encode($response);
    } else {
        /*
        error_log("Latitude or Longitude not provided");
        $response = [
            'status' => 'error',
            'message' => 'Latitude or Longitude not provided'
        ];
        echo json_encode($response);
        */
    }
} else {
    //error_log("Invalid request method");
}
?>