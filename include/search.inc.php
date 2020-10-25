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
function eEmpregos_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $sql = 'select lid,usid,title,date FROM ' . $xoopsDB->prefix('eEmpregos_listing') . " WHERE valid='Yes' AND date<=" . time() . '';

    if (0 != $userid) {
        $sql .= ' AND usid=' . $userid . ' ';
    }

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((description LIKE '%$queryarray[0]%' OR title LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(description LIKE '%$queryarray[$i]%' OR title LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY date DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'images/cat/default.gif';

        $ret[$i]['link'] = 'index.php?pa=viewlistings&AMP;lid=' . $myrow['lid'] . '';

        $ret[$i]['title'] = $myrow['title'];

        $ret[$i]['time'] = $myrow['date'];

        $ret[$i]['uid'] = $myrow['usid'];

        $i++;
    }

    return $ret;
}
