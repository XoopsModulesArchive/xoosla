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
 * XOOPS global header file
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package core
 * @since 2.0.0
 * @author Kazumi Ono <webmaster@myweb.ne.jp>
 * @author Skalpa Keo <skalpa@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

$xoopsPreload = XoopsPreload::getInstance();
$xoopsPreload->triggerEvent( 'core.header.start' );

$xoopsLogger = XoopsLogger::getInstance();
$xoopsLogger->stopTime( 'Module init' );
$xoopsLogger->startTime( 'Xoosla output init' );

require_once $GLOBALS['xoops']->path( 'class/xoopsblock.php' );

// include Smarty template engine and initialize it
if ( !empty( $xoopsOption['template_main'] ) ) {
	if ( false === strpos( $xoopsOption['template_main'], ':' ) ) {
		$xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];
	}
}

$xoopsThemeFactory = new XooslaThemeFactory();
$xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
$xoopsThemeFactory->setDefaultTheme( $xoopsConfig['theme_set'] ) ;

/**
 *
 * @var XooslsTheme
 */
$xoTheme = $xoopsThemeFactory->createInstance( array( 'contentTemplate' => @$xoopsOption['template_main'] ) );
$xoopsTpl = $xoTheme->template;
$xoopsPreload->triggerEvent( 'core.header.addmeta' );

// Temporary solution for start page redirection
if ( defined( 'XOOPS_STARTPAGE_REDIRECTED' ) ) {
	$params = $content = $tpl = $repeat = null;
	$xoTheme->headContent( $params, '<base href="' . XOOPS_URL . '/modules/' . $xoopsConfig['startpage'] . '/" />', $tpl, $repeat );
}

//// Sets cache time
if ( !empty( $xoopsModule ) ) {
	$xoTheme->contentCacheLifetime = @$xoopsConfig['module_cache'][$xoopsModule->getVar( 'mid', 'n' )];
	// Tricky solution for setting cache time for homepage
} else if ( !empty( $xoopsOption['template_main'] ) && $xoopsOption['template_main'] == 'db:system_homepage.html' ) {
	$xoTheme->contentCacheLifetime = 604800;
}

$xoopsPreload->triggerEvent( 'core.header.checkcache' );
if ( $xoTheme->checkCache() ) {
	exit();
}

$xoopsLogger->stopTime( 'Xoosla output init' );
$xoopsLogger->startTime( 'Module display' );

$xoopsPreload->triggerEvent( 'core.header.end' );

?>