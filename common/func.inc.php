<?php
function checkUppercase($string)
{
    if (preg_match('/[A-Z]/', $string) === 0) {
        return 0;
    }

    return 1;
}

function checkDateFormat($date)
{
    //match the format of the date
    if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $date, $parts)) {
        //check weather the date is valid of not
        if (checkdate($parts[2], $parts[3], $parts[1])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function isValidDateTime($dateTime)
{
    if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
        if (checkdate($matches[2], $matches[3], $matches[1])) {

            return true;
        }
    }

    return false;
}

function checkBoxValue($value)
{
    if ($value == 'on') {
        $value = 1;
    } else {
        $value = 0;
    }

    return $value;
}

function serialNoCreator($prefix_serial_number)
{
    $serial_number = $prefix_serial_number . uniqid();

    return $serial_number;
}

function dateCreator()
{
    $creation_date = getdate();
    $creation_date = $creation_date['year'] . '-' . $creation_date['mon'] . '-' . $creation_date['mday'] . ' ' . $creation_date['hours'] . ':' . $creation_date['minutes'] . ':' . $creation_date['seconds'];

    return $creation_date;
}

function voucherCodeCreator()
{
    //$chars = strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 16))

    $guid = '';
    $uid = uniqid('', true);
    $data = '';
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['LOCAL_ADDR'];
    $data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    if (substr($hash, 0, 1) == '0') {
        voucherCodeCreator();
    }
    $guid = substr($hash, 0, 4) .
        substr($hash, 8, 4) .
        substr($hash, 24, 4) .
        substr($hash, 20, 4);

    return $guid;
}

function display_filesize($filesize)
{
    if (is_numeric($filesize)) {
        $decr = 1024;
        $step = 0;

        $prefix = array('بایت', 'کیلو بایت', 'مگا بایت', 'گیگا بایت', 'ترا بایت', 'پارا بایت');

        while (($filesize / $decr) > 0.9) {
            $filesize = $filesize / $decr;

            ++$step;
        }

        return round($filesize, 2) . ' ' . $prefix[$step];
    } else {
        return 'NaN';
    }
}

function generatePassword($length = 9)
{
    // start with a blank password

    $password = '';
    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = 'BCDFGHJKLMNPQRTVWXYZ';
    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
        $length = $maxlength;
    }
    // set up a counter for how many characters are in the password so far
    $i = 0;
    // add random characters to $password until $length is reached
    while ($i < $length) {

        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
        // have we already used this character in $password?
        if (!strstr($password, $char)) {
            // no, so it's OK to add it onto the end of whatever we've already got...
            $password .= $char;
            // ... and increase the counter by one
            ++$i;
        }
    }
    // done!
    return $password;
}

function generatePasswordNumber($length = 9)
{

    // start with a blank password

    $password = '';

    // define possible characters - any character in this string can be

    // picked for use in the password, so if you want to put vowels back in

    // or add special characters such as exclamation marks, this is where

    // you should do it

    $possible = '21346789';

    // we refer to the length of $possible a few times, so let's grab it now

    $maxlength = strlen($possible);

    // check for length overflow and truncate if necessary

    if ($length > $maxlength) {
        $length = $maxlength;
    }

    // set up a counter for how many characters are in the password so far

    $i = 0;

    // add random characters to $password until $length is reached

    while ($i < $length) {

        // pick a random character from the possible ones

        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);

        // have we already used this character in $password?

        if (!strstr($password, $char)) {

            // no, so it's OK to add it onto the end of whatever we've already got...

            $password .= $char;

            // ... and increase the counter by one

            ++$i;
        }
    }

    // done!

    return $password;
}

function redirectPage($page, $message = '')
{
    global $conn, $messageStack;

    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script language="javascript">

            setTimeout("window.location='<?=$page ?>'", 1500);
        </script>
        <style>
            body {
                font-family: sans-serif;
                background: url(<?=TEMPLATE_DIR?>images/background.png);
                line-height: 30px;
            }

            .a {
                background: url(<?=TEMPLATE_DIR?>images/back_light.png) bottom repeat-x #ffffff;
                border: 3px solid #ccc;
                width: 500px;
                margin-top: 10%;
                position: relative;
                padding-left: 200px;
                text-align: left;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -o-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            a {
                color: #990033;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
    <center>
        <div class="a">
            <?php

            echo $message;

            ?>
            <img src="<?php echo RELA_DIR . 'templates/' . CURRENT_SKIN . '/images/logo@2x.png' ?> "
                 align="left" style="position:absolute; left:40px;padding-top:15px; " height="60">
            <div style="clear:both"></div>
            <a href="<?= $page ?>">در صورت عدم ارسال اتوماتیک کلیک نمایید </a>

            <small>Loding ...</small>
            <div style="clear:both"></div>
            <br>
        </div>
    </center>
    </body>
    </html>


    <?php
    die();
}

function GetExtension($str)
{
    $i = strrpos($str, '.');

    if (!$i) {
        return '';
    }

    $l = strlen($str) - $i;

    $ext = substr($str, $i + 1, $l);

    return $ext;
}

function sendmail($email, $subject, $body, $header = '')
{
    include_once ROOT_DIR . 'common/phpmailer/class.phpmailer.php';
    //set_time_limit(3000);
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "$header\r\n" . 'Reply-To: ' . SMTP_USERNAME . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = SMTP_SERVER;
    $mail->SMTPAuth = true;     // turn on SMTP authentication
    $mail->Username = SMTP_USERNAME;  // SMTP username
    $mail->Password = SMTP_PASSWORD; // SMTP password
    $mail->From = SMTP_USERNAME;
    $mail->FromName = SMTP_SENDER;
    $mail->IsHTML(true);
    $mail->SetLanguage('en', ROOT_DIR . 'common/phpmailer/');
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = $body;
    $mail->ClearAddresses();
    $mail->AddAddress($email);

    if (!$mail->Send()) {
        //echo "<div class='fadeout'>Message was not sent";
        // echo "Mailer Error: " . $mail->ErrorInfo . "</div>";
        return 0;
    }

    return 1;
}

function sendmails($email, $bcc, $subject, $body, $orderID, $header = '')
{
    include_once ROOT_DIR . 'common/phpmailer/class.phpmailer.php';

    //set_time_limit(3000);
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "$header\r\n" . 'Reply-To: ' . SMTP_USERNAME . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = SMTP_SERVER;
    $mail->SMTPAuth = true;     // turn on SMTP authentication
    $mail->Username = SMTP_USERNAME;  // SMTP username
    $mail->Password = SMTP_PASSWORD; // SMTP password
    $mail->From = SMTP_USERNAME;
    $mail->FromName = SMTP_SENDER;
    $mail->IsHTML(true);
    $mail->SetLanguage('en', ROOT_DIR . 'common/phpmailer/');
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = $body;
    $mail->ClearAddresses();
    $mail->AddAddress($email);

    // foreach to bcc emails to add address
    foreach ($bcc as $mails) {
        $mail->AddBCC($mails);
    }

    // attach pdf file
    $mail->AddAttachment(ROOT_DIR . 'pdf/' . $orderID . '.pdf', 'orderNo_' . $orderID . '.pdf');

    if (!$mail->Send()) {
        return 0;
    }

    return 1;
}

function convertDate($date)
{
    include_once 'jdf.php';
    list($date, $time) = explode(' ', $date);
    list($g_y, $g_m, $g_d) = explode('-', $date);
    list($j_y, $j_m, $j_d) = gregorian_to_jalali($g_y, $g_m, $g_d);
    list($h, $m, $s) = explode(':', $time);
    $date = "$j_y/$j_m/$j_d";

    return $date;
}

function convertJToGDate($date)
{
    include_once 'jdf.php';
    $dateTime = explode('/', $date);
    $g_y = $dateTime[0];
    $g_m = $dateTime[1];
    $g_d = $dateTime[2];
    list($j_y, $j_m, $j_d) = jalali_to_gregorian($g_y, $g_m, $g_d);

    $date = "$j_y-$j_m-$j_d";

    return $date;
}

function round_func($x)
{
    //echo $x ."<BR>";
    $len = strlen($x);
    //echo $length."<BR>";
    //echo substr($x,$len-($len-1),1);
    if (substr($x, $len - ($len - 1), 1) < 5) {
        return (substr($x, 0, $len - ($len - 1)) . 5) * pow(10, $len - 2);
    } else {
        //return 1000;
        return round($x, ((strlen($x)) * -1));
    }
}

function handleData($data)
{
    return handleSQLData(trim(stripslashes($data)));
}

function checkSite($site)
{
    if (eregi("^[a-z\-\.]+[a-z0-9_\-]+\.[a-z0-9_\-\.]+$", $site)) {
        return 0;
    } else {
        return 1;
    }
}

function handleSQLData($data)
{
    $myData = str_replace("'", "''", $data);
    if (DB_TYPE == 'mysql') {
        $myData = str_replace('\\', '\\\\', $myData);
    }

    return $myData;
}

function handleSql($theValue)
{
    if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    return $theValue;
}

function checkSystemStatus()
{
    if (SYSTEM_STATUS == 1) {
        include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/system.stop.php';
        die();
    }
}

function checkMail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 0;
    } else {
        return 1;
    }
}

function inputCheckNumericId($ascii)
{
    if (preg_match('/^[0-9,]+$/i', $ascii)) {
        //^[a-zA-Z0-9_\.]+@[a-zA-Z0-9\-]+[\.a-zA-Z0-9]+$------>>>>/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/

        return 1;
    } else {
        return 0;
    }
}

function inputCheckEmails($ascii)
{
    if (preg_match("/^[a-zA-Z0-9@_.,\-]+$/i", $ascii)) {
        //^[a-zA-Z0-9_\.]+@[a-zA-Z0-9\-]+[\.a-zA-Z0-9]+$------>>>>/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/

        return 1;
    } else {
        return 0;
    }
}

function checkJoinMail($email)
{
    if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", $email)) {
        //^[a-zA-Z0-9_\.]+@[a-zA-Z0-9\-]+[\.a-zA-Z0-9]+$------>>>>/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/

        return 0;
    } else {
        return 1;
    }
}

function checkAscii($ascii)
{
    if (ereg("^[a-zA-Z0-9\.\,\+\!\@\#\$\%\^\&\*\(\)\:\~\/]+$", $ascii)) {
        return 0;
    } else {
        return 1;
    }
}

function checkUser($ascii)
{
    if (ereg("^[a-zA-Z0-9\-\_]+$", $ascii)) {
        return 0;
    } else {
        return 1;
    }
}

function checkDescription($alpha)
{
    if (ereg("^[a-zA-Z0-9\s ]+$", $alpha)) {
        return 1;
    } else {
        return 0;
    }
}

function checkAlpha($alpha)
{
    if (ereg('^[a-zA-Z ]+$', $alpha)) {
        return 0;
    } else {
        return 1;
    }
}

function checkLength($str, $length)
{
    if (strlen($str) > $length) {
        return -1;
    }

    return 0;
}

function checkNumeric($num)
{
    if (ereg('^[0-9]+$', $num)) {
        return 0;
    } else {
        return 1;
    }
}

function checkDigit($digit)
{
    /*if(ereg("^[0-9]+$", $digit))

    {

        return 0;

    }else {

        return 1;

    }

    */
    return 0;
}

function getDatetime()
{
    return date('Y-m-d H:i:s');
}

function getDateo()
{
    return date('Y-m-d');
}

function generate_password()
{
    $fillers = '1234567890!@#$%&*-_=+^';
    $fillers .= date('h-i-s, j-m-y, it is w Day z ');
    $fillers .= '123!@#$%&*-_4567!@#$%&*-_890=+^';
    $temp = md5($fillers);
    $temp = substr($temp, 5, 10);

    return $temp;
}

/**************************************************************************************************/

/*  Interface operation																			  */

/**************************************************************************************************/
function initPage($rs, $pageSize, &$currentPage, &$pageCount, &$totalRecord)
{
    $totalRecord = $rs->RecordCount();
    $pageCount = $totalRecord / $pageSize;

    if (!is_int($pageCount)) {
        $pageCount = intval($pageCount);
        $pageCount += 1;
    }
    $currentPage = intval($currentPage);
    if ($currentPage < 1) {
        $currentPage = 1;
    }
    if ($currentPage > $pageCount) {
        $currentPage = $pageCount;
    }
}

function showPageButton($currentPage, $pageCount, $totalRecord, $webaddress, $n = '')
{
    ?>
    <div class="pagination">
        <?php
        if ($currentPage > 1) {
            if ($currentPage < $pageCount) {
                ?>
                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=1" title="">&laquo; First</a>
                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=<?= $currentPage - 1 ?>" title="">&laquo; pre</a>
                <?php
                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }
                    ?>
                    <a href="<?= ($i != $currentPage ? $webaddress . '&currentPage' . $n . '=' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>"><?= $i ?></a>
                    <?php

                }
                ?>
                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=<?= $currentPage + 1 ?>" title="">Next Page &raquo;</a>
                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=<?= $pageCount ?>" title="">Last &raquo;</a>
                <?php

            } else {
                ?>
                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=1" title="">&laquo; First</a>
                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=<?= $currentPage - 1 ?>" title="">&laquo; Previous
                    Page</a>

                <?php
                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }
                    ?>
                    <a href="<?= ($i != $currentPage ? $webaddress . '&currentPage' . $n . '=' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>" title=""><?= $i ?></a>
                    <?php

                }
                ?>
                <a href="javascript:;" title="">Next Page &raquo;</a>
                <a href="javascript:;" title="">Last &raquo;</a>
                <?php

            }
        } else {
            if ($currentPage < $pageCount) {
                ?>
                <a href="javascript:;" title="">&laquo; First</a>
                <a href="javascript:;" title="">&laquo; Previous Page</a>
                <?php
                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    //die('1');
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }
                    ?>
                    <a href="<?= ($i != $currentPage ? $webaddress . '&currentPage' . $n . '=' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>"><?= $i ?></a>
                    <?php

                }
                ?>
                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=<?= $currentPage + 1 ?>" title="">Next Page &raquo;</a>

                <a href="<?= $webaddress ?>&currentPage<?= $n ?>=<?= $pageCount ?>" title="">Last &raquo;</a>
                <?php

            } else {
                ?>
                <a href="javascript:;" title="">&laquo; First</a>
                <a href="javascript:;" title="">&laquo; Previous Page</a>
                <?php
                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }
                    ?>
                    <a href="<?= ($i != $currentPage ? $webaddress . '&currentPage' . $n . '=' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>"><?= $i ?></a>
                    <?php

                }
                ?>
                <a href="javascript:;" title="">Next Page &raquo;</a>
                <a href="javascript:;" title="">Last &raquo;</a>
                <?php

            }
        }

        //echo $currentPage . "/" . $pageCount . "صفحه مجموع: " . $totalRecord . " رکورد";

        ?>

    </div> <!-- End .pagination -->

    <div class="clear"></div>

    <?php

}

function showPageButtonSeo($currentPage, $pageCount, $totalRecord, $webaddress)
{
    ?>

    <div class="pagination">

        <?php

        if ($currentPage > 1) {
            if ($currentPage < $pageCount) {
                ?>

                <a href="<?= $webaddress ?>PG-1" title="ابتدا">&laquo; ابتدا</a>

                <a href="<?= $webaddress ?>PG-<?= $currentPage - 1 ?>" title="صفحه قبلی">&laquo; صفحه قبلی</a>

                <?php

                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }

                    ?>

                    <a href="<?= ($i != $currentPage ? $webaddress . 'PG-' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>" title="<?= $i ?>"><?= $i ?></a>

                    <?php

                }

                ?>

                <a href="<?= $webaddress ?>PG-<?= $currentPage + 1 ?>" title="صفحه بعدی">صفحه بعدی &raquo;</a>

                <a href="<?= $webaddress ?>PG-<?= $pageCount ?>" title="انتها">انتها &raquo;</a>

                <?php

            } else {
                ?>

                <a href="<?= $webaddress ?>PG-1" title="ابتدا">&laquo; ابتدا</a>

                <a href="<?= $webaddress ?>PG-<?= $currentPage - 1 ?>" title="صفحه قبلی">&laquo; صفحه قبلی</a>

                <?php

                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }

                    ?>

                    <a href="<?= ($i != $currentPage ? $webaddress . 'PG-' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>" title="<?= $i ?>"><?= $i ?></a>

                    <?php

                }

                ?>

                <a href="javascript:;" title="صفحه بعدی">صفحه بعدی &raquo;</a>

                <a href="javascript:;" title="انتها">انتها &raquo;</a>

                <?php

            }
        } else {
            if ($currentPage < $pageCount) {
                ?>

                <a href="javascript:;" title="ابتدا">&laquo; ابتدا</a>

                <a href="javascript:;" title="صفحه قبلی">&laquo; صفحه قبلی</a>

                <?php

                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }

                    ?>

                    <a href="<?= ($i != $currentPage ? $webaddress . 'PG-' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>" title="<?= $i ?>"><?= $i ?></a>

                    <?php

                }

                ?>

                <a href="<?= $webaddress ?>PG-<?= $currentPage + 1 ?>" title="صفحه بعدی">صفحه بعدی &raquo;</a>

                <a href="<?= $webaddress ?>PG-<?= $pageCount ?>" title="انتها">انتها &raquo;</a>

                <?php

            } else {
                ?>

                <a href="javascript:;" title="ابتدا">&laquo; ابتدا</a>

                <a href="javascript:;" title="صفحه قبلی">&laquo; صفحه قبلی</a>

                <?php

                for ($i = $currentPage - 2; $i < $currentPage + 3; ++$i) {
                    if ($i < 1 || $i > $pageCount) {
                        continue;
                    }

                    ?>

                    <a href="<?= ($i != $currentPage ? $webaddress . 'PG-' . $i : 'javascript:;') ?>"
                       class="number <?= ($i != $currentPage ? '' : 'current') ?>" title="<?= $i ?>"><?= $i ?></a>

                    <?php

                }

                ?>

                <a href="javascript:;" title="صفحه بعدی">صفحه بعدی &raquo;</a>

                <a href="javascript:;" title="انتها">انتها &raquo;</a>

                <?php

            }
        }

        //echo $currentPage . "/" . $pageCount . "صفحه مجموع: " . $totalRecord . " رکورد";

        ?>

    </div> <!-- End .pagination -->

    <div class="clear"></div>

    <?php

}

function showErrorMsg($msg)
{
    global $conn;

    include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/title.inc.php';

    include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/system.error.php';

    include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/tail.inc.php';

    die();
}

function showAdminErrorMsg($msg)
{
    include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/admin.title.inc.php';

    include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/system.error.php';

    include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/admin.tail.inc.php';

    die();
}

function showAlertMsg($msg)
{
    if ($msg != '') {
        ?>
        <div class="alert border">
            <a href="#" class="close" style="display:block"><img
                    src="<?php echo RELA_DIR ?>templates/<?php echo CURRENT_SKIN ?>/images/alert.png" align="left"
                    title="Close this notification" alt="close"/></a>
            <span><?= $msg ?></span>
        </div>


        <?php

    }
}

function showWarningMsg($msg)
{
    if ($msg) {
        ?>
        <div class="notification error png_bg">
            <a class="close" href="#"><img alt="close" title="Close this notification"
                                           src="<?= TEMPLATE_DIR ?>admin/images/cross_grey_small.png"></a>
            <div>
                <?= $msg ?>
            </div>
        </div>

        <?php

    }
}

function showMsg($redirect)
{
    if ($redirect) {
        ?>
        <div class="notification png_bg">
            <div class="success">
                <a href="#" class="close"><img
                        src="<?php echo RELA_DIR ?>templates/<?php echo CURRENT_SKIN ?>/admin/images/icons/cross_grey_small.png"
                        title="Close this notification" alt="close"/></a>
                <div>
                    <?= $redirect ?>
                </div>
            </div>
        </div>
        <?php

    }
}

function showWarningMsg1($msg)
{
    if ($msg) {
        ?>

        <div class="fadeout"><?php echo $msg ?></div>

        <?php

    }
}

//*********************************************Alizadeh***************************************************************
function monthToYear($month)
{
    if ($month >= 12) {
        $year = intval($month / 12);
        $month = $month % 12;
        $result = $year . ' Year ';
        if ($month != 0) {
            $result = $result . ' .  ' . $month . ' Month ';
        }
    } else {
        $result = $month . ' Month ';
    }

    return $result;
}

function mobileChecker($prefix, $number)
{
    if ($prefix == '+964') {
        if (strlen($number) != 10) {
            $return['result'] = -1;
            $return['msg'] = 'Please enter your mobile number correctly.';
        }
    } else {
        $return['result'] = 1;
        $return['msg'] = 'ok';
    }

    return $return;
}

function ipChecker($ip)
{
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $return['result'] = -1;
        $return['msg'] = 'IP is not valid.';
    } else {
        $return['result'] = 1;
        $return['msg'] = 'IP is valid';
    }

    return $return;
}

//************************************************************************************************************
function encrypt($string, $key)
{
    $result = '';
    for ($i = 0; $i < strlen($string); ++$i) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
    }

    return base64_encode($result);
}

function decrypt($string, $key)
{
    $result = '';
    $string = base64_decode($string);

    for ($i = 0; $i < strlen($string); ++$i) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result .= $char;
    }

    return $result;
}

function showAccessError()
{
    //$path=$_SERVER['HTTP_REFERER'];
    $path = RELA_DIR;
    ?>

    <script type="text/javascript">
        alert('you dont have proper permissions');
        window.location = '<?php echo $path ?>';
    </script>

    <?php
    die();
}

function checkPermissions($action)
{
    global $admin_info;
    // $admin_permission=$admin_info['permission'];

    include_once ROOT_DIR . 'model/admin.permission.class.php';

    $PagePermission = getAllPermisssion();
    //echo "<pre>";print_r($PagePermission);die();
    $script = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
    $admin_permission = $admin_info['permission'];
    //$newObj=unserialize($PagePermission[$script]);
    $newObj = $PagePermission[$script];

    unset($PagePermission);

    $return = $newObj->check($action, $admin_permission);
    //echo $return;die();
    if ($return['result'] != 1) {
        showAccessError();
    }

    return 1;
}

function checkPermissionsUI($pageName, $action)
{
    global $admin_info;
    //print_r($admin_info);die('sevjppeml;');
    $admin_permission = $admin_info['permission'];
    ///print_r($admin_info);die('ftyftg');
    ///echo $pageName,$action;die('wefopk;wef');
    include_once ROOT_DIR . 'model/admin.permission.class.php';

    $PagePermission = getAllPermisssion();

    $newObj = $PagePermission[$pageName];

    unset($PagePermission);

    $return = $newObj->check($action, $admin_permission);
    //print_r($return);die('iiiiiiiiiuj');
    if ($return['result'] != 1) {
        return 0;
    }

    return 1;
}

function get_group_info_date($p_id)
{
    global $conn, $member_info, $lang;
    $sql = "select * from  internet_detail  where product_id ='$p_id' ";

    $internet_detail_rs = $conn->Execute($sql);
    if (!$internet_detail_rs) {
        $return['result'] = 0;
        $return['err'] = '400';
        $return['msg'] = 'DB Error';

        return $return;
    }

    $return['result'] = 1;
    $return['err'] = '0';
    $return['msg'] = 'successful';
    $return['rs'] = $internet_detail_rs->fields;
    //echo '<pre/>';
    //print_r($return);
    //die();
    return $return;
}

function print_r_debug($data)
{
    echo '<pre>';
    print_r($data);
    die();
}

function get_cities()
{
    include_once ROOT_DIR . 'component/city/model/city.model.db.php';
    $cities = cityModelDb::getCities()['export']['list'];

    return $cities;
}

//hamid

function paginationButtom($recordCount = 0, $countButtom = 10)
{
    global $page, $PARAM;

    if (($countButtom != 0) and ($recordCount != 0)) {
        $pageCount = ceil($recordCount / PAGE_SIZE);
        $pagination = array();
        $pAddress = implode('/', $PARAM);
        $pAddress .= '/';
        //print_r($pAddress);
        /*for ($num = 0; $num < count($PARAM); $num++) {
            $pAddress = $pAddress . $PARAM[$num] . "/";
        }*/
        //print_r_debug($pAddress);

        if (!isset($page)) {
            $page = 1;
        }

        $fPagination = 0;
        $lPagination = 0;

        $num = $countButtom;
        if ($pageCount < $num) {
            $fPagination = 1;
            $lPagination = $pageCount;
            $nPage = false;
            $pPage = false;
        } elseif ($page == 1) {
            $fPagination = 1;
            $lPagination = $num;
            $nPage = true;
            $pPage = false;
        } elseif (($pageCount == $page)) {
            $fPagination = $pageCount - ($num - 1);
            $lPagination = $pageCount;
            $nPage = false;
            $pPage = true;
        } else {
            $fPagination = $page - floor($num / 2);
            if (($num % 2) == 0) {
                $lPagination = $page + ((floor($num / 2)) - 1);
            } else {
                $lPagination = $page + ((floor($num / 2)));
            }
            $nPage = true;
            $pPage = true;
            if ($fPagination <= 0) {
                //$fPagination = $page-1;
                //$lPagination = $page+3;
                $fPagination = 1;
                $lPagination = $num;
            } elseif ($pageCount < $lPagination) {
                //$fPagination = $page-3;
                //$lPagination = $page+1;
                $fPagination = $pageCount - (($num - 1));
                $lPagination = $pageCount;
            }
        }
        for ($i = $fPagination; $i <= $lPagination; $i++) {
            if (($i == $fPagination) and ($pPage == true)) {
                $pagination[] = [address => $pAddress . 'page/' . ($page - 1), label => ">", number => $i];
                $pPage == false;
            }
            if ($page == $i) {
                $activePage = " activePage";
            } else {
                $activePage = "";
            }
            $pagination[] = [address => $pAddress . 'page/' . $i, number => $i, label => $i, "activePage" => $activePage];
            if (($i == $lPagination) and ($nPage == true)) {
                $pagination[] = [address => $pAddress . 'page/' . ($page + 1), label => "<", number => $i];
                $pPage == false;
            }
        }
    } else {
        $result['result'] = -1;
        $result['export']['list'] = '';
        return $result;
    }
    $result['result'] = 1;
    $result['export']['list'] = $pagination;
    $result['export']['pageCount'] = $pageCount;
    $result['export']['rowCount'] = $recordCount;
    //print_r_debug($result);

    return $result;
}

//hamid vahed
//

function fileUploader($input = array(), $file = array())
{
    $msg = "";
//check type of Image
    if (isset($input['new_name'])) {
        $new_name = $input['new_name'];
    } else {
    }

//check type of Image
    if (isset($input['type'])) {
        $input['type'] = strtolower($input['type']);
        $type = explode(',', $input['type']);
    } else {
        $type = array('jpg','mp4','mp3');
    }

//check size of Image
    if (isset($input['max_size'])) {
        $maxSize = $input['max_size'];
    } else {
        $maxSize = '2048000';  //max size is 2 MB
    }

//check size of Image
    if (isset($input['upload_dir'])) {
        $target_dir = $input['upload_dir'];
    } else {
        $target_dir = $input['upload_dir'];
    }

    //Create directory
    $dirs="";
    if(!(is_dir($target_dir))){

        $dir=explode("/",$target_dir);

        foreach($dir as $value){
            //if($value != ""){

                if((is_dir($dirs.$value)) != 1){
                    mkdir($dirs.$value);

                    $dirs .=$value."/";
                }else{
                    $dirs .= $value. "/" ;
                }
            //}
        }

    }





    if (isset($input['height'])) {
        $height = $input['height'];
    } else {
        $height = '';
    }

    if (isset($input['wight'])) {
        $wight = $input['wight'];
    } else {
        $wight = '';
    }

    if (isset($input['error_msg'])) {
        $error_msg = $input['error_msg'];
    } else {
        $error_msg = "Sorry, your file was not uploaded.";
    }

    if (isset($input['success_msg'])) {
        $success_msg = $input['success_msg'];
    } else {
        $success_msg = "The file " . basename($file["name"]) . " has been uploaded.";
    }

    $target_file = $target_dir . strtotime("now") . "._" . basename($file["name"]);
    $result['image_name'] = (strtotime("now") . "._" . basename($file["name"]));

    $uploadOk = 1;
     $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

//print_r_debug($fileType );

    $nameFile = ((str_ireplace("." . $fileType, "", $file["name"])) . "._" . strtotime("now") . "." . $fileType);
    $check = getimagesize($file["tmp_name"]);


//Check if file already exists
    if (file_exists($target_file)) {
        $result['msg']['file_exists'] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

// Check file size
    if ($file["size"] > $maxSize) {
        $result['msg']['size'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    $uploadOk = 0;

    foreach ($type as $key => $value) {
        if ($value == $fileType){
            $uploadOk = 1;
            break;
        }
    }

    if ($uploadOk == 0) {
        $result['msg']['type'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }



// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $result['msg']['error_msg'] = $error_msg;
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $result['msg']['success_msg'] = $success_msg;
        } else {
            $result['msg']['error_msg'] = $error_msg;
        }
    }
    
    return $result;
}


function fileRemover($dir,$fileName)
{
    if(trim($fileName)!= ''){
        if (file_exists($dir.$fileName)) {
            unlink($dir.$fileName);
            $result['result'] = "1";
            $result['msg'] = "file removed.";
        }else{
            $result['result'] = "-1";
            $result['msg'] = "Sorry, file not exists.";
        }
    }else{
        $result['result'] = "-1";
        $result['msg'] = "Sorry, file name is empety.";
    }

    return $result;
}

function translate($text,$lang='')
{
    if($lang == ''){global $lang;}

    include_once 'component/dictionary/model/dictionary.model.php';
    $obj = dictionary::getBy_text_and_lang($text,$lang)->first();

    if(is_object($obj)){$result = $obj->fields['translate'];}
    else{$result = $text;}

    return $result;

}
?>
