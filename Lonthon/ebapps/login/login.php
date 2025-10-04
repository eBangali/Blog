<?php
namespace ebapps\login;

include_once(ebbd.'/dbconfig.php');
use ebapps\dbconnection\dbconfig;

include_once(ebbd.'/eBConDb.php');
use ebapps\dbconnection\eBConDb;

include_once(ebphpmailer.'/Exception.php');
use ebapps\PHPMailer\Exception;

include_once(ebphpmailer.'/PHPMailer.php');
use ebapps\PHPMailer\PHPMailer;

include_once(ebphpmailer.'/SMTP.php');
use ebapps\PHPMailer\SMTP;

class login extends dbconfig
{
    /*** Public properties ***/
    public $eBUsername;
    public $eBPassword;
    public $eBActiveEmail;
    public $eBFullname;
    public $eBActiveMobile;
    public $eBMembertype;
    public $eBMemberlevel;
    public $eBposition;
    public $eBAddressverified;

    /*** Constructor ***/
    public function __construct() {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 0,
                'cookie_secure'   => true,
                'cookie_httponly' => true,
                'cookie_samesite' => 'Strict'
            ]);
        }

        $csrf_token_expiry = 3600; // 1 hour

        if (!isset($_SESSION['csrf_token']) || time() > ($_SESSION['csrf_token_time'] ?? 0) + $csrf_token_expiry) {

            if (isset($_SESSION['ebusername'])) {
                session_regenerate_id(true);
            }

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();

            $cookieDomain = $_SERVER['HTTP_HOST'];
            if (strpos($cookieDomain, ':') !== false) {
                $cookieDomain = explode(':', $cookieDomain)[0];
            }

            setcookie(
                "csrf_token",
                $_SESSION['csrf_token'],
                [
                    "expires"  => time() + $csrf_token_expiry,
                    "path"     => "/",
                    "domain"   => $cookieDomain,
                    "secure"   => isset($_SERVER['HTTPS']),
                    "httponly" => true,
                    "samesite" => "Strict"
                ]
            );
        }
    }

    /*** CSRF Verification ***/
    public function verify_csrf_token($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /*** Login process ***/
    public function login2system($ebusername_filtered, $ebpasswordFiltered)
    {
        $conn = eBConDb::eBgetInstance()->eBgetConection();
        $queryLogin = "SELECT ebusername, ebpassword, active, full_name, mobileactive, 
                              account_type, member_level, position_names, address_verified, omrusername 
                       FROM excessusers 
                       WHERE ebusername = ? AND ebpassword = ? AND member_level >= 1";

        if ($stmt = $conn->prepare($queryLogin)) {
            $stmt->bind_param("ss", $ebusername_filtered, $ebpasswordFiltered);
            $stmt->execute();
            $resultLogin = $stmt->get_result();

            if ($resultLogin->num_rows === 1) {
                $userinfo = $resultLogin->fetch_assoc();

                // Store in session
                $_SESSION['ebusername']      = $userinfo['ebusername'];
                $_SESSION['ebpassword']      = $userinfo['ebpassword'];
                $_SESSION['activeEmail']     = $userinfo['active'];
                $_SESSION['fullname']        = $userinfo['full_name'];
                $_SESSION['activeMobile']    = $userinfo['mobileactive'];
                $_SESSION['membertype']      = $userinfo['account_type'];
                $_SESSION['memberlevel']     = $userinfo['member_level'];
                $_SESSION['memberposition']  = $userinfo['position_names'];
                $_SESSION['addressverified'] = $userinfo['address_verified'];

                // Store in object properties (public)
                $this->eBUsername        = $userinfo['ebusername'];
                $this->eBPassword        = $userinfo['ebpassword'];
                $this->eBActiveEmail     = $userinfo['active'];
                $this->eBFullname        = $userinfo['full_name'];
                $this->eBActiveMobile    = $userinfo['mobileactive'];
                $this->eBMembertype      = $userinfo['account_type'];
                $this->eBMemberlevel     = $userinfo['member_level'];
                $this->eBposition        = $userinfo['position_names'];
                $this->eBAddressverified = $userinfo['address_verified'];

                $stmt->close();

                return [
                    "status"          => true,
                    "requiresEmail"   => ($_SESSION['memberlevel'] >= 1 && $_SESSION['memberlevel'] < 11 && $_SESSION['activeEmail'] == 0),
                    "requiresMobile"  => ($_SESSION['memberlevel'] > 1 && $_SESSION['memberlevel'] < 11 && $_SESSION['activeMobile'] == 0),
                    "requiresAddress" => ($_SESSION['memberlevel'] >= 4 && $_SESSION['memberlevel'] < 11 && $_SESSION['addressverified'] == 0),
                    "user"            => $userinfo
                ];
            }
            $stmt->close();
        }

        return ["status" => false, "error" => "Invalid username or password"];
    }

    /*** Restore object state from session ***/
    private function retriveFromSession()
    {
        $this->eBUsername        = $_SESSION['ebusername'] ?? null;
        $this->eBPassword        = $_SESSION['ebpassword'] ?? null;
        $this->eBActiveEmail     = $_SESSION['activeEmail'] ?? null;
        $this->eBFullname        = $_SESSION['fullname'] ?? null;
        $this->eBActiveMobile    = $_SESSION['activeMobile'] ?? null;
        $this->eBMembertype      = $_SESSION['membertype'] ?? null;
        $this->eBMemberlevel     = $_SESSION['memberlevel'] ?? null;
        $this->eBposition        = $_SESSION['memberposition'] ?? null;
        $this->eBAddressverified = $_SESSION['addressverified'] ?? null;
    }

    /*** Get session values ***/
    public function getsession()
    {
        if (isset($_SESSION['ebusername'])) {
            $this->retriveFromSession();
            return [
                "username"   => $this->eBUsername,
                "fullname"   => $this->eBFullname,
                "membertype" => $this->eBMembertype,
                "level"      => $this->eBMemberlevel
            ];
        }
        return false;
    }

    /*** Forgot password / retrieve account ***/
    public function retrieve($usernameemail)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $stmt = $conn->prepare("SELECT ebusername, ebpassword, email, active, mobile 
                            FROM excessusers 
                            WHERE ebusername=? OR email=?");
    $stmt->bind_param("ss", $usernameemail, $usernameemail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $activeUserinfo = $result->fetch_assoc();

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
            $mail->addAddress($activeUserinfo['email']);
            $mail->isHTML(true);
            $mail->Subject = "Retrieve your username and password";

            $message = "<html>
                        <head>
                        <meta charset='utf-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1'>
                        <meta http-equiv='X-UA-Compatible' content='IE=edge' />
                        <style type='text/css'>/* Your existing styles here */</style>
                        </head>
                        <body>
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>
                            <tr><td>Retrieve your username and password.</td></tr>
                            <tr><td>Please follow the instructions below.</td></tr>
                            <tr><td>Username: ".$activeUserinfo['ebusername']."</td></tr>
                            <tr><td>Temporary Password: ".$activeUserinfo['ebpassword']."</td></tr>
                            <tr><td>This is temporary login page. Please change your password with above username and password</td></tr>
                            <tr bgcolor='#014693'>
                                <td>
                                    <a href='".hostingAndRoot."/out/access/access-change-passsword-temporary.php' target='_blank' 
                                    style='font-size:16px; font-family:Helvetica, Arial, sans-serif; color:#ffffff; 
                                    text-decoration:none; border-radius:3px; padding:9px 9px; 
                                    border:1px solid #014693; display:block;'>
                                    Change your password
                                    </a>
                                </td>
                            </tr>
                        </table>
                        </body>
                        </html>";

            $mail->Body = $message;

            $mail->send();

            // ✅ Show confirmation
            echo "<div class='well'><b>Login information eMail has been sent successfully.</b></div>";

            return ["status" => true, "message" => "Email sent"];
        } catch (Exception $e) {
            //return ["status" => false, "error" => "Mail error: " . $mail->ErrorInfo];
        }
    } else {
        // No user found
        echo "<div class='well'><b>No account found with this username or email.</b></div>";
        
    }
}

}
?>
