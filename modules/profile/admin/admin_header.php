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
 * Extended User Profile
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package profile
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/mainfile.php';

require_once XOOPS_ROOT_PATH . '/include/cp_functions.php';
require '../../../include/cp_header.php';

if ( file_exists( $GLOBALS['xoops']->path( '/Frameworks/moduleclasses/moduleadmin/moduleadmin.php' ) ) ) {
	include_once $GLOBALS['xoops']->path( '/Frameworks/moduleclasses/moduleadmin/moduleadmin.php' );
} else {
	echo xoops_error( 'Error: You don"t use the Frameworks "admin module". Please install this Frameworks' );
}

$moduleInfo = $module_handler->get( $xoopsModule->getVar( 'mid' ) );
$pathIcon16 = XOOPS_URL . '/' . $moduleInfo->getInfo( 'icons16' );
$pathIcon32 = XOOPS_URL . '/' . $moduleInfo->getInfo( 'icons32' );

$myts = MyTextSanitizer::getInstance();

if ( !isset( $xoopsTpl ) || !is_object( $xoopsTpl ) ) {
	$xoopsTpl = new XoopsTpl();
}
// Load languages
xoops_loadLanguage( 'admin', $xoopsModule->getVar( 'dirname' ) );
xoops_loadLanguage( 'modinfo', $xoopsModule->getVar( 'dirname' ) );
xoops_loadLanguage( 'main', $xoopsModule->getVar( 'dirname' ) );

xoops_loadLanguage( 'user' );

?>