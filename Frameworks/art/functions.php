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
 * common functions
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           1.00
 * @version         $Id$
 * @package         Frameworks
 * @subpackage      art
 */

if (defined('XOOPS_ART_FUNCTIONS')) return false;
define('XOOPS_ART_FUNCTIONS', true);
	
include_once dirname(__FILE__) . "/functions.ini.php";

load_functions("cache");
load_functions("user");
load_functions("locale");
load_functions("admin");

if (!class_exists('ArtObject'))
	include_once dirname(__FILE__) . "/object.php";

/**
 * get MySQL server version
 * 
 * In some cases mysql_get_client_info is required instead
 *
 * @return     string
 */
function mod_getMysqlVersion($conn = null)
{
    static $mysql_version;
    if (isset($mysql_version)) return $mysql_version;
    if (!is_null($conn)) {
        $version = mysql_get_server_info($conn);
    } else {
        $version = mysql_get_server_info();
    }
    return $mysql_version;
}

?>