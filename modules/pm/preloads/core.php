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
 * Private Messages
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package pm
 * @since 2.4.0
 * @author trabis <lusopoemas@gmail.com>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * PM core preloads
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author trabis <lusopoemas@gmail.com>
 */
class PmCorePreload extends XoopsPreloadItem {
	/**
	 * PmCorePreload::eventCorePmliteStart()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventCorePmliteStart( $args )
	{
		header( 'location: ./modules/pm/pmlite.php' . ( empty( $_SERVER['QUERY_STRING'] ) ? '' : '?' . $_SERVER['QUERY_STRING'] ) );
		exit();
	}

	/**
	 * PmCorePreload::eventCoreReadpmsgStart()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventCoreReadpmsgStart( $args )
	{
		header( 'location: ./modules/pm/readpmsg.php' . ( empty( $_SERVER['QUERY_STRING'] ) ? '' : '?' . $_SERVER['QUERY_STRING'] ) );
		exit();
	}

	/**
	 * PmCorePreload::eventCoreViewpmsgStart()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventCoreViewpmsgStart( $args )
	{
		header( 'location: ./modules/pm/viewpmsg.php' . ( empty( $_SERVER['QUERY_STRING'] ) ? '' : '?' . $_SERVER['QUERY_STRING'] ) );
		exit();
	}

	/**
	 * PmCorePreload::eventCoreClassSmartyXoops_pluginsXoinboxcount()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventCoreClassSmartyXoops_pluginsXoinboxcount( $args )
	{
		$args[0] = xoops_getModuleHandler( 'message', 'pm' );
	}
}

?>