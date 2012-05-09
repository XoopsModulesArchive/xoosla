<?php
/**
 * Abstract base class for XOOPS Database access classes
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright The XOOPS project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @subpackage database
 * @since 1.0.0
 * @author Kazumi Ono <onokazu@xoops.org>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * Abstract base class for Database access classes
 *
 * @abstract
 * @author Kazumi Ono <onokazu@xoops.org>
 * @package kernel
 * @subpackage database
 */
abstract class XoopsDatabase {
	/**
	 * Prefix for tables in the database
	 *
	 * @var string
	 */
	public $prefix = '';

	/**
	 * reference to a {@link XoopsLogger} object
	 *
	 * @see XoopsLogger
	 * @var object XoopsLogger
	 */
	public $logger;

	/**
	 * If statements that modify the database are selected
	 *
	 * @var boolean
	 */
	public $allowWebChanges = false;

	/**
	 * assign a {@link XoopsLogger} object to the database
	 *
	 * @see XoopsLogger
	 * @param object $logger reference to a {@link XoopsLogger} object
	 */

	public function setLogger( $logger )
	{
		$this->logger = $logger;
	}

	/**
	 * set the prefix for tables in the database
	 *
	 * @param string $value table prefix
	 */
	public function setPrefix( $value )
	{
		$this->prefix = $value;
	}

	/**
	 * attach the prefix.'_' to a given tablename
	 *
	 * if tablename is empty, only prefix will be returned
	 *
	 * @param string $tablename tablename
	 * @return string prefixed tablename, just prefix if tablename is empty
	 */
	public function prefix( $tablename = '' )
	{
		if ( $tablename != '' ) {
			return $this->prefix . '_' . $tablename;
		} else {
			return $this->prefix;
		}
	}
}

?>