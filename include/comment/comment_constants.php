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
 * XOOPS constants
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @since 2.0.0
 * @author Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version $Id: comment_constants.php 26 2012-02-17 09:16:15Z catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * Comment Constants
 */
define( 'XOOPS_COMMENT_APPROVENONE', 0 );
define( 'XOOPS_COMMENT_APPROVEALL', 1 );
define( 'XOOPS_COMMENT_APPROVEUSER', 2 );
define( 'XOOPS_COMMENT_APPROVEADMIN', 3 );
define( 'XOOPS_COMMENT_PENDING', 1 );
define( 'XOOPS_COMMENT_ACTIVE', 2 );
define( 'XOOPS_COMMENT_HIDDEN', 3 );
define( 'XOOPS_COMMENT_OLD1ST', 0 );
define( 'XOOPS_COMMENT_NEW1ST', 1 );

?>