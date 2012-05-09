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
 * Xoops footer
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @since 2.0.0
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

$xoopsPreload = XoopsPreload::getInstance();
$xoopsPreload->triggerEvent( 'core.footer.start' );

if ( ! defined( 'XOOPS_FOOTER_INCLUDED' ) ) {
	define( 'XOOPS_FOOTER_INCLUDED', 1 );

	$xoopsLogger = XoopsLogger::getInstance();
	$xoopsLogger->stopTime( 'Module display' );

	require_once $GLOBALS['xoops']->path( 'include/notification/notification_select.php' );
	if ( ! headers_sent() ) {
		header( 'Content-Type:text/html; charset=' . _CHARSET );
		header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		header( 'Cache-Control: private, no-cache' );
		header( 'Pragma: no-cache' );
	}

	if ( !isset( $xoTheme ) ) {
		$xoTheme = $GLOBALS['xoTheme'];
	}

	if ( isset( $xoopsOption['template_main'] ) && $xoopsOption['template_main'] != $xoTheme->contentTemplate ) {
		trigger_error( 'xoopsOption[template_main] should be defined before including header.php', E_USER_WARNING );
		if ( false === strpos( $xoopsOption['template_main'], ':' ) ) {
			$xoTheme->contentTemplate = 'db:' . $xoopsOption['template_main'];
		} else {
			$xoTheme->contentTemplate = $xoopsOption['template_main'];
		}
	}
	$xoTheme->render();
	$xoopsLogger->stopTime();
}
$xoopsPreload->triggerEvent( 'core.footer.end' );

?>