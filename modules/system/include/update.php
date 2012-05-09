<?php
/**
 * Xoosla
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package update.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version update.php 26 2012-02-17 09:16:15Z catzwolf $Id:
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * xoops_module_system_update()
 *
 * @param mixed $module
 * @return
 */
function xoops_module_system_update( XoopsModule &$module )
{
	if ( $module->getVar( 'version' ) == 100 ) {
		$result = $xoopsDB->query( 'SELECT t1.tpl_id FROM ' . $xoopsDB->prefix( "tplfile" ) . ' t1, ' . $xoopsDB->prefix( 'tplfile' ) . ' t2 WHERE t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_id > t2.tpl_id' );

		$tplids = array();
		while ( list( $tplid ) = $xoopsDB->fetchRow( $result ) ) {
			$tplids[] = $tplid;
		}
		if ( count( $tplids ) > 0 ) {
			$tplfile_handler = xoops_gethandler( 'tplfile' );
			$duplicate_files = $tplfile_handler->getObjects( new Criteria( 'tpl_id', '(' . implode( ',', $tplids ) . ')', 'IN' ) );

			if ( count( $duplicate_files ) > 0 ) {
				foreach ( array_keys( $duplicate_files ) as $i ) {
					$tplfile_handler->delete( $duplicate_files[$i] );
				}
			}
		}
	}
	return true;
}

?>