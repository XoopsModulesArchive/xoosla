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
 * @version $Id$
 */

/**
 * install_acceptUser()
 *
 * @param string $hash
 * @return
 */
function install_acceptUser( $hash = '' )
{
	$GLOBALS['xoopsUser'] = null;
	$hash_data = @explode( '-', $_COOKIE['xo_install_user'], 2 );
	list( $uname, $hash_login ) = array( $hash_data[0], strval( @$hash_data[1] ) );
	if ( empty( $uname ) || empty( $hash_login ) ) {
		return false;
	}
	$memebr_handler = xoops_gethandler( 'member' );
	$user = array_pop( $memebr_handler->getUsers( new Criteria( 'uname', $uname ) ) );
	if ( $hash_login != md5( $user->getVar( 'pass' ) . XOOPS_DB_NAME . XOOPS_DB_PASS . XOOPS_DB_PREFIX ) ) {
		return false;
	}
	$myts = MyTextsanitizer::getInstance();
	if ( is_object( $GLOBALS['xoops'] ) && method_exists( $GLOBALS['xoops'], 'acceptUser' ) ) {
		$res = $GLOBALS['xoops']->acceptUser( $uname, true, $msg );
		return $res;
	}
	$GLOBALS['xoopsUser'] = $user;
	$_SESSION['xoopsUserId'] = $GLOBALS['xoopsUser']->getVar( 'uid' );
	$_SESSION['xoopsUserGroups'] = $GLOBALS['xoopsUser']->getGroups();
	return true;
}

/**
 * install_finalize()
 *
 * @param mixed $installer_modified
 * @return
 */
function install_finalize( $installer_modified )
{
	// Set mainfile.php readonly
	@chmod( XOOPS_ROOT_PATH . '/mainfile.php', 0444 );
	// Set Secure file readonly
	@chmod( XOOPS_VAR_PATH . '/data/secure.php', 0444 );
	// Rename installer folder
	@rename( XOOPS_ROOT_PATH . '/install', XOOPS_ROOT_PATH . '/' . $installer_modified );
}

/**
 * xoFormField()
 *
 * @param mixed $name
 * @param mixed $value
 * @param mixed $label
 * @param string $help
 * @return
 */
function xoFormField( $name, $value, $label, $help = '', $parms = '' )
{
	$myts = MyTextSanitizer::getInstance();
	$name = $myts->htmlspecialchars( $name, ENT_QUOTES, _INSTALL_CHARSET, false );
	$value = $myts->htmlspecialchars( $value, ENT_QUOTES );

	$ret = '<label class="xolabel" for="' . $name . '">' . $label . '</label>' . NEWLINE;
	$ret .= '	<div class="xoform-container">		<div class="floatleft">' . NEWLINE;
	if ( $name == 'adminname' ) {
		$ret .= '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="34" maxlength="25" />' . NEWLINE;
	} else {
		$ret .= '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="34" /> ' . NEWLINE;
	}
	if ( $parms == - 1 ) {
		$ret .= xoDisplayErrorImg( $parms );
	}
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="floatright">' . NEWLINE;
	if ( $help ) {
		$ret .= ' <span class="xoform-help">' . $help . '</span>' . NEWLINE;
	}
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="clear"></div>' . NEWLINE;
	$ret .= '</div>';
	echo $ret;
}

/**
 * xoSelectField()
 *
 * @param mixed $name
 * @param mixed $value
 * @param mixed $label
 * @param string $help
 * @param string $parms
 * @return
 */
function xoSelectField( $name, $value, $label, $help = '', $parms = '' )
{
	$myts = MyTextSanitizer::getInstance();
	$name = $myts->htmlspecialchars( $name, ENT_QUOTES, _INSTALL_CHARSET, false );
	$value = $myts->htmlspecialchars( $value, ENT_QUOTES );

	$ret = '<label class="xolabel" for="' . $name . '">' . $label . '</label><div class="xoform-container">' . NEWLINE;
	$ret .= '	<div class="floatleft">' . NEWLINE;
	$ret .= '<select size="1" name="DB_TYPE">' . NEWLINE;
	foreach ( $GLOBALS['wizard']->configs['db_types'] as $db_type ) {
		$selected = ( $value == $db_type ) ? 'selected' : '';
		$ret .= '<option value="' . $db_type . '" selected="' . $selected . '">' . $db_type . '</option>' . NEWLINE;
	}
	$ret .= '</select>' . NEWLINE;
	if ( $parms == - 1 ) {
		$ret .= xoDisplayErrorImg( $parms );
	}
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="floatright">' . NEWLINE;
	if ( $help ) {
		$ret .= ' <span class="xoform-help">' . $help . '</span>' . NEWLINE;
	}
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="clear"></div>' . NEWLINE;
	$ret .= '</div>';
	echo $ret;
}

/**
 * xoPassField()
 *
 * @param mixed $name
 * @param mixed $value
 * @param mixed $label
 * @param string $help
 * @return
 */
function xoPassField( $name, $value, $label, $help = '', $parms = '' )
{
	$myts = MyTextSanitizer::getInstance();
	$name = $myts->htmlspecialchars( $name, ENT_QUOTES, _INSTALL_CHARSET, false );
	$value = $myts->htmlspecialchars( $value, ENT_QUOTES );

	$ret = '<label class="xolabel" for="' . $name . '">' . $label . '</label><div class="xoform-container">' . NEWLINE;
	$ret .= '	<div class="floatleft">' . NEWLINE;
	if ( $name == 'adminname' ) {
		$ret .= '<input type="password" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="34" maxlength="25" />' . NEWLINE;
	} else {
		$ret .= '<input type="password" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="34"/>' . NEWLINE;
	}
	if ( $parms == - 1 ) {
		$ret .= xoDisplayErrorImg( $parms );
	}
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="floatright">' . NEWLINE;
	if ( $help ) {
		$ret .= ' <span class="xoform-help">' . $help . '</span>' . NEWLINE;
	}
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="clear"></div>' . NEWLINE;
	$ret .= '</div>';
	echo $ret;
}

/**
 * xoOtherField()
 *
 * @param mixed $name
 * @param mixed $value
 * @param mixed $label
 * @param string $help
 * @param string $parms
 * @return
 */
function xoCheckBoxField( $name, $value, $label, $help = '', $parms = '' )
{
	$myts = MyTextSanitizer::getInstance();
	$name = $myts->htmlspecialchars( $name, ENT_QUOTES, _INSTALL_CHARSET, false );
	$value = $myts->htmlspecialchars( $value, ENT_QUOTES );

	$checked = $value ? '"checked"' : '';

	$ret = '<label class="xolabel" for="' . $name . '">' . $label . '</label><div class="xoform-container">' . NEWLINE;
	$ret .= '	<div class="floatleft">' . NEWLINE;
	$ret .= '<input class="checkbox" type="checkbox" name="' . $name . '" id="' . $name . '" value="1"  ' . $checked . '/> ' . NEWLINE;
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="floatright">' . NEWLINE;
	if ( $help ) {
		$ret .= ' <span class="xoform-help">' . $help . '</span>' . NEWLINE;
	}
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="clear"></div>' . NEWLINE;
	$ret .= '</div>';
	echo $ret;
}

/**
 * xoDivBoxes()
 *
 * @param mixed $left
 * @param mixed $right
 * @return
 */
function xoDivBoxes( $left, $right )
{
	$ret = '<div class="xoform-container">' . NEWLINE;
	$ret .= '	<div class="floatleft">' . NEWLINE;
	$ret .= $left;
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="floatright">' . NEWLINE;
	$ret .= $right;
	$ret .= '</div>' . NEWLINE;
	$ret .= '<div class="clear"></div>' . NEWLINE;
	$ret .= '</div>';
	return $ret;
}

/**
 * xoFormHiddenField()
 *
 * @param mixed $name
 * @param mixed $value
 * @return
 */
function xoFormHiddenField( $name, $value )
{
	$myts = MyTextSanitizer::getInstance();
	$name = $myts->htmlspecialchars( $name, ENT_QUOTES, _INSTALL_CHARSET, false );
	$value = $myts->htmlspecialchars( $value, ENT_QUOTES );
	echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . NEWLINE;;
}

/*
 * gets list of name of directories inside a directory
 */
function getDirList( $dirname )
{
	$dirlist = array();
	if ( $handle = opendir( $dirname ) ) {
		while ( $file = readdir( $handle ) ) {
			if ( $file {
					0} != '.' && is_dir( $dirname . $file ) ) {
				$dirlist[] = $file;
			}
		}
		closedir( $handle );
		asort( $dirlist );
		reset( $dirlist );
	}
	return $dirlist;
}

/**
 * xoDiag()
 *
 * @param mixed $status
 * @param string $str
 * @return
 */
function xoDiag( $status = - 1, $str = '', $show = 1 )
{
	if ( $status == - 1 ) {
		$GLOBALS['error'] = true;
	}
	$classes = array( - 1 => 'error', 0 => 'warning', 1 => 'success', 2 => 'ignore' );
	$strings = array( - 1 => INSTALL_FAILED, 0 => INSTALL_WARNING, 1 => INSTALL_SUCCESS, 2 => '' );

	if ( empty( $str ) && $show == 1 ) {
		$str = $strings[$status];
	}
	if ( isset( $classes[$status] ) ) {
		return '<span class="' . $classes[$status] . '" title="' . $classes[$status] . '">' . $str . '</span>';
	}
}

/**
 * xoDiagBoolSetting()
 *
 * @param mixed $name
 * @param mixed $wanted
 * @param mixed $severe
 * @return
 */
function xoDiagBoolSetting( $name, $wanted = false, $severe = false )
{
	$setting = strtolower( ini_get( $name ) );
	$setting = ( empty( $setting ) || $setting == 'off' || $setting == 'false' ) ? false : true;
	if ( $setting == $wanted ) {
		return xoDiag( 1, $setting ? INSTALL_ON : INSTALL_OFF );
	} else {
		return xoDiag( $severe ? - 1 : 0, $setting ? INSTALL_ON : INSTALL_OFF );
	}
}

/**
 * xoDiagIfWritable()
 *
 * @param mixed $path
 * @return
 */
// function xoDiagIfWritable( $path )
// {
// $path = '../' . $path;
// $errors['mainfile'] = true;
// if ( !is_dir( $path ) ) {
// if ( file_exists( $path ) ) {
//  @chmod ( $path, 0666 );
// $errors['mainfile'] = !is_writeable( $path );
// }
// } else {
//  @chmod ( $path, 0777 );
// $errors['mainfile'] = !is_writeable( $path );
// }
// return xoDiag( $errors['mainfile'] ? - 1 : 1, $errors['mainfile'] ? 'Not writable' : 'Writable' );
// }
/**
 * xoPhpVersion()
 *
 * @return
 */
function xoPhpVersion()
{
	if ( version_compare( phpversion(), '5.2.0', '>=' ) ) {
		return xoDiag( 1, phpversion() );
	} else if ( version_compare( phpversion(), '5.1.0', '>=' ) ) {
		return xoDiag( 0, phpversion() );
	} else {
		return xoDiag( - 1, phpversion() );
	}
}

/**
 * genPathCheckHtml()
 *
 * @param mixed $path
 * @param mixed $valid
 * @return
 */
function genPathCheckHtml( $path, $valid )
{
	if ( $valid ) {
		switch ( $path ) {
			case 'root':
				$msg = sprintf( INSTALL_XOOSLA_FOUND, XOOPS_VERSION );
				break;
			case 'lib':
			case 'data':
			default:
				$msg = INSTALL_XOOSLA_PATH_FOUND;
				break;
		}
		// return '<span class="pathmessage"><img src="img/yes.png" alt="Success" />&nbsp;' . $msg . '</span>';
	} else {
		switch ( $path ) {
			case 'root':
				$msg = INSTALL_ERR_NO_INSTALL_XOOSLA_FOUND;
				break;

			case 'lib':
			case 'data':
			default:
				$msg = INSTALL_ERR_COULD_NOT_ACCESS;
				break;
		}
		return $msg;
	}
}

/**
 * getDbCharsets()
 *
 * @param mixed $link
 * @return
 */
function getDbCharsets( $link )
{
	static $charsets = array();
	if ( $charsets ) {
		return $charsets;
	}
	$charsets['utf8'] = 'UTF-8 Unicode';
	$ut8_available = false;
	if ( $result = mysql_query( 'SHOW CHARSET', $link ) ) {
		while ( $row = mysql_fetch_assoc( $result ) ) {
			$charsets[$row['Charset']] = $row['Description'];
			if ( $row['Charset'] == 'utf8' ) {
				$ut8_available = true;
			}
		}
	}
	if ( !$ut8_available ) {
		unset( $charsets['utf8'] );
	}
	return $charsets;
}
/**
 * getDbCollations()
 *
 * @param mixed $link
 * @param mixed $charset
 * @return
 */
function getDbCollations( $link, $charset )
{
	static $collations = array();
	if ( !empty( $collations[$charset] ) ) {
		return $collations[$charset];
	}
	if ( $result = mysql_query( 'SHOW COLLATION WHERE CHARSET = "' . mysql_real_escape_string( $charset ) . '"', $link ) ) {
		while ( $row = mysql_fetch_assoc( $result ) ) {
			$collations[$charset][$row['Collation']] = $row['Default'] ? 1 : 0;
		}
	}
	return $collations[$charset];
}
/**
 * validateDbCharset()
 *
 * @param mixed $link
 * @param mixed $charset
 * @param mixed $collation
 * @return
 */
function validateDbCharset( $link, &$charset, &$collation )
{
	$errors['mainfile'] = false;
	if ( empty( $charset ) ) {
		$collation = '';
	}
	if ( version_compare( mysql_get_server_info( $link ), '4.1.0', 'lt' ) ) {
		$charset = $collation = '';
	}
	if ( empty( $charset ) && empty( $collation ) ) {
		// return sprintf( INSTALL_ERR_INVALID_DBCHARSET, $charset );
	}

	$charsets = getDbCharsets( $link );
	if ( !isset( $charsets[$charset] ) ) {
		return sprintf( INSTALL_ERR_INVALID_DBCHARSET, $charset );
	} else if ( !empty( $collation ) ) {
		$collations = getDbCollations( $link, $charset );
		if ( !isset( $collations[$collation] ) ) {
			return sprintf( INSTALL_ERR_INVALID_DBCOLLATION, $collation );
		}
	}
}

/**
 * xoFormBlockCollation()
 *
 * @param mixed $name
 * @param mixed $value
 * @param mixed $label
 * @param mixed $help
 * @param mixed $link
 * @param mixed $charset
 * @return
 */
function xoFormBlockCollation( $name, $value, $label, $help, $link, $charset )
{
	$block = '<div id="' . $name . '_div">';
	$block .= xoFormFieldCollation( $name, $value, $label, $help, $link, $charset );
	$block .= '</div>';

	return $block;
}

/**
 * xoPutLicenseKey()
 *
 * @param mixed $system_key
 * @param mixed $licensefile
 * @param string $license_file_dist
 * @return
 */
function xoPutLicenseKey( $system_key, $licensefile, $license_file_dist = 'license.dist.php' )
{
	chmod( $licensefile, 0777 );
	$fver = fopen( $licensefile, 'w' );
	$fver_buf = file( $license_file_dist );
	foreach ( $fver_buf as $line => $value ) {
		if ( strpos( $value, 'XOOPS_LICENSE_KEY' ) > 0 ) {
			$ret = "define( 'XOOPS_LICENSE_KEY', '" . $system_key . "' );";
		} else {
			$ret = $value;
		}
		fwrite( $fver, $ret, strlen( $ret ) );
	}
	fclose( $fver );
	chmod( $licensefile, 0444 );
	return 'Written Xoosla ' . XOOPS_LICENSE_CODE . ' License Key: <strong>' . $system_key . '</strong>';
}

/**
 * xoBuildLicenceKey()
 *
 * @return
 */
function xoBuildLicenceKey()
{
	$xoops_serdat = array();
	srand( ( ( (float)( '0' . substr( microtime(), strpos( microtime(), ' ' ) + 1, strlen( microtime() ) - strpos( microtime(), ' ' ) + 1 ) ) ) * mt_rand( 30, 99999 ) ) );
	srand( ( ( (float)( '0' . substr( microtime(), strpos( microtime(), ' ' ) + 1, strlen( microtime() ) - strpos( microtime(), ' ' ) + 1 ) ) ) * mt_rand( 30, 99999 ) ) );
	$checksums = array( 1 => 'md5', 2 => 'sha1' );
	$type = rand( 1, 2 );
	$func = $checksums[$type];

	error_reporting( 0 );
	// Public Key
	if ( $xoops_serdat['version'] = $func( XOOPS_VERSION ) ) {
		$xoops_serdat['version'] = substr( $xoops_serdat['version'], 0, 6 );
	}
	if ( $xoops_serdat['licence'] = $func( XOOPS_LICENSE_CODE ) ) {
		$xoops_serdat['licence'] = substr( $xoops_serdat['licence'], 0, 2 );
	}
	if ( $xoops_serdat['license_text'] = $func( XOOPS_LICENSE_TEXT ) ) {
		$xoops_serdat['license_text'] = substr( $xoops_serdat['license_text'], 0, 2 );
	}

	if ( $xoops_serdat['domain_host'] = $func( $_SERVER['HTTP_HOST'] ) ) {
		$xoops_serdat['domain_host'] = substr( $xoops_serdat['domain_host'], 0, 2 );
	}
	// Private Key
	$xoops_serdat['file'] = $func( __FILE__ );
	$xoops_serdat['basename'] = $func( basename( __FILE__ ) );
	$xoops_serdat['path'] = $func( dirname( __FILE__ ) );

	foreach ( $_SERVER as $key => $data ) {
		$xoops_serdat[$key] = substr( $func( serialize( $data ) ), 0, 4 );
	}

	foreach ( $xoops_serdat as $key => $data ) {
		$xoops_key .= $data;
	} while ( strlen( $xoops_key ) > 40 ) {
		$lpos = rand( 18, strlen( $xoops_key ) );
		$xoops_key = substr( $xoops_key, 0, $lpos ) . substr( $xoops_key, $lpos + 1 , strlen( $xoops_key ) - ( $lpos + 1 ) );
	}
	return xoStripeKey( $xoops_key );
}

/**
 * *#@+
 * Xoops Stripe Licence System Key
 */
function xoStripeKey( $xoops_key )
{
	$uu = 0;
	$num = 6;
	$length = 30;
	$strip = floor( strlen( $xoops_key ) / 6 );
	for ( $i = 0; $i < strlen( $xoops_key ); $i++ ) {
		if ( $i < $length ) {
			$uu++;
			if ( $uu == $strip ) {
				$ret .= substr( $xoops_key, $i, 1 ) . '-';
				$uu = 0;
			} else {
				if ( substr( $xoops_key, $i, 1 ) != '-' ) {
					$ret .= substr( $xoops_key, $i, 1 );
				} else {
					$uu--;
				}
			}
		}
	}
	$ret = str_replace( '--', '-', $ret );
	if ( substr( $ret, 0, 1 ) == '-' ) {
		$ret = substr( $ret, 2, strlen( $ret ) );
	}
	if ( substr( $ret, strlen( $ret ) - 1, 1 ) == '-' ) {
		$ret = substr( $ret, 0, strlen( $ret ) - 1 );
	}
	return $ret;
}

/**
 * xoShowErrors()
 *
 * @param mixed $val
 * @return
 */
function xoShowErrors( $errors )
{
	$errors = array_filter( $errors );
	if ( !count( $errors ) ) {
		return;
	}
	if ( !is_array( $errors ) ) {
		return $errors;
	}
	$ret = '<div class="t-error">';
	foreach ( $errors as $k => $v ) {
		if ( !empty( $v ) ) {
			$ret .= '<span class="pathmessage">&bull;&nbsp;' . $v . '</span><br />';
		}
	}
	$ret .= '</div>';
	echo $ret;
	unset( $ret );
}

/**
 * xoShowNotice()
 *
 * @param mixed $errors
 * @return
 */
function xoShowNotice( $errors, $msg = 't-info' )
{
	$ret = '<div class="' . $msg . '">' . $errors . '</div>';
	echo $ret;
	unset( $ret );
}

/**
 * xoMissingValues()
 *
 * @param string $val
 * @return
 */
function xoMissingValues( $val = '' )
{
	$params = array(
		'DB_TYPE' => INSTALL_MISSING_DBTYPE,
		'DB_HOST' => INSTALL_MISSING_DBHOST,
		'DB_USER' => INSTALL_MISSING_DBUSER,
		'DB_PASS' => INSTALL_MISSING_DBPASS,
		'DB_NAME' => INSTALL_MISSING_DNAME,
		'DB_PREFIX' => INSTALL_MISSING_DBPREFIX
		);
	if ( $val != '' && isset( $params[$val] ) ) {
		return INSTALL_REQUIRED_MISSING . $params[$val];
	}
	return;
}

/**
 * issetError()
 *
 * @param mixed $errors
 * @return
 */
function issetError( $errors )
{
	return ( isset( $errors ) && !empty( $errors ) ) ? - 1 : 1;
}

/**
 * xoDisplayErrors()
 *
 * @param mixed $val
 * @return
 */
function xoDisplayErrors( &$parms, $key )
{
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET' ) {
		return ( isset( $parms[$key] ) && $parms[$key] != false ) ? xoDiag( $parms[$key] ? - 1 : 2, $parms[$key], '', 0 ) : '';
	}
}

/**
 * xoDisplayErrors()
 *
 * @param mixed $parms
 * @param mixed $key
 * @return
 */
function xoDisplayErrorImg( $parm )
{
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET' ) {
		return xoDiag( $parm, '', 0 );
	}
}

/**
 * xoCreateMainFile()
 *
 * @param mixed $errors
 * @return
 */
function xoCreateMainFile( &$errors )
{
	$vars = $_SESSION['settings'];
	if ( empty( $vars['VAR_PATH'] ) ) {
		$wizard->redirectToPage( 'page1' );
		exit();
	}

	if ( !@copy( $vars['ROOT_PATH'] . '/mainfile.dist.php', $vars['ROOT_PATH'] . '/mainfile.php' ) ) {
		$errors['mainfile'] = INSTALL_ERR_COPY_MAINFILE;
	} else {
		clearstatcache();
		$rewrite = array( 'GROUP_ADMIN' => 1,
			'GROUP_USERS' => 2,
			'GROUP_ANONYMOUS' => 3
			);
		$rewrite = array_merge( $rewrite, $vars );
		if ( !$file = fopen( $vars['ROOT_PATH'] . '/mainfile.php', 'r' ) ) {
			$errors['mainfile'] = INSTALL_ERR_READ_MAINFILE;
		} else {
			$content = fread( $file, filesize( $vars['ROOT_PATH'] . '/mainfile.php' ) );
			fclose( $file );
			foreach ( $rewrite as $key => $val ) {
				if ( $key == 'authorized' ) continue;
				if ( is_int( $val ) && preg_match( "/(define\()([\"'])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", $content ) ) {
					$content = preg_replace( "/(define\()(['\"])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", "define( 'XOOPS_{$key}', {$val} )", $content );
				} else if ( preg_match( "/(define\()(['\"])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", $content ) ) {
					$val = str_replace( '$', '\$', addslashes( $val ) );
					$content = preg_replace( "/(define\()(['\"])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", "define( 'XOOPS_{$key}', '{$val}' )", $content );
				}
			}
			if ( !$file = fopen( $vars['ROOT_PATH'] . '/mainfile.php', 'w' ) ) {
				$errors['mainfile'] = INSTALL_ERR_WRITE_MAINFILE;
			} else {
				if ( fwrite( $file, $content ) == - 1 ) {
					$errors['mainfile'] = INSTALL_ERR_WRITE_MAINFILE;
				}
				fclose( $file );
			}
		}
	}
	if ( file_exists( $rewrite['VAR_PATH'] . '/data/secure.php' ) ) {
		chmod( $rewrite['VAR_PATH'] . '/data/secure.php', 0777 );
	}
	if ( !copy( $rewrite['VAR_PATH'] . '/data/secure.dist.php', $rewrite['VAR_PATH'] . '/data/secure.php' ) ) {
		$errors['mainfile'] = INSTALL_ERR_COPY_MAINFILE . $rewrite['VAR_PATH'] . '/data/secure.dist.php';
	} else {
		clearstatcache();
		$rewrite = array_merge( $rewrite, $vars );
		if ( !$file = fopen( $rewrite['VAR_PATH'] . '/data/secure.php', "r" ) ) {
			$errors['mainfile'] = INSTALL_ERR_READ_MAINFILE;
		} else {
			$content = fread( $file, filesize( $rewrite['VAR_PATH'] . '/data/secure.php' ) );
			fclose( $file );
			foreach ( $rewrite as $key => $val ) {
				if ( $key == 'authorized' ) continue;
				if ( is_int( $val ) && preg_match( "/(define\()([\"'])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", $content ) ) {
					$content = preg_replace( "/(define\()([\"'])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", "define( 'XOOPS_{$key}', {$val} )", $content );
				} else if ( preg_match( "/(define\()([\"'])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", $content ) ) {
					$val = str_replace( '$', '\$', addslashes( $val ) );
					$content = preg_replace( "/(define\()([\"'])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", "define( 'XOOPS_{$key}', '{$val}' )", $content );
				}
			}
			if ( !$file = fopen( $rewrite['VAR_PATH'] . '/data/secure.php', "w" ) ) {
				$errors['mainfile'] = INSTALL_ERR_WRITE_MAINFILE;
			} else {
				if ( fwrite( $file, $content ) == - 1 ) {
					$errors['mainfile'] = INSTALL_ERR_WRITE_MAINFILE;
				}
				fclose( $file );
			}
		}
	}
	clearstatcache();
}

/**
 * xoCreateTableData()
 *
 * @param mixed $errors
 * @return
 */
function xoCreateTableData( &$errors )
{
	$vars = $_SESSION['settings'];
	if ( empty( $vars['ROOT_PATH'] ) || empty( $vars['DB_HOST'] ) ) {
		$GLOBALS['wizard']->redirectToPage( 'page4' );
		exit();
	}

	@require XOOPS_ROOT_PATH . '/mainfile.php';
	require_once XOOPS_ROOT_PATH . '/install/class/dbmanager.php';
	$dbm = new db_manager();
	if ( !$dbm->isConnectable() ) {
		$GLOBALS['wizard']->redirectToPage( 'page4' );
		exit();
	}
	if ( $dbm->tableExists( 'users' ) ) {
		$GLOBALS['wizard']->redirectToPage( 'page5' );
	} else {
		$result = $dbm->queryFromFile( XOOPS_ROOT_PATH . '/install/sql/' . XOOPS_DB_TYPE . '.xoosla.sql' );
		if ( !$result ) {
			$errors['tabledata'] = TABLES_CREATION_FAILURE . '<br />' . $result . '<br />' . $dbm->report();
		}
	}
}

/**
 * xoCreateAdminData()
 *
 * @param mixed $errors
 * @return
 */
function xoCreateAdminData( &$errors, &$dbm, $username = '', $useremail = '', $password = '' )
{
	$errors = array();

	if ( empty( $username ) || empty( $useremail ) || empty( $password ) ) {
		$errors['userdetails'] = 'User details not found';
	} else {
		$password = md5( $password );
		$regdate = time();
		$res = $dbm->insert( 'users', " VALUES (1, '', '" . addslashes( $username ) . "', '" . addslashes( $useremail ) . "','" . XOOPS_URL . "/','avatars/default.png','" . $regdate . "','','','',1,'','','','','" . $password . "',0,0,7,5,'default','0.0'," . time() . ",'flat',0,1,0,'','','',0)" );
		if ( !$res ) {
			$errors['userdetails'] = mysql_errno() . ": " . mysql_error() . "\n"; //'Error Saving to database: User details not updated.';
		}
	}
	return $errors;
}

/**
 * write_key()
 *
 * @return
 */
function xoWriteKey()
{
	set_time_limit( 180 );
	return xoPutLicenseKey( xoBuildLicenceKey(), XOOPS_ROOT_PATH . '/include/license.php', XOOPS_ROOT_PATH . '/install/include/license.dist.php' );
}

/**
 * xoCheckPermissions()
 *
 * @return
 */
function xoCheckPermissions( &$ctrl, $name )
{
	$ret = '';
	if ( $ctrl->validPath[$name] && !empty( $ctrl->permErrors[$name] ) ) {
		$ret .= '<div id="' . $name . 'rootperms" class="x2-note">';
		$ret .= CHECKING_PERMISSIONS . '<br /><p>' . INSTALL_ERR_NEED_WRITE_ACCESS . '</p>';
		$ret .= '<ul class="diags">';
		foreach ( $ctrl->permErrors['root'] as $path => $result ) {
			if ( $result ) {
				$ret .= '<li class="success">' . sprintf( INSTALL_IS_WRITABLE, $path ) . '</li>';
			} else {
				$ret .= '<li class="failure">' . sprintf( INSTALL_IS_NOT_WRITABLE, $path ) . '</li>';
			}
		}
		$ret .= '</ul></div>';
	} else {
		$ret .= '<div id="rootperms" class="x2-note" style="display: none;"></div>';
	}
	echo $ret;
}

/**
 * xoGetSupports()
 *
 * @return
 */
function xoGetSupports()
{
	static $supports;

	if ( !isset( $supports ) ) {
		$temps = getDirList( './language/' );
		if ( !empty( $temps ) ) {
			foreach ( $temps as $temp ) {
				include './language/' . $temp . '/support.php';
				if ( is_array( $xoSupport ) ) {
					$supports = $xoSupport;
				}
			}
		}
	}
	return $supports;
}

function getSupportHtml()
{
	static $langSelect;

	if ( !isset( $langSelect ) ) {
		$supports = xoGetSupports();
		if ( !empty( $supports ) ) {
			$supportSelection = '<select id="select" class="select" onchange="javascript:window.open(this.value);">';
			$supportSelection .= '<option value="#">' . INSTALL_SUPPORT . '</option>';
			foreach ( $supports as $k => $v ) {
				$supportSelection .= '<option value="' . $v['url'] . '"';
				if ( file_exists( './language/' . $k . '/support.png' ) ) {
					$supportSelection .= ' class="option" style="background-image:url(./language/' . $k . '/support.png); background-repeat: no-repeat;"';
				}
				$supportSelection .= '>' . $v['title'] . '</option>';
			}
			$supportSelection .= '</select>';
			$langSelect = $supportSelection;
		}
	}
	return $langSelect;
}

/**
 * getPagesHtml()
 *
 * @return
 */
function getPagesHtml()
{
	static $page_navigation;
	global $wizard;

	if ( !isset( $page_navigation ) ) {
		$page_nav = '';
		$i = 1;
		foreach ( array_keys( $wizard->pages ) as $k => $page ) {
			$class = '';
			if ( $k == $wizard->pageIndex ) {
				$class = ' class="current"';
			} else if ( $k > $wizard->pageIndex ) {
				$class = ' class="disabled"';
			}
			if ( empty( $class ) ) {
				$li = '<a href="' . $wizard->pageURI( $page ) . '">' . $wizard->pages[$page]['name'] . '</a>';
			} else {
				$li = $wizard->pages[$page]['name'];
			}
			$page_nav .= "<li$class>$i : $li</li>\n";
			$i++;
		}
		$page_navigation = $page_nav;
	}
	return $page_navigation;
}

/**
 * xoGetSelectLanguages()
 *
 * @return
 */
function xoGetSelectLanguages()
{
	static $select_languages;
	global $wizard;

	if ( !isset( $select_languages ) ) {
		$languages = xoGetLanguages();
		$native = array( strtolower( $wizard->language ) => $languages[strtolower( $wizard->language )] );
		$languages = array_merge( $native, $languages );

		$selectBox = '<select id="countries" name="lang" size="15" style="width: 25">';
		foreach ( $languages as $k => $v ) {
			$selected = ( $k == strtolower( $wizard->language ) ) ? ' selected="selected"' : '';
			$selectBox .= '<option value="' . $k . '"' . $selected . '>' . $v . '</option>' . NEWLINE;
		}
		$selectBox .= '</select>';
		$select_languages = $selectBox;
	}
	return $select_languages;
}

/**
 * get_languages()
 *
 * @param mixed $language
 * @return
 */
function xoGetLanguages( $user_language = 'en-GB' )
{
	if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		$user_language = substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5 );
	} else {
		$user_language = 'en-GB';
	}
	setcookie( 'xo_install_lang', strtolower( $user_language ), null, null, null );

	$languages = getDirList( './language/' );
	$a_languages = languages();

	$lang_array = array();
	foreach ( $languages as $lang ) {
		$lang = strtolower( $lang );
		if ( array_key_exists( $lang, $a_languages ) ) {
			$lang_array[$lang] = $a_languages[$lang];
		}
	}
	// get the languages
	return $lang_array;
}

/**
 * languages()
 *
 * @return
 */
function languages()
{
	// pack abbreviation/language array
	// important note: you must have the default language as the last item in each major language, after all the
	// en-ca type entries, so en would be last in that case
	$a_languages = array(
		'af' => 'Afrikaans',
		'sq' => 'Albanian',
		'ar-dz' => 'Arabic الجزائر (Algeria)',
		'ar-bh' => 'Arabic (Bahrain)',
		'ar-eg' => 'Arabic (Egypt)',
		'ar-iq' => 'Arabic (Iraq)',
		'ar-jo' => 'Arabic (Jordan)',
		'ar-kw' => 'Arabic (Kuwait)',
		'ar-lb' => 'Arabic (Lebanon)',
		'ar-ly' => 'Arabic (libya)',
		'ar-ma' => 'Arabic (Morocco)',
		'ar-om' => 'Arabic (Oman)',
		'ar-qa' => 'Arabic (Qatar)',
		'ar-sa' => 'Arabic (Saudi Arabia)',
		'ar-sy' => 'Arabic (Syria)',
		'ar-tn' => 'Arabic (Tunisia)',
		'ar-ae' => 'Arabic (U.A.E.)',
		'ar-ye' => 'Arabic (Yemen)',
		'ar' => 'Arabic',
		'hy' => 'Armenian',
		'as' => 'Assamese',
		'az' => 'Azeri',
		'eu' => 'Basque',
		'be' => 'Belarusian',
		'bn' => 'Bengali',
		'bg' => 'Bulgarian',
		'ca' => 'Catalan',
		'zh-cn' => 'Chinese (China)',
		'zh-hk' => 'Chinese (Hong Kong SAR)',
		'zh-mo' => 'Chinese (Macau SAR)',
		'zh-sg' => 'Chinese (Singapore)',
		'zh-tw' => 'Chinese (Taiwan)',
		'zh' => 'Chinese',
		'hr' => 'Croatian',
		'cs' => 'Czech',
		'da' => 'Danish',
		'div' => 'Divehi',
		'nl-be' => 'Dutch (Belgium)',
		'nl' => 'Dutch (Netherlands)',
		'en-au' => 'English (Australia)',
		'en-bz' => 'English (Belize)',
		'en-ca' => 'English (Canada)',
		'en-ie' => 'English (Ireland)',
		'en-jm' => 'English (Jamaica)',
		'en-nz' => 'English (New Zealand)',
		'en-ph' => 'English (Philippines)',
		'en-za' => 'English (South Africa)',
		'en-tt' => 'English (Trinidad)',
		'en-gb' => 'English (United Kingdom)',
		'en-us' => 'English (United States)',
		'en-zw' => 'English (Zimbabwe)',
		'en' => 'English',
		'us' => 'English (United States)',
		'et' => 'Estonian',
		'fo' => 'Faeroese',
		'fa' => 'Farsi',
		'fi' => 'Finnish',
		'fr-be' => 'French (Belgium)',
		'fr-ca' => 'French (Canada)',
		'fr-lu' => 'French (Luxembourg)',
		'fr-mc' => 'French (Monaco)',
		'fr-ch' => 'French (Switzerland)',
		'fr' => 'French (France)',
		'mk' => 'FYRO Macedonian',
		'gd' => 'Gaelic',
		'ka' => 'Georgian',
		'de-at' => 'German (Austria)',
		'de-li' => 'German (Liechtenstein)',
		'de-lu' => 'German (Luxembourg)',
		'de-ch' => 'German (Switzerland)',
		'de' => 'German (Germany)',
		'el' => 'Greek',
		'gu' => 'Gujarati',
		'he' => 'Hebrew',
		'hi' => 'Hindi',
		'hu' => 'Hungarian',
		'is' => 'Icelandic',
		'id' => 'Indonesian',
		'it-ch' => 'Italian (Switzerland)',
		'it' => 'Italian (Italy)',
		'ja' => '日本語 (Japanese)',
		'kn' => 'Kannada',
		'kk' => 'Kazakh',
		'kok' => 'Konkani',
		'ko' => 'Korean',
		'kz' => 'Kyrgyz',
		'lv' => 'Latvian',
		'lt' => 'Lithuanian',
		'ms' => 'Malay',
		'ml' => 'Malayalam',
		'mt' => 'Maltese',
		'mr' => 'Marathi',
		'mn' => 'Mongolian (Cyrillic)',
		'ne' => 'Nepali (India)',
		'nb-no' => 'Norwegian (Bokmal)',
		'nn-no' => 'Norwegian (Nynorsk)',
		'no' => 'Norwegian (Bokmal)',
		'or' => 'Oriya',
		'pl' => 'Polish',
		'pt-br' => 'Portuguese (Brazil)',
		'pt' => 'Portuguese (Portugal)',
		'pa' => 'Punjabi',
		'rm' => 'Rhaeto-Romanic',
		'ro-md' => 'Romanian (Moldova)',
		'ro' => 'Romanian',
		'ru-md' => 'Russian (Moldova)',
		'ru' => 'Russian',
		'sa' => 'Sanskrit',
		'sr' => 'Serbian',
		'sk' => 'Slovak',
		'ls' => 'Slovenian',
		'sb' => 'Sorbian',
		'es-ar' => 'Spanish (Argentina)',
		'es-bo' => 'Spanish (Bolivia)',
		'es-cl' => 'Spanish (Chile)',
		'es-co' => 'Spanish (Colombia)',
		'es-cr' => 'Spanish (Costa Rica)',
		'es-do' => 'Spanish (Dominican Republic)',
		'es-ec' => 'Spanish (Ecuador)',
		'es-sv' => 'Spanish (El Salvador)',
		'es-gt' => 'Spanish (Guatemala)',
		'es-hn' => 'Spanish (Honduras)',
		'es-mx' => 'Spanish (Mexico)',
		'es-ni' => 'Spanish (Nicaragua)',
		'es-pa' => 'Spanish (Panama)',
		'es-py' => 'Spanish (Paraguay)',
		'es-pe' => 'Spanish (Peru)',
		'es-pr' => 'Spanish (Puerto Rico)',
		'es-us' => 'Spanish (United States)',
		'es-uy' => 'Spanish (Uruguay)',
		'es-ve' => 'Spanish (Venezuela)',
		'es' => 'Spanish (Traditional Sort)',
		'sx' => 'Sutu',
		'sw' => 'Swahili',
		'sv-fi' => 'Swedish (Finland)',
		'sv' => 'Swedish',
		'syr' => 'Syriac',
		'ta' => 'Tamil',
		'tt' => 'Tatar',
		'te' => 'Telugu',
		'th' => 'Thai',
		'ts' => 'Tsonga',
		'tn' => 'Tswana',
		'tr' => 'Turkish',
		'uk' => 'Ukrainian',
		'ur' => 'Urdu',
		'uz' => 'Uzbek',
		'vi' => 'Vietnamese',
		'xh' => 'Xhosa',
		'yi' => 'Yiddish',
		'zu' => 'Zulu' );
	return $a_languages;
}

/**
 * xoKillSession()
 *
 * @return
 */
function xoKillSession()
{
	$_SESSION['settings'] = array();
}

?>