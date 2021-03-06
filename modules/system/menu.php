<?php
/**
 * Xoosla
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package menu.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version menu.php 26 2012-02-17 09:16:15Z catzwolf $Id:
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

$groups = $GLOBALS['xoopsUser']->getGroups();
$all_ok = false;
if ( !in_array( XOOPS_GROUP_ADMIN, $groups ) ) {
	$sysperm_handler = xoops_gethandler( 'groupperm' );
	$ok_syscats = $sysperm_handler->getItemIds( 'system_admin', $groups );
} else {
	$all_ok = true;
}
//require_once $GLOBALS['xoops']->path( '/class/xoopslists.php' );

// include system category definitions
include_once $GLOBALS['xoops']->path( '/modules/system/constants.php' );

$admin_dir = $GLOBALS['xoops']->path( '/modules/system/admin' );
$dirlist = XoopsLists::getDirListAsArray( $admin_dir );
$index = 0;
foreach( $dirlist as $file ) {
	if ( file_exists( $admin_dir . '/' . $file . '/xoops_version.php' ) ) {
		include $admin_dir . '/' . $file . '/xoops_version.php';
		if ( $modversion['hasAdmin'] ) {
			if ( xoops_getModuleOption( 'active_' . $file, 'system' ) ) {
				$category = isset( $modversion['category'] ) ? intval( $modversion['category'] ) : 0;
				if ( false != $all_ok || in_array( $modversion['category'], $ok_syscats ) ) {
					$adminmenu[$index]['title'] = trim( $modversion['name'] );
					$adminmenu[$index]['link'] = 'admin.php?fct=' . $file;
					$adminmenu[$index]['image'] = $modversion['image'];
				}
			}
		}
		unset( $modversion );
	}
	$index++;
}
unset( $dirlist );

?>