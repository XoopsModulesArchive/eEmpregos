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

function addindex($cid)
{
    //global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTheme, $photomax, $xoopsLogger;

    global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTheme, $photomax, $xoopsLogger, $xoopsModule;

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php';

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';

    // for XOOPS CODE  by Tom

    require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

    if ('' == $cid) {
        redirect_header('index.php', 1, _JOBS_ADDLISTING);

        exit();
    }

    if (!$xoopsUser && 0 == $annoadd) {
        redirect_header(XOOPS_URL . '/user.php', 3, _JOBS_FORMEMBERS2);

        exit();
    }

    $photomax1 = $photomax / 1024;

    echo '<script type="text/javascript">
          function verify() {
                var msg = "' . _JOBS_VALIDERORMSG . '\\n__________________________________________________\\n\\n";
                var errors = "FALSE";

                if (document.add.type.value == "0") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDTYPE . '\\n";
                }
				
                if (document.add.cid.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDCAT . '\\n";
                }
				
                if (document.add.title.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDTITLE . '\\n";
                }


				if (document.add.description.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDJOB . '\\n";
                }
		
				if (document.add.requirements.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDREQ . '\\n";
                }
				
				if (document.add.submitter.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDSUBMITTER . '\\n";
                }
				
				if (document.add.email.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDEMAIL . '\\n";
                }
				
				if (document.add.town.value == "") {
                        errors = "TRUE";
                        msg += "' . _JOBS_VALIDTOWN . '\\n";
                }
				
                if (errors == "TRUE") {
                        msg += "__________________________________________________\\n\\n' . _JOBS_VALIDMSG . '\\n";
                        alert(msg);
                        return false;
                }
          }
          </script>';

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select cid, title from ' . $xoopsDB->prefix('eEmpregos_categories') . ''));

    if ($numrows > 0) {
        OpenTable();

        if ('1' == $moderated) {
            echo '<b>' . _JOBS_ADDLISTING3 . '</b><br><br><center>' . _JOBS_JOBMODERATE . " $claday " . _JOBS_DAY . '</center><br><br>';
        } else {
            echo '<b>' . _JOBS_ADDLISTING3 . '</b><br><br><center>' . _JOBS_JOBNOMODERATE . " $claday " . _JOBS_DAY . '</center><br><br>';
        }

        echo '<form method="post" action="addlisting.php" enctype="multipart/form-data" name="add" onsubmit="return verify();">';

        echo "<table width='100%' class='outer' cellspacing='1'><tr>";

        $result2 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('eEmpregos_type') . ' order by nom_type');

        echo "<td class='outer'>" . _JOBS_TYPE . " </td><td class='odd'><select name=\"type\"><option value=\"0\">" . _JOBS_SELECTYPE . '</option>';

        while (list($nomtyp) = $xoopsDB->fetchRow($result2)) {
            echo "<option value=\"$nomtyp\">$nomtyp</option>";
        }

        echo '</select></td>
				</tr><tr>';

        echo "<td class='outer'>" . _JOBS_CAT3 . " </td><td class='odd'>";

        $x = 0;

        $i = 0;

        $result = $xoopsDB->query('select cid, pid, title, affprice from ' . $xoopsDB->prefix('eEmpregos_categories') . ' where  cid=' . $cid . '');

        [$ccid, $pid, $title, $affprice] = $xoopsDB->fetchRow($result);

        $varid[$x] = $ccid;

        $varnom[$x] = $title;

        if (0 != $pid) {
            $x = 1;

            while (0 != $pid) {
                $result2 = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('eEmpregos_categories') . ' where cid=' . $pid . '');

                [$ccid, $pid, $title] = $xoopsDB->fetchRow($result2);

                $varid[$x] = $ccid;

                $varnom[$x] = $title;

                $x++;
            }

            $x -= 1;
        }

        while (-1 != $x) {
            echo ' &raquo; ' . $varnom[$x] . '';

            $x -= 1;
        }

        echo "<input type=\"hidden\" name=\"cid\" value=\"$cid\"></td>
				</tr><tr>
				<td class='outer'>" . _JOBS_TITLE2 . " </td><td class='odd'><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\"></td>
				</tr><tr>
				<td class='outer'>" . _JOBS_COMPANY2 . " </td><td class='odd'><input type=\"text\" name=\"company\" size=\"30\" maxlength=\"100\"></td>
				</tr>
				<tr>
				<td class='outer'>" . _JOBS_DESC . " <br></td><td class='odd'>";

        $description = '';

        echo "<textarea name=\"description\" cols=\"30\" rows=\"6\">$description</textarea>";

        // add XOOPS CODE by Tom (hidden)

        //$description ="";

        //ob_start();

        //$GLOBALS["description_text"] = $description;

        //xoopsCodeTarea("description_text",30,6);

        //$xoops_codes_tarea = ob_get_contents();

        //ob_end_clean();

        //echo $xoops_codes_tarea;

        $requirements = '';

        echo "</td></tr><tr>
		      <td class='outer'>" . _JOBS_REQUIRE . " <br></td><td class='odd'> 
		      <textarea name=\"requirements\" cols=\"30\" rows=\"6\">$requirements</textarea>";

        //$requirements ="";

        //ob_start();

        //$GLOBALS["requirements_text"] = $requirements;

        //xoopsCodeTarea("requirements_text",30,6);

        //$xoops_codes_tarea = ob_get_contents();

        //ob_end_clean();

        //echo $xoops_codes_tarea;

        echo '</td></tr>';

        echo '<tr>';

        echo "<td class='outer'>" . _JOBS_IMG . "</td><td class='odd'><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$photomax\"><input type=\"file\" name=\"photo\"> (&lt;  ";

        printf('%.2f KB', $photomax1);

        echo ')</td></tr>';

        if (1 == $ynprice) {
            if (1 == $affprice) {
                echo "<tr><td class='outer'>" . _JOBS_PRICE2 . "</td><td class='odd'><input type=\"text\" name=\"price\" size=\"20\">$monnaie";

                $result3 = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('eEmpregos_price') . ' order by id_price');

                echo '<select name="typeprice">';

                while (list($nom_price) = $xoopsDB->fetchRow($result3)) {
                    echo "<option value=\"$nom_price\">$nom_price</option>";
                }

                echo '</select></td>';

                echo '</tr>';
            }
        }

        $contactinfo = '';

        echo '<tr>';

        echo "<td class='outer'>" . _JOBS_CONTACTINFO . "</td><td class='odd'><textarea name=\"contactinfo\" cols=\"28\" rows=\"4\">$contactinfo</textarea></td>";

        if ($xoopsUser) {
            $iddd = $xoopsUser->getVar('uid', 'E');

            $idd = $xoopsUser->getVar('name', 'E'); // Real name

            $idde = $xoopsUser->getVar('email', 'E');

            // Add by Tom
                $iddn = $xoopsUser->getVar('uname', 'E'); // user name
        }

        $time = time();

        // CHGED name pattern by Tom

        if ($idd) {
            echo "</tr><tr>
					<td class='outer'>" . _JOBS_SURNAME . " </td><td class='odd'><input type=\"text\" name=\"submitter\" size=\"30\" value=\"$idd\"></td>";
        } else {
            echo "</tr><tr>
					<td class='outer'>" . _JOBS_SURNAME . " </td><td class='odd'><input type=\"text\" name=\"submitter\" size=\"30\" value=\"$iddn\"></td>";
        }

        echo "</tr><tr>
				<td class='outer'>" . _JOBS_EMAIL . " </td><td class='odd'><input type=\"text\" name=\"email\" size=\"30\" value=\"$idde\"></td>
				</tr><tr>
				<td class='outer'>" . _JOBS_TEL . " </td><td class='odd'><input type=\"text\" name=\"tel\" size=\"30\"></td>
				</tr><tr>
				<td class='outer'>" . _JOBS_TOWN . " </td><td class='odd'><input type=\"text\" name=\"town\" size=\"30\"></td>
				</tr>
				</table><br>
				<input type=\"hidden\" name=\"usid\" value=\"$iddd\">
				<input type=\"hidden\" name=\"op\" value=\"AddListingsOk\">";

        if ('1' == $moderated) {
            echo '<input type="hidden" name="valid" value="No">';
        } else {
            echo '<input type="hidden" name="valid" value="Yes">';
        }

        echo "<input type=\"hidden\" name=\"lid\" value=\"0\">
				<input type=\"hidden\" name=\"date\" value=\"$time\">
				<input type=\"submit\" value=\"" . _JOBS_VALIDATE . '">';

        echo '</form>';

        CloseTable();
    }
}

function AddListingsOk($lid, $cat, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $usid, $town, $valid, $photo, $photo_size, $photo_name, $HTTP_POST_FILES)
{
    global $xoopsDB, $xoopsConfig, $photomax, $destination, $myts, $xoopsLogger;

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php';

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';

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

    $filename = '';

    if (!empty($HTTP_POST_FILES['photo']['name'])) {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';

        $upload = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/modules/eEmpregos/logo_images/', ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png'], $photomax);

        // for same file name Probrem  by Tom

        //$upload->setTargetFileName($HTTP_POST_FILES['photo']['name']);

        $upload->setTargetFileName($date . '_' . $HTTP_POST_FILES['photo']['name']);

        $upload->fetchMedia('photo');

        if (!$upload->upload()) {
            redirect_header("addlisting.php?cid=$cat", 3, $upload->getErrors());

            exit();
        }

        $filename = $upload->getSavedFileName();
    }

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('eEmpregos_listing') . " values ('', '$cat', '$title', '$type', '$company', '$description', '$requirements', '$tel', '$price', '$typeprice', '$contactinfo', '$date', '$email', '$submitter', '$usid',  '$town',  '$valid', '$filename', '0')");

    redirect_header('index.php', 1, _JOBS_JOBADDED);

    exit();
}

#######################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if (!isset($_POST['cid']) && isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}

if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

if (!isset($op)) {
    $op = '';
}

switch ($op) {
    case 'AddListingsOk':
        AddListingsOk($lid, $cid, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $usid, $town, $valid, $photo, $photo_size, $photo_name, $HTTP_POST_FILES);
        break;
    default:
        require XOOPS_ROOT_PATH . '/header.php';
        addindex($cid);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
}
