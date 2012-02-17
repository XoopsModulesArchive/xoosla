<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
defined( 'XOOPS_MAINFILE_INCLUDED' ) or die();
/**
 * XOOPS defined elements
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: defines.php 1531 2008-05-01 09:25:50Z catzwolf $
 */

/**
 * Define Block Positions
 */
define( 'XOOPS_SIDEBLOCK_LEFT', 0 );
define( 'XOOPS_SIDEBLOCK_RIGHT', 1 );
define( 'XOOPS_SIDEBLOCK_BOTH', 2 );
define( 'XOOPS_CENTERBLOCK_LEFT', 3 );
define( 'XOOPS_CENTERBLOCK_RIGHT', 4 );
define( 'XOOPS_CENTERBLOCK_CENTER', 5 );
define( 'XOOPS_CENTERBLOCK_ALL', 6 );
define( 'XOOPS_CENTERBLOCK_BOTTOMLEFT', 7 );
define( 'XOOPS_CENTERBLOCK_BOTTOMRIGHT', 8 );
define( 'XOOPS_CENTERBLOCK_BOTTOM', 9 );
define( 'XOOPS_BLOCK_INVISIBLE', 0 );
define( 'XOOPS_BLOCK_VISIBLE', 1 );
/**
 * Define Search vars
 */
define( 'XOOPS_MATCH_START', 0 );
define( 'XOOPS_MATCH_END', 1 );
define( 'XOOPS_MATCH_EQUAL', 2 );
define( 'XOOPS_MATCH_CONTAIN', 3 );
/**
 * * Theme Path And Url*
 */
define( 'XOOPS_THEME_PATH', XOOPS_ROOT_PATH . XD_S . 'themes' );
define( 'XOOPS_THEME_URL', XOOPS_URL . '/themes' );
/**
 * Define Upload Path And Url*
 */
define( 'XOOPS_UPLOAD_PATH', XOOPS_ROOT_PATH . XD_S . 'uploads' );
define( 'XOOPS_UPLOAD_URL', XOOPS_URL . '/uploads' );
/**
 * Define Module Path
 */
define( 'XOOPS_MODULES_PATH', XOOPS_ROOT_PATH . XD_S . 'modules' );
/**
 * Define Smarty path
 */
define( 'SMARTY_DIR', XOOPS_ROOT_PATH . XD_S . 'libraries' . XD_S . 'smarty' . XD_S );

/* Define Mail Dir */
define( 'PHPMAILER_DIR', XOOPS_ROOT_PATH . XD_S . 'libraries' . XD_S . 'phpmailer' . XD_S );

/* Define Plugin Dir */
define( 'PLUGIN_DIR', XOOPS_ROOT_PATH . XD_S . 'plugins' . XD_S );

/**
 * Define Smarty path
 */
define( 'XOOPS_CACHE_URL', XOOPS_URL . '/cache' );
define( 'XOOPS_COMPILE_PATH', XOOPS_VAR_PATH . XD_S . 'caches' . XD_S . 'smarty_compile' );
define( 'XOOPS_CACHE_PATH', XOOPS_VAR_PATH . '/caches/xoops_cache' );
/**
 * Define XOOPS_XMLRPC
 */
if ( !defined( 'XOOPS_XMLRPC' ) ) {
    define( 'XOOPS_DB_CHKREF', 1 );
} else {
    define( 'XOOPS_DB_CHKREF', 0 );
}
/**
 * Define Xoops Uses Multibytes
 */
if ( !defined( 'XOOPS_USE_MULTIBYTES' ) ) {
    define( 'XOOPS_USE_MULTIBYTES', 0 );
}

?>