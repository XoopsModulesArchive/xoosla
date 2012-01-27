<?php
/**
 * TextSanitizer extension
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Wishcraft <simon@xoops.org>
 * @version         $Id: ul.php 8066 2011-11-06 05:09:33Z beckmi $
 * @deprecated
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MytsUl extends MyTextSanitizerExtension
{
    function load(&$ts)
    {
        $ts->patterns[] = "/\[ul](.*)\[\/ul\]/sU";
        $ts->replacements[] = '<ul>\\1</ul>';
        return true;
    }
}

?>