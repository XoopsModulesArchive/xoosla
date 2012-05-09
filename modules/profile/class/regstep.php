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
 * Extended User Profile
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package profile
 * @since 2.3.0
 * @author Jan Pedersen
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * ProfileRegstep
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class ProfileRegstep extends XoopsObject {
	/**
	 * ProfileRegstep::__construct()
	 */
	public function __construct()
	{
		$this->initVar( 'step_id', XOBJ_DTYPE_INT );
		$this->initVar( 'step_name', XOBJ_DTYPE_TXTBOX );
		$this->initVar( 'step_desc', XOBJ_DTYPE_TXTAREA );
		$this->initVar( 'step_order', XOBJ_DTYPE_INT, 1 );
		$this->initVar( 'step_save', XOBJ_DTYPE_INT, 0 );
	}
}

/**
 * ProfileRegstepHandler
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class ProfileRegstepHandler extends XoopsPersistableObjectHandler {
	/**
	 * ProfileRegstepHandler::__construct()
	 *
	 * @param mixed $db
	 */
	public function __construct( XoopsDatabase &$db )
	{
		parent::__construct( $db, 'profile_regstep', 'profileregstep', 'step_id', 'step_name' );
	}

	/**
	 * Delete an object from the database
	 *
	 * @see XoopsPersistableObjectHandler
	 * @param profileRegstep $obj
	 * @param bool $force
	 * @return bool
	 */
	public function delete( ProfileRegstep &$obj, $force = false )
	{
		if ( parent::delete( $obj, $force ) ) {
			$field_handler = xoops_getmodulehandler( 'field' );
			return $field_handler->updateAll( 'step_id', 0, new Criteria( 'step_id', $obj->getVar( 'step_id' ) ) );
		}
		return false;
	}
}

?>