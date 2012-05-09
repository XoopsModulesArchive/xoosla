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
 * System Preloads
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author Cointin Maxime (AKA Kraven30)
 * @author Andricq Nicolas (AKA MusS)
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * SystemCorePreload
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class SystemCorePreload extends XoopsPreloadItem {
	/**
	 * SystemCorePreload::eventCoreIncludeFunctionsRedirectheader()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventCoreIncludeFunctionsRedirectheader( $args )
	{
		global $xoopsConfig;

		$url = $args[0];
		if ( preg_match( "/[\\0-\\31]|about:|script:/i", $url ) ) {
			if ( !preg_match( '/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url ) ) {
				$url = XOOPS_URL;
			}
		}
		if ( !headers_sent() && isset( $xoopsConfig['redirect_message_ajax'] ) && $xoopsConfig['redirect_message_ajax'] ) {
			$_SESSION['redirect_message'] = $args[2];
			header( "Location: " . preg_replace( "/[&]amp;/i", '&', $url ) );
			exit();
		}
	}

	/**
	 * SystemCorePreload::eventCoreHeaderCheckcache()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventCoreHeaderCheckcache( $args )
	{
		if ( !empty( $_SESSION['redirect_message'] ) ) {
			$GLOBALS['xoTheme']->contentCacheLifetime = 0;
			unset( $_SESSION['redirect_message'] );
		}
	}

	/**
	 * SystemCorePreload::eventCoreHeaderAddmeta()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventCoreHeaderAddmeta( $args )
	{
		if ( !empty( $_SESSION['redirect_message'] ) ) {
			$GLOBALS['xoTheme']->addStylesheet( 'xoops.css' );
			$GLOBALS['xoTheme']->addScript( 'browse.php?Frameworks/jquery/jquery.js' );
			$GLOBALS['xoTheme']->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.jgrowl.js' );
			$GLOBALS['xoTheme']->addScript( '', array( 'type' => 'text/javascript' ), '
            (function($){
                $(document).ready(function(){
                $.jGrowl("' . $_SESSION['redirect_message'] . '", {  life:3000 , position: "center", speed: "slow" });
            });
            })(jQuery);
            ' );
		}
	}

	/**
	 * SystemCorePreload::eventSystemClassGuiHeader()
	 *
	 * @param mixed $args
	 * @return
	 */
	function eventSystemClassGuiHeader( $args )
	{
		if ( !empty( $_SESSION['redirect_message'] ) ) {
			$GLOBALS['xoTheme']->addStylesheet( 'xoops.css' );
			$GLOBALS['xoTheme']->addScript( 'browse.php?Frameworks/jquery/jquery.js' );
			$GLOBALS['xoTheme']->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.jgrowl.js' );
			$GLOBALS['xoTheme']->addScript( '', array( 'type' => 'text/javascript' ), '
            (function($){
            $(document).ready(function(){
                $.jGrowl("' . $_SESSION['redirect_message'] . '", {  life:3000 , position: "center", speed: "slow" });
            });
            })(jQuery);
            ' );
			unset( $_SESSION['redirect_message'] );
		}
	}
}

?>