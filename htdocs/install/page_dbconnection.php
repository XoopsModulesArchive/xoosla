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
 * Installer database configuration page
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
 * @version     $Id: page_dbsettings.php 1389 2008-03-25 10:31:10Z phppp $
 */

require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )    exit();

    $wizard->setPage( 'dbconnection' );
    $pageHasForm = true;
    $pageHasHelp = true;

    $vars =& $_SESSION['settings'];
    
// Load config values from mainfile.php constants if 1st invocation, or reload has been asked
if ( !isset( $vars['DB_HOST'] ) || false !== @strpos( $_SERVER['HTTP_CACHE_CONTROL'], 'max-age=0' ) ) {
    $keys = array( 'DB_TYPE', 'DB_HOST', 'DB_USER', 'DB_PASS', 'DB_PCONNECT' );
    foreach ( $keys as $k ) {
        $vars[ $k ] = defined( "XOOPS_$k" ) ? constant( "XOOPS_$k" ) : '';
    }
    $vars['DB_PASS'] = '';
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $params = array( 'DB_TYPE', 'DB_HOST', 'DB_USER', 'DB_PASS' );
    foreach ( $params as $name ) {
        $vars[$name] = $_POST[$name];
    }
    $vars['DB_PCONNECT'] = @$_POST['DB_PCONNECT'] ? 1 : 0;
}

$error = '';
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $vars['DB_HOST'] ) && !empty( $vars['DB_USER'] ) ) {
    $func_connect = empty( $vars['DB_PCONNECT'] ) ? "mysql_connect" : "mysql_pconnect";
    if ( ! ( $link = @$func_connect( $vars['DB_HOST'], $vars['DB_USER'], $vars['DB_PASS'], true ) ) ) {
        $error = ERR_NO_DBCONNECTION;
    }
    if ( empty( $error ) ) {
        $wizard->redirectToPage( '+1' );
        exit();
    }
}

if ( @empty( $vars['DB_HOST'] ) ) {
    // Fill with default values
    $vars = array_merge( $vars, array(
        'DB_TYPE'        => 'mysql',
        'DB_HOST'        => 'localhost',
        'DB_USER'        => '',
        'DB_PASS'        => '',
        'DB_PCONNECT'    => 0,
    ) );
}


function xoFormField( $name, $value, $label, $help = '' )
{
    $label = htmlspecialchars( $label );
    $name = htmlspecialchars( $name, ENT_QUOTES );
    $value = htmlspecialchars( $value, ENT_QUOTES );
    
    $field = "<label for='$name'>$label</label>\n";
    if ( $help ) {
        $field .= '<div class="xoform-help">' . $help . "</div>\n";
    }
    $field .= "<input type='text' name='$name' id='$name' value='$value' />";
    
    return $field;
}

    
    ob_start();
?>
<?php if ( !empty( $error ) ) echo '<div class="x2-note error">' . $error . "</div>\n"; ?>
<fieldset>
    <legend><?php echo LEGEND_CONNECTION; ?></legend>
    <label style="text-align:center">
        <?php echo 'Database:'; ?>
        <select size="1" name="DB_TYPE">
            <option value="mysql" selected="selected">mysql</option>
        </select>
    </label>
    <?php echo xoFormField( 'DB_HOST',    $vars['DB_HOST'],        DB_HOST_LABEL, DB_HOST_HELP ); ?>
    <?php echo xoFormField( 'DB_USER',    $vars['DB_USER'],        DB_USER_LABEL, DB_USER_HELP ); ?>
    <?php echo xoFormField( 'DB_PASS',    $vars['DB_PASS'],        DB_PASS_LABEL, DB_PASS_HELP ); ?>

    <label style="text-align:center" title="<?php echo htmlspecialchars( DB_PCONNECT_HELP, ENT_QUOTES ); ?>">
        <?php echo htmlspecialchars( DB_PCONNECT_LABEL ); ?>
        <input class="checkbox" type="checkbox" name="DB_PCONNECT" value="1" <?php echo $vars['DB_PCONNECT'] ? "'checked'" : ""; ?>/>
    </label>
</fieldset>
<?php
    $content = ob_get_contents();
    ob_end_clean();
    include 'install_tpl.php';
?>