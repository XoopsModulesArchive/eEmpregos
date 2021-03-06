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

#  function AnnoncesNewCat
#####################################################
function AnnoncesNewCat($cat)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $classm, $ynprice, $myts;

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

    xoops_cp_header();

    OpenTable();

    ShowImg();

    echo '<form method="post" action="gest-cat.php" name="imcat"><input type="hidden" name="op" value="AnnoncesAddCat">
	    <b>' . _JOBS_ADDSUBCAT . '</b></font><br><br>
		<TABLE BORDER=0>
    <TR>
      <TD>' . _JOBS_CATNAME . ' </TD><TD colspan=2><input type="text" name="title" size="30" maxlength="100">&nbsp; ' . _JOBS_IN . ' &nbsp;';

    $result = $xoopsDB->query('select pid, title, img, ordre from ' . $xoopsDB->prefix('eEmpregos_categories') . " where cid=$cat");

    [$pid, $title, $imgs, $ordre] = $xoopsDB->fetchRow($result);

    $mytree->makeMySelBox('title', 'title', $cat, 1);

    echo '</TD>
	</TR>
    <TR>
      <TD>' . _JOBS_IMGCAT . '  </TD><TD colspan=2><SELECT NAME="img" onChange="showimage()">';

    $rep = XOOPS_ROOT_PATH . '/modules/eEmpregos/images/cat';

    $handle = opendir($rep);

    while ($file = readdir($handle)) {
        $filelist[] = $file;
    }

    asort($filelist);

    while (list($key, $file) = each($filelist)) {
        if (!preg_match('.gif|.jpg|.png', $file)) {
            if ('.' == $file || '..' == $file) {
                $a = 1;
            }
        } else {
            if ('default.gif' == $file) {
                echo "<option value=$file selected>$file</option>";
            } else {
                echo "<option value=$file>$file</option>";
            }
        }
    }

    echo '</select>&nbsp;&nbsp;<img src="' . XOOPS_URL . '/modules/eEmpregos/images/cat/default.gif" name="avatar" align="absmiddle"> </TD></TR><TR><TD>&nbsp;</TD><TD colspan=2>' . _JOBS_REPIMGCAT . ' /modules/eEmpregos/images/cat/</TD></TR>';

    if (1 == $ynprice) {
        echo '<TR><TD>' . _JOBS_DISPLPRICE2 . ' </TD><TD colspan=2><input type="radio" name="affprice" value="1" checked>' . _JOBS_OUI . '&nbsp;&nbsp; <input type="radio" name="affprice" value="0">' . _JOBS_NON . ' (' . _JOBS_INTHISCAT . ')</TD></TR>';
    }

    if ('ordre' == $classm) {
        echo '<TR><TD>' . _JOBS_ORDRE . ' </TD><TD><input type="text" name="ordre" size="4"></TD><TD><input type="submit" value="' . _JOBS_ADD . '"></TD></TR>';
    } else {
        echo '<TR><TD colspan=3><input type="submit" value="' . _JOBS_ADD . '"></TD></TR>';
    }

    echo '</TABLE>
	    </form>';

    echo '<br>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModCat
#####################################################
function AnnoncesModCat($cat)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $classm, $ynprice, $myts;

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

    xoops_cp_header();

    OpenTable();

    ShowImg();

    echo '<b>' . _JOBS_MODIFCAT . '</b><br><br>';

    $result = $xoopsDB->query('select pid, title, img, ordre, affprice from ' . $xoopsDB->prefix('eEmpregos_categories') . " where cid=$cat");

    [$pid, $title, $imgs, $ordre, $affprice] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    echo '<form action="gest-cat.php" method="post" name="imcat">
		<table border="0"><TR>
	<TD>' . _JOBS_CATNAME . "   </TD><TD><input type=\"text\" name=\"title\" value=\"$title\" size=\"30\" maxlength=\"50\">&nbsp; " . _JOBS_IN . ' &nbsp;';

    $mytree->makeMySelBox('title', 'title', $pid, 1);

    echo '</TD></TR><TR>
	<TD>' . _JOBS_IMGCAT . '  </TD><TD><SELECT NAME="img" onChange="showimage()">';

    $rep = XOOPS_ROOT_PATH . '/modules/eEmpregos/images/cat';

    $handle = opendir($rep);

    while ($file = readdir($handle)) {
        $filelist[] = $file;
    }

    asort($filelist);

    while (list($key, $file) = each($filelist)) {
        if (!preg_match('.gif|.jpg|.png', $file)) {
            if ('.' == $file || '..' == $file) {
                $a = 1;
            }
        } else {
            if ($file == $imgs) {
                echo "<option value=$file selected>$file</option>";
            } else {
                echo "<option value=$file>$file</option>";
            }
        }
    }

    echo '</select>&nbsp;&nbsp;<img src="' . XOOPS_URL . "/modules/eEmpregos/images/cat/$imgs\" name=\"avatar\" align=\"absmiddle\"> </TD></TR><TR><TD>&nbsp;</TD><TD>" . _JOBS_REPIMGCAT . ' /modules/eEmpregos/images/cat/</TD></TR>';

    if (1 == $ynprice) {
        echo '<TR><TD>' . _JOBS_DISPLPRICE2 . ' </TD><TD colspan=2><input type="radio" name="affprice" value="1"';

        if ('1' == $affprice) {
            echo 'checked';
        }

        echo '>' . _JOBS_OUI . '&nbsp;&nbsp; <input type="radio" name="affprice" value="0"';

        if ('0' == $affprice) {
            echo 'checked';
        }

        echo '>' . _JOBS_NON . ' (' . _JOBS_INTHISCAT . ')</TD></TR>';
    }

    if ('ordre' == $classm) {
        echo '<TR><TD>' . _JOBS_ORDRE . " </TD><TD><input type=\"text\" name=\"ordre\" size=\"4\" value=\"$ordre\"></TD></TR>";
    } else {
        echo "<input type=\"hidden\" name=\"ordre\" value=\"$ordre\">";
    }

    echo '</table><P>';

    echo "<input type=\"hidden\" name=\"cidd\" value=\"$cat\">"
         . '<input type="hidden" name="op" value="AnnoncesModCatS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _JOBS_SAVMOD
         . '"></form></td><td>'
         . '<form action="gest-cat.php" method="post">'
         . "<input type=\"hidden\" name=\"cid\" value=\"$cat\">"
         . '<input type="hidden" name="op" value="AnoncesDelCat">'
         . '<input type="submit" value="'
         . _JOBS_DEL
         . '"></form></td></tr></table>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModCatS
#####################################################
function AnnoncesModCatS($cidd, $cid, $img, $title, $ordre, $affprice)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $title = $myts->addSlashes($title);

    $xoopsDB->query('update ' . $xoopsDB->prefix('eEmpregos_categories') . " set title='$title', pid='$cid', img='$img', ordre='$ordre', affprice='$affprice' where cid=$cidd");

    redirect_header('map.php', 1, _JOBS_CATSMOD);

    exit();
}

#  function AnnoncesAddCat
#####################################################
function AnnoncesAddCat($title, $img, $ordre, $affprice)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $title = $myts->addSlashes($title);

    if ('' == $title) {
        $title = '! ! ? ! !';
    }

    $xoopsDB->query('insert into ' . $xoopsDB->prefix('eEmpregos_categories') . "(pid, title, img, ordre, affprice) values (0, '$title', '$img', '$ordre', '$affprice')");

    redirect_header('map.php', 1, _JOBS_CATADD);

    exit();
}

#  function AnoncesDelCat
#####################################################
function AnoncesDelCat($cid, $ok = 0)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule;

    if (1 == (int)$ok) {
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

        $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('eEmpregos_categories') . " where cid=$cid or pid=$cid");

        $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('eEmpregos_listing') . " where cid=$cid");

        redirect_header('map.php', 1, _JOBS_CATDEL);

        exit();
    }

    xoops_cp_header();

    OpenTable();

    echo '<br><center><b>' . _JOBS_SURDELCAT . '</b><br><br>';

    echo "[ <a href=\"gest-cat.php?op=AnoncesDelCat&cid=$cid&ok=1\">" . _JOBS_OUI . '</a> | <a href="map.php">' . _JOBS_NON . '</a> ]<br><br>';

    CloseTable();

    xoops_cp_footer();
}

#####################################################
$ordre = null;
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$ok = $_GET['ok'] ?? '';

if (!isset($_POST['cid']) && isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

switch ($op) {
    case 'AnnoncesNewCat':
        AnnoncesNewCat($cid);
        break;
    case 'AnnoncesAddCat':
        AnnoncesAddCat($title, $cid, $img, $ordre, $affprice);
        break;
    case 'AnoncesDelCat':
        AnoncesDelCat($cid, $ok);
        break;
    case 'AnnoncesModCat':
        AnnoncesModCat($cid);
        break;
    case 'AnnoncesModCatS':
        AnnoncesModCatS($cidd, $cid, $img, $title, $ordre, $affprice);
        break;
    default:
        Index();
        break;
}
