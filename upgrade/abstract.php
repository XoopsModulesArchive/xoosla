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
 * Upgrade interface class
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.3.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id: index.php 1369 2008-03-04 02:53:55Z phppp $
 */


class xoopsUpgrade
{
    var $usedFiles = array( );
    var $tasks = array( );
    
    function isApplied()
    {
        return true;
    }

    function apply()
    {
        return true;
    }
    
    function loadLanguage($dirname)
    {
        global $xoopsConfig;
        
        if ( file_exists("./{$dirname}/language/{$xoopsConfig['language']}.php") ) {
            include_once "./{$dirname}/language/{$xoopsConfig['language']}.php";
        } elseif ( file_exists("./{$dirname}/language/english.php") ) {
            include_once "./{$dirname}/language/english.php";
        }
    }
}

?>
