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

function Conf()
{
    global $xoopsUser, $xoopsConfig, $xoopsModule;

    global $annoadd, $justuser, $nb_affichage, $monnaie, $newclassifieds, $moderated, $photomax, $claday, $affichebloc, $countday, $ynprice, $souscat, $classm, $nbsouscat, $newann;

    xoops_cp_header();

    eEmpregos_admin_menu();

    OpenTable();

    echo '<b>' . _JOBS_CONFMYA . '</b><br><br>';

    echo '<form action="pref.php?pa=ConfOk" method=post>';

    echo '<table width = "100%" border = "0"><tr>
	<td>' . _JOBS_JOBOCANPOST . ' </td>
	<td><input type="radio" name="annoaddS" value="1"';

    if ('1' == $annoadd) {
        echo 'checked';
    }

    echo '>' . _JOBS_OUI . '&nbsp;&nbsp; <input type="radio" name="annoaddS" value="0"';

    if ('0' == $annoadd) {
        echo 'checked';
    }

    echo '>' . _JOBS_NON . '</td>
	</tr><tr>
	<td>' . _JOBS_PERPAGE . " </td>
	<td><select name=nb_affichageS>
	<option value=$nb_affichage selected>$nb_affichage</option>
	<option value=10>10</option>
	<option value=15>15</option>
	<option value=20>20</option>
	<option value=25>25</option>
	<option value=30>30</option>
	<option value=50>50</option>
	</select></td>
	</tr><tr>
	<td>" . _JOBS_MONEY . " </td>
	<td><input type=\"text\" name=\"monnaieS\" value=\"$monnaie\" size=\"3\" maxlength=\"2\"></td>
	</tr>
	<tr>
	<td>" . _JOBS_VIEWNEWCLASS . ' </td>
	<td><input type="radio" name="newannS" value="1"';

    if ('1' == $newann) {
        echo 'checked';
    }

    echo '>' . _JOBS_OUI . '&nbsp;&nbsp; <input type="radio" name="newannS" value="0"';

    if ('0' == $newann) {
        echo 'checked';
    }

    echo '>' . _JOBS_NON . ' (' . _JOBS_ONHOME . ')</td>
	</tr>
	<tr>
	<td>' . _JOBS_NUMNEW . " </td>
	<td><select name=newclassifiedsS>
	<option value=$newclassifieds>$newclassifieds</option>
	<option value=5>5</option>
	<option value=10>10</option>
	<option value=15>15</option>
	<option value=20>20</option>
	<option value=25>25</option>
	<option value=30>30</option>
	<option value=50>50</option>
	</select> (" . _JOBS_ONHOME . ')</td>
	</tr>
	<tr>
	<td>' . _JOBS_MODERAT . ' </td>
	<td><input type="radio" name="moderatedS" value="1"';

    if ('1' == $moderated) {
        echo 'checked';
    }

    echo '>' . _JOBS_OUI . '&nbsp;&nbsp; <input type="radio" name="moderatedS" value="0"';

    if ('0' == $moderated) {
        echo 'checked';
    }

    echo '>' . _JOBS_NON . '</td>
	</tr>
	<tr>
	<td>' . _CAL_MAXIIMGS . " </td>
	<td><input type=\"text\" name=\"photomaxS\" value=\"$photomax\" size=\"10\"> (" . _JOBS_INOCTET . ')</td>
	</tr>
	<tr>
	<td>' . _JOBS_TIMEANN . " </td>
	<td><input type=\"text\" name=\"cladayS\" value=\"$claday\" size=\"4\" maxlength=\"4\"> (" . _JOBS_INDAYS . ')</td>
	</tr>
	<tr>
	<td>' . _JOBS_TYPEBLOC . ' </td>
	<td><SELECT NAME="afficheblocS">
	<OPTION VALUE="1"';

    if ('1' == $affichebloc) {
        echo ' selected';
    }

    echo '> ' . _JOBS_LASTTEN . '
	<OPTION VALUE="2"';

    if ('2' == $affichebloc) {
        echo ' selected';
    }

    echo '> ' . _JOBS_JOBRAND . '
	</SELECT></td>
	</tr>
	<tr>
	<td>' . _JOBS_NEWTIME . " </td>
	<td><select name=countdayS>
	<option value=$countday>$countday</option>
	<option value=1>1</option>
	<option value=2>2</option>
	<option value=3>3</option>
	<option value=4>4</option>
	<option value=5>5</option>
	<option value=6>6</option>
	<option value=7>7</option>
	<option value=8>8</option>
	<option value=9>9</option>
	<option value=10>10</option>
	</select> (" . _JOBS_INDAYS . ')</td>
	</tr>
	<tr>
	<td>' . _JOBS_DISPLPRICE . ' </td>
	<td><input type="radio" name="ynpriceS" value="1"';

    if ('1' == $ynprice) {
        echo 'checked';
    }

    echo '>' . _JOBS_OUI . '&nbsp;&nbsp; <input type="radio" name="ynpriceS" value="0"';

    if ('0' == $ynprice) {
        echo 'checked';
    }

    echo '>' . _JOBS_NON . '</td>
	</tr>
	<tr>
	<td>' . _JOBS_DISPLSUBCAT . ' </td>
	<td><input type="radio" name="souscatS" value="1"';

    if ('1' == $souscat) {
        echo 'checked';
    }

    echo '>' . _JOBS_OUI . '&nbsp;&nbsp; <input type="radio" name="souscatS" value="0"';

    if ('0' == $souscat) {
        echo 'checked';
    }

    echo '>' . _JOBS_NON . ' (' . _JOBS_ONHOME . ')</td>
	</tr><tr>
	<td>' . _JOBS_NBDISPLSUBCAT . " </td>
	<td><input type=\"text\" name=\"nbsouscatS\" value=\"$nbsouscat\" size=\"4\" maxlength=\"4\"> (" . _JOBS_IF . ' "' . _JOBS_DISPLSUBCAT . '" ' . _JOBS_ISAT . ' "' . _JOBS_OUI . '")</td>
	</tr>
	<tr>
	<td>' . _JOBS_ORDRECLASS . ' </td>
	<td><select name=classmS>
	<option value=title';

    if ('title' == $classm) {
        echo ' selected';
    }

    echo '>' . _JOBS_ORDREALPHA . '</option>
	<option value=ordre';

    if ('ordre' == $classm) {
        echo ' selected';
    }

    echo '>' . _JOBS_ORDREPERSO . '</option>
	</select></td>
	</tr>
	</table><br>
	<input type="submit" value="' . _JOBS_SAVMOD . '">
	</form>';

    CloseTable();

    xoops_cp_footer();
}

function ConfOK($annoaddS, $nb_affichageS, $monnaieS, $newclassifiedsS, $moderatedS, $photomaxS, $cladayS, $afficheblocS, $countdayS, $ynpriceS, $souscatS, $classmS, $nbsouscatS, $newannS)
{
    $file = fopen(XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php', 'wb');

    $content = "<?php\n";

    $content .= "// \n";

    $content .= "// ------------------------------------------------------------------------- //\n";

    $content .= "//               E-Xoops: Content Management for the Masses                  //\n";

    $content .= "//                       < http://www.e-xoops.com >                          //\n";

    $content .= "// ------------------------------------------------------------------------- //\n";

    $content .= "// Original Author: Pascal Le Boustouller\n";

    $content .= "// Author Website : pascal.e-xoops@perso-search.com\n";

    $content .= "// Licence Type   : GPL\n";

    $content .= "// ------------------------------------------------------------------------- //\n";

    $content .= "\$annoadd = $annoaddS;\n\n";

    $content .= "\$nb_affichage = $nb_affichageS;\n\n";

    $content .= "\$monnaie = \"$monnaieS\";\n\n";

    $content .= "\$newclassifieds = $newclassifiedsS;\n\n";

    $content .= "\$moderated = $moderatedS;\n\n";

    $content .= "\$photomax = $photomaxS;\n\n";

    $content .= "\$claday = $cladayS;\n\n";

    $content .= "\$affichebloc = $afficheblocS;\n\n";

    $content .= "\$countday = $countdayS;\n\n";

    $content .= "\$ynprice = $ynpriceS;\n\n";

    $content .= "\$souscat = $souscatS;\n\n";

    $content .= "\$nbsouscat = \"$nbsouscatS\";\n\n";

    $content .= "\$classm = \"$classmS\";\n\n";

    $content .= "\$newann = \"$newannS\";\n\n";

    $content .= '?>';

    fwrite($file, $content);

    fclose($file);

    redirect_header('pref.php', 1, _JOBS_CONFSAVE);

    exit();
}

#######################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$pa = $_GET['pa'] ?? '';

switch ($pa) {
    case 'ConfOk':
        ConfOK($annoaddS, $nb_affichageS, $monnaieS, $newclassifiedsS, $moderatedS, $photomaxS, $cladayS, $afficheblocS, $countdayS, $ynpriceS, $souscatS, $classmS, $nbsouscatS, $newannS);
        break;
    default:
        Conf();
        break;
}
