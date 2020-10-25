<?php

// $Id: v 1.00 2004/12/30 15:46:00 Gustavo S. Villa Exp
//  ------------------------------------------------------------------------ //
//                            e-Empregos                                     //
//                              E-WARE                                       //
//                   <http://www.e-ware.com.br>                             //
//  ------------------------------------------------------------------------ //
//  Você ainda não pode substituir ou alterar qualquer parte desses comentários    //
//  ou créditos dos titulares e autores os quais são considerados direitos   //
//  reservados.                                                              //
//  ------------------------------------------------------------------------ //
//  Autor: Gustavo S. Villa  <guvilladev@e-ware.com.br>                      //
//  ------------------------------------------------------------------------ //
include 'header.php';
require XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php';
require XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';

function EnvAnn($lid)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger;

    $result = $xoopsDB->query('select lid, title, type FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " where lid=$lid");

    [$lid, $title, $type] = $xoopsDB->fetchRow($result);

    OpenTable();

    echo '
	    <b>' . _JOBS_SENDTO . " $lid \"<b>$title </b>\" " . _JOBS_FRIEND . "<br><br>
	    <form action=\"listing-p-f.php\" method=\"post\">
	    <input type=\"hidden\" name=\"lid\" value=$lid>";

    if ($xoopsUser) {
        $idd = $iddds = $xoopsUser->getVar('name', 'E');

        $idde = $iddds = $xoopsUser->getVar('email', 'E');
    }

    echo "
	<table width='100%' class='outer' cellspacing='1'>
    <tr>
      <td class='head' width='30%'>" . _JOBS_NAME . " </td>
      <td class='even'><input class=\"textbox\" type=\"text\" name=\"yname\" value=\"$idd\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_MAIL . " </td>
      <td class='even'><input class=\"textbox\" type=\"text\" name=\"ymail\" value=\"$idde\"></td>
    </tr>
    <tr>
      <td colspan=2 class='even'>&nbsp;</td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_NAMEFR . " </td>
      <td class='even'><input class=\"textbox\" type=\"text\" name=\"fname\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_MAILFR . " </td>
      <td class='even'><input class=\"textbox\" type=\"text\" name=\"fmail\"></td>
    </tr>
	</table><br>
    <input type=\"hidden\" name=\"op\" value=\"MailAnn\">
    <input type=\"submit\" value=" . _JOBS_SENDFR . '>
    </form>     ';

    CloseTable();

    //	Copyright();
    //	require XOOPS_ROOT_PATH."/footer.php";
}

function MailAnn($lid, $yname, $ymail, $fname, $fmail)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB, $monnaie, $ynprice, $myts, $xoopsLogger;

    $result = $xoopsDB->query('select lid, title, type, company, description, requirements, tel, price, typeprice, contactinfo, date, email, submitter, town, photo FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " where lid=$lid");

    [$lid, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $photo] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

    $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

    $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

    //	$description = $myts->displayTarea($description);

    $requirements = $myts->displayTarea($requirements);

    $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

    $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

    $typeprice = htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);

    $contactinfo = htmlspecialchars($contactinfo, ENT_QUOTES | ENT_HTML5);

    $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

    $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

    //	Specification for Japan:

    //	$message .= ""._JOBS_HELLO." $fname,\n\n$yname "._JOBS_MESSAGE."\n\n";

    $subject = '' . _JOBS_SUBJET . ' ' . $xoopsConfig['sitename'] . '';

    $message .= '' . _JOBS_HELLO . " $fname,\n\n$yname " . _JOBS_MESSAGE . "\n\n";

    //	$message = " $fname"._JOBS_HELLO.",\n\n$yname "._JOBS_MESSAGE."\n\n";

    $message .= "$title :  $type\n$description\n\n";

    if ($price && 1 == $ynprice) {
        $message .= '' . _JOBS_PRICE2 . " $monnaie $price $typeprice\n";
    }

    $message .= '' . _JOBS_BYMAIL . ' ' . XOOPS_URL . "/modules/eEmpregos/index.php?pa=viewlistings&amp;lid=$lid\n";

    if ($tel) {
        $message .= '' . _JOBS_TEL2 . " $tel\n";
    }

    if ($town) {
        $message .= '' . _JOBS_TOWN . " $town\n";
    }

    $message .= "\n" . _JOBS_INTERESS . ' ' . $xoopsConfig['sitename'] . "\n" . XOOPS_URL . '/modules/eEmpregos/';

    //    mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());

    $mail = getMailer();

    $mail->useMail();

    $mail->setFromEmail($ymail);

    $mail->setToEmails($fmail);

    $mail->setSubject($subject);

    $mail->setBody($message);

    $mail->send();

    echo $mail->getErrors();

    redirect_header('index.php', 1, _JOBS_JOBSEND);

    exit();
}

function ImprAnn($lid)
{
    //global $xoopsConfig, $xoopsDB, $monnaie, $useroffset, $claday, $ynprice, $myts,$xoopsLogger;

    global $xoopsConfig, $xoopsUser, $xoopsDB, $monnaie, $useroffset, $claday, $ynprice, $myts, $xoopsLogger;

    $currenttheme = getTheme();

    $result = $xoopsDB->query('select lid, title, type, company, description, requirements, tel, price, typeprice, contactinfo, date, email, submitter, town, photo FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " where lid=$lid");

    [$lid, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $photo] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

    $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

    //	$description = htmlspecialchars($description);

    $description = $myts->displayTarea($description);

    $requirements = $myts->displayTarea($requirements);

    $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

    $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

    $typeprice = htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);

    $contactinfo = $myts->displayTarea($contactinfo);

    $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

    $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

    echo '
    <html>
    <head><title>' . $xoopsConfig['sitename'] . '</title>
	<link rel="StyleSheet" href="../../themes/' . $currenttheme . '/style/style.css" type="text/css">
	</head>
    <body bgcolor="#FFFFFF" text="#000000">';

    $useroffset = '';

    if ($xoopsUser) {
        $timezone = $xoopsUser->timezone();

        if (isset($timezone)) {
            $useroffset = $xoopsUser->timezone();
        } else {
            $useroffset = $xoopsConfig['default_TZ'];
        }
    }

    $date = ($useroffset * 3600) + $date;

    $date2 = $date + ($claday * 86400);

    $date = formatTimestamp($date, 's');

    $date2 = formatTimestamp($date2, 's');

    echo '<table width=100% border=0><tr>
      <td>' . _JOBS_JOBFROM . " (No. $lid ) <br>" . _JOBS_FROM . " $submitter " . _JOBS_FOR . "
      $company<br><br>";

    if ($photo) {
        echo "<tr><td><left><img src=\"logo_images/$photo\" border=0></center>";
    }

    echo '</td>
	      </tr><br><br>';

    echo "<tr><td><b>$title :</b> <I>$type</I> ";

    echo '</td>
	      </tr><br>
    <tr>
      <td><b>' . _JOBS_DESC . "</b><br><br><div style=\"text-align:justify;\">$description</div><P>";

    echo '</td>
	      </tr><br><br>
    <tr>
      <td><b>' . _JOBS_REQUIRE . "</b><br><br><div style=\"text-align:justify;\">$requirements</div><P>";

    if ($price && 1 == $ynprice) {
        echo '<br><B>' . _JOBS_PRICE2 . "</B> $monnaie $price - $typeprice<br>";
    }

    if ($town) {
        echo '<br><b>' . _JOBS_TOWN . "</b> $town<br>";
    }

    echo '</td>
	      </tr><br><br>
	      <tr>
      <td><b>' . _JOBS_CONTACTINFO . "</b><br><br><div style=\"text-align:justify;\">$contactinfo</div><p>";

    echo '<br><br>' . _JOBS_DATE2 . " $date " . _JOBS_DISPO . " $date2<br><br>";

    echo '	
    <br><br><center>
    ' . _JOBS_EXTRANN . ' <b>' . $xoopsConfig['sitename'] . '</b><br>
    <a href="' . XOOPS_URL . '/modules/eEmpregos/">' . XOOPS_URL . '/modules/eEmpregos/</a>
	</td>
	</tr>
	</table>
	</body>
    </html>';
}

##############################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$lid = $_GET['lid'] ?? '';

if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

switch ($op) {
    case 'EnvAnn':
        require XOOPS_ROOT_PATH . '/header.php';
        EnvAnn($lid);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
    case 'MailAnn':
        MailAnn($lid, $yname, $ymail, $fname, $fmail);
        break;
    case 'ImprAnn':
        ImprAnn($lid);
        break;
    default:
        redirect_header('index.php', 1, '' . _RETURNGLO . '');
        break;
}
