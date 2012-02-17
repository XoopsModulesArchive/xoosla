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
 * Object joint handler class.  
 *
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright   The XOOPS project http://www.xoops.org/
 *
 * {@link XoopsObjectAbstract} 
 *
 */

class XoopsModelJoint extends XoopsModelAbstract
{
    /**
     * get a list of objects matching a condition joint with another related object
     * 
     * @param     object    $criteria     {@link CriteriaElement} to match
     * @param     array    $fields     variables to fetch
     * @param     bool    $asObject     flag indicating as object, otherwise as array
     * @param     string    $field_link     field of linked object for JOIN
     * @param     string    $field_object   field of current object for JOIN
     * @return     array of objects {@link XoopsObject}
     */
    function &getByLink($criteria = null, $fields = null, $asObject = true, $field_link = null, $field_object = null)
    {
        if (is_array($fields) && count($fields)) {
            if ( !in_array("o." . $this->handler->keyName, $fields) ) {
                $fields[] = "o." . $this->handler->keyName;
            }
            $select = implode(",", $fields);
        } else {
            $select = "o.*, l.*";
        }
        $limit = null;
        $start = null;
        
        $field_object = empty($field_object) ? $field_link : $field_object;
        $sql =  "   SELECT {$select}".
                "   FROM " . $this->handler->table." AS o ".
                "   LEFT JOIN ".$this->handler->table_link." AS l ON o.".$field_object." = l.".$field_link;
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY " . $sort . " " . $criteria->getOrder();
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        if (empty($orderSet)) {
            $sql .= " ORDER BY o." . $this->handler->keyName . " DESC";
        }
        $result = $this->handler->db->query($sql, $limit, $start);
        $ret = array();
        if ($asObject) {        
            while ($myrow = $this->handler->db->fetchArray($result)) {
                $object =& $this->handler->create(false);
                $object->assignVars($myrow);
                $ret[$myrow[$this->handler->keyName]] = $object;
                unset($object);
            }
        } else {
            $object =& $this->handler->create(false);
            while ($myrow = $this->handler->db->fetchArray($result)) {
                $object->assignVars($myrow);
                $ret[$myrow[$this->keyName]] = $object->getValues(array_keys($myrow));
            }
            unset($object);
        }
        
        return $ret;
    }

    /**
     * Count of objects matching a condition
     * 
     * @param   object $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function getCountByLink($criteria = null)
    {
        $sql =  "   SELECT COUNT(DISTINCT " . $this->handler->keyName . ") AS count".
                "   FROM " . $this->handler->table . " AS o ".
                "   LEFT JOIN " . $this->handler->table_link . " AS l ON o." . $this->handler->field_object . " = l." . $this->handler->field_link;
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " ".$criteria->renderWhere();
        }
        if (!$result = $this->handler->db->query($sql)) {
            return false;
        }
        $myrow = $this->handler->db->fetchArray($result);
        return intval($myrow["count"]);
    }
    
    /**
     * array of count of objects matching a condition of, groupby linked object keyname
     * 
     * @param   object $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function getCountsByLink($criteria = null)
    {
        $sql =  "   SELECT l." . $this->handler->keyName_link . ", COUNT(*)".
                "   FROM " . $this->handler->table . " AS o ".
                "   LEFT JOIN " . $this->handler->table_link . " AS l ON o." . $this->handler->field_object . " = l." . $this->handler->field_link;
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
        }
        $sql .= " GROUP BY l." . $this->handler->keyName_link;
        if (!$result = $this->handler->db->query($sql)) {
            return false;
        }
        $ret = array();
        while (list($id, $count) = $this->handler->db->fetchRow($result)) {
            $ret[$id] = $count;
        }
        return $ret;
    }
    
    /**
     * upate objects matching a condition against linked objects
     * 
     * @param   array   $data  array of key => value
     * @param   object  $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function updateByLink($data, $criteria = null)
    {
        $set = array();
        foreach ($data as $key => $val){
            $set[] = "o.{$key}=" . $this->handler->db->quoteString($val);
        }
        $sql =  "   UPDATE " . $this->handler->table . " AS o ".
                "   SET " . implode(", ", $set) .
                "   LEFT JOIN " . $this->handler->table_link . " AS l ON o." . $this->handler->field_object . " = l." . $this->handler->field_link;
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
        }
        return $this->handler->db->query($sql);
    }
    
    /**
     * Delete objects matching a condition against linked objects
     * 
     * @param   object  $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function deleteByLink($criteria = null)
    {
        $sql =  "DELETE FROM " . $this->handler->table . " AS o ".
                " LEFT JOIN " . $this->handler->table_link . " AS l ON o." . $this->handler->field_object . " = l." . $this->handler->field_link;
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " ". $criteria->renderWhere();
        }
        return $this->handler->db->query($sql);
    }
}
?>