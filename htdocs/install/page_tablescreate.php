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
 * Installer table creation page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )    exit();

    $wizard->setPage( 'tablescreate' );
    $pageHasForm = false;
    $pageHasHelp = false;

    $vars =& $_SESSION['settings'];
    
    
    include_once "../mainfile.php";
    include_once './class/dbmanager.php';
    $dbm =& new db_manager();

    if ( !$dbm->isConnectable() ) {
        $wizard->redirectToPage( '-3' );
        exit();
    }
    if ( $dbm->tableExists( 'users' ) ) {
        $content = '<p class="x2-note">' . XOOPS_TABLES_FOUND . '</p>';
    } else {
        $result = $dbm->queryFromFile( './sql/' . XOOPS_DB_TYPE . '.structure.sql' );
        $content = XOOPS_TABLES_CREATED . "<br />" . $dbm->report();
    }
    include 'install_tpl.php';
?>