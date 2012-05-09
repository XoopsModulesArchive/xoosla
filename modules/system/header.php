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
 * System header file
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version $Id$
 */
include_once dirname( dirname( dirname( __FILE__ ) ) ) . '/include/cp_header.php';

/**
 * Check user rights
 */
if ( is_object( $xoopsUser ) ) {
	$admintest = 0;
	$xoopsModule = XoopsModule::getByDirname( 'system' );
	if ( !$xoopsUser->isAdmin( $xoopsModule->mid() ) ) {
		redirect_header( XOOPS_URL, 1, _NOPERM );
		exit();
	}
	$admintest = 1;
} else {
	redirect_header( XOOPS_URL, 1, _NOPERM );
	exit();
}

// System Class
include_once $GLOBALS['xoops']->path( '/modules/system/class/breadcrumb.php' );
include_once $GLOBALS['xoops']->path( '/modules/system/class/cookie.php' );

// Load Language
xoops_loadLanguage( 'admin', 'system' );

// Include System files
include_once $GLOBALS['xoops']->path( '/modules/system/include/functions.php' );
// include system category definitions
include_once $GLOBALS['xoops']->path( '/modules/system/constants.php' );
// Get request variable
$fct = system_CleanVars ( $_REQUEST, 'fct', '', 'string' );

$xoBreadCrumb = new SystemBreadcrumb( $fct );
$xoBreadCrumb->addLink ( _AM_SYSTEM_CPANEL, XOOPS_URL . '/admin.php', true );

?>