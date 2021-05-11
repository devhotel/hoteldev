<?php
//set_include_path('/home/guptasfitser/site/public/');
if (!defined('SYSBASE')) define('SYSBASE', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../') . '/'));

if (trim(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/')), '/') != 'setup.php') {
    $base = getenv('BASE');
    if ($base === false) {
        $request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $pos = strrpos(SYSBASE, '/' . $request_uri[0] . '/');
        $base = false;
        if ($pos !== false) $base = substr(SYSBASE, $pos);
        if ($base === false || $base == '') $base = '/';
    }
    define('DOCBASE', $base);
}

$default_lang = 2;
$default_lang_tag = 'en';
$lang_alias = '';
$locale = 'en_GB';
$default_currency_code = 'USD';
$default_currency_sign = '$';
$default_currency_rate = 1;
$rtl_dir = false;
$db = false;
require('common/config.php');
require('common/lib-cron.php');
try {
    $db = new db('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    $db->exec('SET NAMES \'utf8\'');
} catch (PDOException $e) {
    die('Unable to connect to the database. Please contact the webmaster or retry later.');
}

$response = array('html' => '', 'notices' => array(), 'error' => '', 'success' => '');

if (isset($db) && $db !== false) {
    $today = gm_strtotime(gmdate('Y') . '-' . gmdate('n') . '-' . gmdate('j') . ' 00:00:00');
    $result_booking = $db->query('SELECT * FROM pm_booking ');
    if ($result_booking !== false) {
        foreach ($result_booking as $key => $lb) {
            $from_date = $lb['from_date'];
            $status = $lb['status'];
            $firstDate = time();
            $from_date = $lb['from_date'];
            $todate_date = $lb['to_date'];
            $recipient_name = $lb['firstname'];
            $fromdatechk = date('d', $firstDate) === date('d', $from_date);
            $todatechk = date('d', $firstDate) === date('d', $todate_date);
            if ($fromdatechk) {
                $subject = ' Booking is Check in';
                $from_email = 'reservation@guptahotels.com';
                $from_name = 'Gupta Hotels';
                $content = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking at Gupta Hotels for check-in date: ' . gmstrftime(DATE_FORMAT, $lb['from_date']) . ' has been cancelled.</p>
                    			    <p style="margin: 0 0 20px;">We hope to host you in the future.</p>
                    			    <p style="margin: 0 0 20px;">Cheers <br>Team Gupta Hotels </p></td></tr>';

                $to = 'sonjoy.bhadra@met-technologies.com';
                $from = 'reservation@guptahotels.com';
                $body = '<html><head><meta charset="utf-8"></head><body><div style="width: 700px;margin: 0 auto;"><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;"><tbody><tr><td style="text-align: center;padding: 20px 20px 0 20px;background: #fff;"><a href="#"><img src="https://devhotel.fitser.com//templates/gupta/images/logo.png" alt="" /></a>
                    <h2 style="color: #00767b;font-size: 20px;margin: 0 0 10px;line-height: 20px;">Hi, ' . $recipient_name . '</h2>
                    <p style="color: #000;font-size: 22px;margin: 0;">' . $subject . '</p>
                    </td>
                    </tr>' . htmlaccents($content) . '</tbody></table></div></body></html>';
                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                // Create email headers
                $headers .= 'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                // Sending email
                if (mail($to, $subject, $body, $headers)) {
                    echo 'Your mail has been sent successfully.';
                } else {
                    echo 'Unable to send email. Please try again.';
                }
            }
            if ($todatechk) {
                $subject = ' Booking is Check Out ';
                $from_email = 'reservation@guptahotels.com';
                $from_name = 'Gupta Hotels';
                $content = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking at Gupta Hotels for check-in date: ' . gmstrftime(DATE_FORMAT, $lb['from_date']) . ' has been cancelled.</p>
                    			    <p style="margin: 0 0 20px;">We hope to host you in the future.</p>
                    			    <p style="margin: 0 0 20px;">Cheers <br>Team Gupta Hotels </p></td></tr>';

                $to = 'anurag.sen@met-technologies.com';
                $from = 'reservation@guptahotels.com';
                $body = '<html><head><meta charset="utf-8"></head><body><div style="width: 700px;margin: 0 auto;"><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;"><tbody><tr><td style="text-align: center;padding: 20px 20px 0 20px;background: #fff;"><a href="#"><img src="https://devhotel.fitser.com//templates/gupta/images/logo.png" alt="" /></a>
                    <h2 style="color: #00767b;font-size: 20px;margin: 0 0 10px;line-height: 20px;">Hi, ' . $recipient_name . '</h2>
                    <p style="color: #000;font-size: 22px;margin: 0;">' . $subject . '</p>
                    </td>
                    </tr>' . htmlaccents($content) . '</tbody></table></div></body></html>';
                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                // Create email headers
                $headers .= 'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                // Sending email
                if (mail($to, $subject, $body, $headers)) {
                    echo 'Your mail has been sent successfully.';
                } else {
                    echo 'Unable to send email. Please try again.';
                }
            }
        }
    }
}
