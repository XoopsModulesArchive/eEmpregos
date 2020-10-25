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

function showNew()
{
    global $myts, $xoopsDB, $xoopsTpl, $mf, $xoopsUser, $newclassifieds, $monnaie, $ynprice;

    require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

    // Add 'typeprice' by Tom

    $result = $xoopsDB->query('select lid, title, type, company, price, typeprice, date, town, valid, photo, view FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='Yes' ORDER BY date DESC LIMIT $newclassifieds");

    if ($result) {
        $xoopsTpl->assign('last_head', _JOBS_THE . " $newclassifieds " . _JOBS_LASTADD);

        $xoopsTpl->assign('last_head_title', _JOBS_TITLE);

        $xoopsTpl->assign('last_head_company', _JOBS_COMPANY);

        $xoopsTpl->assign('last_head_price', _JOBS_PRICE);

        $xoopsTpl->assign('last_head_date', _JOBS_DATE);

        $xoopsTpl->assign('last_head_local', _JOBS_LOCAL2);

        $xoopsTpl->assign('last_head_views', _JOBS_VIEW);

        $xoopsTpl->assign('last_head_photo', _JOBS_PHOTO);

        $rank = 1;

        // Add $typeprice by Tom

        while (list($lid, $title, $type, $company, $price, $typeprice, $date, $town, $valid, $photo, $vu) = $xoopsDB->fetchRow($result)) {
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

            $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

            $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

            $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

            $a_item = [];

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

            $date = formatTimestamp($date, 's');

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    $a_item['admin'] = "<a href='admin/index.php?op=AnnoncesModAnnonce&amp;lid=$lid'><img src='images/modif.gif' border=0 alt=\"" . _JOBS_MODADMIN . '"></a>';
                }
            }

            $a_item['title'] = "<a href='index.php?pa=viewlistings&amp;lid=$lid'>$title</a>";

            $a_item['company'] = $company;

            if (1 == $ynprice) {
                if ($price > 0) {
                    $a_item['price'] = "$monnaie $price";

                    // Add $price_typeprice by Tom

                    $a_item['price_typeprice'] = (string)$typeprice;
                } else {
                    $a_item['price'] = '';

                    $a_item['price_typeprice'] = (string)$typeprice;
                }
            }

            $a_item['date'] = $date;

            $a_item['local'] = '';

            if ($town) {
                $a_item['local'] .= $town;
            }

            if ($photo) {
                $a_item['photo'] = "<a href=\"javascript:CLA('display-image.php?lid=$lid')\"><img src=\"images/photo.gif\" border=0 width=15 height=11 alt='" . _JOBS_IMGPISP . "'></a>";
            }

            $a_item['views'] = $vu;

            $rank++;

            $xoopsTpl->append('items', $a_item);
        }
    }
}

function showViewListings($debut, $cid, $nb_affichage, $nbe)
{
    global $xoopsDB, $xoopsConfig, $xoopsTpl, $monnaie, $newclassifieds, $xoopsUser, $ynprice, $myts;

    // Add 'typeprice' by Tom

    //$result3=$xoopsDB->query("select lid, cid, title, type, company, price, date, town, valid, photo, view from ".$xoopsDB->prefix("eEmpregos_listing")." where  valid='yes' AND cid=$cid order by date DESC  LIMIT $debut,$nb_affichage");

    $result3 = $xoopsDB->query('select lid, cid, title, type, company, price, typeprice, date, town, valid, photo, view from ' . $xoopsDB->prefix('eEmpregos_listing') . " where  valid='yes' AND cid=$cid order by date DESC  LIMIT $debut,$nb_affichage");

    $xoopsTpl->assign('data_rows', $nbe);

    if ('0' == $nbe) {
        $xoopsTpl->assign('no_data', _JOBS_NOANNINCAT);
    } else {
        $xoopsTpl->assign('last_head', _JOBS_THE . " $newclassifieds " . _JOBS_LASTADD);

        $xoopsTpl->assign('last_head_title', _JOBS_TITLE);

        $xoopsTpl->assign('last_head_company', _JOBS_COMPANY);

        $xoopsTpl->assign('last_head_price', _JOBS_PRICE);

        $xoopsTpl->assign('last_head_date', _JOBS_DATE);

        $xoopsTpl->assign('last_head_local', _JOBS_LOCAL2);

        $xoopsTpl->assign('last_head_views', _JOBS_VIEW);

        $xoopsTpl->assign('last_head_photo', _JOBS_PHOTO);

        $rank = 1;

        // Add 'typeprice' by Tom

        //while(list($lid, $cid, $title, $type, $company, $price, $date, $town, $valid, $photo, $vu)=$xoopsDB->fetchRow($result3))

        while (list($lid, $cid, $title, $type, $company, $price, $typeprice, $date, $town, $valid, $photo, $vu) = $xoopsDB->fetchRow($result3)) {
            $a_item = [];

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

            $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

            $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

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

            $date = ($useroffset * 3600) + $date;

            $date = formatTimestamp($date, 's');

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    $a_item['admin'] = "<a href='admin/index.php?op=AnnoncesModAnnonce&amp;lid=$lid'><img src='images/modif.gif' border=0 alt=\"" . _JOBS_MODADMIN . '"></a>';
                }
            }

            $a_item['type'] = $type;

            $a_item['title'] = "<a href='index.php?pa=viewlistings&amp;lid=$lid'>$title</a>";

            $a_item['company'] = $company;

            if (1 == $ynprice) {
                if ($price > 0) {
                    $a_item['price'] = "$monnaie $price";

                    // Add $price_typeprice by Tom

                    $a_item['price_typeprice'] = (string)$typeprice;
                } else {
                    $a_item['price'] = '';

                    $a_item['price_typeprice'] = (string)$typeprice;
                }
            }

            $a_item['date'] = $date;

            $a_item['local'] = '';

            if ($town) {
                $a_item['local'] .= $town;
            }

            if ($photo) {
                $a_item['photo'] = "<a href=\"javascript:CLA('display-image.php?lid=$lid')\"><img src=\"images/photo.gif\" border=0 width=15 height=11 alt='" . _JOBS_IMGPISP . "'></a>";
            }

            $a_item['views'] = $vu;

            $rank++;

            $xoopsTpl->append('items', $a_item);
        }
    }
}

function SupprClaDay()
{
    //for xoops2//

    include './cache/config.php';

    global $xoopsDB, $claday, $xoopsConfig, $myts, $meta;

    $datenow = time();

    $result5 = $xoopsDB->query('select lid, title, type, company, description, requirements, contactinfo, date, email, submitter, photo, view FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='Yes'");

    while (list($lids, $title, $type, $company, $description, $requirements, $contactinfo, $dateann, $email, $submitter, $photo, $lu) = $xoopsDB->fetchRow($result5)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $company = htmlspecialchars($company, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $requirements = htmlspecialchars($requirements, ENT_QUOTES | ENT_HTML5);

        $contactinfo = htmlspecialchars($contactinfo, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $supprdate = $dateann + ($claday * 86400);

        if ($supprdate < $datenow) {
            //for xoops2//	$xoopsDB->query("delete from ".$xoopsDB->prefix("eEmpregos_listing")." where lid='$lids'");

            $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('eEmpregos_listing') . " where lid='$lids'");

            $destination = XOOPS_ROOT_PATH . '/modules/eEmpregos/logo_images';

            if ($photo) {
                if (file_exists("$destination/$photo")) {
                    unlink("$destination/$photo");
                }
            }

            //	Specification for Japan:

            //	$message = ""._JOBS_HELLO." $submitter,\n\n"._JOBS_STOP2."\n $type : $title\n $description\n"._JOBS_STOP3."\n\n"._JOBS_VU." $lu "._JOBS_VU2."\n\n"._JOBS_OTHER." ".XOOPS_URL."/modules/eEmpregos\n\n"._JOBS_THANK."\n\n"._JOBS_TEAM." ".$meta['title']."\n".XOOPS_URL."";

            if ($email) {
                $message = "$submitter "
                           . _JOBS_HELLO
                           . " \n\n"
                           . _JOBS_STOP2
                           . "\n $type : $title\n $description\n"
                           . _JOBS_STOP3
                           . "\n\n"
                           . _JOBS_VU
                           . " $lu "
                           . _JOBS_VU2
                           . "\n\n"
                           . _JOBS_OTHER
                           . ' '
                           . XOOPS_URL
                           . "/modules/eEmpregos\n\n"
                           . _JOBS_THANK
                           . "\n\n"
                           . _JOBS_TEAM
                           . ' '
                           . $meta['title']
                           . "\n"
                           . XOOPS_URL
                           . '';

                $subject = '' . _JOBS_STOP . '';

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
        }
    }
}

function copyright()
{
    global $xoopsTpl;

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/xoops_version.php';

    $cr_developed = 'eEmpregos ' . $modversion['version'] . ' ' . _JOBS_FOR . ' Xoops ' . _JOBS_CREATBY . ' <a href="http://www.searchsouthington.com/" target="_blank">John Mordo</a>';

    $cr_redesigned = 'redesigned from myads 2.04';

    if (isset($GLOBALS['xoopsOption']['template_main'])) {
        $xoopsTpl->assign('cr_developed', $cr_developed);

        $xoopsTpl->assign('cr_redesigned', $cr_redesigned);
    }
}

function getTotalItems($sel_id, $status = '')
{
    global $xoopsDB, $mytree;

    $pfx = $xoopsDB->prefix('eEmpregos_listing');

    $count = 1;

    $arr = [];

    $status_q = '';

    if ($status) {
        if (_YES == $status) {
            $status_q = " and valid='Yes'";
        } else {
            $status_q = " and valid='No'";
        }
    }

    $query = "select lid from $pfx where cid=" . $sel_id . (string)$status_q;

    $result = $xoopsDB->query($query);

    $count = $xoopsDB->getRowsNum($result);

    $arr = $mytree->getAllChildId($sel_id);

    $size = count($arr);

    for ($i = 0; $i < $size; $i++) {
        $query2 = "select lid from $pfx where cid=" . $arr[$i] . (string)$status_q;

        $result2 = $xoopsDB->query($query2);

        $count += $xoopsDB->getRowsNum($result2);
    }

    return $count;
}

function ShowImg()
{
    echo "<script type=\"text/javascript\">\n";

    echo "<!--\n\n";

    echo "function showimage() {\n";

    echo "if (!document.images)\n";

    echo "return\n";

    echo "document.images.avatar.src=\n";

    echo "'" . XOOPS_URL . "/modules/eEmpregos/images/cat/' + document.imcat.img.options[document.imcat.img.selectedIndex].value\n";

    echo "}\n\n";

    echo "//-->\n";

    echo "</script>\n";
}
