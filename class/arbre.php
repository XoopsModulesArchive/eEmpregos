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

class XoopsArbre
{
    public $table;     //table with parent-child structure
    public $id;    //name of unique id for records in table $table
    public $pid;     // name of parent id used in table $table
    public $order;    //specifies the order of query results
    public $title;     // name of a field in table $table which will be used when  selection box and paths are generated
    public $xoopsDB;

    //constructor of class XoopsTree

    //sets the names of table, unique id, and parend id

    public function __construct($table_name, $id_name, $pid_name)
    {
        $this->table = $table_name;

        $this->id = $id_name;

        $this->pid = $pid_name;
    }

    // returns an array of first child objects for a given id($sel_id)

    public function getFirstChild($sel_id, $order = '')
    {
        global $xoopsDB;

        $arr = [];

        $sql = 'select * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        $count = $xoopsDB->getRowsNum($result);

        if (0 == $count) {
            return $arr;
        }

        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            $arr[] = $myrow;
        }

        return $arr;
    }

    // returns an array of all FIRST child ids of a given id($sel_id)

    public function getFirstChildId($sel_id)
    {
        global $xoopsDB;

        $idarray = [];

        $result = $xoopsDB->query('select ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '');

        $count = $xoopsDB->getRowsNum($result);

        if (0 == $count) {
            return $idarray;
        }

        while (list($id) = $xoopsDB->fetchRow($result)) {
            $idarray[] = $id;
        }

        return $idarray;
    }

    //returns an array of ALL child ids for a given id($sel_id)

    public function getAllChildId($sel_id, $order = '', $idarray = [])
    {
        global $xoopsDB;

        $sql = 'select ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        $count = $xoopsDB->getRowsNum($result);

        if (0 == $count) {
            return $idarray;
        }

        while (list($r_id) = $xoopsDB->fetchRow($result)) {
            $idarray[] = $r_id;

            $idarray = $this->getAllChildId($r_id, $order, $idarray);
        }

        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($sel_id)

    public function getAllParentId($sel_id, $order = '', $idarray = [])
    {
        global $xoopsDB;

        $sql = 'select ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        [$r_id] = $xoopsDB->fetchRow($result);

        if (0 == $r_id) {
            return $idarray;
        }

        $idarray[] = $r_id;

        $idarray = $this->getAllParentId($r_id, $order, $idarray);

        return $idarray;
    }

    //generates path from the root id to a given id($sel_id)

    // the path is delimetered with "/"

    public function getPathFromId($sel_id, $title, $path = '')
    {
        global $xoopsDB, $myts;

        $result = $xoopsDB->query('select ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");

        if (0 == $this->db->getRowsNum($result)) {
            return $path;
        }

        [$parentid, $name] = $xoopsDB->fetchRow($result);

        $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5);

        $path = '/' . $name . $path . '';

        if (0 == $parentid) {
            return $path;
        }

        $path = $this->getPathFromId($parentid, $title, $path);

        return $path;
    }

    //makes a nicely ordered selection box

    //$preset_id is used to specify a preselected item

    //set $none to 1 to add a option with value 0

    public function makeMySelBox($title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '')
    {
        global $myts, $xoopsDB;

        if ('' == $sel_name) {
            $sel_name = $this->id;
        }

        echo "<select name='" . $sel_name . "'";

        if ('' != $onchange) {
            echo " onchange='" . $onchange . "'";
        }

        echo ">\n";

        $sql = 'select ' . $this->id . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=0';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        if ($none) {
            echo "<option value='0'>" . _JOBS_BIGCAT . "</option>\n";
        }

        while (list($catid, $name) = $xoopsDB->fetchRow($result)) {
            $sel = null;

            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }

            echo "<option value='$catid' $sel>$name</option>\n";

            $sel = '';

            $arr = $this->getChildTreeArray($catid);

            foreach ($arr as $option) {
                $option['prefix'] = str_replace('.', '--', $option['prefix']);

                $catpath = $option['prefix'] . '&nbsp;' . htmlspecialchars($option[$title], ENT_QUOTES | ENT_HTML5);

                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }

                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";

                $sel = '';
            }
        }

        echo "</select>\n";
    }

    //generates nicely formatted linked path from the root id to a given id

    public function getNicePathFromId($sel_id, $title, $funcURL, $path = '')
    {
        global $xoopsDB, $myts;

        $sql = 'select ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id";

        $result = $xoopsDB->query($sql);

        if (0 == $xoopsDB->getRowsNum($result)) {
            return $path;
        }

        [$parentid, $name] = $xoopsDB->fetchRow($result);

        $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5);

        $path = "<a href='" . $funcURL . '&amp;' . $this->id . '=' . $sel_id . "'>" . $name . '</a>&nbsp;&raquo;&nbsp;' . $path . '';

        if (0 == $parentid) {
            return $path;
        }

        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

        return $path;
    }

    //generates id path from the root id to a given id

    // the path is delimetered with "/"

    public function getIdPathFromId($sel_id, $path = '')
    {
        global $xoopsDB;

        $result = $xoopsDB->query('select ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");

        if (0 == $xoopsDB->getRowsNum($result)) {
            return $path;
        }

        [$parentid] = $xoopsDB->fetchRow($result);

        $path = '/' . $sel_id . $path . '';

        if (0 == $parentid) {
            return $path;
        }

        $path = $this->getIdPathFromId($parentid, $path);

        return $path;
    }

    public function getAllChild($sel_id = 0, $order = '', $parray = [])
    {
        global $xoopsDB;

        $sql = 'select * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        $count = $xoopsDB->getRowsNum($result);

        if (0 == $count) {
            return $parray;
        }

        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $parray[] = $row;

            $parray = $this->getAllChild($row[$this->id], $order, $parray);
        }

        return $parray;
    }

    public function getChildTreeArray($sel_id = 0, $order = '', $parray = [], $r_prefix = '')
    {
        global $xoopsDB;

        $sql = 'select * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        $count = $xoopsDB->getRowsNum($result);

        if (0 == $count) {
            return $parray;
        }

        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $row['prefix'] = $r_prefix . '.';

            $parray[] = $row;

            $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }

    //makes a nicely ordered selection box

    //$preset_id is used to specify a preselected item

    //set $none to 1 to add a option with value 0

    public function makeMapSelBox($title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '')
    {
        global $classm, $myts, $xoopsDB;

        if ('' == $sel_name) {
            $sel_name = $this->id;
        }

        $sql = 'select ' . $this->id . ', ' . $title . ', ordre FROM ' . $this->table . ' WHERE ' . $this->pid . '=0';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        while (list($catid, $name, $ordre) = $xoopsDB->fetchRow($result)) {
            echo "<p><a href=\"gest-cat.php?op=AnnoncesNewCat&amp;cid=$catid\"><img src=\""
                 . XOOPS_URL
                 . '/modules/eEmpregos/images/plus.gif" border=0 width=10 height=10 alt="'
                 . _JOBS_ADDSUBCAT
                 . "\"></a>&nbsp;<a href=\"gest-cat.php?op=AnnoncesModCat&amp;cid=$catid\" title=\""
                 . _JOBS_MODIFCAT
                 . "\">$name</a> ";

            if ('ordre' == $classm) {
                echo "($ordre)";
            }

            echo "<br>\n";

            $arr = $this->getChildTreeMapArray($catid, $order);

            foreach ($arr as $option) {
                $option['prefix'] = str_replace('.', ' --->', $option['prefix']);

                $catpath = $option['prefix']
                                    . '&nbsp;<a href="gest-cat.php?op=AnnoncesNewCat&amp;cid='
                                    . $option[$this->id]
                                    . '"><img src="'
                                    . XOOPS_URL
                                    . '/modules/eEmpregos/images/plus.gif" border=0 width=10 height=10 alt="'
                                    . _JOBS_ADDSUBCAT
                                    . '"></a>&nbsp;<a href="gest-cat.php?op=AnnoncesModCat&amp;cid='
                                    . $option[$this->id]
                                    . '" title="'
                                    . _JOBS_MODIFCAT
                                    . '">'
                           . htmlspecialchars($option[$title], ENT_QUOTES | ENT_HTML5);

                $ordreS = $option['ordre'];

                echo "$catpath</a> ";

                if ('ordre' == $classm) {
                    echo "($ordreS)";
                }

                echo "<br>\n";
            }
        }
    }

    public function getChildTreeMapArray($sel_id = 0, $order = '', $parray = [], $r_prefix = '')
    {
        global $xoopsDB;

        $sql = 'select * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $xoopsDB->query($sql);

        $count = $xoopsDB->getRowsNum($result);

        if (0 == $count) {
            return $parray;
        }

        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $row['prefix'] = $r_prefix . '.';

            $parray[] = $row;

            $parray = $this->getChildTreeMapArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }
}
