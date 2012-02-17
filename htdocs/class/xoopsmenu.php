<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/* Usage */

/**
 * To add the menu use the following example:
 *
 * to have the menu available on ever admin page either add the code below or add it to your admin_header.php include.
 *
 * xoops_load( 'menu' );
 * $menu_handler = xoopsMenu::getInstance();
 *
 * To add top level links within the menu use the following:
 * addMenuTop( 'path/to/file', 'Link Name' )
 * $menu_handler->addMenuTop( '../index.php', 'Module Home' );
 *
 * To add a tab to the menu use the following:
 * addMenuTabs( 'index.php', 'Tab Name' )
 * $menu_handler->addMenuTabs( 'index.php', 'Admin Index' );
 *
 * To add the menu anywhere within a module use:
 * to add a menu sub heading use:
 * $menu_handler->addSubHeader( _MA_WFC_MAINAREA_DSC );
 *
 * to rednder the menu use:
 * INT $selectedtab: YOu can highlight or show as selected by entering a Tab number. First tab = 0
 * render( $selectedtab )
 *
 * $menu_handler->render( $selectedtab );
 */

/**
 * XOOPS methods for user handling
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: menu.php 1531 2008-05-01 09:25:50Z catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

/**
 * xoopsMenu
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2007
 * @version $Id$
 * @access public
 */
class xoopsMenu {
    /**
     *
     * @var string
     */
    private $_menutop = array();
    private $_menutabs = array();
    private $_obj;
    private $_header;
    private $_subheader;

    public function __construct()
    {
        $this->_obj = &$GLOBALS['xoopsModule'];
    }

    /**
     * xoopsMenu::getInstance()
     *
     * @return
     **/
    public function &getInstance()
    {
        static $instance;
        if ( !isset( $instance ) ) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * xoopsMenu::getModule()
     *
     * @param array $module
     * @return
     */
    public function getModule( $module = array() )
    {
        $this->_obj = &$module;
    }

    /**
     * xoopsMenu::addMenuTop()
     *
     * @param mixed $value
     * @param mixed $name
     * @return
     */
    public function addMenuTop( $value, $name = null )
    {
        if ( !is_null( $name ) ) {
            $this->_menutop[$value] = $name;
        } else {
            $this->_menutop[$value] = $value;
        }
    }

    /**
     * xoopsMenu::addMenuTopArray()
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
                    self::addOptionTop( $k, $v );
                }
            } else {
                foreach ( $options as $k ) {
                    self::addOptiontop( $k, $k );
                }
            }
        }
    }

    /**
     * xoopsMenu::addMenuTabs()
     *
     * @param mixed $value
     * @param mixed $name
     * @return
     */
    public function addMenuTabs( $value, $name = null )
    {
        if ( !is_null( $name ) ) {
            $this->_menutabs[$value] = $name;
        } else {
            $this->_menutabs[$value] = $value;
        }
    }

    /**
     * xoopsMenu::addMenuTabsArray()
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
                    self::addMenuTabsTop( $k, $v );
                }
            } else {
                foreach ( $options as $k ) {
                    self::addMenuTabsTop( $k, $k );
                }
            }
        }
    }

    /**
     * xoopsMenu::addHeader()
     *
     * @param mixed $value
     * @return
     */
    public function addHeader( $value )
    {
        $this->_header = $value;
    }

    /**
     * xoopsMenu::addSubHeader()
     *
     * @param mixed $value
     * @return
     */
    public function addSubHeader( $value )
    {
        $this->_subheader = $value;
    }

    /**
     * xoopsMenu::breadcrumb_nav()
     *
     * @param string $basename
     * @return
     */
    private function breadcrumb_nav( $basename = 'Home' )
    {
        global $bc_site, $bc_label;

        $site = $bc_site;
        $return_str = "<a href=\"/\" title=\"$basename\">$basename</a>";
        $str = substr( dirname( xoops_getenv( 'PHP_SELF' ) ), 1 );
        $arr = split( '/', $str );
        $num = count( $arr );
        if ( $num > 1 ) {
            foreach( $arr as $val ) {
                $return_str .= ' &gt; <a href="' . $site . $val . '/" title="' . $bc_label[$val] . '">' . $bc_label[$val] . '</a>';
                $site .= $val . '/';
            }
        } elseif ( $num == 1 ) {
            $arr = $str;
            $return_str .= ' &gt; <a href="' . $bc_site . $arr . '/" title="' . $bc_label[$arr] . '">' . $bc_label[$arr] . '</a>';
        }
        return $return_str;
    }

    /**
     * xoopsMenu::render()
     *
     * @param integer $currentoption
     * @param mixed $display
     * @return
     */
    public function render( $currentoption = 1, $display = true )
    {
        global $modversion;

        $i = 0;
        /*
		* Selects current menu tab
		*/
        foreach ( $this->_menutabs as $k => $menus ) {
            $menuItems[] = $menus;
        }
        $breadcrumb = $menuItems[$currentoption];
        $menuItems[$currentoption] = 'current';
        /**
		* Not the best method of adding CSS but the only method available at the moment since xoops is shitty with the backend
		**/
        $menu = "<style type=\"text/css\" media=\"screen\">@import \"" . XOOPS_URL . "/modules/system/style.css\";</style>";
        $menu .= "<h3 class='admin_header'>Module: " . $this->_obj->getVar( 'name' ) . "</h3>\n";
        $menu .= "<div id='buttontop_mod'>";
        $menu .= "<table style='width: 100%; padding: 0;' cellspacing='0'>\n<tr>";
        $menu .= "<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'>";
        if ( $this->_obj->getVar( 'mid' ) != 1 ) {
            $menu .= "<a href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $this->_obj->getVar( 'mid' ) . "'>" . _MA_WFCR_PREFS . "</a>";
        }
        foreach ( $this->_menutop as $k => $v ) {
            $k = htmlspecialchars( $k, ENT_QUOTES );
            $v = htmlspecialchars( $v, ENT_QUOTES );
            $menu .= " <a href=\"$k\">$v</a>";
        }
        $menu .= "</td>";
        $menu .= "<td style='text-align: right;'><strong>" . _XO_AD_ADMINBREADCRUMB . "</strong> " . $breadcrumb . "</td>";
        $menu .= "</tr>\n</table>\n";
        $menu .= "</div>\n";
        $menu .= "<div id='buttonbar_mod'><ul>";
        foreach ( $this->_menutabs as $k => $v ) {
            $menu .= "<li id='" . $menuItems[$i] . "'><a href=\"$k\"><span>$v</span></a></li>\n";
            $i++;
        }
        $menu .= "</ul>\n</div>\n";
        if ( $this->_header ) {
            $menu .= "<h4 class='admin_header'>";
            if ( isset( $modversion['name'] ) ) {
                if ( $modversion['image'] && $this->_obj->getVar( 'mid' ) == 1 ) {
					$system_image = XOOPS_URL . '/modules/system/images/system/' . $modversion['image'];
                } else {
                    $system_image = XOOPS_URL . '/modules/' . $this->_obj->getVar( 'dirname' ) . '/images/' . $modversion['image'];
                }
                $menu .= "<img src='$system_image' align='middle' height='32' width='32' alt='' />";
                $menu .= " " . $modversion['name'] . "</h4>\n";
            } else {
                $menu .= " " . $this->_header . "</h4>\n";
            }
        }
        if ( $this->_subheader ) {
            $menu .= "<div class='admin_subheader'>" . $this->_subheader . "</div>\n";
        }
        unset( $this->_obj );
        if ( $display == true ) {
            echo $menu;
        } else {
            return $menu;
        }
    }
}

?>