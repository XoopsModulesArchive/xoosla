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
 * Xoops Frameworks addon: art
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           1.00
 * @version         $Id$
 * @package         Frameworks
 */
 
class XoopsArt 
{
    function __construct()
    {
    }
    
    function XoopsArt()
    {
        $this->__construct();
    }
    
    /**
     * Load a collective functions of Frameworks
     *
     * @param    string    $group        name of  the collective functions, empty for functions.php
     * @return    bool
     */
    function loadFunctions($group = "")
    {
        return include_once FRAMEWORKS_ROOT_PATH . "/art/functions.{$group}" . (empty($group) ? "" : "." ) . "php";
    }
}
?>