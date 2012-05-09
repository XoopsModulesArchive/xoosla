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
 * XOOPS control panel functions
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @since 2.0.0
 * @version $Id$
 */
    //define( 'XOOPS_CPFUNC_LOADED', 1 );

/**
 * CP Header
 */
function xoops_cp_header()
{
    $cpanel = XoopsSystemCpanel::getInstance();
    $cpanel->gui->header();
}

/**
 * CP Footer
 */
function xoops_cp_footer()
{
    global $xoopsConfig;

    $cpanel = XoopsSystemCpanel::getInstance();
    $cpanel->gui->footer();
}

/**
 * Enter description here...
 *
 * @return unknown
 */
function xoopsfwrite()
{
    if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
        return false;
    } else {
    }
    if ( ! $GLOBALS['xoopsSecurity']->checkReferer() ) {
        return false;
    } else {
    }
    return true;
}

?>