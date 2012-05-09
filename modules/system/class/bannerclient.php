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
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author Gregory Mage (AKA Mage)
 * @package system
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'XOOPS root path not defined' );

/**
 * System Banner Client
 *
 * @copyright copyright (c) 2000 XOOPS.org
 * @package system
 */
class SystemBannerclient extends XoopsObject {
	/**
	 * SystemBannerclient::__construct()
	 */
	public function __construct()
	{
		$this->initVar( 'cid', XOBJ_DTYPE_INT, null, false, 5 );
		$this->initVar( 'name', XOBJ_DTYPE_TXTBOX, null, false );
		$this->initVar( 'contact', XOBJ_DTYPE_TXTBOX, null, false );
		$this->initVar( 'email', XOBJ_DTYPE_TXTBOX, null, false );
		$this->initVar( 'login', XOBJ_DTYPE_TXTBOX, null, false );
		$this->initVar( 'passwd', XOBJ_DTYPE_TXTBOX, null, false );
		$this->initVar( 'extrainfo', XOBJ_DTYPE_TXTAREA, null, false );
		// For allow HTML
		// $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
	}

	/**
	 * SystemBannerclient::getForm()
	 *
	 * @param mixed $action
	 * @return
	 */
	public function getForm( $action = false )
	{
		if ( $action === false ) {
			$action = $_SERVER['REQUEST_URI'];
		}

		$title = $this->isNew() ? sprintf( _AM_SYSTEM_BANNERS_ADDNWCLI ) : sprintf( _AM_SYSTEM_BANNERS_EDITADVCLI );
		// xoops_load( 'XoopsFormLoader' );
		$form = new XoopsThemeForm( $title, 'form', $action, 'post', true );
		$form->addElement( new XoopsFormText( _AM_SYSTEM_BANNERS_CLINAMET, 'name', 50, 255, $this->getVar( 'name' ) ), true );
		$form->addElement( new XoopsFormText( _AM_SYSTEM_BANNERS_CONTNAMET, 'contact', 50, 255, $this->getVar( 'contact' ) ) );
		$form->addElement( new XoopsFormText( _AM_SYSTEM_BANNERS_CONTMAILT, 'email', 50, 255, $this->getVar( 'email' ) ) );
		$form->addElement( new XoopsFormText( _AM_SYSTEM_BANNERS_CLILOGINT, 'login', 50, 255, $this->getVar( 'login' ) ) );
		$form->addElement( new XoopsFormText( _AM_SYSTEM_BANNERS_CLIPASST, 'passwd', 50, 255, $this->getVar( 'passwd' ) ) );
		$form->addElement( new xoopsFormTextArea( _AM_SYSTEM_BANNERS_EXTINFO, 'extrainfo', $this->getVar( 'extrainfo' ), 5, 50 ), false );
		$form->addElement( new XoopsFormHidden( 'op', 'banner_client_save' ) );
		$form->addElement( new XoopsFormButton( '', 'submit', _SUBMIT, 'submit' ) );
		return $form;
	}
}

/**
 * System banner client handler class. (Singelton)
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS block class objects.
 *
 * @copyright copyright (c) 2000 XOOPS.org
 * @package system
 * @subpackage banner
 */
class SystemBannerclientHandler extends XoopsPersistableObjectHandler {
	/**
	 * SystemBannerclientHandler::__construct()
	 *
	 * @param mixed $db
	 */
	public function __construct( $db )
	{
		parent::__construct( $db, 'bannerclient', 'SystemBannerclient', 'cid', 'name' );
	}
}

?>