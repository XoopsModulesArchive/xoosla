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
 * TinyMCE adapter for XOOPS
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @subpackage editor
 * @since 2.3.0
 * @author Laurent JEN <dugris@frxoops.org>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * check categories readability by group
 */
$groups = is_object( $GLOBALS['xoopsUser'] ) ? $GLOBALS['xoopsUser']->getGroups() : array( XOOPS_GROUP_ANONYMOUS );
$imgcat_handler = xoops_gethandler( 'imagecategory' );
if ( count( $imgcat_handler->getList( $groups, 'imgcat_read', 1 ) ) == 0 ) {
	return false;
}
return true;

?>