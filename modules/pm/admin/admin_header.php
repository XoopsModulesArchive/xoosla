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
 * Private message
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package pm
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
include_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/mainfile.php';

include '../../../include/cp_header.php';

if ( file_exists( $GLOBALS['xoops']->path( '/Frameworks/moduleclasses/moduleadmin/moduleadmin.php' ) ) ) {
	include_once $GLOBALS['xoops']->path( '/Frameworks/moduleclasses/moduleadmin/moduleadmin.php' );
} else {
	redirect_header( '../../../admin.php', 1, _AM_MODULEADMIN_MISSING, false );
}

$myts = MyTextSanitizer::getInstance();

$moduleInfo = $module_handler->get( $xoopsModule->getVar( 'mid' ) );
$pathIcon16 = XOOPS_URL . '/' . $moduleInfo->getInfo( 'icons16' );
$pathIcon32 = XOOPS_URL . '/' . $moduleInfo->getInfo( 'icons32' );

if ( $xoopsUser ) {
	$moduleperm_handler = xoops_gethandler( 'groupperm' );
	if ( !$moduleperm_handler->checkRight( 'module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups() ) ) {
		redirect_header( XOOPS_URL, 1, _NOPERM );
		exit();
	}
} else {
	redirect_header( XOOPS_URL . '/user.php', 1, _NOPERM );
	exit();
}

if ( !isset( $xoopsTpl ) || !is_object( $xoopsTpl ) ) {
	$xoopsTpl = new XoopsTpl();
}
$xoopsTpl->assign( 'pathIcon16', $pathIcon16 );

xoops_loadLanguage( 'admin', $xoopsModule->getVar( 'dirname' ) );
xoops_loadLanguage( 'modinfo', $xoopsModule->getVar( 'dirname' ) );
xoops_loadLanguage( 'main', $xoopsModule->getVar( 'dirname' ) );

?>