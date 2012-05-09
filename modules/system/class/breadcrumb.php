<?php
/**
 * Xoosla
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package breadcrumb.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version breadcrumb.php 26 2012-02-17 09:16:15Z catzwolf $Id:
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * SystemBreadcrumb
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class SystemBreadcrumb {
	/**
	 *
	 * @var
	 */
	private $_directory;
	/**
	 *
	 * @var
	 */
	private $_bread = array();
	/**
	 *
	 * @var
	 */
	private $_help;
	/**
	 *
	 * @var
	 */
	private $_tips;

	/**
	 * SystemBreadcrumb::__construct()
	 *
	 * @param mixed $directory
	 */
	public function __construct( $directory )
	{
		$this->_directory = $directory;
	}

	/**
	 * Add link to breadcrumb
	 */
	public function addLink( $title = '', $link = '', $home = false )
	{
		$this->_bread[] = array(
			'link' => $link,
			'title' => $title,
			'home' => $home
			);
	}

	/**
	 * Add Help link
	 */
	public function addHelp( $link = '' )
	{
		$this->_help = $link;
	}

	/**
	 * Add Tips
	 */
	public function addTips( $value )
	{
		$this->_tips = $value;
	}

	/**
	 * Render System BreadCrumb
	 */
	public function render()
	{
		if ( isset( $GLOBALS['xoopsTpl'] ) ) {
			$GLOBALS['xoopsTpl']->assign( 'xo_sys_breadcrumb', $this->_bread );
			$GLOBALS['xoopsTpl']->assign( 'xo_sys_help', $this->_help );
			if ( $this->_tips ) {
				if ( xoops_getModuleOption( 'usetips', 'system' ) ) {
					$GLOBALS['xoopsTpl']->assign( 'xo_sys_tips', $this->_tips );
				}
			}
			// Call template
			if ( file_exists( XOOPS_ROOT_PATH . '/modules/system/language/' . $GLOBALS['xoopsConfig']['language'] . '/help/' . $this->_directory . '.html' ) ) {
				$GLOBALS['xoopsTpl']->assign( 'help_content', XOOPS_ROOT_PATH . '/modules/system/language/' . $GLOBALS['xoopsConfig']['language'] . '/help/' . $this->_directory . '.html' );
			} else {
				if ( file_exists( XOOPS_ROOT_PATH . '/modules/system/language/english/help/' . $this->_directory . '.html' ) ) {
					$GLOBALS['xoopsTpl']->assign( 'help_content', XOOPS_ROOT_PATH . '/modules/system/language/english/help/' . $this->_directory . '.html' );
				} else {
					$GLOBALS['xoopsTpl']->assign( 'load_error', 1 );
				}
			}
		} else {
			$out = $menu = '<style type="text/css" media="screen">@import ' . XOOPS_URL . '/modules/system/css/menu.css;</style>';
			$out .= '<ul id="xo-breadcrumb">';
			foreach ( $this->_bread as $menu ) {
				if ( $menu['home'] ) {
					$out .= '<li><a href="' . $menu['link'] . '" title="' . $menu['title'] . '"><img src="images/home.png" alt="' . $menu['title'] . '" class="home" /></a></li>';
				} else {
					if ( $menu['link'] != '' ) {
						$out .= '<li><a href="' . $menu['link'] . '" title="' . $menu['title'] . '">' . $menu['title'] . '</a></li>';
					} else {
						$out .= '<li>' . $menu['title'] . '</li>';
					}
				}
			}
			$out .= '</ul>';
			if ( $this->_tips ) {
				$out .= '<div class="tips">' . $this->_tips . '</div>';
			}
			echo $out;
		}
	}
}

?>