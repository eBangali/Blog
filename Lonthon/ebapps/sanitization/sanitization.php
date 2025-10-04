<?php 
namespace ebapps\sanitization;
include_once(ebbd.'/dbconfig.php');
use ebapps\dbconnection\dbconfig;
/*** ***/
include_once(ebbd.'/eBConDb.php');
use ebapps\dbconnection\eBConDb;

class formSanitization extends dbconfig
{

    public function test_input_keyword($data) 
    {
        $data = trim($data);
        $con = eBConDb::eBgetInstance()->eBgetConection();
        $data = mysqli_real_escape_string($con, $data);
        return $data;
    }

    public function onlyUsernameInputForLowercase($data) 
    {
        $data = trim($data);
        $data = strtolower(trim($data));
        $con = eBConDb::eBgetInstance()->eBgetConection();
        $data = mysqli_real_escape_string($con, $data);
        return $data;
    }

    public function test_input($data) 
    {
        $data = trim($data);
        $con = eBConDb::eBgetInstance()->eBgetConection();
        $data = mysqli_real_escape_string($con, $data);
        return $data;
    }

    public function testArea($data) 
    {
        $data = trim($data);
        $con = eBConDb::eBgetInstance()->eBgetConection();
        $data = mysqli_real_escape_string($con, $data);
        return $data;
    }

    public function validEmail($email) 
    {
        $email = trim($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }


    public function validEmailDnsAndIP($email) 
    {
        $parts = explode("@", $email, 2);
        if (!isset($parts[1])) return false;

        $domain = $parts[1];

        if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
            return false;
        }

        $ip = gethostbyname($domain);
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public function validEmailOnlyGmail($email) 
    {
        $email = trim($email);
        return (mb_substr($email, -10) === '@gmail.com');
    }
    
    public function checkDisallowedHTMLTagsAndValues($data)
    {
    $allowedTags = ['div','h2','h3','h4','h5','h6','p','b','strong','u','i','ol','ul','li','em','section','table','tbody','tr','td','br','span'];
    $allowedAttrs = ['class','id','style','width','height'];
    $allowedAttrPatterns = [
        '/^data-[a-z0-9\-\_]+$/i',
        '/^aria-[a-z0-9\-\_]+$/i'
    ];
    $disallowedCSS = ['expression','javascript','vbscript'];

    $output = preg_replace_callback('/<\s*\/?\s*([a-zA-Z0-9]+)([^>]*)>/u',
        function($match) use ($allowedTags,$allowedAttrs,$allowedAttrPatterns,$disallowedCSS) {
            $tag = strtolower($match[1]);
            $attrs = $match[2];

            if (!in_array($tag, $allowedTags)) {
                return '';
            }

            $cleanAttrs = '';
            preg_match_all('/([a-zA-Z0-9\'\-\:\s]+)\s*=\s*"([^"]*)"/u', $attrs, $attrMatches, PREG_SET_ORDER);

            foreach ($attrMatches as $attr) {
                $attrName  = strtolower(trim(preg_replace("/[^a-zA-Z0-9\-\:]/", "-", $attr[1]))); // auto-fix
                $attrValue = $attr[2];

                $isAllowed = in_array($attrName, $allowedAttrs);
                foreach ($allowedAttrPatterns as $pattern) {
                    if (preg_match($pattern, $attrName)) {
                        $isAllowed = true;
                        break;
                    }
                }
                if (!$isAllowed) continue;

                if ($attrName === 'style') {
                    $styleContent = strtolower($attrValue);
                    foreach ($disallowedCSS as $cssPattern) {
                        if (strpos($styleContent, $cssPattern) !== false) {
                            continue 2;
                        }
                    }
                }

                $cleanAttrs .= " {$attrName}=\"{$attrValue}\"";
            }

            return "<{$tag}{$cleanAttrs}>";
        }, $data);

    return $output;
    }

}
?>