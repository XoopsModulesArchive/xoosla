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
 * Xoosla
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package install
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version $Id:
 */
defined( 'XOOSLA_INSTALL' ) or die( 'Direct Access To This File Not Allowed!' );

if ( empty( $vars['ROOT_PATH'] ) ) {
	$wizard->redirectToPage( 'page3' );
	exit();
}

$vars['DB_CHARSET'] = 'utf8';
$vars['DB_COLLATION'] = 'utf8_general_ci';
$params = array( 'DB_TYPE', 'DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME', 'DB_PREFIX' );
$defaults = array( 'DB_TYPE' => 'mysql',
	'DB_HOST' => '',
	'DB_USER' => '',
	'DB_PASS' => '',
	'DB_PCONNECT' => 0,
	'DB_NAME' => '',
	'DB_CHARSET' => 'utf8',
	'DB_COLLATION' => 'utf8_general_ci',
	'DB_PREFIX' => '',
	);

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	foreach ( $params as $name ) {
		if ( isset( $_POST[$name] ) && !empty( $_POST[$name] ) ) {
			$vars[$name] = $_POST[$name];
		} else {
			$vars[$name] = $defaults[$name];
			$errors[$name] = xoMissingValues( $name );
		}
	}
	$vars['DB_PCONNECT'] = @$_POST['DB_PCONNECT'] ? 1 : 0;
} else {
	/**
	 * Create default values
	 */
	$vars = array_merge( $vars,
		array( 'DB_TYPE' => 'mysql',
			'DB_HOST' => 'localhost',
			'DB_USER' => '',
			'DB_PASS' => '',
			'DB_PCONNECT' => 0,
			'DB_NAME' => '',
			'DB_CHARSET' => 'utf8',
			'DB_COLLATION' => 'utf8_general_ci',
			'DB_PREFIX' => 'x' . substr( md5( time() ), 0, 3 ),
			)
		);
}

if ( empty( $errors ) ) {
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $vars['DB_HOST'] ) && !empty( $vars['DB_USER'] ) ) {
		$func_connect = empty( $vars['DB_PCONNECT'] ) ? 'mysql_connect' : 'mysql_pconnect';
		if ( !( $link = @$func_connect( $vars['DB_HOST'], $vars['DB_USER'], $vars['DB_PASS'], true ) ) ) {
			$errors['noconnection'] = str_replace( '<br>', '<br />' , sprintf( INSTALL_ERR_PLEASE_CHECK_UANDP, mysql_errno(), mysql_error() ) ) ;
		}
		if ( empty( $errors ) ) {
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $vars['DB_NAME'] ) ) {
				$result = validateDbCharset( $link, $vars['DB_CHARSET'], $vars['DB_COLLATION'] );
				if ( $result ) {
					$errors['novalidcharset'] = $result;
				}
				$db_exist = true;
				if ( empty( $errors ) ) {
					if ( !@mysql_select_db( $vars['DB_NAME'], $link ) ) {
						// Database not here: try to create it
						$result = mysql_query( 'CREATE DATABASE `' . $vars['DB_NAME'] . '`' );
						if ( !$result ) {
							$errors['nodatabase'] = sprintf( INSTALL_INSTALL_ERR_NO_DATABASE, mysql_errno(), mysql_error() ) ;
							$db_exist = false;
						}
					}
					if ( $db_exist && $vars['DB_CHARSET'] ) {
						$sql = 'ALTER DATABASE `' . $vars['DB_NAME'] . '` DEFAULT CHARACTER SET ' . mysql_real_escape_string( $vars['DB_CHARSET'] )
						 . ( $vars['DB_COLLATION'] ? ' COLLATE ' . mysql_real_escape_string( $vars['DB_COLLATION'] ) : '' );
						$result = mysql_query( $sql );

						if ( !$result ) {
							$errors['charsetnotset'] = sprintf( INSTALL_ERR_CHARSET_NOT_SET, mysql_errno(), mysql_error() );
						}
					}
				}
				xoCreateMainFile( $errors );
				clearstatcache();
				$_SESSION['settings']['authorized'] = false;
				if ( empty( $errors ) ) {
					$_SESSION['UserLogin'] = true;
					$_SESSION['settings']['authorized'] = true;
					xoCreateTableData( $errors );
					xoWriteKey();
					if ( empty( $errors ) ) {
						$wizard->redirectToPage( 'page5' );
						exit();
					}
				}
			}
		}
	}
}
if ( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['charset'] ) && @$_GET['action'] == 'updateCollation' ) {
	echo xoFormFieldCollation( 'DB_COLLATION', $vars['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $link, $_GET['charset'] );
	exit();
}

ob_start();
xoShowErrors( $errors );

?>
<fieldset>
    <legend><?php echo INSTALL_CONNECTION_LEGEND; ?></legend>
    <div class="message"><?php echo INSTALL_CONNECTION_LEGEND_MSG; ?></div>
<?php
xoSelectField( 'DB_TYPE', $vars['DB_TYPE'], INSTALL_DB_TYPE_LABEL, INSTALL_DB_TYPE_HELP, issetError( @$errors['DB_TYPE'] ) );
xoFormField( 'DB_HOST', $vars['DB_HOST'], INSTALL_DB_HOST_LABEL, INSTALL_DB_HOST_HELP, issetError( @$errors['DB_HOST'] ) );
xoFormField( 'DB_NAME', $vars['DB_NAME'], INSTALL_DB_NAME_LABEL, INSTALL_DB_NAME_HELP, issetError( @$errors['DB_NAME'] ) );
xoFormField( 'DB_USER', $vars['DB_USER'], INSTALL_DB_USER_LABEL, INSTALL_DB_USER_HELP, issetError( @$errors['DB_USER'] ) );
xoPassField( 'DB_PASS', $vars['DB_PASS'], INSTALL_DB_PASS_LABEL, INSTALL_DB_PASS_HELP, issetError( @$errors['DB_PASS'] ) );
xoFormField( 'DB_PREFIX', $vars['DB_PREFIX'], INSTALL_DB_PREFIX_LABEL, INSTALL_DB_PREFIX_HELP, issetError( @$errors['DB_PREFIX'] ) );
xoCheckBoxField( 'DB_PCONNECT', $vars['DB_PCONNECT'], INSTALL_DB_PCONNECT_LABEL, INSTALL_DB_PCONNECT_HELP );
xoFormHiddenField( 'DB_CHARSET', $vars['DB_CHARSET'] );
xoFormHiddenField( 'DB_COLLATION', $vars['DB_COLLATION'] );

?>
</fieldset>
<?php
$content = ob_get_contents();
ob_end_clean();
include './include/install_tpl.php';

?>