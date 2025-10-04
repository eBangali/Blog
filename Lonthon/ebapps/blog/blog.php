<?php
namespace ebapps\blog;
include_once(ebbd.'/dbconfig.php');
use ebapps\dbconnection\dbconfig;
/*** ***/
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
/*** ***/
class blog extends dbconfig
{
/*** ***/
public function __construct()
{
parent::__construct();
/*** ***/
if(!$this->blogTableExists())
{
$this->createBlogTables();
}
/*** ***/
}
/*** ***/
public function getGroupedBlogComments()
{
    $this->eBData = null;
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $alertFor = $_SESSION['ebusername'];

    $query = "
        SELECT 
            c.blogs_comments_id,
            c.blogs_system_id,
            c.blogs_username,
            c.blogs_back_system,
            c.blogs_system_alert_type,
            c.blogs_comment_details,
            c.blogs_comment_date,
            c.blogs_comment_status,
            b.contents_id,
            b.contents_og_image_title
        FROM blog_comments c
        LEFT JOIN blog_contents b
            ON c.blogs_system_id = b.contents_id
        WHERE c.blogs_username = ? AND b.contents_approved = 'OK'
          AND c.blogs_comment_status = 'OK'
          AND c.blogs_back_system = 'BLOG'
        ORDER BY c.blogs_comment_date DESC
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $alertFor);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $this->eBData = [];
            while ($row = $result->fetch_assoc()) {
                $this->eBData[] = $row;
            }
            $result->free();
        }
        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function getBlogPostTitle($contentID)
{
    $this->eBData = null;
    $contentID = strval($contentID);
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT contents_og_image_title 
              FROM blog_contents 
              WHERE contents_approved = 'OK' 
              AND contents_id = ? 
              LIMIT 1";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $contentID);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $this->eBData = $result->fetch_assoc();
            $result->free();
        }
        $stmt->close();
    }
    return $this->eBData;
}


/*** ***/
public function getActiveUsersForOpinionEmail()
{
    $usersData = [];
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT email, emailhash, ebusername, full_name 
              FROM excessusers 
              WHERE active = 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $usersData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $usersData;
}

/*** ***/
public function sendBlogOpinionMassEmail($contentID)
{
    $contentTitle = $this->getBlogPostTitle($contentID);

    if (!$contentTitle || !isset($contentTitle['contents_og_image_title'])) {
        error_log("Blog post title not found for content ID: " . $contentID);
        return false;
    }

    $filteredTitle = $this->metaVisulString($contentTitle['contents_og_image_title']);
    $titleUrl      = $this->seoUrl($contentTitle['contents_og_image_title']);

    $activeUsers = $this->getActiveUsersForOpinionEmail();
    if (empty($activeUsers)) {
        error_log("No active users found for opinion email.");
        return false;
    }

    $mailer = new PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host       = smtpHost;
        $mailer->SMTPAuth   = true;
        $mailer->Username   = smtpUsername;
        $mailer->Password   = smtpPassword;
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port       = smtpPort;
        $mailer->setFrom(adminEmail, domain);
        $mailer->isHTML(true);

        foreach ($activeUsers as $user) {
            if (!empty($user['email'])) {
                $mailer->addBCC($user['email'], $user['full_name'] ?? '');
            }
        }

        $subject        = "Submit your opinion on " . $filteredTitle;
        $mailer->Subject = $subject;

        $emailStyles = $this->generateEmailStyles();
        $message  = "<html><head><title>$subject</title>";
        $message .= "<meta charset='utf-8'>";
        $message .= "<meta name='viewport' content='width=device-width, initial-scale=1'>";
        $message .= "<meta http-equiv='X-UA-Compatible' content='IE=edge' />";
        $message .= $emailStyles;
        $message .= "</head><body>";

        $message .= "<table style='border-collapse:separate!important;border-radius:5px;background-color:#014693' border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>";
        $message .= "<tr><td><p style='font-size:16px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;'>Hi, submit your opinion on $filteredTitle</p></td></tr>";
        $message .= "<tr><td><a href='" . outContentsLinkFull . "/contents/solve/$contentID/$titleUrl/' target='_blank' style='font-size:16px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;'>Submit your opinion</a></td></tr>";
        $message .= "<tr><td><p style='font-size:16px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;'>Thanks.</p></td></tr>";
        $message .= "</table></body></html>";

        $mailer->Body = $message;

        $mailer->send();
        error_log("Mass opinion email sent successfully to " . count($activeUsers) . " users.");
        return true;

    } catch (Exception $e) {
        error_log("PHPMailer Error sending mass opinion email: " . $e->getMessage());
        return false;

    } finally {
        if (isset($mailer)) {
            $mailer->clearAddresses();
            $mailer->clearAttachments();
        }
    }
}

/*** ***/
private function generateEmailStyles()
{
    return "
        <style type='text/css'>
            body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;}
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
            img{-ms-interpolation-mode:bicubic;}
            img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
            table{border-collapse:collapse !important;}
            body{height:100% !important; margin:0 !important; padding:0 !important; width:100% !important;}
            a[x-apple-data-detectors]{color:inherit !important; text-decoration:none !important;}
            @media screen and (max-width:525px) {
                .wrapper{width:100% !important; max-width:100% !important;}
                .logo img{margin:0 auto !important;}
                .mobile-hide{display:none !important;}
                .img-max{max-width:100% !important; width:100% !important; height:auto !important;}
                .responsive-table{width:100% !important;}
                .padding{padding:6px 3% 9px 3% !important;}
                .padding-meta{text-align:center;}
                .padding-copy{text-align:center;}
                .no-padding{padding:0 !important;}
                .section-padding{padding:9px 9px 9px 9px !important;}
                .mobile-button-container{margin:0 auto; width:100% !important;}
                .mobile-button{padding:9px !important; font-size:16px !important;}
            }
        </style>
    ";
}
/*** ***/
public function getCommentedUsersForEmail($contentID)
{
    $usersData = [];
    $contentID = strval($contentID);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "
        SELECT e.email 
        FROM excessusers e
        JOIN blog_comments c ON c.blogs_username = e.ebusername 
        WHERE c.blogs_system_id = ? 
        AND c.blogs_comment_status = 'OK'
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $contentID);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $usersData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }
    return $usersData;
}

/*** ***/
public function sendBlogCommentMassEmail($contentID, $blogs_username)
{
    $contentTitle = $this->getBlogPostTitle($contentID);
    if (!$contentTitle || !isset($contentTitle['contents_og_image_title'])) {
        error_log("Blog post title not found for comment notification content ID: " . $contentID);
        return false;
    }

    $filteredTitle = $this->metaVisulString($contentTitle['contents_og_image_title']);
    $titleUrl      = $this->seoUrl($contentTitle['contents_og_image_title']);

    $commentedUsers = $this->getCommentedUsersForEmail($contentID);
    $recipientEmails = [];

    if (!empty($commentedUsers)) {
        foreach ($commentedUsers as $val) {
            if (!empty($val['email'])) {
                $recipientEmails[] = $val['email'];
            }
        }
        $recipientEmails = array_unique($recipientEmails);
    }

    if (empty($recipientEmails)) {
        error_log("No valid commented users found for blog ID: " . $contentID);
        return false;
    }

    $subject     = "$blogs_username also commented on $filteredTitle";
    $emailStyles = $this->generateEmailStyles();

    $message = "<html>
                    <head>
                        <title>$subject</title>
                        <meta charset='utf-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1'>
                        <meta http-equiv='X-UA-Compatible' content='IE=edge' />
                        $emailStyles
                    </head>
                    <body>
                        <table style='border-collapse:separate!important;border-radius:5px;background-color:#014693' border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>
                            <tr><td style='font-size:16px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;padding:9px;'>Hi,</td></tr>
                            <tr><td style='font-size:16px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;padding:9px;'>
                                $blogs_username also commented on the post: $filteredTitle
                            </td></tr>
                            <tr><td style='font-size:16px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;padding:9px;'>
                                <a href='" . outContentsLinkFull . "/contents/solve/$contentID/$titleUrl/' target='_blank' style='color:#ffffff;'>View the comment</a>
                            </td></tr>
                        </table>
                    </body>
                </html>";

    $massMail = new PHPMailer(true);
    try {
        $massMail->isSMTP();
        $massMail->Host       = smtpHost;
        $massMail->SMTPAuth   = true;
        $massMail->Username   = smtpUsername;
        $massMail->Password   = smtpPassword;
        $massMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $massMail->Port       = smtpPort;
        $massMail->setFrom(adminEmail, domain);
        $massMail->isHTML(true);

        foreach ($recipientEmails as $email) {
            $massMail->addBCC($email);
        }

        $massMail->Subject = $subject;
        $massMail->Body    = $message;

        $massMail->send();
        error_log("Mass email for comment notification sent successfully to " . count($recipientEmails) . " users.");
        return true;

    } catch (Exception $e) {
        error_log("PHPMailer Error sending mass comment notification email: " . $e->getMessage());
        return false;

    } finally {
        if (isset($massMail)) {
            $massMail->clearAddresses();
            $massMail->clearAttachments();
        }
    }
}
/*** ***/
public function getLikedUsersForEmail($contentIdForLike)
{
    $usersData = [];
    $contentIdForLike = intval($contentIdForLike);
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "
        SELECT e.email
        FROM excessusers e
        JOIN blog_like l ON l.blogs_username = e.ebusername
        WHERE l.blogs_id_in_blog_like = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $contentIdForLike);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['email'])) {
                    $usersData[] = $row['email'];
                }
            }
            $result->free();
        }

        $stmt->close();
    }
    return array_unique($usersData);
}


/*** ***/
public function sendBlogLikeMassEmail($likerUsername, $contentIdForLike)
{
    $likedUsers = $this->getLikedUsersForEmail($contentIdForLike);
    
    if(empty($likedUsers)){
        error_log("No users found who liked content ID: " . $contentIdForLike);
        return;
    }
    
    $recipientEmails = [];
    foreach($likedUsers as $val) {
        $recipientEmails[] = $val['email'];
    }
    
    $contentTitle = $this->getBlogPostTitle($contentIdForLike);
    if (!$contentTitle || !isset($contentTitle['contents_og_image_title'])) {
        error_log("Blog post title not found for like notification content ID: " . $contentIdForLike);
        return '';
    }
    $filteredTitle = $this->metaVisulString($contentTitle['contents_og_image_title']);
    $titleUrl = $this->seoUrl($contentTitle['contents_og_image_title']);
    
    $subject = "$likerUsername also liked the post: $filteredTitle";
    
    $emailStyles = $this->generateEmailStyles();

    $message = "<html>
        <head>
            <title>$subject</title>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge' />
            $emailStyles
        </head>
        <body>
            <table style='border-collapse:separate!important;border-radius:5px;background-color:#014693' border='0' cellpadding='0' cellspacing='0' width='100%' class='wrapper'>
                <tr style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>
                    <td>Hi, $likerUsername also liked this post $filteredTitle.</td>
                </tr>
                <tr style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>
                    <td>
                        <a href='".outContentsLinkFull."/contents/solve/$contentIdForLike/$titleUrl/' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; border-radius: 3px; padding: 9px 9px; border: 1px solid #014693; display: block;'>View the post</a>
                    </td>
                </tr>
            </table>
        </body>
    </html>";
    
    $massMail = new PHPMailer(true);
    try {
        $massMail->isSMTP();
        $massMail->Host = smtpHost;
        $massMail->SMTPAuth = true;
        $massMail->Username = smtpUsername;
        $massMail->Password = smtpPassword;
        $massMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $massMail->Port = smtpPort;

        $massMail->setFrom(adminEmail, domain);
        
        foreach($recipientEmails as $email) {
            $massMail->addBCC($email);
        }

        $massMail->isHTML(true);
        $massMail->Subject = $subject;
        $massMail->Body = $message;

        $massMail->send();
        error_log("Mass email for like notification sent successfully to " . count($recipientEmails) . " users.");
    } catch (Exception $e) {
        error_log("PHPMailer Error sending mass like notification email: " . $e->getMessage());
    } finally {

    }
}
/*** ***/
public function content_upload_video()
{
    if (isset($_REQUEST['upload_bideo_blog']) && isset($_REQUEST['contents_id'])) {
        $contents_id = intval($_REQUEST['contents_id']);

        $conn = eBConDb::eBgetInstance()->eBgetConection();
        $query = "SELECT * FROM blog_contents WHERE contents_id = ? LIMIT 1";

        $this->eBData = [];

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $contents_id);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $this->eBData[] = $row;
                }
                $result->free();
            }

            $stmt->close();
        }

        return $this->eBData;
    }

    return [];
}

/*** ***/
public function eidt_submit_sub_category_update($contents_category_old, $contents_sub_category_old, $contents_sub_category_new)
{
    $contents_category_old = (string)$contents_category_old;
    $contents_sub_category_old = (string)$contents_sub_category_old;
    $contents_sub_category_new = (string)$contents_sub_category_new;

    $conn = eBConDb::eBgetInstance()->eBgetConection();

    try {
        $db->begin_transaction();

        $stmt = $conn->prepare("SELECT COUNT(*) FROM blog_sub_category WHERE contents_sub_category=? AND contents_category_in_blog_sub_category=?");
        $stmt->bind_param("ss", $contents_sub_category_old, $contents_category_old);
        $stmt->execute();
        $stmt->bind_result($num_result);
        $stmt->fetch();
        $stmt->close();

        if ($num_result != 1) {
            throw new Exception("Old sub-category not found.");
        }

        $stmt = $conn->prepare("SELECT COUNT(*) FROM blog_sub_category WHERE contents_sub_category=? AND contents_category_in_blog_sub_category=?");
        $stmt->bind_param("ss", $contents_sub_category_new, $contents_category_old);
        $stmt->execute();
        $stmt->bind_result($num_result_new);
        $stmt->fetch();
        $stmt->close();

        if ($num_result_new > 0) {
            throw new Exception("New sub-category already exists.");
        }

        $stmt = $conn->prepare("UPDATE blog_sub_category SET contents_sub_category=? WHERE contents_sub_category=? AND contents_category_in_blog_sub_category=?");
        $stmt->bind_param("sss", $contents_sub_category_new, $contents_sub_category_old, $contents_category_old);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE blog_contents SET contents_sub_category=? WHERE contents_category=? AND contents_sub_category=?");
        $stmt->bind_param("sss", $contents_sub_category_new, $contents_category_old, $contents_sub_category_old);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        echo "<pre>Done " . $this->visulString($contents_sub_category_new) . "</pre>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('contents-add-sub-category.php');
        }, 3000);
        </script>
        <?php

    } catch (Exception $e) {

        $conn->rollback();

        echo "<pre>Sorry not Done " . $this->visulString($contents_sub_category_new) . "</pre>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('contents-add-sub-category.php');
        }, 3000);
        </script>
        <?php
    }
}

/*** ***/
public function eidt_category_to_show_by_name()
{
    if (isset($_REQUEST['contents_sub_category_eidt']) 
        && isset($_GET['contents_sub_category_old']) 
        && isset($_GET['contents_category_in_blog_sub_category_old'])) 
    {
        $contents_sub_category_old = trim(strval($_GET['contents_sub_category_old']));
        $contents_category_in_blog_sub_category_old = trim(strval($_GET['contents_category_in_blog_sub_category_old']));

        $conn = eBConDb::eBgetInstance()->eBgetConection();
        $query = "SELECT * 
                  FROM blog_sub_category 
                  WHERE contents_sub_category = ? 
                  AND contents_category_in_blog_sub_category = ?";

        $this->eBData = [];

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ss", $contents_sub_category_old, $contents_category_in_blog_sub_category_old);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $this->eBData[] = $row;
                }
                $result->free();
            }

            $stmt->close();
        }

        return $this->eBData;
    }

    return [];
}


/*** ***/
public function select_sub_category_to_show_all()
{
    $this->eBData = [];

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_sub_category ORDER BY contents_category_in_blog_sub_category ASC, contents_sub_category ASC";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function eidt_submit_category_blog($contents_category_old, $contents_category)
{
    // Force to string
    $contents_category_old = (string)$contents_category_old;
    $contents_category = (string)$contents_category;

    // Get one DB connection
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    try {
        // Start transaction
        $conn->begin_transaction();

        // 1. Check if old category exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM blog_category WHERE contents_category=?");
        $stmt->bind_param("s", $contents_category_old);
        $stmt->execute();
        $stmt->bind_result($num_result);
        $stmt->fetch();
        $stmt->close();

        if ($num_result != 1) {
            throw new Exception("Old category not found.");
        }

        // 2. Update category name
        $stmt = $conn->prepare("UPDATE blog_category SET contents_category=? WHERE contents_category=?");
        $stmt->bind_param("ss", $contents_category, $contents_category_old);
        $stmt->execute();
        $stmt->close();

        // 3. Update all sub-categories linked to this category
        $stmt = $conn->prepare("UPDATE blog_sub_category SET contents_category_in_blog_sub_category=? WHERE contents_category_in_blog_sub_category=?");
        $stmt->bind_param("ss", $contents_category, $contents_category_old);
        $stmt->execute();
        $stmt->close();

        // 4. Update all blog contents linked to this category
        $stmt = $conn->prepare("UPDATE blog_contents SET contents_category=? WHERE contents_category=?");
        $stmt->bind_param("ss", $contents_category, $contents_category_old);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo $this->ebDone();
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('contents-add-category.php');
        }, 3000);
        </script>
        <?php

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();

        echo "<pre>Sorry not Done " . $this->visulString($contents_category) . "</pre>";
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('contents-add-category.php');
        }, 3000);
        </script>
        <?php
    }
}

/*** ***/
public function eidt_category_show_by_name()
{
    if (isset($_REQUEST['edit_contents_category']) && isset($_GET['contents_category_old'])) {
        $contents_category_old = trim(strval($_GET['contents_category_old']));

        $conn = eBConDb::eBgetInstance()->eBgetConection();
        $query = "SELECT * FROM blog_category WHERE contents_category = ?";

        $this->eBData = [];

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $contents_category_old);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $this->eBData[] = $row;
                }
                $result->free();
            }

            $stmt->close();
        }

        return $this->eBData;
    }

    return [];
}

/*** ***/
public function select_category_to_show_all()
{
    $this->eBData = [];

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_category ORDER BY contents_category ASC";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}


/*** ***/
public function item_details($articleno)
{
    $articleno = strval($articleno); 

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_contents WHERE contents_approved = 'OK' AND contents_id = ?";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function select_contents_sub_category_for_tags($blog_product_or_services_id)
{
    $blog_product_or_services_id = intval($blog_product_or_services_id);

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT contents_sub_category FROM blog_contents WHERE contents_id = ?";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $blog_product_or_services_id);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}


/*** ***/
public function contents_implementation_video_last_for_promotion()
{
    $this->eBData = [];

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT contents_video_link, contents_sub_category 
              FROM blog_contents 
              WHERE contents_approved = 'OK' 
              AND contents_video_link LIKE '%/%' 
              ORDER BY contents_date DESC 
              LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function submit_contents_query_mini_merchant($blogs_system_id, $blogs_comment_details)
{
    // Generate values
    $contents_id        = strval($this->generatedUniqueKey());
    $blogs_system_id    = strval($blogs_system_id);
    $blogs_username     = $_SESSION['ebusername'];
    $blogs_comment_date = date("Y-m-d H:i:s");
    $blogs_back_system  = "BLOG";
    $blogs_alert_type   = "ALERT";
    $blogs_status       = "OK";

    // Get DB connection (mysqli)
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    try {
        // Start transaction
        $conn->autocommit(false);

        // Prepare insert
        $stmt = $conn->prepare("
            INSERT INTO blog_comments 
            (blogs_comments_id, blogs_system_id, blogs_username, blogs_back_system, blogs_system_alert_type, blogs_comment_details, blogs_comment_date, blogs_comment_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Bind values
        $stmt->bind_param(
            "ssssssss",
            $contents_id,
            $blogs_system_id,
            $blogs_username,
            $blogs_back_system,
            $blogs_alert_type,
            $blogs_comment_details,
            $blogs_comment_date,
            $blogs_status
        );

        // Execute insert
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Commit
        $conn->commit();
        $conn->autocommit(true);

        // Send notification email (after DB success)
        $this->sendBlogCommentMassEmail($blogs_system_id, $blogs_username);

        return true;

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        $conn->autocommit(true);

        error_log("DB Insert Failed: " . $e->getMessage());
        return false;
    }
}

public function read_contents_query_to_submit_another_one($articleno)
{
    $items_id = strval($articleno);

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * 
              FROM blog_comments 
              WHERE blogs_system_id = ? 
              ORDER BY blogs_comments_id DESC 
              LIMIT 1";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $items_id);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function count_total_like_ajax($contents_id_for_like)
{
    $blogs_id_in_like = strval($contents_id_for_like); // keep as string

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT COUNT(blogs_id_in_blog_like) AS totalPostLikes 
              FROM blog_like 
              WHERE blogs_id_in_blog_like = ?";

    $totalAjaxLike = 0;

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $blogs_id_in_like); // bind as string
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_array(MYSQLI_ASSOC)) {
            $totalAjaxLike = intval($row['totalPostLikes']);
            $result->free();
        }

        $stmt->close();
    }

    return $totalAfaxLikeNum = $totalAjaxLike;
}

/*** ***/
public function count_total_like($articleno)
{
    $blogs_id_in_like = strval($articleno); // keep as string

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT COUNT(blogs_id_in_blog_like) AS totalPostLikes 
              FROM blog_like 
              WHERE blogs_id_in_blog_like = ?";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $blogs_id_in_like); // bind as string
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function ajax_add_for_like($contents_id_for_like)
{
    $contents_id_for_like = strval($_POST["contents_id_for_like"]); // keep as string
    $usernameLiker = $_SESSION["ebusername"];
    $blogs_like_date = date("Y-m-d H:i:s");
    $blog_like_id = strval($this->generatedUniqueKey());

    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Check if user already liked
    $queryCheck = "SELECT * FROM blog_like WHERE blogs_id_in_blog_like = ? AND blogs_username = ?";
    $num_result = 0;

    if ($stmtCheck = $conn->prepare($queryCheck)) {
        $stmtCheck->bind_param("ss", $contents_id_for_like, $usernameLiker);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        if ($resultCheck) {
            $num_result = $resultCheck->num_rows;
            $resultCheck->free();
        }
        $stmtCheck->close();
    }

    // Insert like if not already liked
    if ($num_result == 0) {
        $queryInsert = "INSERT INTO blog_like (blog_like_id, blogs_id_in_blog_like, blogs_username, blogs_like_date) 
                        VALUES (?, ?, ?, ?)";
        if ($stmtInsert = $conn->prepare($queryInsert)) {
            $stmtInsert->bind_param("ssss", $blog_like_id, $contents_id_for_like, $usernameLiker, $blogs_like_date);
            $stmtInsert->execute();
            $stmtInsert->close();

            // Optional: send email notification if hosting resources allow
            $this->sendBlogLikeMassEmail($usernameLiker, $contents_id_for_like);
        }
    }

    // Get updated like count
    $countLIKE = $this->count_total_like_ajax($contents_id_for_like);

    // Prepare response text
    if ($countLIKE <= 1) {
        $postArticleLike = $countLIKE . " Like";
    } else {
        $postArticleLike = $countLIKE . " Likes";
    }

    $heartData = array('postArticleLike' => $postArticleLike);
    echo json_encode($heartData);

}

/*** ***/
public function add_for_like($contents_id_for_like)
{
    if (isset($_REQUEST['add_for_like'])) {

        $contents_id_for_like = strval($_POST["contents_id_for_like"]); // keep as string
        $usernameLiker = $_SESSION["ebusername"];
        $blogs_like_date = date("Y-m-d H:i:s");

        $conn = eBConDb::eBgetInstance()->eBgetConection();

        // Check if user already liked
        $queryCheck = "SELECT * FROM blog_like WHERE blogs_id_in_blog_like = ? AND blogs_username = ?";
        $num_result = 0;

        if ($stmtCheck = $conn->prepare($queryCheck)) {
            $stmtCheck->bind_param("ss", $contents_id_for_like, $usernameLiker);
            $stmtCheck->execute();

            $resultCheck = $stmtCheck->get_result();
            if ($resultCheck) {
                $num_result = $resultCheck->num_rows;
                $resultCheck->free();
            }

            $stmtCheck->close();
        }

        // Insert like if not already liked
        if ($num_result == 0) {
            $queryInsert = "INSERT INTO blog_like (blogs_id_in_blog_like, blogs_username, blogs_like_date) 
                            VALUES (?, ?, ?)";
            if ($stmtInsert = $conn->prepare($queryInsert)) {
                $stmtInsert->bind_param("sss", $contents_id_for_like, $usernameLiker, $blogs_like_date);
                $stmtInsert->execute();
                $stmtInsert->close();

                // Optional: send email notification
                $this->sendBlogLikeMassEmail($usernameLiker, $contents_id_for_like);
            }
        }

    }
}

/*** ***/
public function count_like_now($articleno)
{
    // Ensure username is a string
    $like_username = isset($_SESSION['ebusername']) ? strval($_SESSION['ebusername']) : '';
    $blogs_id_in_like = strval($articleno); // keep as string

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT COUNT(blogs_id_in_blog_like) AS likeNow 
              FROM blog_like 
              WHERE blogs_id_in_blog_like = ? AND blogs_username = ?";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $blogs_id_in_like, $like_username); // both as strings
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

	
/*** ***/
public function count_total_contents($articleno)
{
    $blogs_system_id = strval($articleno);

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT COUNT(blogs_system_id) AS totalPostComments 
              FROM blog_comments 
              WHERE blogs_system_id = ? AND blogs_comment_status = 'OK' 
              ORDER BY blogs_comments_id DESC";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $blogs_system_id);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }


    return $this->eBData;
}


/*** ***/
public function read_all_contents_query($articleno)
{
    $blogs_system_id = strval($articleno);

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * 
              FROM blog_comments 
              WHERE blogs_system_id = ? 
              ORDER BY blogs_comment_date DESC";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $blogs_system_id);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function edit_update_contents_item(
    $contents_id,
    $username_contents,
    $contents_approved,
    $contents_category,
    $contents_sub_category,
    $contents_og_image_title,
    $contents_og_image_what_to_do_filtered,
    $contents_og_login_required,
    $contents_og_image_how_to_solve_filtered,
    $contents_affiliate_link,
    $contents_github_link,
    $contents_preview_link,
    $contents_video_link
) {
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Force string types for consistency
    $contents_id = (string)$contents_id;
    $contents_approved = (string)$contents_approved;
    $contents_og_image_what_to_do_2nd = (string)$contents_og_image_what_to_do_filtered;
    $contents_og_image_how_to_solve_2nd = (string)$contents_og_image_how_to_solve_filtered;

    try {
        // Start transaction
        $conn->begin_transaction();

        // Check if content exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM blog_contents WHERE contents_id=? AND username_contents=?");
        $stmt->bind_param("ss", $contents_id, $username_contents);
        $stmt->execute();
        $stmt->bind_result($num_result);
        $stmt->fetch();
        $stmt->close();

        if ($num_result !== 1) {
            throw new Exception("Content not found or invalid user.");
        }

        // Memberlevel check
        if (isset($_SESSION['memberlevel']) && $_SESSION['memberlevel'] <= 9) {
            if (empty($contents_video_link)) {
                $stmt = $conn->prepare("UPDATE blog_contents SET contents_approved='GPOST' WHERE contents_id=? AND username_contents=?");
            } else {
                $stmt = $conn->prepare("UPDATE blog_contents SET contents_approved='NO' WHERE contents_id=? AND username_contents=?");
            }
            $stmt->bind_param("ss", $contents_id, $username_contents);
            $stmt->execute();
            $stmt->close();
        }

        // Map of fields to update
        $updates = [
            "contents_category" => $contents_category,
            "contents_sub_category" => $contents_sub_category,
            "contents_og_image_title" => $contents_og_image_title,
            "contents_og_image_what_to_do" => $contents_og_image_what_to_do_2nd,
            "contents_og_login_required" => $contents_og_login_required,
            "contents_og_image_how_to_solve" => $contents_og_image_how_to_solve_2nd,
            "contents_affiliate_link" => $contents_affiliate_link,
            "contents_github_link" => $contents_github_link,
            "contents_preview_link" => $contents_preview_link,
            "contents_video_link" => $contents_video_link,
        ];

        // Update fields (both empty and non-empty values allowed)
        foreach ($updates as $field => $value) {
            if ($value !== null) {
                $stmt = $conn->prepare("UPDATE blog_contents SET $field=? WHERE contents_id=? AND username_contents=?");
                $stmt->bind_param("sss", $value, $contents_id, $username_contents);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Commit transaction
        $conn->commit();

        echo $this->ebDone();
        ?>
        <script>
        setTimeout(function(){
            window.location.replace('contents-items-status.php');
        }, 3000);
        </script>
        <?php

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Error updating content: " . htmlspecialchars($e->getMessage());
    }
}

/*** ***/
public function edit_select_contents_category()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_category";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function edit_select_contents_item()
{
    if (isset($_GET['contents_id'])) { 
        $contents_id = strval($_GET['contents_id']); // keep as string
    } else {
        return []; // return empty if no contents_id provided
    }

    $userOfContent = strval($_SESSION['ebusername']); // keep as string

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_contents 
              WHERE contents_id = ? AND username_contents = ?";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $contents_id, $userOfContent); // bind as strings
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function menu_sub_category_contents($cat)
{
    // Get a single DB connection
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    // Prepare the SQL statement
    $query = "
        SELECT * FROM blog_contents 
        WHERE contents_category = ? 
        AND contents_id IN (
            SELECT MAX(contents_id) 
            FROM blog_contents 
            WHERE contents_approved = 'OK' 
            GROUP BY contents_sub_category
        ) 
        ORDER BY contents_date DESC
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $cat);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function menu_category_contents()
{
    // Get a single database connection
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    // Prepare SQL statement
    $query = "
        SELECT * FROM blog_contents 
        WHERE contents_id IN (
            SELECT MAX(contents_id) 
            FROM blog_contents 
            WHERE contents_approved = 'OK' 
            GROUP BY contents_category
        ) 
        ORDER BY contents_date DESC
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function search_in_contents()
{
    if (isset($_REQUEST['search_contents']) && $_REQUEST['search_contents'] != '') {
        $search_contents = trim($_REQUEST['search_contents']);
        $this->eBData = [];

        // Get the database connection
        $conn = eBConDb::eBgetInstance()->eBgetConection();

        // Prepare SQL statement with LIKE
        $query = "SELECT * FROM blog_contents WHERE contents_approved='OK' AND contents_og_image_title LIKE ? LIMIT 9";

        if ($stmt = $conn->prepare($query)) {
            // Add wildcards for LIKE
            $like_search = "%" . $search_contents . "%";
            $stmt->bind_param("s", $like_search);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result) {
                while ($rows = $result->fetch_assoc()) {
                    $this->eBData[] = $rows;
                }
                $result->free();
            }

            $stmt->close();
        }

        return $this->eBData;
    }
}


public function submit_contents_sub_category($contentCategory, $contentsSub_category)
{
    // Get a single database connection
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    try {
        // Start transaction
        $conn->begin_transaction();

        // Prepare SELECT statement to check if the subcategory already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM blog_sub_category WHERE contents_sub_category = ? AND contents_category_in_blog_sub_category = ?");
        $stmt->bind_param("ss", $contentsSub_category, $contentCategory);
        $stmt->execute();
        $stmt->bind_result($num_result);
        $stmt->fetch();
        $stmt->close();

        if ($num_result == 0) {
            // Prepare INSERT statement
            $insertStmt = $conn->prepare("INSERT INTO blog_sub_category (contents_sub_category, contents_category_in_blog_sub_category) VALUES (?, ?)");
            $insertStmt->bind_param("ss", $contentsSub_category, $contentCategory);
            
            if ($insertStmt->execute()) {
                // Commit transaction
                $conn->commit();
                echo "<pre>Done " . $this->visulString($contentsSub_category) . "</pre>";
            } else {
                // Rollback on failure
                $conn->rollback();
                echo "<pre>Sorry not Done " . $this->visulString($contentsSub_category) . "</pre>";
            }
            $insertStmt->close();
        } else {
            // Subcategory already exists
            $conn->rollback();
            echo "<pre>Sorry not Done " . $this->visulString($contentsSub_category) . "</pre>";
        }

    } catch (Exception $e) {
        // Rollback on any exception
        $conn->rollback();
        echo "<pre>Error: " . $e->getMessage() . "</pre>";
    }
}

public function submit_contents_category($contents_category_filtered)
{
    // Reuse only one connection object
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    try {
        // Begin transaction
        $conn->begin_transaction();

        // Check if category already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM blog_category WHERE contents_category=?");
        $stmt->bind_param("s", $contents_category_filtered);
        $stmt->execute();
        $stmt->bind_result($num_result);
        $stmt->fetch();
        $stmt->close();

        if ($num_result == 0) {
            // Insert new category
            $stmt = $conn->prepare("INSERT INTO blog_category (contents_category) VALUES (?)");
            $stmt->bind_param("s", $contents_category_filtered);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();

            echo "<pre>Done " . $this->visulString($contents_category_filtered) . "</pre>";
        } else {
            // Rollback if category already exists
            $conn->rollback();

            echo "<pre>Sorry not Done " . $this->visulString($contents_category_filtered) . "</pre>";
        }

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "<pre>Error: " . htmlspecialchars($e->getMessage()) . "</pre>";
    }
}

/*** ***/
public function select_contents_category()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_category";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                echo "<option value='" . $row['contents_category'] . "'>" . $this->visulString($row['contents_category']) . "</option>";
            }
            $result->free();
        }

        $stmt->close();
    }

}

/*** ***/
public function submit_new_contents_item(
    $contents_category,
    $contents_sub_category,
    $contents_og_image_title,
    $contents_og_image_what_to_do_filtered,
    $contents_og_login_required,
    $contents_og_image_how_to_solve_filtered,
    $contents_affiliate_link,
    $contents_github_link,
    $contents_preview_link
) 
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $contents_id        = strval($this->generatedUniqueKey());
    $blogs_comments_id  = $contents_id;
    $ebusername         = $_SESSION['ebusername'];
    $contents_date      = date("Y-m-d H:i:s");

    if (isset($_SESSION['memberlevel'])) {
        $contents_approved = ($_SESSION['memberlevel'] <= 8) ? "GPOST" : "NO";

        try {
            // Start transaction
            $conn->begin_transaction();

            // First insert: blog_contents
            $stmt1 = $conn->prepare("
                INSERT INTO blog_contents (
                    contents_id, username_contents, contents_approved,
                    contents_category, contents_sub_category,
                    contents_og_image_url, contents_og_small_image_url,
                    contents_og_image_title, contents_og_image_what_to_do,
                    contents_og_login_required, contents_og_image_how_to_solve,
                    contents_affiliate_link, contents_github_link,
                    contents_preview_link, contents_video_link, contents_date
                ) VALUES (?, ?, ?, ?, ?, '', '', ?, ?, ?, ?, ?, ?, ?, '', ?)
            ");

            $stmt1->bind_param(
                "sssssssssssss",
                $contents_id,
                $ebusername,
                $contents_approved,
                $contents_category,
                $contents_sub_category,
                $contents_og_image_title,
                $contents_og_image_what_to_do_filtered,
                $contents_og_login_required,
                $contents_og_image_how_to_solve_filtered,
                $contents_affiliate_link,
                $contents_github_link,
                $contents_preview_link,
                $contents_date
            );

            if (!$stmt1->execute()) {
                throw new Exception("Error inserting into blog_contents: " . $stmt1->error);
            }
            $stmt1->close();

            // Second insert: blog_comments
            $stmt2 = $conn->prepare("
                INSERT INTO blog_comments (
                    blogs_comments_id, blogs_system_id, blogs_username,
                    blogs_back_system, blogs_system_alert_type,
                    blogs_comment_details, blogs_comment_date, blogs_comment_status
                ) VALUES (?, ?, ?, 'BLOG', 'ALERTALL', 'Any Query?', ?, 'NO')
            ");

            $stmt2->bind_param(
                "ssss",
                $blogs_comments_id,
                $contents_id,
                $ebusername,
                $contents_date
            );

            if (!$stmt2->execute()) {
                throw new Exception("Error inserting into blog_comments: " . $stmt2->error);
            }
            $stmt2->close();

            // Commit both inserts
            $conn->commit();

            echo $this->ebDone();

        } catch (Exception $e) {
            // Rollback if anything fails
            $conn->rollback();
            error_log($e->getMessage()); // log for debugging
            echo "Error: Could not submit new content.";
        }
    }
}

/*** ***/
public function contents_view_items()
{
    $username = strval($_SESSION['ebusername']); // keep as string

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_contents 
              WHERE contents_approved != 'DELETED' AND username_contents = ? 
              ORDER BY contents_date DESC";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $username); // bind as string
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function updates_contents_image_url($contents_id, $contents_og_image_url)
{
    $contents_id = strval($contents_id);
    $contents_og_image_url = strval($contents_og_image_url);

    if (isset($_SESSION['memberlevel'])) {
        $conn = eBConDb::eBgetInstance()->eBgetConection();

        if ($_SESSION['memberlevel'] <= 9) {
            $query = "UPDATE blog_contents 
                      SET contents_approved = 'GPOST', contents_og_image_url = ? 
                      WHERE contents_id = ?";
        } else {
            $query = "UPDATE blog_contents 
                      SET contents_approved = 'NO', contents_og_image_url = ? 
                      WHERE contents_id = ?";
        }

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ss", $contents_og_image_url, $contents_id); // bind as strings
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        }

        if (isset($affectedRows) && $affectedRows > 0) {
            echo $this->ebDone();
            ?>
            <script>
            setTimeout(function(){
                window.location.replace('contents-items-status.php');
            }, 3000);
            </script>
            <?php
        }
    }
}

/*** ***/
public function updates_contents_small_image_url($contents_id, $contents_og_small_image_url)
{
    $contents_id = strval($contents_id);
    $contents_og_small_image_url = strval($contents_og_small_image_url);

    if (isset($_SESSION['memberlevel'])) {
        $conn = eBConDb::eBgetInstance()->eBgetConection();

        if ($_SESSION['memberlevel'] <= 9) {
            $query = "UPDATE blog_contents 
                      SET contents_approved = 'GPOST', contents_og_small_image_url = ? 
                      WHERE contents_id = ?";
        } else {
            $query = "UPDATE blog_contents 
                      SET contents_approved = 'NO', contents_og_small_image_url = ? 
                      WHERE contents_id = ?";
        }

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ss", $contents_og_small_image_url, $contents_id); // bind as strings
            $stmt->execute();
            $stmt->close();
        }

    }
}


/*** ***/
public function select_image_from_contents()
{
    /* Read to Edit */
    if (isset($_REQUEST['upload_image'])) {
        extract($_REQUEST);
        $contents_id = strval($contents_id); // keep as string

        $conn = eBConDb::eBgetInstance()->eBgetConection();
        $query = "SELECT * FROM blog_contents WHERE contents_id = ?";

        $this->eBData = [];

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $contents_id); // bind as string
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $this->eBData[] = $row;
                }
                $result->free();
            }

            $stmt->close();
        }

        return $this->eBData;
    }
}

/*** ***/
public function admin_contents_view_items()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $query = "SELECT * FROM blog_contents WHERE contents_approved = 'NO' ORDER BY contents_date DESC";

    $this->eBData = [];

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->eBData[] = $row;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}


/*** ***/
public function approve_contents_items($contents_id)
{
    $contents_id = strval($contents_id);
    $contents_approved = "OK";

    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Update blog_contents
    $updateContentsQuery = "UPDATE blog_contents SET contents_approved = ? WHERE contents_id = ?";
    if ($stmt = $conn->prepare($updateContentsQuery)) {
        $stmt->bind_param("ss", $contents_approved, $contents_id);
        $stmt->execute();
        $resultAlert = $stmt->affected_rows == 1;
        $stmt->close();
    }

    // Send opinion mass email
    $this->sendBlogOpinionMassEmail($contents_id);

    // Update blog_comments
    $updateCommentsQuery = "UPDATE blog_comments SET blogs_comment_status = 'OK' WHERE blogs_system_id = ?";
    if ($stmt = $conn->prepare($updateCommentsQuery)) {
        $stmt->bind_param("s", $contents_id);
        $stmt->execute();
        $stmt->close();
    }

    if ($resultAlert) {
        echo $this->ebDone();
    }
}

/*** ***/
public function notSercicesApproved($contents_id, $contents_og_image_url, $contents_video_link)
{
    $contents_id = strval($contents_id);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Remove existing image file if it exists
    if (!empty($contents_og_image_url) && file_exists(docRoot . $contents_og_image_url)) {
        unlink(docRoot . $contents_og_image_url);
    }

    // Remove existing video file if it exists
    if (!empty($contents_video_link) && file_exists(docRoot . $contents_video_link)) {
        unlink(docRoot . $contents_video_link);
    }

    // Update blog_contents to mark as NOT approved and clear media links
    $query = "UPDATE blog_contents 
              SET contents_approved='NO', contents_og_image_url='', contents_og_small_image_url='', contents_video_link='' 
              WHERE contents_id = ?";

    $result1 = false;
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $contents_id); // bind as string
        $stmt->execute();
        $result1 = $stmt->affected_rows > 0;
        $stmt->close();
    }


    if ($result1) {
        echo $this->ebDone();
    }
}


/*** ***/
public function notSercicesApproved_small($contents_id, $contents_og_small_image_url)
{
    $contents_id = strval($contents_id); // keep as string for consistency

    if (!empty($contents_og_small_image_url) && file_exists(docRoot . $contents_og_small_image_url)) {
        unlink(docRoot . $contents_og_small_image_url);
    }
}

/*** ***/
public function delete_contents_items($contents_id, $contents_og_image_url, $contents_og_small_image_url, $contents_video_link)
{
    $contents_id = strval($contents_id);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Check if the content exists
    $queryTest = "SELECT * FROM blog_contents WHERE contents_id = ?";
    $num_result = 0;
    if ($stmt = $conn->prepare($queryTest)) {
        $stmt->bind_param("s", $contents_id);
        $stmt->execute();
        $resultTest = $stmt->get_result();
        $num_result = $resultTest->num_rows;
        $stmt->close();
    }

    if ($num_result == 1) {
        // Delete files if they exist
        if (!empty($contents_og_image_url) && file_exists(docRoot . $contents_og_image_url)) {
            unlink(docRoot . $contents_og_image_url);
        }
        if (!empty($contents_og_small_image_url) && file_exists(docRoot . $contents_og_small_image_url)) {
            unlink(docRoot . $contents_og_small_image_url);
        }
        if (!empty($contents_video_link) && file_exists(docRoot . $contents_video_link)) {
            unlink(docRoot . $contents_video_link);
        }

        // Update content to DELETED
        $queryDeleted = "UPDATE blog_contents 
                         SET contents_approved='DELETED', contents_og_image_url='', contents_og_small_image_url='', contents_video_link='' 
                         WHERE contents_id = ?";
        $resultDeleted = false;
        if ($stmt = $conn->prepare($queryDeleted)) {
            $stmt->bind_param("s", $contents_id);
            $stmt->execute();
            $resultDeleted = $stmt->affected_rows > 0;
            $stmt->close();
        }

        if ($resultDeleted) {
            echo $this->ebDone();
        } else {
            echo $this->ebNotDone();
        }
    } else {
        echo $this->ebNotDone();
    }
}

/*** ***/
public function read_contents_items_download_link_to_edit()
{
    /* Read to Edit */
    if (isset($_REQUEST['contents_github_link'])) {
        $contents_id = strval($_REQUEST['contents_id']);
        $conn = eBConDb::eBgetInstance()->eBgetConection();

        $query = "SELECT * FROM blog_contents WHERE contents_id = ? LIMIT 1";
        $this->eBData = [];

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $contents_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($rows = $result->fetch_array()) {
                $this->eBData[] = $rows;
            }

            $stmt->close();
            mysqli_free_result($result);
        }

        return $this->eBData;
    }
}

/*** ***/
public function update_contents_download_link($contents_id, $contents_github_link)
{
    $contents_id = strval($contents_id);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "UPDATE blog_contents SET contents_approved='NO', contents_github_link=? WHERE contents_id=?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $contents_github_link, $contents_id);
        $stmt->execute();
        $stmt->close();
    }

    echo $this->ebDone();
}

/*** ***/
public function read_contents_items_video_link_to_edit()
{
    /* Read to Edit */
    if (isset($_REQUEST['contents_video_link'])) {
        $contents_id = strval($_REQUEST['contents_id']);
        $conn = eBConDb::eBgetInstance()->eBgetConection();

        $query = "SELECT * FROM blog_contents WHERE contents_id = ? LIMIT 1";
        $this->eBData = [];

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $contents_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($rows = $result->fetch_array()) {
                $this->eBData[] = $rows;
            }

            $stmt->close();
            mysqli_free_result($result);
        }

        return $this->eBData;
    }
}

/*** ***/
public function read_contents_items_preview_link_to_edit()
{
    /* Read to Edit */
    if (isset($_REQUEST['contents_preview_link'])) {
        $contents_id = strval($_REQUEST['contents_id']);
        $conn = eBConDb::eBgetInstance()->eBgetConection();

        $query = "SELECT * FROM blog_contents WHERE contents_id = ? LIMIT 1";
        $this->eBData = [];

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $contents_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($rows = $result->fetch_array()) {
                $this->eBData[] = $rows;
            }

            $stmt->close();
            mysqli_free_result($result);
        }

        return $this->eBData;
    }
}

/*** ***/
public function update_contents_video_link($contents_id, $contents_video_link)
{
    $contents_id = strval($contents_id);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "UPDATE blog_contents SET contents_approved='NO', contents_video_link=? WHERE contents_id=?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $contents_video_link, $contents_id);
        $stmt->execute();
        $stmt->close();
    }

    echo $this->ebDone();
}


/*** ***/
public function update_contents_preview_link($contents_id, $contents_preview_link)
{
    $contents_id = strval($contents_id);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "UPDATE blog_contents SET contents_approved='NO', contents_preview_link=? WHERE contents_id=?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $contents_preview_link, $contents_id);
        $stmt->execute();
        $stmt->close();
    }

    echo $this->ebDone();
}

/*** ***/
public function blog_control()
{
//
$this->eb_blog();
}

/*** ***/
private function eb_blog()
{
    /* controlling cart */
    $view = empty($_GET['view']) ? 'index' : $_GET['view'];
    $controller = 'shop';

    /* List of views that require articleno */
    $viewsWithArticleno = ['index', 'category', 'subcategory', 'writer', 'commentsofmy', 'solve'];

    if (in_array($view, $viewsWithArticleno) && isset($_GET['articleno'])) {
        $articleno = $_SESSION['articleno'] = strval($_GET['articleno']);
    }

    include(ebcontents . '/views/layouts/' . $controller . '.php');
}

/*** ***/
public function contents_carousel_all()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "
        SELECT 
            bc.*,
            COUNT(DISTINCT bl.blog_like_id) AS total_likes,
            COUNT(DISTINCT bcom.blogs_comments_id) AS total_comments
        FROM blog_contents bc
        LEFT JOIN blog_like bl 
            ON bc.contents_id = bl.blogs_id_in_blog_like
        LEFT JOIN blog_comments bcom 
            ON bc.contents_id = bcom.blogs_system_id
        WHERE bc.contents_approved = ? 
          AND bc.contents_og_image_url != ''
        GROUP BY bc.contents_id
        ORDER BY total_likes DESC, total_comments DESC
        LIMIT 5
    ";
    
    if ($stmt = $conn->prepare($query)) {
        $approvedStatus = 'OK';
        $stmt->bind_param("s", $approvedStatus);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->eBData = [];
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function contents_video()
{
    if (!isset($_SESSION['memberlevel'])) {
        return;
    }

    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    // Determine approval status based on member level
    $approvalStatus = ($_SESSION['memberlevel'] <= 9) ? 'GPOST' : 'OK';

    // Prepare the query
    $query = "
        SELECT contents_category, contents_sub_category, contents_video_link 
        FROM blog_contents 
        WHERE contents_category IN (
            SELECT contents_category 
            FROM blog_contents 
            WHERE contents_approved = ? 
            GROUP BY contents_category
        ) 
        ORDER BY contents_date DESC 
        LIMIT 4
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $approvalStatus);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}


/*** ***/
public function contentsForeCommerce()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    // Prepare the query
    $query = "SELECT * FROM blog_contents WHERE contents_approved = ? ORDER BY contents_date DESC LIMIT 4";

    if ($stmt = $conn->prepare($query)) {
        $approvalStatus = 'OK';
        $stmt->bind_param("s", $approvalStatus);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/

public function rightBarAllCategory()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    $query = "
        SELECT 
            bc.*,
            COUNT(DISTINCT bl.blog_like_id) AS total_likes,
            COUNT(DISTINCT bcom.blogs_comments_id) AS total_comments
        FROM blog_contents bc
        LEFT JOIN blog_like bl 
            ON bc.contents_id = bl.blogs_id_in_blog_like
        LEFT JOIN blog_comments bcom 
            ON bc.contents_id = bcom.blogs_system_id
        WHERE bc.contents_id IN (
            SELECT MAX(contents_id)
            FROM blog_contents
            WHERE contents_approved = ?
            GROUP BY contents_category
        )
        GROUP BY bc.contents_id
        ORDER BY bc.contents_date DESC, total_likes DESC, total_comments DESC
        LIMIT 4
    ";

    if ($stmt = $conn->prepare($query)) {
        $approvedStatus = 'OK';
        $stmt->bind_param("s", $approvedStatus);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function rightBarAllCategoryPost($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    $query = "
        SELECT * 
        FROM blog_contents 
        WHERE contents_approved = ? AND contents_id != ? 
        ORDER BY contents_date DESC 
        LIMIT 4
    ";

    if ($stmt = $conn->prepare($query)) {
        $approvedStatus = 'OK';
        $stmt->bind_param("ss", $approvedStatus, $articleno);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function rightBarAll()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    $query = "SELECT * FROM blog_contents WHERE contents_approved = ? ORDER BY contents_date DESC LIMIT 4";

    if ($stmt = $conn->prepare($query)) {
        $approvedStatus = 'OK';
        $stmt->bind_param("s", $approvedStatus);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function contentsLikeAll()
{
    $userForBlog = $_SESSION['ebusername'];
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    $queryLike = "
        SELECT * 
        FROM blog_contents
        JOIN blog_like ON blog_contents.contents_id = blog_like.blogs_id_in_blog_like
        WHERE blog_contents.contents_approved = ? AND blog_like.blogs_username = ?
        ORDER BY blog_contents.contents_date DESC
    ";

    if ($stmt = $conn->prepare($queryLike)) {
        $approvedStatus = 'OK';
        $stmt->bind_param("ss", $approvedStatus, $userForBlog);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }
    return $this->eBData;
}

/*** ***/
public function guestContentsLikeAll()
{
    $userForBlog = $_SESSION['ebusername'];
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    $queryLike = "
        SELECT * 
        FROM blog_contents
        JOIN blog_like ON blog_contents.contents_id = blog_like.blogs_id_in_blog_like
        WHERE blog_contents.contents_approved = ? AND blog_like.blogs_username = ?
        ORDER BY blog_contents.contents_date DESC
    ";

    if ($stmt = $conn->prepare($queryLike)) {
        $approvedStatus = 'GPOST';
        $stmt->bind_param("ss", $approvedStatus, $userForBlog);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function postArticleAllScronDownInSolvePage($articleno)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    // Ensure start and limit are integers for safety
    $start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
    $limit = isset($_REQUEST["limit"]) ? intval($_REQUEST["limit"]) : 10;

    $query = "
        SELECT 
            bc.contents_id,
            bc.username_contents,
            bc.contents_approved,
            bc.contents_category,
            bc.contents_sub_category,
            bc.contents_og_image_url,
            bc.contents_og_small_image_url,
            bc.contents_og_image_title,
            bc.contents_og_image_what_to_do,
            bc.contents_og_login_required,
            bc.contents_og_image_how_to_solve,
            bc.contents_affiliate_link,
            bc.contents_github_link,
            bc.contents_preview_link,
            bc.contents_video_link,
            bc.contents_date,
            COUNT(DISTINCT bl.blog_like_id) AS total_likes,
            COUNT(DISTINCT bcom.blogs_comments_id) AS total_comments
        FROM blog_contents bc
        LEFT JOIN blog_like bl ON bc.contents_id = bl.blogs_id_in_blog_like
        LEFT JOIN blog_comments bcom ON bc.contents_id = bcom.blogs_system_id
        WHERE bc.contents_approved = ? AND bc.contents_id != ?
        GROUP BY bc.contents_id
        ORDER BY total_likes DESC, bc.contents_date DESC, total_comments DESC
        LIMIT ?, ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $approvedStatus = 'OK';
        $stmt->bind_param("ssii", $approvedStatus, $articleno, $start, $limit);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function postArticleAllScronDown()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    // Ensure start and limit are integers for safety
    $start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
    $limit = isset($_REQUEST["limit"]) ? intval($_REQUEST["limit"]) : 10;

    $query = "
        SELECT 
            bc.contents_id,
            bc.username_contents,
            bc.contents_approved,
            bc.contents_category,
            bc.contents_sub_category,
            bc.contents_og_image_url,
            bc.contents_og_small_image_url,
            bc.contents_og_image_title,
            bc.contents_og_image_what_to_do,
            bc.contents_og_login_required,
            bc.contents_og_image_how_to_solve,
            bc.contents_affiliate_link,
            bc.contents_github_link,
            bc.contents_preview_link,
            bc.contents_video_link,
            bc.contents_date,
            COUNT(DISTINCT bcom.blogs_comments_id) AS total_comments,
            COUNT(DISTINCT bl.blog_like_id) AS total_likes
        FROM blog_contents bc
        LEFT JOIN blog_like bl ON bc.contents_id = bl.blogs_id_in_blog_like
        LEFT JOIN blog_comments bcom ON bc.contents_id = bcom.blogs_system_id
        WHERE bc.contents_approved = ?
        GROUP BY bc.contents_id
        ORDER BY total_comments DESC, bc.contents_date DESC, total_likes DESC
        LIMIT ?, ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $approvedStatus = 'OK';
        $stmt->bind_param("sii", $approvedStatus, $start, $limit);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}


/** GUEST POST PAGINATION **/
public function guestPostContentsPostAll()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();
    $this->eBData = [];

    $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
    if ($page <= 0) $page = 1;

    $per_page = 10;
    $startpoint = ($page * $per_page) - $per_page;

    $query = "
        SELECT * 
        FROM blog_contents 
        WHERE contents_approved = ? 
        ORDER BY contents_date DESC 
        LIMIT ?, ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $approvedStatus = 'GPOST';
        $stmt->bind_param("sii", $approvedStatus, $startpoint, $per_page);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
    }

    return $this->eBData;
}


/** GUEST POST PAGINATION  **/
public function guestPostContentsPagination()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
    if ($page <= 0) $page = 1;

    $per_page = 10;
    $startpoint = ($page * $per_page) - $per_page;

    $url = '?';

    // Use prepared statement for COUNT(*)
    $query = "SELECT COUNT(*) as num FROM blog_contents WHERE contents_approved = ?";
    $total = 0;

    if ($stmt = $conn->prepare($query)) {
        $approved = 'GPOST';
        $stmt->bind_param("s", $approved);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $total = $row['num'];
        }
        $stmt->close();
    }

    $adjacents = 2;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $lastlabel = "Last &rsaquo;&rsaquo;";

    $lastpage = ceil($total / $per_page);
    $prev = $page - 1;
    $next = $page + 1;
    $lpm1 = $lastpage - 1;

    $pagination = "<div class='pager'><div class='pages'>";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";

        if ($page > 1) {
            $pagination .= "<li><a href='{$url}page={$prev}'>{$prevlabel}</a></li>";
        }

        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<li class='active'><a>{$counter}</a></li>";
                else
                    $pagination .= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";
            }
        } elseif ($lastpage > 1 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";
                }
                $pagination .= "<li class='dot'>...</li>";
                $pagination .= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
                $pagination .= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2'>2</a></li>";
                $pagination .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";
                }
                $pagination .= "<li class='dot'>..</li>";
                $pagination .= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
                $pagination .= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";
            } else {
                $pagination .= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2'>2</a></li>";
                $pagination .= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";
                }
            }
        }

        if ($page < $counter - 1) {
            $pagination .= "<li><a href='{$url}page={$next}'>{$nextlabel}</a></li>";
            $pagination .= "<li><a href='{$url}page=$lastpage'>{$lastlabel}</a></li>";
        }

        $pagination .= "</ul>";
    }
    $pagination .= "</div></div>";

    return $pagination;
}


/*** ***/
public function contents_list_menue()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT contents_id, contents_og_image_title 
              FROM blog_contents 
              WHERE contents_approved = ? 
              ORDER BY contents_date DESC 
              LIMIT 16";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'OK';
        $stmt->bind_param("s", $approved);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();

        return $this->eBData;
    }

    return [];
}

/*** ***/
public function contents_thurmnail_subcategory_gpost($category, $subcategory)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_approved = ? 
              AND contents_category = ? 
              AND contents_sub_category = ? 
              ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'GPOST';
        $stmt->bind_param("sss", $approved, $category, $subcategory);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();

        return $this->eBData;
    }

    return [];
}
/*** ***/
public function contents_thurmnail_subcategory($category, $subcategory)
{
    $this->eBData = [];

    // Get DB connection
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepared SQL statement
    $query = "SELECT * FROM blog_contents 
              WHERE contents_approved = 'OK' 
              AND contents_category = ? 
              AND contents_sub_category = ? 
              ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $category, $subcategory);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function contents_thurmnail_category_gpost($category)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_approved = ? 
              AND contents_category = ? 
              ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'GPOST';
        $stmt->bind_param("ss", $approved, $category);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();

        return $this->eBData;
    }

    return [];
}
/*** ***/
public function contents_thurmnail_category($category)
{
    $this->eBData = [];

    // Get a single database connection
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    // Prepare the SQL statement
    $query = "SELECT * FROM blog_contents WHERE contents_approved='OK' AND contents_category=? ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $category);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $result->free();
        }

        $stmt->close();
    }

    return $this->eBData;
}

/*** ***/
public function contents_thurmnail_group_guest($contentsbloggroup)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_approved = ? 
              AND username_contents = ? 
              ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'GPOST';
        $stmt->bind_param("ss", $approved, $contentsbloggroup);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();

        return $this->eBData;
    }

    return [];
}

/*** ***/
public function contents_thurmnail_group($contentsbloggroup)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_approved = ? 
              AND username_contents = ? 
              ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'OK';
        $stmt->bind_param("ss", $approved, $contentsbloggroup);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
        return $this->eBData;
    }

    return [];
}

/*** ***/
public function itemDetailsContentsGuest($articleno)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_approved = ? 
              AND contents_id = ? 
              LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'GPOST';
        $stmt->bind_param("ss", $approved, $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();

        return $this->eBData;
    }

    return [];
}

/*** ***/
public function itemDetailsContents($articleno)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_approved = ? 
              AND contents_id = ? 
              LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'OK';
        $stmt->bind_param("ss", $approved, $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();

        return $this->eBData;
    }

    return [];
}

/*** ***/
public function item_details_contents_guest($articleno)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_approved = ? 
              AND contents_id = ? 
              LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'GPOST';
        $stmt->bind_param("ss", $approved, $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row;
        }

        $stmt->close();
    }

    return null;
}

/*** ***/
public function item_details_contents($articleno)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE contents_id = ? 
              LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row; // return single row
        }

        $stmt->close();
    }

    return null;
}


/*** ***/
public function item_details_articlewriter($articlewriter)
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * 
              FROM blog_contents 
              WHERE username_contents = ? 
                AND contents_approved = 'OK' 
              LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articlewriter);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row; // return single row
        }

        $stmt->close();
    }

    return null; // return null if nothing found
}

/*** ***/
public function contents_detail_all_part_guest($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_approved = 'GPOST' AND contents_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->eBData = [];
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
        return $this->eBData;
    }

    return [];
}

/*** ***/
public function contents_detail_all_part($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_approved = 'OK' AND contents_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->eBData = [];
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
        return $this->eBData;
    }

    return [];
}

/*** ***/
public function contents_detail_how_to_do_guest($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_approved='GPOST' AND contents_id=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }

    return [];
}

/*** ***/
public function contents_detail_how_to_do($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_approved='OK' AND contents_id=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }

    return [];
}

/*** ***/
public function contents_detail_video_guest($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT contents_video_link FROM blog_contents WHERE contents_approved='GPOST' AND contents_id=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }

    return [];
}

/*** ***/
public function contents_detail_video($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT contents_video_link FROM blog_contents WHERE contents_approved='OK' AND contents_id=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }

    return [];
}

/*** ***/
public function contents_download_guest($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT contents_preview_link, contents_github_link 
              FROM blog_contents 
              WHERE contents_approved='GPOST' AND contents_id=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }

    return [];
}

/*** ***/
public function contents_download($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT contents_preview_link, contents_github_link 
              FROM blog_contents 
              WHERE contents_approved='OK' AND contents_id=?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }

    return [];
}

/*** ***/
public function content_item_details_seo_last()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_approved='OK' ORDER BY contents_date DESC LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
        $num_result = $result->num_rows;

        if ($num_result == 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }
    return [];
}

/*** ***/
public function content_item_details_seo($articleno)
{
    $articleno = strval($articleno);
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $articleno);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_result = $result->num_rows;

        if ($num_result == 1) {
            $this->eBData = [];
            while ($rows = $result->fetch_assoc()) {
                $this->eBData[] = $rows;
            }
            $stmt->close();
            return $this->eBData;
        }

        $stmt->close();
    }

    return [];
}


/*** ***/
public function last_item()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_approved='OK' ORDER BY contents_date DESC LIMIT 1";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();

        $this->eBData = [];
        while ($rows = $result->fetch_assoc()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
        return $this->eBData;
    }

    return [];
}

/*** ***/
public function contents_mrss()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT * FROM blog_contents WHERE contents_approved=? ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'OK';
        $stmt->bind_param("s", $approved);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->eBData = [];
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
        return $this->eBData;
    }

    return [];
}

/*** ***/
public function contents_mrss_video()
{
    $conn = eBConDb::eBgetInstance()->eBgetConection();

    $query = "SELECT contents_id, contents_category, contents_sub_category, 
                     contents_og_image_title, contents_og_image_what_to_do, 
                     contents_date, contents_video_link 
              FROM blog_contents 
              WHERE contents_approved=? 
              ORDER BY contents_date DESC";

    if ($stmt = $conn->prepare($query)) {
        $approved = 'OK';
        $stmt->bind_param("s", $approved);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->eBData = [];
        while ($rows = $result->fetch_array()) {
            $this->eBData[] = $rows;
        }

        $stmt->close();
        return $this->eBData;
    }

    return [];
}
/*** ***/
private function blogTableExists()
{
$conn = eBConDb::eBgetInstance()->eBgetConection();
$result = $conn->query("SHOW TABLES LIKE 'blog_category'");
if($result && $result->num_rows > 0)
{
return true;
}
else
{
return false;
}
}
/*** ***/
private function createBlogTables()
{
    eBConDb::eBgetInstance()->eBgetConection()->query("CREATE TABLE IF NOT EXISTS `blog_category` (
        `contents_category_id` int(11) NOT NULL AUTO_INCREMENT,
        `contents_category` varchar(64) NOT NULL,
        PRIMARY KEY (`contents_category_id`),
        UNIQUE KEY `contents_category` (`contents_category`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
        
        eBConDb::eBgetInstance()->eBgetConection()->query("CREATE TABLE IF NOT EXISTS `blog_sub_category` (
        `contents_sub_category_id` int(11) NOT NULL AUTO_INCREMENT,
        `contents_sub_category` varchar(64) NOT NULL,
        `contents_category_in_blog_sub_category` varchar(64) NOT NULL,
        PRIMARY KEY (`contents_sub_category_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
        
        eBConDb::eBgetInstance()->eBgetConection()->query("CREATE TABLE IF NOT EXISTS `blog_contents` (
        `contents_id` varchar(48) NOT NULL,
        `username_contents` varchar(64) NOT NULL,
        `contents_approved` varchar(8) NOT NULL,
        `contents_category` varchar(64) NOT NULL,
        `contents_sub_category` varchar(64) NOT NULL,
        `contents_og_image_url` varchar(255) NOT NULL,
        `contents_og_small_image_url` varchar(255) NOT NULL,
        `contents_og_image_title` varchar(160) NOT NULL,
        `contents_og_image_what_to_do` longtext NOT NULL,
        `contents_og_login_required` varchar(8) NOT NULL,
        `contents_og_image_how_to_solve` longtext NOT NULL,
        `contents_affiliate_link` varchar(255) NOT NULL,
        `contents_github_link` varchar(255) NOT NULL,
        `contents_preview_link` varchar(255) NOT NULL,
        `contents_video_link` varchar(255) NOT NULL,
        `contents_date` varchar(64) NOT NULL,
        PRIMARY KEY (`contents_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8");
        
        eBConDb::eBgetInstance()->eBgetConection()->query("CREATE TABLE IF NOT EXISTS `blog_comments` (
        `blogs_comments_id` varchar(64) NOT NULL,
        `blogs_system_id` varchar(64) NOT NULL,
        `blogs_username` varchar(64) NOT NULL,
        `blogs_back_system` varchar(64) NOT NULL,
        `blogs_system_alert_type` varchar(24) NOT NULL,
        `blogs_comment_details` varchar(1600) NOT NULL,
        `blogs_comment_date` varchar(64) NOT NULL,
        `blogs_comment_status` varchar(8) NOT NULL,
        PRIMARY KEY (`blogs_comments_id`),
        INDEX (`blogs_username`),
        INDEX (`blogs_comment_status`),
        INDEX (`blogs_system_id`),
        INDEX (`blogs_comment_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
            
        eBConDb::eBgetInstance()->eBgetConection()->query("CREATE TABLE IF NOT EXISTS `blog_like` (
        `blog_like_id` varchar(48) NOT NULL,
        `blogs_id_in_blog_like` varchar(48) NOT NULL,
        `blogs_username` varchar(64) NOT NULL,
        `blogs_like_date` varchar(64) NOT NULL,
        PRIMARY KEY (`blog_like_id`),
        KEY `blogs_username` (`blogs_username`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8");
        
        $queryOne = "SELECT * FROM blog_category";
        $resultOne = eBConDb::eBgetInstance()->eBgetConection()->query($queryOne);
        $numResultOne = $resultOne->num_rows;
        if($numResultOne == 0)
        {
        //
        }  

}
/* End */
}
?>