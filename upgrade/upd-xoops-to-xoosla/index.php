<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
require_once 'dbmanager.php';

/**
 * Upgrader from to Xoops to Xoosla
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright The XOOPS project http://www.xoops.org/
 * @license http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package upgrader
 * @since 1.0.0.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @author trabis <lusopoemas@gmail.com>
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: index.php 8066 2011-11-06 05:09:33Z beckmi $
 */
class upgrade_xoops extends xoopsUpgrade {
	var $tasks = array( 'configoption' );

	/**
	 * upgrade_xoops::check_configoption()
	 *
	 * @return
	 */
	function check_configoption()
	{
		$sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix( 'configoption' ) . "` WHERE `confop_name` IN ( '_MD_AM_DEBUGMODE4' )";

        if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
			return false;
		}
		list( $count ) = $GLOBALS['xoopsDB']->fetchRow( $result );
		return ( $count == 0 ) ? false : true;
	}

	/**
	 * upgrade_xoops::apply_configoption()
	 *
	 * @return
	 */
	function apply_configoption()
	{
		$dbm = new db_manager();
		$dbm->insert( 'configoption', " (confop_name,confop_value,conf_id) VALUES ('_MD_AM_DEBUGMODE4', '4', '13')" );
		return true;
	}

	/**
	 * upgrade_xoops::upgrade_xoops()
	 */
	function upgrade_xoops()
	{
		$this->xoopsUpgrade( basename( dirname( __FILE__ ) ) );
	}
}

$upg = new upgrade_xoops();
return $upg;

?>