<?php 
include_once ('initialize.php'); 
include_once (ebaccess.'/error-message.php'); 

// PHP fallback redirect after 3 seconds
header("Refresh: 3; URL=" . hostingAndRoot . "/index.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Error</title>
</head>
<body>
    <p id="redirectMsg">Redirecting to homepage in 3 seconds...</p>

    <script>
    let seconds = 3;
    let countdown = setInterval(function() {
        document.getElementById("redirectMsg").innerText = 
            "Redirecting to homepage in " + seconds + " seconds...";
        seconds--;
        if (seconds < 0) {
            clearInterval(countdown);
            window.location.replace('<?php echo hostingAndRoot."/index.php"; ?>');
        }
    }, 1000);
    </script>
</body>
</html>
