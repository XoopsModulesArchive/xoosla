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
 * @copyright The Xoosla project http://www.xoops.org/
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
 * PathController
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class PathController {
	var $xoopsPath = array(
		'root' => '',
		'lib' => '',
		'data' => '',
		);
	var $xoopsPathDefault = array(
		'lib' => 'xoops_lib',
		'data' => 'xoops_data',
		);
	var $dataPath = array(
		'caches' => array(
			'xoops_cache',
			'smarty_cache',
			'smarty_compile',
			),
		'configs',
		);
	var $path_lookup = array(
		'root' => 'ROOT_PATH',
		'data' => 'VAR_PATH',
		'lib' => 'PATH',
		);
	var $xoopsUrl = '';
	var $validPath = array(
		'root' => 0,
		'data' => 0,
		'lib' => 0,
		);
	var $validUrl = false;
	var $permErrors = array(
		'root' => null,
		'data' => null,
		);

	/**
	 * PathController::PathController()
	 *
	 * @param mixed $xoopsPathDefault
	 * @param mixed $dataPath
	 */
	function PathController( $xoopsPathDefault, $dataPath )
	{
		$this->xoopsPathDefault = $xoopsPathDefault;
		$this->dataPath = $dataPath;

		if ( isset( $_SESSION['settings']['ROOT_PATH'] ) ) {
			foreach ( $this->path_lookup as $req => $sess ) {
				$this->xoopsPath[$req] = $_SESSION['settings'][$sess];
			}
		} else {
			$path = str_replace( "\\", '/', realpath( '../' ) );
			if ( substr( $path, - 1 ) == '/' ) {
				$path = substr( $path, 0, - 1 );
			}
			if ( file_exists( $path . '/mainfile.php' ) ) {
				$this->xoopsPath['root'] = $path;
			}
			// Firstly, locate Xoosla lib folder out of Xoosla root folder
			$this->xoopsPath['lib'] = dirname( $path ) . '/' . ( $this->xoopsPathDefault['lib'] );
			// If the folder is not created, re-locate Xoosla lib folder inside Xoosla root folder
			if ( !is_dir( $this->xoopsPath['lib'] ) ) {
				$this->xoopsPath['lib'] = $path. '/' . ( $this->xoopsPathDefault['lib'] );
			}
			// Firstly, locate Xoosla data folder out of Xoosla root folder
			$this->xoopsPath['data'] = dirname( $path ) . '/' . ( $this->xoopsPathDefault['data'] );
			// If the folder is not created, re-locate Xoosla data folder inside Xoosla root folder
			if ( !is_dir( $this->xoopsPath['data'] ) ) {
				$this->xoopsPath['data'] = $path . '/' . ( $this->xoopsPathDefault['data'] );
			}
		}
		if ( isset( $_SESSION['settings']['URL'] ) ) {
			$this->xoopsUrl = $_SESSION['settings']['URL'];
		} else {
			$path = $GLOBALS['wizard']->baseLocation();
			$this->xoopsUrl = substr( $path, 0, strrpos( $path, '/' ) );
		}
	}

	/**
	 * PathController::execute()
	 *
	 * @return
	 */
	function execute( &$errors )
	{
		$this->readRequest();
		$valid = $this->validate();
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			foreach ( $this->path_lookup as $req => $sess ) {
				$_SESSION['settings'][$sess] = $this->xoopsPath[$req];
			}
			$_SESSION['settings']['URL'] = $this->xoopsUrl;
			if ( $valid ) {
				$GLOBALS['wizard']->redirectToPage( '+1' );
			} else {
				$errors['pathcheck'] = INSTALL_ERR_PATHS_INCORRECT;
			}
		}
	}

	/**
	 * PathController::readRequest()
	 *
	 * @return
	 */
	function readRequest()
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$request = $_POST;
			foreach ( $this->path_lookup as $req => $sess ) {
				if ( isset( $request[$req] ) ) {
					$request[$req] = str_replace( "\\", '/', trim( $request[$req] ) );
					if ( substr( $request[$req], - 1 ) == '/' ) {
						$request[$req] = substr( $request[$req], 0, - 1 );
					}
					$this->xoopsPath[$req] = $request[$req];
				}
			}
			if ( isset( $request['URL'] ) ) {
				$request['URL'] = trim( $request['URL'] );
				if ( substr( $request['URL'], - 1 ) == '/' ) {
					$request['URL'] = substr( $request['URL'], 0, - 1 );
				}
				$this->xoopsUrl = $request['URL'];
			}
		}
	}

	/**
	 * PathController::validate()
	 *
	 * @return
	 */
	function validate()
	{
		foreach ( array_keys( $this->xoopsPath ) as $path ) {
			if ( $this->checkPath( $path ) ) {
				$this->checkPermissions( $path );
			}
		}
		$this->validUrl = !empty( $this->xoopsUrl );
		$validPaths = ( array_sum( array_values( $this->validPath ) ) == count( array_keys( $this->validPath ) ) ) ? 1 : 0;
		$validPerms = true;
		foreach ( $this->permErrors as $key => $errs ) {
			if ( empty( $errs ) ) continue;
			foreach ( $errs as $path => $status ) {
				if ( empty( $status ) ) {
					$validPerms = false;
					break;
				}
			}
		}
		return ( $validPaths && $this->validUrl && $validPerms );
	}

	/**
	 * PathController::checkPath()
	 *
	 * @param string $PATH
	 * @return
	 */
	function checkPath( $PATH = '' )
	{
		$ret = 1;
		if ( $PATH == 'root' || empty( $PATH ) ) {
			$path = 'root';
			if ( is_dir( $this->xoopsPath[$path] ) && is_readable( $this->xoopsPath[$path] ) ) {
				require_once $this->xoopsPath[$path] . '/include/version.php';
				if ( file_exists( $this->xoopsPath[$path] . '/mainfile.php' ) && defined( 'XOOPS_VERSION' ) ) {
					$this->validPath[$path] = 1;
				}
			}
			$ret *= $this->validPath[$path];
		}
		if ( $PATH == 'lib' || empty( $PATH ) ) {
			$path = 'lib';
			if ( is_dir( $this->xoopsPath[$path] ) && is_readable( $this->xoopsPath[$path] ) ) {
				$this->validPath[$path] = 1;
			}
			$ret *= $this->validPath[$path];
		}
		if ( $PATH == 'data' || empty( $PATH ) ) {
			$path = 'data';
			if ( is_dir( $this->xoopsPath[$path] ) && is_readable( $this->xoopsPath[$path] ) ) {
				$this->validPath[$path] = 1;
			}

			$ret *= $this->validPath[$path];
		}
		return $ret;
	}

	/**
	 * PathController::setPermission()
	 *
	 * @param mixed $parent
	 * @param mixed $path
	 * @param mixed $error
	 * @return
	 */
	function setPermission( $parent, $path, &$error )
	{
		if ( is_array( $path ) ) {
			foreach ( array_keys( $path ) as $item ) {
				if ( is_string( $item ) ) {
					$error[$parent . '/' . $item] = $this->makeWritable( $parent . '/' . $item );
					if ( empty( $path[$item] ) ) continue;
					foreach ( $path[$item] as $child ) {
						$this->setPermission( $parent . '/' . $item, $child, $error );
					}
				} else {
					$error[$parent . '/' . $path[$item]] = $this->makeWritable( $parent . '/' . $path[$item] );
				}
			}
		} else {
			$error[$parent . '/' . $path] = $this->makeWritable( $parent . '/' . $path );
		}
		return;
	}

	/**
	 * PathController::checkPermissions()
	 *
	 * @param mixed $path
	 * @return
	 */
	function checkPermissions( $path )
	{
		$paths = array(
			'root' => array( 'mainfile.php', 'uploads' ),
			'data' => $this->dataPath
			);
		$errors = array(
			'root' => null,
			'data' => null,
			);
		if ( !isset( $this->xoopsPath[$path] ) ) {
			return false;
		}
		if ( !isset( $errors[$path] ) ) {
			return true;
		}
		$this->setPermission( $this->xoopsPath[$path], $paths[$path], $errors[$path] );
		if ( in_array( false, $errors[$path] ) ) {
			$this->permErrors[$path] = $errors[$path];
		}
		return true;
	}

	/**
	 * Write-enable the specified folder
	 *
	 * @param string $path
	 * @param bool $recurse
	 * @return false on failure, method (u-ser,g-roup,w-orld) on success
	 */
	function makeWritable( $path, $create = true )
	{
		$mode = intval( '0777', 8 );
		if ( !file_exists( $path ) ) {
			if ( !$create ) {
				return false;
			} else {
				mkdir( $path, $mode );
			}
		}
		if ( !is_writable( $path ) ) {
			chmod( $path, $mode );
		}
		clearstatcache();
		if ( is_writable( $path ) ) {
			$info = stat( $path );
			if ( $info['mode'] &0002 ) {
				return 'w';
			} elseif ( $info['mode'] &0020 ) {
				return 'g';
			}
			return 'u';
		}
		return false;
	}
}

?>