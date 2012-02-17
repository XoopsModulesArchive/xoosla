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
 * Upgrader index file
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.0.13
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

@include_once '../mainfile.php';
require './abstract.php';

@set_time_limit(0);
error_reporting( E_ALL );

if ( !defined( 'XOOPS_ROOT_PATH' ) ) {
    die( 'Bad installation: please add this folder to the XOOPS install you want to upgrade');
}

/*
 * gets list of name of directories inside a directory
 */
function getDirList($dirname)
{
    $dirlist = array();
    if ( is_dir($dirname) && $handle = opendir($dirname) ) {
        while (false !== ($file = readdir($handle))) {
            if ( substr( $file, 0, 1 ) != '.'  && strtolower($file) != 'cvs' ) {
                if ( is_dir( "$dirname/$file" ) ) {
                    $dirlist[] = $file;
                }
            }
        }
        closedir($handle);
        asort($dirlist);
        reset($dirlist);
    }
    return $dirlist;
}

function getDbValue( &$db, $table, $field, $condition = '' )
{
    $table = $db->prefix( $table );
    $sql = "SELECT `$field` FROM `$table`";
    if ( $condition ) {
        $sql .= " WHERE $condition";
    }
    $result = $db->query($sql);
    if ( $result ) {
        $row = $db->fetchRow($result);
        if ( $row ) {
            return $row[0];
        }
    }
    return false;
}



if ( file_exists("./language/".$xoopsConfig['language']."/upgrade.php") ) {
    include_once "./language/".$xoopsConfig['language']."/upgrade.php";
    $language = $xoopsConfig['language'];
} elseif ( file_exists("./language/english/upgrade.php") ) {
    include_once "./language/english/upgrade.php";
    $language = 'english';
} else {
    echo 'no language file.';
    exit();
}


ob_start();
global $xoopsUser;
if ( !$xoopsUser || !$xoopsUser->isAdmin() ) {
    include_once "login.php";    
} else {
    $op = @$_REQUEST['action'];
    if ( empty( $_SESSION['xoops_upgrade'] ) ) {
        $op = '';
    }
    if ( empty( $op ) ) {
        include_once 'check_version.php';
    } else {
        $next = array_shift( $_SESSION['xoops_upgrade'] );
        printf( '<h2>' . _PERFORMING_UPGRADE . '</h2>', $next );
        $upgrader = include_once "$next/index.php";
        $res = $upgrader->apply();
        if ( !$res ) {
            array_unshift( $_SESSION['xoops_upgrade'], $next );
            echo '<a id="link-next" href="index.php?action=next">' . _RELOAD . '</a>';
        } else {
            if ( empty( $_SESSION['xoops_upgrade'] ) ) {
                 $text = _FINISH;
            } else {
                list($key, $val) = each( $_SESSION['xoops_upgrade'] );
                $text = sprintf( _APPLY_NEXT, $val );
            }
            echo '<a id="link-next" href="index.php?action=next">' . $text . '</a>';
        }
    }
}
$content = ob_get_contents();
ob_end_clean();

include_once 'upgrade_tpl.php';

?>