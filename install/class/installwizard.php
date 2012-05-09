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
 * @author John Neill (aka Zaquria) <zaquria@xoosla.com>
 * @version $Id$
 */
defined( 'XOOSLA_INSTALL' ) or die( 'Direct Access To This File Not Allowed!' );

class XoopsInstallWizard {
	var $language = 'en-GB';
	var $pages = array();
	var $currentPage = 'page1';
	var $pageIndex = 0;
	var $configs = array();

	/**
	 * XoopsInstallWizard::xoInit()
	 *
	 * @return
	 */
	function xoInit()
	{
		if ( @empty( $_SERVER['REQUEST_URI'] ) ) {
			$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
		}
		// Load the main language file
		$this->initLanguage( !empty( $_COOKIE['xo_install_lang'] ) ? $_COOKIE['xo_install_lang'] : 'en-gb' );
		// Setup pages
		$this->pages = self::getPages();
		// Load default configs
		$this->configs = self::getConfigs();
		if ( !$this->checkAccess() ) {
			return false;
		}
		$pagename = ( isset( $_GET['p'] ) && !empty( $_GET['p'] ) ) ? $_GET['p'] : 'page1';
		$this->setPage( $pagename );
		// Prevent client caching
		header( 'Cache-Control: no-store, no-cache, must-revalidate', false );
		header( 'Pragma: no-cache' );
		return true;
	}

	/**
	 * XoopsInstallWizard::checkAccess()
	 *
	 * @return
	 */
	function checkAccess()
	{
		if ( INSTALL_USER != '' && INSTALL_PASSWORD != '' ) {
			if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
				header( 'WWW-Authenticate: Basic realm="Xoosla! Installer"' );
				header( 'HTTP/1.0 401 Unauthorized' );
				echo INSTALL_ERR_NO_PERMISSION;
				return false;
			}
			if ( INSTALL_USER != '' && $_SERVER['PHP_AUTH_USER'] != INSTALL_USER ) {
				header( 'HTTP/1.0 401 Unauthorized' );
				echo INSTALL_ERR_NO_PERMISSION;
				return false;
			}
			if ( INSTALL_PASSWORD != $_SERVER['PHP_AUTH_PW'] ) {
				header( 'HTTP/1.0 401 Unauthorized' );
				echo INSTALL_ERR_NO_PERMISSION;
				return false;
			}
		}
		if ( empty( $GLOBALS['xoopsOption']['checkadmin'] ) ) {
			return true;
		}
		if ( empty( $GLOBALS['xoopsUser'] ) && !empty( $_COOKIE['xo_install_user'] ) ) {
			install_acceptUser( $_COOKIE['xo_install_user'] );
		}
		if ( empty( $GLOBALS['xoopsUser'] ) ) {
			redirect_header( '../user.php', 1 );
		}
		if ( !$GLOBALS['xoopsUser']->isAdmin() ) {
			return false;
		}
		return true;
	}

	/**
	 * XoopsInstallWizard::loadLangFile()
	 *
	 * @param mixed $file
	 * @return
	 */
	function loadLangFile( $file )
	{
		if ( file_exists( './language/' . $this->language . '/' . $file . '.php' ) ) {
			require_once './language/' . $this->language . '/' . $file . '.php';
		} else {
			require_once './language/en-GB/' . $file . '.php';
		}
	}

	/**
	 * XoopsInstallWizard::initLanguage()
	 *
	 * @param mixed $language
	 * @return
	 */
	function initLanguage( $language )
	{
		$language = preg_replace( '/[^a-z0-9_\-]/i', '', $language );
		if ( !file_exists( './language/' . $language . '/install.php' ) ) {
			$language = 'en-GB';
		}
		$this->language = $language;
		$this->loadLangFile( 'install' );
	}

	/**
	 * XoopsInstallWizard::setPage()
	 *
	 * @param mixed $page
	 * @return
	 */
	function setPage( $page )
	{
		$pages = array_keys( $this->pages );
		if ( (int)$page && $page >= 0 && $page < count( $pages ) ) {
			$this->pageIndex = $page;
			$this->currentPage = $pages[$page];
		} else if ( isset( $this->pages[$page] ) ) {
			$this->currentPage = $page;
			$this->pageIndex = array_search( $this->currentPage, $pages );
		} else {
			return false;
		}
		if ( $this->pageIndex > 0 && !isset( $_COOKIE['xo_install_lang'] ) ) {
			header( 'Location: index.php' );
		}
		return $this->pageIndex;
	}

	/**
	 * XoopsInstallWizard::baseLocation()
	 *
	 * @return
	 */
	function baseLocation()
	{
		$proto = ( @$_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
		$host = $_SERVER['HTTP_HOST'];
		$base = substr( $_SERVER['PHP_SELF'], 0, strrpos( $_SERVER['PHP_SELF'], '/' ) );
		return $proto . '://' . $host . $base;
	}

	/**
	 * XoopsInstallWizard::pageURI()
	 *
	 * @param mixed $page
	 * @return
	 */
	function pageURI( $page )
	{
		$pages = array_keys( $this->pages );
		$pageIndex = $this->pageIndex;
		if ( !(int)$page {
				0}
			) {
			if ( $page {
					0} == '+' ) {
				$pageIndex += substr( $page, 1 );
			} else if ( $page {
					0} == '-' ) {
				$pageIndex -= substr( $page, 1 );
			} else {
				$pageIndex = (int)array_search( $page, $pages );
			}
		}
		if ( !isset( $pages[$pageIndex] ) ) {
			if ( defined( 'XOOPS_URL' ) ) {
				return XOOPS_URL;
			} else {
				return $this->baseLocation();
			}
		}
		$page = $pages[$pageIndex];
		return $this->baseLocation() . '/index.php?p=' . htmlspecialchars( $page );
	}

	/**
	 * redirectToPage()
	 *
	 * @param mixed $page
	 * @param integer $status
	 * @param string $message
	 * @return
	 */
	function redirectToPage( $page, $status = 303, $message = 'See other' )
	{
		$location = $this->pageURI( $page );
		$proto = !@empty( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
		header( "{$proto} {$status} {$message}" );
		// header( "Status: $status $message" );
		header( "Location: {$location}" );
	}

	/**
	 * XoopsInstallWizard::CreateForm()
	 *
	 * @return
	 */
	function CreateForm()
	{
		$hidden = '';
		$ret = '';
		foreach ( $this->form as $form ) {
			$ret .= '<fieldset><legend>' . $form->getTitle() . '</legend>' . NEWLINE;
			foreach ( $form->getElements() as $ele ) {
				if ( is_object( $ele ) ) {
					if ( !$ele->isHidden() ) {
						if ( ( $caption = $ele->getCaption() ) != '' ) {
							$name = $ele->getName();
							$ret .= '<label class="xolabel" for="' . $ele->getName() . '">' . $caption . '</label>';
							if ( ( $desc = $ele->getDescription() ) != '' ) {
								$ret .= '<div class="xoform help">';
								$ret .= $desc;
								$ret .= '</div>';
							}
						}
						$ret .= $ele->render() . '\n';
					} else {
						$hidden .= $ele->render() . '\n';
					}
				}
			}
			$ret .= '</fieldset>\n' . $hidden . NEWLINE . $form->renderValidationJS( true );
		}
		return $ret;
	}

	/**
	 * XoopsInstallWizard::getPages()
	 *
	 * @return
	 */
	function getPages()
	{
		return array(
			'page1' => array(
				'name' => INSTALL_STARTING,
				'title' => INSTALL_STARTING_TITLE ),
			'page2' => array(
				'name' => INSTALL_LICENSE_SELECTION,
				'title' => INSTALL_LICENSE_SELECTION_TITLE ),
			'page3' => array(
				'name' => INSTALL_CONFIGURATION_CHECK,
				'title' => INSTALL_CONFIGURATION_CHECK_TITLE ),
			'page4' => array(
				'name' => INSTALL_DATABASE_CONNECTION,
				'title' => INSTALL_DATABASE_CONNECTION_TITLE ),
			'page5' => array(
				'name' => INSTALL_ADMINISTRATION_DETAILS,
				'title' => INSTALL_ADMINISTRATION_DETAILS_TITLE ),
			'page6' => array(
				'name' => INSTALL_FINISH,
				'title' => INSTALL_FINISH_TITLE ),
			);
	}

	function getConfigs()
	{
		$configs = array();
		// setup config site info
		$configs['db_types'] = array( 'mysql' );
		// languages config files
		$configs['language_files'] = array(
			'global',
			);
		// extension_loaded
		$configs['extensions'] = array(
			'mbstring' => array( 'MBString', sprintf( INSTALL_PHP_EXTENSION, INSTALL_CHAR_ENCODING ) ),
			'iconv' => array( 'Iconv', sprintf( INSTALL_PHP_EXTENSION, INSTALL_ICONV_CONVERSION ) ),
			'xml' => array( 'XML', sprintf( INSTALL_PHP_EXTENSION, INSTALL_XML_PARSING ) ),
			'zlib' => array( 'Zlib', sprintf( INSTALL_PHP_EXTENSION, INSTALL_ZLIB_COMPRESSION ) ),
			'gd' => array( ( function_exists( 'gd_info' ) && $gdlib = @gd_info() ) ? 'GD ' . $gdlib['GD Version'] : '', sprintf( INSTALL_PHP_EXTENSION, INSTALL_IMAGE_FUNCTIONS ) ),
			'exif' => array( 'Exif', sprintf( INSTALL_PHP_EXTENSION, INSTALL_IMAGE_METAS ) ),
			);
		// // Writable files and directories
		// $configs['writable'] = array( 'uploads/', 'uploads/avatars/', 'uploads/images/', 'uploads/ranks/', 'uploads/smilies/', 'mainfile.php', 'include/license.php' );
		// Modules to be installed by default
		$configs['modules'] = array();
		// xoops_lib, xoops_data directories
		$configs['xoopsPathDefault'] = array(
			'lib' => 'xoops_lib',
			'data' => 'xoops_data',
			);
		// writable xoops_lib, xoops_data directories
		$configs['dataPath'] = array(
			'caches' => array(
				'xoops_cache',
				'smarty_cache',
				'smarty_compile',
				),
			'configs' => null,
			'data' => null
			);
		return $configs;
	}
}

?>