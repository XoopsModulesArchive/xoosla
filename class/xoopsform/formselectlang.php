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
 * XOOPS form element
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @subpackage form
 * @since 2.0.0
 * @author Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );
// xoops_load( 'XoopsLists' );
// xoops_load( 'XoopsFormSelect' );
/**
 * A select field with available languages
 */
class XoopsFormSelectLang extends XoopsFormSelect {
	/**
	 * Constructor
	 *
	 * @param string $caption
	 * @param string $name
	 * @param mixed $value Pre-selected value (or array of them).
	 * 							Legal is any name of a XOOPS_ROOT_PATH."/language/" subdirectory.
	 * @param int $size Number of rows. "1" makes a drop-down-list.
	 */
	public function __Construct( $caption, $name, $value = null, $size = 1 )
	{
		parent::__Construct( $caption, $name, $value, $size );
		$this->addOptionArray( XoopsLists::getLangList() );
	}
}

?>