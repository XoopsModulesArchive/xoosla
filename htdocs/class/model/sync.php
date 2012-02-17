<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code 
 which is considered copyrighted (c) material of the original comment or credit authors.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Extended object handlers
 *
 * @copyright       The XOOPS project http://www.xoops.org/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         kernel
 * @subpackage      model
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

/**
 * Object synchronization handler class.  
 *
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright   The XOOPS project http://www.xoops.org/
 *
 * {@link XoopsObjectAbstract} 
 *
 */

class XoopsModelSync extends XoopsModelAbstract
{
    /**
     * Clean orphan objects against linked objects
     * 
     * @param     string    $table_link     table of linked object for JOIN
     * @param     string    $field_link     field of linked object for JOIN
     * @param     string    $field_object   field of current object for JOIN
     * @return     bool    true on success
     */
    function cleanOrphan($table_link = "", $field_link = "", $field_object = "")
    {
        $table_link = empty($table_link) ? @$this->handler->table_link : preg_replace("/[^a-z0-9\-_]/i", "", $table_link);
        if (empty($table_link)) {
            return false;
        }
        $field_link = empty($field_link) ? @$this->handler->field_link : preg_replace("/[^a-z0-9\-_]/i", "", $field_link);
        if (empty($field_link)) {
            return false;
        }
        $field_object = empty($field_object) ? ( empty($this->handler->field_object) ? $field_link : $this->handler->field_object ) : preg_replace("/[^a-z0-9\-_]/i", "", $field_object);
        
        /* for MySQL 4.1+ */
        if (version_compare( mysql_get_server_info(), "4.1.0", "ge" )):
        $sql =  "   DELETE FROM {$this->handler->table}" .
                "   WHERE ({$field_object} NOT IN ( SELECT DISTINCT {$field_link} FROM {$table_link}) )";
        else:
        // for 4.0+
        $sql =     "   DELETE {$this->handler->table} FROM " . $this->handler->table .
                "   LEFT JOIN {$table_link} AS aa ON {$this->handler->table}.{$field_object} = aa." . $field_link .
                "   WHERE (aa.{$field_link} IS NULL)";
        endif;
        if (!$result = $this->handler->db->queryF($sql)) {
            return false;
        }
        return true;
    }
    
    /**
     * Synchronizing objects
     * 
     * @return     bool    true on success
     */
    function synchronization()
    {
        $this->cleanOrphan();
        return true;
    }
}
?>