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
 * user/member handlers
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           1.00
 * @version         $Id$
 * @package         Frameworks
 * @subpackage      art
 */
if (!defined("FRAMEWORKS_ART_FUNCTIONS_USER")):
define("FRAMEWORKS_ART_FUNCTIONS_USER", true);

//xoops_load('XoopsUserUtility');

function mod_getIP($asString = false)
{
    $GLOBALS['xoopsLogger']->addDeprecated("Deprecated function '" . __FUNCTION__ . "', use XoopsUserUtility directly.");
    return XoopsUserUtility::getIP($asString);
}

function &mod_getUnameFromIds( $uid, $usereal = false, $linked = false )
{
    $GLOBALS['xoopsLogger']->addDeprecated("Deprecated function '" . __FUNCTION__ . "', use XoopsUserUtility directly.");
    $ids = XoopsUserUtility::getUnameFromIds($uid, $usereal, $linked);
    return $ids;
}

function mod_getUnameFromId( $uid, $usereal = 0, $linked = false)
{
    $GLOBALS['xoopsLogger']->addDeprecated("Deprecated function '" . __FUNCTION__ . "', user XoopsUserUtility directly.");
    return XoopsUserUtility::getUnameFromId($uid, $usereal, $linked);
}

endif;
?>