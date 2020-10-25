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

class PageNav
{
    public $total;

    public $perpage;

    public $current;

    public $url;

    public function __construct($total_items, $items_perpage, $current_start, $start_name = 'start', $extra_arg = '')
    {
        $this->total = (int)$total_items;

        $this->perpage = (int)$items_perpage;

        $this->current = (int)$current_start;

        if ('' != $extra_arg && ('&amp;' != mb_substr($extra_arg, -5) || '&' != mb_substr($extra_arg, -1))) {
            $extra_arg .= '&amp;';
        }

        //$this->url = $GLOBALS['PHP_SELF'].'?'.$extra_arg.trim($start_name).'=';

        $this->url = $_SERVER['PHP_SELF'] . '?' . $extra_arg . trim($start_name) . '=';
    }

    public function renderNav($offset = 4)
    {
        if ($this->total < $this->perpage) {
            return;
        }

        $total_pages = ceil($this->total / $this->perpage);

        if ($total_pages > 1) {
            $ret = '';

            $prev = $this->current - $this->perpage;

            $ret .= '<table width=100% border=0><tr><td height=1 BGCOLOR="#000000" colspan=3></td></tr><tr>';

            if ($prev >= 0) {
                $ret .= '<td align="left"><a href="' . $this->url . $prev . '">&laquo;&laquo; ' . _JOBS_PREV . '</a></td>';
            } else {
                $ret .= '<td align="left"><font color="#C0C0C0">&laquo;&laquo; ' . _JOBS_PREV . '</font></td>';
            }

            $ret .= '<td align="center">';

            $counter = 1;

            $current_page = (int)floor(($this->current + $this->perpage) / $this->perpage);

            while ($counter <= $total_pages) {
                if ($counter == $current_page) {
                    $ret .= '<b>' . $counter . '</b> ';
                } elseif (($counter > $current_page - $offset && $counter < $current_page + $offset) || 1 == $counter || $counter == $total_pages) {
                    if ($counter == $total_pages && $current_page < $total_pages - $offset) {
                        $ret .= '... ';
                    }

                    $ret .= '<a href="' . $this->url . (($counter - 1) * $this->perpage) . '">' . $counter . '</a> ';

                    if (1 == $counter && $current_page > 1 + $offset) {
                        $ret .= '... ';
                    }
                }

                $counter++;
            }

            $ret .= '</td>';

            $next = $this->current + $this->perpage;

            if ($this->total > $next) {
                $ret .= '<td align="right"><a href="' . $this->url . $next . '">' . _JOBS_NEXT . ' &raquo;&raquo;</a></td>';
            } else {
                $ret .= '<td align="right"><font color="#C0C0C0">' . _JOBS_NEXT . ' &raquo;&raquo;</font></td>';
            }

            $ret .= '</tr><tr><td height=1 bgcolor="#000000" colspan=3></td></tr></table>';
        }

        return $ret;
    }
}
