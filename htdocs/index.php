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
 * XOOPS global index file
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package core
 * @since 2.0.0
 * @author Kazumi Ono <webmaster@myweb.ne.jp>
 * @author Skalpa Keo <skalpa@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id$
 */
include 'mainfile.php';
/**
 * redirects to installation, if xoops is not installed yet
 */
// check if start page is defined
if ( isset( $xoopsConfig['startpage'] ) && $xoopsConfig['startpage'] != '' && $xoopsConfig['startpage'] != '--' ) {
    // Temporary solution for start page redirection
    define( "XOOPS_STARTPAGE_REDIRECTED", 1 );

    $module_handler = &xoops_gethandler( 'module' );
    $xoopsModule = &$module_handler->getByDirname( $xoopsConfig['startpage'] );
    if ( !$xoopsModule || !$xoopsModule->getVar( 'isactive' ) ) {
        include_once XOOPS_ROOT_PATH . "/header.php";
        // echo "<h4>" . _MODULENOEXIST . "</h4>";
        include_once XOOPS_ROOT_PATH . "/footer.php";
        exit();
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
            redirect_header( XOOPS_URL . "/user.php", 1, _NOPERM );
            exit();
        }
    }
    if ( file_exists( XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar( 'dirname', 'n' ) . "/language/" . $xoopsConfig['language'] . "/main.php" ) ) {
        include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar( 'dirname', 'n' ) . "/language/" . $xoopsConfig['language'] . "/main.php";
    } elseif ( file_exists( XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar( 'dirname', 'n' ) . "/language/english/main.php" ) ) {
        include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar( 'dirname', 'n' ) . "/language/english/main.php";
    }
    if ( $xoopsModule->getVar( 'hasconfig' ) == 1 || $xoopsModule->getVar( 'hascomments' ) == 1 || $xoopsModule->getVar( 'hasnotification' ) == 1 ) {
        $xoopsModuleConfig = $config_handler->getConfigsByCat( 0, $xoopsModule->getVar( 'mid' ) );
    }

    chdir( 'modules/' . $xoopsConfig['startpage'] . '/' );
    $_SERVER['REQUEST_URI'] .= substr( $_SERVER['REQUEST_URI'], 0, -9 ) . 'modules/' . $xoopsConfig['startpage'] . '/index.php';
    include XOOPS_ROOT_PATH . '/modules/' . $xoopsConfig['startpage'] . '/index.php';
    exit();
} else {
    $xoopsOption['show_cblock'] = 1;
    include 'header.php';
    include 'footer.php';
}

?>