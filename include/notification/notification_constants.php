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
 * XOOPS Notifications
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @subpackage Xoop Notifications Defines
 * @since 2.0.0
 * @author Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version $Id: notification_constants.php 26 2012-02-17 09:16:15Z catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

define( 'XOOPS_NOTIFICATION_MODE_SENDALWAYS', 0 );
define( 'XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE', 1 );
define( 'XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT', 2 );
define( 'XOOPS_NOTIFICATION_MODE_WAITFORLOGIN', 3 );

define( 'XOOPS_NOTIFICATION_METHOD_DISABLE', 0 );
define( 'XOOPS_NOTIFICATION_METHOD_PM', 1 );
define( 'XOOPS_NOTIFICATION_METHOD_EMAIL', 2 );

define( 'XOOPS_NOTIFICATION_DISABLE', 0 );
define( 'XOOPS_NOTIFICATION_ENABLEBLOCK', 1 );
define( 'XOOPS_NOTIFICATION_ENABLEINLINE', 2 );
define( 'XOOPS_NOTIFICATION_ENABLEBOTH', 3 );

?>