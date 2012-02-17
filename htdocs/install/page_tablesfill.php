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
 * Installer db inserting page
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

    $wizard->setPage( 'tablesfill' );
    $pageHasForm = false;
    $pageHasHelp = false;

    $vars =& $_SESSION['settings'];
    
    include_once "../mainfile.php";
    include_once './class/dbmanager.php';
    $dbm =& new db_manager();

    if ( !$dbm->isConnectable() ) {
        $wizard->redirectToPage( 'dbsettings' );
        exit();
    }
    $res = $dbm->query( "SELECT COUNT(*) FROM " . $dbm->db->prefix( "users" ) );
    if ( !$res ) {
        $wizard->redirectToPage( 'dbsettings' );
        exit();
    }
    list ( $count ) = $dbm->db->fetchRow( $res );
    $process = $count ? '' : 'insert';
    
    extract( $_SESSION['siteconfig'], EXTR_SKIP );
    
if ( $process ) {
    include_once './makedata.php';
    $cm = 'dummy';

    $wizard->loadLangFile( 'install2' );

    $language = $wizard->language;
    
    $result = $dbm->queryFromFile('./sql/'.XOOPS_DB_TYPE.'.data.sql');
    $result = $dbm->queryFromFile('./language/'.$language.'/'.XOOPS_DB_TYPE.'.lang.data.sql');
    $group = make_groups( $dbm );
    $result = make_data( $dbm, $cm, $adminname, $adminpass, $adminmail, $language, $group );
    $content = DATA_INSERTED . "<br />" . $dbm->report();
} elseif ($update) {
    $sql = "UPDATE " . $dbm->db->prefix("users") . " SET `uname` = '" . addslashes($adminname). "', `email` = '" . addslashes($adminmail) . "', `user_regdate` = '" . time() . "', `pass` = '" . md5($adminpass) . "', `last_login` = '" . time() . "' WHERE uid = 1"; 
    $dbm->db->queryF($sql);
    $content = '';
} else {
    $content = "<p class='x2-note'>" . DATA_ALREADY_INSERTED . "</p>";
}

    include 'install_tpl.php';
?>