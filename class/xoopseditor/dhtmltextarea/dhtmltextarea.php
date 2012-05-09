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
 * XOOPS dhtmltextarea class
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package class
 * @subpackage editor
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * FormDhtmlTextArea
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2009
 * @version $Id$
 * @access public
 */
class FormDhtmlTextArea extends XoopsEditor {
	/**
	 * Hidden text
	 *
	 * @var string
	 * @access private
	 */
	private $_hiddenText = 'xoopsHiddenText';

	/**
	 * FormDhtmlTextArea::__construct()
	 *
	 * @param array $options
	 */
	public function __construct( $options = array() )
	{
		parent::__construct( $options );
		$this->rootPath = '/class/xoopseditor/' . basename( dirname( __FILE__ ) );
		$hiddenText = isset( $this->configs['hiddenText'] ) ? $this->configs['hiddenText'] : $this->_hiddenText;
		$this->renderer = new XoopsFormDhtmlTextArea( '', $this->getName(), $this->getValue(), $this->getRows(), $this->getCols(), $hiddenText, $this->configs );
	}

	/**
	 * FormDhtmlTextArea::render()
	 *
	 * @return
	 */
	public function render()
	{
		return $this->renderer->render();
	}
}

?>