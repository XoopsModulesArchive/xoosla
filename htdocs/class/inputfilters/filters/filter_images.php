<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

/**
 * XOOPS methods for input filter handling
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: about.php 1531 2008-05-01 09:25:50Z catzwolf $
 * @subpackage xoopsInputFilter
 */
class FilterImages {
    /**
     * Constructor
     *
     * @access protected
     */
    function render( &$array )
    {
        /**
         * This removes any malious Images contained within image elements etc
         */
        $array->search[] = '#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si';
        $array->replace[] = '';
        return $array;
    }
}

?>