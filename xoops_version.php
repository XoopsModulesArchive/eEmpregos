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
$modversion['name'] = _MI_JOBS_NAME;
$modversion['version'] = '1.00';
$modversion['description'] = _MI_JOBS_DESC;
$modversion['credits'] = 'Módulo e-Empregos para Xoops';
$modversion['author'] = 'Gustavo S. Villa';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'eEmpregos.gif';
$modversion['dirname'] = 'eEmpregos';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Templates
$modversion['templates'][1]['file'] = 'eEmpregos_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'eEmpregos_adlist.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'eEmpregos_category.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'eEmpregos_item.html';
$modversion['templates'][4]['description'] = '';

// Blocks
$modversion['blocks'][1]['file'] = 'eEmpregos.php';
$modversion['blocks'][1]['name'] = _MI_JOBS_BNAME;
$modversion['blocks'][1]['description'] = 'Listar novas vagas';
$modversion['blocks'][1]['show_func'] = 'eEmpregos_show';
$modversion['blocks'][1]['template'] = 'eEmpregos_block_new.html';

// Menu
$modversion['hasMain'] = 1;

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'eEmpregos_listing';
$modversion['tables'][1] = 'eEmpregos_categories';
$modversion['tables'][2] = 'eEmpregos_type';
$modversion['tables'][3] = 'eEmpregos_price';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'eEmpregos_search';