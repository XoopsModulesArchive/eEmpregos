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
//  ------------------------------------------------------------------------ ///
include 'admin_header.php';
require XOOPS_ROOT_PATH . '/modules/eEmpregos/cache/config.php';
require XOOPS_ROOT_PATH . '/modules/eEmpregos/include/functions.php';

require_once XOOPS_ROOT_PATH . '/modules/eEmpregos/class/arbre.php';
$mytree = new XoopsArbre($xoopsDB->prefix('eEmpregos_categories'), 'cid', 'pid');

global $mytree, $classm, $xoopsDB;

xoops_cp_header();
eEmpregos_admin_menu();

OpenTable();
echo '<b>' . _JOBS_GESTCAT . '</b><br><br>';
echo '<a href="gest-cat.php?op=AnnoncesNewCat&amp;cid=0"><img src="' . XOOPS_URL . '/modules/eEmpregos/images/plus.gif" border=0 width=10 height=10  alt="' . _JOBS_ADDSUBCAT . '"></a> ' . _JOBS_ADDCATPRINC . '<br><br>';

$mytree->makeMapSelBox('title', (string)$classm);

echo '<p><HR>';

echo _JOBS_HELP1 . ' <p>';

if ('ordre' == $classm) {
    echo _JOBS_HELP2 . ' <p>';
}
CloseTable();
xoops_cp_footer();
