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

xoops_header();
$lid = $_GET['lid'];

global $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsDB, $xoops_footer, $xoopsLogger;
$currenttheme = getTheme();

$result = $xoopsDB->query('select photo FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE lid = '$lid'");
$recordexist = $xoopsDB->getRowsNum($result);

if ($recordexist) {
    [$photo] = $xoopsDB->fetchRow($result);

    echo "<center><img src=\"logo_images/$photo\" border=0></center>";
}

echo "<center><table><tr><td><a href=#  onClick='window.close()'>" . _JOBS_CLOSEF . '</a></td></tr></table></center>';

xoops_footer();
