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
 * @package menu.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @author Andricq Nicolas (AKA MusS)
 * @version menu.php 00 27/02/2012 06:04 Catzwolf $Id:
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * SystemMenuHandler
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class SystemMenuHandler {
	/**
	 *
	 * @var string
	 */
	protected $_menutop = array();
	protected $_menutabs = array();
	private $_obj;
	private $_header;
	private $_subheader;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $xoopsModule;
		$this->_obj = $xoopsModule;
	}

	/**
	 * SystemMenuHandler::getAddon()
	 *
	 * @param mixed $addon
	 * @return
	 */
	public function getAddon( $addon )
	{
		$this->_obj = $addon;
	}

	/**
	 * SystemMenuHandler::addMenuTop()
	 *
	 * @param mixed $value
	 * @param string $name
	 * @return
	 */
	public function addMenuTop( $value, $name = '' )
	{
		if ( $name != '' ) {
			$this->_menutop[$value] = $name;
		} else {
			$this->_menutop[$value] = $value;
		}
	}

	/**
	 * SystemMenuHandler::addMenuTopArray()
	 *
	 * @param mixed $options
	 * @param mixed $multi
	 * @return
	 */
	public function addMenuTopArray( $options, $multi = true )
	{
		if ( is_array( $options ) ) {
			if ( $multi == true ) {
				foreach ( $options as $k => $v ) {
					$this->addOptionTop( $k, $v );
				}
			} else {
				foreach ( $options as $k ) {
					$this->addOptiontop( $k, $k );
				}
			}
		}
	}

	/**
	 * SystemMenuHandler::addMenuTabs()
	 *
	 * @param mixed $value
	 * @param string $name
	 * @return
	 */
	public function addMenuTabs( $value, $name = '' )
	{
		if ( $name != '' ) {
			$this->_menutabs[$value] = $name;
		} else {
			$this->_menutabs[$value] = $value;
		}
	}

	/**
	 * SystemMenuHandler::addMenuTabsArray()
	 *
	 * @param mixed $options
	 * @param mixed $multi
	 * @return
	 */
	public function addMenuTabsArray( $options, $multi = true )
	{
		if ( is_array( $options ) ) {
			if ( $multi == true ) {
				foreach ( $options as $k => $v ) {
					$this->addMenuTabsTop( $k, $v );
				}
			} else {
				foreach ( $options as $k ) {
					$this->addMenuTabsTop( $k, $k );
				}
			}
		}
	}

	/**
	 * SystemMenuHandler::addHeader()
	 *
	 * @param mixed $value
	 * @return
	 */
	public function addHeader( $value )
	{
		$this->_header = $value;
	}

	/**
	 * SystemMenuHandler::addSubHeader()
	 *
	 * @param mixed $value
	 * @return
	 */
	public function addSubHeader( $value )
	{
		$this->_subheader = $value;
	}

	/**
	 * SystemMenuHandler::breadcrumb_nav()
	 *
	 * @param string $basename
	 * @return
	 */
	public function breadcrumb_nav( $basename = 'Home' )
	{
		global $bc_site, $bc_label;
		$site = $bc_site;
		$return_str = '<a href="/">$basename</a>';
		$str = substr( dirname( xoops_getenv( 'PHP_SELF' ) ), 1 );

		$arr = explode( '/', $str );
		$num = count( $arr );

		if ( $num > 1 ) {
			foreach( $arr as $val ) {
				$return_str .= ' &gt; <a href="' . $site . $val . '/">' . $bc_label[$val] . '</a>';
				$site .= $val . '/';
			}
		} else if ( $num == 1 ) {
			$arr = $str;
			$return_str .= ' &gt; <a href="' . $bc_site . $arr . '/">' . $bc_label[$arr] . '</a>';
		}
		return $return_str;
	}

	/**
	 * SystemMenuHandler::render()
	 *
	 * @param integer $currentoption
	 * @param mixed $display
	 * @return
	 */
	public function render( $currentoption = 1, $display = true )
	{
		global $modversion;
		$_dirname = $this->_obj->getVar( 'dirname' );
		$i = 0;

		/**
		 * Selects current menu tab
		 */
		foreach ( $this->_menutabs as $k => $menus ) {
			$menuItems[] = $menus;
		}
		$breadcrumb = $menuItems[$currentoption];
		$menuItems[$currentoption] = 'current';
		$menu = '<div id="buttontop_mod">';
		$menu .= '<table style="width: 100%; padding: 0;" cellspacing="0"><tr>';
		$menu .= '<td style="font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;">';
		foreach ( $this->_menutop as $k => $v ) {
			$menu .= ' <a href="'.$k.'">'.$v.'</a> |';
		}
		$menu = substr( $menu, 0, - 1 );

		$menu .= '</td>';
		$menu .= '<td style="text-align: right;"><strong>' . $this->_obj->getVar( 'name' ) . '</strong> : ' . $breadcrumb . '</td>';
		$menu .= '</tr></table>';
		$menu .= '</div>';
		$menu .= '<div id="buttonbar_mod"><ul>';
		foreach ( $this->_menutabs as $k => $v ) {
			$menu .= '<li id="' . $menuItems[$i] . '"><a href="' . XOOPS_URL . '/modules/' . $this->_obj->getVar( 'dirname' ) . '/' . $k . '"><span>'.$v.'</span></a></li>';
			$i++;
		}
		$menu .= '</ul></div>';
		if ( $this->_header ) {
			$menu .= '<h4 class="admin_header">';
			if ( isset( $modversion["name"] ) ) {
				if ( $modversion['image'] && $this->_obj->getVar( 'mid' ) == 1 ) {
					$system_image = XOOPS_URL . '/modules/system/images/system/' . $modversion['image'];
				} else {
					$system_image = XOOPS_URL . '/modules/' . $_dirname . '/images/' . $modversion['image'];
				}
				$menu .= '<img src="$system_image" align="middle" height="32" width="32" alt="" />';
				$menu .= ' ' . $modversion['name'] . '</h4>';
			} else {
				$menu .= ' ' . $this->_header . '</h4>';
			}
		}
		if ( $this->_subheader ) {
			$menu .= '<div class="admin_subheader">' . $this->_subheader . '</div>';
		}
		$menu .= '<div class="clear">&nbsp;</div>';
		unset( $this->_obj );
		if ( $display == true ) {
			echo $menu;
		} else {
			return $menu;
		}
	}
}

?>