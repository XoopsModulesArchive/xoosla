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
 * Xoosla
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package install
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version $Id:
 */
define( 'INSTALL_USER', '' );
define( 'INSTALL_PASSWORD', '' );
define( 'XOOSLA_INSTALL', 1 );
define( 'NEWLINE', "\n" );

/**
 * Add no option for array
 */
if ( empty( $xoopsOption['hascommon'] ) ) {
	$xoopsOption['nocommon'] = true;
	session_start();
}

if ( file_exists( '../mainfile.php' ) ) {
	include '../mainfile.php';
}

if ( !defined( 'XOOPS_ROOT_PATH' ) ) {
	define( 'XOOPS_ROOT_PATH', str_replace( "\\", "/", realpath( '../' ) ) );
}

require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';
require_once XOOPS_ROOT_PATH . '/include/version.php';
require_once XOOPS_ROOT_PATH . '/install/class/installwizard.php';
require_once XOOPS_ROOT_PATH . '/install/include/functions.php';
require_once XOOPS_ROOT_PATH . '/install/class/pathcontroller.php';

$wizard = new XoopsInstallWizard();
if ( !$wizard->xoInit() ) {
	exit();
}

if ( !@is_array( $_SESSION['settings'] ) ) {
	$_SESSION['settings'] = array();
}

?>