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
 * @copyright The XOOPS project http://www.xoops.org/
 * @license http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package installer
 * @since 2.3.0
 * @author Haruki Setoyama <haruki@planewave.org>
 * @author Kazumi Ono <webmaster@myweb.ne.jp>
 * @author Skalpa Keo <skalpa@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @author DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version $Id: page_modcheck.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined( 'XOOSLA_INSTALL' ) or die( 'Direct Access To This File Not Allowed!' );

$ctrl = new PathController( $wizard->configs['xoopsPathDefault'], $wizard->configs['dataPath'] );

if ( $_SERVER['REQUEST_METHOD'] == 'GET' && @$_POST['var'] && $_POST['action'] == 'checkpath' ) {
	$path = $_GET['var'];
	$ctrl->xoopsPath[$path] = htmlspecialchars( trim( $_GET['path'] ) );
	echo genPathCheckHtml( $path, $ctrl->checkPath( $path ) );
	exit();
}

$ctrl->execute( $errors );

foreach ( $wizard->configs['extensions'] as $ext => $value ) {
	if ( extension_loaded( $ext ) ) {
		if ( is_array( $value[0] ) ) {
			$wizard->configs['extensions'][$ext][] = xoDiag( 1, implode( ',', $value[0] ) );
		} else {
			$wizard->configs['extensions'][$ext][] = xoDiag( 1, $value[0] );
		}
	} else {
		$wizard->configs['extensions'][$ext][] = xoDiag( 0, INSTALL_NONE );
	}
}

$ret = '';
foreach ( $wizard->configs['extensions'] as $key => $value ) {
	$ret .= '<tr><td class="head">' . $value[1] . '</td><td>' . $value[2] . '</td></tr>';
}

$errors['root'] = genPathCheckHtml( 'root', $ctrl->validPath['root'] );
$errors['lib'] = genPathCheckHtml( 'lib', $ctrl->validPath['lib'] );
$errors['data'] = genPathCheckHtml( 'data', $ctrl->validPath['data'] );

ob_start();
xoShowErrors( $errors );
?>

<fieldset>
    <legend><?php echo INSTALL_SERVER_CHECK_LEGEND; ?></legend>
    <div class="message"><?php echo INSTALL_SERVER_CHECK_MSG; ?></div>
<table>
<tbody>
<tr>
    <td class="head"><?php echo INSTALL_SERVER_API; ?></td>
    <td><?php echo php_sapi_name(); ?><br /> <?php echo $_SERVER["SERVER_SOFTWARE"]; ?></td>
</tr>
<tr>
    <td class="head"><?php echo INSTALL_PHP_VERSION; ?></td>
    <td><?php echo xoPhpVersion(); ?></td>
</tr>
<tr>
    <td class="head"><?php printf( INSTALL_PHP_EXTENSION, 'MySQL' ); ?></td>
    <td><?php echo xoDiag( function_exists( 'mysql_connect' ) ? 1 : - 1, @mysql_get_client_info() ); ?></td>
</tr>

<tr>
    <td class="head"><?php printf( INSTALL_PHP_EXTENSION, 'Session' ); ?></td>
    <td><?php echo xoDiag( extension_loaded( 'session' ) ? 1 : - 1, '', 1 ); ?></td>
</tr>

<tr>
    <td class="head"><?php printf( INSTALL_PHP_EXTENSION, 'PCRE' ); ?></td>
    <td><?php echo xoDiag( extension_loaded( 'pcre' ) ? 1 : - 1 ); ?></td>
</tr>
<tr>
    <td class="head" scope="row">file_uploads</td>
    <td><?php echo xoDiagBoolSetting( 'file_uploads', true ); ?></td>
</tr>
</tbody>
</table>
</fieldset>

<fieldset>
    <legend><?php echo INSTALL_RECOMMENDED_EXTENSIONS_LEGEND; ?></legend>
	<div class="message"><?php echo INSTALL_RECOMMENDED_EXTENSIONS_LEGEND_MSG; ?></div>
	<table>
	<tbody>
	<?php echo $ret; ?>
	</tbody>
	</table>
</fieldset>

<fieldset>
    <legend><?php echo INSTALL_XOOSLA_PATHS_LEGEND; ?></legend>
	<div class="message"><?php echo INSTALL_XOOSLA_PATHS_LEGEND_MSG; ?></div>
<?php



xoFormField( 'root', $ctrl->xoopsPath['root'], INSTALL_ROOT_PATH_LABEL, INSTALL_ROOT_PATH_HELP, issetError( $errors['root'] ) );
xoFormField( 'lib', $ctrl->xoopsPath['lib'], INSTALL_LIB_PATH_LABEL, INSTALL_LIB_PATH_HELP, issetError( $errors['lib'] ) );
xoFormField( 'data', $ctrl->xoopsPath['data'], INSTALL_DATA_PATH_LABEL, INSTALL_DATA_PATH_HELP, issetError( $errors['data'] ) );
xoFormField( 'URL', $ctrl->xoopsUrl, INSTALL_URL_LABEL, INSTALL_URL_HELP );

?>
</fieldset>

<script type="text/javascript">
function removeTrailing(id, val) {
    if (val[val.length-1] == '/') {
        val = val.substr(0, val.length-1);
        $(id).value = val;
    }
    return val;
}
function updPath( key, val ) {
    val = removeTrailing(key, val);
    new Ajax.Updater(
        key+'pathimg', '<?php echo $_SERVER['PHP_SELF']; ?>',
        { method:'get',parameters:'action=checkpath&var='+key+'&path='+val }
    );
    $(key+'perms').style.display='none';
}
</script>

<?php
$content = ob_get_contents();
ob_end_clean();

include './include/install_tpl.php';

?>