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

function eEmpregos_show()
{
    global $xoopsDB, $xoopsConfig, $ynprice, $myts;

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    $block['title'] = _MB_JOBS_TITLE;

    require XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php';

    $query = 'select lid, title, type FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='Yes' ORDER BY date DESC LIMIT $newclassifieds";

    $result = $xoopsDB->query($query);

    while (list($lid, $title, $type) = $xoopsDB->fetchRow($result)) {
        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($title) >= 14) {
                $title = mb_substr($title, 0, 18) . '...';
            }
        }

        $a_item['type'] = $type;

        $a_item['link'] = '<a href="' . XOOPS_URL . "/modules/eEmpregos/index.php?pa=viewlistings&lid=$lid\">$title</a>";

        $block['items'][] = $a_item;
    }

    $block['link'] = '<a href="' . XOOPS_URL . '/modules/eEmpregos/">' . _MB_JOBS_ALLANN2 . '</a></div>';

    return $block;
}
