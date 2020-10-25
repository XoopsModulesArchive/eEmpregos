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
include 'admin_header.php';
require XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php';

#  function Index
#####################################################
function Index()
{
    global $hlpfile, $xoopsDB, $xoopsConfig, $xoopsModule, $monnaie, $moderated, $ynprice, $classm, $myts;

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

    xoops_cp_header();

    eEmpregos_admin_menu();

    $result = $xoopsDB->query('select lid, title, date from ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='No' order by lid");

    $numrows = $xoopsDB->getRowsNum($result);

    if ($numrows > 0) {
        OpenTable();

        echo '<b>' . _JOBS_WAIT . '</b><br><br>';

        echo _JOBS_THEREIS . " <b>$numrows</b> " . _JOBS_WAIT . '<br><br>';

        echo '<table width=100% cellpadding=2 cellspacing=0 border=0>';

        $rank = 1;

        while (list($lid, $title, $date) = $xoopsDB->fetchRow($result)) {
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $date2 = formatTimestamp($date, 's');

            if (is_int($rank / 2)) {
                $color = 'bg3';
            } else {
                $color = 'bg4';
            }

            echo "<tr class='$color'><td><a href=\"index.php?op=IndexView&amp;lid=$lid\">$title</a></td><td align=right> $date2</td></tr>";

            $rank++;
        }

        echo '</table>';

        CloseTable();

        echo '<br>';
    } else {
        OpenTable();

        echo _JOBS_NOANNVAL;

        CloseTable();

        echo '<br>';
    }

    // Modify Listing

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('eEmpregos_listing') . ''));

    if ($numrows > 0) {
        OpenTable();

        echo '<form method="post" action="index.php">'
             . '<b>'
             . _JOBS_MODANN
             . '</b><br><br>'
             . ''
             . _JOBS_NUMANN
             . ' <input type="text" name="lid" size="12" maxlength="11">&nbsp;&nbsp;'
             . '<input type="hidden" name="op" value="AnnoncesModAnnonce">'
             . '<input type="submit" value="'
             . _JOBS_MODIF
             . '">'
             . '<br><br>'
             . _JOBS_ALLMODANN
             . ''
             . '</form><center><A HREF="../index.php">'
             . _JOBS_ACCESMYANN
             . '</A></center>';

        CloseTable();

        echo '<br>';
    }

    // Add Type

    OpenTable();

    echo '<form method="post" action="index.php">
		<b>' . _JOBS_ADDTYPE . '</b><br><br>
		' . _JOBS_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
		<input type="hidden" name="op" value="AnnoncesAddType">
		<input type="submit" value="' . _JOBS_ADD . '">
		</form>';

    echo '<br>';

    // Modify Type

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('eEmpregos_type') . ''));

    if ($numrows > 0) {
        echo '<form method="post" action="index.php">
			 <b>' . _JOBS_MODTYPE . '</b></font><br><br>';

        $result = $xoopsDB->query('select id_type, nom_type from ' . $xoopsDB->prefix('eEmpregos_type') . ' order by nom_type');

        echo '' . _JOBS_TYPE . ' <select name="id_type">';

        while (list($id_type, $nom_type) = $xoopsDB->fetchRow($result)) {
            $nom_type = htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);

            echo "<option value=\"$id_type\">$nom_type</option>";
        }

        echo '</select>
		    <input type="hidden" name="op" value="AnnoncesModType"> 
			<input type="submit" value="' . _JOBS_MODIF . '">
		    </form>';

        CloseTable();

        echo '<br>';
    }

    // Add price

    OpenTable();

    echo '<form method="post" action="index.php">
		<b>' . _JOBS_ADDPRICE . '</b><br><br>
		' . _JOBS_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
		<input type="hidden" name="op" value="AnnoncesAddprice">
		<input type="submit" value="' . _JOBS_ADD . '">
		</form>';

    echo '<br>';

    // Modify price

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('eEmpregos_price') . ''));

    if ($numrows > 0) {
        echo '<form method="post" action="index.php">
			<b>' . _JOBS_MODPRICE . '</b></font><br><br>';

        $result = $xoopsDB->query('select id_price, nom_price from ' . $xoopsDB->prefix('eEmpregos_price') . ' order by nom_price');

        echo _JOBS_TYPE . ' <select name="id_price">';

        while (list($id_price, $nom_price) = $xoopsDB->fetchRow($result)) {
            $nom_price = htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);

            echo "<option value=\"$id_price\">$nom_price</option>";
        }

        echo '</select>
		    <input type="hidden" name="op" value="AnnoncesModprice"> 
			<input type="submit" value="' . _JOBS_MODIF . '">
		    </form>';

        CloseTable();

        echo '<br>';
    }

    xoops_cp_footer();
}

#  function IndexView
#####################################################
function IndexView($lid)
{
    //  global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $ynprice;

    global $xoopsDB, $xoopsModule, $xoopsConfig, $ynprice, $monnaie, $myts;

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

    xoops_cp_header();

    $result = $xoopsDB->query('select lid, cid, title, type, description, requirements, tel, price, typeprice, contactinfo, date, email, submitter, town, photo from ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='No' AND lid='$lid'");

    $numrows = $xoopsDB->getRowsNum($result);

    if ($numrows > 0) {
        OpenTable();

        echo '<B>' . _JOBS_WAIT . '</B><br><br>';

        [$lid, $cid, $title, $type, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $photo] = $xoopsDB->fetchRow($result);

        $date2 = formatTimestamp($date, 's');

        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $requirements = htmlspecialchars($requirements, ENT_QUOTES | ENT_HTML5);

        $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

        $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

        $typeprice = htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);

        $contactinfo = htmlspecialchars($contactinfo, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

        echo '<form action="index.php" method="post">
			<TABLE><tr>
			<td>' . _JOBS_NUMANN . " </td><td>$lid / $date2</td>
			</tr><tr>
			<td>" . _JOBS_SENDBY . " </td><td>$submitter</td>
			</tr><tr>
			<td>" . _JOBS_EMAIL . " </td><td><input type=\"text\" name=\"email\" size=\"30\" value=\"$email\"></td>
			</tr><tr>
			<td>" . _JOBS_TEL . " </td><td><input type=\"text\" name=\"tel\" size=\"30\" value=\"$tel\"></td>
			</tr><tr>
			<td>" . _JOBS_TOWN . " </td><td><input type=\"text\" name=\"town\" size=\"30\" value=\"$town\"></td>
			</tr><tr>
			<td>" . _JOBS_TITLE2 . " </td><td><input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"></td>
			</tr><tr>
			<td>" . _JOBS_TYPE . ' </td><td><select name="type">';

        $result5 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('eEmpregos_type') . ' order by nom_type');

        while (list($nom_type) = $xoopsDB->fetchRow($result5)) {
            $sel = '';

            if ($nom_type == $type) {
                $sel = 'selected';
            }

            echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
        }

        echo '</select></td>
			</tr><tr>
			<td>' . _JOBS_PHOTO2 . " </td><td><input type=\"text\" name=\"photo\" size=\"30\" value=\"$photo\"></td>
			</tr>";

        echo '<tr><td>' . _JOBS_DESC2 . " </td><td><textarea name=\"description\" cols=\"30\" rows=\"8\">$description</textarea></td>
			</tr><tr>";

        echo '<tr>
			<td>' . _JOBS_REQUIRE . " </td><td><textarea name=\"requirements\" cols=\"30\" rows=\"8\">$requirements</textarea></td>
			</tr><tr>";

        if (1 == $ynprice) {
            //			echo "<td>"._JOBS_PRICE2." </td><td><input type=\"text\" name=\"price\" size=\"10\" value=\"$price\"> $monnaie";

            echo '<td>' . _JOBS_PRICE2 . " </td><td><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> $monnaie";

            $result3 = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('eEmpregos_price') . ' order by id_price');

            echo " <select name=\"typeprice\"><option value=\"$typeprice\">$typeprice</option>";

            while (list($nom_price) = $xoopsDB->fetchRow($result3)) {
                echo "<option value=\"$nom_price\">$nom_price</option>";
            }

            echo '</select></td>';
        }

        echo '<tr>
			<td>' . _JOBS_CONTACTINFO2 . " </td><td><textarea name=\"contactinfo\" cols=\"28\" rows=\"4\">$contactinfo</textarea></td>
			</tr><td>";

        echo '</tr><tr>
			<td>' . _JOBS_CAT . ' </td><td>';

        $mytree->makeMySelBox('title', 'title', $cid);

        echo '</td>
			</tr><tr>
			<td>&nbsp;</td><td><select name="op">
			<option value="AnnoncesValid"> ' . _JOBS_OK . '
			<option value="AnnoncesDel"> ' . _JOBS_DEL . '
			</select><input type="submit" value="' . _JOBS_GO . '"></td>
			</tr></table>';

        echo '<input type="hidden" name="valid" value="Yes">';

        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";

        echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";

        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
			</form>";

        CloseTable();

        echo '<br>';
    }

    xoops_cp_footer();
}

#  function AnnoncesModAnnonce
#####################################################
function AnnoncesModAnnonce($lid)
{
    // for XOOPS CODE by Tom

    //global $xoopsDB, $xoopsModule, $xoopsConfig, $ynprice, $monnaie, $myts;

    global $xoopsDB, $xoopsModule, $xoopsConfig, $ynprice, $monnaie, $myts, $description, $requirements;

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

    // for XOOPS CODE  by Tom

    require XOOPS_ROOT_PATH . '/include/xoopscodes.php';

    xoops_cp_header();

    $result = $xoopsDB->query('select lid, cid, title, type, description, requirements, tel, price, typeprice, contactinfo, date, email, submitter, town, valid, photo from ' . $xoopsDB->prefix('eEmpregos_listing') . " where lid=$lid");

    OpenTable();

    echo '<b>' . _JOBS_MODANN . '</b><br><br>';

    while (list($lid, $cid, $title, $type, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $valid, $photo) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $requirements = htmlspecialchars($requirements, ENT_QUOTES | ENT_HTML5);

        $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

        $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

        $typeprice = htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);

        $contactinfo = htmlspecialchars($contactinfo, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

        $date2 = formatTimestamp($date, 's');

        echo '<form action="index.php" method=post>
		    <table class="outer" border=0><tr>
			<td class="outer">' . _JOBS_NUMANN . " </td><td class=\"odd\">$lid &nbsp; submitted on &nbsp; $date2</td>
			</tr><tr>
			<td class=\"outer\">" . _JOBS_SENDBY . " </td><td class=\"odd\">$submitter</td>
			</tr><tr>
			<td class=\"outer\">" . _JOBS_EMAIL . " </td><td class=\"odd\"><input type=\"text\" name=\"email\" size=\"30\" value=\"$email\"></td>
			</tr><tr>
			<td class=\"outer\">" . _JOBS_TEL . " </td><td class=\"odd\"><input type=\"text\" name=\"tel\" size=\"30\" value=\"$tel\"></td>
			</tr><tr>
			<td class=\"outer\">" . _JOBS_TOWN . " </td><td class=\"odd\"><input type=\"text\" name=\"town\" size=\"30\" value=\"$town\"></td>
			</tr><tr>
			<td class=\"outer\">" . _JOBS_TITLE2 . " </td><td class=\"odd\"><input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"></td>
			</tr><tr>
			<td class=\"outer\">" . _JOBS_TYPE . ' </td><td class="odd"><select name="type">';

        $result5 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('eEmpregos_type') . ' order by nom_type');

        while (list($nom_type) = $xoopsDB->fetchRow($result5)) {
            $sel = '';

            if ($nom_type == $type) {
                $sel = 'selected';
            }

            echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
        }

        echo '</select></td>
			</tr><tr>
			<td class="outer">' . _JOBS_CAT2 . ' </td><td class="odd">';

        $mytree->makeMySelBox('title', 'title', $cid);

        echo '</td>
			</tr><tr>
			<td class="outer">' . _JOBS_DESC2 . ' </td><td class="odd">';

        echo "<textarea name=\"description\" cols=\"30\" rows=\"10\">$description</textarea>";

        // add XOOPS CODE by Tom (hidden)

        //xoopsCodeTarea("description");

        echo '</td><tr>';

        echo '<td class="outer">' . _JOBS_REQUIRE . ' </td><td class="odd">';

        echo "<textarea name=\"requirements\" cols=\"30\" rows=\"10\">$requirements</textarea>";

        // add XOOPS CODE by Tom (hidden)

        //xoopsCodeTarea("requirements");

        echo '</td></tr><tr>
			<td class="outer">' . _JOBS_PHOTO2 . " </td><td class=\"odd\"><input type=\"text\" name=\"photo\" size=\"30\" value=\"$photo\"></td>
			</tr><tr>";

        if (1 == $ynprice) {
            echo '<td class="outer">' . _JOBS_PRICE2 . " </td><td class=\"odd\"><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> $monnaie";

            $result = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('eEmpregos_price') . ' order by nom_price');

            echo " <select name=\"id_price\"><option value=\"$typeprice\">$typeprice</option>";

            while (list($nom_price) = $xoopsDB->fetchRow($result)) {
                $nom_price = htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);

                echo "<option value=\"$nom_price\">$nom_price</option>";
            }

            echo '</select></td>';
        }

        echo '</tr>';

        echo '<td class="outer">' . _JOBS_CONTACTINFO2 . "</td><td class=\"odd\"><textarea name=\"contactinfo\" cols=\"28\" rows=\"4\">$contactinfo</textarea></td>";

        $time = time();

        echo '</tr><tr>
			<td>&nbsp;</td><td><select name="op">
			<option value="AnnoncesModAnnonceS"> ' . _JOBS_MODIF . '
			<option value="AnnoncesDel"> ' . _JOBS_DEL . '
			</select><input type="submit" value="' . _JOBS_GO . '"></td>
			</tr></table>';

        echo '<input type="hidden" name="valid" value="Yes">';

        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";

        echo "<input type=\"hidden\" name=\"date\" value=\"$time\">";

        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
		</form><br>";

        CloseTable();

        xoops_cp_footer();
    }
}

#  function AnnoncesModAnnonceS
#####################################################
function AnnoncesModAnnonceS($lid, $cat, $title, $type, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $valid, $photo)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $title = $myts->addSlashes($title);

    $type = $myts->addSlashes($type);

    $description = $myts->addSlashes($description);

    $requirements = $myts->addSlashes($requirements);

    $tel = $myts->addSlashes($tel);

    $price = $myts->addSlashes($price);

    $typeprice = $myts->addSlashes($typeprice);

    $contactinfo = $myts->addSlashes($contactinfo);

    $submitter = $myts->addSlashes($submitter);

    $town = $myts->addSlashes($town);

    $xoopsDB->query(
        'update '
        . $xoopsDB->prefix('eEmpregos_listing')
        . " set cid='$cat', title='$title', type='$type', description='$description', requirements='$requirements', tel='$tel', price='$price', typeprice='$typeprice', contactinfo='$contactinfo', date='$date', email='$email', submitter='$submitter', town='$town', valid='$valid', photo='$photo' where lid=$lid"
    );

    redirect_header('index.php', 1, _JOBS_JOBMOD);

    exit();
}

#  function AnnoncesDel
#####################################################
function AnnoncesDel($lid, $photo)
{
    global $xoopsDB;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('eEmpregos_listing') . " where lid=$lid");

    $destination = XOOPS_ROOT_PATH . '/modules/eEmpregos/logo_images';

    if ($photo) {
        if (file_exists("$destination/$photo")) {
            unlink("$destination/$photo");
        }
    }

    redirect_header('index.php', 1, _JOBS_JOBDEL);

    exit();
}

#  function AnnoncesValid
#####################################################
function AnnoncesValid($lid, $cat, $title, $type, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $valid, $photo)
{
    global $xoopsDB, $xoopsConfig, $myts, $meta;

    $title = $myts->addSlashes($title);

    $type = $myts->addSlashes($type);

    $description = $myts->addSlashes($description);

    $requirements = $myts->addSlashes($requirements);

    $tel = $myts->addSlashes($tel);

    $price = $myts->addSlashes($price);

    $typeprice = $myts->addSlashes($typeprice);

    $contactinfo = $myts->addSlashes($contactinfo);

    $submitter = $myts->addSlashes($submitter);

    $town = $myts->addSlashes($town);

    $xoopsDB->query(
        'update '
        . $xoopsDB->prefix('eEmpregos_listing')
        . " set cid='$cat', title='$title', type='$type', description='$description', requirements='$requirements', tel='$tel', price='$price', typeprice='$typeprice', contactinfo='$contactinfo', date='$date', email='$email', submitter='$submitter', town='$town', valid='$valid', photo='$photo'  where lid=$lid"
    );

    //	Specification for Japan:

    //	$message = ""._JOBS_HELLO." $submitter,\n\n "._JOBS_JOBACCEPT." :\n\n$type $title\n $description\n\n\n "._JOBS_CONSULTTO."\n ".XOOPS_URL."/modules/eEmpregos/index.php?pa=viewlistings&lid=$lid\n\n "._JOBS_THANK."\n\n"._JOBS_TEAMOF." ".$meta['title']."\n".XOOPS_URL."";

    if ('' == $email) {
    } else {
        $message = "$submitter "
                   . _JOBS_HELLO
                   . "\n\n "
                   . _JOBS_JOBACCEPT
                   . " :\n\n$type $title\n $description\n\n\n "
                   . _JOBS_CONSULTTO
                   . "\n "
                   . XOOPS_URL
                   . "/modules/eEmpregos/index.php?pa=viewlistings&amp;lid=$lid\n\n "
                   . _JOBS_THANK
                   . "\n\n"
                   . _JOBS_TEAMOF
                   . ' '
                   . $meta['title']
                   . "\n"
                   . XOOPS_URL
                   . '';

        $subject = '' . _JOBS_JOBACCEPT . '';

        $mail = getMailer();

        $mail->useMail();

        $mail->setFromName($meta['title']);

        $mail->setFromEmail($xoopsConfig['adminmail']);

        $mail->setToEmails($email);

        $mail->setSubject($subject);

        $mail->setBody($message);

        $mail->send();

        echo $mail->getErrors();
    }

    redirect_header('index.php', 1, _JOBS_JOBVALID);

    exit();
}

#  function AnnoncesAddType
#####################################################
function AnnoncesAddType($type)
{
    global $xoopsDB, $xoopsConfig, $myts;

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('eEmpregos_type') . " where nom_type='$type'"));

    if ($numrows > 0) {
        xoops_cp_header();

        OpenTable();

        echo '<br><center><b>' . _JOBS_ERRORTYPE . " $nom_type " . _JOBS_EXIST . '</b><br><br>';

        echo '<form method="post" action="index.php">
			<b>' . _JOBS_ADDTYPE . '</b><br><br>
			' . _JOBS_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
			<input type="hidden" name="op" value="AnnoncesAddType">
			<input type="submit" value="' . _JOBS_ADD . '">
			</form>';

        CloseTable();

        xoops_cp_footer();
    } else {
        $type = $myts->addSlashes($type);

        if ('' == $type) {
            $type = '! ! ? ! !';
        }

        $xoopsDB->query('insert into ' . $xoopsDB->prefix('eEmpregos_type') . " values (NULL, '$type')");

        redirect_header('index.php', 1, _JOBS_ADDTYPE2);

        exit();
    }
}

#  function AnnoncesModType
#####################################################
function AnnoncesModType($id_type)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts;

    xoops_cp_header();

    OpenTable();

    echo '<b>' . _JOBS_MODTYPE . '</b><br><br>';

    $result = $xoopsDB->query('select id_type, nom_type from ' . $xoopsDB->prefix('eEmpregos_type') . " where id_type=$id_type");

    [$id_type, $nom_type] = $xoopsDB->fetchRow($result);

    $nom_type = htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);

    echo '<form action="index.php" method="post">'
         . ''
         . _JOBS_TYPE
         . " <input type=\"text\" name=\"nom_type\" value=\"$nom_type\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_type\" value=\"$id_type\">"
         . '<input type="hidden" name="op" value="AnnoncesModTypeS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _JOBS_SAVMOD
         . '"></form></td><td>'
         . '<form action="index.php" method="post">'
         . "<input type=\"hidden\" name=\"id_type\" value=\"$id_type\">"
         . '<input type="hidden" name="op" value="AnnoncesDelType">'
         . '<input type="submit" value="'
         . _JOBS_DEL
         . '"></form></td></tr></table>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModTypeS
#####################################################
function AnnoncesModTypeS($id_type, $nom_type)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $nom_type = $myts->addSlashes($nom_type);

    $xoopsDB->query('update ' . $xoopsDB->prefix('eEmpregos_type') . " set nom_type='$nom_type' where id_type='$id_type'");

    redirect_header('index.php', 1, _JOBS_TYPEMOD);

    exit();
}

#  function AnnoncesDelType
#####################################################
function AnnoncesDelType($id_type)
{
    global $xoopsDB;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('eEmpregos_type') . " where id_type='$id_type'");

    redirect_header('index.php', 1, _JOBS_TYPEDEL);

    exit();
}

#  function AnnoncesAddprice
#####################################################
function AnnoncesAddprice($type)
{
    global $xoopsDB, $xoopsConfig, $myts;

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('eEmpregos_price') . " where nom_price='$type'"));

    if ($numrows > 0) {
        xoops_cp_header();

        OpenTable();

        echo '<br><center><b>' . _JOBS_ERRORPRICE . " <i>'$type'</i> " . _JOBS_EXIST . '</b><br><br>';

        echo '<form method="post" action="index.php">
			<b>' . _JOBS_ADDPRICE . '</b><br><br>
			' . _JOBS_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
			<input type="hidden" name="op" value="AnnoncesAddprice">
			<input type="submit" value="' . _JOBS_ADD . '">
			</form>';

        CloseTable();

        xoops_cp_footer();
    } else {
        $type = $myts->addSlashes($type);

        if ('' == $type) {
            $type = '! ! ? ! !';
        }

        $xoopsDB->query('insert into ' . $xoopsDB->prefix('eEmpregos_price') . " values (NULL, '$type')");

        redirect_header('index.php', 1, _JOBS_ADDPRICE2);

        exit();
    }
}

#  function AnnoncesModprice
#####################################################
//function AnnoncesModprice($id_price, $nom_type)
function AnnoncesModprice($id_price)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts;

    xoops_cp_header();

    OpenTable();

    echo '<b>' . _JOBS_MODPRICE . '</b><br><br>';

    $result = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('eEmpregos_price') . " where id_price=$id_price");

    [$nom_price] = $xoopsDB->fetchRow($result);

    $nom_price = htmlspecialchars($nom_price, ENT_QUOTES | ENT_HTML5);

    echo '<form action="index.php" method="post">'
         . ''
         . _JOBS_TYPE
         . " <input type=\"text\" name=\"nom_price\" value=\"$nom_price\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_price\" value=\"$id_price\">"
         . '<input type="hidden" name="op" value="AnnoncesModpriceS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _JOBS_SAVMOD
         . '"></form></td><td>'
         . '<form action="index.php" method="post">'
         . "<input type=\"hidden\" name=\"id_price\" value=\"$id_price\">"
         . '<input type="hidden" name="op" value="AnnoncesDelprice">'
         . '<input type="submit" value="'
         . _JOBS_DEL
         . '"></form></td></tr></table>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModpriceS
#####################################################
function AnnoncesModpriceS($id_price, $nom_price)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $nom_price = $myts->addSlashes($nom_price);

    $xoopsDB->query('update ' . $xoopsDB->prefix('eEmpregos_price') . " set nom_price='$nom_price' where id_price='$id_price'");

    redirect_header('index.php', 1, _JOBS_PRICEMOD);

    exit();
}

#  function AnnoncesDelprice
#####################################################
function AnnoncesDelprice($id_price)
{
    global $xoopsDB;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('eEmpregos_price') . " where id_price='$id_price'");

    redirect_header('index.php', 1, _JOBS_PRICEDEL);

    exit();
}

#####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$pa = $_GET['pa'] ?? '';

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = $_GET['lid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (!isset($op)) {
    $op = '';
}

switch ($op) {
    case 'IndexView':
        IndexView($lid);
        break;
    case 'AnnoncesDelprice':
        AnnoncesDelprice($id_price);
        break;
    case 'AnnoncesModprice':
        AnnoncesModprice($id_price);
        break;
    case 'AnnoncesModpriceS':
        AnnoncesModpriceS($id_price, $nom_price);
        break;
    case 'AnnoncesAddprice':
        AnnoncesAddprice($type);
        break;
    case 'AnnoncesDelType':
        AnnoncesDelType($id_type);
        break;
    case 'AnnoncesModType':
        AnnoncesModType($id_type);
        break;
    case 'AnnoncesModTypeS':
        AnnoncesModTypeS($id_type, $nom_type);
        break;
    case 'AnnoncesAddType':
        AnnoncesAddType($type);
        break;
    case 'AnnoncesDel':
        AnnoncesDel($lid, $photo);
        break;
    case 'AnnoncesValid':
        AnnoncesValid($lid, $cid, $title, $type, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $valid, $photo);
        break;
    case 'AnnoncesModAnnonce':
        AnnoncesModAnnonce($lid);
        break;
    case 'AnnoncesModAnnonceS':
        AnnoncesModAnnonceS($lid, $cid, $title, $type, $description, $requirements, $tel, $price, $id_price, $contactinfo, $date, $email, $submitter, $town, $valid, $photo);
        break;
    default:
        Index();
        break;
}
