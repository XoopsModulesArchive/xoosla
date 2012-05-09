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
 * XoopsFormColorPicker component class file
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @subpackage form
 * @since 2.0.0
 * @author Zoullou <webmaster@zoullou.org>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * Color Selection Field
 *
 * @author Zoullou <webmaster@zoullou.org>
 * @author John Neill <catzwolf@xoops.org>
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @version $Id$
 * @package Kernel
 * @access public
 */
class XoopsFormColorPicker extends XoopsFormText {
	/**
	 * XoopsFormColorPicker::XoopsFormColorPicker()
	 *
	 * @param mixed $caption
	 * @param mixed $name
	 * @param string $value
	 */
	public function __Construct( $caption, $name, $value = '#FFFFFF' )
	{
		parent::__Construct( $caption, $name, 9, 7, $value );
	}

	/**
	 * XoopsFormColorPicker::render()
	 *
	 * @return
	 */
	public function render()
	{
		if ( isset( $GLOBALS['xoTheme'] ) ) {
			$GLOBALS['xoTheme']->addScript( 'include/color-picker/color-picker.js' );
		} else {
			echo '<script type="text/javascript" src="' . XOOPS_URL . '/include/color-picker/color-picker.js"></script>';
		}
		$this->setExtra( ' style="background-color:' . $this->getValue() . ';"' );
		return parent::render() . "<input type='reset' value=' ... ' onclick=\"return TCP.popup('" . XOOPS_URL . "/include/',document.getElementById('" . $this->getName() . "'));\">" ;
	}

	/**
	 * Returns custom validation Javascript
	 *
	 * @return string Element validation Javascript
	 */
	public function renderValidationJS()
	{
		$eltname = $this->getName();
		$eltcaption = $this->getCaption();
		$eltmsg = empty( $eltcaption ) ? sprintf( _FORM_ENTER, $eltname ) : sprintf( _FORM_ENTER, $eltcaption );

		return "if ( !(new RegExp(\"^#[0-9a-fA-F]{6}\",\"i\").test(myform.{$eltname}.value)) ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
	}
}

?>