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
require XOOPS_ROOT_PATH . '/modules/empregos/cache/config.php';

function AnnoncesDel($lid, $ok)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsLogger;

    $result = $xoopsDB->query('select usid, photo FROM ' . $xoopsDB->prefix('empregos_listing') . " where lid=$lid");

    [$usid, $photo] = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusern = $xoopsUser->getVar('uid', 'E');

        if ($usid == $calusern) {
            if (1 == $ok) {
                $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('empregos_listing') . " where lid=$lid");

                if ($photo) {
                    $destination = XOOPS_ROOT_PATH . '/modules/empregos/logo_images';

                    if (file_exists("$destination/$photo")) {
                        unlink("$destination/$photo");
                    }
                }

                redirect_header('index.php', 1, _JOBS_JOBDEL);

                exit();
            }

            OpenTable();

            echo '<br><center>';

            echo '<b>' . _JOBS_SURDELANN . '</b><br><br>';

            echo "[ <a href=\"modempregos.php?op=AnnoncesDel&amp;lid=$lid&amp;ok=1\">" . _JOBS_OUI . '</a> | <a href="index.php">' . _JOBS_NON . '</a> ]<br><br>';

            CloseTable();
        }
    }
}

function ModAnnonce($lid)
{
    // for XOOPS CODE by Tom

    //global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsUser, $monnaie, $moderated, $photomax, $ynprice, $xoopsTheme, $myts, $xoopsLogger;

    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsUser, $monnaie, $moderated, $photomax, $ynprice, $xoopsTheme, $myts, $xoopsLogger, $description;

    // for XOOPS CODE  by Tom

    require XOOPS_ROOT_PATH . '/include/xoopscodes.php';

    echo "<script language=\"javascript\">\nfunction CLA(CLA) { var MainWindow = window.open (CLA, \"_blank\",\"width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no\");}\n</script>";

    require_once XOOPS_ROOT_PATH . '/modules/empregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('empregos_categories'), 'cid', 'pid');

    $photomax1 = $photomax / 1024;

    $result = $xoopsDB->query('select lid, cid, title, type, company, description, requirements, tel, price, typeprice, contactinfo, date, email, submitter, usid, town, valid, photo from ' . $xoopsDB->prefix('empregos_listing') . " where lid=$lid");

    [$lid, $cide, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $usid, $town, $valid, $photo_old] = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusern = $xoopsUser->uid();

        if ($usid == $calusern) {
            OpenTable();

            echo '<b>' . _JOBS_MODIFANN . '</b><br><br>';

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

            $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

            $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

            $requirements = htmlspecialchars($requirements, ENT_QUOTES | ENT_HTML5);

            $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

            $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

            $typeprice = htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);

            $contactinfo = htmlspecialchars($contactinfo, ENT_QUOTES | ENT_HTML5);

            $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

            $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

            $useroffset = '';

            if ($xoopsUser) {
                $timezone = $xoopsUser->timezone();

                if (isset($timezone)) {
                    $useroffset = $xoopsUser->timezone();
                } else {
                    $useroffset = $xoopsConfig['default_TZ'];
                }
            }

            $dates = ($useroffset * 3600) + $date;

            $dates = formatTimestamp($date, 's');

            echo '<form action="modempregos.php" method=post ENCTYPE="multipart/form-data">
		    <table class="outer"><tr>
			<td class="outer">' . _JOBS_NUMANNN . " </td><td class=\"odd\">$lid " . _JOBS_DU . " $dates</td>
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
			</tr>
			<tr>
			<td class=\"outer\">" . _JOBS_COMPANY2 . " </td><td class=\"odd\"><input type=\"text\" name=\"company\" size=\"30\" value=\"$company\"></td>
			</tr>
			<tr>
			<td class=\"outer\">" . _JOBS_TYPE . ' </td><td class="odd"><select name="type">';

            $result5 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('empregos_type') . ' order by nom_type');

            while (list($nom_type) = $xoopsDB->fetchRow($result5)) {
                $sel = '';

                if ($nom_type == $type) {
                    $sel = 'selected';
                }

                echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
            }

            echo '</select></td>';

            echo '</tr><tr>
			<td class="outer">' . _JOBS_CAT2 . ' </td><td class="odd">';

            $mytree->makeMySelBox('title', 'title', $cide);

            echo '</td>
			</tr><tr>
			<td class="outer">' . _JOBS_DESC . ' </td><td class="odd">';

            echo "<textarea name=\"description\" cols=\"30\" rows=\"8\">$description</textarea></td>";

            // add XOOPS CODE by Tom (hidden)

            //ob_start();

            //$GLOBALS["description_text"] = $description;

            //xoopsCodeTarea("description_text",30,8);

            //$xoops_codes_tarea = ob_get_contents();

            //ob_end_clean();

            //echo $xoops_codes_tarea;

            echo '</tr><tr>';

            echo '<td class="outer">' . _JOBS_REQUIRE . ' </td><td class="odd">';

            echo "<textarea name=\"requirements\" cols=\"30\" rows=\"8\">$requirements</textarea></td>";

            // add XOOPS CODE by Tom (hidden)

            //ob_start();

            //$GLOBALS["requirements_text"] = $requirements;

            //xoopsCodeTarea("requirements_text",30,8);

            //$xoops_codes_tarea = ob_get_contents();

            //ob_end_clean();

            //echo $xoops_codes_tarea;

            echo '</td></tr>';

            if (1 == $ynprice) {
                echo '<tr><td class="outer">' . _JOBS_PRICE2 . " </td><td class=\"odd\"><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> $monnaie";

                $result3 = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('empregos_price') . ' order by id_price');

                echo " <select name=\"typeprice\"><option value=\"$typeprice\">$typeprice</option>";

                while (list($nom_price) = $xoopsDB->fetchRow($result3)) {
                    echo "<option value=\"$nom_price\">$nom_price</option>";
                }

                echo '</select></td></tr>';
            }

            echo '<tr><td class="outer"><B>' . _JOBS_CONTACTINFO . "</B></td><td class=\"odd\"><textarea name=\"contactinfo\" cols=\"25\" rows=\"6\">$contactinfo</textarea></td>";

            echo '</td><tr>';

            if ($photo_old) {
                echo '</tr><td>' . _JOBS_ACTUALPICT . " </td><td><A href=\"javascript:CLA('display-image.php?lid=$lid')\">$photo_old</A> <input type=\"hidden\" name=\"photo_old\" value=\"$photo_old\"> <INPUT TYPE=\"checkbox\" NAME=\"supprim\" VALUE=\"yes\"> " . _JOBS_DELPICT . '</td>
				</tr><tr>';

                echo '<td>' . _JOBS_NEWPICT . " </td><td><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$photomax\"><input type=file name=\"photo\"><br> (<  ";

                printf('%.2f KB', $photomax1);

                echo ')</td>';
            } else {
                echo '<td class="outer">' . _JOBS_IMG . "</td><td class=\"odd\"><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$photomax\"><input type=file name=\"photo\"><br> (<  ";

                printf('%.2f KB', $photomax1);

                echo ')</td>';
            }

            echo '</tr><tr>';

            echo '<td colspan=2><input type="submit" value="' . _JOBS_MODIFANN . '"></td>
			</tr></TABLE>';

            echo '<input type="hidden" name="op" value="ModAnnonceS">';

            if ('1' == $moderated) {
                echo '<input type="hidden" name="valid" value="No">';

                echo '<br>' . _JOBS_MODIFBEFORE . '<br>';
            } else {
                echo '<input type="hidden" name="valid" value="Yes">';
            }

            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";

            echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";

            echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
			</form><br>";

            CloseTable();
        }
    }
}

function ModAnnonceS($lid, $cat, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $valid, $photo, $photo_old, $photoS_size, $photoS_name, $HTTP_POST_FILES, $supprim)
{
    global $xoopsDB, $xoopsConfig, $photomax, $myts, $xoopsLogger;

    $destination = XOOPS_ROOT_PATH . '/modules/empregos/logo_images';

    if ('yes' == $supprim) {
        if (file_exists("$destination/$photo_old")) {
            unlink("$destination/$photo_old");
        }

        $photo_old = '';
    }

    $title = $myts->addSlashes($title);

    $type = $myts->addSlashes($type);

    $company = $myts->addSlashes($company);

    $description = $myts->addSlashes($description);

    $requirements = $myts->addSlashes($requirements);

    $tel = $myts->addSlashes($tel);

    $price = $myts->addSlashes($price);

    $typeprice = $myts->addSlashes($typeprice);

    $contactinfo = $myts->addSlashes($contactinfo);

    $submitter = $myts->addSlashes($submitter);

    $town = $myts->addSlashes($town);

    if (!empty($HTTP_POST_FILES['photo']['name'])) {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';

        $upload = new XoopsMediaUploader("$destination/", ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png'], $photomax);

        // for same file name Probrem  by Tom

        //$upload->setTargetFileName($HTTP_POST_FILES['photo']['name']);

        $upload->setTargetFileName($date . '_' . $HTTP_POST_FILES['photo']['name']);

        $upload->fetchMedia('photo');

        if (!$upload->upload()) {
            redirect_header("modempregos.php?op=ModAnnonce&amp;lid=$lid", 3, $upload->getErrors());

            exit();
        }

        if ($photo_old) {
            if (@file_exists("$destination/$photo_old")) {
                unlink("$destination/$photo_old");
            }
        }

        $photo_old = $upload->getSavedFileName();
    }

    $xoopsDB->query(
        'update '
        . $xoopsDB->prefix('empregos_listing')
        . " set cid='$cat', title='$title', type='$type', company='$company', description='$description', requirements='$requirements', tel='$tel', price='$price', typeprice='$typeprice',  contactinfo='$contactinfo', date='$date', email='$email', submitter='$submitter', town='$town', valid='$valid', photo='$photo_old' where lid=$lid"
    );

    redirect_header('index.php', 1, _JOBS_JOBMOD2);

    exit();
}

####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$ok = $_GET['ok'] ?? '';

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = $_GET['lid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

switch ($op) {
    case 'ModAnnonce':
        require XOOPS_ROOT_PATH . '/header.php';
        ModAnnonce($lid);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
    case 'ModAnnonceS':
        ModAnnonceS($lid, $cid, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $valid, $photo, $photo_old, $photo_size, $photo_name, $HTTP_POST_FILES, $supprim);
        break;
    case 'AnnoncesDel':
        require XOOPS_ROOT_PATH . '/header.php';
        AnnoncesDel($lid, $ok);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
    default:
        redirect_header('index.php', 1, '' . _RETURNANN . '');
        break;
}
