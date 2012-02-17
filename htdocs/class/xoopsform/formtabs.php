<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

/**
 * XOOPS Form Tabs: Add tabs to XoopsForm class
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: xoopstabs.php 1531 2008-05-01 09:25:50Z catzwolf $
 */
class XoopsTabs {
    /**
     *
     * @var int Use cookies
     */
    var $_useCookies = 0;
    /**
     * Constructor
     * Includes files needed for displaying tabs and sets cookie options
     *
     * @param int $ useCookies, if set to 1 cookie will hold last used tab between page refreshes
     */
    function XoopsTabs( $useCookies = 0 )
    {
        $this->_useCookies = ( int )$useCookies;
        unset( $useCookies );
        if ( !empty( $GLOBALS['xoTheme'] ) ) {
            $GLOBALS['xoTheme']->addStylesheet( '/include/javascript/tabs/tabpane.css', array( 'id="luna-tab-style-sheet"' ) );
            $GLOBALS['xoTheme']->addScript( '/include/javascript/tabs/tabpane.js' );
        } else {
            echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/include/javascript/tabs/tabpane.css" />';
            echo '<script type="text/javascript" src="' . XOOPS_URL . '/include/javascript/tabs/tabpane.js"></script>';
        }
    }

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
     * creates a tab pane and creates JS obj
     *
     * @param string $ The Tab Pane Name
     */
    function startPane( $id )
    {
        $id = htmlspecialchars( $id, ENT_QUOTES ) ;
        $ret = '<div class="tab-pageouter" id="' . $id . '">
        	<script type="text/javascript">
        	var tabPane1 = new WebFXTabPane( document.getElementById( "' . $id . '" ), ' . $this->_useCookies . ' )
        </script>';
        return $ret;
    }

    /**
     * Ends Tab Pane
     */
    function endPane()
    {
        $ret = '</div>';
        return $ret;
    }

    /**
     * Creates a tab with title text and starts that tabs page
     *
     * @param tabText $ - This is what is displayed on the tab
     * @param paneid $ - This is the parent pane to build this tab on
     */
    function startTab( $tabText, $paneid )
    {
        $tabText = htmlspecialchars( $tabText, ENT_QUOTES );
        $paneid = htmlspecialchars( $paneid, ENT_QUOTES ) ;
        $ret = '<div class="tab-page" id="' . $paneid . '">
        	<h2 class="tab">' . $tabText . '</h2>
        	<script type="text/javascript">
        	tabPane1.addTabPage( document.getElementById( "' . $paneid . '" ) );
    	  </script>
		  <table width="100%" cellspacing="1" cellpadding="2">
		  ';
        return $ret;
    }

    /**
     * Ends a tab page
     */
    function endTab()
    {
        $ret = '</table></div>';
        return $ret;
    }
}

?>