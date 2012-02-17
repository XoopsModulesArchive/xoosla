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
 * Installer mainfile creation page
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

    $wizard->setPage( 'configsave' );
    $pageHasForm = false;
    $pageHasHelp = false;

    $vars =& $_SESSION['settings'];
    
if ( empty($vars['ROOT_PATH']) ) {
    $wizard->redirectToPage( 'pathsettings' );
    exit();
} elseif ( empty($vars['DB_HOST']) ) {
    $wizard->redirectToPage( 'dbsettings' );
    exit();
}

    $error = '';
    if ( !copy( $vars['ROOT_PATH'] . '/mainfile.dist.php', $vars['ROOT_PATH'] . '/mainfile.php' ) ) {
        $error = ERR_COPY_MAINFILE;
    } else {
        clearstatcache();

        $rewrite = array( 'GROUP_ADMIN' => 1, 'GROUP_USERS' => 2, 'GROUP_ANONYMOUS' => 3 );
        $rewrite = array_merge( $rewrite, $vars );
        if ( ! $file = fopen( $vars['ROOT_PATH'] . '/mainfile.php', "r" ) ) {
            $error = ERR_READ_MAINFILE;
        } else {
            $content = fread( $file, filesize( $vars['ROOT_PATH'] . '/mainfile.php' ) );
            fclose($file);

            foreach( $rewrite as $key => $val ) {
                if ( is_int($val) && preg_match("/(define\()([\"'])(XOOPS_$key)\\2,\s*([0-9]+)\s*\)/", $content ) ) {
                    $content = preg_replace( "/(define\()([\"'])(XOOPS_$key)\\2,\s*([0-9]+)\s*\)/",
                        "define( 'XOOPS_$key', $val )", $content );
                } elseif( preg_match( "/(define\()([\"'])(XOOPS_$key)\\2,\s*([\"'])(.*?)\\4\s*\)/", $content ) ) {
                    $val = str_replace( '$', '\$', addslashes( $val ) );
                    $content = preg_replace( "/(define\()([\"'])(XOOPS_$key)\\2,\s*([\"'])(.*?)\\4\s*\)/",
                        "define( 'XOOPS_$key', '$val' )", $content );
                } else {
                    //$this->error = true;
                    //$this->report .= _NGIMG.sprintf( ERR_WRITING_CONSTANT, "<strong>$val</strong>")."<br />\n";
                }
            }
            if ( !$file = fopen( $vars['ROOT_PATH'] . '/mainfile.php', "w" ) ) {
                $error = ERR_WRITE_MAINFILE;
            } else {
                if ( fwrite( $file, $content ) == -1 ) {
                    $error = ERR_WRITE_MAINFILE;
                }
                fclose($file);
            }
        }
    }
    
if ( empty( $error ) ) {
    ob_start();
?>
    <table class="diags">
    <caption><?php echo SAVED_MAINFILE; ?></caption>
    <thead>
        <tr><th colspan="2"><p><?php echo SAVED_MAINFILE_MSG; ?></p></th></tr>
    </thead>
    <tbody>
    <?php foreach ( $vars as $k => $v ) { ?>
    <tr>
        <th>XOOPS_<?php echo $k; ?></th>
        <td><?php echo $v; ?></td>
    </tr>
    <?php
    } ?>
    </tbody>
    </table>

<?php
    $content = ob_get_contents();
    ob_end_clean();
} else {
    $content = '<p class="errorMsg">' . $error . '</p>';
}
include 'install_tpl.php';

?>