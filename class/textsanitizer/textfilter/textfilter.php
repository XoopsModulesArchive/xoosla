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
 * TextSanitizer extension
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package class
 * @subpackage textsanitizer
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * Filter out possible malicious text
 * kses project at SF could be a good solution to check
 *
 * @param string $text text to filter
 * @param bool $force flag indicating to force filtering
 * @return string filtered text
 */
class MytsTextfilter extends MyTextSanitizerExtension {
	/**
	 * MytsTextfilter::load()
	 *
	 * @param mixed $ts
	 * @param mixed $text
	 * @param mixed $force
	 * @return
	 */
	public function load( MyTextSanitizer &$ts, $text, $force = false )
	{
		global $xoopsUser, $xoopsConfig, $xoopsUserIsAdmin;
		if ( empty( $force ) && $xoopsUserIsAdmin ) {
			return $text;
		}
		// Built-in fitlers for XSS scripts
		// To be improved
		$text = $ts->filterXss( $text );

		$tags = array();
		$search = array();
		$replace = array();
		$config = parent::loadConfig( dirname( __FILE__ ) );
		if ( !empty( $config['patterns'] ) ) {
			foreach ( $config['patterns'] as $pattern ) {
				if ( empty( $pattern['search'] ) )
					continue;
				$search[] = $pattern['search'];
				$replace[] = $pattern['replace'];
			}
		}
		if ( !empty( $config['tags'] ) ) {
			$tags = array_map( 'trim', $config['tags'] );
		}
		// set embedded tags
		$tags[] = 'script';
		$tags[] = 'vbscript';
		$tags[] = 'javascript';
		foreach ( $tags as $tag ) {
			$search[] = '/<' . $tag . '[^>]*?>.*?<\/' . $tag . '>/si';
			$replace[] = ' [!' . strtoupper( $tag ) . ' filtered!] ';
		}
		// set meta refresh tag
		$search[] = '/<meta[^>\/]*http-equiv=([""])?refresh(\\1)[^>\/]*?\/>/si';
		$replace[] = '';
		// sanitizing scripts in img tag
		// $search[]= "/(<img[\s]+[^>\/]*source=)(['\"])?(.*)(\\2)([^>\/]*?\/>)/si";
		// $replace[]="";
		// set iframe tag
		$search[] = '/<iframe[^>\/]*src=([""])?([^>\/]*)(\\1)[^>\/]*?\/>/si';
		$replace[] = ' [!iframe filtered! \\2] ';
		$search[] = '/<iframe[^>]*?>([^<]*)<\/iframe>/si';
		$replace[] = ' [!iframe filtered! \\1] ';
		// action
		$text = preg_replace( $search, $replace, $text );
		return $text;
	}
}

?>