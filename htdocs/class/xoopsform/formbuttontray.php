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
 * XOOPS methods for user handling
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: formbuttontray.php 1531 2008-05-01 09:25:50Z catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

/**
 * XoopsFormButtonTray
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2008
 * @version $Id$
 * @access public
 **/
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
    public function XoopsFormButtonTray( $name, $value = '', $type = 'submit', $onclick = '' )
    {
        $this->setName( $name );
        $this->setValue( $value );
        $this->_type = $type;
        if ( $onclick ) {
            $this->setExtra( $onclick );
        } else {
            $this->setExtra( 'onClick="history.go(-1);return true;"' );
        }
    }

    /**
     * XoopsFormButtonTray::getValue()
     *
     * @return
     */
    private function getValue( $encode = false )
    {
        return $encode ? htmlspecialchars( $this->_value, ENT_QUOTES ) : $this->_value;
    }

    /**
     * XoopsFormButtonTray::setValue()
     *
     * @param mixed $value
     * @return
     */
    private function setValue( $value )
    {
        $this->_value = $value;
    }

    /**
     * XoopsFormButtonTray::getType()
     *
     * @return
     */
    private function getType()
    {
        return in_array( strtolower( $this->_type ), array( 'button', 'submit', 'reset' ) ) ? $this->_type : 'button';
    }

    /**
     * XoopsFormButtonTray::render()
     *
     * @return
     */
    public function render()
    {
        $ret = '
			<input type="button" class="formbutton"  name="cancel"  id="cancel" value="' . _CANCEL . '" ' . $this->getExtra() . ' />
			<input type="reset" class="formbutton"  name="reset"  id="reset" value="' . _RESET . '"  />
			<input type="' . $this->getType() . '" class="formbutton"  name="' . $this->getName() . '"  id="' . $this->getName() . '" value="' . $this->getValue() . '"  />';
        return $ret;
    }
}

?>