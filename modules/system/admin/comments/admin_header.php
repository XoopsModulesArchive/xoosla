<?php
// $Id$
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
 * @package admin_header.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version admin_header.php 00 27/02/2012 04:50 Catzwolf $Id:
 */
require '../../../../mainfile.php';

require_once $GLOBALS['xoops']->path( '/include/cp_functions.php' );

if ( is_object( $xoopsUser ) ) {
	$module_handler = xoops_gethandler( 'module' );
	$xoopsModule = $module_handler->getByDirname( 'system' );
	if ( !in_array( XOOPS_GROUP_ADMIN, $xoopsUser->getGroups() ) ) {
		require_once $GLOBALS['xoops']->path( 'modules/system/constants.php' );
		$sysperm_handler = xoops_gethandler( 'groupperm' );
		if ( !$sysperm_handler->checkRight( 'system_admin', XOOPS_SYSTEM_COMMENT, $xoopsUser->getGroups() ) ) {
			redirect_header( XOOPS_URL, 1, _NOPERM );;
			exit();
		}
	}
} else {
	redirect_header( XOOPS_URL, 1, _NOPERM );
	exit();
}

?>