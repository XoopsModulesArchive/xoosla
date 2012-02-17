<?php
// $Id$
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
defined( 'XOOPS_MAINFILE_INCLUDED' ) or die();

set_magic_quotes_runtime( 0 );

define( 'XD_S', DIRECTORY_SEPARATOR );
/**
 * Include Xoops Defines
 */
global $xoops, $xoopsSecurity, $xoopsToken;
global $xoopsLogger, $xoopsErrorHandler, $sess_handler;

/**
 * Include Xoops Common Functions
 */
include_once XOOPS_ROOT_PATH . XD_S . 'include' . XD_S . 'functions.php';

/**
 * Include Xoops defines
 */
xoops_inlude( 'include.defines' );
xoops_inlude( 'include.version' );
xoops_inlude( 'class.xoopskernel' );
xoops_inlude( 'class.xoopstoken' );
xoops_inlude( 'class.logger' );
xoops_inlude( 'class.xoopstextsanitizer' );
xoops_inlude( 'class.inputfilters.class_input' );
xoops_inlude( 'class.criteria' );
xoops_inlude( 'kernel.object' );
/**
 * Load Languages
 */
xoops_language( 'global' );
/**
 */
$xoops = &xoops_Kernel::getInstance();
$xoopsToken = &XoopsToken::getInstance();
$xoopsLogger = &XoopsLogger::instance();
$inputFilter = xoopsInputFilter::getInstance();

/**
 * Xoops Superglobals check
 */
$inputFilter->sanitizeSuperGlobals();
$xoopsSecurity = &$xoopsToken;

/**
 * Activate Xoops Error logging and Logger system
 */
$xoopsErrorHandler = &$xoopsLogger;

/**
 * Start Xoops Boot Logging
 */
$xoopsLogger->startTime();
$xoopsLogger->startTime( 'XOOPS Boot' );

/**
 * Include Xoops Database and Connect to it
 */
$xoopsDB = $xoops->loadDB();

/**
 * Load Config Settings
 */
$config_handler = &xoops_gethandler( 'config' );
$xoopsConfig = $xoops->getConfig();

/**
 * Xoops gzip Compression
 */
$xoops->gzipCompression();

/**
 * Error reporting settings (To move into the class and call it via its own method
 */
if ( $xoopsConfig['debug_mode'] == 1 || $xoopsConfig['debug_mode'] == 2 ) {
    error_reporting( E_ALL );
    $xoopsLogger->enableRendering();
    $xoopsLogger->usePopup = ( $xoopsConfig['debug_mode'] == 2 );
} else {
    error_reporting( 0 );
    $xoopsLogger->activated = false;
}

/**
 * Check bad IP's
 */
$xoops->checkbadIps();

/**
 * Include version info file, why do we have this? Nothing uses it as far as I know lol
 */

/**
 * Include site-wide lang file
 */

if ( xoops_language( @$xoopsOption['pagetype'] ) ) {
    $xoopsOption = array();
}

/**
 * Do path translation
 */
$xoops->pathTranslation();

/**
 * Login a user with a valid session
 */
$member_handler = &xoops_gethandler( 'member' );
$sess_handler = &xoops_gethandler( 'session' );

$xoopsUser = '';
$xoopsUserIsAdmin = false;
if ( $xoopsConfig['use_ssl'] && isset( $_POST[$xoopsConfig['sslpost_name']] ) && $_POST[$xoopsConfig['sslpost_name']] != '' ) {
    session_id( $_POST[$xoopsConfig['sslpost_name']] );
} elseif ( $xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '' && $xoopsConfig['session_expire'] > 0 ) {
    if ( isset( $_COOKIE[$xoopsConfig['session_name']] ) ) {
        session_id( $_COOKIE[$xoopsConfig['session_name']] );
    }
    if ( function_exists( 'session_cache_expire' ) ) {
        session_cache_expire( $xoopsConfig['session_expire'] );
    }
    @ini_set( 'session.gc_maxlifetime', $xoopsConfig['session_expire'] * 60 );
}
session_set_save_handler( array( &$sess_handler, 'open' ), array( &$sess_handler, 'close' ), array( &$sess_handler, 'read' ), array( &$sess_handler, 'write' ), array( &$sess_handler, 'destroy' ), array( &$sess_handler, 'gc' ) );
session_start();

/**
 * Remove expired session for xoopsUserId
 */
if ( $xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '' && !isset( $_COOKIE[$xoopsConfig['session_name']] ) && !empty( $_SESSION['xoopsUserId'] ) ) {
    unset( $_SESSION['xoopsUserId'] );
}

/**
 * Load xoopsUserId from cookie if "Remember me" is enabled.
 */
if ( empty( $_SESSION['xoopsUserId'] ) && !empty( $xoopsConfig['usercookie'] ) && !empty( $_COOKIE[$xoopsConfig['usercookie']] ) ) {
    $_SESSION['xoopsUserId'] = $_COOKIE[$xoopsConfig['usercookie']];
}
/**
 * Log in a user if there is a session ID
 */
if ( !empty( $_SESSION['xoopsUserId'] ) ) {
    $xoopsUser = &$member_handler->getUser( $_SESSION['xoopsUserId'] );
    if ( !is_object( $xoopsUser ) ) {
        $xoopsUser = '';
        $_SESSION = array();
        session_destroy();
    } else {
        $GLOBALS["sess_handler"]->update_cookie();
        if ( isset( $_SESSION['xoopsUserGroups'] ) ) {
            $xoopsUser->setGroups( $_SESSION['xoopsUserGroups'] );
        } else {
            $_SESSION['xoopsUserGroups'] = $xoopsUser->getGroups();
        }
        $xoopsUserIsAdmin = $xoopsUser->isAdmin();
    }
}
/**
 * Check to see if there is a user theme selected
 */
if ( !empty( $_POST['xoops_theme_select'] ) && in_array( $_POST['xoops_theme_select'], $xoopsConfig['theme_set_allowed'] ) ) {
    $xoopsConfig['theme_set'] = $_POST['xoops_theme_select'];
    $_SESSION['xoopsUserTheme'] = $_POST['xoops_theme_select'];
} elseif ( !empty( $_SESSION['xoopsUserTheme'] ) && in_array( $_SESSION['xoopsUserTheme'], $xoopsConfig['theme_set_allowed'] ) ) {
    $xoopsConfig['theme_set'] = $_SESSION['xoopsUserTheme'];
}

/**
 * If Admin has closed site we shall do it here.
 */
if ( $xoopsConfig['closesite'] == 1 ) {
    xoops_inlude( 'include.site_closed' );
}

/**
 * Test to see if the module exists and include it, if not redirect back to the main page
 * This needs to be moved out of here and into the xoops module class
 */
if ( file_exists( './xoops_version.php' ) ) {
    $url_arr = explode( '/', strstr( $_SERVER['PHP_SELF'], '/modules/' ) );
    $module_handler = &xoops_gethandler( 'module' );
    $xoopsModule = &$module_handler->getByDirname( $url_arr[2] );
    unset( $url_arr );
    if ( !$xoopsModule || !$xoopsModule->getVar( 'isactive' ) ) {
        trigger_error( _MODULENOEXIST, E_USER_NOTICE );
        header( 'location:' . XOOPS_URL );
    }
    $moduleperm_handler = &xoops_gethandler( 'groupperm' );
    if ( $xoopsUser ) {
        if ( !$moduleperm_handler->checkRight( 'module_read', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups() ) ) {
            redirect_header( XOOPS_URL, 1, _NOPERM, false );
            exit();
        }
        $xoopsUserIsAdmin = $xoopsUser->isAdmin( $xoopsModule->getVar( 'mid' ) );
    } else {
        if ( !$moduleperm_handler->checkRight( 'module_read', $xoopsModule->getVar( 'mid' ), XOOPS_GROUP_ANONYMOUS ) ) {
            redirect_header( XOOPS_URL . '/user.php', 1, _NOPERM );
            exit();
        }
    }
    xoops_language( 'main', true );
    if ( $xoopsModule->getVar( 'hasconfig' ) == 1 || $xoopsModule->getVar( 'hascomments' ) == 1 || $xoopsModule->getVar( 'hasnotification' ) == 1 ) {
        $xoopsModuleConfig = $config_handler->getConfigsByCat( 0, $xoopsModule->getVar( 'mid' ) );
    }
} elseif ( $xoopsUser ) {
    $xoopsUserIsAdmin = $xoopsUser->isAdmin( 1 );
}

/**
 * Xoops Logger Stop Xoops Boot
 */
$xoopsLogger->stopTime( 'XOOPS Boot' );

/**
 * Xoops Logger Start Xoops Module
 */
$xoopsLogger->startTime( 'Module init' );

?>