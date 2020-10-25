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
ini_set('arg_separator.output', '&amp;');
require XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php';
require XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

$mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

if ('eEmpregos' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';

    make_cblock();
} else {
    $xoopsOption['show_rblock'] = 0;

    require XOOPS_ROOT_PATH . '/header.php';
}

/**
 *  function index
 **/
function index()
{
    global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTpl, $moderated, $myts, $mytree, $souscat, $classm, $nbsouscat, $meta, $newann, $mid;

    $GLOBALS['xoopsOption']['template_main'] = 'eEmpregos_index.html';

    $xoopsTpl->assign('add_from', _JOBS_ADDFROM . ' ' . $xoopsConfig['sitename']);

    // Add Template assign  by Tom

    $xoopsTpl->assign('add_from_title', _JOBS_ADDFROM);

    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

    $xoopsTpl->assign('add_from_title', _JOBS_ADDFROM);

    if ('1' == $moderated) {
        $result = $xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='No'");

        [$propo] = $xoopsDB->fetchRow($result);

        $xoopsTpl->assign('moderated', true);

        if ($xoopsUser) {
            if ($xoopsUser->isAdmin()) {
                $xoopsTpl->assign('admin_block', _JOBS_ADMINCADRE);

                if (0 == $propo) {
                    $xoopsTpl->assign('confirm_ads', _JOBS_NO_JOBS);
                } else {
                    $xoopsTpl->assign('confirm_ads', _JOBS_THEREIS . " $propo  " . _JOBS_WAIT . '<br><a href="admin/index.php">' . _JOBS_SEEIT . '</a>');
                }
            }
        }
    }

    $result = $xoopsDB->query('select cid, title, img FROM ' . $xoopsDB->prefix('eEmpregos_categories') . " WHERE pid = 0 ORDER BY $classm") || die('Error');

    [$ncatp] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('eEmpregos_categories') . ' WHERE pid=0'));

    $count = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $a_cat = [];

        $cid = $myrow['cid'];

        $title = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

        $totallink = getTotalItems($myrow['cid'], _YES);

        $a_cat['image'] = "<img src='" . XOOPS_URL . '/modules/eEmpregos/images/cat/' . $myrow['img'] . "' align=\"middle\" border=\"0\" width=\"10\" height=\"10\"alt=\"\">";

        $a_cat['link'] = '<a href="index.php?pa=view&amp;cid=' . $myrow['cid'] . "\"><b>$title</b></a>";

        $a_cat['count'] = $totallink;

        if (1 == $souscat) {
            // get child category objects

            $arr = [];

            $arr = $mytree->getFirstChild($myrow['cid'], (string)$classm);

            $space = 0;

            $chcount = 1;

            $subcat = '';

            foreach ($arr as $ele) {
                $chtitle = htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5);

                if ($chcount > $nbsouscat) {
                    $subcat .= ', ...';

                    break;
                }

                if ($space > 0) {
                    $subcat .= ', ';
                }

                $subcat .= '<a href="index.php?pa=view&amp;cid=' . $ele['cid'] . '">' . $chtitle . '</a>';

                $space++;

                $chcount++;

                $a_cat['subcat'] = $subcat;
            }
        }

        $bis = ($ncatp + 1) / 2;

        $bis = (int)$bis;

        $a_cat['i'] = $count;

        $xoopsTpl->append('categories', $a_cat);

        $count++;
    }

    $xoopsTpl->assign('cat_count', $count - 1);

    [$ann] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='Yes'"));

    [$catt] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('eEmpregos_categories') . ''));

    $xoopsTpl->assign('total_listings', _JOBS_ACTUALY . " $ann " . _JOBS_LISTINGS . ' ' . _JOBS_INCAT . " $catt " . _JOBS_CAT2);

    if ('1' == $moderated) {
        $xoopsTpl->assign('total_confirm', _JOBS_AND . " $propo " . _JOBS_WAIT3);
    }

    if (1 == $newann) {
        showNew();
    }

    copyright();

    SupprClaDay();
}

/**
 *  function view (categories)
 * @param mixed $cid
 * @param mixed $debut
 **/
function view($cid, $debut)
{
    global $xoopsDB, $xoopsTpl, $xoopsConfig, $nb_affichage, $myts, $mytree, $imagecat, $classm, $meta;

    $GLOBALS['xoopsOption']['template_main'] = 'eEmpregos_category.html';

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/class/nav.php';

    $xoopsTpl->assign('add_from', _JOBS_ADDFROM . ' ' . $xoopsConfig['sitename']);

    // Add Template assign  by Tom

    $xoopsTpl->assign('add_from_title', _JOBS_ADDFROM);

    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

    $xoopsTpl->assign('add_listing', "<a href='addlisting.php?cid=$cid'>" . _JOBS_ADDLISTING2 . '</a>');

    $count = 0;

    if (!$debut) {
        $debut = 0;
    }

    $x = 0;

    $i = 0;

    $requete = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('eEmpregos_categories') . ' where  cid=' . $cid . '');

    [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $varid[$x] = $ccid;

    $varnom[$x] = $title;

    [$nbe] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " where valid='Yes' AND cid='$cid'"));

    $pagenav = new PageNav($nbe, $nb_affichage, $debut, "pa=view&amp;cid=$cid&amp;debut", '');

    if (0 != $pid) {
        $x = 1;

        while (0 != $pid) {
            $requete2 = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('eEmpregos_categories') . ' where cid=' . $pid . '');

            [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete2);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $varid[$x] = $ccid;

            $varnom[$x] = $title;

            $x++;
        }

        $x -= 1;
    }

    $subcats = '';

    while (-1 != $x) {
        $subcats .= ' &raquo; <a href="index.php?pa=view&amp;cid=' . $varid[$x] . '">' . $varnom[$x] . '</a>';

        $x -= 1;
    }

    $xoopsTpl->assign('nav_main', '<a href="index.php">' . _JOBS_MAIN . '</a>');

    $xoopsTpl->assign('nav_sub', $subcats);

    $xoopsTpl->assign('nav_subcount', $nbe);

    $subresult = $xoopsDB->query('select cid, title, img from ' . $xoopsDB->prefix('eEmpregos_categories') . " where pid=$cid ORDER BY $classm");

    $numrows = $xoopsDB->getRowsNum($subresult);

    if (0 != $numrows) {
        $scount = 0;

        $xoopsTpl->assign('availability', _JOBS_AVAILAB);

        while (list($ccid, $title, $img) = $xoopsDB->fetchRow($subresult)) {
            $a_cat = [];

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $numrows = getTotalItems($ccid, _YES);

            $a_cat['image'] = "<img src='" . XOOPS_URL . "/modules/eEmpregos/images/cat/$img' align='middle'>";

            $a_cat['link'] = '<a href="index.php?pa=view&amp;cid=' . $ccid . "\"><b>$title</b></a>";

            $a_cat['adcount'] = $numrows;

            $a_cat['i'] = $scount;

            $a_cat['new'] = categorynewgraphic($ccid);

            $scount++;

            if (4 == $scount) {
                $scount = 0;
            }

            $xoopsTpl->append('subcategories', $a_cat);
        }

        if (0 == $count) {
            $cols = 4 - $scount;
        }

        $xoopsTpl->assign('subcat_count', $scount - 1);
    }

    showViewListings($debut, $cid, $nb_affichage, $nbe);

    if (!isset($debut)) {
        $debut = 0;
    }

    //show render nav

    $xoopsTpl->assign('nav_page', $pagenav->renderNav());

    copyright();
}

/**
 *  function viewlistings
 * @param mixed $lid
 **/
function viewlistings($lid)
{
    global $xoopsDB, $xoopsConfig, $xoopsTpl, $xoopsUser, $monnaie, $claday, $ynprice, $myts, $meta, $nb_affichage;

    $GLOBALS['xoopsOption']['template_main'] = 'eEmpregos_item.html';

    // add for Nav by Tom

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/class/nav.php';

    $result = $xoopsDB->query('select lid, cid, title, type, company, description, requirements, tel, price, typeprice, contactinfo, date, email, submitter, usid, town, valid, photo, view FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE lid = '$lid'");

    $recordexist = $xoopsDB->getRowsNum($result);

    $xoopsTpl->assign('add_from', _JOBS_ADDFROM . ' ' . $xoopsConfig['sitename']);

    // Add Template assign  by Tom

    $xoopsTpl->assign('add_from_title', _JOBS_ADDFROM);

    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

    $xoopsTpl->assign('ad_exists', $recordexist);

    /* ---- add nav  by Tom ----- */

    $count = 0;

    $x = 0;

    $i = 0;

    $requete2 = $xoopsDB->query('select cid from ' . $xoopsDB->prefix('eEmpregos_listing') . ' where  lid=' . $lid . '');

    [$cid] = $xoopsDB->fetchRow($requete2);

    $requete = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('eEmpregos_categories') . ' where  cid=' . $cid . '');

    [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $varid[$x] = $ccid;

    $varnom[$x] = $title;

    [$nbe] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " where valid='Yes' AND cid='$cid'"));

    if (0 != $pid) {
        $x = 1;

        while (0 != $pid) {
            $requete2 = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('eEmpregos_categories') . ' where cid=' . $pid . '');

            [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete2);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $varid[$x] = $ccid;

            $varnom[$x] = $title;

            $x++;
        }

        $x -= 1;
    }

    $subcats = '';

    while (-1 != $x) {
        $subcats .= ' &raquo; <a href="index.php?pa=view&amp;cid=' . $varid[$x] . '">' . $varnom[$x] . '</a>';

        $x -= 1;
    }

    $xoopsTpl->assign('nav_main', '<a href="index.php">' . _JOBS_MAIN . '</a>');

    $xoopsTpl->assign('nav_sub', $subcats);

    $xoopsTpl->assign('nav_subcount', $nbe);

    /* ---- /nav ----- */

    if ($recordexist) {
        [$lid, $cid, $title, $type, $company, $description, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $usid, $town, $valid, $photo, $view] = $xoopsDB->fetchRow($result);

        //	Specification for Japan: move after for view count up judge

        //		$xoopsDB->queryf("UPDATE ".$xoopsDB->prefix("eEmpregos_listing")." SET view=view+1 WHERE lid = '$lid'");

        //		$useroffset = "";

        //    	if($xoopsUser)

        //    	{

        //			$timezone = $xoopsUser->timezone();

        //			if(isset($timezone))

        //				$useroffset = $xoopsUser->timezone();

        //			else

        //				$useroffset = $xoopsConfig['default_TZ'];

        //		}

        //	Specification for Japan: add  $viewcount_judge for view count up judge

        $viewcount_judge = true;

        $useroffset = '';

        if ($xoopsUser) {
            $timezone = $xoopsUser->timezone();

            if (isset($timezone)) {
                $useroffset = $xoopsUser->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }

            //	Specification for Japan: view count up judge

            if ((1 == $xoopsUser->getVar('uid')) || ($xoopsUser->getVar('uid') == $usid)) {
                $viewcount_judge = false;
            }
        }

        //	Specification for Japan: view count up judge

        if (true === $viewcount_judge) {
            $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('eEmpregos_listing') . " SET view=view+1 WHERE lid = '$lid'");
        }

        $date = ($useroffset * 3600) + $date;

        $date2 = $date + ($claday * 86400);

        $date = formatTimestamp($date, 's');

        $date2 = formatTimestamp($date2, 's');

        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

        $description = $myts->displayTarea($description);

        $requirements = $myts->displayTarea($requirements);

        $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

        $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

        $typeprice = htmlspecialchars($typeprice, ENT_QUOTES | ENT_HTML5);

        $contactinfo = $myts->displayTarea($contactinfo);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

        $imprD = "<a href=\"listing-p-f.php?op=ImprAnn&amp;lid=$lid\" target=\"_blank\"><img src=\"images/print.gif\" border=\"0\" alt=\"" . _JOBS_PRINT . '" width="15" height="11"></a>&nbsp;';

        if ($usid > 0) {
            $xoopsTpl->assign('submitter', _JOBS_FROM . " <a href='" . XOOPS_URL . "/userinfo.php?uid=$usid'>$submitter</a>");
        } else {
            $xoopsTpl->assign('submitter', _JOBS_FROM . " $submitter");
        }

        // Add PM by Tom

        //$contact_pm ="<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/pmlite.php?send2=1&amp;to_userid=".$usid."', 'pmlite', 450, 380);\"><img src=\"".XOOPS_URL."/images/icons/pm.gif\" alt=\"".sprintf(_SENDPMTO,$xoopsUser->getVar('uname'))."\"></a>";

        //$xoopsTpl->assign('contact_pm', "$contact_pm");

        $xoopsTpl->assign('read', "$view " . _JOBS_VIEW2);

        if ($xoopsUser) {
            $calusern = $xoopsUser->getVar('uid', 'E');

            if ($usid == $calusern) {
                $xoopsTpl->assign(
                    'modify',
                    "<a href=\"modeEmpregos.php?op=ModAnnonce&amp;lid=$lid\"><img src=\"images/modif.gif\" border=0 alt=\""
                                  . _JOBS_MODIFANN
                                  . "\"></a>&nbsp;<a href=\"modeEmpregos.php?op=AnnoncesDel&amp;lid=$lid\"><img src=\"images/del.gif\" border=0 alt=\""
                                  . _CAL_SUPPRANN
                                  . '"></a>'
                );
            }

            if ($xoopsUser->isAdmin()) {
                $xoopsTpl->assign('admin', "<a href=\"admin/index.php?op=AnnoncesModAnnonce&amp;lid=$lid\"><img src=\"images/modif.gif\" border=0 alt=\"" . _JOBS_MODADMIN . '"></a>');
            }
        }

        $xoopsTpl->assign('title', $title);

        $xoopsTpl->assign('type', $type);

        $xoopsTpl->assign('company', $company);

        $xoopsTpl->assign('description', $description);

        $xoopsTpl->assign('requirements', $requirements);

        $xoopsTpl->assign('company_head', _JOBS_COMPANY2);

        $xoopsTpl->assign('description_head', _JOBS_DESC2);

        $xoopsTpl->assign('requirements_head', _JOBS_REQUIRE);

        $xoopsTpl->assign('local_town', (string)$town);

        $xoopsTpl->assign('local_head', _JOBS_LOCAL);

        $xoopsTpl->assign('job_mustlogin', _JOBS_MUSTLOGIN);

        if (1 == $ynprice && $price > 0) {
            // Add Template assign  by Tom

            $xoopsTpl->assign('price', '<b>' . _JOBS_PRICE2 . "</b> $price $monnaie - $typeprice");

            $xoopsTpl->assign('price_head', _JOBS_PRICE2);

            $xoopsTpl->assign('price_price', "$monnaie $price");

            $xoopsTpl->assign('price_typeprice', (string)$typeprice);
        } elseif (1 == $ynprice) {
            $xoopsTpl->assign('price_head', _JOBS_PRICE2);

            $xoopsTpl->assign('price_price', '');

            $xoopsTpl->assign('price_typeprice', (string)$typeprice);
        }

        $xoopsTpl->assign('contactinfo', (string)$contactinfo);

        $xoopsTpl->assign('contactinfo_head', _JOBS_CONTACTINFO);

        $contact = '<b>' . _JOBS_CONTACT . "</b> <a href=\"contact.php?lid=$lid\">" . _JOBS_BYMAIL2 . '</a>';

        // Add Template assign  by Tom

        $xoopsTpl->assign('contact_head', _JOBS_CONTACT);

        $xoopsTpl->assign('contact_email', "<a href=\"contact.php?lid=$lid\">" . _JOBS_BYMAIL2 . '</a>');

        //if ($tel) {

        //	$contact .= "<br><b>"._JOBS_TEL."</b> $tel";

        // Add Template assign  by Tom

        //	$xoopsTpl->assign('contact_tel_head', _JOBS_TEL);

        //	$xoopsTpl->assign('contact_tel', "$tel");

        //}

        // Layout CHG by Tom

        //$contact .= "<br>";

        //if ($town) {

        $contact .= '<br><b>' . _JOBS_TOWN . "</b> $town";

        // Add Template assign  by Tom

        //	$xoopsTpl->assign('local_town', "$town");

        //}

        //$xoopsTpl->assign('contact', $contact);

        // Add Template assign  by Tom

        //$xoopsTpl->assign('local_head', _JOBS_LOCAL);

        if ($photo) {
            // add 'alt=' by Tom

            $xoopsTpl->assign('photo', "<img src=\"logo_images/$photo\" alt=\"$title\">");
        }

        $xoopsTpl->assign('date', _JOBS_DATE2 . " $date " . _JOBS_DISPO . " $date2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $imprD");
    } else {
        $xoopsTpl->assign('no_ad', _JOBS_NOCLAS);
    }

    $result8 = $xoopsDB->query('select title from ' . $xoopsDB->prefix('eEmpregos_categories') . " where cid=$cid");

    [$ctitle] = $xoopsDB->fetchRow($result8);

    $xoopsTpl->assign('link_main', '<a href="../eEmpregos/">' . _JOBS_MAIN . '</a>');

    $xoopsTpl->assign('link_cat', "<a href=\"index.php?pa=view&amp;cid=$cid\">" . _JOBS_GORUB . " $ctitle</a>");
}

/**
 *  function categorynewgraphic
 * @param mixed $cat
 *
 * @return string
 * @return string
 */
function categorynewgraphic($cat)
{
    global $xoopsDB;

    $newresult = $xoopsDB->query('select date from ' . $xoopsDB->prefix('eEmpregos_listing') . " where cid=$cat and valid = 'Yes' order by date desc limit 1");

    [$timeann] = $xoopsDB->fetchRow($newresult);

    $count = 1;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $timeann) {
        return '<img src="' . XOOPS_URL . '/modules/eEmpregos/images/newred.gif">';
    }
}

######################################################

$pa = $_GET['pa'] ?? '';
$lid = $_GET['lid'] ?? '';
$cid = $_GET['cid'] ?? '';
$debut = $_GET['debut'] ?? '';

/*
if (!isset($pa))
    $pa = '';
if (!isset($debut))
    $debut = '';
*/
switch ($pa) {
    case 'viewlistings':
        viewlistings($lid);
        break;
    case 'view':
        view($cid, $debut);
        break;
    //    case "views":
    //    views($sid, $debut);
    //    break;

    default:
        index();
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';
