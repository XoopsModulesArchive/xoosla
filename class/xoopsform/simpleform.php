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
 * XOOPS simple form
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

/**
 * base class
 */
// xoops_load( 'XoopsForm' );
/**
 * Form that will output as a simple HTML form with minimum formatting
 */
class XoopsSimpleForm extends XoopsForm {
	/**
	 * create HTML to output the form with minimal formatting
	 *
	 * @return string
	 */
	public function render()
	{
		$ret = $this->getTitle() . '<form name="' . $this->getName() . '" id="' . $this->getName() . '" action="' . $this->getAction() . '" method="' . $this->getMethod() . '"' . $this->getExtra() . '>' . NWLINE;
		foreach ( $this->getElements() as $ele ) {
			if ( !$ele->isHidden() ) {
				$ret .= '<strong>' . $ele->getCaption() . '</strong><br />' . $ele->render() . '<br />' . NWLINE;
			} else {
				$ret .= $ele->render() . NWLINE;
			}
		}
		$ret .= '</form>' . NWLINE;
		return $ret;
	}
}

?>