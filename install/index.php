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
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package install
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version $Id:
 */
if ( version_compare( PHP_VERSION, '5.2.4', '<' ) ) {
	die( 'Your host needs to use PHP 5.2.4 or higher to run Joomla 1.7.' );
}
require_once './include/common.inc.php';

/**
 * Cheap way to get through each page
 */
$page = ( isset( $_REQUEST['p'] ) && !empty( $_REQUEST['p'] ) ) ? strip_tags( $_REQUEST['p'] ) : 'page1';
$pageHasForm = true;

$vars = $_SESSION['settings'];
$errors = array();
switch ( $page ) {
	case 'page1':
	default:
		xoKillSession();
		require_once 'page1.php';
		break;
	case 'page2':
		$pageHasForm = false;
		require_once 'page2.php';
		break;
	case 'page3':
		require_once 'page3.php';
		break;
	case 'page4':
		require_once 'page4.php';
		break;
	case 'page5':
		require_once 'page5.php';
		break;
	case 'page6':
		xoKillSession();
		$pageHasForm = false;
		require_once 'page6.php';
		break;
} // switch
exit();

?>