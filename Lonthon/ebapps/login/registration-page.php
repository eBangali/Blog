<?php
namespace ebapps\login;

include_once(ebbd.'/dbconfig.php');
use ebapps\dbconnection\dbconfig;
/** **/
include_once(ebbd.'/eBConDb.php');
use ebapps\dbconnection\eBConDb;
/*** ***/
include_once(ebphpmailer.'/Exception.php');
use ebapps\PHPMailer\Exception;
/*** ***/
include_once(ebphpmailer.'/PHPMailer.php');
use ebapps\PHPMailer\PHPMailer;
/*** ***/
include_once(ebphpmailer.'/SMTP.php');
use ebapps\PHPMailer\SMTP;

class registration_page extends dbconfig
{
/* Total Notification */
public function total_notify($notify)
{
    // Default as 0
    $num_notify = 0;

    // Only run if $notify is array
    if (!is_array($notify)) {
        return $num_notify; // return 1 by default
    }

    // If user not logged in → return default (1)
    if (empty($_SESSION['ebusername'])) {
        return $num_notify;
    }

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $blogUserAlert = $_SESSION['ebusername'];

    $notify_query = "
        SELECT COUNT(*) AS notifyDate 
        FROM blog_comments 
        WHERE blogs_comment_status = 'OK' AND blogs_username = ?
          AND blogs_comment_date >= (NOW() - INTERVAL 1 DAY)
    ";

    if ($stmt = $conn->prepare($notify_query)) {
        $stmt->bind_param("s", $blogUserAlert);

        if ($stmt->execute()) {
            $result_notify = $stmt->get_result();
            if ($result_notify && $row = $result_notify->fetch_assoc()) {
                $num_notify = (int) $row['notifyDate']; // overwrite default
            }
            $result_notify->free();
        }

        $stmt->close();
    }

    return $num_notify;
}

/*** ***/
public function notificttion()
{
if(empty($_SESSION['notify']))
{
$_SESSION['notify'] = array();
$_SESSION['total_notify'] = $this->total_notify($_SESSION['notify']);
}
}

/*** ***/
public function payment_gateways_delete_payment_option($payment_gateways_id)
{
    if (isset($_REQUEST['DeletePaymentOption'])) {
        $payment_gateways_id = intval($_POST['payment_gateways_id']); // sanitize

        $conn = eBConDb::eBgetInstance()->eBgetConection();
        $queryPaymentUpdate = "DELETE FROM payment_gateways WHERE payment_gateways_id = ?";

        $stmt = $conn->prepare($queryPaymentUpdate);
        $stmt->bind_param("i", $payment_gateways_id);

        if ($stmt->execute()) {
            echo $this->ebDone();
            ?>
            <script>
            setTimeout(function(){
                window.location.replace('<?php echo hostingAndRoot."/out/access/access-payment-gateways.php"; ?>');
            }, 3000);
            setTimeout();
            </script>
            <?php
        }

        $stmt->close();
    }
}

/*** ***/
public function payment_gateways_donot_accept($payment_gateways_id, $gateway, $payment_user_id_filtered, $public_key_filtered, $private_key_filtered)
{
    if (isset($_REQUEST['DonotAcceptPayment'])) {
        $payment_gateways_id = intval($payment_gateways_id);
        $gateway = strval($gateway);
        $payment_user_id_filtered = strval($payment_user_id_filtered);
        $public_key_filtered = strval($public_key_filtered);
        $private_key_filtered = strval($private_key_filtered);

        $conn = eBConDb::eBgetInstance()->eBgetConection();

        $queryPaymentUpdate = "
            UPDATE payment_gateways 
            SET 
                payment_gateways_username = ?, 
                payment_gateways_public_key = ?, 
                payment_gateways_privet_key = ?, 
                payment_gateways_status = 'NOOK' 
            WHERE payment_gateways_id = ? 
              AND payment_gateways_brand = ?
        ";

        $stmt = $conn->prepare($queryPaymentUpdate);
        if ($stmt === false) {
            die("MySQL prepare failed: " . $conn->error);
        }

        // Bind parameters: s = string, i = integer
        $stmt->bind_param(
            "sssis", 
            $payment_user_id_filtered, 
            $public_key_filtered, 
            $private_key_filtered, 
            $payment_gateways_id, 
            $gateway
        );

        if ($stmt->execute()) {
            echo $this->ebDone();
            ?>
            <script>
            setTimeout(function(){
                window.location.replace('<?php echo hostingAndRoot."/out/access/access-payment-gateways.php"; ?>');
            }, 3000);
            setTimeout();
            </script>
            <?php
        }
        else{
            echo $this->eBNotDone();
            ?>
            <script>
            setTimeout(function(){
                window.location.replace('<?php echo hostingAndRoot."/out/access/access-payment-gateways.php"; ?>');
            }, 3000);
            setTimeout();
            </script>
            <?php
        }

        $stmt->close();
    }
}
/*** ***/
public function payment_gateways_accept_payment_getway($payment_gateways_id, $gateway, $payment_user_id_filtered, $public_key_filtered, $private_key_filtered)
{
    if (isset($_POST['AcceptPayment'])) {
        $payment_gateways_id = intval($payment_gateways_id);
        $gateway = strval($gateway);
        $payment_user_id_filtered = strval($payment_user_id_filtered);
        $public_key_filtered = strval($public_key_filtered);
        $private_key_filtered = strval($private_key_filtered);

        $conn = eBConDb::eBgetInstance()->eBgetConection();

        $queryPaymentUpdate = "
            UPDATE payment_gateways 
            SET 
                payment_gateways_username = ?, 
                payment_gateways_public_key = ?, 
                payment_gateways_privet_key = ?, 
                payment_gateways_status = 'OK' 
            WHERE payment_gateways_id = ? 
              AND payment_gateways_brand = ?
        ";

        $stmt = $conn->prepare($queryPaymentUpdate);
        if ($stmt === false) {
            die("MySQL prepare failed: " . $conn->error);
        }

        // Bind params (s = string, i = integer)
        $stmt->bind_param(
            "sssis",
            $payment_user_id_filtered,
            $public_key_filtered,
            $private_key_filtered,
            $payment_gateways_id,
            $gateway
        );

        if ($stmt->execute()) {
            echo $this->ebDone();
            ?>
            <script>
            setTimeout(function(){
                window.location.replace('<?php echo hostingAndRoot."/out/access/access-payment-gateways.php"; ?>');
            }, 3000);
            setTimeout();
            </script>
            <?php
        }
        else{
            echo $this->eBNotDone();
            ?>
            <script>
            setTimeout(function(){
                window.location.replace('<?php echo hostingAndRoot."/out/access/access-payment-gateways.php"; ?>');
            }, 3000);
            setTimeout();
            </script>
            <?php
        }

        $stmt->close();
    }
}
/*** ***/
public function select_image_from_user()
{
    if (!isset($_SESSION['ebusername'])) {
        return [];
    }

    $ebusername = $_SESSION['ebusername'];

    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM excessusers WHERE ebusername = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("MySQL prepare failed: " . $conn->error);
    }

    // Bind param (s = string)
    $stmt->bind_param("s", $ebusername);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}
/*** ***/
public function payment_gateways_show_for_edit()
{
    if (!isset($_POST['option-payment-gateway-edit'])) {
        return [];
    }

    $payment_gateways_id = intval($_POST['payment_gateways_id']);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM payment_gateways WHERE payment_gateways_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("MySQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $payment_gateways_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}
/*** ***/
public function payment_gateways_show()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM payment_gateways";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("MySQL prepare failed: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}
/*** ***/
public function paymentGatewaySetUp($gateway_filtered, $payment_user_id_filtered, $public_key_filtered, $private_key_filtered)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $gateway = strval($gateway_filtered);
    $payment_user_id = strval($payment_user_id_filtered);
    $public_key = strval($public_key_filtered);
    $private_key = strval($private_key_filtered);

    // Check if gateway already exists
    $queryCheck = "SELECT * FROM payment_gateways WHERE payment_gateways_brand = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("s", $gateway);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $numRows = $resultCheck->num_rows;
    $stmtCheck->close();

    if ($numRows === 0) {
        // Insert new payment gateway
        $queryInsert = "INSERT INTO payment_gateways (payment_gateways_brand, payment_gateways_username, payment_gateways_public_key, payment_gateways_privet_key, payment_gateways_status) VALUES (?, ?, ?, ?, 'NO')";
        $stmtInsert = $conn->prepare($queryInsert);
        $stmtInsert->bind_param("ssss", $gateway, $payment_user_id, $public_key, $private_key);

        if ($stmtInsert->execute()) {
            echo $this->ebDone();
        } else {
            echo $this->ebNotDone();
        }

        $stmtInsert->close();
    } else {
        echo $this->ebNotDone();
    }

}
/*** ***/
public function ebSendMail($email_filtered,$subjectfor_filtered,$messagepre_filtered)
{
/*** eMail to User ***/
$mailOffer = new PHPMailer(true);
try
{
//
$mailOffer->isSMTP();
//$mailOffer->SMTPDebug = SMTP::DEBUG_SERVER;
$mailOffer->Host = smtpHost;
$mailOffer->SMTPAuth   = true;
$mailOffer->Username   = smtpUsername;
$mailOffer->Password   = smtpPassword;
/* For port 587 and TLS */
$mailOffer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mailOffer->Port = smtpPort;
//
$mailOffer->setFrom(adminEmail, domain);
$mailOffer->addAddress($email_filtered);
$mailOffer->isHTML(true);
//$mailOffer->addAttachment('');
$con = eBConDb::eBgetInstance()->eBgetConection();
$mailOffer->Subject = mysqli_real_escape_string($con, $subjectfor_filtered);

$mailOffer->Body = mysqli_real_escape_string($con, $messagepre_filtered);
//
$mailOffer->send();
}
catch (Exception $e)
{
/*
echo "Message could not be sent. Mailer Error: {$mailOffer->ErrorInfo}";
*/
}
//
echo $this->ebDone();
}
/*** ***/
public function ebSendMailContact($email_address_filtered, $subjectfor_filtered, $messagepre_filtered)
{
/*** eMail to User ***/
$mailOffer = new PHPMailer(true);
try
{
//
$mailOffer->isSMTP();
//$mailOffer->SMTPDebug = SMTP::DEBUG_SERVER;
$mailOffer->Host = smtpHost;
$mailOffer->SMTPAuth   = true;
$mailOffer->Username   = smtpUsername;
$mailOffer->Password   = smtpPassword;
/* For port 587 and TLS */
$mailOffer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mailOffer->Port = smtpPort;
//
$mailOffer->setFrom(alertToAdmin, domain);
$mailOffer->AddReplyTo($email_address_filtered);
$mailOffer->addAddress(alertToAdmin);
$mailOffer->isHTML(true);
//$mailOffer->addAttachment('');
$con = eBConDb::eBgetInstance()->eBgetConection();
$mailOffer->Subject = mysqli_real_escape_string($con, $subjectfor_filtered);
$mailOffer->Body = mysqli_real_escape_string($con, $messagepre_filtered);
//
$mailOffer->send();
}
catch (Exception $e)
{
/*
echo "Message could not be sent. Mailer Error: {$mailOffer->ErrorInfo}";
*/
}
//
?>
<script>
setTimeout(function(){
window.location.replace('<?php echo hostingAndRoot."/index.php"; ?>');
}, 3000);
setTimeout();
</script>
<?php
}
/*** ***/
public function totalReferFirstLevel($user)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $user = strval($user);

    $query = "SELECT COUNT(omrusername) AS totalreferfirst_l 
              FROM excessusers 
              WHERE omrusername = ? AND active = 1 AND member_level >= 1";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}


/*** ***/
public function countFirstLevelOfInvite()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $user = $_SESSION['ebusername'];

    $query = "SELECT COUNT(omrusername) AS countFirstLevelTotal 
              FROM excessusers 
              WHERE omrusername = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}

/*** ***/
public function firstLevelOfInvite()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $user = $_SESSION['ebusername'];

    $query = "SELECT ebusername AS firstLevelOfInviteUsername 
              FROM excessusers 
              WHERE omrusername = ? AND ebusername != ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}
/*** ***/
public function secondLevelOfInvite($levelOne)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT ebusername AS secondLevelOfInviteUsername 
              FROM excessusers 
              WHERE omrusername = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $levelOne);
    $stmt->execute();
    $result = $stmt->get_result();

    $this->eBData = []; // reset to avoid carrying old data

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}

/*** ***/
public function thirdLevelOfInvite($levelTwo)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT ebusername AS thirdLevelOfInviteUsername 
              FROM excessusers 
              WHERE omrusername = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $levelTwo);
    $stmt->execute();
    $result = $stmt->get_result();

    $this->eBData = []; // reset before fetching

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}
/*** ***/
public function selectedUserPositionToLevelName($userpower_position)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $userpower_position = strval($userpower_position);

    $query = "SELECT userpower_level_names 
              FROM userpower 
              WHERE userpower_position = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userpower_position);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}
/*** ***/
public function selectedUserPositionToPower($userpower_position)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $userpower_position = strval($userpower_position);

    $query = "SELECT userpower_level_power 
              FROM userpower 
              WHERE userpower_position = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userpower_position);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}

/*** ***/
public function select_userpower()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $level_limit = 12;

    $query = "SELECT * FROM userpower WHERE userpower_level_power <= ? ORDER BY userpower_level_power DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $level_limit);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}

/*** ***/
public function selectedCountryAndCodeWhenSignup($selectCountryVal)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $selectCountryVal = intval($selectCountryVal);

    $query = "SELECT country_name, country_code FROM country_and_zone WHERE bay_dhl_country_zone_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selectCountryVal);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}
/*** ***/
public function select_country_id()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT bay_dhl_country_zone_id, country_name, country_code FROM country_and_zone ORDER BY country_name ASC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($rows = $result->fetch_array()) {
            echo "<option value='" . htmlspecialchars($rows['bay_dhl_country_zone_id']) . "'>" 
                 . htmlspecialchars($rows['country_name']) . "</option>";
        }
        $result->free();
    }

    $stmt->close();
}
/*** ***/
public function select_user_country()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT country_name FROM country_and_zone";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData;
}
/*** ***/
public function sorryEmailVerifyRequired()
{
    $ebusername = $_SESSION["ebusername"];
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare the statement
    $stmt = $conn->prepare("SELECT mobile FROM excessusers WHERE ebusername = ? AND active = 1");
    $stmt->bind_param("s", $ebusername);
    $stmt->execute();
    $resultCheck = $stmt->get_result();
    $numResultCheck = $resultCheck->num_rows;

    if ($numResultCheck == 1) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        echo "<div class='well'><pre><b>eMail Verification Required</b></pre></div>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo hostingAndRoot."/out/access/access-verify-re-send.php"; ?>');
        }, 3000);
        setTimeout();
        </script>
        <?php
    }
}
/*** ***/
public function sorryMobileVerifyRequired()
{
    $ebusername = $_SESSION["ebusername"];
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare the statement
    $stmt = $conn->prepare("SELECT mobile FROM excessusers WHERE ebusername = ? AND mobileactive = 1");
    $stmt->bind_param("s", $ebusername);
    $stmt->execute();
    $resultCheck = $stmt->get_result();
    $numResultCheck = $resultCheck->num_rows;

    if ($numResultCheck == 1) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        echo "<div class='well'><pre><b>Mobile Number Verification Required</b></pre></div>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo hostingAndRoot."/out/access/access-verify-mobile-number.php"; ?>');
        }, 3000);
        setTimeout();
        </script>
        <?php
    }
}
/*** ***/
public function sorryAddressVerifyRequired()
{
    $ebusername = $_SESSION["ebusername"];
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare the statement
    $stmt = $conn->prepare("SELECT mobile FROM excessusers WHERE ebusername = ? AND address_verified = 1");
    $stmt->bind_param("s", $ebusername);
    $stmt->execute();
    $resultCheck = $stmt->get_result();
    $numResultCheck = $resultCheck->num_rows;

    if ($numResultCheck == 1) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        echo "<div class='well'><pre><b>Address Verification Required</b></pre></div>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo hostingAndRoot."/out/access/access-verify-address.php"; ?>');
        }, 3000);
        setTimeout();
        </script>
        <?php
    }
}
/*** ***/
public function verifyMobile($smsCode)
{
    $ebusername = $_SESSION["ebusername"];
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Check if any account already has this mobile active
    $stmtCheck = $conn->prepare("SELECT mobile FROM excessusers WHERE mobileactive = 1");
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $numResultCheck = $resultCheck->num_rows;
    $stmtCheck->close();

    // Verify the SMS code for this user
    $stmtVerify = $conn->prepare("SELECT mobile_verification_codes FROM excessusers WHERE ebusername = ? AND mobile_verification_codes = ? AND mobileactive = 0");
    $stmtVerify->bind_param("si", $ebusername, $smsCode);
    $stmtVerify->execute();
    $resultOne = $stmtVerify->get_result();
    $numResultOne = $resultOne->num_rows;
    $stmtVerify->close();

    if ($numResultOne == 1 && $numResultCheck == 0) {
        // Activate mobile
        $stmtUpdate = $conn->prepare("UPDATE excessusers SET mobileactive = 1 WHERE ebusername = ?");
        $stmtUpdate->bind_param("s", $ebusername);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        echo $this->ebDone();
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo hostingAndRoot."/out/access/access-update-account-information.php"; ?>');
        }, 3000);
        setTimeout();
        </script>
        <?php
    } else {
        echo "<pre><b>This mobile number is already verified by another account.</b></pre>";
    }
}
/*** ***/
public function registration_admin_once_and_only(
    $ebusername_filtered, 
    $eBNewpassword, 
    $email_filtered, 
    $emailhash_filtered, 
    $full_name_filtered, 
    $signup_date, 
    $user_ip_address, 
    $code_mobile_filtered, 
    $countryNameWhenSignup
) {
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $conn->autocommit(false); // start transaction mode

    try {
        $usernameNeweB    = strval($ebusername_filtered);
        $member_level     = 13;
        $position_names   = "Chief Technology Officer";
        $account_type     = "Executive Leadership";

        // --- Step 1: Check if user already exists ---
        $queryCheck = "SELECT email 
                       FROM excessusers 
                       WHERE ebusername = ? OR email = ? OR member_level = ?";
        $stmtCheck = $conn->prepare($queryCheck);
        $stmtCheck->bind_param("ssi", $usernameNeweB, $email_filtered, $member_level);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $num_result_check = $resultCheck->num_rows;
        $stmtCheck->close();

        if ($num_result_check > 0) {
            echo "<div class='well'><b>Sorry this eMail or Username Exists!</b></div>";
            $conn->rollback();
            return;
        }

        // --- Step 2: Prepare default values ---
        $active = 0;
        $gender = "";
        $date_of_birth = "";
        $mobileactive = 0;

        // Generate SMS verification code
        $mobile_verification_codes = 0;
        if (!empty($code_mobile_filtered)) {
            $digits = '123456789';
            $generated = '';
            for ($i = 0; $i < 6; $i++) {
                $generated .= $digits[rand(0, strlen($digits)-1)];
            }
            $mobile_verification_codes = intval($generated);
        }

        // Generate address verification code
        $address_verification_codes = 0;
        if (!empty($code_mobile_filtered)) {
            $digits = '1235789';
            $generated = '';
            for ($i = 0; $i < 6; $i++) {
                $generated .= $digits[rand(0, strlen($digits)-1)];
            }
            $address_verification_codes = intval($generated);
        }

        $address_verified = 0;
        $omrusername = "";
        $paypalid = "";
        $bkashid = 0;
        $branch_name = "";
        $facebook_link = "";
        $twitter_link = "";
        $github_link = "";
        $linkedin_link = "";
        $pinterest_link = "";
        $youtube_link = "";
        $instagram_link = "";
        $profile_picture_link = "";
        $cover_photo_link = "";
        $address_line_1 = "";
        $address_line_2 = "";
        $city_town = "";
        $state_province_region = "";
        $postal_code = "";

        // --- Step 3: Insert into excessusers ---
        $adminQuery = "INSERT INTO excessusers (
            ebusername, ebpassword, email, emailhash, active, full_name, gender, date_of_birth, mobile, 
            mobile_verification_codes, mobileactive, signup_date, account_type, member_level, position_names, 
            user_ip, address_line_1, address_line_2, city_town, state_province_region, postal_code, country, 
            address_verification_codes, address_verified, omrusername, paypalid, bkashid, branch_name, 
            facebook_link, twitter_link, github_link, linkedin_link, pinterest_link, youtube_link, 
            instagram_link, profile_picture_link, cover_photo_link
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmtAdminAccount = $conn->prepare($adminQuery);
        $stmtAdminAccount->bind_param(
            "ssssissssiississssssssiisssssssssssss",
            $usernameNeweB, $eBNewpassword, $email_filtered, $emailhash_filtered,
            $active, $full_name_filtered, $gender, $date_of_birth, $code_mobile_filtered,
            $mobile_verification_codes, $mobileactive, $signup_date, $account_type, $member_level,
            $position_names, $user_ip_address, $address_line_1, $address_line_2, $city_town,
            $state_province_region, $postal_code, $countryNameWhenSignup, $address_verification_codes,
            $address_verified, $omrusername, $paypalid, $bkashid, $branch_name, $facebook_link,
            $twitter_link, $github_link, $linkedin_link, $pinterest_link, $youtube_link,
            $instagram_link, $profile_picture_link, $cover_photo_link
        );
        $stmtAdminAccount->execute();
        $stmtAdminAccount->close();

        // --- Step 4: Insert into excess_merchant_business_details ---
        $business_name = "";
        $business_vat_tax_gst = "";
        $business_title_one = "";
        $business_title_two = "";
        $business_full_address = "";
        $business_city_town = "";
        $business_state_province_region = "";
        $business_postal_code = "";
        $business_country = "";
        $business_geolocation_latitude = floatval(0.0);
        $business_geolocation_longitude = floatval(0.0);
        $cash_on_delivery_distance_meter = 10000;
        $business_logo_link = "";
        $business_cover_photo_link = "";
        $verification_status = "OK";

        $merchantQuery = "INSERT INTO excess_merchant_business_details (
            business_username, business_name, business_vat_tax_gst, business_title_one, business_title_two, 
            business_full_address, business_city_town, business_state_province_region, business_postal_code, business_country, 
            business_geolocation_latitude, business_geolocation_longitude, cash_on_delivery_distance_meter, business_logo_link, 
            business_cover_photo_link, verification_status
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmtMerchant = $conn->prepare($merchantQuery);
        $stmtMerchant->bind_param(
            "ssssssssssddisss",
            $usernameNeweB, $business_name, $business_vat_tax_gst, $business_title_one,
            $business_title_two, $business_full_address, $business_city_town,
            $business_state_province_region, $business_postal_code, $business_country,
            $business_geolocation_latitude, $business_geolocation_longitude,
            $cash_on_delivery_distance_meter, $business_logo_link,
            $business_cover_photo_link, $verification_status
        );
        $stmtMerchant->execute();
        $stmtMerchant->close();

        // --- Step 5: Commit transaction ---
        $conn->commit();

        // --- Step 6: Send verification email ---
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = smtpUsername;
            $mail->Password   = smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = smtpPort;

            $mail->setFrom(adminEmail, domain);
            $mail->addAddress($email_filtered);
            $mail->isHTML(true);

            $mail->Subject = "eMail Verification and Business Settings for Admin Account";
            //
            $message ="<html>";
            $message .="<head>";
            $message .="<meta charset='utf-8'>";
            $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
            $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            $message .="<style type='text/css'>
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{-ms-interpolation-mode: bicubic;}
            /* RESET STYLES */
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors]
            {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            }
            /* MOBILE STYLES */
            @media screen and (max-width: 525px)
            {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper
            {
            width: 100% !important;
            max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img
            {
            margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide 
            {
            display: none !important;
            }
            .img-max 
            {
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table
            {
            width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding
            {
            padding: 6px 3% 9px 3% !important;
            }
            .padding-meta
            {
            padding: 9px 3% 0px 3% !important;
            text-align: center;
            }
            .padding-copy
            {
            padding: 9px 3% 9px 3% !important;
            text-align: center;
            }
            .no-padding
            {
            padding: 0 !important;
            }
            .section-padding
            {
            padding: 9px 9px 9px 9px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container
            {
            margin: 0 auto;
            width: 100% !important;
            }
            .mobile-button
            {
            padding: 9px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
            }
            }
            /* ANDROID CENTER FIX */
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
            </style>";
            $message .="</head>";
            $message .="<body>";

            $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
            //
            $message .="<tr>";
            $message .="<td>2-Steps for Admin Account.</td>";
            $message .="</tr>";
            //
            $message .="<tr>";
            $message .="<td>Please follow the instructions below.</td>";
            $message .="</tr>";
            //
            $message .="<tr>";
            $message .="<td>Username: $usernameNeweB</td>";
            $message .="</tr>";
            //
            $message .="<tr bgcolor='#014693'>";
            $message .="<td>";
            $message .="<a href='";
            $message .=hostingAndRoot."/out/access/access-verify.php?email=$email_filtered&emailhash=$emailhash_filtered";
            $message .="' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>1. Please Login and Activated your account.</a>";
            $message .="</td>";
            $message .="</tr>";
            //
            $message .="<br>";
            //
            $message .="<tr bgcolor='#014693'>";
            $message .="<td>";
            $message .="<a href='";
            $message .=hostingAndRoot."/out/access/access-admin-merchant-first-time-set-up.php";
            $message .="' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>2. Please Submit Your Business Settings.</a>";
            $message .="</td>";
            $message .="</tr>";
            //
            $message .="</table>";
            $message .="</body>";
            $message .="</html>";
            //
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            // optional: log error silently
        }

        echo "<div class='well'><b>Admin Signup Done.</b></div>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo outAccessLink; ?>/home.php');
        }, 3000);
        </script>
        <?php

    } catch (Exception $e) {
        $conn->rollback(); // rollback if anything failed
        echo "<div class='well'><b>Error: Signup failed. Please try again.</b></div>";
    }

    $conn->autocommit(true);
}
/*** ***/
public function update_merchant_business_info_read()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Step 1: Get the merchant username with member_level = 13
    $stmtOne = $conn->prepare("SELECT ebusername FROM excessusers WHERE member_level = ?");
    $memberLevel = 13;
    $stmtOne->bind_param("i", $memberLevel);
    $stmtOne->execute();
    $resultOne = $stmtOne->get_result();

    if ($resultOne->num_rows == 1) {
        $rowOne = $resultOne->fetch_assoc();
        $merchantUsername = $rowOne['ebusername'];
        $stmtOne->close();

        // Step 2: Get merchant business details
        $stmtDetails = $conn->prepare("SELECT * FROM excess_merchant_business_details WHERE business_username = ?");
        $stmtDetails->bind_param("s", $merchantUsername);
        $stmtDetails->execute();
        $resultDetails = $stmtDetails->get_result();

        if ($resultDetails->num_rows == 1) {
            while ($rows = $resultDetails->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmtDetails->close();
            return $this->eBData;
        }

        $stmtDetails->close();
    } else {
        $stmtOne->close();
    }
    return [];
}
/*** ***/
public function update_admin_business_info_read()
{
    if (!isset($_SESSION['ebusername'])) {
        return [];
    }

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $business_username = $_SESSION['ebusername'];

    // Prepare statement
    $stmt = $conn->prepare("SELECT * FROM excess_merchant_business_details WHERE business_username = ?");
    $stmt->bind_param("s", $business_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}
/*** ***/
public function select_business_country_name_from_bay_dhl_country_id()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare statement
    $stmt = $conn->prepare("SELECT * FROM country_and_zone");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}

/*** ***/
public function update_merchant_business_details(
    $business_name_filtered,
    $business_vat_tax_gst_filtered,
    $business_title_one_filtered,
    $business_title_two_filtered,
    $business_full_address_filtered,
    $business_city_town_filtered,
    $business_state_province_region_filtered,
    $business_postal_code_filtered,
    $business_country_filtered,
    $business_geolocation_latitude_filtered,
    $business_geolocation_longitude_filtered,
    $cash_on_delivery_distance_meter_filtered
) {
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $business_username = isset($_SESSION['ebusername']) ? $_SESSION['ebusername'] : "";

    // Step 1: check if record exists
    $queryCheck = "SELECT * FROM excess_merchant_business_details WHERE business_username = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("s", $business_username);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $num_result = $resultCheck->num_rows;
    $stmtCheck->close();

    if ($num_result === 1) {
        // Step 2: build dynamic update query
        $fields = [];
        $params = [];
        $types  = "";

        if (isset($business_name_filtered)) {
            $fields[] = "business_name = ?";
            $params[] = $business_name_filtered;
            $types   .= "s";
        }
        if (isset($business_vat_tax_gst_filtered)) {
            $fields[] = "business_vat_tax_gst = ?";
            $params[] = $business_vat_tax_gst_filtered;
            $types   .= "s";
        }
        if (isset($business_title_one_filtered)) {
            $fields[] = "business_title_one = ?";
            $params[] = $business_title_one_filtered;
            $types   .= "s";
        }
        if (isset($business_title_two_filtered)) {
            $fields[] = "business_title_two = ?";
            $params[] = $business_title_two_filtered;
            $types   .= "s";
        }
        if (isset($business_full_address_filtered)) {
            $fields[] = "business_full_address = ?";
            $params[] = $business_full_address_filtered;
            $types   .= "s";
        }
        if (isset($business_city_town_filtered)) {
            $fields[] = "business_city_town = ?";
            $params[] = $business_city_town_filtered;
            $types   .= "s";
        }
        if (isset($business_state_province_region_filtered)) {
            $fields[] = "business_state_province_region = ?";
            $params[] = $business_state_province_region_filtered;
            $types   .= "s";
        }
        if (isset($business_postal_code_filtered)) {
            $fields[] = "business_postal_code = ?";
            $params[] = $business_postal_code_filtered;
            $types   .= "s";
        }
        if (isset($business_country_filtered)) {
            $fields[] = "business_country = ?";
            $params[] = $business_country_filtered;
            $types   .= "s";
        }
        if (isset($business_geolocation_latitude_filtered)) {
            $fields[] = "business_geolocation_latitude = ?";
            $params[] = $business_geolocation_latitude_filtered;
            $types   .= "d"; // double
        }
        if (isset($business_geolocation_longitude_filtered)) {
            $fields[] = "business_geolocation_longitude = ?";
            $params[] = $business_geolocation_longitude_filtered;
            $types   .= "d"; // double
        }
        if (isset($cash_on_delivery_distance_meter_filtered)) {
            $fields[] = "cash_on_delivery_distance_meter = ?";
            $params[] = intval($cash_on_delivery_distance_meter_filtered);
            $types   .= "i"; // integer
        }

        if (!empty($fields)) {
            $queryUpdate = "UPDATE excess_merchant_business_details SET " . implode(", ", $fields) . " WHERE business_username = ?";
            $stmtUpdate = $conn->prepare($queryUpdate);

            // Add business_username as last parameter
            $params[] = $business_username;
            $types   .= "s";

            // Bind params dynamically
            $stmtUpdate->bind_param($types, ...$params);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        echo $this->ebDone();
    }
}

/*** ***/
public function registrationInvitedSignup(
    $full_name,
    $code_mobile,
    $ebusername,
    $ebpassword,
    $email,
    $signup_date,
    $user_ip_address,
    $countryNameWhenSignup
) {
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $ebusername = strtolower(strval($ebusername));

    $stmtEmail = $conn->prepare("SELECT email FROM excessusers WHERE email = ?");
    $stmtEmail->bind_param("s", $email);
    $stmtEmail->execute();
    $resultEmail = $stmtEmail->get_result();
    $numTestEmailOne = $resultEmail->num_rows;
    $stmtEmail->close();

    $stmtUsername = $conn->prepare("SELECT ebusername FROM excessusers WHERE ebusername = ?");
    $stmtUsername->bind_param("s", $ebusername);
    $stmtUsername->execute();
    $resultUsernameCheck = $stmtUsername->get_result();
    $num_testresultquery2ndForInviet = $resultUsernameCheck->num_rows;
    $stmtUsername->close();

    if ($numTestEmailOne == 1 && $num_testresultquery2ndForInviet == 0) {
        $stmtUpdate = $conn->prepare("UPDATE excessusers SET ebusername = ?, ebpassword = ?, active = ?, full_name = ?, mobile = ?, signup_date = ?, user_ip = ?, country = ? WHERE email = ?");

        $active = 1;

        $stmtUpdate->bind_param(
            "sssssssss",
            $ebusername,
            $ebpassword,
            $active,
            $full_name,
            $code_mobile,
            $signup_date,
            $user_ip_address,
            $countryNameWhenSignup,
            $email
        );

        $invitedSignup = $stmtUpdate->execute();
        $stmtUpdate->close();

        if ($invitedSignup) {
            $mailAlert = new PHPMailer(true);
            try {
                $mailAlert->isSMTP();
                $mailAlert->Host = smtpHost;
                $mailAlert->SMTPAuth = true;
                $mailAlert->Username = smtpUsername;
                $mailAlert->Password = smtpPassword;
                $mailAlert->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mailAlert->Port = smtpPort;

                $mailAlert->setFrom(adminEmail, domain);
                $mailAlert->addAddress(alertToAdmin);
                $mailAlert->isHTML(true);
                $mailAlert->Subject = "New invited user $ebusername";

                $message  ="<html>";
                $message .="<head>";
                $message .="<meta charset='utf-8'>";
                $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
                $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
                $message .="<style type='text/css'>
                /* CLIENT-SPECIFIC STYLES */
                body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
                table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
                img{-ms-interpolation-mode: bicubic;}
                /* RESET STYLES */
                img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
                table{border-collapse: collapse !important;}
                body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
                /* iOS BLUE LINKS */
                a[x-apple-data-detectors]
                {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;

                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                }
                /* MOBILE STYLES */
                @media screen and (max-width: 525px)
                {
                /* ALLOWS FOR FLUID TABLES */
                .wrapper
                {
                width: 100% !important;
                max-width: 100% !important;
                }
                /* ADJUSTS LAYOUT OF LOGO IMAGE */
                .logo img
                {
                margin: 0 auto !important;
                }
                /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
                .mobile-hide 
                {
                display: none !important;
                }
                .img-max 
                {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
                }
                /* FULL-WIDTH TABLES */
                .responsive-table
                {
                width: 100% !important;
                }
                /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
                .padding
                {
                padding: 6px 3% 9px 3% !important;
                }
                .padding-meta
                {
                padding: 9px 3% 0px 3% !important;
                text-align: center;
                }
                .padding-copy
                {
                padding: 9px 3% 9px 3% !important;
                text-align: center;
                }
                .no-padding
                {
                padding: 0 !important;
                }
                .section-padding
                {
                padding: 9px 9px 9px 9px !important;
                }
                /* ADJUST BUTTONS ON MOBILE */
                .mobile-button-container
                {
                margin: 0 auto;
                width: 100% !important;
                }
                .mobile-button
                {
                padding: 9px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
                }
                }
                /* ANDROID CENTER FIX */
                div[style*='margin: 16px 0;'] { margin: 0 !important; }
                </style>";
                $message .="</head>";
                $message .="<body>";
                $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
                //
                $message .="<tr>";
                $message .="<td>New user invited $ebusername registration done.</td>";
                $message .="</tr>";
                //
                $message .="</table>";
                $message .="</body>";
                $message .="</html>";

                $mailAlert->Body = $message;
                $mailAlert->send();
            } catch (Exception $e) {
                // log error in production
            }

            echo "<b>Sign Up Done.</b>";
            echo '</div></div></div></div>';
            include_once(eblayout . '/a-common-footer-admin.php');
            exit();
        }
    } else {
        echo "<pre><b>This username already taken or this email is registered with us.</b></pre>";
    }
}
/*** ***/
public function reSendInviteSignURL($email_filtered){
    
        // Send email using PHPMailer
        $mailInvite = new PHPMailer(true);
        try {
            $mailInvite->isSMTP();
            $mailInvite->Host = smtpHost;
            $mailInvite->SMTPAuth = true;
            $mailInvite->Username = smtpUsername;
            $mailInvite->Password = smtpPassword;
            $mailInvite->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailInvite->Port = smtpPort;

            $mailInvite->setFrom(adminEmail, domain);
            $mailInvite->addAddress($email_filtered);
            $mailInvite->isHTML(true);
            $mailInvite->Subject = "Hi join with us";

            $signupLink = hostingAndRoot."/out/access/signup-by-invite.php?email=$email_filtered";
            //
            $message  ="<html>";
            $message .="<head>";
            $message .="<title>Hi we invite you to join with us</title>";
            $message .="<meta charset='utf-8'>";
            $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
            $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            $message .="<style type='text/css'>
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{-ms-interpolation-mode: bicubic;}
            /* RESET STYLES */
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors]
            {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            }
            /* MOBILE STYLES */
            @media screen and (max-width: 525px)
            {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper
            {
            width: 100% !important;
            max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img
            {
            margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide 
            {
            display: none !important;
            }
            .img-max 
            {
            max-width: 100% !important;
            width: 100% !important;

            height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table
            {
            width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding
            {
            padding: 6px 3% 9px 3% !important;
            }
            .padding-meta
            {
            padding: 9px 3% 0px 3% !important;
            text-align: center;
            }
            .padding-copy
            {
            padding: 9px 3% 9px 3% !important;
            text-align: center;
            }
            .no-padding
            {
            padding: 0 !important;
            }
            .section-padding
            {
            padding: 9px 9px 9px 9px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container
            {
            margin: 0 auto;
            width: 100% !important;
            }
            .mobile-button
            {
            padding: 9px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
            }
            }
            /* ANDROID CENTER FIX */
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
            </style>";
            $message .="</head>";
            $message .="<body>";
            $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
            //
            $message .="<tr>";
            $message .="<td>We invite you to join us</td>";
            $message .="</tr>";
            //
            $message .="<tr bgcolor='#014693'>";
            $message .="<td>";
            $message .="<a href='";
            $message .=hostingAndRoot."/out/access/signup-by-invite.php?email=$email_filtered";
            $message .="' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>SIGN UP</a>";
            $message .="</td>";
            $message .="</tr>";
            //
            $message .="</table>";
            $message .="</body>";
            $message .="</html>";
            //
            $mailInvite->Body = $message;
            $mailInvite->send();
        } catch (Exception $e) {
            // Mail sending failed, but continue
        }
    
}
/*** ***/
public function registration(
    $full_name_filtered, 
    $email_filtered, 
    $code_mobile_filtered, 
    $ebusername_filtered, 
    $passWithHash_filtered, 
    $hash_filtered, 
    $signup_date_filtered, 
    $ip_user_filtered, 
    $countryNameWhenSignup_filtered
) {
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $ebusername_filtered = strtolower(strval($ebusername_filtered));
    $account_type_filtered = "Remote Teams";


    /* === Check omrusername has a value === */
    $stmtOmrusername = $conn->prepare("SELECT omrusername FROM excessusers WHERE email = ?");
    $stmtOmrusername->bind_param("s", $email_filtered);
    $stmtOmrusername->execute();
    $stmtOmrusername->bind_result($dbOmrusername_filtered);
    $stmtOmrusername->fetch();
    $stmtOmrusername->close();

    /* === Check duplicate email === */
    $stmtEmail = $conn->prepare("SELECT email FROM excessusers WHERE email = ?");
    $stmtEmail->bind_param("s", $email_filtered);
    $stmtEmail->execute();
    $stmtEmail->store_result();
    $numCheckResultForEmail = $stmtEmail->num_rows;
    $stmtEmail->close();

    /* === Check duplicate username === */
    $stmtUsername = $conn->prepare("SELECT ebusername FROM excessusers WHERE ebusername = ?");
    $stmtUsername->bind_param("s", $ebusername_filtered);
    $stmtUsername->execute();
    $stmtUsername->store_result();
    $numCheckResultForUsername = $stmtUsername->num_rows;
    $stmtUsername->close();

    /* === Check duplicate mobile (if active) === */
    $stmtMobile = $conn->prepare("SELECT mobile FROM excessusers WHERE mobile = ? AND mobileactive = 1");
    $stmtMobile->bind_param("s", $code_mobile_filtered);
    $stmtMobile->execute();
    $stmtMobile->store_result();
    $numCheckResultForMobile = $stmtMobile->num_rows;
    $stmtMobile->close();

    // === Handle duplicates ===
    if (!empty($dbOmrusername_filtered)) {
        echo "<pre>Please check your eMail for Sign Up</pre>";
        $this->reSendInviteSignURL($email_filtered);
        return;
    }
    if ($numCheckResultForEmail > 0) {
        echo "<pre>Sorry this eMail Exists!</pre>";
        return;
    }
    if ($numCheckResultForUsername > 0) {
        echo "<pre>Sorry this Username Exists!</pre>";
        return;
    }
    if ($numCheckResultForMobile > 0) {
        echo "<pre>Sorry this Mobile Exists!</pre>";
        return;
    }

    // === Default values ===
    $active_filtered = 0;
    $gender_filtered = "";
    $date_of_birth_filtered = "";
    $mobileactive_filtered = 0;
    $member_level_filtered = 1;
    $position_names_filtered = "";
    $address_verified_filtered = 0;
    $omrusername_filtered = isset($_SESSION['omrebusername']) ? strtolower(strval($_SESSION['omrebusername'])) : "";
    $paypalid_filtered = "";
    $bkashid_filtered = 0;
    $branch_name_filtered = "";
    $facebook_link_filtered = "";
    $twitter_link_filtered = "";
    $github_link_filtered = "";
    $linkedin_link_filtered = "";
    $pinterest_link_filtered = "";
    $youtube_link_filtered = "";
    $instagram_link_filtered = "";
    $profile_picture_link_filtered = "";
    $cover_photo_link_filtered = "";
    $address_line_1_filtered = "";
    $address_line_2_filtered = "";
    $city_town_filtered = "";
    $state_province_region_filtered = "";
    $postal_code_filtered = "";

    // === Generate mobile verification code ===
    $mobile_verification_codes_filtered = 0;
    if (!empty($code_mobile_filtered)) {
        $digits = '123456789';
        $generated = '';
        for ($i = 0; $i < 6; $i++) {
            $generated .= $digits[rand(0, strlen($digits)-1)];
        }
        $mobile_verification_codes_filtered = intval($generated);
    }

    // === Generate address verification code ===
    $address_verification_codes_filtered = 0;
    if (!empty($code_mobile_filtered)) {
        $digits = '1235789';
        $generated = '';
        for ($i = 0; $i < 6; $i++) {
            $generated .= $digits[rand(0, strlen($digits)-1)];
        }
        $address_verification_codes_filtered = intval($generated);
    }

    // --- Step 3: Insert into excessusers ---
    $newUserAccountQuery = "INSERT INTO excessusers (
        ebusername, ebpassword, email, emailhash, active, full_name, gender, date_of_birth, mobile, 
        mobile_verification_codes, mobileactive, signup_date, account_type, member_level, position_names, 
        user_ip, address_line_1, address_line_2, city_town, state_province_region, postal_code, country, 
        address_verification_codes, address_verified, omrusername, paypalid, bkashid, branch_name, 
        facebook_link, twitter_link, github_link, linkedin_link, pinterest_link, youtube_link, 
        instagram_link, profile_picture_link, cover_photo_link
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $inserNewUserAccount = $conn->prepare($newUserAccountQuery);
    $inserNewUserAccount->bind_param(
        "ssssissssiississssssssiisssssssssssss",
        $ebusername_filtered, $passWithHash_filtered, $email_filtered, $hash_filtered,
        $active_filtered, $full_name_filtered, $gender_filtered, $date_of_birth_filtered, $code_mobile_filtered,
        $mobile_verification_codes_filtered, $mobileactive_filtered, $signup_date_filtered, $account_type_filtered, $member_level_filtered,
        $position_names_filtered, $ip_user_filtered, $address_line_1_filtered, $address_line_2_filtered, $city_town_filtered,
        $state_province_region_filtered, $postal_code_filtered, $countryNameWhenSignup_filtered, $address_verification_codes_filtered,
        $address_verified_filtered, $omrusername_filtered, $paypalid_filtered, $bkashid_filtered, $branch_name_filtered, $facebook_link_filtered,
        $twitter_link_filtered, $github_link_filtered, $linkedin_link_filtered, $pinterest_link_filtered, $youtube_link_filtered,
        $instagram_link_filtered, $profile_picture_link_filtered, $cover_photo_link_filtered
    );
    $inserNewUserAccount->execute();
    $inserNewUserAccount->close();

    if ($inserNewUserAccount) {
        // === Send user verification email ===
        $mailSignup = new PHPMailer(true);
        try {
            $mailSignup->isSMTP();
            $mailSignup->Host = smtpHost;
            $mailSignup->SMTPAuth   = true;
            $mailSignup->Username   = smtpUsername;
            $mailSignup->Password   = smtpPassword;
            $mailSignup->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailSignup->Port = smtpPort;

            $mailSignup->setFrom(adminEmail, domain);
            $mailSignup->addAddress($email_filtered);
            $mailSignup->isHTML(true);
            $mailSignup->Subject = "eMail verification for User account";
            //
            $messageNewUser  ="<html>";
            $messageNewUser .="<head>";
            $messageNewUser .="<meta charset='utf-8'>";
            $messageNewUser .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
            $messageNewUser .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            $messageNewUser .="<style type='text/css'>
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{-ms-interpolation-mode: bicubic;}
            /* RESET STYLES */
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors]
            {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            }
            /* MOBILE STYLES */
            @media screen and (max-width: 525px)
            {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper
            {
            width: 100% !important;
            max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img
            {
            margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide 

            {
            display: none !important;
            }
            .img-max 
            {
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table
            {
            width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding
            {
            padding: 6px 3% 9px 3% !important;
            }
            .padding-meta
            {
            padding: 9px 3% 0px 3% !important;
            text-align: center;
            }
            .padding-copy
            {
            padding: 9px 3% 9px 3% !important;
            text-align: center;
            }
            .no-padding
            {
            padding: 0 !important;
            }
            .section-padding
            {
            padding: 9px 9px 9px 9px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container
            {
            margin: 0 auto;
            width: 100% !important;
            }
            .mobile-button
            {
            padding: 9px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
            }
            }
            /* ANDROID CENTER FIX */
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
            </style>";
            $messageNewUser .="</head>";
            $messageNewUser .="<body>";
            $messageNewUser .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
            //
            $messageNewUser .="<tr>";
            $messageNewUser .="<td>eMail verification for user account.</td>";
            $messageNewUser .="</tr>";
            //
            $messageNewUser .="<tr>";
            $messageNewUser .="<td>Please follow the instructions below.</td>";
            $messageNewUser .="</tr>";
            //
            $messageNewUser .="<tr>";
            $messageNewUser .="<td>Username: $ebusername_filtered</td>";
            $messageNewUser .="</tr>";
            //
            $messageNewUser .="<tr>";
            $messageNewUser .="<td>Please Login and Activated your account.</td>";
            $messageNewUser .="</tr>";
            //
            $messageNewUser .="<tr bgcolor='#014693'>";
            $messageNewUser .="<td>";
            $messageNewUser .="<a href='";
            $messageNewUser .=hostingAndRoot."/out/access/access-verify.php?email=$email_filtered&emailhash=$hash_filtered";
            $messageNewUser .="' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>Active your Account</a>";
            $messageNewUser .="</td>";
            $messageNewUser .="</tr>";
            //
            $messageNewUser .="</table>";
            $messageNewUser .="</body>";
            $messageNewUser .="</html>";
            //
            $mailSignup->Body = $messageNewUser;
            $mailSignup->send();
        } catch (Exception $e) { /* handle silently */ }

        // === Notify admin ===
        $mail2nd = new PHPMailer(true);
        try {
            $mail2nd->isSMTP();
            $mail2nd->Host = smtpHost;
            $mail2nd->SMTPAuth   = true;
            $mail2nd->Username   = smtpUsername;
            $mail2nd->Password   = smtpPassword;
            $mail2nd->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail2nd->Port = smtpPort;

            $mail2nd->setFrom(adminEmail, domain);
            $mail2nd->addAddress(alertToAdmin);
            $mail2nd->isHTML(true);
            $mail2nd->Subject = "New user $ebusername_filtered";
            //
            $messageSystem  ="<html>";
            $messageSystem .="<head>";
            $messageSystem .="<meta charset='utf-8'>";
            $messageSystem .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
            $messageSystem .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            $messageSystem .="<style type='text/css'>
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{-ms-interpolation-mode: bicubic;}
            /* RESET STYLES */
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors]
            {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            }
            /* MOBILE STYLES */
            @media screen and (max-width: 525px)
            {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper
            {
            width: 100% !important;
            max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img
            {
            margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide 
            {
            display: none !important;
            }
            .img-max 
            {
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table
            {
            width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding
            {
            padding: 6px 3% 9px 3% !important;
            }
            .padding-meta
            {
            padding: 9px 3% 0px 3% !important;
            text-align: center;
            }
            .padding-copy
            {
            padding: 9px 3% 9px 3% !important;
            text-align: center;
            }
            .no-padding
            {
            padding: 0 !important;
            }
            .section-padding
            {
            padding: 9px 9px 9px 9px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container
            {
            margin: 0 auto;
            width: 100% !important;
            }
            .mobile-button
            {
            padding: 9px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
            }
            }
            /* ANDROID CENTER FIX */
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
            </style>";
            $messageSystem .="</head>";
            $messageSystem .="<body>";
            $messageSystem .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
            //
            $messageSystem .="<tr>";
            $messageSystem .="<td>New user: $ebusername_filtered registration done.</td>";
            $messageSystem .="</tr>";
            //
            $messageSystem .="</table>";
            $messageSystem .="</body>";
            $messageSystem .="</html>";
            //
            $mail2nd->Body = $messageSystem;
            $mail2nd->send();
        } catch (Exception $e) { /* handle silently */ }
        echo "<div class='well'><b>Sign Up Done.</b></div>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo outAccessLink; ?>/home-for-first-signup.php');
        }, 100);
        </script>
        <?php
    }
}


/*** ***/
public function inviteAFriend($email_filtered)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    
    // Check if email already exists
    $stmtCheck = $conn->prepare("SELECT email FROM excessusers WHERE email=?");
    $stmtCheck->bind_param("s", $email_filtered);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $numInviteAFriend = $resultCheck->num_rows;
    $stmtCheck->close();

    if($numInviteAFriend == 1){
        echo "<b>Sorry this eMail Exits!</b>";
        return;
    }

    $ebusername = "";
    $ebpassword = "";
    //
    if (!empty($email_filtered))
    {
    $generate_email_hash_formate = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $generated_new_email_hash = ''; 
    for ($i = 0; $i < 40; $i++)
    {
    $generated_new_email_hash .= $generate_email_hash_formate[rand(0, strlen($generate_email_hash_formate)-1)];
    }
    $emailhash = $generated_new_email_hash;
    }
    //
    $active = 0;
    $full_name = "";
    $gender = "";
    $date_of_birth = "";
    $mobile = 0;
    //
    if (!empty($email_filtered))
    {
    $digits = '123456789';
    $generated = '';
    for ($i = 0; $i < 6; $i++) {
    $generated .= $digits[rand(0, strlen($digits)-1)];
    }
    $mobile_verification_codes = intval($generated);
    }
    //
    $mobileactive = 0;
    $signup_date = "";
    $account_type = "Remote Teams";
    $member_level = 1;
    $position_names = "invited";
    $user_ip = "";
    $address_line_1 = "";
    $address_line_2 = "";
    $city_town = "";
    $state_province_region = "";
    $postal_code = "";
    $country = "";
    if (!empty($email_filtered))
    {
    $digits = '1235789';
    $generated = '';
    for ($i = 0; $i < 6; $i++) {
    $generated .= $digits[rand(0, strlen($digits)-1)];
    }
    $address_verification_codes = intval($generated);
    }
    //
    $address_verified = 0;
    if(isset($_SESSION['ebusername']))
    {
    $omrusername = $_SESSION['ebusername'];
    }
    $paypalid = ""; 
    $bkashid = "";
    $branch_name = "";
    $facebook_link = ""; 
    $twitter_link = ""; 
    $github_link = ""; 
    $linkedin_link = ""; 
    $pinterest_link = ""; 
    $youtube_link = ""; 
    $instagram_link = ""; 
    $profile_picture_link = ""; 
    $cover_photo_link = "";
    //
    $inviteSignupQuery = "INSERT INTO excessusers (
            ebusername, ebpassword, email, emailhash, active, full_name, gender, date_of_birth, mobile, 
            mobile_verification_codes, mobileactive, signup_date, account_type, member_level, position_names, 
            user_ip, address_line_1, address_line_2, city_town, state_province_region, postal_code, country, 
            address_verification_codes, address_verified, omrusername, paypalid, bkashid, branch_name, 
            facebook_link, twitter_link, github_link, linkedin_link, pinterest_link, youtube_link, 
            instagram_link, profile_picture_link, cover_photo_link
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    
    $stmtInsert = $conn->prepare($inviteSignupQuery);
    
    $stmtInsert->bind_param("ssssissssiississssssssiisssssssssssss",
            $ebusername, $ebpassword, $email_filtered, $emailhash,
            $active, $full_name, $gender, $date_of_birth, $mobile,
            $mobile_verification_codes, $mobileactive, $signup_date, $account_type, $member_level,
            $position_names, $user_ip, $address_line_1, $address_line_2, $city_town,
            $state_province_region, $postal_code, $country, $address_verification_codes,
            $address_verified, $omrusername, $paypalid, $bkashid, $branch_name, $facebook_link,
            $twitter_link, $github_link, $linkedin_link, $pinterest_link, $youtube_link,
            $instagram_link, $profile_picture_link, $cover_photo_link);
    

    $resultInvited = $stmtInsert->execute();
    $stmtInsert->close();

    if($resultInvited)
        {
        // Send email using PHPMailer
        $mailInvite = new PHPMailer(true);
        try {
            $mailInvite->isSMTP();
            $mailInvite->Host = smtpHost;
            $mailInvite->SMTPAuth = true;
            $mailInvite->Username = smtpUsername;
            $mailInvite->Password = smtpPassword;
            $mailInvite->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailInvite->Port = smtpPort;

            $mailInvite->setFrom(adminEmail, domain);
            $mailInvite->addAddress($email_filtered);
            $mailInvite->isHTML(true);
            $mailInvite->Subject = "Hi $omrusername invited you to join with us";

            $signupLink = hostingAndRoot."/out/access/signup-by-invite.php?email=$email_filtered";
            //
            $message  ="<html>";
            $message .="<head>";
            $message .="<title>Hi $omrusername invited you to join with us</title>";
            $message .="<meta charset='utf-8'>";
            $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
            $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            $message .="<style type='text/css'>
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{-ms-interpolation-mode: bicubic;}
            /* RESET STYLES */
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors]
            {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            }
            /* MOBILE STYLES */
            @media screen and (max-width: 525px)
            {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper
            {
            width: 100% !important;
            max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img
            {
            margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide 
            {
            display: none !important;
            }
            .img-max 
            {
            max-width: 100% !important;
            width: 100% !important;

            height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table
            {
            width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding
            {
            padding: 6px 3% 9px 3% !important;
            }
            .padding-meta
            {
            padding: 9px 3% 0px 3% !important;
            text-align: center;
            }
            .padding-copy
            {
            padding: 9px 3% 9px 3% !important;
            text-align: center;
            }
            .no-padding
            {
            padding: 0 !important;
            }
            .section-padding
            {
            padding: 9px 9px 9px 9px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container
            {
            margin: 0 auto;
            width: 100% !important;
            }
            .mobile-button
            {
            padding: 9px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
            }
            }
            /* ANDROID CENTER FIX */
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
            </style>";
            $message .="</head>";
            $message .="<body>";
            $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
            //
            $message .="<tr>";
            $message .="<td>We invite you to join us</td>";
            $message .="</tr>";
            //
            $message .="<tr bgcolor='#014693'>";
            $message .="<td>";
            $message .="<a href='";
            $message .=hostingAndRoot."/out/access/signup-by-invite.php?email=$email_filtered";
            $message .="' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>SIGN UP</a>";
            $message .="</td>";
            $message .="</tr>";
            //
            $message .="</table>";
            $message .="</body>";
            $message .="</html>";
            //
            $mailInvite->Body = $message;
            $mailInvite->send();
        } catch (Exception $e) {
            // Mail sending failed, but continue
        }

        echo "<pre><b>Invite Done</b></pre>";
    }

}
/*** ***/
public function varify_address()
{
    if (isset($_REQUEST['submit_address_verification_code'])) {
        $address_verification_codes = intval($_REQUEST['addressCode']);
        $ebusername = $_SESSION['ebusername'];
        $conn = eBConDb::eBgetInstance()->eBgetConection();

        // Check if the address verification code is correct and not verified yet
        $stmtCheck = $conn->prepare("SELECT address_verification_codes FROM excessusers WHERE ebusername=? AND address_verification_codes=? AND address_verified=0");
        $stmtCheck->bind_param("si", $ebusername, $address_verification_codes);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $num_result = $resultCheck->num_rows;
        $stmtCheck->close();

        if ($num_result == 1) {
            // Update address_verified to 1
            $stmtUpdate = $conn->prepare("UPDATE excessusers SET address_verified=1 WHERE ebusername=? AND address_verification_codes=?");
            $stmtUpdate->bind_param("si", $ebusername, $address_verification_codes);
            $resultUpdate = $stmtUpdate->execute();
            $stmtUpdate->close();

            if ($resultUpdate) {
                echo $this->ebDone();
            }
        } else {
            echo "<b>Invalid or already verified address code.</b>";
        }

    }
}
/*** ***/ 
public function edit_view_user_mobile_to_verify($ebusername)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT ebusername, mobile, mobileactive FROM excessusers WHERE ebusername=? AND member_level != 13");
    $stmt->bind_param("s", $ebusername);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_result = $result->num_rows;

    if ($num_result == 1) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    } else {
        $stmt->close();
        return []; // Return empty array if user not found
    }
}
/*** ***/ 
public function submit_user_mobile_to_verify($ebusername)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare statement to check if user exists and mobile is not verified
    $stmtCheck = $conn->prepare("SELECT ebusername, mobileactive FROM excessusers WHERE ebusername=? AND mobileactive=0");
    $stmtCheck->bind_param("s", $ebusername);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $num_result = $result->num_rows;

    if ($num_result == 1) {
        // Update mobileactive to 1
        $stmtUpdate = $conn->prepare("UPDATE excessusers SET mobileactive=1 WHERE ebusername=?");
        $stmtUpdate->bind_param("s", $ebusername);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        echo $this->ebDone();
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo hostingAndRoot."/out/access/access-all-account-information.php"; ?>');
        }, 3000);
        </script>
        <?php
    }

    $stmtCheck->close();
}
/*** ***/
public function varify_mobile()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $mobileUser = $_SESSION["ebusername"];

    // Prepare statement to get mobile and verification code
    $stmt = $conn->prepare("SELECT mobile, mobile_verification_codes FROM excessusers WHERE ebusername = ?");
    $stmt->bind_param("s", $mobileUser);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_result = $result->num_rows;

    if ($num_result == 1) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}
/*** ***/
public function varify_email_re_sent($usernameemail_filtered)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare and execute statement safely
    $stmt = $conn->prepare("SELECT ebusername, email, emailhash FROM excessusers WHERE ebusername=? OR email=?");
    $stmt->bind_param("ss", $usernameemail_filtered, $usernameemail_filtered);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_result = $result->num_rows;

    if ($num_result === 0) {
        echo "<b>Sorry this eMail or Username does not exist!</b>";
        $stmt->close();
        return;
    }

    if ($num_result === 1) {
        $userinfo = $result->fetch_assoc();
        $userusername = $userinfo['ebusername'];
        $emailhash = $userinfo['emailhash'];
        $email = $userinfo['email'];

        /*** send email verification link ***/ 
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = smtpUsername;
            $mail->Password = smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = smtpPort;

            $mail->setFrom(adminEmail, domain);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "eMail verification for your account";
            //
            $message  ="<html>";
            $message .="<head>";
            $message .="<meta charset='utf-8'>";
            $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
            $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            $message .="<style type='text/css'>
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{-ms-interpolation-mode: bicubic;}
            /* RESET STYLES */
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors]
            {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            }
            /* MOBILE STYLES */
            @media screen and (max-width: 525px)
            {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper
            {
            width: 100% !important;
            max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img
            {
            margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide 
            {
            display: none !important;
            }
            .img-max 
            {

            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table
            {
            width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding
            {
            padding: 6px 3% 9px 3% !important;
            }
            .padding-meta
            {
            padding: 9px 3% 0px 3% !important;
            text-align: center;
            }
            .padding-copy
            {
            padding: 9px 3% 9px 3% !important;
            text-align: center;
            }
            .no-padding
            {
            padding: 0 !important;
            }
            .section-padding
            {
            padding: 9px 9px 9px 9px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container
            {
            margin: 0 auto;
            width: 100% !important;
            }
            .mobile-button
            {
            padding: 9px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
            }
            }
            /* ANDROID CENTER FIX */
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
            </style>";
            $message .="</head>";
            $message .="<body>";
            $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
            //
            $message .="<tr>";
            $message .="<td>eMail verification for your account.</td>";
            $message .="</tr>";
            //
            $message .="<tr>";
            $message .="<td>Please follow the instructions below.</td>";
            $message .="</tr>";
            //
            $message .="<tr>";
            $message .="<td>Username: $userusername</td>";
            $message .="</tr>";
            //
            $message .="<tr>";
            $message .="<td>Please Login and Activated your account.</td>";
            $message .="</tr>";
            //
            $message .="<tr bgcolor='#014693'>";
            $message .="<td>";
            $message .="<a href='";
            $message .=hostingAndRoot."/out/access/access-verify.php?email=$email&emailhash=$emailhash";
            $message .="' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>Active your Account</a>";
            $message .="</td>";
            $message .="</tr>";
            $message .="</table>";
            $message .="</body>";
            $message .="</html>";
            //
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            // Optionally log or display error
            // echo "Mailer Error: {$mail->ErrorInfo}";
        }

        echo "<b>An eMail verification has been sent.</b>";
    }

    $stmt->close();
}
/*** ***/ 
public function unsubscribe($email, $emailhash)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare and execute SELECT statement safely
    $stmt = $conn->prepare("SELECT email, emailhash FROM excessusers WHERE email=? AND emailhash=?");
    $stmt->bind_param("ss", $email, $emailhash);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_result = $result->num_rows;

    if ($num_result === 0) {
        include(ebaccess . '/access-unsubscribe-sorry.php');
    } elseif ($num_result === 1) {
        // Prepare and execute UPDATE statement safely
        $updateStmt = $conn->prepare("UPDATE excessusers SET account_type='unsubscribe' WHERE email=? AND emailhash=?");
        $updateStmt->bind_param("ss", $email, $emailhash);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            include(ebaccess . '/access-unsubscribe.php');
        } else {
            // Optional: handle case where update failed
            echo "<b>Unable to unsubscribe at this moment. Please try again later.</b>";
        }

        $updateStmt->close();
    }

    $stmt->close();
}
/*** ***/ 
public function varify_email($email, $emailhash)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $usernameVerify = isset($_SESSION['ebusername']) ? $_SESSION['ebusername'] : "";

    $queryCheck = "SELECT email, emailhash 
                   FROM excessusers 
                   WHERE ebusername = ? AND email = ? AND emailhash = ? AND active = 0";

    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("sss", $usernameVerify, $email, $emailhash);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $num_result = $resultCheck->num_rows;
    $stmtCheck->close();

    if ($num_result === 0) {
        include(ebaccess . '/access-verification-error.php');
        return;
    }

    $queryUpdate = "UPDATE excessusers 
                    SET active = 1 
                    WHERE email = ? AND emailhash = ?";

    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("ss", $email, $emailhash);

    if ($stmtUpdate->execute()) {
        $_SESSION['activeEmail'] = 1;
        include(ebaccess . '/access-registration-done.php');
    }

    $stmtUpdate->close();
}
/*** ***/ 
public function changepassword($ebconfirmpasswordTow)
{
    if (!isset($_SESSION['ebusername'])) {
        echo $this->ebNotDone();
        return;
    }

    $ebusername = $_SESSION['ebusername'];
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Check if user exists
    $stmt = $conn->prepare("SELECT ebusername FROM excessusers WHERE ebusername=?");
    $stmt->bind_param("s", $ebusername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Update password safely (consider hashing password in production!)
        $updateStmt = $conn->prepare("UPDATE excessusers SET ebpassword=? WHERE ebusername=?");
        $updateStmt->bind_param("ss", $ebconfirmpasswordTow, $ebusername);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            // Destroy session after password change
            session_unset();
            session_destroy();

            echo $this->ebDone();
            ?>
            <script>
            setTimeout(function(){
                window.location.replace('<?php echo hostingAndRoot."/index.php"; ?>');
            }, 3000);
            </script>
            <?php
        } else {
            echo $this->ebNotDone();
        }

        $updateStmt->close();
    } else {
        echo $this->ebNotDone();
    }

    $stmt->close();
}

/*** ***/ 
public function update_account_info_read()
{
    if (!isset($_SESSION['ebusername'])) {
        return []; // No user session found
    }

    $updateaccountfor = $_SESSION['ebusername'];
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM excessusers WHERE ebusername = ?");
    $stmt->bind_param("s", $updateaccountfor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
    }

    $result->free();
    $stmt->close();

    return $this->eBData ?? [];
}
/*** ***/ 
public function update_account_for_free_pos($ebusername)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Check if the user exists
    $stmtCheck = $conn->prepare("SELECT account_type FROM excessusers WHERE ebusername = ?");
    $stmtCheck->bind_param("s", $ebusername);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows === 1) {
        // Update account_type safely
        $stmtUpdate = $conn->prepare("UPDATE excessusers SET account_type = 'Request for POS' WHERE ebusername = ?");
        $stmtUpdate->bind_param("s", $ebusername);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        /*** Email alert for admin ***/
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = smtpUsername;
            $mail->Password   = smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = smtpPort;

            $mail->setFrom(adminEmail, domain);
            $mail->addAddress(alertToAdmin);
            $mail->isHTML(true);
            $mail->Subject = "$ebusername requested for POS";
            //
            $message  ="<html>";
            $message .="<head>";
            $message .="<meta charset='utf-8'>";
            $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
            $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
            $message .="<style type='text/css'>
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
            table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{-ms-interpolation-mode: bicubic;}
            /* RESET STYLES */
            img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
            table{border-collapse: collapse !important;}
            body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
            /* iOS BLUE LINKS */
            a[x-apple-data-detectors]
            {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            }
            /* MOBILE STYLES */
            @media screen and (max-width: 525px)
            {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper
            {
            width: 100% !important;
            max-width: 100% !important;
            }
            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img
            {
            margin: 0 auto !important;
            }
            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide 
            {
            display: none !important;
            }
            .img-max 
            {
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
            }
            /* FULL-WIDTH TABLES */
            .responsive-table
            {
            width: 100% !important;
            }
            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding
            {
            padding: 6px 3% 9px 3% !important;
            }
            .padding-meta
            {
            padding: 9px 3% 0px 3% !important;
            text-align: center;
            }
            .padding-copy
            {
            padding: 9px 3% 9px 3% !important;
            text-align: center;
            }
            .no-padding
            {
            padding: 0 !important;
            }
            .section-padding
            {
            padding: 9px 9px 9px 9px !important;
            }
            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container
            {
            margin: 0 auto;
            width: 100% !important;
            }
            .mobile-button
            {
            padding: 9px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
            }
            }
            /* ANDROID CENTER FIX */
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
            </style>";
            $message .="</head>";
            $message .="<body>";
            $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
            //
            $message .="<tr>";
            $message .="<td>$ebusername requested for POS</td>";
            $message .="</tr>";
            //
            $message .="</table>";
            $message .="</body>";
            $message .="</html>";
            //
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            // Optional: Log the error $mail->ErrorInfo
        }

        echo $this->ebDone();
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo hostingAndRoot."/out/access/access-update-account-information.php"; ?>');
        }, 3000);
        </script>
        <?php
    }

    $stmtCheck->close();
}
/*** ***/ 
public function update_account_information(
            $email_filtered,
            $full_name_filtered,
            $gender_filtered,
            $mobile_filtered,
            $position_names_filtered,
            $address_line_1_filtered,
            $address_line_2_filtered,
            $city_town_filtered,
            $state_province_region_filtered,
            $postal_code_filtered,
            $paypalid_filtered,
            $bkashid_filtered,
            $facebook_link_filtered,
            $twitter_link_filtered,
            $github_link_filtered,
            $linkedin_link_filtered,
            $pinterest_link_filtered,
            $youtube_link_filtered,
            $instagram_link_filtered
        )
{
$usernameUp = $_SESSION['ebusername'];
$query = "SELECT * FROM  excessusers WHERE ebusername='$usernameUp'";
$testresult = eBConDb::eBgetInstance()->eBgetConection()->query($query);
$num_result_up = $testresult->num_rows;
/*** ***/ 
$userinfo = mysqli_fetch_array($testresult);
$previous_email = $userinfo['email'];
$previous_mobile = intval($userinfo['mobile']);
$previous_address_line_1 = $userinfo['address_line_1'];
/*** ***/ 
if(!empty($email_filtered) and $email_filtered != $previous_email)
{
$generate_email_hash_formate = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$generated_new_email_hash = ''; 
for ($i = 0; $i < 40; $i++)
{
$generated_new_email_hash .= $generate_email_hash_formate[rand(0, strlen($generate_email_hash_formate)-1)];
}
$emailhash = $generated_new_email_hash;
}
/*** ***/
$mobileNew = intval($mobile_filtered); 

if(!empty($mobileNew) and $mobileNew != $previous_mobile)
{
$generate_sms_code_formate = '123456789';
$generated_new_address_verification_codes = ''; 
for ($i = 0; $i < 6; $i++)
{
$generated_new_address_verification_codes .= $generate_sms_code_formate[rand(0, strlen($generate_sms_code_formate)-1)];
}
$generated_sms_code_for_mobile = intval($generated_new_address_verification_codes);
}
/*** ***/
if(!empty($address_line_1_filtered) and $address_line_1_filtered != $previous_address_line_1)
{ 
$generate_code_formate = '1235789';
$generated_new_address_verification_codes = ''; 
for ($i = 0; $i < 8; $i++)
{
$generated_new_address_verification_codes .= $generate_code_formate[rand(0, strlen($generate_code_formate)-1)];
}
$generated_code_for_address = intval($generated_new_address_verification_codes);
}
/** **/
if($num_result_up == 1)
{
if(!empty($full_name_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET full_name = ? 
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $full_name_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
if(!empty($gender_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET gender = ? 
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $gender_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($email_filtered) and $email_filtered!=$previous_email)
{
/*** ***/
$queryToExistingEmailCheck = "SELECT email FROM  excessusers WHERE active=1 AND email ='$email_filtered'";
$queryToExistingEmailCheckResult = eBConDb::eBgetInstance()->eBgetConection()->query($queryToExistingEmailCheck);
$num_result_email = $queryToExistingEmailCheckResult->num_rows;
/*** ***/
if($num_result_email ==0)
{
$emailactive = 0;
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    email = ?,
    active = ?,
    emailhash =?
    WHERE ebusername = ?
");
$stmt->bind_param("siss", $email_filtered, $emailactive, $emailhash, $usernameUp);
$stmt->execute();
$stmt->close();
}
}
/*** ***/ 
if(!empty($mobileNew) and $mobileNew != $previous_mobile)
{
/*** ***/
$queryToExistingMobileCheck = "SELECT mobile FROM  excessusers WHERE mobileactive=1 AND mobile='$mobileNew'";
$queryToExistingMobileCheckResult = eBConDb::eBgetInstance()->eBgetConection()->query($queryToExistingMobileCheck);
$num_result_mobile = $queryToExistingMobileCheckResult->num_rows;
/*** ***/
if($num_result_mobile ==0)
{
$mobileactive = 0;
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    mobile = ?,
    mobile_verification_codes =?,
    mobileactive = ?
    WHERE ebusername = ?
");
$stmt->bind_param("siis", $mobileNew, $generated_sms_code_for_mobile, $mobileactive, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/
}
/*** ***/ 
if(!empty($position_names_filtered) || empty($position_names_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    position_names = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $position_names_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}

/*** ***/ 
if(!empty($paypalid_filtered) || empty($paypalid_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    paypalid = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $paypalid_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}

/*** ***/ 
if(!empty($bkashid_filtered) || empty($bkashid_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    bkashid = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $bkashid_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}

/*** ***/ 
if(!empty($address_line_1_filtered) and $address_line_1_filtered != $previous_address_line_1)
{
$address_verified = 0;
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    address_line_1 = ?,
    address_verification_codes = ?,
    address_verified = ?
    WHERE ebusername = ?
");
$stmt->bind_param("siis", $address_line_1_filtered, $generated_code_for_address, $address_verified, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($address_line_2_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    address_line_2 = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $address_line_2_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($city_town_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    city_town = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $city_town_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($state_province_region_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    state_province_region = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $state_province_region_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($postal_code_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    postal_code = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $postal_code_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($facebook_link_filtered) || empty($facebook_link_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    facebook_link = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $facebook_link_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($twitter_link_filtered) || empty($twitter_link_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    twitter_link = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $twitter_link_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}

/*** ***/ 
if(!empty($github_link_filtered) || empty($github_link_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    github_link = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $github_link_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($linkedin_link_filtered) || empty($linkedin_link_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    linkedin_link = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $linkedin_link_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($pinterest_link_filtered) || empty($pinterest_link_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    pinterest_link = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $pinterest_link_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($youtube_link_filtered) || empty($youtube_link_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    youtube_link = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $youtube_link_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}
/*** ***/ 
if(!empty($instagram_link_filtered) || empty($instagram_link_filtered))
{
$stmt = eBConDb::eBgetInstance()->eBgetConection()->prepare("
    UPDATE excessusers 
    SET
    instagram_link = ?
    WHERE ebusername = ?
");
$stmt->bind_param("ss", $instagram_link_filtered, $usernameUp);
$stmt->execute();
$stmt->close();
}

/*** ***/
echo $this->ebDone();
}
}
/*** ***/ 
public function adminViewReferral($ebusername)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT omrusername FROM excessusers WHERE ebusername = ?");
    $stmt->bind_param("s", $ebusername);
    $stmt->execute();

    $result = $stmt->get_result();
    $num_result = $result->num_rows;

    if ($num_result == 1) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        mysqli_free_result($result);
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return []; // Return empty array if no record found
}
/*** ***/ 
public function pos_user_rool_power($usernameEmailMobile)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare statement with placeholders
    $stmt = $conn->prepare(
        "SELECT ebusername, email, emailhash, active, full_name, mobile, mobileactive, 
                account_type, member_level, position_names, user_ip, 
                address_verification_codes, address_verified, omrusername
         FROM excessusers 
         WHERE ebusername = ? OR email = ? OR mobile = ? 
         ORDER BY userid DESC"
    );

    // Bind parameters (s = string)
    $stmt->bind_param("sss", $usernameEmailMobile, $usernameEmailMobile, $usernameEmailMobile);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        mysqli_free_result($result);
    }

    $stmt->close();

    return $this->eBData ?? [];
}
/*** ***/ 
public function search_all_user_read($usernameEmailMobile_filtered)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $usernameEmailMobile = strval($usernameEmailMobile_filtered);

    $query = "SELECT 
                ebusername, 
                email, 
                emailhash, 
                active, 
                full_name, 
                mobile, 
                mobileactive, 
                account_type, 
                member_level, 
                position_names, 
                user_ip, 
                country, 
                address_verification_codes, 
                address_verified, 
                omrusername 
              FROM excessusers 
              WHERE ebusername = ? OR email = ? OR mobile = ? 
              ORDER BY userid DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $usernameEmailMobile, $usernameEmailMobile, $usernameEmailMobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }
        $result->free();
    }

    $stmt->close();

    return $this->eBData ?? [];
}

/*** ***/ 
public function all_user_account_info_read()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare the query
    $stmt = $conn->prepare(
        "SELECT ebusername, email, emailhash, active, full_name, mobile, mobileactive, 
                account_type, member_level, position_names, user_ip, country, 
                address_verification_codes, address_verified, omrusername 
         FROM excessusers 
         ORDER BY userid DESC"
    );

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        mysqli_free_result($result);
    }

    $stmt->close();

    return $this->eBData ?? [];
}
/*** ***/ 
public function edit_view_user_power($ebusername)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare statement
    $stmt = $conn->prepare(
        "SELECT ebusername, email, member_level, position_names 
         FROM excessusers 
         WHERE ebusername = ? AND member_level != 13"
    );

    // Bind parameter
    $stmt->bind_param("s", $ebusername);

    $stmt->execute();
    $result = $stmt->get_result();
    $num_result = $result->num_rows;

    if ($num_result == 1) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        mysqli_free_result($result);
    }

    $stmt->close();

    return $this->eBData ?? [];
}

/*** ***/ 
public function submit_user_power($email, $ebusername, $userpower_level_names, $userpower_level_power, $userpower_position)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $account_type = $userpower_level_names;
    $member_level = intval($userpower_level_power);
    $position_names = $userpower_position;

    // First check if the user exists
    $query = "SELECT ebusername, account_type, member_level, position_names 
              FROM excessusers 
              WHERE ebusername = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ebusername);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_result = $result->num_rows;

    if ($num_result === 1) {
        $userinfo = $result->fetch_array(MYSQLI_ASSOC);

        // Update account_type
        if (!empty($account_type)) {
            $update = "UPDATE excessusers SET account_type=? WHERE ebusername=?";
            $stmtUpdate = $conn->prepare($update);
            $stmtUpdate->bind_param("ss", $account_type, $ebusername);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        // Update member_level
        if (isset($member_level)) {
            $update = "UPDATE excessusers SET member_level=? WHERE ebusername=?";
            $stmtUpdate = $conn->prepare($update);
            $stmtUpdate->bind_param("is", $member_level, $ebusername);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        // Update position_names
        if (!empty($position_names)) {
            $update = "UPDATE excessusers SET position_names=? WHERE ebusername=?";
            $stmtUpdate = $conn->prepare($update);
            $stmtUpdate->bind_param("ss", $position_names, $ebusername);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        /*** ===================== eMail Notifications ===================== ***/

        /* POS Merchant */
        if ($member_level == 4) {
            $mailFreePOS = new PHPMailer(true);
            try {
                $mailFreePOS->isSMTP();
                $mailFreePOS->Host = smtpHost;
                $mailFreePOS->SMTPAuth   = true;
                $mailFreePOS->Username   = smtpUsername;
                $mailFreePOS->Password   = smtpPassword;
                $mailFreePOS->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mailFreePOS->Port = smtpPort;

                $mailFreePOS->setFrom(adminEmail, domain);
                $mailFreePOS->addAddress($email);
                $mailFreePOS->isHTML(true);
                $mailFreePOS->Subject = "Your POS Account Approved. Create your Shop.";

                $message  ="<html>";
                $message .="<head>";
                $message .="<meta charset='utf-8'>";
                $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
                $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
                $message .="<style type='text/css'>
                /* CLIENT-SPECIFIC STYLES */
                body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
                table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
                img{-ms-interpolation-mode: bicubic;}
                /* RESET STYLES */
                img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
                table{border-collapse: collapse !important;}
                body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
                /* iOS BLUE LINKS */
                a[x-apple-data-detectors]
                {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                }
                /* MOBILE STYLES */
                @media screen and (max-width: 525px)
                {
                /* ALLOWS FOR FLUID TABLES */
                .wrapper
                {
                width: 100% !important;
                max-width: 100% !important;
                }
                /* ADJUSTS LAYOUT OF LOGO IMAGE */
                .logo img
                {
                margin: 0 auto !important;
                }
                /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
                .mobile-hide 
                {
                display: none !important;
                }
                .img-max 
                {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
                }
                /* FULL-WIDTH TABLES */
                .responsive-table
                {
                width: 100% !important;
                }
                /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
                .padding
                {
                padding: 6px 3% 9px 3% !important;
                }
                .padding-meta
                {
                padding: 9px 3% 0px 3% !important;
                text-align: center;
                }
                .padding-copy
                {
                padding: 9px 3% 9px 3% !important;
                text-align: center;
                }
                .no-padding
                {
                padding: 0 !important;
                }
                .section-padding
                {
                padding: 9px 9px 9px 9px !important;
                }
                /* ADJUST BUTTONS ON MOBILE */
                .mobile-button-container
                {
                margin: 0 auto;
                width: 100% !important;
                }
                .mobile-button
                {
                padding: 9px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
                }
                }
                /* ANDROID CENTER FIX */
                div[style*='margin: 16px 0;'] { margin: 0 !important; }
                </style>";
                $message .="</head>";
                $message .="<body>";
                $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
                //
                $message .="<tr>";
                $message .="<td>Configure Your Shop to use POS</td>";
                $message .="</tr>";
                //
                $message .="</table>";
                $message .="</body>";
                $message .="</html>";

                $mailFreePOS->Body = $message;
                $mailFreePOS->send();
            } catch (Exception $e) {
                // log error instead of echo
            }
        }

        /* Administrative (8–10) */
        if ($member_level >= 8 && $member_level <= 10) {
            $mailPlusPOS = new PHPMailer(true);
            try {
                $mailPlusPOS->isSMTP();
                $mailPlusPOS->Host = smtpHost;
                $mailPlusPOS->SMTPAuth   = true;
                $mailPlusPOS->Username   = smtpUsername;
                $mailPlusPOS->Password   = smtpPassword;
                $mailPlusPOS->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mailPlusPOS->Port = smtpPort;

                $mailPlusPOS->setFrom(adminEmail, domain);
                $mailPlusPOS->addAddress($email);
                $mailPlusPOS->isHTML(true);
                $mailPlusPOS->Subject = "Your Account Approved";

                $message  ="<html>";
                $message .="<head>";
                $message .="<meta charset='utf-8'>";
                $message .="<meta name='viewport' content='width=device-width, initial-scale=1'>";
                $message .="<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
                $message .="<style type='text/css'>
                /* CLIENT-SPECIFIC STYLES */
                body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
                table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
                img{-ms-interpolation-mode: bicubic;}
                /* RESET STYLES */
                img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
                table{border-collapse: collapse !important;}
                body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
                /* iOS BLUE LINKS */
                a[x-apple-data-detectors]
                {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                }
                /* MOBILE STYLES */
                @media screen and (max-width: 525px)
                {
                /* ALLOWS FOR FLUID TABLES */
                .wrapper
                {
                width: 100% !important;
                max-width: 100% !important;
                }
                /* ADJUSTS LAYOUT OF LOGO IMAGE */
                .logo img
                {
                margin: 0 auto !important;
                }
                /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
                .mobile-hide 
                {
                display: none !important;
                }
                .img-max 
                {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
                }
                /* FULL-WIDTH TABLES */
                .responsive-table
                {
                width: 100% !important;
                }
                /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
                .padding
                {
                padding: 6px 3% 9px 3% !important;
                }
                .padding-meta
                {
                padding: 9px 3% 0px 3% !important;
                text-align: center;
                }
                .padding-copy
                {
                padding: 9px 3% 9px 3% !important;
                text-align: center;
                }
                .no-padding
                {
                padding: 0 !important;
                }
                .section-padding
                {
                padding: 9px 9px 9px 9px !important;
                }
                /* ADJUST BUTTONS ON MOBILE */
                .mobile-button-container
                {
                margin: 0 auto;
                width: 100% !important;
                }
                .mobile-button
                {
                padding: 9px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
                }
                }
                /* ANDROID CENTER FIX */
                div[style*='margin: 16px 0;'] { margin: 0 !important; }
                </style>";
                $message .="</head>";
                $message .="<body>";
                $message .="<table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
                //
                $message .="<tr>";
                $message .="<td>Please check</td>";
                $message .="</tr>";
                //
                $message .="</table>";
                $message .="</body>";
                $message .="</html>";

                $mailPlusPOS->Body = $message;
                $mailPlusPOS->send();
            } catch (Exception $e) {
                // log error instead of echo
            }
        }

        echo $this->ebDone();
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('<?php echo hostingAndRoot."/out/access/access-all-account-information.php"; ?>');
        }, 3000);
        setTimeout();
        </script>
        <?php
    }

    $stmt->close();
}
/*** ***/ 
public function site_owner_social_info()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT facebook_link, twitter_link, github_link, linkedin_link, 
                     pinterest_link, youtube_link, instagram_link 
              FROM excessusers 
              WHERE account_type = ? AND member_level = ?";

    $stmt = $conn->prepare($query);
    $accountType = "Executive Leadership";
    $memberLevel = 13;
    $stmt->bind_param("si", $accountType, $memberLevel);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}

/*** ***/ 
public function site_owner_title()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Step 1: Get admin username
    $queryOne = "SELECT ebusername FROM excessusers WHERE member_level = ?";
    $stmtOne = $conn->prepare($queryOne);
    $memberLevel = 13;
    $stmtOne->bind_param("i", $memberLevel);
    $stmtOne->execute();
    $resultOne = $stmtOne->get_result();

    if ($resultOne && $resultOne->num_rows === 1) {
        $rowOne = $resultOne->fetch_assoc();
        $adminusername = $rowOne['ebusername'];
        $stmtOne->close();

        // Step 2: Get business titles using the username
        $queryTwo = "SELECT business_title_one, business_title_two 
                     FROM excess_merchant_business_details 
                     WHERE business_username = ?";
        $stmtTwo = $conn->prepare($queryTwo);
        $stmtTwo->bind_param("s", $adminusername);
        $stmtTwo->execute();
        $resultTwo = $stmtTwo->get_result();

        if ($resultTwo && $resultTwo->num_rows === 1) {
            while ($rows = $resultTwo->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmtTwo->close();
            return $this->eBData;
        }
        $stmtTwo->close();
    }
    return null;
}

/*** ***/ 
public function content_profile_pic($username_contents)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT full_name, profile_picture_link 
              FROM excessusers 
              WHERE ebusername = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username_contents);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}

/*** ***/ 
public function our_team_member_administration_minimum()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT full_name, position_names, facebook_link, twitter_link, 
                     github_link, linkedin_link, pinterest_link, youtube_link, 
                     instagram_link, profile_picture_link 
              FROM excessusers 
              WHERE member_level >= ?";

    $stmt = $conn->prepare($query);
    $minLevel = 9;
    $stmt->bind_param("i", $minLevel);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return [];
}

/*** ***/ 
public function site_location()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT business_name, business_city_town 
              FROM excess_merchant_business_details 
              ORDER BY business_details_id DESC 
              LIMIT 1";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }
        $stmt->close();
        return $this->eBData;
    }

    $stmt->close();
    return null;
}

}

?>
