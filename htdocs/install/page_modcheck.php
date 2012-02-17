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
 * Installer configuration check page
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

    $wizard->setPage( 'modcheck' );
    $pageHasForm = false;

$diagsOK = false;

function xoDiag( $status = -1, $str = '' ) {
    if ( $status == -1 ) {
        $GLOBALS['error'] = true;
    }
    $classes = array( -1 => 'error', 0 => 'warning', 1 => 'success' );
    $strings = array( -1 => FAILED, 0 => WARNING, 1 => SUCCESS );
    if ( empty($str) ) {
        $str = $strings[$status];
    }
    return '<span class="' . $classes[$status] . '">' . $str . '</span>';    
}
function xoDiagBoolSetting( $name, $wanted = false, $severe = false ) {
    $setting = strtolower( ini_get( $name ) );
    $setting = ( empty( $setting ) || $setting == 'off' || $setting == 'false' ) ? false : true;
    if ( $setting == $wanted ) {
        return xoDiag( 1, $setting ? 'ON' : 'OFF' );
    } else {
        return xoDiag( $severe ? -1 : 0, $setting ? 'ON' : 'OFF' );
    }
}

function xoDiagIfWritable( $path ) {
    $path = "../" . $path;
    $error = true;
    if ( !is_dir( $path ) ) {
        if ( file_exists( $path ) ) {
            @chmod( $path, 0666 );
            $error = !is_writeable( $path );
        }
    } else {
        @chmod( $path, 0777 );
        $error = !is_writeable( $path );
    }
    return xoDiag( $error ? -1 : 1, $error ? 'Not writable' : 'Writable' );
}


    ob_start();
?>
    <table class="diags">
    <caption><?php echo REQUIREMENTS; ?></caption>
    <tr>
      <th><?php echo SERVER_API; ?></th>
      <td><?php echo php_sapi_name(); ?></td>
    </tr>
    <tr>
        <th><?php echo _PHP_VERSION; ?></th>
        <td><?php
            if ( version_compare( phpversion(), '4.3.0', '>=') ) {
                echo xoDiag( 1, phpversion() );
            } elseif ( version_compare( phpversion(), '4.2.1', '>=') ) {
                echo xoDiag( 0, phpversion() );
            } else {
                echo xoDiag( -1, phpversion() );
            }
             ?></td>
    </tr>
    <tr>
        <th><?php printf( PHP_EXTENSION, 'MySQL' ); ?></th>
        <td><?php echo xoDiag( function_exists( 'mysql_connect' ) ? 1 : -1 ); ?></td>
    </tr>
    <tr>
        <th><?php printf( PHP_EXTENSION, 'Session' ); ?></th>
        <td><?php echo xoDiag( extension_loaded( 'session' ) ? 1 : -1 ); ?></td>
    </tr>
    <tr>
        <th><?php printf( PHP_EXTENSION, 'PCRE' ); ?></th>
        <td><?php echo xoDiag( extension_loaded( 'pcre' ) ? 1 : -1 ); ?></td>
    </tr>
    <tr>
        <th scope="row">file_uploads</th>
        <td><?php echo xoDiagBoolSetting( 'file_uploads', true ); ?></td>
    </tr>
    </table>

    <table class="diags">
    <caption><?php echo RECOMMENDED_EXTENSIONS; ?></caption>
    <thead>
        <tr><th colspan="2"><p><?php echo RECOMMENDED_EXTENSIONS_MSG; ?></p></th></tr>
    </thead>
    <tbody>
    <tr>
        <th><?php printf( PHP_EXTENSION, CHAR_ENCODING ); ?></th>
        <td><?php
                $ext = array();
                if ( extension_loaded( 'iconv' ) )        $ext[] = 'Iconv';
                if ( extension_loaded( 'mb_string' ) )    $ext[] = 'MBString';
                if ( empty($ext) ) {
                    echo xoDiag( 0, NONE );
                } else {
                    echo xoDiag( 1, implode( ',', $ext ) );
                }
            ?></td>
    </tr>   
    <tr>
        <th><?php printf( PHP_EXTENSION, XML_PARSING ); ?></th>
        <td><?php
                $ext = array();
                if ( extension_loaded( 'xml' ) )        $ext[] = 'XML';
                //if ( extension_loaded( 'dom' ) )        $ext[] = 'DOM';
                if ( empty($ext) ) {
                    echo xoDiag( 0, NONE );
                } else {
                    echo xoDiag( 1, implode( ',', $ext ) );
                }
            ?></td>
    </tr>   
    </tbody>
    </table>
<?php
    $content = ob_get_contents();
    ob_end_clean();
    
    include 'install_tpl.php';

?>