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
include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require XOOPS_ROOT_PATH . '/include/cp_functions.php';

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('eEmpregos');

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);

    exit();
}
if (file_exists('../language/' . $xoopsConfig['language'] . '/admin.php')) {
    include '../language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    include '../language/english/admin.php';
}
$myts = MyTextSanitizer::getInstance();

function eEmpregos_admin_menu()
{
    global $xoopsConfig, $xoopsModule;

    // language files

    $language = $xoopsConfig['language'];

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/system/language/' . $language . '/admin/blocksadmin.php')) {
        $language = 'english';
    }

    require_once XOOPS_ROOT_PATH . '/modules/system/language/' . $language . '/admin.php';

    require_once XOOPS_ROOT_PATH . '/modules/system/language/' . $language . '/admin/blocksadmin.php';

    // link to pref.php   add by Tom

    // link to myblocksadmin.php add by Tom Thanks GIJ

    echo "<h3 style='text-align:left;'>" . $xoopsModule->name() . "</h3>\n";

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='3' class='outer'><td class='even'>";

    echo '<a href="index.php">' . _JOBS_CONF . '</a>';

    echo "</td><td class='even'>";

    echo '<a href="map.php">' . _JOBS_GESTCAT . '</a>';

    echo "</td><td class='even'>";

    echo '<a href="pref.php">' . _MD_AM_PREF . '</a>';

    echo "</td><td class='even'>";

    echo '<a href="myblocksadmin.php">' . _AM_BADMIN . '</a>';

    CloseTable();

    echo '<br>';
}
