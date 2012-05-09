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
 * XOOPS Form Class Elements
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @subpackage form
 * @since 2.4.0
 * @author John Neill <catzwolf@xoops.org>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XoopsFormButtonTray
 *
 * @author John Neill <catzwolf@xoops.org>
 * @package kernel
 * @subpackage form
 * @access public
 */
class XoopsFormButtonTray extends XoopsFormElement {
	/**
	 * Value
	 *
	 * @var string
	 * @access private
	 */
	private $_value;

	/**
	 * Type of the button. This could be either "button", "submit", or "reset"
	 *
	 * @var string
	 * @access private
	 */
	private $_type;

	/**
	 * XoopsFormButtonTray::XoopsFormButtonTray()
	 *
	 * @param mixed $name
	 * @param string $value
	 * @param string $type
	 * @param string $onclick
	 */
	public function __Construct( $name, $value = '', $type = '', $onclick = '', $showDelete = false )
	{
		$this->setName( $name );
		$this->setValue( $value );
		$this->_type = ( !empty( $type ) ) ? $type : 'submit';
		$this->_showDelete = $showDelete;
		if ( $onclick ) {
			$this->setExtra( $onclick );
		} else {
			$this->setExtra( '' );
		}
	}

	/**
	 * XoopsFormButtonTray::getValue()
	 *
	 * @return
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * XoopsFormButtonTray::setValue()
	 *
	 * @param mixed $value
	 * @return
	 */
	public function setValue( $value )
	{
		$this->_value = $value;
	}

	/**
	 * XoopsFormButtonTray::getType()
	 *
	 * @return
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * XoopsFormButtonTray::render()
	 *
	 * @return
	 */
	public function render()
	{
		$ret = '';
		if ( $this->_showDelete ) {
			$ret .= '<input type="submit" class="button" name="delete" id="delete" value="' . _DELETE . '" onclick="this.form.elements.op.value=\'delete\'">';
		}
		$ret .= '
            <input type="button" class="button" value="' . _CANCEL . '" onClick="history.go(-1);return true;" />
            <input type="reset" class="button" name="reset"  id="reset" value="' . _RESET . '" />
            <input type="' . $this->getType() . '" class="button" name="' . $this->getName() . '"  id="' . $this->getName() . '" value="' . $this->getValue() . '"' . $this->getExtra() . '  />';
		return $ret;
	}
}

?>